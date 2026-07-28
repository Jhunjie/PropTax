<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privacy Policy — {{ config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
@include('layouts.partials.brand-styles')
<style>
  .page-wrap{ max-width:760px; margin:0 auto; padding:48px 24px 80px; }
  .back-link{
    display:inline-flex; align-items:center; gap:6px; font-size:13.5px; color:var(--ink-soft);
    text-decoration:none; margin-bottom:32px;
  }
  .back-link:hover{ color:var(--ink); }
  .page-wrap h1{ font-size:30px; margin-bottom:6px; }
  .updated{ font-size:13px; color:var(--ink-faint); font-family:'IBM Plex Mono'; margin-bottom:36px; }
  .page-wrap h2{ font-size:18px; margin:36px 0 12px; }
  .page-wrap p, .page-wrap li{ font-size:14.5px; line-height:1.7; color:var(--ink-soft); }
  .page-wrap ul{ padding-left:20px; margin:10px 0; }
  .notice{
    background:var(--gold-soft); border:1px solid var(--gold); border-radius:12px;
    padding:16px 18px; font-size:13.5px; line-height:1.6; color:#8A6414; margin-bottom:32px;
  }
  header.site-head{ border-bottom:1px solid var(--line); }
  header.site-head .wrap{ max-width:1120px; margin:0 auto; padding:18px 24px; }
</style>
</head>
<body>

<header class="site-head">
  <div class="wrap">
    <a href="{{ route('home') }}" style="text-decoration:none; color:inherit;">
      <x-brand-mark size="sm" />
    </a>
  </div>
</header>

<div class="page-wrap">
  <a href="{{ route('home') }}" class="back-link">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    {{ __('Back to home') }}
  </a>

  <h1>{{ __('Privacy Policy') }}</h1>
  <div class="updated">{{ __('Last updated: :date', ['date' => now()->format('F j, Y')]) }}</div>

  <div class="notice">
    {{ __('Placeholder content. Replace this page with the Province\'s reviewed and legally approved privacy policy before going live.') }}
  </div>

  <p>{{ __('This Online Real Property Tax Payment portal, operated by the Province of Negros Occidental, collects and processes personal information solely to identify property accounts, verify ownership, process tax payments, and communicate with residents about their account status and dues.') }}</p>

  <h2>{{ __('Information we collect') }}</h2>
  <ul>
    <li>{{ __('Your email address and Tax Identification Number (TIN), provided at registration.') }}</li>
    <li>{{ __('Property records linked to your account once verified by an administrator, including account number, name on account, and location.') }}</li>
    <li>{{ __('Payment and transaction records related to real property tax dues.') }}</li>
  </ul>

  <h2>{{ __('How we use your information') }}</h2>
  <ul>
    <li>{{ __('To verify your identity and link you to the correct property account(s).') }}</li>
    <li>{{ __('To process and confirm real property tax payments.') }}</li>
    <li>{{ __('To send account status updates, due-date reminders, and payment receipts.') }}</li>
  </ul>

  <h2>{{ __('How we protect your information') }}</h2>
  <p>{{ __('Access to resident data is restricted to authorized provincial staff. Passwords are stored using industry-standard hashing, and payment transactions are handled through PCI DSS compliant channels.') }}</p>

  <h2>{{ __('Your rights') }}</h2>
  <p>{{ __('You may request a copy of the information we hold about you, ask us to correct inaccurate details, or request account deletion by visiting the provincial treasurer\'s office in person.') }}</p>

  <h2>{{ __('Contact us') }}</h2>
  <p>{{ __('For questions about this policy, please visit or contact the Provincial Treasurer\'s Office, Province of Negros Occidental.') }}</p>
</div>

</body>
</html>
