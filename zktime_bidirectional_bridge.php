<?php
/**
 * ZKTime.Net Bidirectional Bridge Script
 * Syncs data between Laravel (MySQL) and ZKTime.Net (SQLite)
 * 
 * Usage: php zktime_bidirectional_bridge.php
 */

require_once 'vendor/autoload.php';

class ZKTimeBidirectionalBridge 
{
    private $sqliteDb;
    private $mysqlDb;
    private $laravelApiUrl;
    private $logFile;
    
    public function __construct() 
    {
        $this->logFile = 'zktime_sync.log';
        $this->log("=== ZKTime.Net Bridge Started ===");
        
        try {
            // ZKTime.Net SQLite database path
            $sqlitePath = 'C:\Program Files (x86)\ZKBio Time.Net\TimeNet\att2000.db';
            
            // Check if SQLite file exists
            if (!file_exists($sqlitePath)) {
                throw new Exception("ZKTime.Net database not found at: $sqlitePath");
            }
            
            // Copy to temp to avoid file locks
            $tempPath = sys_get_temp_dir() . '\att2000_temp_' . time() . '.db';
            if (!copy($sqlitePath, $tempPath)) {
                throw new Exception("Failed to copy SQLite database to temp location");
            }
            
            $this->sqliteDb = new PDO("sqlite:$tempPath");
            $this->sqliteDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->log("SQLite database connected successfully");
            
            // Laravel MySQL connection
            $this->mysqlDb = new PDO(
                'mysql:host=localhost;dbname=smartpathshala',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            $this->log("MySQL database connected successfully");
            
            // Laravel API URL
            $this->laravelApiUrl = 'http://localhost:8000/api';
            
        } catch (Exception $e) {
            $this->log("Initialization error: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function runBidirectionalSync() 
    {
        $this->log("Starting bidirectional sync...");
        
        try {
            // Step 1: Pull commands from Laravel (Website → Device)
            $this->pullCommandsFromLaravel();
            
            // Step 2: Push attendance to Laravel (Device → Website)  
            $this->pushAttendanceToLaravel();
            
            $this->log("Bidirectional sync completed successfully");
            
        } catch (Exception $e) {
            $this->log("Sync error: " . $e->getMessage());
        }
    }
    
    private function pullCommandsFromLaravel() 
    {
        try {
            $this->log("Pulling commands from Laravel...");
            
            // Get pending commands from Laravel API
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => 'Accept: application/json',
                    'timeout' => 10
                ]
            ]);
            
            $response = @file_get_contents($this->laravelApiUrl . '/device/commands', false, $context);
            
            if ($response === false) {
                $this->log("Failed to connect to Laravel API");
                return;
            }
            
            $data = json_decode($response, true);
            
            if (!$data || !$data['success']) {
                $this->log("Invalid response from Laravel API");
                return;
            }
            
            if (empty($data['commands'])) {
                $this->log("No pending commands found");
                return;
            }
            
            $this->log("Found " . count($data['commands']) . " pending commands");
            
            foreach ($data['commands'] as $command) {
                $this->processCommand($command);
            }
            
        } catch (Exception $e) {
            $this->log("Error pulling commands: " . $e->getMessage());
        }
    }
    
    private function processCommand($command) 
    {
        try {
            $commandData = json_decode($command['data'], true);
            $this->log("Processing command: {$command['command_type']} for ID: {$command['personnel_id']}");
            
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
                default:
                    throw new Exception("Unknown command type: {$command['command_type']}");
            }
            
            // Mark command as completed
            $this->markCommandCompleted($command['id']);
            $this->log("Successfully processed command {$command['id']}");
            
        } catch (Exception $e) {
            $this->markCommandFailed($command['id'], $e->getMessage());
            $this->log("Failed to process command {$command['id']}: " . $e->getMessage());
        }
    }
    
