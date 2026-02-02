<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // Check if we're in tenant context
        if (tenancy()->initialized) {
            return view('tenant.auth.login');
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        // Check if we're in tenant context
        if (tenancy()->initialized) {
            // Redirect to tenant dashboard
            return redirect()->route('tenant.dashboard');
        }

        // ১. যদি আপনি সুপার এডমিন হন
        if ($user->isAdmin()) {
            return redirect()->route('central.dashboard');
        }

        // ২. যদি সে স্কুল মালিক হয়, তাকে সরাসরি মেইন সাইটের "ক্লায়েন্ট ড্যাশবোর্ডে" পাঠান
        // সেখান থেকে সে তার পেমেন্ট দেখবে এবং তার স্কুলের লিঙ্কে ক্লিক করবে
        return redirect()->route('client.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if (tenancy()->initialized) {
            return redirect()->route('tenant.home');
        }

        return redirect('/');
    }
}
