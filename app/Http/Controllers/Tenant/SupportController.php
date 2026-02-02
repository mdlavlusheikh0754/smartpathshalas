<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ContactMessage;

class SupportController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('tenant.support.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        if ($message->status === 'pending') {
            $message->update(['status' => 'read']);
        }
        return view('tenant.support.show', compact('message'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',            'phone' => 'nullable|string|max:20',            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return redirect()->back()->with('success', 'আপনার মেসেজটি সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('tenant.support.index')->with('success', 'মেসেজটি মুছে ফেলা হয়েছে।');
    }
}
