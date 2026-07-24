<?php

namespace App\Livewire;

use App\Imports\PropertyRegistrationSheetImport;
use App\Models\UserProperty;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Title('Properties')]
class UserProperties extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $upload;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $type = 'all';

    #[Url(history: true)]
    public string $status = 'all';

    // Manual "Add Property" form fields.
    public string $newAcctNo = '';
    public string $newNameOfAccount = '';
    public string $newAccountCode = '';
    public string $newType = 'Land';
    public string $newLotNo = '';
    public string $newBrgyName = '';
    public string $newLgu = '';
    public string $newDateOfRegistration = '';
    public string $newStatus = 'active';
    public string $newEmail = '';

    protected $rules = [
        'upload' => 'required|file|mimes:xlsx,xls|max:10240',
    ];

    protected function addPropertyRules(): array
    {
        return [
            'newAcctNo' => ['required', 'integer'],
            'newNameOfAccount' => ['required', 'string', 'max:255'],
            'newAccountCode' => ['required', 'string', 'max:255'],
            'newType' => ['required', 'in:Land,Impr/Bldg'],
            'newLotNo' => ['nullable', 'string', 'max:255'],
            'newBrgyName' => ['required', 'string', 'max:255'],
            'newLgu' => ['required', 'string', 'max:255'],
            'newDateOfRegistration' => ['required', 'date'],
            'newStatus' => ['required', 'string', 'max:255'],
            'newEmail' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'type', 'status');
        $this->resetPage();
    }

    public function importUpload()
    {
        $this->validate();

        $import = new PropertyRegistrationSheetImport;
        $import->import($this->upload->getRealPath());

        $path = $this->upload->store('uploads/property-registrations');

        $this->reset('upload');
        $this->resetPage();
        $this->dispatch('$refresh');
        $this->dispatch('property-imported');

        session()->flash('status', "Imported {$import->imported} row(s). Saved to {$path}.");

        if (!empty($import->duplicates)) {
            $count = count($import->duplicates);
            $examples = collect($import->duplicates)->take(5)->map(fn ($d) => "{$d['account_code']} ({$d['type']})")->implode(', ');

            session()->flash(
                'duplicate_warning',
                "{$count} duplicate row(s) skipped: {$examples}" . ($count > 5 ? '...' : '')
            );
        }

        if (!empty($import->invalid)) {
            $count = count($import->invalid);
            $examples = collect($import->invalid)->take(5)->map(fn ($d) => "row {$d['row']}: ".implode('; ', $d['errors']))->implode(' | ');

            session()->flash(
                'invalid_warning',
                "{$count} row(s) skipped for missing/invalid required fields: {$examples}" . ($count > 5 ? '...' : '')
            );
        }
    }

    public function addProperty()
    {
        $validated = $this->validate($this->addPropertyRules());

        $duplicate = UserProperty::where('account_code', $validated['newAccountCode'])
            ->where('type', $validated['newType'])
            ->exists();

        if ($duplicate) {
            $this->addError('newAccountCode', __('A property with this account code and type already exists.'));
            return;
        }

        $email = trim($validated['newEmail']) !== '' ? trim($validated['newEmail']) : null;

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $email) {
            if ($email !== null) {
                // An email may only be linked to one account number at a time.
                UserProperty::where('acct_email_address', $email)
                    ->where('acct_no', '!=', $validated['newAcctNo'])
                    ->update(['acct_email_address' => null]);
            }

            UserProperty::create([
                'acct_email_address' => $email,
                'acct_no' => $validated['newAcctNo'],
                'name_of_account' => $validated['newNameOfAccount'],
                'account_code' => $validated['newAccountCode'],
                'type' => $validated['newType'],
                'lot_no' => $validated['newLotNo'] !== '' ? $validated['newLotNo'] : null,
                'brgy_name' => $validated['newBrgyName'],
                'lgu' => $validated['newLgu'],
                'date_of_registration' => $validated['newDateOfRegistration'],
                'status' => $validated['newStatus'],
            ]);

            if ($email !== null) {
                $user = \App\Models\User::where('email', $email)->first();

                if ($user) {
                    // Only one user may be linked to this acct_no.
                    \App\Models\User::where('acct_no', $validated['newAcctNo'])
                        ->where('id', '!=', $user->id)
                        ->update(['acct_no' => null]);

                    $user->update([
                        'acct_no' => $validated['newAcctNo'],
                        'name' => $validated['newNameOfAccount'],
                        'name_of_account' => $validated['newNameOfAccount'],
                    ]);
                }
            }
        });

        $this->reset([
            'newAcctNo', 'newNameOfAccount', 'newAccountCode', 'newLotNo',
            'newBrgyName', 'newLgu', 'newDateOfRegistration', 'newEmail',
        ]);
        $this->newType = 'Land';
        $this->newStatus = 'active';

        $this->resetPage();
        $this->dispatch('$refresh');
        $this->dispatch('property-added');

        session()->flash('status', __('Property added successfully.'));
    }

    #[Computed]
    public function typeOptions()
    {
        return UserProperty::query()
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');
    }

    #[Computed]
    public function statusOptions()
    {
        return UserProperty::query()
            ->whereNotNull('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');
    }

    #[Computed]
    public function stats()
    {
        return [
            'total' => UserProperty::count(),
            'active' => UserProperty::where('status', 'active')->count(),
            'needsEmail' => UserProperty::where(function ($q) {
                $q->whereNull('acct_email_address')->orWhere('acct_email_address', '');
            })->count(),
        ];
    }

    public function render()
    {
        $userPropertiesData = UserProperty::query()
            ->when($this->search !== '', function ($query) {
                $term = "%{$this->search}%";
                $query->where(function ($q) use ($term) {
                    $q->where('name_of_account', 'like', $term)
                        ->orWhere('account_code', 'like', $term)
                        ->orWhere('acct_no', 'like', $term)
                        ->orWhere('acct_email_address', 'like', $term)
                        ->orWhere('brgy_name', 'like', $term)
                        ->orWhere('lgu', 'like', $term);
                });
            })
            ->when($this->type !== 'all', fn ($query) => $query->where('type', $this->type))
            ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
            ->orderByDesc('date_of_registration')
            ->paginate(10);

        return view('livewire.user-properties-table', [
            'userPropertiesData' => $userPropertiesData,
        ])->layout('layouts.admin', [
            'title' => 'Properties',
            'pendingCount' => $this->pendingAccountsCount(),
        ]);
    }

    protected function pendingAccountsCount(): int
    {
        return \App\Models\User::where('role', '!=', 'admin')->where('status', 'pending')->count();
    }
}