# ZKTime.Net Bidirectional Integration System

## 🎯 Complete Two-Way Data Sync Solution

### Problem Statement:
- **Laravel → ZKTime.Net**: New students from website should auto-sync to device
- **ZKTime.Net → Laravel**: Attendance data should sync back to website
- **Challenge**: ZKTime.Net SQLite database is local, no direct web access

## 📋 Solution Architecture

### 1. Laravel Side: Device Commands Queue

```sql
-- Migration: Device Commands Queue
CREATE TABLE device_commands (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    command_type ENUM('add_user', 'update_user', 'delete_user', 'sync_users'),
    student_id BIGINT,
    personnel_id VARCHAR(50),
    data JSON,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    attempts INT DEFAULT 0,
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    processed_at TIMESTAMP NULL
);
```

### 2. Laravel Controller: Student Management with Device Sync

```php
<?php
// app/Http/Controllers/Tenant/StudentDeviceController.php

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
                'status' => 'pending'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student queued for device sync'
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
                        'status' => 'pending'
                    ]);
                    $queuedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "$queuedCount students queued for device sync"
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
    
    public function markCommandProcessed($commandId, $status = 'completed', $errorMessage = null)
    {
        DB::table('device_commands')
            ->where('id', $commandId)
            ->update([
                'status' => $status,
                'error_message' => $errorMessage,
                'processed_at' => now(),
                'attempts' => DB::raw('attempts + 1')
            ]);
        
        return response()->json(['success' => true]);
    }
}
```

### 3. Enhanced Bridge Script: Bidirectional Sync

```php
<?php
// zktime_bidirectional_bridge.php

require_once 'vendor/autoload.php';

class ZKTimeBidirectionalBridge 
{
    private $sqliteDb;
    private $mysqlDb;
    private $laravelApiUrl;
    
    public function __construct() 
    {
        // ZKTime.Net SQLite database
        $sqlitePath = 'C:\Program Files (x86)\ZKBio Time.Net\TimeNet\att2000.db';
        $tempPath = sys_get_temp_dir() . '\att2000_temp.db';
        copy($sqlitePath, $tempPath);
        
        $this->sqliteDb = new PDO("sqlite:$tempPath");
        
        // Laravel MySQL connection
        $this->mysqlDb = new PDO(
            'mysql:host=localhost;dbname=smartpathshala',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Laravel API URL
        $this->laravelApiUrl = 'http://localhost:8000/api';
    }
    
    public function runBidirectionalSync() 
    {
        echo "=== ZKTime.Net Bidirectional Sync Started ===\n";
        
        // Step 1: Pull commands from Laravel (Website → Device)
        $this->pullCommandsFromLaravel();
        
        // Step 2: Push attendance to Laravel (Device → Website)
        $this->pushAttendanceToLaravel();
        
        echo "=== Sync Completed ===\n";
    }
    
    private function pullCommandsFromLaravel() 
    {
        try {
            echo "Pulling commands from Laravel...\n";
            
            // Get pending commands from Laravel
            $response = file_get_contents($this->laravelApiUrl . '/device-commands');
            $data = json_decode($response, true);
            
            if ($data['success'] && !empty($data['commands'])) {
                foreach ($data['commands'] as $command) {
                    $this->processCommand($command);
                }
            }
            
        } catch (Exception $e) {
            echo "Error pulling commands: " . $e->getMessage() . "\n";
        }
    }
    
    private function processCommand($command) 
    {
        try {
            $commandData = json_decode($command['data'], true);
            
            switch ($command['command_type']) {
                case 'add_user':
                    $this->addUserToSQLite($commandData);
                    break;
                case 'update_user':
                    $this->updateUserInSQLite($commandData);
                    break;
                case 'delete_user':
                    $this->deleteUserFromSQLite($commandData);
                    break;
            }
            
            // Mark command as completed
            $this->markCommandCompleted($command['id']);
            echo "Processed command: {$command['command_type']} for {$commandData['emp_firstname']}\n";
            
        } catch (Exception $e) {
            $this->markCommandFailed($command['id'], $e->getMessage());
            echo "Failed to process command {$command['id']}: " . $e->getMessage() . "\n";
        }
    }
    
    private function addUserToSQLite($userData) 
    {
        // Insert into hr_employee table
        $stmt = $this->sqliteDb->prepare("
            INSERT OR REPLACE INTO hr_employee 
            (emp_pin, emp_firstname, emp_lastname, emp_cardNumber, department_id, emp_active) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userData['emp_pin'],
            $userData['emp_firstname'],
            $userData['emp_lastname'] ?? '',
            $userData['emp_cardNumber'] ?? null,
            $userData['department_id'] ?? 1,
            $userData['emp_active'] ?? 1
        ]);
        
        // Also insert into hr_biotemplate for fingerprint enrollment
        $biotemplateStmt = $this->sqliteDb->prepare("
            INSERT OR IGNORE INTO hr_biotemplate 
            (emp_id, template_type, template_data, template_size) 
            VALUES (?, 1, '', 0)
        ");
        $biotemplateStmt->execute([$userData['emp_pin']]);
    }
    
    private function updateUserInSQLite($userData) 
    {
        $stmt = $this->sqliteDb->prepare("
            UPDATE hr_employee 
            SET emp_firstname = ?, emp_lastname = ?, emp_cardNumber = ?, department_id = ? 
            WHERE emp_pin = ?
        ");
        
        $stmt->execute([
            $userData['emp_firstname'],
            $userData['emp_lastname'] ?? '',
            $userData['emp_cardNumber'] ?? null,
            $userData['department_id'] ?? 1,
            $userData['emp_pin']
        ]);
    }
    
    private function deleteUserFromSQLite($userData) 
    {
        // Soft delete - mark as inactive
        $stmt = $this->sqliteDb->prepare("
            UPDATE hr_employee SET emp_active = 0 WHERE emp_pin = ?
        ");
        $stmt->execute([$userData['emp_pin']]);
    }
    
    private function pushAttendanceToLaravel() 
    {
        try {
            echo "Pushing attendance to Laravel...\n";
            
            // Get recent attendance records
            $stmt = $this->sqliteDb->prepare("
                SELECT personnel_id, punch_time, punch_state, verify_type, work_code 
                FROM att_punches 
                WHERE punch_time > datetime('now', '-1 day')
                ORDER BY punch_time DESC
            ");
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($records)) {
                // Send to Laravel API
                $postData = json_encode(['attendance_records' => $records]);
                
                $context = stream_context_create([
                    'http' => [
                        'method' => 'POST',
                        'header' => 'Content-Type: application/json',
                        'content' => $postData
                    ]
                ]);
                
                $response = file_get_contents($this->laravelApiUrl . '/sync-attendance', false, $context);
                $result = json_decode($response, true);
                
                if ($result['success']) {
                    echo "Synced " . count($records) . " attendance records\n";
                }
            }
            
        } catch (Exception $e) {
            echo "Error pushing attendance: " . $e->getMessage() . "\n";
        }
    }
    
    private function markCommandCompleted($commandId) 
    {
        $postData = json_encode(['status' => 'completed']);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => $postData
            ]
        ]);
        
        file_get_contents($this->laravelApiUrl . "/device-commands/$commandId/status", false, $context);
    }
    
    private function markCommandFailed($commandId, $errorMessage) 
    {
        $postData = json_encode(['status' => 'failed', 'error_message' => $errorMessage]);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => $postData
            ]
        ]);
        
        file_get_contents($this->laravelApiUrl . "/device-commands/$commandId/status", false, $context);
    }
}

// Run the bidirectional sync
$bridge = new ZKTimeBidirectionalBridge();
$bridge->runBidirectionalSync();
?>
```

