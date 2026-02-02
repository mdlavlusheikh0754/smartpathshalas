<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardianDashboardController extends Controller
{
    public function index()
    {
        $guardian = Auth::guard('guardian')->user()->load(['students.attendances', 'students.bookIssues', 'notifications' => function($query) {
            $query->latest()->limit(10);
        }]);
        
        // Get recent notices
        $notices = \App\Models\Notice::latest()->limit(5)->get();
        
        // Get students IDs for queries
        $studentIds = $guardian->students->pluck('id')->toArray();
        
        return view('tenant.guardian.dashboard', compact('guardian', 'notices'));
    }
}
