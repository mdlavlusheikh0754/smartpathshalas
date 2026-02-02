<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class ZKTecoService
{
    private $ip;
    private $port;
    private $socket;
    private $sessionId;
    private $isConnected = false;

    public function __construct($ip = '192.168.1.201', $port = 4370)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    public function connect()
    {
        try {
            $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            if (!$this->socket) {
                throw new Exception('Socket creation failed');
            }

            socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 5, 'usec' => 0]);
            socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 5, 'usec' => 0]);

            // Connect command
            $command = $this->createCommand(1000, '');
            $result = $this->sendCommand($command);
            
            if ($result) {
                $this->isConnected = true;
                $this->sessionId = unpack('v', substr($result, 4, 2))[1];
                Log::info('ZKTeco connected successfully', ['ip' => $this->ip, 'session_id' => $this->sessionId]);
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            Log::error('ZKTeco connection failed: ' . $e->getMessage());
            return false;
        }
    }

    public function disconnect()
    {
        if ($this->isConnected && $this->socket) {
            $command = $this->createCommand(1001, '');
            $this->sendCommand($command);
            socket_close($this->socket);
            $this->isConnected = false;
            Log::info('ZKTeco disconnected');
        }
    }

    public function getAttendanceRecords()
    {
        if (!$this->isConnected) {
            if (!$this->connect()) {
                return false;
            }
        }

        try {
            // Get attendance records command
            $command = $this->createCommand(13, '');
            $result = $this->sendCommand($command);
            
            if (!$result) {
                return [];
            }

            return $this->parseAttendanceData($result);
        } catch (Exception $e) {
            Log::error('Failed to get attendance records: ' . $e->getMessage());
            return [];
        }
    }

    public function clearAttendanceRecords()
    {
        if (!$this->isConnected) {
            if (!$this->connect()) {
                return false;
            }
        }

        try {
            $command = $this->createCommand(14, '');
            $result = $this->sendCommand($command);
            return $result !== false;
        } catch (Exception $e) {
            Log::error('Failed to clear attendance records: ' . $e->getMessage());
            return false;
        }
    }

    public function addUser($userId, $name, $password = '', $privilege = 0, $cardNumber = '')
    {
        if (!$this->isConnected) {
            if (!$this->connect()) {
                return false;
            }
        }

        try {
            // User data format for ZKTeco
            $userData = pack('v', $userId) . 
                       str_pad($name, 24, "\0") . 
                       str_pad($password, 8, "\0") . 
                       pack('C', $privilege) . 
                       str_pad($cardNumber, 5, "\0");
            
            $command = $this->createCommand(8, $userData);
            $result = $this->sendCommand($command);
            
            return $result !== false;
        } catch (Exception $e) {
            Log::error('Failed to add user: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId)
    {
        if (!$this->isConnected) {
            if (!$this->connect()) {
                return false;
            }
        }

        try {
            $userData = pack('v', $userId);
            $command = $this->createCommand(18, $userData);
            $result = $this->sendCommand($command);
            
            return $result !== false;
        } catch (Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return false;
        }
    }

    public function getDeviceInfo()
    {
        if (!$this->isConnected) {
            if (!$this->connect()) {
                return false;
            }
        }

        try {
            $command = $this->createCommand(11, '');
            $result = $this->sendCommand($command);
            
            if ($result) {
                return [
                    'device_name' => 'ZKTeco K50A',
                    'firmware_version' => $this->extractString($result, 8, 16),
                    'serial_number' => $this->extractString($result, 24, 16),
                    'user_count' => unpack('V', substr($result, 40, 4))[1] ?? 0,
                    'record_count' => unpack('V', substr($result, 44, 4))[1] ?? 0,
                ];
            }
            
            return false;
        } catch (Exception $e) {
            Log::error('Failed to get device info: ' . $e->getMessage());
            return false;
        }
    }

    private function createCommand($command, $data = '')
    {
        $header = pack('vvVv', 0x5050, 0x0000, $this->sessionId ?? 0, $command);
        $size = pack('v', strlen($data));
        $checksum = $this->calculateChecksum($header . $size . $data);
        
        return $header . $size . pack('v', $checksum) . $data;
    }

    private function sendCommand($command)
    {
        try {
            $sent = socket_sendto($this->socket, $command, strlen($command), 0, $this->ip, $this->port);
            if ($sent === false) {
                return false;
            }

            $response = '';
            $from = '';
            $port = 0;
            $received = socket_recvfrom($this->socket, $response, 1024, 0, $from, $port);
            
            if ($received === false || $received === 0) {
                return false;
            }

            // Verify response
            if (strlen($response) < 8) {
                return false;
            }

            $header = unpack('v4', substr($response, 0, 8));
            if ($header[1] !== 0x5050) {
                return false;
            }

            return $response;
        } catch (Exception $e) {
            Log::error('Command send failed: ' . $e->getMessage());
            return false;
        }
    }

    private function parseAttendanceData($data)
    {
        $records = [];
        $offset = 8; // Skip header
        
        while ($offset < strlen($data) - 40) {
            try {
                $userId = unpack('v', substr($data, $offset, 2))[1];
                $timestamp = unpack('V', substr($data, $offset + 2, 4))[1];
                $verifyType = unpack('C', substr($data, $offset + 6, 1))[1];
                $inOutMode = unpack('C', substr($data, $offset + 7, 1))[1];
                
                $records[] = [
                    'user_id' => $userId,
                    'timestamp' => date('Y-m-d H:i:s', $timestamp),
                    'verify_type' => $verifyType, // 1=fingerprint, 15=password, etc
                    'in_out_mode' => $inOutMode,  // 0=check in, 1=check out
                ];
                
                $offset += 40; // Each record is 40 bytes
            } catch (Exception $e) {
                break;
            }
        }
        
        return $records;
    }

    private function calculateChecksum($data)
    {
        $checksum = 0;
        for ($i = 0; $i < strlen($data); $i += 2) {
            $word = unpack('v', substr($data . "\0", $i, 2))[1];
            $checksum += $word;
            if ($checksum > 0xFFFF) {
                $checksum = ($checksum & 0xFFFF) + 1;
            }
        }
        return $checksum;
    }

    private function extractString($data, $offset, $length)
    {
        $str = substr($data, $offset, $length);
        $nullPos = strpos($str, "\0");
        return $nullPos !== false ? substr($str, 0, $nullPos) : $str;
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}