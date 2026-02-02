<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'tenant.lock',
])->group(function () {
    
    Route::controller(\App\Http\Controllers\Tenant\PageController::class)->group(function () {
        Route::get('/', 'home')->name('tenant.home');
        
        // About
        Route::get('/about', 'about')->name('tenant.about');
        
        // Administration
        Route::get('/administration', 'administration')->name('tenant.administration');
        Route::get('/administration/committee', 'committee')->name('tenant.administration.committee');
        Route::get('/administration/staff', 'staff')->name('tenant.administration.staff');
        
        // Academic
        Route::get('/academic', 'academic')->name('tenant.academic');
        Route::get('/academic/routine', 'routine')->name('tenant.academic.routine');
        Route::get('/academic/syllabus', 'syllabus')->name('tenant.academic.syllabus');
        Route::get('/academic/holidays', 'holidays')->name('tenant.academic.holidays');
        Route::get('/academic/calendar', 'calendar')->name('tenant.academic.calendar');

        // Admission
        // Route::get('/admission/apply', 'admissionApply')->name('tenant.admission.apply'); // Moved to AdmissionController
        Route::get('/admission/rules', 'admissionRules')->name('tenant.admission.rules');

        Route::get('/notice', 'notice')->name('tenant.notice');
        Route::get('/gallery', 'gallery')->name('tenant.gallery');
        Route::get('/contact', 'contact')->name('tenant.contact');
        Route::post('/contact', 'storeContact')->name('tenant.contact.store');
        
        // Login Credentials Info Page
        Route::get('/login-info', 'studentsInfo')->name('tenant.login.info');
    });

    // Admission Routes
    Route::controller(\App\Http\Controllers\Tenant\AdmissionController::class)->group(function () {
        Route::get('/admission/apply', 'apply')->name('tenant.admission.apply');
        Route::post('/admission/apply', 'store')->name('tenant.admission.store');
    });

    // Auth Routes for Tenant
    Route::middleware('guest')->group(function () {
        Route::get('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])
            ->name('login');
        Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
        
        Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
            ->name('register');
        Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
        
        Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
            ->name('password.email');
        
        Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
            ->name('password.reset');
        Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
            ->name('password.store');
    });

    // Auth Routes for Authenticated Users
    Route::middleware('auth')->group(function () {
        Route::get('verify-email', [\App\Http\Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])
            ->name('verification.notice');
        
        Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');
        
        Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
        
        Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');
        Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);
        
        Route::put('password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])
            ->name('password.update');
    });

    // Student Auth Routes
    Route::prefix('student')->name('student.')->group(function () {
        Route::middleware('guest:student')->group(function () {
            Route::get('login', [\App\Http\Controllers\Tenant\Auth\StudentAuthController::class, 'create'])->name('login');
            Route::post('login', [\App\Http\Controllers\Tenant\Auth\StudentAuthController::class, 'store']);
        });
        
        Route::middleware('auth:student')->group(function () {
            Route::post('logout', [\App\Http\Controllers\Tenant\Auth\StudentAuthController::class, 'destroy'])->name('logout');
            Route::get('dashboard', [\App\Http\Controllers\Tenant\StudentDashboardController::class, 'index'])->name('dashboard');
        });
    });

    // Guardian Auth Routes
    Route::prefix('guardian')->name('guardian.')->group(function () {
        Route::middleware('guest:guardian')->group(function () {
            Route::get('login', [\App\Http\Controllers\Tenant\Auth\GuardianAuthController::class, 'create'])->name('login');
            Route::post('login', [\App\Http\Controllers\Tenant\Auth\GuardianAuthController::class, 'store']);
        });
        
        Route::middleware('auth:guardian')->group(function () {
            Route::post('logout', [\App\Http\Controllers\Tenant\Auth\GuardianAuthController::class, 'destroy'])->name('logout');
            Route::get('dashboard', [\App\Http\Controllers\Tenant\GuardianDashboardController::class, 'index'])->name('dashboard');
        });
    });

    // Homework Routes (Public) - Place early to avoid conflicts
    Route::get('/homework', [\App\Http\Controllers\Tenant\HomeworkController::class, 'index'])->name('homework.index');
    Route::get('/homework-details/{id}', [\App\Http\Controllers\Tenant\HomeworkController::class, 'getDetails'])->name('homework.details');
    Route::get('/homework/class/{class}', [\App\Http\Controllers\Tenant\HomeworkController::class, 'getByClass'])->name('homework.by-class');
    Route::get('/homework/subjects-for-class', [\App\Http\Controllers\Tenant\HomeworkController::class, 'getSubjects'])->name('homework.subjects-for-class');
    Route::get('/homework/{id}', [\App\Http\Controllers\Tenant\HomeworkController::class, 'show'])->name('homework.show')->where('id', '[0-9]+');

    // Public Result Routes
    Route::get('/public-result', [\App\Http\Controllers\Tenant\PublicResultController::class, 'index'])->name('public.result.index');
    Route::get('/public/result', [\App\Http\Controllers\Tenant\PublicResultController::class, 'index']); // Alias for user convenience
    Route::post('/public-result/search', [\App\Http\Controllers\Tenant\PublicResultController::class, 'search'])->name('public.result.search');

    // Storage file serving route for tenant domains
    Route::get('/files/{path}', function ($path) {
        $filePath = storage_path('app/public/' . $path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        $mimeType = mime_content_type($filePath);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    })->where('path', '.*')->name('tenant.files');

    // স্কুলের ড্যাশবোর্ড (লগইন করা থাকলে)
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', function () {
            return view('tenant.dashboard');
        })->name('tenant.dashboard');

        // Students Routes
        Route::prefix('students')->name('tenant.students.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\StudentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Tenant\StudentController::class, 'create'])->name('create');
            
            // Login Management (Placed before {id} to avoid conflict)
            Route::get('/login-management', [\App\Http\Controllers\Tenant\StudentController::class, 'loginManagement'])->name('login-management');
            Route::post('/{id}/regenerate-password', [\App\Http\Controllers\Tenant\StudentController::class, 'regeneratePassword'])->name('regenerate-password');
            
            // Admission Approval (Placed before {id} to avoid conflict)
            Route::get('/admission-requests', [\App\Http\Controllers\Tenant\StudentController::class, 'admissionRequests'])->name('admission-requests');
            Route::post('/{id}/approve-admission', [\App\Http\Controllers\Tenant\StudentController::class, 'approveAdmission'])->name('approve-admission');

            Route::post('/', [\App\Http\Controllers\Tenant\StudentController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Tenant\StudentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Tenant\StudentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\StudentController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\StudentController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/id-card', [\App\Http\Controllers\Tenant\StudentController::class, 'idCard'])->name('id-card');
            
            // Document routes
            Route::get('/{id}/document/{field}/view', [\App\Http\Controllers\Tenant\StudentController::class, 'viewDocument'])->name('document.view');
            Route::get('/{id}/document/{field}/download', [\App\Http\Controllers\Tenant\StudentController::class, 'downloadDocument'])->name('document.download');
        });

        // API Routes for Student QR/RFID
        Route::prefix('api/tenant/students')->name('tenant.api.students.')->group(function () {
            Route::post('/qr-rfid-list', [\App\Http\Controllers\Tenant\AttendanceController::class, 'qrRfidList'])->name('qr-rfid-list');
            Route::post('/generate-qr/{id}', [\App\Http\Controllers\Tenant\AttendanceController::class, 'generateQr'])->name('generate-qr');
            Route::post('/set-rfid/{id}', [\App\Http\Controllers\Tenant\AttendanceController::class, 'setRfid'])->name('set-rfid');
            Route::post('/generate-all-qr', [\App\Http\Controllers\Tenant\AttendanceController::class, 'generateAllQr'])->name('generate-all-qr');
        });

        // Teachers Routes
        Route::prefix('teachers')->name('tenant.teachers.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\TeacherController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Tenant\TeacherController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Tenant\TeacherController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Tenant\TeacherController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Tenant\TeacherController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\TeacherController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\TeacherController::class, 'destroy'])->name('destroy');
        });

        // Classes Routes
        Route::prefix('classes')->name('tenant.classes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\ClassController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Tenant\ClassController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Tenant\ClassController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Tenant\ClassController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Tenant\ClassController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\ClassController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\ClassController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [\App\Http\Controllers\Tenant\ClassController::class, 'bulkDelete'])->name('bulk-delete');
        });

        // Exams Routes
        Route::prefix('exams')->name('tenant.exams.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\ExamController::class, 'index'])->name('index');
            Route::get('/manage', [\App\Http\Controllers\Tenant\ExamController::class, 'manage'])->name('manage');
            Route::get('/api/list', [\App\Http\Controllers\Tenant\ExamController::class, 'getApiList'])->name('api.list');
            Route::get('/subjects', function () {
                return redirect()->route('tenant.exams.subject-selection');
            })->name('subjects');
            Route::get('/subject-selection', [\App\Http\Controllers\Tenant\ExamController::class, 'subjectSelection'])->name('subject-selection');
            Route::get('/create', [\App\Http\Controllers\Tenant\ExamController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Tenant\ExamController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Tenant\ExamController::class, 'show'])->name('show');
            Route::get('/{id}/data', [\App\Http\Controllers\Tenant\ExamController::class, 'getData'])->name('data');
            Route::get('/{id}/edit', [\App\Http\Controllers\Tenant\ExamController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\ExamController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\ExamController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/publish', [\App\Http\Controllers\Tenant\ExamController::class, 'publish'])->name('publish');
            Route::post('/{id}/unpublish', [\App\Http\Controllers\Tenant\ExamController::class, 'unpublish'])->name('unpublish');
            Route::post('/{id}/update-status', [\App\Http\Controllers\Tenant\ExamController::class, 'updateStatus'])->name('update-status');
            
            // Exam Subjects API Routes
            Route::get('/api/exam-subjects/{examId}', [\App\Http\Controllers\Tenant\ExamController::class, 'getExamSubjects'])->name('api.exam-subjects');
            Route::post('/api/exam-subjects/{examId}', [\App\Http\Controllers\Tenant\ExamController::class, 'saveExamSubjects'])->name('api.save-exam-subjects');
            
            // Subject Groups API Routes
            Route::get('/api/subject-groups/{examId}', [\App\Http\Controllers\Tenant\ExamController::class, 'getSubjectGroups'])->name('api.subject-groups');
            Route::delete('/api/subject-groups/{examId}', [\App\Http\Controllers\Tenant\ExamController::class, 'deleteSubjectGroups'])->name('api.delete-subject-groups');
            Route::delete('/api/subject-groups/{examId}/group/{groupId}', [\App\Http\Controllers\Tenant\ExamController::class, 'deleteSubjectGroup'])->name('api.delete-subject-group');
        });

        // Results Routes
        Route::prefix('results')->name('tenant.results.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\ResultController::class, 'index'])->name('index');
            
            // API Routes for Results
            Route::get('/api/exams', [\App\Http\Controllers\Tenant\ResultController::class, 'getExams'])->name('api.exams');
            Route::get('/api/classes', [\App\Http\Controllers\Tenant\ResultController::class, 'getClasses'])->name('api.classes');
            Route::get('/api/results', [\App\Http\Controllers\Tenant\ResultController::class, 'getResults'])->name('api.results');
            Route::post('/api/calculate-with-monthly', [\App\Http\Controllers\Tenant\ResultController::class, 'calculateWithMonthly'])->name('api.calculate-with-monthly');
        });

        // Subjects Routes
        Route::prefix('subjects')->name('tenant.subjects.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\SubjectController::class, 'index'])->name('index');
            Route::get('/api/subjects', [\App\Http\Controllers\Tenant\SubjectController::class, 'getSubjects'])->name('api.subjects');
            Route::get('/api/stats', [\App\Http\Controllers\Tenant\SubjectController::class, 'getStats'])->name('api.stats');
            Route::get('/api/classes', [\App\Http\Controllers\Tenant\SubjectController::class, 'getClasses'])->name('api.classes');
            Route::post('/api/subjects', [\App\Http\Controllers\Tenant\SubjectController::class, 'store'])->name('api.store');
            Route::put('/api/subjects/{subject}', [\App\Http\Controllers\Tenant\SubjectController::class, 'update'])->name('api.update');
            Route::delete('/api/subjects/{subject}', [\App\Http\Controllers\Tenant\SubjectController::class, 'destroy'])->name('api.destroy');
            Route::post('/api/subjects/bulk-delete', [\App\Http\Controllers\Tenant\SubjectController::class, 'bulkDestroy'])->name('api.bulk-destroy');
        });

        // Routine Routes
        Route::prefix('routine')->name('tenant.routine.')->group(function () {
            Route::get('/', function () {
                return view('tenant.routine.index');
            })->name('index');
            Route::get('/create', function () {
                return view('tenant.routine.create');
            })->name('create');
            Route::post('/', function () {
                return redirect()->route('tenant.routine.index')->with('success', 'রুটিন সফলভাবে তৈরি করা হয়েছে');
            })->name('store');
            Route::get('/{id}/edit', function ($id) {
                return view('tenant.routine.edit', compact('id'));
            })->name('edit');
            Route::put('/{id}', function ($id) {
                return redirect()->route('tenant.routine.index')->with('success', 'রুটিন সফলভাবে আপডেট করা হয়েছে');
            })->name('update');
            Route::delete('/{id}', function ($id) {
                return redirect()->route('tenant.routine.index')->with('success', 'রুটিন সফলভাবে মুছে ফেলা হয়েছে');
            })->name('destroy');
            Route::get('/class/{class_id}', function ($class_id) {
                return view('tenant.routine.class', compact('class_id'));
            })->name('class');
            Route::get('/teacher/{teacher_id}', function ($teacher_id) {
                return view('tenant.routine.teacher', compact('teacher_id'));
            })->name('teacher');
        });

        // Marks Routes
        Route::prefix('marks')->name('tenant.marks.')->group(function () {
            Route::get('/entry', function () {
                return view('tenant.marks.entry');
            })->name('entry');
            Route::get('/save', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'showSavePage'])->name('save');
            Route::post('/create-backup', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'createBackup'])->name('create-backup');
            
            // API Routes for Marks Entry
            Route::get('/api/exams', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'getExams'])->name('api.exams');
            Route::get('/api/classes', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'getClasses'])->name('api.classes');
            Route::get('/api/marks-exam-subjects/{examId}', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'getExamSubjects'])->name('api.marks-exam-subjects');
            Route::get('/api/students/{classId}', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'getStudents'])->name('api.students');
            Route::get('/api/marks', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'getMarks'])->name('api.marks');
            Route::post('/api/marks', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'saveMarks'])->name('api.save-marks');
            Route::get('/api/saved-marks', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'getSavedMarks'])->name('api.saved-marks');
            Route::get('/api/summary', [\App\Http\Controllers\Tenant\MarksEntryController::class, 'getSummary'])->name('api.summary');
        });

        // Promotion Routes
        Route::prefix('promotion')->name('tenant.promotion.')->group(function () {
            Route::get('/', function () {
                return view('tenant.promotion.index');
            })->name('index');
        });

        // Attendance Routes
        Route::prefix('attendance')->name('tenant.attendance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\AttendanceController::class, 'index'])->name('index');
            Route::get('/take', [\App\Http\Controllers\Tenant\AttendanceController::class, 'take'])->name('take');
            Route::post('/store', [\App\Http\Controllers\Tenant\AttendanceController::class, 'store'])->name('store');
            Route::post('/mark-scan', [\App\Http\Controllers\Tenant\AttendanceController::class, 'markByScan'])->name('mark-scan');
            Route::get('/report', [\App\Http\Controllers\Tenant\AttendanceController::class, 'report'])->name('report');
            Route::get('/report-data', [\App\Http\Controllers\Tenant\AttendanceController::class, 'reportData'])->name('report-data');
            Route::get('/export', [\App\Http\Controllers\Tenant\AttendanceController::class, 'export'])->name('export');
            Route::get('/students/{classId}', [\App\Http\Controllers\Tenant\AttendanceController::class, 'getStudents'])->name('students');
            Route::get('/settings', [\App\Http\Controllers\Tenant\AttendanceController::class, 'settings'])->name('settings');
            Route::get('/id-cards', [\App\Http\Controllers\Tenant\AttendanceController::class, 'idCards'])->name('id-cards');
            
            // ZKTeco Device Routes
            Route::prefix('zkteco')->name('zkteco.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'index'])->name('index');
                Route::get('/settings', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'settings'])->name('settings');
                Route::post('/update-settings', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'updateSettings'])->name('update-settings');
                Route::get('/status', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'deviceStatus'])->name('status');
                Route::post('/sync', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'syncAttendance'])->name('sync');
                Route::post('/sync-users', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'syncUsers'])->name('sync-users');
                Route::post('/clear', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'clearDeviceRecords'])->name('clear');
                Route::post('/test', [\App\Http\Controllers\Tenant\ZKTecoController::class, 'testConnection'])->name('test');
            });
        });

        // Fee Management Routes
        Route::prefix('fees')->name('tenant.fees.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\FeeController::class, 'index'])->name('index');
            Route::get('/collect', [\App\Http\Controllers\Tenant\FeeController::class, 'collect'])->name('collect');
            Route::get('/collect/admission', [\App\Http\Controllers\Tenant\FeeController::class, 'collectAdmission'])->name('collect.admission');
            Route::get('/collect/monthly', [\App\Http\Controllers\Tenant\FeeController::class, 'collectMonthly'])->name('collect.monthly');
            Route::get('/collect/exam', [\App\Http\Controllers\Tenant\FeeController::class, 'collectExam'])->name('collect.exam');
            Route::post('/store', [\App\Http\Controllers\Tenant\FeeController::class, 'store'])->name('store');
            Route::get('/structure', [\App\Http\Controllers\Tenant\FeeController::class, 'structure'])->name('structure');
            Route::get('/due', [\App\Http\Controllers\Tenant\FeeController::class, 'due'])->name('due');
        });

        // Notice Routes
        Route::prefix('notices')->name('tenant.notices.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\NoticeController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Tenant\NoticeController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Tenant\NoticeController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Tenant\NoticeController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Tenant\NoticeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\NoticeController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\NoticeController::class, 'destroy'])->name('destroy');
        });

        // Reports Routes
        Route::prefix('reports')->name('tenant.reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\ReportController::class, 'index'])->name('index');
            Route::get('/students', [\App\Http\Controllers\Tenant\ReportController::class, 'students'])->name('students');
            Route::get('/attendance', [\App\Http\Controllers\Tenant\ReportController::class, 'attendance'])->name('attendance');
            Route::get('/fees', [\App\Http\Controllers\Tenant\ReportController::class, 'fees'])->name('fees');
            Route::get('/exams', [\App\Http\Controllers\Tenant\ReportController::class, 'exams'])->name('exams');
        });

        // Library Routes
        Route::prefix('library')->name('tenant.library.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\LibraryController::class, 'index'])->name('index');
            Route::get('/books', [\App\Http\Controllers\Tenant\LibraryController::class, 'books'])->name('books');
            Route::get('/issue', [\App\Http\Controllers\Tenant\LibraryController::class, 'issue'])->name('issue');
            Route::get('/return', [\App\Http\Controllers\Tenant\LibraryController::class, 'return'])->name('return');
        });

        // Hostel Routes
        Route::prefix('hostel')->name('tenant.hostel.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\HostelController::class, 'index'])->name('index');
            Route::get('/rooms', [\App\Http\Controllers\Tenant\HostelController::class, 'rooms'])->name('rooms');
        });

        // Transport Routes
        Route::prefix('transport')->name('tenant.transport.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\TransportController::class, 'index'])->name('index');
            Route::get('/routes', [\App\Http\Controllers\Tenant\TransportController::class, 'routes'])->name('routes');
            Route::get('/vehicles', [\App\Http\Controllers\Tenant\TransportController::class, 'vehicles'])->name('vehicles');
        });

        // Grants Routes
        Route::prefix('grants')->name('tenant.grants.')->group(function () {
            Route::get('/', function () {
                return view('tenant.grants.index');
            })->name('index');
            Route::get('/create', function () {
                return view('tenant.grants.create');
            })->name('create');
            Route::post('/', function () {
                return redirect()->route('tenant.grants.index')->with('success', 'অনুদান আবেদন সফলভাবে জমা দেওয়া হয়েছে');
            })->name('store');
            Route::get('/{id}', function ($id) {
                return view('tenant.grants.show', compact('id'));
            })->name('show');
            Route::get('/{id}/edit', function ($id) {
                return view('tenant.grants.edit', compact('id'));
            })->name('edit');
            Route::put('/{id}', function ($id) {
                return redirect()->route('tenant.grants.index')->with('success', 'অনুদান আবেদন সফলভাবে আপডেট করা হয়েছে');
            })->name('update');
            Route::delete('/{id}', function ($id) {
                return redirect()->route('tenant.grants.index')->with('success', 'অনুদান আবেদন সফলভাবে মুছে ফেলা হয়েছে');
            })->name('destroy');
        });

        // Complaints Routes
        Route::prefix('complaints')->name('tenant.complaints.')->group(function () {
            Route::get('/', function () {
                return view('tenant.complaints.index');
            })->name('index');
            Route::get('/create', function () {
                return view('tenant.complaints.create');
            })->name('create');
            Route::post('/', function () {
                return redirect()->route('tenant.complaints.index')->with('success', 'অভিযোগ সফলভাবে জমা দেওয়া হয়েছে');
            })->name('store');
            Route::get('/{id}', function ($id) {
                return view('tenant.complaints.show', compact('id'));
            })->name('show');
            Route::get('/{id}/edit', function ($id) {
                return view('tenant.complaints.edit', compact('id'));
            })->name('edit');
            Route::put('/{id}', function ($id) {
                return redirect()->route('tenant.complaints.index')->with('success', 'অভিযোগ সফলভাবে আপডেট করা হয়েছে');
            })->name('update');
            Route::delete('/{id}', function ($id) {
                return redirect()->route('tenant.complaints.index')->with('success', 'অভিযোগ সফলভাবে মুছে ফেলা হয়েছে');
            })->name('destroy');
        });

        // Inventory Routes
        Route::prefix('inventory')->name('tenant.inventory.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\InventoryController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Tenant\InventoryController::class, 'store'])->name('store');
            Route::get('/stats', [\App\Http\Controllers\Tenant\InventoryController::class, 'getStats'])->name('stats');
            Route::get('/{id}', [\App\Http\Controllers\Tenant\InventoryController::class, 'show'])->name('show');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\InventoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\InventoryController::class, 'destroy'])->name('destroy');
        });

        // Homework Management Routes (Authenticated)
        Route::get('/homework/test', function() {
            return 'Homework route test works!';
        });
        
        Route::prefix('homework')->name('tenant.homework.')->group(function () {
            Route::get('/manage', [\App\Http\Controllers\Tenant\HomeworkController::class, 'manage'])->name('manage');
            Route::get('/create', [\App\Http\Controllers\Tenant\HomeworkController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Tenant\HomeworkController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Tenant\HomeworkController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Tenant\HomeworkController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Tenant\HomeworkController::class, 'destroy'])->name('destroy');
            Route::get('/api/subjects', [\App\Http\Controllers\Tenant\HomeworkController::class, 'getSubjects'])->name('api.subjects');
        });

        // Settings Routes
        Route::prefix('settings')->name('tenant.settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Tenant\SettingController::class, 'index'])->name('index');
            Route::get('/school', [\App\Http\Controllers\Tenant\SettingController::class, 'school'])->name('school');
            Route::post('/school/basic', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSchoolBasic'])->name('school.basic.update');
            Route::post('/school/contact', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSchoolContact'])->name('school.contact.update');
            Route::post('/school/principal', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSchoolPrincipal'])->name('school.principal.update');
            Route::post('/school/timing', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSchoolTiming'])->name('school.timing.update');
            Route::post('/school/academic', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSchoolAcademic'])->name('school.academic.update');
            Route::post('/school/financial', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSchoolFinancial'])->name('school.financial.update');
            
            // Academic Session Management Routes
            Route::post('/school/sessions', [\App\Http\Controllers\Tenant\SettingController::class, 'storeAcademicSession']);
            Route::get('/school/sessions/{session}', [\App\Http\Controllers\Tenant\SettingController::class, 'getAcademicSession']);
            Route::put('/school/sessions/{session}', [\App\Http\Controllers\Tenant\SettingController::class, 'updateAcademicSession']);
            Route::delete('/school/sessions/{session}', [\App\Http\Controllers\Tenant\SettingController::class, 'deleteAcademicSession']);
            Route::post('/school/sessions/{session}/set-current', [\App\Http\Controllers\Tenant\SettingController::class, 'setCurrentAcademicSession']);
            Route::get('/school/academic-sessions', [\App\Http\Controllers\Tenant\SettingController::class, 'getAcademicSessions']);
            
            // Fee Structure Management Routes
            Route::post('/school/fee-structures', [\App\Http\Controllers\Tenant\SettingController::class, 'storeFeeStructure']);
            Route::get('/school/fee-structures/{feeStructure}', [\App\Http\Controllers\Tenant\SettingController::class, 'getFeeStructure']);
            Route::put('/school/fee-structures/{feeStructure}', [\App\Http\Controllers\Tenant\SettingController::class, 'updateFeeStructure']);
            Route::delete('/school/fee-structures/{feeStructure}', [\App\Http\Controllers\Tenant\SettingController::class, 'deleteFeeStructure']);
            Route::get('/debug-images', function() {
                $schoolSettings = \App\Models\SchoolSetting::getSettings();
                $websiteSettings = \App\Models\WebsiteSetting::getSettings();
                
                return response()->json([
                    'school_logo' => $schoolSettings->logo,
                    'school_logo_url' => $schoolSettings->getImageUrl('logo'),
                    'school_principal_photo' => $schoolSettings->principal_photo,
                    'school_principal_photo_url' => $schoolSettings->getImageUrl('principal_photo'),
                    'website_logo' => $websiteSettings->logo,
                    'website_logo_url' => $websiteSettings->getImageUrl('logo'),
                    'storage_path' => storage_path('app/public'),
                    'public_path' => public_path('storage'),
                    'asset_url' => asset('storage/'),
                ]);
            })->name('debug.images');
            Route::get('/website', [\App\Http\Controllers\Tenant\SettingController::class, 'website'])->name('website');
            Route::post('/website', [\App\Http\Controllers\Tenant\SettingController::class, 'updateWebsite'])->name('website.update');
            Route::delete('/website/hero-image', [\App\Http\Controllers\Tenant\SettingController::class, 'deleteHeroImage'])->name('website.delete-hero-image');
            Route::delete('/website/hero-images/all', [\App\Http\Controllers\Tenant\SettingController::class, 'deleteAllHeroImages'])->name('website.delete-all-hero-images');
            Route::get('/academic', [\App\Http\Controllers\Tenant\SettingController::class, 'academic'])->name('academic');
            Route::get('/academic-files', [\App\Http\Controllers\Tenant\SettingController::class, 'academicFiles'])->name('academic-files.index');
            Route::get('/users', [\App\Http\Controllers\Tenant\SettingController::class, 'users'])->name('users');
            Route::get('/fee-structure', [\App\Http\Controllers\Tenant\SettingController::class, 'feeStructure'])->name('feeStructure');
            Route::get('/grade', [\App\Http\Controllers\Tenant\SettingController::class, 'grade'])->name('grade');
            Route::get('/notification', [\App\Http\Controllers\Tenant\SettingController::class, 'notification'])->name('notification');
            Route::post('/notification', [\App\Http\Controllers\Tenant\SettingController::class, 'updateNotification'])->name('notification.update');
            Route::get('/sms-gateway', [\App\Http\Controllers\Tenant\SettingController::class, 'smsGateway'])->name('smsGateway');
            Route::post('/sms-gateway', [\App\Http\Controllers\Tenant\SettingController::class, 'updateSmsGateway'])->name('smsGateway.update');
            Route::post('/sms-gateway/test', [\App\Http\Controllers\Tenant\SettingController::class, 'sendTestSms'])->name('smsGateway.test');
            Route::post('/custom-sms-template', [\App\Http\Controllers\Tenant\SettingController::class, 'storeCustomSmsTemplate'])->name('custom-sms-template.store');
            Route::put('/custom-sms-template/{id}', [\App\Http\Controllers\Tenant\SettingController::class, 'updateCustomSmsTemplate'])->name('custom-sms-template.update');
            Route::delete('/custom-sms-template/{id}', [\App\Http\Controllers\Tenant\SettingController::class, 'destroyCustomSmsTemplate'])->name('custom-sms-template.destroy');
            Route::get('/payment-gateway', [\App\Http\Controllers\Tenant\SettingController::class, 'paymentGateway'])->name('paymentGateway');
            Route::post('/payment-gateway', [\App\Http\Controllers\Tenant\SettingController::class, 'updatePaymentGateway'])->name('payment-gateway.update');
            Route::post('/custom-payment-method', [\App\Http\Controllers\Tenant\SettingController::class, 'storeCustomPaymentMethod'])->name('custom-payment-method.store');
            Route::get('/custom-payment-method/{id}', [\App\Http\Controllers\Tenant\SettingController::class, 'showCustomPaymentMethod'])->name('custom-payment-method.show');
            Route::put('/custom-payment-method/{id}', [\App\Http\Controllers\Tenant\SettingController::class, 'updateCustomPaymentMethod'])->name('custom-payment-method.update');
            Route::delete('/custom-payment-method/{id}', [\App\Http\Controllers\Tenant\SettingController::class, 'destroyCustomPaymentMethod'])->name('custom-payment-method.destroy');
            Route::get('/backup', [\App\Http\Controllers\Tenant\SettingController::class, 'backup'])->name('backup');
            Route::get('/security', [\App\Http\Controllers\Tenant\SettingController::class, 'security'])->name('security');
        });
    });

    // ব্রিজের অথ রাউটগুলো লোড করা
    require __DIR__.'/auth.php';
    
    // Bangladesh Address API Routes (for tenant context)
    Route::prefix('api/address')->name('api.address.')->group(function () {
        Route::get('/divisions', [\App\Http\Controllers\Api\AddressController::class, 'getDivisions'])->name('divisions');
        Route::get('/districts/{divisionId}', [\App\Http\Controllers\Api\AddressController::class, 'getDistricts'])->name('districts');
        Route::get('/upazilas/{districtId}', [\App\Http\Controllers\Api\AddressController::class, 'getUpazilas'])->name('upazilas');
        Route::get('/unions/{upazilaId}', [\App\Http\Controllers\Api\AddressController::class, 'getUnions'])->name('unions');
    });
    
    // New Location API Routes (Enhanced)
    Route::prefix('api/locations')->name('api.locations.')->group(function () {
        Route::get('/divisions', [\App\Http\Controllers\Api\LocationController::class, 'getDivisions'])->name('divisions');
        Route::get('/districts/{divisionId}', [\App\Http\Controllers\Api\LocationController::class, 'getDistricts'])->name('districts');
        Route::get('/upazilas/{districtId}', [\App\Http\Controllers\Api\LocationController::class, 'getUpazilas'])->name('upazilas');
        Route::get('/unions/{upazilaId}', [\App\Http\Controllers\Api\LocationController::class, 'getUnions'])->name('unions');
        Route::get('/wards/{unionId}', [\App\Http\Controllers\Api\LocationController::class, 'getWards'])->name('wards');
        Route::get('/villages/{parentId}', [\App\Http\Controllers\Api\LocationController::class, 'getVillages'])->name('villages');
        Route::get('/search', [\App\Http\Controllers\Api\LocationController::class, 'search'])->name('search');
        Route::get('/stats', [\App\Http\Controllers\Api\LocationController::class, 'getStats'])->name('stats');
        Route::get('/all', [\App\Http\Controllers\Api\LocationController::class, 'getAllLocations'])->name('all');
        Route::get('/{id}', [\App\Http\Controllers\Api\LocationController::class, 'getLocation'])->name('show');
    });
});
