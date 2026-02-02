<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'school_name' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'string', 'max:50', 'unique:tenants,id', 'alpha_dash'],
            'domain' => ['required', 'string', 'max:100', 'unique:domains,domain', 'alpha_dash'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        try {
            // Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'client', // নতুন ইউজার ডিফল্ট client হবে
            ]);

            // Create Tenant (School) for the new user
            $tenant = \App\Models\Tenant::create([
                'id' => $request->school_id
            ]);

            // Create Domain with subdomain format
            $tenant->domains()->create([
                'domain' => $request->domain . '.smartpathshala.test'
            ]);

            // Store school information
            $tenant->data = [
                'school_name' => $request->school_name,
                'owner_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'user_id' => $user->id,
                'created_at' => now()
            ];
            $tenant->save();

            event(new Registered($user));

            Auth::login($user);

            // Redirect to client dashboard
            return redirect()->route('client.dashboard')->with('success', 'আপনার স্কুল সফলভাবে তৈরি হয়েছে!');

        } catch (\Exception $e) {
            // If tenant creation fails, delete the user
            if (isset($user)) {
                $user->delete();
            }
            
            return back()->withErrors(['error' => 'রেজিস্ট্রেশন করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।'])->withInput();
        }
    }
}
