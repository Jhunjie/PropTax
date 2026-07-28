{{--
  Branded admin shell. Mirrors the landing page's design system (fonts, palette,
  card/pill styling) instead of the generic Flux/Tailwind theme, so the admin
  area feels like the same product as the marketing site.

  Usage:
    <x-layouts::admin title="Overview">
        ... page content ...
    </x-layouts::admin>
--}}
@props(['title' => null, 'pendingCount' => null])
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
  <aside class="sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <x-brand-mark size="sm" />
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-label">Actions</div>

      <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
        Overview
      </a>

      <a href="{{ route('accounts.approvals') }}" class="sidebar-link {{ request()->routeIs('accounts.approvals') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c0-3 2.5-5 5.5-5s5.5 2 5.5 5"/><circle cx="17.5" cy="9" r="2.2"/><path d="M15 19c.2-2.2 1.8-3.8 3.8-3.8"/></svg>
        Account approvals
        @isset($pendingCount)
          @if ($pendingCount > 0)
            <span class="sidebar-badge">{{ $pendingCount }}</span>
          @endif
        @endisset
      </a>

      <a href="{{ route('user-properties-table') }}" class="sidebar-link {{ request()->routeIs('user-properties-table') || request()->routeIs('accounts.user-email-update') ? 'is-active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/></svg>
        Properties
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
      <div class="sidebar-user">
        <div class="name">{{ auth()->user()->name }}</div>
        <div class="role">Administrator</div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-logout" title="Log out" aria-label="Log out">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 20H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
        </button>
      </form>
    </div>
  </aside>

  <div class="sidebar-backdrop" id="adminSidebarBackdrop"></div>

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
    var sidebar = document.getElementById('adminSidebar');
    var backdrop = document.getElementById('adminSidebarBackdrop');
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
