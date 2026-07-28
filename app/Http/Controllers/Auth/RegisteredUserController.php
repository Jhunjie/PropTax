<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('welcome');
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'tin' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'privacy_policy' => ['accepted'],
        ], [
            'privacy_policy.accepted' => 'You must agree to the Privacy Policy to create an account.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'register')
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $validated = $validator->validated();

        // Name is intentionally left blank here. It gets filled in from the
        // matching property record's "name of account" once an admin links
        // this email to a property (see UserEmailUpdate), so we don't rely
        // on the resident to type it correctly at signup.
        $user = User::create([
            'email' => $validated['email'],
            'tin' => $validated['tin'],
            'password' => Hash::make($validated['password']),
            'status' => User::STATUS_PENDING,
            'privacy_accepted_at' => now(),
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('status', __("We've sent a verification link to your email. Verify it, then log in — your account will also need admin approval before you can view your properties."));
    }
}