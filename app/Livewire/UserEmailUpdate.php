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

        // An email may only be linked to one account number at a time. If
        // this user is already linked elsewhere, require the admin to
        // unlink that account first rather than silently overwriting it.
        if ($user->acct_no !== null && (int) $user->acct_no !== (int) $this->acctNo) {
            $this->addError('email', __('This email is already linked to account #:acctNo. Unlink it from that account before linking a new one.', ['acctNo' => $user->acct_no]));
            return;
        }

        DB::transaction(function () use ($user) {
            // If this account number was previously linked to a different
            // user, that link is superseded — clear it so an account number
            // is never linked to more than one email at a time.
            User::where('acct_no', $this->acctNo)->where('id', '!=', $user->id)->update(['acct_no' => null]);

            $user->update([
                'acct_no' => $this->acctNo,
                'name_of_account' => $this->accountName,
                // The resident's name isn't collected at registration —
                // it's populated here from the property record the first
                // time an admin links (verifies) the account.
                'name' => $user->name ?: $this->accountName,
            ]);

            UserProperty::where('acct_no', $this->acctNo)->update(['acct_email_address' => $this->email]);
        });

        $affected = UserProperty::where('acct_no', $this->acctNo)->count();

        session()->flash('status', "Linked {$this->email} to account #{$this->acctNo} and updated {$affected} property record(s).");
    }

    /**
     * Detach this email from the account number so it can be linked to a
     * different one. Only affects the User record's link — the underlying
     * property rows keep their acct_email_address until relinked.
     */
    public function unlink(): void
    {
        $user = User::where('acct_no', $this->acctNo)->first();

        if ($user) {
            $user->update(['acct_no' => null]);
        }

        session()->flash('status', __('Account #:acctNo was unlinked.', ['acctNo' => $this->acctNo]));
    }

    public function render()
    {
        return view('livewire.user-email-update')->layout('layouts.admin', [
            'title' => 'Account #'.$this->acctNo,
            'pendingCount' => User::where('role', '!=', 'admin')->where('status', 'pending')->count(),
        ]);
    }
}