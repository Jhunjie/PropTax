{{--
  Admin dashboard overview.

  $stats           ['pendingAccounts', 'approvedAccounts', 'totalProperties', 'unlinkedProperties']
  $recentAccounts  Latest 5 non-admin User rows (newest first)
  $pendingCount    Same as $stats['pendingAccounts'] — used for the sidebar badge
--}}
<x-layouts::admin title="Overview" :pending-count="$pendingCount">

    <div style="margin-bottom:32px;">
        <h1 style="font-size:26px;">Admin overview</h1>
        <p style="color:var(--ink-soft); font-size:14.5px; margin-top:6px;">What needs attention right now.</p>
    </div>

    <div class="stat-grid" style="margin-bottom:36px;">
        <a href="{{ route('accounts.approvals') }}" class="stat-card">
            <div class="lbl">Pending accounts</div>
            <div class="val {{ $stats['pendingAccounts'] > 0 ? 'warn' : '' }}">{{ number_format($stats['pendingAccounts']) }}</div>
        </a>
        <a href="{{ route('accounts.approvals') }}" class="stat-card">
            <div class="lbl">Approved accounts</div>
            <div class="val">{{ number_format($stats['approvedAccounts']) }}</div>
        </a>
        <a href="{{ route('user-properties-table') }}" class="stat-card">
            <div class="lbl">Total properties</div>
            <div class="val">{{ number_format($stats['totalProperties']) }}</div>
        </a>
        <a href="{{ route('user-properties-table') }}" class="stat-card">
            <div class="lbl">Needs email link</div>
            <div class="val {{ $stats['unlinkedProperties'] > 0 ? 'warn' : '' }}">{{ number_format($stats['unlinkedProperties']) }}</div>
        </a>
    </div>

    <div class="action-grid" style="margin-bottom:40px;">
        <div class="action-card">
            <div class="icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c0-3 2.5-5 5.5-5s5.5 2 5.5 5"/><circle cx="17.5" cy="9" r="2.2"/><path d="M15 19c.2-2.2 1.8-3.8 3.8-3.8"/></svg>
            </div>
            <h2 style="font-size:17px;">Review accounts</h2>
            <p>Approve or reject residents who verified in person at the office.</p>
            <a href="{{ route('accounts.approvals') }}" class="btn-primary">Go to approvals</a>
        </div>
        <div class="action-card">
            <div class="icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/></svg>
            </div>
            <h2 style="font-size:17px;">Manage properties</h2>
            <p>Upload the registration spreadsheet and link properties to owner emails.</p>
            <a href="{{ route('user-properties-table') }}" class="btn-primary">Go to properties</a>
        </div>
    </div>

    <div>
        <h2 style="font-size:17px; margin-bottom:14px;">Recently registered</h2>

        @if ($recentAccounts->isEmpty())
            <div class="panel">
                <div class="empty-state">No resident accounts yet.</div>
            </div>
        @else
            <div class="panel">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentAccounts as $account)
                            <tr>
                                <td style="font-weight:500;">{{ $account->name }}</td>
                                <td>{{ $account->email }}</td>
                                <td>{{ $account->created_at?->format('M d, Y') ?? '—' }}</td>
                                <td>
                                    <span class="pill {{ match ($account->status) { 'approved' => 'green', 'rejected' => 'rust', default => 'amber' } }}">
                                        {{ ucfirst($account->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layouts::admin>
