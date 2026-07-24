<div>

    {{-- Page header --}}
    <div class="page-head">
        <div>
            <h1 style="font-size:26px;">{{ __('Account approvals') }}</h1>
            <p>{{ __('Accounts wait here after a resident registers and verifies in person at the admin office.') }}</p>
        </div>
    </div>

    {{-- Stat strip / status filter --}}
    <div class="stat-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom:24px;">
        <button
            type="button"
            wire:click="$set('status', 'pending')"
            class="stat-card {{ $status === 'pending' ? 'is-active amber' : '' }}"
        >
            <div class="lbl">{{ __('Pending') }}</div>
            <div class="val {{ $this->stats['pending'] > 0 ? 'warn' : '' }}">{{ number_format($this->stats['pending']) }}</div>
        </button>
        <button
            type="button"
            wire:click="$set('status', 'approved')"
            class="stat-card {{ $status === 'approved' ? 'is-active green' : '' }}"
        >
            <div class="lbl">{{ __('Approved') }}</div>
            <div class="val">{{ number_format($this->stats['approved']) }}</div>
        </button>
        <button
            type="button"
            wire:click="$set('status', 'rejected')"
            class="stat-card {{ $status === 'rejected' ? 'is-active rust' : '' }}"
        >
            <div class="lbl">{{ __('Rejected') }}</div>
            <div class="val">{{ number_format($this->stats['rejected']) }}</div>
        </button>
    </div>

    {{-- Flash message --}}
    @if (session('status'))
        <div class="callout success" x-data="{ visible: true }" x-show="visible" x-transition>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.2l2.3 2.3 4.7-5"/></svg>
            <span>{{ session('status') }}</span>
            <button type="button" class="callout-close" x-on:click="visible = false" aria-label="{{ __('Dismiss') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    @endif

    {{-- Search + filters --}}
    <div class="filter-bar">
        <div class="search-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
            <input
                type="text"
                wire:model.live.debounce.400ms="search"
                placeholder="{{ __('Search by name, email, or TIN') }}"
            >
            @if ($search !== '')
                <button type="button" class="clear-x" wire:click="$set('search', '')" aria-label="{{ __('Clear search') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            @endif
        </div>

        <select wire:model.live="status" class="select-field">
            <option value="all">{{ __('All statuses') }}</option>
            <option value="pending">{{ __('Pending') }}</option>
            <option value="approved">{{ __('Approved') }}</option>
            <option value="rejected">{{ __('Rejected') }}</option>
        </select>

        @if ($search !== '' || $status !== 'pending')
            <button type="button" class="btn btn-ghost" wire:click="resetFilters">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                {{ __('Clear') }}
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="panel">
        @if ($accounts->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c0-3 2.5-5 5.5-5s5.5 2 5.5 5"/><circle cx="17.5" cy="9" r="2.2"/><path d="M15 19c.2-2.2 1.8-3.8 3.8-3.8"/></svg>
                @if ($search !== '' || $status !== 'all')
                    <p>{{ __('No accounts match your search or filters.') }}</p>
                    <button type="button" class="btn btn-ghost btn-sm" wire:click="resetFilters">{{ __('Clear filters') }}</button>
                @else
                    <p>{{ __('No accounts on record yet.') }}</p>
                @endif
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('TIN') }}</th>
                        <th>{{ __('Registered') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr wire:key="account-{{ $account->id }}">
                            <td>
                                <div class="row-flex">
                                    <div class="avatar-sm">{{ strtoupper(substr($account->name, 0, 1)) }}</div>
                                    <span style="font-weight:500;">{{ $account->name }}</span>
                                </div>
                            </td>
                            <td>{{ $account->email }}</td>
                            <td class="mono" style="font-size:12.5px;">{{ $account->tin }}</td>
                            <td>{{ $account->created_at?->format('M d, Y') ?? '—' }}</td>
                            <td>
                                <span class="pill {{ match ($account->status) { 'approved' => 'green', 'rejected' => 'rust', default => 'amber' } }}">
                                    {{ ucfirst($account->status) }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <div class="row-actions">
                                    @if ($account->status !== 'approved')
                                        <button
                                            type="button"
                                            wire:click="approve({{ $account->id }})"
                                            wire:confirm="{{ __('Approve :name\'s account?', ['name' => $account->name]) }}"
                                            class="btn btn-sm btn-green"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.5l4.5 4.5L19 7"/></svg>
                                            {{ __('Approve') }}
                                        </button>
                                    @endif

                                    @if ($account->status !== 'rejected')
                                        <button
                                            type="button"
                                            wire:click="reject({{ $account->id }})"
                                            wire:confirm="{{ __('Reject :name\'s account?', ['name' => $account->name]) }}"
                                            class="btn btn-sm btn-rust"
                                        >
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                                            {{ __('Reject') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $accounts->links('pagination.admin') }}
</div>
