<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class RegistrationController extends Controller
{
    public function showRegisterForm()
    {
        return view('central.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'school_id' => 'required|alpha|unique:tenants,id', // iqra, school1 etc
            'email' => 'required|email'
        ]);

        // ১. অটোমেটিক টেন্যান্ট এবং ডাটাবেস তৈরি
        $tenant = Tenant::create([
            'id' => $request->school_id,
            'email' => $request->email,
        ]);

        // ২. ডোমেইন সেটআপ
        $tenant->domains()->create([
            'domain' => $request->school_id . '.smartpathshala.test'
        ]);

        return "অভিনন্দন! আপনার স্কুলের ওয়েবসাইট তৈরি হয়ে গেছে: " . $request->school_id . ".smartpathshala.test";
    }
}
