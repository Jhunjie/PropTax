<style>
  :root{
    --bg:#FAFAF7; --surface:#FFFFFF; --surface-2:#F1F0EA;
    --ink:#14171F; --ink-soft:#5B6169; --ink-faint:#A2A79E;
    --green:#0E6B52; --green-deep:#0A4E3C; --green-soft:#E4EEE9;
    --gold:#C99A2E; --gold-soft:#F6ECD3;
    --rust:#B3402A; --rust-soft:#F7E3DE;
    --line:#E6E4DC; --line-strong:#D8D5CB;
    --radius:14px; --shadow:0 20px 50px -28px rgba(20,23,31,0.28);
    --sidebar-w:248px;
  }
  *{ box-sizing:border-box; }
  [x-cloak]{ display:none !important; }
  body{ margin:0; background:var(--bg); color:var(--ink); font-family:'IBM Plex Sans',sans-serif; -webkit-font-smoothing:antialiased; }
  h1,h2,h3{ font-family:'Space Grotesk',sans-serif; font-weight:600; letter-spacing:-0.02em; margin:0; }
  .mono{ font-family:'IBM Plex Mono',monospace; }
  a{ color:inherit; }
  :focus-visible{ outline:2px solid var(--green); outline-offset:3px; border-radius:4px; }

  /* ---------- brand mark (logo + two-line name) ---------- */
  .brand-mark{ display:flex; align-items:center; gap:10px; }
  .brand-mark-logo{ height:34px; width:auto; flex-shrink:0; object-fit:contain; }
  .brand-mark-text{ display:flex; flex-direction:column; line-height:1.2; }
  .brand-mark-title{ font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:12.5px; letter-spacing:.01em; }
  .brand-mark-sub{ font-size:10px; color:var(--ink-faint); font-weight:500; }
  .brand-mark-sm .brand-mark-logo{ height:26px; }
  .brand-mark-sm .brand-mark-title{ font-size:11px; }
  .brand-mark-sm .brand-mark-sub{ font-size:9px; }

  /* ---------- shell ---------- */
  .shell{ display:flex; min-height:100vh; }

  /* ---------- sidebar ---------- */
  .sidebar{
    width:var(--sidebar-w); flex-shrink:0; background:var(--surface);
    border-right:1px solid var(--line); display:flex; flex-direction:column;
    position:fixed; inset:0 auto 0 0; z-index:50; transition:transform .2s ease;
  }
  .sidebar-brand{ display:flex; align-items:center; gap:9px; font-family:'Space Grotesk'; font-weight:600; font-size:16.5px; padding:22px 20px; border-bottom:1px solid var(--line); }
  .sidebar-brand .mark{ width:24px; height:24px; flex-shrink:0; }
  .sidebar-nav{ flex:1; padding:16px 12px; display:flex; flex-direction:column; gap:3px; }
  .sidebar-label{ font-family:'IBM Plex Mono'; font-size:10.5px; text-transform:uppercase; letter-spacing:.07em; color:var(--ink-faint); padding:10px 12px 6px; }
  .sidebar-link{
    display:flex; align-items:center; gap:11px; padding:10px 12px; border-radius:10px;
    font-size:14px; font-weight:500; color:var(--ink-soft); text-decoration:none;
    transition:background .15s ease, color .15s ease;
  }
  .sidebar-link svg{ width:18px; height:18px; flex-shrink:0; opacity:.8; }
  .sidebar-link:hover{ background:var(--surface-2); color:var(--ink); }
  .sidebar-link.is-active{ background:var(--green-soft); color:var(--green-deep); }
  .sidebar-link.is-active svg{ opacity:1; }
  .sidebar-badge{
    margin-left:auto; font-family:'IBM Plex Mono'; font-size:10.5px; font-weight:600;
    background:var(--gold-soft); color:#8A6414; padding:2px 7px; border-radius:100px;
  }
  .sidebar-footer{ border-top:1px solid var(--line); padding:14px 16px; display:flex; align-items:center; gap:10px; }
  .sidebar-avatar{
    width:34px; height:34px; border-radius:50%; background:var(--green-soft); color:var(--green-deep);
    display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono'; font-size:13px; font-weight:600; flex-shrink:0;
  }
  .sidebar-user{ min-width:0; flex:1; }
  .sidebar-user .name{ font-size:13.5px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .sidebar-user .role{ font-size:11.5px; color:var(--ink-faint); }
  .sidebar-logout{
    background:none; border:1px solid var(--line-strong); color:var(--ink-soft); width:30px; height:30px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; transition:border-color .15s ease, color .15s ease;
  }
  .sidebar-logout:hover{ border-color:var(--ink); color:var(--ink); }
  .sidebar-logout svg{ width:15px; height:15px; }

  /* ---------- main ---------- */
  .main{ flex:1; margin-left:var(--sidebar-w); min-width:0; }
  .topbar{
    display:none; align-items:center; justify-content:space-between; height:60px; padding:0 20px;
    border-bottom:1px solid var(--line); background:rgba(250,250,247,0.92); backdrop-filter:blur(10px);
    position:sticky; top:0; z-index:40;
  }
  .topbar-brand{ display:flex; align-items:center; gap:8px; font-family:'Space Grotesk'; font-weight:600; font-size:15.5px; }
  .topbar-toggle{ background:none; border:1px solid var(--line-strong); border-radius:10px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer; }
  .content{ max-width:1080px; margin:0 auto; padding:40px 32px 64px; }

  @media (max-width:900px){
    .sidebar{ transform:translateX(-100%); }
    .sidebar.is-open{ transform:translateX(0); box-shadow:var(--shadow); }
    .main{ margin-left:0; }
    .topbar{ display:flex; }
    .content{ padding:28px 20px 48px; }
    .sidebar-backdrop{
      display:block; position:fixed; inset:0; z-index:45;
      background:rgba(20,23,31,0.4); opacity:0; visibility:hidden;
      transition:opacity .2s ease, visibility 0s linear .2s;
    }
    .sidebar-backdrop.is-open{ opacity:1; visibility:visible; transition:opacity .2s ease, visibility 0s linear 0s; }
  }
  .sidebar-backdrop{ display:none; }

  /* ---------- shared page components ---------- */
  .stat-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
  @media (max-width:900px){ .stat-grid{ grid-template-columns:1fr 1fr; } }
  @media (max-width:520px){ .stat-grid{ grid-template-columns:1fr; } }
  .stat-card{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:20px 22px; text-decoration:none; display:block; width:100%; text-align:left; font-family:inherit; cursor:pointer; transition:border-color .15s ease, transform .15s ease, background .15s ease; }
  .stat-card:hover{ border-color:var(--line-strong); transform:translateY(-1px); }
  .stat-card .lbl{ font-size:12.5px; color:var(--ink-faint); margin-bottom:8px; }
  .stat-card .val{ font-family:'IBM Plex Mono'; font-size:26px; font-weight:600; }
  .stat-card .val.warn{ color:var(--rust); }
  .stat-card.is-active.amber{ border-color:var(--gold); background:var(--gold-soft); }
  .stat-card.is-active.green{ border-color:var(--green); background:var(--green-soft); }
  .stat-card.is-active.rust{ border-color:var(--rust); background:var(--rust-soft); }

  .action-grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  @media (max-width:700px){ .action-grid{ grid-template-columns:1fr; } }
  .action-card{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:24px; }
  .action-card .icon-wrap{ width:38px; height:38px; border-radius:10px; background:var(--green-soft); display:flex; align-items:center; justify-content:center; margin-bottom:14px; }
  .action-card .icon-wrap svg{ width:19px; height:19px; color:var(--green-deep); }
  .action-card p{ color:var(--ink-soft); font-size:14px; line-height:1.55; margin:6px 0 16px; }
  .btn-primary{
    display:inline-block; background:var(--ink); color:#fff; padding:11px 20px; border-radius:100px;
    font-size:14px; font-weight:500; text-decoration:none; border:1px solid var(--ink);
    transition:background .15s ease, transform .15s ease;
  }
  .btn-primary:hover{ background:var(--green-deep); border-color:var(--green-deep); transform:translateY(-1px); }

  .panel{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); overflow:hidden; }
  .panel-head{ display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-bottom:1px solid var(--line); }
  .table-scroll{ overflow-x:auto; -webkit-overflow-scrolling:touch; }
  table.data-table{ width:100%; min-width:640px; border-collapse:collapse; }
  table.data-table th{ text-align:left; font-size:11.5px; text-transform:uppercase; letter-spacing:.04em; color:var(--ink-faint); font-weight:500; padding:12px 20px; background:var(--surface-2); }
  table.data-table td{ padding:14px 20px; font-size:13.5px; border-top:1px solid var(--line); }
  table.data-table tr:hover td{ background:var(--surface-2); }
  .pill{ display:inline-flex; align-items:center; gap:5px; font-family:'IBM Plex Mono'; font-size:11px; font-weight:500; padding:4px 10px; border-radius:100px; white-space:nowrap; }
  .pill.green{ background:var(--green-soft); color:var(--green-deep); }
  .pill.amber{ background:var(--gold-soft); color:#8A6414; }
  .pill.rust{ background:var(--rust-soft); color:var(--rust); }
  .pill.zinc{ background:var(--surface-2); color:var(--ink-soft); }
  .empty-state{ text-align:center; padding:48px 24px; color:var(--ink-soft); font-size:14px; }
  .empty-state svg{ width:30px; height:30px; color:var(--ink-faint); margin:0 auto 14px; display:block; }
  .empty-state p{ margin:0 0 14px; }

  /* ---------- page header row ---------- */
  .page-head{ display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end; justify-content:space-between; margin-bottom:28px; }
  .page-head p{ color:var(--ink-soft); font-size:14.5px; margin-top:6px; }
  @media (max-width:640px){
    .page-head{ align-items:stretch; }
    .page-head > div:last-child{ width:100%; }
    .page-head .btn{ flex:1; }
  }

  /* ---------- buttons ---------- */
  .btn{ display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:10px 18px; border-radius:100px; font-size:13.5px; font-weight:500; border:1px solid transparent; cursor:pointer; text-decoration:none; font-family:inherit; line-height:1; transition:background .15s ease, border-color .15s ease, color .15s ease, transform .15s ease; }
  .btn svg{ width:14px; height:14px; flex-shrink:0; }
  .btn-sm{ padding:7px 13px; font-size:12.5px; }
  .btn-icon{ width:32px; height:32px; padding:0; border-radius:9px; }
  .btn-icon svg{ width:15px; height:15px; }
  .btn-green{ background:var(--green); color:#fff; border-color:var(--green); }
  .btn-green:hover{ background:var(--green-deep); border-color:var(--green-deep); }
  .btn-rust{ background:var(--rust-soft); color:var(--rust); }
  .btn-rust:hover{ background:var(--rust); color:#fff; }
  .btn-ghost{ background:none; border-color:var(--line-strong); color:var(--ink-soft); }
  .btn-ghost:hover{ border-color:var(--ink); color:var(--ink); }
  .btn-dark{ background:var(--ink); color:#fff; border-color:var(--ink); }
  .btn-dark:hover{ background:var(--green-deep); border-color:var(--green-deep); }
  .btn:disabled{ opacity:.5; cursor:not-allowed; transform:none !important; }

  /* ---------- filter bar ---------- */
  .filter-bar{ display:flex; flex-wrap:wrap; gap:10px; align-items:center; margin-bottom:20px; }
  .search-field{ position:relative; flex:1 1 240px; }
  .search-field svg{ position:absolute; left:13px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:var(--ink-faint); pointer-events:none; }
  .search-field input{ width:100%; padding:10px 34px 10px 36px; border:1px solid var(--line-strong); border-radius:10px; font-size:13.5px; font-family:inherit; background:var(--surface); color:var(--ink); }
  .search-field input:focus{ outline:none; border-color:var(--green); }
  .search-field .clear-x{ position:absolute; right:8px; top:50%; transform:translateY(-50%); width:22px; height:22px; border:none; background:none; color:var(--ink-faint); cursor:pointer; display:flex; align-items:center; justify-content:center; border-radius:50%; }
  .search-field .clear-x:hover{ background:var(--surface-2); color:var(--ink); }
  .search-field .clear-x svg{ position:static; width:13px; height:13px; }
  .select-field{ padding:10px 32px 10px 14px; border:1px solid var(--line-strong); border-radius:10px; font-size:13.5px; font-family:inherit; background-color:var(--surface); color:var(--ink); appearance:none; cursor:pointer; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235B6169' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:15px; }
  .select-field:focus{ outline:none; border-color:var(--green); }
  @media (max-width:640px){
    .filter-bar{ flex-direction:column; align-items:stretch; }
    .search-field{ flex-basis:auto; }
    .select-field{ width:100%; }
  }

  /* ---------- callouts ---------- */
  .callout{ display:flex; gap:10px; align-items:flex-start; padding:13px 16px; border-radius:12px; font-size:13.5px; line-height:1.55; margin-bottom:18px; }
  .callout svg{ width:17px; height:17px; flex-shrink:0; margin-top:2px; }
  .callout.success{ background:var(--green-soft); color:var(--green-deep); }
  .callout.warn{ background:var(--gold-soft); color:#8A6414; }
  .callout.danger{ background:var(--rust-soft); color:var(--rust); }
  .callout-close{ margin-left:auto; background:none; border:none; cursor:pointer; color:inherit; opacity:.55; padding:2px; flex-shrink:0; }
  .callout-close:hover{ opacity:1; }
  .callout-close svg{ width:14px; height:14px; margin:0; }

  /* ---------- table row helpers ---------- */
  .avatar-sm{ width:30px; height:30px; border-radius:50%; background:var(--green-soft); color:var(--green-deep); display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono'; font-size:12px; font-weight:600; flex-shrink:0; }
  .row-icon{ width:32px; height:32px; border-radius:9px; background:var(--surface-2); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .row-icon svg{ width:16px; height:16px; color:var(--ink-soft); }
  .row-flex{ display:flex; align-items:center; gap:11px; }
  .row-flex.align-top{ align-items:flex-start; }
  .row-sub{ font-size:12px; color:var(--ink-faint); margin-top:2px; }
  table.data-table td.actions-cell{ text-align:right; white-space:nowrap; }
  .row-actions{ display:flex; gap:6px; justify-content:flex-end; }

  /* ---------- combobox / typeahead ---------- */
  .combo{ position:relative; }
  .combo-menu{ position:absolute; z-index:20; top:calc(100% + 6px); left:0; right:0; background:var(--surface); border:1px solid var(--line); border-radius:12px; box-shadow:var(--shadow); overflow-y:auto; max-height:220px; padding:6px; }
  .combo-item{ display:block; width:100%; text-align:left; padding:9px 12px; font-size:13.5px; background:none; border:none; cursor:pointer; color:var(--ink); border-radius:8px; font-family:inherit; }
  .combo-item:hover{ background:var(--surface-2); }
  .combo-empty{ padding:9px 12px; font-size:13px; color:var(--ink-faint); }

  /* ---------- forms ---------- */
  .field{ margin-bottom:18px; }
  .field label{ display:block; font-size:12.5px; font-weight:500; color:var(--ink-soft); margin-bottom:7px; }
  .field input[type="text"], .field input[type="email"], .field input[type="file"]{ width:100%; padding:10px 14px; border:1px solid var(--line-strong); border-radius:10px; font-size:13.5px; font-family:inherit; background:var(--surface); color:var(--ink); }
  .field input[type="file"]{ padding:9px 10px; font-size:13px; }
  .field input:focus{ outline:none; border-color:var(--green); }
  .field-error{ color:var(--rust); font-size:12.5px; margin-top:7px; }
  .field-hint{ color:var(--ink-soft); font-size:13px; margin-top:10px; line-height:1.55; }
  .loading-note{ display:flex; align-items:center; gap:8px; font-size:13px; color:var(--ink-soft); }
  .field-row-2{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
  @media (max-width:420px){ .field-row-2{ grid-template-columns:1fr; } }

  /* ---------- modal ---------- */
  .modal-mask{ position:fixed; inset:0; background:rgba(20,23,31,0.45); backdrop-filter:blur(2px); display:flex; align-items:center; justify-content:center; z-index:100; padding:20px; }
  .modal-box{ background:var(--surface); border-radius:16px; padding:26px; width:100%; max-width:440px; max-height:calc(100dvh - 40px); overflow-y:auto; box-shadow:var(--shadow); }
  .modal-box.modal-box-lg{ max-width:560px; }
  .modal-actions{
    display:flex; justify-content:flex-end; gap:10px;
    position:sticky; bottom:-26px;
    margin:20px -26px -26px; padding:14px 26px 20px;
    background:var(--surface); border-top:1px solid var(--line);
  }

  /* ---------- pagination ---------- */
  .pager{ display:flex; flex-wrap:wrap; align-items:center; justify-content:center; gap:4px; margin-top:26px; font-family:'IBM Plex Mono'; font-size:12.5px; }
  .pager button, .pager span{ min-width:32px; height:32px; padding:0 6px; display:flex; align-items:center; justify-content:center; border-radius:9px; color:var(--ink-soft); border:none; background:none; cursor:pointer; font-family:inherit; }
  .pager button:hover{ background:var(--surface-2); color:var(--ink); }
  .pager button:disabled{ opacity:.4; cursor:not-allowed; }
  .pager .is-current{ background:var(--ink); color:#fff; }
  .pager .is-disabled{ color:var(--ink-faint); cursor:default; }

  /* ---------- notice pages (pending / rejected states) ---------- */
  .notice-page{ max-width:440px; margin:80px auto 0; text-align:center; }
  .notice-icon{ width:56px; height:56px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; }
  .notice-icon.amber{ background:var(--gold-soft); color:#8A6414; }
  .notice-icon.rust{ background:var(--rust-soft); color:var(--rust); }
  .notice-icon svg{ width:26px; height:26px; }
  .notice-page p{ color:var(--ink-soft); font-size:14.5px; line-height:1.6; margin-top:10px; }

  /* ---------- account info panel ---------- */
  .info-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px; padding:22px 24px; }
  @media (max-width:700px){ .info-grid{ grid-template-columns:1fr; } }
  .info-grid .lbl{ font-size:11.5px; text-transform:uppercase; letter-spacing:.04em; color:var(--ink-faint); margin-bottom:6px; }
  .info-grid .value{ font-size:14.5px; font-weight:500; }
</style>
