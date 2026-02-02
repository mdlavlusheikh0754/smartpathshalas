<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('tenant.reports.index');
    }

    public function students()
    {
        return view('tenant.reports.students');
    }

    public function attendance()
    {
        return view('tenant.reports.attendance');
    }

    public function fees()
    {
        return view('tenant.reports.fees');
    }

    public function exams()
    {
        return view('tenant.reports.exams');
    }
}
