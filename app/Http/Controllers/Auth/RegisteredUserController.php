<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'register')
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $validated = $validator->validated();

        $user = User::create([
            // No name is collected at registration. This is a placeholder
            // only; the real name is populated automatically from the
            // imported property spreadsheet once an admin links this email
            // to an account (see UserEmailUpdate::update()).
            'name' => (string) Str::of($validated['email'])->before('@')->replace(['.', '_', '-'], ' ')->headline(),
            'email' => $validated['email'],
            'tin' => $validated['tin'],
            'password' => Hash::make($validated['password']),
            'status' => User::STATUS_PENDING,
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('status', __('Your account has been created and is pending approval. You will be notified once it is reviewed.'));
    }
}