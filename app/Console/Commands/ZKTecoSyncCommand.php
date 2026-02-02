<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ZKTecoService;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ZKTecoSyncCommand extends Command
{
    protected $signature = 'zkteco:sync {--clear : Clear device records after sync}';
    protected $description = 'Sync attendance records from ZKTeco device';

    public function handle()
    {
        $this->info('Starting ZKTeco attendance sync...');
        
        try {
            $zkService = new ZKTecoService(
                config('zkteco.device_ip'),
                config('zkteco.device_port')
            );
            
            // Get attendance records
            $records = $zkService->getAttendanceRecords();
            
            if ($records === false) {
                $this->error('Failed to connect to ZKTeco device');
                return 1;
            }
            
            if (empty($records)) {
                $this->info('No attendance records found on device');
                return 0;
            }
            
            $syncedCount = 0;
            $errors = [];
            
            $this->info('Processing ' . count($records) . ' records...');
            
            foreach ($records as $record) {
                try {
                    // Find student
                    $student = Student::where('device_user_id', $record['user_id'])
                                    ->orWhere('student_id', $record['user_id'])
                                    ->orWhere('roll', $record['user_id'])
                                    ->first();
                    
                    if (!$student) {
                        $errors[] = "Student not found for User ID: {$record['user_id']}";
                        continue;
                    }
                    
                    $attendanceDate = Carbon::parse($record['timestamp'])->format('Y-m-d');
                    
                    // Check if attendance already exists
                    $exists = StudentAttendance::where('student_id', $student->id)
                                              ->where('attendance_date', $attendanceDate)
                                              ->exists();
                    
                    if (!$exists) {
                        StudentAttendance::create([
                            'student_id' => $student->id,
                            'attendance_date' => $attendanceDate,
                            'status' => 'present',
                            'class' => $student->class,
                            'section' => $student->section,
                            'academic_year' => date('Y'),
                            'marked_by' => 'ZKTeco Auto Sync',
                            'check_in_time' => Carbon::parse($record['timestamp'])->format('H:i:s'),
                            'device_user_id' => $record['user_id'],
                            'verify_type' => $record['verify_type'],
                            'in_out_mode' => $record['in_out_mode'],
                            'device_timestamp' => $record['timestamp']
                        ]);
                        
                        $syncedCount++;
                        $this->line("✓ Synced: {$student->name_en} ({$attendanceDate})");
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error processing record: " . $e->getMessage();
                }
            }
            
            // Clear device records if requested
            if ($this->option('clear') || config('zkteco.clear_device_after_sync')) {
                $this->info('Clearing device records...');
                $cleared = $zkService->clearAttendanceRecords();
                if ($cleared) {
                    $this->info('✓ Device records cleared');
                } else {
                    $this->warn('Failed to clear device records');
                }
            }
            
            $this->info("Sync completed: {$syncedCount} records synced");
            
            if (!empty($errors)) {
                $this->warn('Errors encountered:');
                foreach ($errors as $error) {
                    $this->error("  - {$error}");
                }
            }
            
            Log::info('ZKTeco sync completed', [
                'synced_count' => $syncedCount,
                'total_records' => count($records),
                'errors_count' => count($errors)
            ]);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());
            Log::error('ZKTeco sync failed: ' . $e->getMessage());
            return 1;
        }
    }
}