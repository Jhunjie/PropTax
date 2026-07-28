<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'email' => $this->emailRules(),
            'tin' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        // Name is intentionally omitted — it is populated later from the
        // matching property record once an admin links this email to an
        // account (see UserEmailUpdate).
        return User::create([
            'email' => $input['email'],
            'tin' => $input['tin'],
            'password' => $input['password'],
        ]);
    }
}
