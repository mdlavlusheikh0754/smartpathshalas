<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\HomeworkController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\SchoolController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\RoutineController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });
    
    // School information (public)
    Route::get('/school/info', [SchoolController::class, 'getSchoolInfo']);
    Route::get('/school/settings', [SchoolController::class, 'getSchoolSettings']);
    
    // Public homework (for parents/students)
    Route::get('/homework', [HomeworkController::class, 'getPublicHomework']);
    Route::get('/homework/{id}', [HomeworkController::class, 'getHomeworkDetails']);
    
    // Public notices
    Route::get('/notices', [NoticeController::class, 'getPublicNotices']);
    Route::get('/notices/{id}', [NoticeController::class, 'getNoticeDetails']);
    
    // Bangladesh Address API (public access for dropdowns)
    Route::prefix('address')->group(function () {
        Route::get('/divisions', [AddressController::class, 'getDivisions']);
        Route::get('/districts/{divisionId}', [AddressController::class, 'getDistricts']);
        Route::get('/upazilas/{districtId}', [AddressController::class, 'getUpazilas']);
        Route::get('/unions/{upazilaId}', [AddressController::class, 'getUnions']);
        Route::get('/all', [AddressController::class, 'getAllAddresses']);
    });
    
});

// Tenant-specific API routes
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('v1')->group(function () {
    
    // Authentication routes for tenant
    Route::prefix('auth')->group(function () {
        Route::post('/tenant-login', [AuthController::class, 'tenantLogin']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    });
    
    // Protected routes (require authentication)
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Authentication
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/user', [AuthController::class, 'user']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
        });
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
        
        // Students Management
        Route::prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index']);
            Route::post('/', [StudentController::class, 'store']);
            Route::get('/{id}', [StudentController::class, 'show']);
            Route::put('/{id}', [StudentController::class, 'update']);
            Route::delete('/{id}', [StudentController::class, 'destroy']);
            Route::get('/class/{class}', [StudentController::class, 'getByClass']);
            Route::post('/{id}/photo', [StudentController::class, 'uploadPhoto']);
        });
        
        // Teachers Management
        Route::prefix('teachers')->group(function () {
            Route::get('/', [TeacherController::class, 'index']);
            Route::post('/', [TeacherController::class, 'store']);
            Route::get('/{id}', [TeacherController::class, 'show']);
            Route::put('/{id}', [TeacherController::class, 'update']);
            Route::delete('/{id}', [TeacherController::class, 'destroy']);
            Route::post('/{id}/photo', [TeacherController::class, 'uploadPhoto']);
        });
        
        // Classes Management
        Route::prefix('classes')->group(function () {
            Route::get('/', [ClassController::class, 'index']);
            Route::post('/', [ClassController::class, 'store']);
            Route::get('/{id}', [ClassController::class, 'show']);
            Route::put('/{id}', [ClassController::class, 'update']);
            Route::delete('/{id}', [ClassController::class, 'destroy']);
            Route::get('/{id}/students', [ClassController::class, 'getStudents']);
        });
        
        // Homework Management
        Route::prefix('homework')->group(function () {
            Route::get('/', [HomeworkController::class, 'index']);
            Route::post('/', [HomeworkController::class, 'store']);
            Route::get('/{id}', [HomeworkController::class, 'show']);
            Route::put('/{id}', [HomeworkController::class, 'update']);
            Route::delete('/{id}', [HomeworkController::class, 'destroy']);
            Route::get('/class/{class}', [HomeworkController::class, 'getByClass']);
            Route::post('/{id}/attachment', [HomeworkController::class, 'uploadAttachment']);
        });
        
        // Attendance Management
        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceController::class, 'index']);
            Route::post('/', [AttendanceController::class, 'store']);
            Route::get('/class/{class}/date/{date}', [AttendanceController::class, 'getByClassAndDate']);
            Route::post('/bulk', [AttendanceController::class, 'bulkStore']);
            Route::get('/student/{studentId}', [AttendanceController::class, 'getStudentAttendance']);
            Route::get('/reports', [AttendanceController::class, 'getReports']);
        });
        
        // QR/RFID Management for Students
        Route::prefix('tenant/students')->group(function () {
            Route::post('/qr-rfid-list', [StudentController::class, 'getQrRfidList']);
            Route::post('/generate-qr/{id}', [StudentController::class, 'generateQR']);
            Route::post('/generate-all-qr', [StudentController::class, 'generateAllQR']);
            Route::post('/set-rfid/{id}', [StudentController::class, 'setRFID']);
        });
        
        // Device Sync Management (ZKTime.Net Integration)
        Route::prefix('device')->group(function () {
            Route::get('/commands', [\App\Http\Controllers\Tenant\StudentDeviceController::class, 'getDeviceCommands']);
            Route::post('/commands/{id}/status', [\App\Http\Controllers\Tenant\StudentDeviceController::class, 'markCommandProcessed']);
            Route::get('/sync-status', [\App\Http\Controllers\Tenant\StudentDeviceController::class, 'getSyncStatus']);
        });
        
        Route::prefix('students')->group(function () {
            Route::post('/{id}/sync-to-device', [\App\Http\Controllers\Tenant\StudentDeviceController::class, 'syncStudentToDevice']);
            Route::post('/bulk-sync-to-device', [\App\Http\Controllers\Tenant\StudentDeviceController::class, 'bulkSyncStudents']);
        });
        
        // Fee Management
        Route::prefix('fees')->group(function () {
            Route::get('/', [FeeController::class, 'index']);
            Route::get('/structure', [FeeController::class, 'getFeeStructure']);
            Route::get('/student/{studentId}', [FeeController::class, 'getStudentFees']);
            Route::post('/collect', [FeeController::class, 'collectFee']);
            Route::get('/due', [FeeController::class, 'getDueFees']);
            Route::get('/reports', [FeeController::class, 'getFeeReports']);
        });
        
        // Exam Management
        Route::prefix('exams')->group(function () {
            Route::get('/', [ExamController::class, 'index']);
            Route::post('/', [ExamController::class, 'store']);
            Route::get('/{id}', [ExamController::class, 'show']);
            Route::put('/{id}', [ExamController::class, 'update']);
            Route::delete('/{id}', [ExamController::class, 'destroy']);
            Route::get('/{id}/results', [ExamController::class, 'getResults']);
            Route::post('/{id}/results', [ExamController::class, 'storeResults']);
        });
        
        // Notice Management
        Route::prefix('notices')->group(function () {
            Route::get('/', [NoticeController::class, 'index']);
            Route::post('/', [NoticeController::class, 'store']);
            Route::get('/{id}', [NoticeController::class, 'show']);
            Route::put('/{id}', [NoticeController::class, 'update']);
            Route::delete('/{id}', [NoticeController::class, 'destroy']);
            Route::post('/{id}/attachment', [NoticeController::class, 'uploadAttachment']);
        });
        
        // ZKTeco Attendance Integration
        Route::prefix('zkteco')->middleware(['zkteco.validate', 'zkteco.rate_limit'])->group(function () {
            // Device Management
            Route::get('/devices', [\App\Http\Controllers\Api\ZktecoController::class, 'getDevices']);
            Route::post('/devices', [\App\Http\Controllers\Api\ZktecoController::class, 'registerDevice']);
            Route::get('/devices/{deviceId}/status', [\App\Http\Controllers\Api\ZktecoController::class, 'getDeviceStatus']);
            Route::put('/devices/{deviceId}', [\App\Http\Controllers\Api\ZktecoController::class, 'updateDeviceConfig']);
            Route::delete('/devices/{deviceId}', [\App\Http\Controllers\Api\ZktecoController::class, 'deleteDevice']);
            Route::post('/devices/{deviceId}/heartbeat', [\App\Http\Controllers\Api\ZktecoController::class, 'updateHeartbeat']);
            Route::get('/devices/{deviceId}/health', [\App\Http\Controllers\Api\ZktecoController::class, 'getDeviceHealth']);
            Route::get('/devices/{deviceId}/activity', [\App\Http\Controllers\Api\ZktecoController::class, 'getDeviceActivityLog']);
            
            // Attendance Records
            Route::post('/attendance/records', [\App\Http\Controllers\Api\ZktecoController::class, 'storeAttendanceRecords']);
            Route::get('/attendance/records', [\App\Http\Controllers\Api\ZktecoController::class, 'getAttendanceRecords']);
            Route::get('/attendance/student/{studentId}', [\App\Http\Controllers\Api\ZktecoController::class, 'getStudentAttendance']);
            
            // Student RFID Management
            Route::get('/students/rfid-mappings', [\App\Http\Controllers\Api\ZktecoController::class, 'getRfidMappings']);
            Route::post('/students/sync', [\App\Http\Controllers\Api\ZktecoController::class, 'syncStudents']);
            Route::post('/students/{studentId}/rfid', [\App\Http\Controllers\Api\ZktecoController::class, 'assignRfid']);
            Route::post('/students/rfid/bulk-assign', [\App\Http\Controllers\Api\ZktecoController::class, 'bulkAssignRfids']);
            Route::post('/students/rfid/bulk-remove', [\App\Http\Controllers\Api\ZktecoController::class, 'bulkRemoveRfids']);
            
            // Reports and Analytics
            Route::get('/reports/attendance', [\App\Http\Controllers\Api\ZktecoController::class, 'getAttendanceReports']);
            Route::get('/reports/device-status', [\App\Http\Controllers\Api\ZktecoController::class, 'getDeviceStatusReports']);
            
            // Health Check and Monitoring
            Route::get('/health', [\App\Http\Controllers\Api\ZktecoController::class, 'healthCheck']);
            Route::get('/system/status', [\App\Http\Controllers\Api\ZktecoController::class, 'getSystemStatus']);
        });

        // School Settings
        Route::prefix('school')->group(function () {
            Route::get('/settings', [SchoolController::class, 'getSettings']);
            Route::put('/settings', [SchoolController::class, 'updateSettings']);
            Route::post('/logo', [SchoolController::class, 'uploadLogo']);
            Route::get('/academic-sessions', [SchoolController::class, 'getAcademicSessions']);
            Route::post('/academic-sessions', [SchoolController::class, 'storeAcademicSession']);
        });
    });

    // Academic Routine (Publicly accessible within tenant)
    Route::prefix('routine')->group(function () {
        Route::get('/class-routine', [RoutineController::class, 'getClassRoutine']);
    });
});