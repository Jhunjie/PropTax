<div class="max-w-lg">
    <flux:heading size="lg" class="mb-1">{{ __('Account') }} #{{ $acctNo }}</flux:heading>
    <flux:text class="mb-6 text-zinc-500">{{ $accountName ?? __('No account name on file') }}</flux:text>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle" x-data="{ visible: true }" x-show="visible" x-transition class="mb-4">
            <flux:callout.text>{{ session('status') }}</flux:callout.text>
            <x-slot name="controls">
                <flux:button icon="x-mark" variant="ghost" size="sm" x-on:click="visible = false" aria-label="{{ __('Dismiss') }}" />
            </x-slot>
        </flux:callout>
    @endif

    <flux:field>
        <flux:label>{{ __('Email Address') }}</flux:label>

        <div class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
            <flux:input
                type="text"
                autocomplete="off"
                wire:model.live.debounce.300ms="email"
                x-on:focus="open = true"
                x-on:input="open = true"
                placeholder="{{ __('Start typing an email...') }}"
            />

            <div
                x-show="open"
                x-cloak
                class="absolute z-10 mt-1 w-full rounded-lg border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-800"
            >
                @forelse ($this->matchingEmails as $matchedEmail)
                    <button
                        type="button"
                        wire:click="selectEmail('{{ $matchedEmail }}')"
                        x-on:click="open = false"
                        class="block w-full px-3 py-2 text-left text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700 first:rounded-t-lg last:rounded-b-lg"
                    >
                        {{ $matchedEmail }}
                    </button>
                @empty
                    @if (strlen($email) >= 2)
                        <div class="px-3 py-2 text-sm text-zinc-500">
                            {{ __('No matching users found.') }}
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        <flux:error name="email" />
    </flux:field>

    <div class="mt-4 flex gap-2">
        <flux:button wire:click="update" variant="primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="update">{{ __('Update') }}</span>
            <span wire:loading wire:target="update">{{ __('Updating...') }}</span>
        </flux:button>

        <flux:button :href="route('user-properties-table')" variant="ghost" wire:navigate>
            {{ __('Back') }}
        </flux:button>
    </div>

    <flux:text class="mt-4 text-sm text-zinc-500">
        {{ __('Updating this email applies it to every property under account #:acctNo.', ['acctNo' => $acctNo]) }}
    </flux:text>
</div>