    private function addUserToSQLite($userData) 
    {
        // Check if user already exists
        $checkStmt = $this->sqliteDb->prepare("SELECT emp_pin FROM hr_employee WHERE emp_pin = ?");
        $checkStmt->execute([$userData['emp_pin']]);
        
        if ($checkStmt->fetch()) {
            // Update existing user
            $this->updateUserInSQLite($userData);
            return;
        }
        
        // Insert new user into hr_employee table
        $stmt = $this->sqliteDb->prepare("
            INSERT INTO hr_employee 
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
        
        $this->log("Added user to SQLite: {$userData['emp_firstname']} (ID: {$userData['emp_pin']})");
    }
    
    private function updateUserInSQLite($userData) 
    {
        $stmt = $this->sqliteDb->prepare("
            UPDATE hr_employee 
            SET emp_firstname = ?, emp_lastname = ?, emp_cardNumber = ?, department_id = ?, emp_active = ?
            WHERE emp_pin = ?
        ");
        
        $stmt->execute([
            $userData['emp_firstname'],
            $userData['emp_lastname'] ?? '',
            $userData['emp_cardNumber'] ?? null,
            $userData['department_id'] ?? 1,
            $userData['emp_active'] ?? 1,
            $userData['emp_pin']
        ]);
        
        $this->log("Updated user in SQLite: {$userData['emp_firstname']} (ID: {$userData['emp_pin']})");
    }
    
    private function deleteUserFromSQLite($userData) 
    {
        // Soft delete - mark as inactive
        $stmt = $this->sqliteDb->prepare("UPDATE hr_employee SET emp_active = 0 WHERE emp_pin = ?");
        $stmt->execute([$userData['emp_pin']]);
        
        $this->log("Deactivated user in SQLite: ID {$userData['emp_pin']}");
    }
    
    private function pushAttendanceToLaravel() 
    {
        try {
            $this->log("Pushing attendance to Laravel...");
            
            // Get recent attendance records from SQLite
            $stmt = $this->sqliteDb->prepare("
                SELECT personnel_id, punch_time, punch_state, verify_type, work_code 
                FROM att_punches 
                WHERE punch_time > datetime('now', '-2 days')
                ORDER BY punch_time DESC
                LIMIT 1000
            ");
            $stmt->execute();
            $records = $stmt->fetchAll();
            
            if (empty($records)) {
                $this->log("No attendance records found");
                return;
            }
            
            $this->log("Found " . count($records) . " attendance records");
            
            // Send to Laravel via direct MySQL insert (more reliable than API)
            $this->insertAttendanceToMySQL($records);
            
        } catch (Exception $e) {
            $this->log("Error pushing attendance: " . $e->getMessage());
        }
    }
    
    private function insertAttendanceToMySQL($records) 
    {
        $syncedCount = 0;
        
        foreach ($records as $record) {
            try {
                // Check if already synced
                $checkStmt = $this->mysqlDb->prepare("
                    SELECT id FROM student_attendances 
                    WHERE device_user_id = ? AND check_in_time = ?
                ");
                $checkStmt->execute([
                    $record['personnel_id'], 
                    date('H:i:s', strtotime($record['punch_time']))
                ]);
                
                if ($checkStmt->fetch()) {
                    continue; // Already synced
                }
                
                // Find student by personnel_id (roll number)
                $studentStmt = $this->mysqlDb->prepare("
                    SELECT id, name_bn, class, section 
                    FROM students 
                    WHERE roll = ? OR student_id = ?
                ");
                $studentStmt->execute([$record['personnel_id'], $record['personnel_id']]);
                $student = $studentStmt->fetch();
                
                if (!$student) {
                    $this->log("Student not found for personnel_id: {$record['personnel_id']}");
                    continue;
                }
                
                $attendanceDate = date('Y-m-d', strtotime($record['punch_time']));
                $checkInTime = date('H:i:s', strtotime($record['punch_time']));
                
                // Insert attendance record
                $insertStmt = $this->mysqlDb->prepare("
                    INSERT INTO student_attendances 
                    (student_id, attendance_date, status, class, section, academic_year, marked_by, check_in_time, device_user_id, verify_type, in_out_mode, created_at, updated_at) 
                    VALUES (?, ?, 'present', ?, ?, ?, 'ZKTime.Net', ?, ?, ?, ?, NOW(), NOW())
                    ON DUPLICATE KEY UPDATE 
                    check_in_time = VALUES(check_in_time),
                    device_user_id = VALUES(device_user_id),
                    verify_type = VALUES(verify_type),
                    in_out_mode = VALUES(in_out_mode),
                    updated_at = NOW()
                ");
                
                $insertStmt->execute([
                    $student['id'],
                    $attendanceDate,
                    $student['class'],
                    $student['section'],
                    date('Y'),
                    $checkInTime,
                    $record['personnel_id'],
                    $record['verify_type'],
                    $record['punch_state']
                ]);
                
                $syncedCount++;
                $this->log("Synced attendance for: {$student['name_bn']} on $attendanceDate at $checkInTime");
                
            } catch (Exception $e) {
                $this->log("Error syncing attendance record: " . $e->getMessage());
            }
        }
        
        $this->log("Successfully synced $syncedCount attendance records");
    }
    
    private function markCommandCompleted($commandId) 
    {
        $this->updateCommandStatus($commandId, 'completed', null);
    }
    
    private function markCommandFailed($commandId, $errorMessage) 
    {
        $this->updateCommandStatus($commandId, 'failed', $errorMessage);
    }
    
    private function updateCommandStatus($commandId, $status, $errorMessage) 
    {
        try {
            $postData = json_encode([
                'status' => $status,
                'error_message' => $errorMessage
            ]);
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => $postData,
                    'timeout' => 5
                ]
            ]);
            
            @file_get_contents($this->laravelApiUrl . "/device/commands/$commandId/status", false, $context);
            
        } catch (Exception $e) {
            $this->log("Failed to update command status: " . $e->getMessage());
        }
    }
    
    private function log($message) 
    {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        
        echo $logMessage;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
    
    public function __destruct() 
    {
        $this->log("=== ZKTime.Net Bridge Ended ===");
    }
}

// Run the bidirectional sync
try {
    $bridge = new ZKTimeBidirectionalBridge();
    $bridge->runBidirectionalSync();
} catch (Exception $e) {
    echo "Fatal error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
?>