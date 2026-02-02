<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        $notifications = $student->notifications()->latest()->limit(10)->get();
        return view('tenant.student.dashboard', compact('student', 'notifications'));
    }

    public function library()
    {
        $student = Auth::guard('student')->user();
        $issues = $student->bookIssues()->with('book')->latest()->get();
        return view('tenant.student.library', compact('student', 'issues'));
    }

    public function hostel()
    {
        $student = Auth::guard('student')->user();
        $allocation = $student->hostelAllocation()->with(['room.hostel'])->first();
        return view('tenant.student.hostel', compact('student', 'allocation'));
    }

    public function transport()
    {
        $student = Auth::guard('student')->user();
        $allocation = $student->transportAllocation()->with(['route', 'vehicle'])->first();
        return view('tenant.student.transport', compact('student', 'allocation'));
    }
}
