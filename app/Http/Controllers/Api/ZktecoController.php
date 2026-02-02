<?php

namespace App\Http\Controllers\Api;

use App\Models\ZktecoDevice;
use App\Models\AttendanceRecord;
use App\Models\StudentRfidMapping;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ZktecoController extends BaseApiController
{
    /**
     * Get all registered devices
     */
    public function getDevices(): JsonResponse
    {
        try {
            $devices = ZktecoDevice::with(['attendanceRecords' => function($query) {
                $query->latest()->limit(5);
            }])->get();

            return $this->sendResponse($devices, 'Devices retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve devices: ' . $e->getMessage());
        }
    }

    /**
     * Register a new device
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|max:50|unique:zkteco_devices',
            'name' => 'required|string|max:100',
            'ip_address' => 'required|ip',
            'port' => 'integer|min:1024|max:65535',
            'location' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $device = ZktecoDevice::create([
                'device_id' => $request->device_id,
                'name' => $request->name,
                'ip_address' => $request->ip_address,
                'port' => $request->port ?? 4370,
                'location' => $request->location,
                'is_active' => true,
                'last_heartbeat' => now(),
            ]);

            return $this->sendResponse($device, 'Device registered successfully', 201);
        } catch (\Exception $e) {
            return $this->sendServerError('Failed to register device: ' . $e->getMessage());
        }
    }

    /**
     * Get device status
     */
    public function getDeviceStatus(string $deviceId): JsonResponse
    {
        try {
            $device = ZktecoDevice::where('device_id', $deviceId)->first();

            if (!$device) {
                return $this->sendNotFound('Device not found');
            }

            $status = [
                'device' => $device,
                'is_online' => $device->isOnline(),
                'last_sync' => $device->attendanceRecords()->latest('sync_timestamp')->first()?->sync_timestamp,
                'total_records_today' => $device->attendanceRecords()->whereDate('punch_timestamp', today())->count(),
            ];

            return $this->sendResponse($status, 'Device status retrieved successfully');
        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve device status: ' . $e->getMessage());
        }
    }

    /**
     * Update device heartbeat
     */
    public function updateHeartbeat(Request $request, string $deviceId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firmware_version' => 'nullable|string|max:50',
            'total_users' => 'nullable|integer|min:0',
            'total_records' => 'nullable|integer|min:0',
            'current_users' => 'nullable|integer|min:0',
            'current_records' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $device = ZktecoDevice::where('device_id', $deviceId)->first();

            if (!$device) {
                return $this->sendNotFound('Device not found');
            }

            $device->update([
                'last_heartbeat' => now(),
                'firmware_version' => $request->firmware_version ?? $device->firmware_version,
                'total_users' => $request->total_users ?? $device->total_users,
                'total_records' => $request->total_records ?? $device->total_records,
                'current_users' => $request->current_users ?? $device->current_users,
                'current_records' => $request->current_records ?? $device->current_records,
            ]);

            return $this->sendResponse($device, 'Heartbeat updated successfully');
        } catch (\Exception $e) {
            return $this->sendServerError('Failed to update heartbeat: ' . $e->getMessage());
        }
    }

    /**
     * Store attendance records from ZKTeco device
     */
    public function storeAttendanceRecords(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|exists:zkteco_devices,device_id',
            'records' => 'required|array|min:1|max:100',
            'records.*.rfid_number' => 'required|string|max:50',
            'records.*.punch_timestamp' => 'required|date',
            'records.*.punch_type' => 'required|in:IN,OUT,BREAK_IN,BREAK_OUT',
            'records.*.device_timestamp' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $results = [
                'processed' => 0,
                'duplicates' => 0,
                'invalid_students' => 0,
                'errors' => []
            ];

            DB::beginTransaction();

            foreach ($request->records as $recordData) {
                try {
                    // Find student by RFID
                    $student = StudentRfidMapping::findStudentByRfid($recordData['rfid_number']);
                    
                    if (!$student) {
                        $results['invalid_students']++;
                        $results['errors'][] = "Student not found for RFID: {$recordData['rfid_number']}";
                        continue;
                    }

                    // Check for duplicates
                    $existingRecord = AttendanceRecord::where('student_id', $student->id)
                        ->where('punch_type', $recordData['punch_type'])
                        ->where('device_timestamp', $recordData['device_timestamp'])
                        ->first();

                    if ($existingRecord) {
                        $results['duplicates']++;
                        continue;
                    }

                    // Check for potential duplicates (within 60 seconds)
                    $deviceTimestamp = Carbon::parse($recordData['device_timestamp']);
                    $potentialDuplicate = AttendanceRecord::where('student_id', $student->id)
                        ->where('punch_type', $recordData['punch_type'])
                        ->whereBetween('device_timestamp', [
                            $deviceTimestamp->copy()->subSeconds(60),
                            $deviceTimestamp->copy()->addSeconds(60)
                        ])
                        ->first();

                    $isDuplicate = $potentialDuplicate !== null;

                    // Create attendance record
                    AttendanceRecord::create([
                        'device_id' => $request->device_id,
                        'student_id' => $student->id,
                        'rfid_number' => $recordData['rfid_number'],
                        'punch_timestamp' => $recordData['punch_timestamp'],
                        'punch_type' => $recordData['punch_type'],
                        'device_timestamp' => $recordData['device_timestamp'],
                        'sync_timestamp' => now(),
                        'is_duplicate' => $isDuplicate,
                    ]);

                    if ($isDuplicate) {
                        $results['duplicates']++;
                    } else {
                        $results['processed']++;
                    }

                } catch (\Exception $e) {
                    $results['errors'][] = "Error processing record for RFID {$recordData['rfid_number']}: {$e->getMessage()}";
                }
            }

            DB::commit();

            return $this->sendResponse($results, 'Attendance records processed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->sendServerError('Failed to store attendance records: ' . $e->getMessage());
        }
    }

    /**
     * Get attendance records with filtering
     */
    public function getAttendanceRecords(Request $request): JsonResponse
    {
        try {
            $query = AttendanceRecord::with(['student', 'device'])
                ->valid() // Non-duplicate records only
                ->latest('punch_timestamp');

            // Apply filters
            if ($request->has('device_id')) {
                $query->where('device_id', $request->device_id);
            }

            if ($request->has('student_id')) {
                $query->where('student_id', $request->student_id);
            }

            if ($request->has('punch_type')) {
                $query->where('punch_type', $request->punch_type);
            }

            if ($request->has('date_from')) {
                $query->whereDate('punch_timestamp', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('punch_timestamp', '<=', $request->date_to);
            }

            // Pagination
            $perPage = min($request->get('per_page', 50), 100);
            $records = $query->paginate($perPage);

            return $this->sendPaginatedResponse($records, 'Attendance records retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve attendance records: ' . $e->getMessage());
        }
    }

    /**
     * Get student attendance history
     */
    public function getStudentAttendance(Request $request, int $studentId): JsonResponse
    {
        try {
            $student = Student::find($studentId);
            
            if (!$student) {
                return $this->sendNotFound('Student not found');
            }

            $query = AttendanceRecord::where('student_id', $studentId)
                ->valid()
                ->with('device')
                ->latest('punch_timestamp');

            // Date range filter
            if ($request->has('date_from')) {
                $query->whereDate('punch_timestamp', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('punch_timestamp', '<=', $request->date_to);
            }

            $records = $query->paginate($request->get('per_page', 50));

            $data = [
                'student' => $student,
                'attendance_records' => $records
            ];

            return $this->sendResponse($data, 'Student attendance retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve student attendance: ' . $e->getMessage());
        }
    }

    /**
     * Get RFID mappings for device synchronization
     */
    public function getRfidMappings(): JsonResponse
    {
        try {
            $mappings = StudentRfidMapping::active()
                ->with(['student:id,name_en,student_id,class'])
                ->get()
                ->map(function ($mapping) {
                    return [
                        'student_id' => $mapping->student_id,
                        'rfid_number' => $mapping->rfid_number,
                        'student_name' => $mapping->student->name_en ?? 'Unknown',
                        'registration_number' => $mapping->student->student_id ?? 'Unknown',
                        'class_id' => $mapping->student->class ?? 'Unknown',
                        'assigned_at' => $mapping->assigned_at,
                    ];
                });

            return $this->sendResponse($mappings, 'RFID mappings retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve RFID mappings: ' . $e->getMessage());
        }
    }

    /**
     * Assign RFID to student
     */
    public function assignRfid(Request $request, int $studentId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rfid_number' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $student = Student::find($studentId);
            
            if (!$student) {
                return $this->sendNotFound('Student not found');
            }

            $mapping = StudentRfidMapping::assignRfidToStudent($studentId, $request->rfid_number);

            return $this->sendResponse($mapping->load('student'), 'RFID assigned successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to assign RFID: ' . $e->getMessage());
        }
    }

    /**
     * Bulk assign RFIDs to students
     */
    public function bulkAssignRfids(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'assignments' => 'required|array|min:1|max:100',
            'assignments.*.student_id' => 'required|integer|exists:students,id',
            'assignments.*.rfid_number' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $results = [
                'successful' => 0,
                'failed' => 0,
                'errors' => []
            ];

            DB::beginTransaction();

            foreach ($request->assignments as $assignment) {
                try {
                    StudentRfidMapping::assignRfidToStudent(
                        $assignment['student_id'], 
                        $assignment['rfid_number']
                    );
                    $results['successful']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Student ID {$assignment['student_id']}: {$e->getMessage()}";
                }
            }

            DB::commit();

            return $this->sendResponse($results, 'Bulk RFID assignment completed');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendServerError('Failed to process bulk RFID assignment: ' . $e->getMessage());
        }
    }

    /**
     * Bulk remove RFID mappings
     */
    public function bulkRemoveRfids(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'student_ids' => 'required|array|min:1|max:100',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $deactivatedCount = StudentRfidMapping::whereIn('student_id', $request->student_ids)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            return $this->sendResponse([
                'deactivated_count' => $deactivatedCount,
                'student_ids' => $request->student_ids
            ], 'Bulk RFID removal completed');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to remove RFIDs: ' . $e->getMessage());
        }
    }

    /**
     * Sync students to device (placeholder for bulk operations)
     */
    public function syncStudents(Request $request): JsonResponse
    {
        try {
            // This endpoint would be used by the Python service
            // to get updated student information for device synchronization
            
            $students = Student::with(['rfidMapping' => function($query) {
                $query->active();
            }])
            ->whereHas('rfidMapping', function($query) {
                $query->active();
            })
            ->get()
            ->map(function ($student) {
                return [
                    'student_id' => $student->id,
                    'name' => $student->name,
                    'registration_number' => $student->registration_number,
                    'rfid_number' => $student->rfidMapping->rfid_number ?? null,
                    'class_id' => $student->class_id,
                ];
            });

            return $this->sendResponse($students, 'Students data retrieved for sync');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve students for sync: ' . $e->getMessage());
        }
    }

    /**
     * Get attendance reports
     */
    public function getAttendanceReports(Request $request): JsonResponse
    {
        try {
            $dateFrom = $request->get('date_from', today()->toDateString());
            $dateTo = $request->get('date_to', today()->toDateString());

            $stats = [
                'total_punches' => AttendanceRecord::valid()
                    ->dateRange($dateFrom, $dateTo)
                    ->count(),
                
                'unique_students' => AttendanceRecord::valid()
                    ->dateRange($dateFrom, $dateTo)
                    ->distinct('student_id')
                    ->count(),
                
                'punch_types' => AttendanceRecord::valid()
                    ->dateRange($dateFrom, $dateTo)
                    ->groupBy('punch_type')
                    ->selectRaw('punch_type, count(*) as count')
                    ->pluck('count', 'punch_type'),
                
                'daily_stats' => AttendanceRecord::valid()
                    ->dateRange($dateFrom, $dateTo)
                    ->groupBy(DB::raw('DATE(punch_timestamp)'))
                    ->selectRaw('DATE(punch_timestamp) as date, count(*) as count')
                    ->orderBy('date')
                    ->get(),
            ];

            return $this->sendResponse($stats, 'Attendance reports generated successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to generate attendance reports: ' . $e->getMessage());
        }
    }

    /**
     * Get device status reports
     */
    public function getDeviceStatusReports(): JsonResponse
    {
        try {
            $devices = ZktecoDevice::with(['attendanceRecords' => function($query) {
                $query->whereDate('punch_timestamp', today());
            }])
            ->get()
            ->map(function ($device) {
                return [
                    'device_id' => $device->device_id,
                    'name' => $device->name,
                    'location' => $device->location,
                    'is_online' => $device->isOnline(),
                    'last_heartbeat' => $device->last_heartbeat,
                    'records_today' => $device->attendanceRecords->count(),
                    'firmware_version' => $device->firmware_version,
                    'current_users' => $device->current_users,
                    'current_records' => $device->current_records,
                ];
            });

            return $this->sendResponse($devices, 'Device status reports generated successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to generate device status reports: ' . $e->getMessage());
        }
    }

    /**
     * Update device configuration
     */
    public function updateDeviceConfig(Request $request, string $deviceId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $device = ZktecoDevice::where('device_id', $deviceId)->first();

            if (!$device) {
                return $this->sendNotFound('Device not found');
            }

            $device->update($request->only(['name', 'location', 'is_active']));

            return $this->sendResponse($device, 'Device configuration updated successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to update device configuration: ' . $e->getMessage());
        }
    }

    /**
     * Delete device
     */
    public function deleteDevice(string $deviceId): JsonResponse
    {
        try {
            $device = ZktecoDevice::where('device_id', $deviceId)->first();

            if (!$device) {
                return $this->sendNotFound('Device not found');
            }

            // Check if device has attendance records
            $recordCount = $device->attendanceRecords()->count();
            
            if ($recordCount > 0) {
                // Soft delete by deactivating instead of hard delete
                $device->update(['is_active' => false]);
                
                return $this->sendResponse([
                    'device' => $device,
                    'message' => 'Device deactivated due to existing attendance records',
                    'attendance_records_count' => $recordCount
                ], 'Device deactivated successfully');
            } else {
                // Hard delete if no attendance records
                $device->delete();
                
                return $this->sendResponse([
                    'device_id' => $deviceId,
                    'message' => 'Device deleted permanently'
                ], 'Device deleted successfully');
            }

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to delete device: ' . $e->getMessage());
        }
    }

    /**
     * Get device health status
     */
    public function getDeviceHealth(string $deviceId): JsonResponse
    {
        try {
            $device = ZktecoDevice::where('device_id', $deviceId)->first();

            if (!$device) {
                return $this->sendNotFound('Device not found');
            }

            // Calculate health metrics
            $now = now();
            $lastWeek = $now->copy()->subWeek();
            
            $healthMetrics = [
                'device' => $device,
                'online_status' => $device->isOnline(),
                'last_heartbeat' => $device->last_heartbeat,
                'uptime_percentage' => $this->calculateUptimePercentage($device, $lastWeek, $now),
                'attendance_stats' => [
                    'today' => $device->attendanceRecords()->whereDate('punch_timestamp', today())->count(),
                    'this_week' => $device->attendanceRecords()->where('punch_timestamp', '>=', $lastWeek)->count(),
                    'total' => $device->attendanceRecords()->count(),
                ],
                'error_rate' => $this->calculateErrorRate($device, $lastWeek, $now),
                'health_score' => 0, // Will be calculated
            ];

            // Calculate overall health score (0-100)
            $healthScore = 100;
            
            // Deduct points for being offline
            if (!$healthMetrics['online_status']) {
                $healthScore -= 30;
            }
            
            // Deduct points for low uptime
            if ($healthMetrics['uptime_percentage'] < 95) {
                $healthScore -= (95 - $healthMetrics['uptime_percentage']);
            }
            
            // Deduct points for high error rate
            if ($healthMetrics['error_rate'] > 5) {
                $healthScore -= ($healthMetrics['error_rate'] * 2);
            }
            
            $healthMetrics['health_score'] = max(0, $healthScore);
            
            // Determine health status
            if ($healthScore >= 90) {
                $healthMetrics['health_status'] = 'excellent';
            } elseif ($healthScore >= 75) {
                $healthMetrics['health_status'] = 'good';
            } elseif ($healthScore >= 50) {
                $healthMetrics['health_status'] = 'fair';
            } else {
                $healthMetrics['health_status'] = 'poor';
            }

            return $this->sendResponse($healthMetrics, 'Device health status retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve device health: ' . $e->getMessage());
        }
    }

    /**
     * Get device activity log
     */
    public function getDeviceActivityLog(Request $request, string $deviceId): JsonResponse
    {
        try {
            $device = ZktecoDevice::where('device_id', $deviceId)->first();

            if (!$device) {
                return $this->sendNotFound('Device not found');
            }

            $query = $device->attendanceRecords()
                ->with('student:id,name,registration_number')
                ->latest('punch_timestamp');

            // Apply date filters
            if ($request->has('date_from')) {
                $query->whereDate('punch_timestamp', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('punch_timestamp', '<=', $request->date_to);
            }

            // Apply time filters
            if ($request->has('time_from')) {
                $query->whereTime('punch_timestamp', '>=', $request->time_from);
            }

            if ($request->has('time_to')) {
                $query->whereTime('punch_timestamp', '<=', $request->time_to);
            }

            // Apply punch type filter
            if ($request->has('punch_type')) {
                $query->where('punch_type', $request->punch_type);
            }

            $perPage = min($request->get('per_page', 50), 100);
            $records = $query->paginate($perPage);

            $data = [
                'device' => $device,
                'activity_log' => $records
            ];

            return $this->sendPaginatedResponse($records, 'Device activity log retrieved successfully');

        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve device activity log: ' . $e->getMessage());
        }
    }

    /**
     * Calculate device uptime percentage
     */
    private function calculateUptimePercentage(ZktecoDevice $device, $startDate, $endDate): float
    {
        // This is a simplified calculation
        // In a real implementation, you would track heartbeat history
        
        if (!$device->last_heartbeat) {
            return 0.0;
        }

        $totalHours = $startDate->diffInHours($endDate);
        $offlineHours = 0;

        // If device is currently offline, count offline time
        if (!$device->isOnline()) {
            $offlineHours = $device->last_heartbeat->diffInHours($endDate);
        }

        // Simplified calculation - in reality you'd need heartbeat history
        $uptimePercentage = max(0, (($totalHours - $offlineHours) / $totalHours) * 100);
        
        return round($uptimePercentage, 2);
    }

    /**
     * Calculate device error rate
     */
    private function calculateErrorRate(ZktecoDevice $device, $startDate, $endDate): float
    {
        // This would typically be based on failed API calls, connection errors, etc.
        // For now, we'll use duplicate records as a proxy for errors
        
        $totalRecords = $device->attendanceRecords()
            ->whereBetween('punch_timestamp', [$startDate, $endDate])
            ->count();

        $duplicateRecords = $device->attendanceRecords()
            ->whereBetween('punch_timestamp', [$startDate, $endDate])
            ->where('is_duplicate', true)
            ->count();

        if ($totalRecords === 0) {
            return 0.0;
        }

        return round(($duplicateRecords / $totalRecords) * 100, 2);
    }
    
    /**
     * Health check endpoint
     */
    public function healthCheck(): JsonResponse
    {
        try {
            $health = [
                'status' => 'healthy',
                'timestamp' => now()->toISOString(),
                'version' => '1.0.0',
                'services' => [
                    'database' => $this->checkDatabaseHealth(),
                    'devices' => $this->checkDevicesHealth(),
                    'api' => 'healthy'
                ]
            ];
            
            // Determine overall health
            $allHealthy = collect($health['services'])->every(function ($status) {
                return $status === 'healthy';
            });
            
            if (!$allHealthy) {
                $health['status'] = 'degraded';
            }
            
            $statusCode = $allHealthy ? 200 : 503;
            
            return response()->json($health, $statusCode);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'unhealthy',
                'timestamp' => now()->toISOString(),
                'error' => $e->getMessage()
            ], 503);
        }
    }
    
    /**
     * Get system status
     */
    public function getSystemStatus(): JsonResponse
    {
        try {
            $status = [
                'system' => [
                    'uptime' => $this->getSystemUptime(),
                    'memory_usage' => $this->getMemoryUsage(),
                    'disk_usage' => $this->getDiskUsage(),
                ],
                'database' => [
                    'status' => $this->checkDatabaseHealth(),
                    'total_records' => AttendanceRecord::count(),
                    'records_today' => AttendanceRecord::whereDate('punch_timestamp', today())->count(),
                    'active_devices' => ZktecoDevice::where('is_active', true)->count(),
                ],
                'devices' => ZktecoDevice::all()->map(function ($device) {
                    return [
                        'device_id' => $device->device_id,
                        'name' => $device->name,
                        'status' => $device->isOnline() ? 'online' : 'offline',
                        'last_heartbeat' => $device->last_heartbeat,
                        'records_today' => $device->attendanceRecords()->whereDate('punch_timestamp', today())->count(),
                    ];
                }),
                'performance' => [
                    'avg_response_time' => $this->getAverageResponseTime(),
                    'error_rate' => $this->getErrorRate(),
                    'throughput' => $this->getThroughput(),
                ]
            ];
            
            return $this->sendResponse($status, 'System status retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->sendServerError('Failed to retrieve system status: ' . $e->getMessage());
        }
    }
    
    /**
     * Check database health
     */
    private function checkDatabaseHealth(): string
    {
        try {
            DB::connection()->getPdo();
            return 'healthy';
        } catch (\Exception $e) {
            return 'unhealthy';
        }
    }
    
    /**
     * Check devices health
     */
    private function checkDevicesHealth(): string
    {
        try {
            $totalDevices = ZktecoDevice::where('is_active', true)->count();
            
            if ($totalDevices === 0) {
                return 'no_devices';
            }
            
            $onlineDevices = ZktecoDevice::where('is_active', true)
                ->where('last_heartbeat', '>=', now()->subMinutes(5))
                ->count();
            
            $healthyPercentage = ($onlineDevices / $totalDevices) * 100;
            
            if ($healthyPercentage >= 80) {
                return 'healthy';
            } elseif ($healthyPercentage >= 50) {
                return 'degraded';
            } else {
                return 'unhealthy';
            }
            
        } catch (\Exception $e) {
            return 'error';
        }
    }
    
    /**
     * Get system uptime (simplified)
     */
    private function getSystemUptime(): array
    {
        // This is a simplified implementation
        // In production, you might track application start time
        return [
            'days' => 0,
            'hours' => 0,
            'minutes' => 0,
            'note' => 'Uptime tracking not implemented'
        ];
    }
    
    /**
     * Get memory usage (simplified)
     */
    private function getMemoryUsage(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        
        return [
            'current' => $this->formatBytes($memoryUsage),
            'peak' => $this->formatBytes($memoryPeak),
            'current_bytes' => $memoryUsage,
            'peak_bytes' => $memoryPeak
        ];
    }
    
    /**
     * Get disk usage (simplified)
     */
    private function getDiskUsage(): array
    {
        $path = storage_path();
        $totalBytes = disk_total_space($path);
        $freeBytes = disk_free_space($path);
        $usedBytes = $totalBytes - $freeBytes;
        
        return [
            'total' => $this->formatBytes($totalBytes),
            'used' => $this->formatBytes($usedBytes),
            'free' => $this->formatBytes($freeBytes),
            'usage_percentage' => round(($usedBytes / $totalBytes) * 100, 2)
        ];
    }
    
    /**
     * Get average response time (placeholder)
     */
    private function getAverageResponseTime(): string
    {
        // This would typically be tracked by middleware or monitoring tools
        return 'Not tracked';
    }
    
    /**
     * Get error rate (placeholder)
     */
    private function getErrorRate(): string
    {
        // This would typically be calculated from logs or monitoring data
        return 'Not tracked';
    }
    
    /**
     * Get throughput (simplified)
     */
    private function getThroughput(): array
    {
        $recordsLastHour = AttendanceRecord::where('created_at', '>=', now()->subHour())->count();
        $recordsLast24Hours = AttendanceRecord::where('created_at', '>=', now()->subDay())->count();
        
        return [
            'records_per_hour' => $recordsLastHour,
            'records_per_day' => $recordsLast24Hours,
            'avg_per_minute' => round($recordsLastHour / 60, 2)
        ];
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}