### 4. Laravel API Routes

```php
<?php
// routes/api.php

Route::prefix('device')->group(function () {
    Route::get('/commands', [StudentDeviceController::class, 'getDeviceCommands']);
    Route::post('/commands/{id}/status', [StudentDeviceController::class, 'markCommandProcessed']);
    Route::post('/sync-attendance', [ZKTimeNetController::class, 'syncAttendance']);
});

Route::prefix('students')->group(function () {
    Route::post('/{id}/sync-to-device', [StudentDeviceController::class, 'syncStudentToDevice']);
    Route::post('/bulk-sync-to-device', [StudentDeviceController::class, 'bulkSyncStudents']);
});
```

### 5. Student Management Integration

```blade
{{-- resources/views/tenant/students/index.blade.php --}}

<!-- Add Device Sync Buttons -->
<div class="mb-4 flex gap-4">
    <button onclick="bulkSyncToDevice()" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        <i class="fas fa-sync mr-2"></i>সকল ছাত্র Device এ Sync করুন
    </button>
    <button onclick="checkSyncStatus()" 
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
        <i class="fas fa-check mr-2"></i>Sync Status দেখুন
    </button>
</div>

<!-- Individual Student Sync -->
@foreach($students as $student)
<tr>
    <td>{{ $student->name_bn }}</td>
    <td>{{ $student->roll }}</td>
    <td>{{ $student->class }}</td>
    <td>
        <button onclick="syncStudentToDevice({{ $student->id }})" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-sm">
            Device এ Sync করুন
        </button>
    </td>
</tr>
@endforeach

<script>
function syncStudentToDevice(studentId) {
    fetch(`/api/students/${studentId}/sync-to-device`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        });
}

function bulkSyncToDevice() {
    if (confirm('সকল ছাত্রকে Device এ sync করবেন?')) {
        fetch('/api/students/bulk-sync-to-device', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            });
    }
}
</script>
```

### 6. Automated Sync Setup

```batch
REM zktime_sync_scheduler.bat
@echo off
:loop
echo Running ZKTime.Net Bidirectional Sync...
php zktime_bidirectional_bridge.php
echo Waiting 2 minutes...
timeout /t 120 /nobreak
goto loop
```

## 🎯 Complete Workflow:

### Website → Device:
1. **Student Added**: Laravel creates device command
2. **Bridge Script**: Pulls commands every 2 minutes
3. **SQLite Update**: Adds user to hr_employee table
4. **ZKTime.Net**: Shows new user (requires manual "Upload to Device")

### Device → Website:
1. **Attendance Taken**: Student uses fingerprint
2. **ZKTime.Net**: Stores in SQLite att_punches table
3. **Bridge Script**: Reads attendance data
4. **Laravel Update**: Creates attendance records

## 🚨 Important Notes:

### Manual Steps Required:
1. **After SQLite Update**: Open ZKTime.Net → Upload Users to Device
2. **Fingerprint Enrollment**: Students must enroll fingerprints manually
3. **Initial Setup**: Configure ZKTime.Net device connection

### Automation Possibilities:
- **ZK SDK Integration**: For fully automated device updates
- **Scheduled Tasks**: Windows Task Scheduler for bridge script
- **Real-time Notifications**: SMS/Email when sync completes

This system gives you **complete bidirectional integration** while working within ZKTime.Net's limitations!