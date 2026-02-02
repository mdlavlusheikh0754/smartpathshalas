# ZKTime.Net 3.3 + Laravel Integration Guide

## 🎯 Overview
ZKTime.Net 3.3 software দিয়ে ZKTeco K50-A device manage করে Laravel system এ attendance data sync করার complete solution।

## 📋 Phase 1: ZKTime.Net 3.3 Setup

### Step 1: Software Installation
1. **Download ZKTime.Net 3.3** from ZKTeco official website
2. Install করুন Windows PC তে
3. Admin privileges দিয়ে run করুন

### Step 2: Device Connection
1. ZKTime.Net software open করুন
2. **Device Management** → **Add Device**
3. Device settings:
   ```
   Device Name: K50A-School
   IP Address: 192.168.1.201
   Port: 4370
   Communication: TCP/IP
   ```
4. **Test Connection** করুন
5. Device successfully connect হলে **Download Users** করুন

### Step 3: Student Enrollment
1. **Personnel Management** → **Add Personnel**
2. Each student এর জন্য:
   ```
   Personnel ID: Student Roll Number
   Name: Student Name
   Department: Class Name
   Card Number: (optional)
   ```
3. **Upload to Device** করুন
4. Device এ manually fingerprint enroll করুন

## 📋 Phase 2: Laravel Integration

### Database Structure
Laravel system এ ZKTime.Net data sync করার জন্য:

```sql
-- ZKTime.Net attendance sync table
CREATE TABLE zkteco_attendance_sync (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    personnel_id VARCHAR(50),
    punch_time DATETIME,
    punch_state INT, -- 0=Check In, 1=Check Out
    verify_type INT, -- 1=Fingerprint, 15=Password
    work_code VARCHAR(10),
    synced_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Data Bridge Script
ZKTime.Net SQLite database থেকে Laravel MySQL এ data sync:

```php
<?php
// zktime_sync_bridge.php

require_once 'vendor/autoload.php';

class ZKTimeNetBridge 
{
    private $sqliteDb;
    private $mysqlDb;
    
    public function __construct() 
    {
        // ZKTime.Net SQLite database path
        $sqlitePath = 'C:\Program Files (x86)\ZKBio Time.Net\TimeNet\att2000.db';
        
        // Copy to temp to avoid file locks
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
    }
    
