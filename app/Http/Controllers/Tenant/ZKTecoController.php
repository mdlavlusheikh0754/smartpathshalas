<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\ZKTecoService;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ZKTecoController extends Controller
{
    private function getZKService()
    {
        return new ZKTecoService(
            config('zkteco.device_ip', '192.168.1.201'),
            config('zkteco.device_port', 4370)
        );
    }

    public function index()
    {
        return view('tenant.attendance.zkteco.index');
    }

    public function deviceStatus()
    {
        try {
            $zkService = $this->getZKService();
            $connected = $zkService->connect();
            
            if ($connected) {
                $info = $zkService->getDeviceInfo();
                $zkService->disconnect();
                
                return response()->json([
                    'success' => true,
                    'connected' => true,
                    'device_info' => $info,
                    'message' => 'ডিভাইস সংযুক্ত এবং কার্যকর'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'connected' => false,
                'message' => 'ডিভাইসের সাথে সংযোগ স্থাপন করা যায়নি'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'connected' => false,
                'message' => 'ত্রুটি: ' . $e->getMessage()
            ]);
        }
    }

    public function syncAttendance()
    {
        try {
            $zkService = $this->getZKService();
            $records = $zkService->getAttendanceRecords();
            
            if ($records === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'ডিভাইস থেকে ডেটা পড়া যায়নি'
                ]);
            }

            $syncedCount = 0;
            $errors = [];

            foreach ($records as $record) {
                try {
                    // Find student by user_id (which should match student_id or roll)
                    $student = Student::where('student_id', $record['user_id'])
                                    ->orWhere('roll', $record['user_id'])
                                    ->first();

                    if (!$student) {
                        $errors[] = "শিক্ষার্থী পাওয়া যায়নি (User ID: {$record['user_id']})";
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
                            'marked_by' => 'ZKTeco Device',
                            'check_in_time' => Carbon::parse($record['timestamp'])->format('H:i:s'),
                            'device_user_id' => $record['user_id'],
                            'verify_type' => $record['verify_type'],
                            'in_out_mode' => $record['in_out_mode']
                        ]);
                        
                        $syncedCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "রেকর্ড সিঙ্ক করতে ত্রুটি: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'synced_count' => $syncedCount,
                'total_records' => count($records),
                'errors' => $errors,
                'message' => "{$syncedCount} টি রেকর্ড সফলভাবে সিঙ্ক করা হয়েছে"
            ]);

        } catch (\Exception $e) {
            Log::error('ZKTeco sync failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সিঙ্ক করতে ত্রুটি: ' . $e->getMessage()
            ]);
        }
    }

    public function clearDeviceRecords()
    {
        try {
            $zkService = $this->getZKService();
            $result = $zkService->clearAttendanceRecords();
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'ডিভাইসের সকল রেকর্ড মুছে ফেলা হয়েছে'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'রেকর্ড মুছতে ব্যর্থ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ত্রুটি: ' . $e->getMessage()
            ]);
        }
    }

    public function syncUsers()
    {
        try {
            $zkService = $this->getZKService();
            $students = Student::where('status', 'active')->get();
            $syncedCount = 0;
            $errors = [];

            foreach ($students as $student) {
                try {
                    $userId = (int) ($student->roll ?: $student->id);
                    $name = $student->name_en ?: $student->name_bn;
                    
                    // Add user to device
                    $result = $zkService->addUser($userId, $name);
                    
                    if ($result) {
                        $syncedCount++;
                        
                        // Update student record with device user ID
                        $student->update(['device_user_id' => $userId]);
                    } else {
                        $errors[] = "ব্যবহারকারী যোগ করতে ব্যর্থ: {$name} (ID: {$userId})";
                    }
                } catch (\Exception $e) {
                    $errors[] = "ত্রুটি: {$student->name_en} - " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'synced_count' => $syncedCount,
                'total_students' => $students->count(),
                'errors' => $errors,
                'message' => "{$syncedCount} জন শিক্ষার্থী ডিভাইসে যোগ করা হয়েছে"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ব্যবহারকারী সিঙ্ক করতে ত্রুটি: ' . $e->getMessage()
            ]);
        }
    }

    public function testConnection(Request $request)
    {
        try {
            // Check if sockets extension is loaded
            if (!extension_loaded('sockets')) {
                return response()->json([
                    'success' => false,
                    'message' => 'PHP Sockets এক্সটেনশন সক্রিয় নেই। php.ini ফাইলে extension=sockets সক্রিয় করুন।'
                ]);
            }

            // Get IP and port from request or use defaults
            $deviceIp = $request->input('device_ip', config('zkteco.device_ip', '192.168.1.201'));
            $devicePort = $request->input('device_port', config('zkteco.device_port', 4370));

            $diagnostics = [];
            
            // Test 1: Basic ping test
            $pingResult = shell_exec("ping -n 1 $deviceIp");
            $pingSuccess = strpos($pingResult, 'Reply from') !== false;
            $diagnostics['ping'] = [
                'success' => $pingSuccess,
                'message' => $pingSuccess ? 'ডিভাইস নেটওয়ার্কে পৌঁছানো যাচ্ছে' : 'ডিভাইস নেটওয়ার্কে পৌঁছানো যাচ্ছে না'
            ];

            if (!$pingSuccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'ডিভাইস নেটওয়ার্কে পৌঁছানো যাচ্ছে না। IP ঠিকানা পরীক্ষা করুন।',
                    'diagnostics' => $diagnostics,
                    'connection_details' => [
                        'ip' => $deviceIp,
                        'port' => $devicePort,
                        'status' => 'Network Unreachable'
                    ]
                ]);
            }

            // Test 2: ZKTeco protocol test
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            if (!$socket) {
                $diagnostics['socket'] = [
                    'success' => false,
                    'message' => 'সকেট তৈরি করতে ব্যর্থ: ' . socket_strerror(socket_last_error())
                ];
                
                return response()->json([
                    'success' => false,
                    'message' => 'সকেট তৈরি করতে ব্যর্থ',
                    'diagnostics' => $diagnostics
                ]);
            }

            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 3, 'usec' => 0]);
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 3, 'usec' => 0]);

            // Create ZKTeco connect command
            $sessionId = 0;
            $command = 1000; // Connect command
            $data = '';
            $header = pack('vvVv', 0x5050, 0x0000, $sessionId, $command);
            $size = pack('v', strlen($data));
            
            // Calculate checksum
            $checksum = 0;
            $checksumData = $header . $size . $data;
            for ($i = 0; $i < strlen($checksumData); $i += 2) {
                $word = unpack('v', substr($checksumData . "\0", $i, 2))[1];
                $checksum += $word;
                if ($checksum > 0xFFFF) {
                    $checksum = ($checksum & 0xFFFF) + 1;
                }
            }
            
            $packet = $header . $size . pack('v', $checksum) . $data;
            
            // Send command
            $sent = socket_sendto($socket, $packet, strlen($packet), 0, $deviceIp, $devicePort);
            if ($sent === false) {
                $diagnostics['send'] = [
                    'success' => false,
                    'message' => 'কমান্ড পাঠাতে ব্যর্থ: ' . socket_strerror(socket_last_error($socket))
                ];
                socket_close($socket);
                
                return response()->json([
                    'success' => false,
                    'message' => 'ডিভাইসে কমান্ড পাঠাতে ব্যর্থ',
                    'diagnostics' => $diagnostics
                ]);
            }

            $diagnostics['send'] = [
                'success' => true,
                'message' => "কমান্ড সফলভাবে পাঠানো হয়েছে ($sent bytes)"
            ];

            // Receive response
            $response = '';
            $from = '';
            $fromPort = 0;
            
            // Suppress the warning and handle the timeout gracefully
            $received = @socket_recvfrom($socket, $response, 1024, 0, $from, $fromPort);
            
            socket_close($socket);

            if ($received === false || $received === 0) {
                $socketError = socket_last_error();
                $socketErrorMsg = socket_strerror($socketError);
                
                $diagnostics['receive'] = [
                    'success' => false,
                    'message' => 'ডিভাইস থেকে কোনো উত্তর পাওয়া যায়নি (timeout)',
                    'technical_details' => "Socket Error $socketError: $socketErrorMsg"
                ];
                
                return response()->json([
                    'success' => false,
                    'message' => 'ডিভাইস সংযুক্ত কিন্তু ZKTeco সার্ভিস চালু নেই। ডিভাইসের নেটওয়ার্ক সেটিংস পরীক্ষা করুন।',
                    'diagnostics' => $diagnostics,
                    'connection_details' => [
                        'ip' => $deviceIp,
                        'port' => $devicePort,
                        'status' => 'Device Not Responding',
                        'suggestion' => 'ডিভাইসের মেনুতে গিয়ে Communication > Network সেটিংস পরীক্ষা করুন। নিশ্চিত করুন যে ডিভাইসে সঠিক IP ঠিকানা সেট করা আছে।'
                    ]
                ]);
            }

            // Parse response
            if (strlen($response) >= 8) {
                $header = unpack('v4', substr($response, 0, 8));
                if ($header[1] === 0x5050) {
                    $diagnostics['receive'] = [
                        'success' => true,
                        'message' => "বৈধ ZKTeco উত্তর পাওয়া গেছে ($received bytes)"
                    ];
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'ডিভাইসের সাথে সফলভাবে সংযোগ স্থাপিত হয়েছে!',
                        'diagnostics' => $diagnostics,
                        'connection_details' => [
                            'ip' => $deviceIp,
                            'port' => $devicePort,
                            'status' => 'Connected',
                            'response_size' => $received
                        ]
                    ]);
                }
            }

            $diagnostics['receive'] = [
                'success' => false,
                'message' => 'অবৈধ উত্তর পাওয়া গেছে'
            ];
            
            return response()->json([
                'success' => false,
                'message' => 'ডিভাইস থেকে অবৈধ উত্তর পাওয়া গেছে',
                'diagnostics' => $diagnostics
            ]);

        } catch (\Exception $e) {
            Log::error('ZKTeco test connection failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'সংযোগ পরীক্ষায় ত্রুটি: ' . $e->getMessage(),
                'error_details' => [
                    'error' => $e->getMessage(),
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    public function settings()
    {
        $currentIp = config('zkteco.device_ip', '192.168.1.201');
        $currentPort = config('zkteco.device_port', 4370);
        
        return view('tenant.attendance.zkteco.settings', compact('currentIp', 'currentPort'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'device_ip' => 'required|ip',
            'device_port' => 'required|integer|min:1|max:65535'
        ]);

        try {
            // Update environment file
            $envFile = base_path('.env');
            $envContent = file_get_contents($envFile);
            
            $envContent = preg_replace('/ZKTECO_DEVICE_IP=.*/', 'ZKTECO_DEVICE_IP=' . $request->device_ip, $envContent);
            $envContent = preg_replace('/ZKTECO_DEVICE_PORT=.*/', 'ZKTECO_DEVICE_PORT=' . $request->device_port, $envContent);
            
            // Add if not exists
            if (!str_contains($envContent, 'ZKTECO_DEVICE_IP=')) {
                $envContent .= "\nZKTECO_DEVICE_IP=" . $request->device_ip;
            }
            if (!str_contains($envContent, 'ZKTECO_DEVICE_PORT=')) {
                $envContent .= "\nZKTECO_DEVICE_PORT=" . $request->device_port;
            }
            
            file_put_contents($envFile, $envContent);
            
            return response()->json([
                'success' => true,
                'message' => 'সেটিংস সফলভাবে আপডেট করা হয়েছে'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সেটিংস আপডেট করতে ত্রুটি: ' . $e->getMessage()
            ]);
        }
    }
}