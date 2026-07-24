<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserProperty;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Account')]
class UserEmailUpdate extends Component
{
    public int $acctNo;

    public ?string $accountName = null;

    public string $email = '';

    public function mount(int $acctNo): void
    {
        $this->acctNo = $acctNo;

        $property = UserProperty::where('acct_no', $acctNo)->first();
        $this->accountName = $property->name_of_account ?? null;

        $this->email = $property->acct_email_address ?? '';
    }

    public function getMatchingEmailsProperty()
    {
        if (strlen($this->email) < 2) {
            return collect();
        }

        return User::where('email', 'like', "%{$this->email}%")
            ->orderBy('email')
            ->limit(10)
            ->pluck('email');
    }

    public function selectEmail(string $email): void
    {
        $this->email = $email;
    }

    public function update()
    {
        $this->validate(['email' => 'required|email']);

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', __('No registered user found with that email.'));
            return;
        }

        DB::transaction(function () use ($user) {
            // Only one user account may be linked to this acct_no.
            User::where('acct_no', $this->acctNo)->where('id', '!=', $user->id)->update(['acct_no' => null]);

            // An email may only be linked to one acct_no at a time. If this
            // email was previously linked to a different account, unlink it
            // there first so we never end up with the same email attached
            // to more than one account number.
            UserProperty::where('acct_email_address', $this->email)
                ->where('acct_no', '!=', $this->acctNo)
                ->update(['acct_email_address' => null]);

            // The account holder's name comes from the imported property
            // spreadsheet (name_of_account), not from what the user typed at
            // registration.
            $user->update([
                'acct_no' => $this->acctNo,
                'name' => $this->accountName ?? $user->name,
                'name_of_account' => $this->accountName,
            ]);

            UserProperty::where('acct_no', $this->acctNo)->update(['acct_email_address' => $this->email]);
        });

        $affected = UserProperty::where('acct_no', $this->acctNo)->count();

        session()->flash('status', "Linked {$this->email} to account #{$this->acctNo} and updated {$affected} property record(s).");
    }

    public function render()
    {
        return view('livewire.user-email-update')->layout('layouts.admin', [
            'title' => 'Account #'.$this->acctNo,
            'pendingCount' => User::where('role', '!=', 'admin')->where('status', 'pending')->count(),
        ]);
    }
}