    public function syncAttendance() 
    {
        try {
            // Get new attendance records from ZKTime.Net
            $stmt = $this->sqliteDb->prepare("
                SELECT personnel_id, punch_time, punch_state, verify_type, work_code 
                FROM att_punches 
                WHERE punch_time > datetime('now', '-1 day')
                ORDER BY punch_time DESC
            ");
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $syncedCount = 0;
            
            foreach ($records as $record) {
                // Check if already synced
                $checkStmt = $this->mysqlDb->prepare("
                    SELECT id FROM zkteco_attendance_sync 
                    WHERE personnel_id = ? AND punch_time = ?
                ");
                $checkStmt->execute([$record['personnel_id'], $record['punch_time']]);
                
                if (!$checkStmt->fetch()) {
                    // Insert new record
                    $insertStmt = $this->mysqlDb->prepare("
                        INSERT INTO zkteco_attendance_sync 
                        (personnel_id, punch_time, punch_state, verify_type, work_code, synced_at) 
                        VALUES (?, ?, ?, ?, ?, NOW())
                    ");
                    $insertStmt->execute([
                        $record['personnel_id'],
                        $record['punch_time'],
                        $record['punch_state'],
                        $record['verify_type'],
                        $record['work_code']
                    ]);
                    
                    // Process attendance in Laravel system
                    $this->processAttendanceRecord($record);
                    $syncedCount++;
                }
            }
            
            echo "Synced $syncedCount new attendance records\n";
            
        } catch (Exception $e) {
            echo "Sync error: " . $e->getMessage() . "\n";
        }
    }
    
    private function processAttendanceRecord($record) 
    {
        // Find student by personnel_id (roll number)
        $stmt = $this->mysqlDb->prepare("
            SELECT id, name, class, section 
            FROM students 
            WHERE roll = ? OR student_id = ?
        ");
        $stmt->execute([$record['personnel_id'], $record['personnel_id']]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student) {
            $attendanceDate = date('Y-m-d', strtotime($record['punch_time']));
            
            // Insert/Update student attendance
            $attendanceStmt = $this->mysqlDb->prepare("
                INSERT INTO student_attendances 
                (student_id, attendance_date, status, class, section, academic_year, marked_by, check_in_time) 
                VALUES (?, ?, 'present', ?, ?, ?, 'ZKTime.Net', ?)
                ON DUPLICATE KEY UPDATE 
                check_in_time = VALUES(check_in_time),
                marked_by = 'ZKTime.Net'
            ");
            
            $attendanceStmt->execute([
                $student['id'],
                $attendanceDate,
                $student['class'],
                $student['section'],
                date('Y'),
                date('H:i:s', strtotime($record['punch_time']))
            ]);
            
            echo "Processed attendance for: {$student['name']}\n";
        }
    }
}

// Run sync
$bridge = new ZKTimeNetBridge();
$bridge->syncAttendance();
?>
```

## 📋 Phase 3: Laravel Controller Integration

### ZKTime.Net Sync Controller
```php
<?php
// app/Http/Controllers/Tenant/ZKTimeNetController.php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ZKTimeNetController extends Controller
{
    public function index()
    {
        return view('tenant.attendance.zktime-net.index');
    }
    
    public function syncStatus()
    {
        try {
            // Check last sync time
            $lastSync = DB::table('zkteco_attendance_sync')
                ->orderBy('synced_at', 'desc')
                ->first();
            
            $totalRecords = DB::table('zkteco_attendance_sync')->count();
            $todayRecords = DB::table('zkteco_attendance_sync')
                ->whereDate('punch_time', today())
                ->count();
            
            return response()->json([
                'success' => true,
                'last_sync' => $lastSync ? $lastSync->synced_at : null,
                'total_records' => $totalRecords,
                'today_records' => $todayRecords,
                'message' => 'ZKTime.Net sync status retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving sync status: ' . $e->getMessage()
            ]);
        }
    }
    
    public function manualSync()
    {
        try {
            // Run the bridge script
            $output = shell_exec('php zktime_sync_bridge.php 2>&1');
            
            return response()->json([
                'success' => true,
                'message' => 'Manual sync completed',
                'output' => $output
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Manual sync failed: ' . $e->getMessage()
            ]);
        }
    }
    
    public function attendanceReport(Request $request)
    {
        $date = $request->input('date', today()->format('Y-m-d'));
        
        $attendances = DB::table('student_attendances')
            ->join('students', 'student_attendances.student_id', '=', 'students.id')
            ->where('attendance_date', $date)
            ->where('marked_by', 'ZKTime.Net')
            ->select([
                'students.name',
                'students.roll',
                'students.class',
                'students.section',
                'student_attendances.check_in_time',
                'student_attendances.status'
            ])
            ->orderBy('students.class')
            ->orderBy('students.roll')
            ->get();
        
        return view('tenant.attendance.zktime-net.report', compact('attendances', 'date'));
    }
}
```

## 📋 Phase 4: Automated Sync Setup

### Windows Task Scheduler
1. **Task Scheduler** open করুন
2. **Create Basic Task** → "ZKTime.Net Sync"
3. **Trigger**: Daily, every 5 minutes
4. **Action**: Start a program
   ```
   Program: php
   Arguments: C:\path\to\zktime_sync_bridge.php
   ```

### Laravel Cron Job (Alternative)
```php
// app/Console/Commands/ZKTimeNetSync.php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ZKTimeNetSync extends Command
{
    protected $signature = 'zktime:sync';
    protected $description = 'Sync attendance data from ZKTime.Net';
    
    public function handle()
    {
        $output = shell_exec('php zktime_sync_bridge.php 2>&1');
        $this->info($output);
    }
}

// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('zktime:sync')->everyFiveMinutes();
}
```

## 📋 Phase 5: Web Interface

### ZKTime.Net Dashboard
```blade
{{-- resources/views/tenant/attendance/zktime-net/index.blade.php --}}

@extends('layouts.tenant')

@section('title', 'ZKTime.Net Integration')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ZKTime.Net Integration</h1>
            <p class="text-gray-600 mt-1">ZKTeco K50-A device attendance management</p>
        </div>
    </div>

    <!-- Sync Status Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Sync Status</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="sync-status">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600" id="total-records">-</div>
                <div class="text-gray-600">Total Records</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600" id="today-records">-</div>
                <div class="text-gray-600">Today's Records</div>
            </div>
            <div class="text-center">
                <div class="text-sm text-gray-600" id="last-sync">-</div>
                <div class="text-gray-600">Last Sync</div>
            </div>
        </div>
        
        <div class="mt-6 flex gap-4">
            <button onclick="checkSyncStatus()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Check Status
            </button>
            <button onclick="manualSync()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Manual Sync
            </button>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Today's Attendance</h3>
        <div id="attendance-table">
            <!-- Attendance data will be loaded here -->
        </div>
    </div>
</div>

<script>
function checkSyncStatus() {
    fetch('/attendance/zktime-net/status')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-records').textContent = data.total_records;
                document.getElementById('today-records').textContent = data.today_records;
                document.getElementById('last-sync').textContent = data.last_sync || 'Never';
            }
        });
}

function manualSync() {
    fetch('/attendance/zktime-net/manual-sync', { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                checkSyncStatus();
            }
        });
}

// Auto-refresh status every 30 seconds
setInterval(checkSyncStatus, 30000);
checkSyncStatus(); // Initial load
</script>
@endsection
```

## 🎯 Benefits of This Approach:

### ✅ Advantages:
1. **Reliable Device Communication** - ZKTime.Net handles device connectivity
2. **Professional Software** - ZKTeco's official software
3. **Data Backup** - SQLite database backup
4. **Flexible Integration** - Can work with any Laravel system
5. **Real-time Sync** - Automated data synchronization

### 🔧 Setup Steps:
1. Install ZKTime.Net 3.3
2. Configure device connection
3. Setup bridge script
4. Configure automated sync
5. Test integration

## 📞 Next Steps:
1. Download এবং install ZKTime.Net 3.3
2. Device connection test করুন
3. Bridge script setup করুন
4. Laravel integration complete করুন

This hybrid approach will give you the best of both worlds - reliable device management through ZKTime.Net and powerful web-based attendance system through Laravel!