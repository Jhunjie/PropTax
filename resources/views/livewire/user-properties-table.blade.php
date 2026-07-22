<div>
    <div class="mb-4 flex items-center justify-between gap-4">
        <flux:input
            type="file"
            wire:model="upload"
            accept=".xlsx,.xls"
            label="{{ __('Upload Property Registration (Excel)') }}"
        />
        <flux:button wire:click="importUpload" variant="primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="importUpload">{{ __('Upload') }}</span>
            <span wire:loading wire:target="importUpload">{{ __('Processing...') }}</span>
        </flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" class="mb-4">{{ session('status') }}</flux:callout>
    @endif

    @error('upload')
        <flux:callout variant="danger" class="mb-4">{{ $message }}</flux:callout>
    @enderror

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Email Address') }}</flux:table.column>
            <flux:table.column>{{ __('Account No') }}</flux:table.column>
            <flux:table.column>{{ __('Account Name') }}</flux:table.column>
            <flux:table.column>{{ __('Account Code') }}</flux:table.column>
            <flux:table.column>{{ __('Type') }}</flux:table.column>
            <flux:table.column>{{ __('Lot No') }}</flux:table.column>
            <flux:table.column>{{ __('Brgy Name') }}</flux:table.column>
            <flux:table.column>{{ __('LGU') }}</flux:table.column>
            <flux:table.column>{{ __('Registration Date') }}</flux:table.column>
            <flux:table.column>{{ __('Status') }}</flux:table.column>
            <flux:table.column>{{ __('Actions') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($userPropertiesData as $property)
                <flux:table.row wire:key="property-{{ $property->id }}">
                    <flux:table.cell>{{ $property->acct_email_address }}</flux:table.cell>
                    <flux:table.cell>{{ $property->acct_no }}</flux:table.cell>
                    <flux:table.cell>{{ $property->name_of_account }}</flux:table.cell>
                    <flux:table.cell>{{ $property->account_code }}</flux:table.cell>
                    <flux:table.cell>{{ $property->type }}</flux:table.cell>
                    <flux:table.cell>{{ $property->lot_no }}</flux:table.cell>
                    <flux:table.cell>{{ $property->brgy_name }}</flux:table.cell>
                    <flux:table.cell>{{ $property->lgu }}</flux:table.cell>
                    <flux:table.cell>{{ $property->date_of_registration->format('M d, Y') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$property->status === 'active' ? 'green' : 'zinc'">
                            {{ $property->status }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>

                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="8" class="text-center text-zinc-500">
                        {{ __('No data found.') }}
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <div class="mt-4">
        {{ $userPropertiesData->links() }}
    </div>
</div>