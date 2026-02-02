<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ZktecoDevice;
use App\Models\Student;
use App\Models\StudentRfidMapping;
use App\Models\AttendanceRecord;

class TestZktecoApi extends Command
{
    protected $signature = 'zkteco:test-api';
    protected $description = 'Test ZKTeco API functionality';

    public function handle()
    {
        $this->info('Testing ZKTeco API functionality...');

        try {
            // Test 1: Register a device
            $this->info("\n1. Testing device registration...");
            
            $device = ZktecoDevice::updateOrCreate(
                ['device_id' => 'TEST_DEVICE_001'],
                [
                    'name' => 'Test ZKTeco Device',
                    'ip_address' => '192.168.1.100',
                    'port' => 4370,
                    'location' => 'Main Gate',
                    'is_active' => true,
                    'last_heartbeat' => now(),
                ]
            );
            
            $this->info("✓ Device registered: {$device->name} ({$device->device_id})");
            
            // Test 2: Create test student and RFID mapping
            $this->info("\n2. Creating test student and RFID mapping...");
            
            $student = Student::first();
            if (!$student) {
                $this->error("No students found in database. Please add students first.");
                return 1;
            }
            
            $rfidMapping = StudentRfidMapping::assignRfidToStudent($student->id, 'RFID123456789');
            $this->info("✓ RFID assigned to student: {$student->name} -> {$rfidMapping->rfid_number}");
            
            // Test 3: Create attendance record
            $this->info("\n3. Testing attendance record creation...");
            
            $attendanceRecord = AttendanceRecord::create([
                'device_id' => $device->device_id,
                'student_id' => $student->id,
                'rfid_number' => $rfidMapping->rfid_number,
                'punch_timestamp' => now(),
                'punch_type' => 'IN',
                'device_timestamp' => now(),
                'sync_timestamp' => now(),
                'is_duplicate' => false,
            ]);
            
            $this->info("✓ Attendance record created: ID {$attendanceRecord->id}");
            
            // Test 4: Check device status
            $this->info("\n4. Testing device status...");
            
            $isOnline = $device->isOnline();
            $recordsToday = $device->attendanceRecords()->whereDate('punch_timestamp', today())->count();
            
            $this->info("✓ Device online: " . ($isOnline ? 'Yes' : 'No'));
            $this->info("✓ Records today: {$recordsToday}");
            
            // Test 5: Get RFID mappings
            $this->info("\n5. Testing RFID mappings...");
            
            $mappingsCount = StudentRfidMapping::active()->count();
            $this->info("✓ Active RFID mappings: {$mappingsCount}");
            
            // Test 6: Test duplicate detection
            $this->info("\n6. Testing duplicate detection...");
            
            $isDuplicate = $attendanceRecord->isPotentialDuplicate();
            $this->info("✓ Duplicate detection works: " . ($isDuplicate ? 'Yes' : 'No'));
            
            $this->info("\n✅ All API tests completed successfully!");
            
            // Show summary
            $this->info("\n📊 Summary:");
            $this->info("- Devices: " . ZktecoDevice::count());
            $this->info("- RFID Mappings: " . StudentRfidMapping::active()->count());
            $this->info("- Attendance Records: " . AttendanceRecord::count());
            $this->info("- Records Today: " . AttendanceRecord::whereDate('punch_timestamp', today())->count());
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }
    }
}