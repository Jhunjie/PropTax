{{--
  Resident dashboard.

  $user        Auth::user() — ->name, ->email, ->tin, ->acct_no, ->status ('pending'|'approved'|'rejected')
  $properties  UserProperty rows linked to this user (matched on email by an admin), each with:
                 ->id, ->account_code, ->type, ->lot_no, ->brgy_name, ->lgu,
                 ->date_of_registration, ->status
--}}
<x-layouts::resident :title="__('Dashboard')">

    @if ($user->status === 'pending')

        <div class="notice-page">
            <div class="notice-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
            </div>
            <h1 style="font-size:22px;">{{ __('Your account is awaiting verification') }}</h1>
            <p>{{ __('Thanks for registering, :name. Please visit the admin office in person to verify your details — once approved, your linked properties will show up here.', ['name' => $user->name]) }}</p>
            <p>{{ __("We'll email you as soon as your account is approved or rejected.") }}</p>
        </div>

    @elseif ($user->status === 'rejected')

        <div class="notice-page">
            <div class="notice-icon rust">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M9 9l6 6M15 9l-6 6"/></svg>
            </div>
            <h1 style="font-size:22px;">{{ __('Your account was not approved') }}</h1>
            <p>{{ __('Please visit the admin office for details on why your verification was rejected and what to do next.') }}</p>
        </div>

    @else

        <div>
            <h1 style="font-size:26px;">{{ __('Welcome back, :name', ['name' => explode(' ', $user->name)[0]]) }}</h1>
            <p style="color:var(--ink-soft); font-size:14.5px; margin-top:6px; margin-bottom:28px;">
                {{ __('Your account and the properties linked to it.') }}
            </p>

            {{-- Account info --}}
            <div class="panel" style="margin-bottom:24px;">
                <div class="panel-head">
                    <h2 style="font-size:16px;">{{ __('Your account') }}</h2>
                    <span class="pill green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11" height="11"><path d="M5 12.5l4.5 4.5L19 7"/></svg>
                        {{ __('Verified') }}
                    </span>
                </div>
                <div class="info-grid">
                    <div>
                        <div class="lbl">{{ __('Name') }}</div>
                        <div class="value">{{ $user->name }}</div>
                    </div>
                    <div>
                        <div class="lbl">{{ __('Email') }}</div>
                        <div class="value">{{ $user->email }}</div>
                    </div>
                    <div>
                        <div class="lbl">{{ __('TIN') }}</div>
                        <div class="value mono">{{ $user->tin ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Stat strip --}}
            <div class="stat-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom:32px;">
                <div class="stat-card" style="cursor:default;">
                    <div class="lbl">{{ __('Properties linked') }}</div>
                    <div class="val">{{ $properties->count() }}</div>
                </div>
                <div class="stat-card" style="cursor:default;">
                    <div class="lbl">{{ __('Active') }}</div>
                    <div class="val">{{ $properties->where('status', 'active')->count() }}</div>
                </div>
                <div class="stat-card" style="cursor:default;">
                    <div class="lbl">{{ __('Inactive') }}</div>
                    <div class="val">{{ $properties->where('status', '!=', 'active')->count() }}</div>
                </div>
            </div>

            {{-- Properties --}}
            <div>
                <h2 style="font-size:17px; margin-bottom:14px;">{{ __('Your properties') }}</h2>

                @if ($properties->isEmpty())
                    <div class="panel">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/></svg>
                            <p>{{ __("No properties linked yet. An administrator will link the properties you own to your account once they're uploaded and matched to your email.") }}</p>
                        </div>
                    </div>
                @else
                    <div class="panel">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Property') }}</th>
                                    <th>{{ __('Location') }}</th>
                                    <th>{{ __('Account code') }}</th>
                                    <th>{{ __('Registered') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($properties as $property)
                                    <tr>
                                        <td>
                                            <div class="row-flex">
                                                <div class="row-icon">
                                                    @if (str_contains(strtolower($property->type), 'bldg') || str_contains(strtolower($property->type), 'build'))
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="M9 8h.01M15 8h.01M9 12h.01M15 12h.01M9 16h.01M15 16h.01"/></svg>
                                                    @else
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M9 4L3 6.5v14L9 18l6 2.5 6-2.5v-14L15 6.5 9 4z"/><path d="M9 4v14M15 6.5v14"/></svg>
                                                    @endif
                                                </div>
                                                <span style="font-weight:500;">
                                                    {{ $property->type }}{{ $property->lot_no ? ', Lot '.$property->lot_no : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>{{ $property->brgy_name }}, {{ $property->lgu }}</td>
                                        <td class="mono" style="font-size:12.5px;">{{ $property->account_code }}</td>
                                        <td>{{ optional($property->date_of_registration)->format('M d, Y') ?? '—' }}</td>
                                        <td>
                                            <span class="pill {{ $property->status === 'active' ? 'green' : 'zinc' }}">
                                                {{ ucfirst($property->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    @endif

</x-layouts::resident>
