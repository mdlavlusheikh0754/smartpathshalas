<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class ClientPanelController extends Controller
{
    public function index()
    {
        // বর্তমান ইউজারের স্কুলগুলো দেখানো
        $tenants = Tenant::with('domains')->where('user_id', auth()->id())->latest()->get();
        
        return view('central.client.dashboard', compact('tenants'));
    }

    public function billing()
    {
        // বিলিং ইনফরমেশন
        $tenants = Tenant::with('domains')->where('user_id', auth()->id())->latest()->get();
        
        return view('central.client.billing', compact('tenants'));
    }

    public function makePayment(Request $request)
    {
        // পেমেন্ট প্রসেসিং লজিক এখানে যুক্ত করুন
        
        return redirect()->route('client.billing')->with('success', 'পেমেন্ট সফলভাবে সম্পন্ন হয়েছে!');
    }
}
