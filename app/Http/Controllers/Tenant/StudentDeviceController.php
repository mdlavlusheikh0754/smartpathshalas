<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentDeviceController extends Controller
{
    public function syncStudentToDevice($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            
            // Check if already queued
            $exists = DB::table('device_commands')
                ->where('student_id', $student->id)
                ->where('status', 'pending')
                ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'ছাত্র ইতিমধ্যে sync queue এ আছে'
                ]);
            }
            
            // Create device command
            DB::table('device_commands')->insert([
                'command_type' => 'add_user',
                'student_id' => $student->id,
                'personnel_id' => $student->roll ?: $student->student_id,
                'data' => json_encode([
                    'emp_pin' => $student->roll ?: $student->student_id,
                    'emp_firstname' => $student->name_en ?: $student->name_bn,
                    'emp_lastname' => '',
                    'emp_cardNumber' => $student->card_number,
                    'department_id' => $student->class,
                    'emp_active' => 1
                ]),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'ছাত্র device sync এর জন্য queue করা হয়েছে'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public function bulkSyncStudents()
    {
        try {
            $students = Student::where('status', 'active')->get();
            $queuedCount = 0;
            
            foreach ($students as $student) {
                // Check if already queued
                $exists = DB::table('device_commands')
                    ->where('student_id', $student->id)
                    ->where('status', 'pending')
                    ->exists();
                
                if (!$exists) {
                    DB::table('device_commands')->insert([
                        'command_type' => 'add_user',
                        'student_id' => $student->id,
                        'personnel_id' => $student->roll ?: $student->student_id,
                        'data' => json_encode([
                            'emp_pin' => $student->roll ?: $student->student_id,
                            'emp_firstname' => $student->name_en ?: $student->name_bn,
                            'emp_lastname' => '',
                            'emp_cardNumber' => $student->card_number,
                            'department_id' => $student->class,
                            'emp_active' => 1
                        ]),
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $queuedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "$queuedCount জন ছাত্র device sync এর জন্য queue করা হয়েছে"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getDeviceCommands()
    {
        $commands = DB::table('device_commands')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit(50)
            ->get();
        
        return response()->json([
            'success' => true,
            'commands' => $commands
        ]);
    }
    
    public function markCommandProcessed($commandId, Request $request)
    {
        $status = $request->input('status', 'completed');
        $errorMessage = $request->input('error_message');
        
        DB::table('device_commands')
            ->where('id', $commandId)
            ->update([
                'status' => $status,
                'error_message' => $errorMessage,
                'processed_at' => now(),
                'attempts' => DB::raw('attempts + 1'),
                'updated_at' => now()
            ]);
        
        return response()->json(['success' => true]);
    }
    
    public function getSyncStatus()
    {
        $stats = [
            'pending' => DB::table('device_commands')->where('status', 'pending')->count(),
            'completed' => DB::table('device_commands')->where('status', 'completed')->count(),
            'failed' => DB::table('device_commands')->where('status', 'failed')->count(),
            'total' => DB::table('device_commands')->count()
        ];
        
        $recentCommands = DB::table('device_commands')
            ->join('students', 'device_commands.student_id', '=', 'students.id')
            ->select([
                'device_commands.*',
                'students.name_bn',
                'students.roll',
                'students.class'
            ])
            ->orderBy('device_commands.created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
            'recent_commands' => $recentCommands
        ]);
    }
}