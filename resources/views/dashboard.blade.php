{{--
  Taxpayer dashboard.
  Expected data from the controller (fallback mock data below is only for previewing —
  replace by passing real data from DashboardController@index):

  $user        Auth::user() — expects ->name, ->status ('pending' | 'approved')
  $properties  Collection of objects with:
                 ->id, ->type ('Land'|'Building'), ->lot_no, ->barangay,
                 ->tax_year, ->amount_due, ->penalties, ->total_payable,
                 ->due_date, ->status ('unpaid'|'overdue'|'paid')
  $payments    Collection of objects with:
                 ->id, ->property_label, ->amount, ->method, ->paid_at, ->receipt_url
--}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — System name nadi sa</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#FAFAF7; --surface:#FFFFFF; --surface-2:#F1F0EA;
    --ink:#14171F; --ink-soft:#5B6169; --ink-faint:#A2A79E;
    --green:#0E6B52; --green-deep:#0A4E3C; --green-soft:#E4EEE9;
    --gold:#C99A2E; --gold-soft:#F6ECD3;
    --rust:#B3402A; --rust-soft:#F7E3DE;
    --line:#E6E4DC; --line-strong:#D8D5CB;
    --radius:14px; --shadow:0 20px 50px -28px rgba(20,23,31,0.28);
  }
  *{ box-sizing:border-box; }
  body{ margin:0; background:var(--bg); color:var(--ink); font-family:'IBM Plex Sans',sans-serif; -webkit-font-smoothing:antialiased; }
  h1,h2,h3{ font-family:'Space Grotesk',sans-serif; font-weight:600; letter-spacing:-0.02em; margin:0; }
  .mono{ font-family:'IBM Plex Mono',monospace; }
  a{ color:inherit; }
  :focus-visible{ outline:2px solid var(--green); outline-offset:3px; border-radius:4px; }
  .wrap{ max-width:1160px; margin:0 auto; padding:0 32px; }

  /* nav */
  .nav{ background:rgba(250,250,247,0.9); backdrop-filter:blur(10px); border-bottom:1px solid var(--line); position:sticky; top:0; z-index:40; }
  .nav .wrap{ display:flex; align-items:center; justify-content:space-between; height:64px; }
  .brand{ display:flex; align-items:center; gap:9px; font-family:'Space Grotesk'; font-weight:600; font-size:17px; }
  .mark{ width:24px; height:24px; }
  .nav-user{ display:flex; align-items:center; gap:14px; }
  .avatar{ width:32px; height:32px; border-radius:50%; background:var(--green-soft); color:var(--green-deep); display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono'; font-size:13px; font-weight:600; }
  .user-name{ font-size:14px; font-weight:500; }
  .logout-btn{ background:none; border:1px solid var(--line-strong); color:var(--ink-soft); padding:8px 14px; border-radius:100px; font-size:13.5px; font-family:'IBM Plex Sans'; cursor:pointer; transition:border-color .15s ease, color .15s ease; }
  .logout-btn:hover{ border-color:var(--ink); color:var(--ink); }

  /* pending state */
  .pending-wrap{ max-width:520px; margin:96px auto; text-align:center; padding:0 24px; }
  .pending-icon{ width:56px; height:56px; margin:0 auto 24px; border-radius:50%; background:var(--gold-soft); display:flex; align-items:center; justify-content:center; }
  .pending-wrap h1{ font-size:26px; margin-bottom:12px; }
  .pending-wrap p{ color:var(--ink-soft); font-size:15px; line-height:1.6; margin:0 0 6px; }

  /* page header */
  .dash-head{ display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; padding:44px 0 32px; }
  .dash-head h1{ font-size:28px; }
  .status-pill{ display:inline-flex; align-items:center; gap:6px; font-family:'IBM Plex Mono'; font-size:11.5px; background:var(--green-soft); color:var(--green-deep); padding:5px 11px; border-radius:100px; margin-bottom:10px; }
  .status-pill .dot{ width:6px; height:6px; border-radius:50%; background:var(--green); }
  .dash-head p{ color:var(--ink-soft); font-size:14.5px; margin-top:6px; }
  .btn-primary{ background:var(--ink); color:#fff; padding:12px 20px; border-radius:100px; font-size:14.5px; font-weight:500; text-decoration:none; border:none; cursor:pointer; font-family:'IBM Plex Sans'; transition:background .15s ease, transform .15s ease; }
  .btn-primary:hover{ background:var(--green-deep); transform:translateY(-1px); }

  /* stat cards */
  .stat-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:48px; }
  .stat-card{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:20px 22px; }
  .stat-card .lbl{ font-size:12.5px; color:var(--ink-faint); margin-bottom:8px; }
  .stat-card .val{ font-family:'IBM Plex Mono'; font-size:24px; font-weight:600; }
  .stat-card .val.warn{ color:var(--rust); }
  @media (max-width:900px){ .stat-grid{ grid-template-columns:1fr 1fr; } }
  @media (max-width:560px){ .stat-grid{ grid-template-columns:1fr; } }

  /* section headers */
  .sec-title{ display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
  .sec-title h2{ font-size:19px; }
  .sec-link{ font-size:13.5px; color:var(--green); text-decoration:none; font-weight:500; }
  .sec-link:hover{ text-decoration:underline; }
  section.dash-sec{ margin-bottom:52px; }

  /* property cards */
  .prop-list{ display:flex; flex-direction:column; gap:12px; }
  .prop-card{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:20px 22px; display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap; }
  .prop-info .type{ font-size:15px; font-weight:600; font-family:'Space Grotesk'; margin-bottom:4px; }
  .prop-info .meta{ font-size:13px; color:var(--ink-soft); }
  .prop-mid{ display:flex; gap:28px; flex-wrap:wrap; }
  .prop-figure .lbl{ font-size:11.5px; color:var(--ink-faint); text-transform:uppercase; letter-spacing:.04em; margin-bottom:4px; }
  .prop-figure .val{ font-family:'IBM Plex Mono'; font-size:15px; font-weight:500; }
  .prop-actions{ display:flex; align-items:center; gap:14px; }
  .badge{ font-family:'IBM Plex Mono'; font-size:11px; font-weight:500; padding:5px 10px; border-radius:100px; white-space:nowrap; }
  .badge.unpaid{ background:var(--gold-soft); color:#8A6414; }
  .badge.overdue{ background:var(--rust-soft); color:var(--rust); }
  .badge.paid{ background:var(--green-soft); color:var(--green-deep); }
  .pay-btn{ background:var(--green); color:#fff; padding:10px 18px; border-radius:100px; font-size:13.5px; font-weight:500; text-decoration:none; border:none; cursor:pointer; font-family:'IBM Plex Sans'; transition:background .15s ease; }
  .pay-btn:hover{ background:var(--green-deep); }
  .receipt-link{ font-size:13px; color:var(--green); text-decoration:none; font-weight:500; }
  .receipt-link:hover{ text-decoration:underline; }

  /* payment history table */
  .hist-table{ width:100%; border-collapse:collapse; background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden; }
  .hist-table th{ text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:var(--ink-faint); font-weight:500; padding:14px 20px; background:var(--surface-2); }
  .hist-table td{ padding:16px 20px; font-size:14px; border-top:1px solid var(--line); }
  .hist-table td.amt{ font-family:'IBM Plex Mono'; font-weight:500; }
  .hist-table tr:hover td{ background:var(--surface-2); }
  @media (max-width:700px){ .hist-table thead{ display:none; } .hist-table, .hist-table tbody, .hist-table tr, .hist-table td{ display:block; width:100%; } .hist-table tr{ border-top:1px solid var(--line); padding:14px 0; } .hist-table td{ border:none; padding:4px 20px; } }

  /* empty state */
  .empty-state{ text-align:center; padding:48px 24px; background:var(--surface); border:1px dashed var(--line-strong); border-radius:var(--radius); color:var(--ink-soft); font-size:14.5px; }
  .empty-state a{ color:var(--green); font-weight:500; text-decoration:none; }
  .empty-state a:hover{ text-decoration:underline; }

  /* modal (add property) */
  .modal-overlay{ position:fixed; inset:0; z-index:100; background:rgba(20,23,31,0.46); backdrop-filter:blur(3px); display:flex; align-items:center; justify-content:center; padding:20px; opacity:0; visibility:hidden; transition:opacity .2s ease, visibility 0s linear .2s; }
  .modal-overlay.is-open{ opacity:1; visibility:visible; transition:opacity .2s ease, visibility 0s linear 0s; }
  .modal-dialog{ width:100%; max-width:420px; background:var(--surface); border:1px solid var(--line); border-radius:20px; box-shadow:var(--shadow); padding:32px 30px 28px; position:relative; transform:translateY(14px) scale(.97); opacity:0; transition:transform .25s cubic-bezier(.2,.8,.3,1), opacity .25s ease; }
  .modal-overlay.is-open .modal-dialog{ transform:translateY(0) scale(1); opacity:1; }
  .modal-close{ position:absolute; top:18px; right:18px; width:32px; height:32px; border-radius:50%; background:var(--surface-2); border:none; color:var(--ink-soft); display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:16px; }
  .modal-close:hover{ background:var(--line); color:var(--ink); }
  .modal-dialog h2{ font-size:21px; margin-bottom:6px; }
  .modal-sub{ font-size:13.5px; color:var(--ink-soft); margin:0 0 22px; line-height:1.5; }
  .field-group{ margin-bottom:15px; }
  .field-group label{ display:block; font-size:13px; font-weight:500; margin-bottom:7px; }
  .field-group input{ width:100%; padding:12px 14px; border-radius:10px; border:1px solid var(--line-strong); background:var(--bg); font-size:14.5px; font-family:'IBM Plex Sans'; }
  .field-group input:focus{ outline:none; border-color:var(--green); box-shadow:0 0 0 3px var(--green-soft); }
  .field-hint{ font-size:12.5px; color:var(--ink-faint); margin-top:6px; }
  .modal-submit{ width:100%; background:var(--ink); color:#fff; border:none; padding:13px; border-radius:100px; font-size:14.5px; font-weight:500; cursor:pointer; margin-top:4px; }
  .modal-submit:hover{ background:var(--green-deep); }
</style>
</head>
<body>

<nav class="nav">
  <div class="wrap">
    <div class="brand">
      <svg class="mark" viewBox="0 0 26 26" fill="none">
        <rect x="1" y="1" width="24" height="24" rx="6" fill="#0E6B52"/>
        <rect x="4.5" y="4.5" width="7.5" height="7.5" rx="1.5" fill="#FAFAF7" opacity="0.9"/>
        <rect x="14" y="4.5" width="7.5" height="7.5" rx="1.5" fill="#FAFAF7" opacity="0.55"/>
        <rect x="4.5" y="14" width="7.5" height="7.5" rx="1.5" fill="#FAFAF7" opacity="0.55"/>
        <rect x="14" y="14" width="7.5" height="7.5" rx="1.5" fill="#C99A2E"/>
      </svg>
      System name nadi sa
    </div>
    <div class="nav-user">
      <span class="user-name">{{ $user->name ?? 'Resident' }}</span>
      <div class="avatar">{{ strtoupper(substr($user->name ?? 'R', 0, 1)) }}</div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">Log out</button>
      </form>
    </div>
  </div>
</nav>

@php
  $unpaidCount = $properties->whereIn('status', ['unpaid','overdue'])->count();
  $overdueCount = $properties->where('status','overdue')->count();
  $totalDue = $properties->whereIn('status', ['unpaid','overdue'])->sum('total_payable');
  $lastPayment = $payments->first();
@endphp

@if (($user->status ?? 'approved') !== 'approved')

  <div class="pending-wrap">
    <div class="pending-icon">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#8A6414" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>
    </div>
    <h1>Your account is awaiting approval</h1>
    <p>Thanks for registering, {{ $user->name }}. An administrator needs to verify your details before you can view or pay your property tax.</p>
    <p>We'll email you the moment it's approved — usually within one business day.</p>
  </div>

@else

  <main class="wrap">
    <div class="dash-head">
      <div>
        <span class="status-pill"><span class="dot"></span>Account approved</span>
        <h1>Welcome back, {{ explode(' ', $user->name)[0] }}.</h1>
        <p>Here's what's due across your properties.</p>
      </div>
      <button type="button" class="btn-primary" data-open-modal="addProperty">+ Link a property</button>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="lbl">Properties linked</div>
        <div class="val">{{ $properties->count() }}</div>
      </div>
      <div class="stat-card">
        <div class="lbl">Total due now</div>
        <div class="val mono">₱{{ number_format($totalDue, 2) }}</div>
      </div>
      <div class="stat-card">
        <div class="lbl">Overdue</div>
        <div class="val {{ $overdueCount > 0 ? 'warn' : '' }}">{{ $overdueCount }}</div>
      </div>
      <div class="stat-card">
        <div class="lbl">Last payment</div>
        <div class="val" style="font-size:16px;">{{ $lastPayment->paid_at ?? '—' }}</div>
      </div>
    </div>

    <section class="dash-sec">
      <div class="sec-title">
        <h2>Your properties</h2>
        @if($unpaidCount > 0)
          <span style="font-size:13px;color:var(--ink-soft);">{{ $unpaidCount }} {{ Str::plural('bill', $unpaidCount) }} need attention</span>
        @endif
      </div>

      @forelse ($properties as $property)
        <div class="prop-list" style="margin-bottom:12px;">
          <div class="prop-card">
            <div class="prop-info">
              <div class="type">{{ $property->type }}, Lot {{ $property->lot_no }}</div>
              <div class="meta">{{ $property->barangay }} · Tax Year {{ $property->tax_year }} · Due {{ $property->due_date }}</div>
            </div>
            <div class="prop-mid">
              <div class="prop-figure"><div class="lbl">Amount due</div><div class="val">₱{{ number_format($property->amount_due, 2) }}</div></div>
              <div class="prop-figure"><div class="lbl">Penalties</div><div class="val">₱{{ number_format($property->penalties, 2) }}</div></div>
              <div class="prop-figure"><div class="lbl">Total payable</div><div class="val">₱{{ number_format($property->total_payable, 2) }}</div></div>
            </div>
            <div class="prop-actions">
              @if ($property->status === 'paid')
                <span class="badge paid">Paid</span>
                <a href="#" class="receipt-link">View receipt</a>
              @else
                <span class="badge {{ $property->status }}">{{ ucfirst($property->status) }}</span>
                <a href="{{ Route::has('payments.checkout') ? route('payments.checkout', $property->id) : '#' }}" class="pay-btn">Pay now</a>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="empty-state">
          No properties linked yet. <a href="#" data-open-modal="addProperty">Link your first property</a> to see what's owed.
        </div>
      @endforelse
    </section>

    <section class="dash-sec">
      <div class="sec-title">
        <h2>Payment history</h2>
      </div>

      @forelse ($payments as $payment)
        @if ($loop->first)
          <table class="hist-table">
            <thead>
              <tr><th>Property</th><th>Amount</th><th>Method</th><th>Date</th><th>Receipt</th></tr>
            </thead>
            <tbody>
        @endif
              <tr>
                <td>{{ $payment->property_label }}</td>
                <td class="amt">₱{{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->method }}</td>
                <td>{{ $payment->paid_at }}</td>
                <td><a href="{{ $payment->receipt_url }}" class="receipt-link">Download</a></td>
              </tr>
        @if ($loop->last)
            </tbody>
          </table>
        @endif
      @empty
        <div class="empty-state">No payments yet. Once you pay a bill, it'll show up here with a downloadable receipt.</div>
      @endforelse
    </section>
  </main>

  <div class="modal-overlay" id="addPropertyModal" aria-hidden="true">
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="addPropTitle">
      <button type="button" class="modal-close" data-close-modal aria-label="Close">✕</button>
      <h2 id="addPropTitle">Link a property</h2>
      <p class="modal-sub">Search by RPT account number, or by lot number and barangay.</p>
      <form method="POST" action="{{ Route::has('properties.store') ? route('properties.store') : '#' }}">
        @csrf
        <div class="field-group">
          <label for="rpt-code">RPT account number</label>
          <input id="rpt-code" type="text" name="rpt_account_no" placeholder="e.g. 0421-4000-106723">
          <div class="field-hint">Don't have it? Fill in lot number and barangay instead.</div>
        </div>
        <div class="field-group">
          <label for="lot-no">Lot number</label>
          <input id="lot-no" type="text" name="lot_no">
        </div>
        <div class="field-group">
          <label for="barangay">Barangay</label>
          <input id="barangay" type="text" name="barangay">
        </div>
        <button type="submit" class="modal-submit">Link property</button>
      </form>
    </div>
  </div>

@endif

<script>
  (function(){
    const overlays = document.querySelectorAll('.modal-overlay');
    if(!overlays.length) return;
    let lastFocused = null;
    function open(id){
      const modal = document.getElementById(id);
      if(!modal) return;
      lastFocused = document.activeElement;
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden','false');
      document.body.style.overflow = 'hidden';
      const first = modal.querySelector('input');
      if(first) setTimeout(()=> first.focus(), 200);
    }
    function close(modal){
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden','true');
      document.body.style.overflow = '';
      if(lastFocused) lastFocused.focus();
    }
    document.querySelectorAll('[data-open-modal]').forEach(btn=>{
      btn.addEventListener('click', (e)=>{ e.preventDefault(); open(btn.dataset.openModal + 'Modal'); });
    });
    document.querySelectorAll('[data-close-modal]').forEach(btn=>{
      btn.addEventListener('click', ()=> close(btn.closest('.modal-overlay')));
    });
    overlays.forEach(modal=>{
      modal.addEventListener('click', (e)=>{ if(e.target === modal) close(modal); });
    });
    document.addEventListener('keydown', (e)=>{
      if(e.key === 'Escape') overlays.forEach(close);
    });
  })();
</script>

</body>
</html>