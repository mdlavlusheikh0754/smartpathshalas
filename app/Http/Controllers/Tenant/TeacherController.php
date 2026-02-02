<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return view('tenant.teachers.index');
    }

    public function create()
    {
        return view('tenant.teachers.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('tenant.teachers.index')->with('success', 'শিক্ষক সফলভাবে যোগ করা হয়েছে');
    }

    public function show($id)
    {
        return view('tenant.teachers.show', compact('id'));
    }

    public function edit($id)
    {
        return view('tenant.teachers.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('tenant.teachers.index')->with('success', 'শিক্ষক সফলভাবে আপডেট করা হয়েছে');
    }

    public function destroy($id)
    {
        return redirect()->route('tenant.teachers.index')->with('success', 'শিক্ষক সফলভাবে মুছে ফেলা হয়েছে');
    }
}
