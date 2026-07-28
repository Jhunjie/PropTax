{{--
  Branded resident shell. Same design system as layouts.admin (shared via
  layouts.partials.brand-styles) but with resident-appropriate navigation
  (no account approvals / property management links).

  Usage:
    <x-layouts::resident title="Dashboard">
        ... page content ...
    </x-layouts::resident>
--}}
@props(['title' => null])
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ? $title.' — '.config('app.name') : config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
@include('layouts.partials.brand-styles')
</head>
<body>

<div class="shell">
  <aside class="sidebar" id="residentSidebar">
    <div class="sidebar-brand">
      <x-brand-mark size="sm" />
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-label">Account</div>

      <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
        Overview
      </a>

      <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') || request()->routeIs('appearance.edit') || request()->routeIs('security.edit') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="3"/><path d="M19.4 13.5a7.5 7.5 0 0 0 0-3l1.9-1.5-2-3.4-2.3.6a7.4 7.4 0 0 0-2.6-1.5L14 2.4h-4l-.4 2.3a7.4 7.4 0 0 0-2.6 1.5l-2.3-.6-2 3.4L4.6 10.5a7.5 7.5 0 0 0 0 3L2.7 15l2 3.4 2.3-.6a7.4 7.4 0 0 0 2.6 1.5l.4 2.3h4l.4-2.3a7.4 7.4 0 0 0 2.6-1.5l2.3.6 2-3.4-1.9-1.5z"/></svg>
        Settings
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
      <div class="sidebar-user">
        <div class="name">{{ auth()->user()->name }}</div>
        <div class="role">Resident</div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-logout" title="Log out" aria-label="Log out">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 20H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
        </button>
      </form>
    </div>
  </aside>

  <div class="sidebar-backdrop" id="residentSidebarBackdrop"></div>

  <div class="main">
    <div class="topbar">
      <div class="topbar-brand">{{ $title ?? config('app.name') }}</div>
      <button type="button" class="topbar-toggle" id="sidebarToggle" aria-label="Toggle menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>

    <div class="content">
      {{ $slot }}
    </div>
  </div>
</div>

<script>
  (function(){
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('residentSidebar');
    var backdrop = document.getElementById('residentSidebarBackdrop');
    if(!toggle || !sidebar) return;
    function setOpen(open){
      sidebar.classList.toggle('is-open', open);
      if(backdrop) backdrop.classList.toggle('is-open', open);
    }
    toggle.addEventListener('click', function(){
      setOpen(!sidebar.classList.contains('is-open'));
    });
    if(backdrop) backdrop.addEventListener('click', function(){ setOpen(false); });
    document.addEventListener('click', function(e){
      if(window.innerWidth > 900) return;
      if(!sidebar.contains(e.target) && !toggle.contains(e.target)){
        setOpen(false);
      }
    });
  })();
</script>

</body>
</html>
