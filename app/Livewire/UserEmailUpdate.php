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
            User::where('acct_no', $this->acctNo)->where('id', '!=', $user->id)->update(['acct_no' => null]);

            $user->update(['acct_no' => $this->acctNo, 'name_of_account' => $this->accountName,]);

            UserProperty::where('acct_no', $this->acctNo)->update(['acct_email_address' => $this->email]);
        });

        $affected = UserProperty::where('acct_no', $this->acctNo)->count();

        session()->flash('status', "Linked {$this->email} to account #{$this->acctNo} and updated {$affected} property record(s).");
    }

    public function render()
    {
        return view('livewire.user-email-update');
    }
}