<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardianAuthController extends Controller
{
    public function create()
    {
        return view('tenant.auth.guardian-login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('guardian')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('guardian.dashboard'));
        }

        return back()->withErrors([
            'phone' => 'প্রদত্ত তথ্য আমাদের রেকর্ডের সাথে মিলছে না।',
        ])->onlyInput('phone');
    }

    public function destroy(Request $request)
    {
        Auth::guard('guardian')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('guardian.login');
    }
}
