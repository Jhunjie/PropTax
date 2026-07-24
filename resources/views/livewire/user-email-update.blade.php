<div style="max-width:520px;">

    <a
        href="{{ route('user-properties-table') }}"
        wire:navigate
        style="display:inline-flex; align-items:center; gap:6px; font-size:13px; color:var(--ink-soft); text-decoration:none; margin-bottom:18px;"
    >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M15 18l-6-6 6-6"/></svg>
        {{ __('Back to properties') }}
    </a>

    <div class="page-head" style="margin-bottom:22px; align-items:flex-start;">
        <div>
            <h1 style="font-size:22px;">{{ __('Account') }} #{{ $acctNo }}</h1>
            <p>{{ $accountName ?? __('No account name on file') }}</p>
        </div>
    </div>

    @if (session('status'))
        <div class="callout success" x-data="{ visible: true }" x-show="visible" x-transition>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.2l2.3 2.3 4.7-5"/></svg>
            <span>{{ session('status') }}</span>
            <button type="button" class="callout-close" x-on:click="visible = false" aria-label="{{ __('Dismiss') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    @endif

    <div class="panel" style="padding:24px;">
        <div class="field combo" style="margin-bottom:0;" x-data="{ open: false }" x-on:click.outside="open = false">
            <label>{{ __('Email Address') }}</label>

            <input
                type="text"
                autocomplete="off"
                wire:model.live.debounce.300ms="email"
                x-on:focus="open = true"
                x-on:input="open = true"
                placeholder="{{ __('Start typing an email...') }}"
            >

            <div class="combo-menu" x-show="open" x-cloak>
                @forelse ($this->matchingEmails as $matchedEmail)
                    <button
                        type="button"
                        wire:click="selectEmail('{{ $matchedEmail }}')"
                        x-on:click="open = false"
                        class="combo-item"
                    >
                        {{ $matchedEmail }}
                    </button>
                @empty
                    @if (strlen($email) >= 2)
                        <div class="combo-empty">{{ __('No matching users found.') }}</div>
                    @endif
                @endforelse
            </div>

            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="button" wire:click="update" wire:loading.attr="disabled" wire:target="update" class="btn btn-dark">
                <span wire:loading.remove wire:target="update">{{ __('Update') }}</span>
                <span wire:loading wire:target="update">{{ __('Updating...') }}</span>
            </button>

            <a href="{{ route('user-properties-table') }}" wire:navigate class="btn btn-ghost">
                {{ __('Back') }}
            </a>
        </div>

        <p class="field-hint">
            {{ __('Updating this email applies it to every property under account #:acctNo.', ['acctNo' => $acctNo]) }}
        </p>
    </div>
</div>
