<div
    x-data="{ importOpen: false, addOpen: false, uploadProgress: 0, uploading: false }"
    x-on:property-imported.window="importOpen = false"
    x-on:property-added.window="addOpen = false"
    x-on:livewire-upload-start="uploading = true; uploadProgress = 0"
    x-on:livewire-upload-finish="uploading = false; uploadProgress = 100"
    x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-progress="uploadProgress = $event.detail.progress"
>

    {{-- Page header --}}
    <div class="page-head">
        <div>
            <h1 style="font-size:26px;">{{ __('Property registrations') }}</h1>
            <p>{{ __('Every property on record, and which ones still need an owner email linked.') }}</p>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="button" class="btn btn-ghost" x-on:click="addOpen = true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v14M5 12h14"/></svg>
                {{ __('Add property') }}
            </button>
            <button type="button" class="btn btn-dark" x-on:click="importOpen = true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 15V4M8 8l4-4 4 4"/><path d="M4 15v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
                {{ __('Import spreadsheet') }}
            </button>
        </div>
    </div>

    {{-- Stat strip --}}
    <div class="stat-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom:24px;">
        <div class="stat-card" style="cursor:default;">
            <div class="lbl">{{ __('Total properties') }}</div>
            <div class="val">{{ number_format($this->stats['total']) }}</div>
        </div>
        <div class="stat-card" style="cursor:default;">
            <div class="lbl">{{ __('Active') }}</div>
            <div class="val">{{ number_format($this->stats['active']) }}</div>
        </div>
        <div class="stat-card" style="cursor:default;">
            <div class="lbl">{{ __('Needs email link') }}</div>
            <div class="val {{ $this->stats['needsEmail'] > 0 ? 'warn' : '' }}">{{ number_format($this->stats['needsEmail']) }}</div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('status'))
        <div class="callout success" x-data="{ visible: true }" x-show="visible" x-transition>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.2l2.3 2.3 4.7-5"/></svg>
            <span>{{ session('status') }}</span>
            <button type="button" class="callout-close" x-on:click="visible = false" aria-label="{{ __('Dismiss') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    @endif

    @if (session('duplicate_warning'))
        <div class="callout warn" x-data="{ visible: true }" x-show="visible" x-transition>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9L2.6 17.5a1.6 1.6 0 0 0 1.4 2.4h16a1.6 1.6 0 0 0 1.4-2.4L13.7 3.9a1.6 1.6 0 0 0-2.8 0z"/></svg>
            <span>{{ session('duplicate_warning') }}</span>
            <button type="button" class="callout-close" x-on:click="visible = false" aria-label="{{ __('Dismiss') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    @endif

    @if (session('invalid_warning'))
        <div class="callout warn" x-data="{ visible: true }" x-show="visible" x-transition>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9L2.6 17.5a1.6 1.6 0 0 0 1.4 2.4h16a1.6 1.6 0 0 0 1.4-2.4L13.7 3.9a1.6 1.6 0 0 0-2.8 0z"/></svg>
            <span>{{ session('invalid_warning') }}</span>
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
                placeholder="{{ __('Search by name, account no, email, or barangay') }}"
            >
            @if ($search !== '')
                <button type="button" class="clear-x" wire:click="$set('search', '')" aria-label="{{ __('Clear search') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            @endif
        </div>

        <select wire:model.live="type" class="select-field">
            <option value="all">{{ __('All types') }}</option>
            @foreach ($this->typeOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>

        <select wire:model.live="status" class="select-field">
            <option value="all">{{ __('All statuses') }}</option>
            @foreach ($this->statusOptions as $option)
                <option value="{{ $option }}">{{ ucfirst($option) }}</option>
            @endforeach
        </select>

        @if ($search !== '' || $type !== 'all' || $status !== 'all')
            <button type="button" class="btn btn-ghost" wire:click="resetFilters">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
                {{ __('Clear') }}
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="panel">
        @if ($userPropertiesData->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/></svg>
                @if ($search !== '' || $type !== 'all' || $status !== 'all')
                    <p>{{ __('No properties match your search or filters.') }}</p>
                    <button type="button" class="btn btn-ghost btn-sm" wire:click="resetFilters">{{ __('Clear filters') }}</button>
                @else
                    <p>{{ __('No properties on record yet.') }}</p>
                    <button type="button" class="btn btn-ghost btn-sm" x-on:click="importOpen = true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 15V4M8 8l4-4 4 4"/><path d="M4 15v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
                        {{ __('Import a spreadsheet') }}
                    </button>
                @endif
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('Account') }}</th>
                        <th>{{ __('Location') }}</th>
                        <th>{{ __('Registered') }}</th>
                        <th>{{ __('Owner email') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userPropertiesData as $property)
                        <tr wire:key="property-{{ $property->id }}">
                            <td>
                                <div class="row-flex align-top">
                                    <div class="row-icon">
                                        @if (str_contains(strtolower($property->type), 'bldg') || str_contains(strtolower($property->type), 'build'))
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="M9 8h.01M15 8h.01M9 12h.01M15 12h.01M9 16h.01M15 16h.01"/></svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 4L3 6.5v14L9 18l6 2.5 6-2.5v-14L15 6.5 9 4z"/><path d="M9 4v14M15 6.5v14"/></svg>
                                        @endif
                                    </div>
                                    <div style="min-width:0;">
                                        <a
                                            href="{{ route('accounts.user-email-update', ['acctNo' => $property->acct_no]) }}"
                                            wire:navigate
                                            style="font-weight:500; text-decoration:none; color:var(--ink);"
                                            onmouseover="this.style.color='var(--green-deep)'"
                                            onmouseout="this.style.color='var(--ink)'"
                                        >
                                            {{ $property->name_of_account }}
                                        </a>
                                        <div class="row-sub">
                                            {{ __('Acct #:no', ['no' => $property->acct_no]) }} · {{ $property->account_code }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div>{{ $property->type }}{{ $property->lot_no ? ', Lot ' . $property->lot_no : '' }}</div>
                                <div class="row-sub">{{ $property->brgy_name }}, {{ $property->lgu }}</div>
                            </td>

                            <td>{{ $property->date_of_registration?->format('M d, Y') ?? '—' }}</td>

                            <td>
                                @if ($property->acct_email_address)
                                    <span style="font-size:13.5px;">{{ $property->acct_email_address }}</span>
                                @else
                                    <span class="pill amber">{{ __('Not linked') }}</span>
                                @endif
                            </td>

                            <td>
                                <span class="pill {{ $property->status === 'active' ? 'green' : 'zinc' }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>

                            <td class="actions-cell">
                                <a
                                    href="{{ route('accounts.user-email-update', ['acctNo' => $property->acct_no]) }}"
                                    wire:navigate
                                    class="btn btn-icon btn-ghost"
                                    title="{{ __('Manage owner email') }}"
                                    aria-label="{{ __('Manage owner email') }}"
                                >
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 17.25V20h2.75L17.8 8.94l-2.75-2.75L4 17.25zM19.7 6.04a1.2 1.2 0 0 0 0-1.7l-1.04-1.04a1.2 1.2 0 0 0-1.7 0l-1.3 1.3 2.75 2.75 1.3-1.3z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $userPropertiesData->links('pagination.admin') }}

    {{-- Import modal --}}
    <div class="modal-mask" x-show="importOpen" x-cloak x-on:click.self="importOpen = false" x-transition>
        <div class="modal-box">
            <h2 style="font-size:18px; margin-bottom:6px;">{{ __('Import property registrations') }}</h2>
            <p style="color:var(--ink-soft); font-size:13.5px; line-height:1.55; margin-bottom:18px;">
                {{ __('Upload the registration spreadsheet (.xlsx or .xls). Both the standard template and the no-email variant are accepted — rows already on file are skipped automatically.') }}
            </p>

            <div class="field">
                <label>{{ __('Spreadsheet file') }}</label>
                <input type="file" wire:model="upload" accept=".xlsx,.xls">
                @error('upload')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div x-show="uploading" x-cloak class="progress-bar" role="progressbar" :aria-valuenow="uploadProgress" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar-fill" :style="`width: ${uploadProgress}%`"></div>
            </div>
            <div x-show="uploading" x-cloak class="loading-note" x-text="`{{ __('Uploading…') }} ${uploadProgress}%`"></div>

            <div wire:loading wire:target="importUpload" class="loading-note">
                <div class="progress-bar progress-bar-indeterminate">
                    <div class="progress-bar-fill"></div>
                </div>
                {{ __('Processing spreadsheet…') }}
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" x-on:click="importOpen = false">{{ __('Cancel') }}</button>
                <button
                    type="button"
                    wire:click="importUpload"
                    wire:loading.attr="disabled"
                    wire:target="importUpload"
                    class="btn btn-dark"
                >
                    <span wire:loading.remove wire:target="importUpload">{{ __('Import') }}</span>
                    <span wire:loading wire:target="importUpload">{{ __('Processing…') }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Add property modal --}}
    <div class="modal-mask" x-show="addOpen" x-cloak x-on:click.self="addOpen = false" x-transition>
        <div class="modal-box">
            <h2 style="font-size:18px; margin-bottom:6px;">{{ __('Add a property') }}</h2>
            <p style="color:var(--ink-soft); font-size:13.5px; line-height:1.55; margin-bottom:18px;">
                {{ __('Manually register a single property. Linking an owner email here works the same as importing a spreadsheet — each email can only be linked to one account number.') }}
            </p>

            <div class="field">
                <label>{{ __('Account number') }}</label>
                <input type="text" inputmode="numeric" wire:model="newAcctNo">
                @error('newAcctNo') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Name on account') }}</label>
                <input type="text" wire:model="newNameOfAccount">
                @error('newNameOfAccount') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Account code') }}</label>
                <input type="text" wire:model="newAccountCode">
                @error('newAccountCode') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Type') }}</label>
                <select wire:model="newType">
                    <option value="Land">{{ __('Land') }}</option>
                    <option value="Impr/Bldg">{{ __('Impr/Bldg') }}</option>
                </select>
                @error('newType') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Lot no. (optional)') }}</label>
                <input type="text" wire:model="newLotNo">
                @error('newLotNo') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Barangay') }}</label>
                <input type="text" wire:model="newBrgyName">
                @error('newBrgyName') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('LGU') }}</label>
                <input type="text" wire:model="newLgu">
                @error('newLgu') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Date of registration') }}</label>
                <input type="date" wire:model="newDateOfRegistration">
                @error('newDateOfRegistration') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Status') }}</label>
                <input type="text" wire:model="newStatus" placeholder="active">
                @error('newStatus') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>{{ __('Owner email (optional)') }}</label>
                <input type="email" wire:model="newEmail" placeholder="owner@example.com">
                @error('newEmail') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" x-on:click="addOpen = false">{{ __('Cancel') }}</button>
                <button
                    type="button"
                    wire:click="addProperty"
                    wire:loading.attr="disabled"
                    wire:target="addProperty"
                    class="btn btn-dark"
                >
                    <span wire:loading.remove wire:target="addProperty">{{ __('Add property') }}</span>
                    <span wire:loading wire:target="addProperty">{{ __('Saving…') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }

.progress-bar{
    width:100%; height:8px; border-radius:999px;
    background:var(--surface-2, #F1F0EA);
    overflow:hidden; margin:10px 0 6px;
}
.progress-bar-fill{
    height:100%; border-radius:999px;
    background:var(--green, #0E6B52);
    transition:width .2s ease;
}
.progress-bar-indeterminate .progress-bar-fill{
    width:40%;
    animation:progress-indeterminate 1.2s ease-in-out infinite;
}
@keyframes progress-indeterminate{
    0%{ transform:translateX(-100%); }
    100%{ transform:translateX(250%); }
}
@media (prefers-reduced-motion: reduce){
    .progress-bar-indeterminate .progress-bar-fill{ animation:none; width:100%; }
}
</style>
