<?php

use App\Http\Controllers\Central\AdminDashboardController;
use App\Http\Controllers\Central\ClientPanelController;
use App\Http\Controllers\Central\RegistrationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// এটি আপনার মেইন ল্যান্ডিং পেজ (Main Website)
foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return view('central.landing'); 
        });
        
        // Include auth routes for central domain
        require __DIR__.'/auth.php';
        
        // Authenticated routes for central domain
        Route::middleware(['auth'])->group(function () {
            Route::get('/dashboard', function () {
                $user = auth()->user();
                
                // যদি সুপার এডমিন হয়, তবে তাকে সেন্ট্রাল এডমিন প্যানেলে পাঠান
                if ($user->isAdmin()) {
                    return redirect()->route('central.dashboard');
                }
                
                // যদি ক্লায়েন্ট হয়, তবে তাকে ক্লায়েন্ট (বিলিং) প্যানেলে পাঠান
                return redirect()->route('client.dashboard');
            })->name('dashboard');
            
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });
    });
}

// Admin Routes - শুধুমাত্র সুপার এডমিনদের জন্য
Route::middleware(['auth', 'super_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('central.dashboard');
    // এখানে আপনি সব স্কুলের লিস্ট দেখানোর রাউট দিতে পারেন
    Route::get('/schools', [AdminDashboardController::class, 'schools'])->name('central.schools');
    Route::get('/schools/{id}/view', [AdminDashboardController::class, 'viewSchool'])->name('central.schools.view');
    
    // API Routes for School Management (Production Ready)
    Route::prefix('api')->group(function () {
        Route::post('/schools', [AdminDashboardController::class, 'storeSchool'])->name('central.schools.store');
        Route::delete('/schools/{id}', [AdminDashboardController::class, 'deleteSchool'])->name('central.schools.delete');
        Route::put('/schools/{id}', [AdminDashboardController::class, 'updateSchool'])->name('central.schools.update');
        Route::get('/schools/{id}', [AdminDashboardController::class, 'getSchool'])->name('central.schools.get');
        Route::post('/schools/{id}/toggle-lock', [AdminDashboardController::class, 'toggleLock'])->name('central.schools.toggle-lock');
        
        // Admin Management Routes
        Route::post('/schools/{id}/create-admin', [AdminDashboardController::class, 'createAdmin'])->name('central.schools.create-admin');
        Route::get('/schools/{tenantId}/admins', [AdminDashboardController::class, 'getAdmins'])->name('central.schools.admins');
        Route::delete('/schools/{tenantId}/admins/{adminId}', [AdminDashboardController::class, 'deleteAdmin'])->name('central.schools.delete-admin');
        Route::post('/schools/{tenantId}/admins/{adminId}/reset-password', [AdminDashboardController::class, 'resetAdminPassword'])->name('central.schools.reset-password');
        
        // Admission Application Routes
        Route::get('/schools/{tenantId}/applications/{applicationId}', [AdminDashboardController::class, 'getAdmissionApplication'])->name('central.schools.admission-application');
    });
    
    // School View Page
    Route::get('/schools/{id}/view', [AdminDashboardController::class, 'viewSchool'])->name('central.schools.view');
});

// শুধুমাত্র স্কুল মালিকদের (Clients) জন্য মেইন সাইটের প্যানেল
Route::middleware(['auth', 'client'])->prefix('client')->group(function () {
    Route::get('/dashboard', [ClientPanelController::class, 'index'])->name('client.dashboard');
    Route::get('/billing', [ClientPanelController::class, 'billing'])->name('client.billing');
    Route::post('/payment', [ClientPanelController::class, 'makePayment'])->name('client.payment');
});

// CSRF Test Routes (Development Only)
if (app()->environment('local')) {
    Route::get('/test-csrf-token', function () {
        return view('test-csrf-token');
    });
    
    Route::post('/test-csrf', function () {
        return response()->json(['status' => 'success', 'message' => 'CSRF token is working!']);
    });
}
