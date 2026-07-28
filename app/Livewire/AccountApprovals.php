<?php

namespace App\Livewire;

use App\Models\User;
use App\Notifications\AccountStatusUpdated;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Account approvals')]
class AccountApprovals extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $status = 'pending';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search');
        $this->status = 'pending';
        $this->resetPage();
    }

    public function approve(int $userId): void
    {
        $this->setStatus($userId, 'approved');
    }

    public function reject(int $userId): void
    {
        $this->setStatus($userId, 'rejected');
    }

    protected function setStatus(int $userId, string $status): void
    {
        $user = User::where('role', '!=', 'admin')->findOrFail($userId);

        $user->update(['status' => $status]);

        $user->notify(new AccountStatusUpdated($status));

        $label = $user->name ?: $user->email;
        session()->flash('status', "{$label}'s account was marked as {$status}.");
    }

    #[Computed]
    public function stats()
    {
        return [
            'pending' => User::where('role', '!=', 'admin')->where('status', 'pending')->count(),
            'approved' => User::where('role', '!=', 'admin')->where('status', 'approved')->count(),
            'rejected' => User::where('role', '!=', 'admin')->where('status', 'rejected')->count(),
        ];
    }

    public function render()
    {
        $accounts = User::query()
            ->where('role', '!=', 'admin')
            ->when($this->search !== '', function ($query) {
                $term = "%{$this->search}%";
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('tin', 'like', $term);
                });
            })
            ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.account-approvals', [
            'accounts' => $accounts,
        ])->layout('layouts.admin', [
            'title' => 'Account approvals',
            'pendingCount' => $this->stats['pending'],
        ]);
    }
}
