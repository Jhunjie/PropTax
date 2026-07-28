<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Property Tax Payment System — Pay your real property tax online</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#FAFAF7;
    --surface:#FFFFFF;
    --surface-2:#F1F0EA;
    --ink:#14171F;
    --ink-soft:#5B6169;
    --ink-faint:#A2A79E;
    --green:#0E6B52;
    --green-deep:#0A4E3C;
    --green-soft:#E4EEE9;
    --gold:#C99A2E;
    --gold-soft:#F6ECD3;
    --line:#E6E4DC;
    --line-strong:#D8D5CB;
    --dark:#14171F;
    --dark-line:rgba(255,255,255,0.10);
    --dark-soft:#9BA3AC;
    --radius:14px;
    --shadow: 0 24px 60px -30px rgba(20,23,31,0.28);
  }

  *{ box-sizing:border-box; }
  html{ scroll-behavior:smooth; }
  body{
    margin:0;
    background:var(--bg);
    color:var(--ink);
    font-family:'IBM Plex Sans', sans-serif;
    -webkit-font-smoothing:antialiased;
  }
  h1,h2,h3,.display{
    font-family:'Space Grotesk', sans-serif;
    font-weight:600;
    letter-spacing:-0.02em;
    margin:0;
  }
  .mono{ font-family:'IBM Plex Mono', monospace; }
  a{ color:inherit; }
  .wrap{ max-width:1160px; margin:0 auto; padding:0 32px; }
  section{ position:relative; }
  img,svg{ display:block; }

  :focus-visible{ outline:2px solid var(--green); outline-offset:3px; border-radius:4px; }

  .reveal{ opacity:0; transform:translateY(14px); transition:opacity .6s ease, transform .6s ease; }
  .reveal.is-visible{ opacity:1; transform:translateY(0); }
  @media (prefers-reduced-motion: reduce){
    .reveal{ opacity:1; transform:none; transition:none; }
    html{ scroll-behavior:auto; }
  }

  /* ---------- LOGO MARK ---------- */
  .mark{ width:26px; height:26px; flex-shrink:0; }
  .brand-mark{ display:flex; align-items:center; gap:10px; }
  .brand-mark-logo{ height:38px; width:auto; flex-shrink:0; object-fit:contain; }
  .brand-mark-text{ display:flex; flex-direction:column; line-height:1.2; }
  .brand-mark-title{ font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:13px; letter-spacing:.01em; }
  .brand-mark-sub{ font-size:10.5px; color:var(--ink-faint); font-weight:500; }
  .brand-mark-sm .brand-mark-logo{ height:30px; }
  .brand-mark-sm .brand-mark-title{ font-size:11.5px; }
  .brand-mark-sm .brand-mark-sub{ font-size:9.5px; }

  /* ---------- NAV ---------- */
  .nav{
    position:sticky; top:0; z-index:50;
    background:rgba(250, 250, 247, 0.89);
    backdrop-filter:blur(10px);
    border-bottom:1px solid var(--line);
  }
  .nav .wrap{ display:flex; align-items:center; justify-content:space-between; height:64px; }
  .brand{ display:flex; align-items:center; gap:10px; font-family:'Space Grotesk',sans-serif; font-weight:600; font-size:18.5px; letter-spacing:-0.01em; }
  .nav-links{ display:flex; gap:30px; font-size:14.5px; color:var(--ink-soft); }
  .nav-links a{ text-decoration:none; transition:color .15s ease; }
  .nav-links a:hover{ color:var(--ink); }
  .nav-right{ display:flex; align-items:center; gap:18px; }
  .nav-login{ font-size:14.5px; font-weight:500; text-decoration:none; color:var(--ink); padding: 0.5rem 1.5rem 0.5rem 1.5rem;  border-radius: 30px;}
  .nav-login:hover{ color:var(--green); }
  .nav-cta{
    background:var(--ink); color:#fff; padding:10px 18px; border-radius:100px;
    font-size:14px; font-weight:500; text-decoration:none; border:1px solid var(--ink);
    transition:background .15s ease, transform .15s ease;
  }



  .nav-cta:hover{ background:var(--green-deep); border-color:var(--green-deep); transform:translateY(-1px); }
  .nav-burger{
    display:none; width:38px; height:38px; align-items:center; justify-content:center;
    border-radius:10px; border:1px solid var(--line-strong); background:none; color:var(--ink);
    cursor:pointer; flex-shrink:0;
  }
  .nav-burger svg{ width:18px; height:18px; }
  .nav-mobile-panel{
    display:none; flex-direction:column; gap:2px;
    max-height:0; overflow:hidden;
    border-top:1px solid transparent;
    transition:max-height .25s ease, border-color .25s ease;
    background:var(--bg);
  }
  .nav-mobile-panel a, .nav-mobile-panel .nav-mobile-login{
    padding:14px 32px; font-size:15px; font-weight:500; color:var(--ink); text-decoration:none;
    border-top:1px solid var(--line); background:none; border-left:none; border-right:none; border-bottom:none;
    text-align:left; font-family:inherit; cursor:pointer; width:100%;
  }
  .nav-mobile-register{
    padding:14px 32px; font-size:15px; font-weight:500; color:white; text-decoration:none; background:var(--green); 
    border-top:1px solid var(--line); border-left:none; border-right:none; border-bottom:none;
    text-align:left; font-family:inherit; cursor:pointer; width:100%;
  }
  .nav.is-open .nav-mobile-panel{ max-height:320px; border-top-color:var(--line); }
  @media (max-width:820px){
    .nav-links{ display:none; }
    .nav-login{ display:none; }
    .nav-burger{ display:flex; }
    .nav-mobile-panel{ display:flex; }
  }
  @media (max-width:500px){
    .nav-cta{ display:none; }
    #features, #fees{ padding: 0 1rem 0 1rem;}
  }

  /* ---------- AUTH MODALS ---------- */
  .modal-overlay{
    position:fixed; inset:0; z-index:100;
    background:rgba(20,23,31,0.46);
    backdrop-filter:blur(3px);
    display:flex; align-items:center; justify-content:center;
    padding:20px;
    opacity:0; visibility:hidden;
    transition:opacity .2s ease, visibility 0s linear .2s;
  }
  .modal-overlay.is-open{
    opacity:1; visibility:visible;
    transition:opacity .2s ease, visibility 0s linear 0s;
  }
  .modal-dialog{
    width:100%; max-width:400px;
    max-height:calc(100dvh - 40px);
    overflow-y:auto;
    background:var(--surface);
    border:1px solid var(--line);
    border-radius:20px;
    box-shadow:var(--shadow);
    padding:32px 30px 28px;
    position:relative;
    transform:translateY(14px) scale(.97);
    opacity:0;
    transition:transform .25s cubic-bezier(.2,.8,.3,1), opacity .25s ease;
  }
  .modal-overlay.is-open .modal-dialog{ transform:translateY(0) scale(1); opacity:1; }
  @media (prefers-reduced-motion: reduce){
    .modal-overlay, .modal-dialog{ transition:none; }
  }
  .modal-close{
    position:absolute; top:18px; right:18px;
    width:32px; height:32px; border-radius:50%;
    background:var(--surface-2); border:none; color:var(--ink-soft);
    display:flex; align-items:center; justify-content:center; cursor:pointer;
    font-size:16px; line-height:1; transition:background .15s ease, color .15s ease;
  }
  .modal-close:hover{ background:var(--line); color:var(--ink); }
  .modal-mark{ margin-bottom:22px; }
  .modal-dialog h2{ font-size:23px; margin-bottom:6px; }
  .modal-sub{ font-size:14px; color:var(--ink-soft); margin:0 0 24px; line-height:1.5; }
  .field-group{ margin-bottom:16px; }
  .field-group label{ display:block; font-size:13px; font-weight:500; color:var(--ink); margin-bottom:7px; }
  .field-group input{
    width:100%; padding:12px 14px; border-radius:10px; border:1px solid var(--line-strong);
    background:var(--bg); font-family:'IBM Plex Sans'; font-size:14.5px; color:var(--ink);
    transition:border-color .15s ease, box-shadow .15s ease;
  }
  .field-group input:focus{ outline:none; border-color:var(--green); box-shadow:0 0 0 3px var(--green-soft); }
  .field-error{ font-size:12.5px; color:#B3402A; margin-top:6px; }
  .field-row-between{ display:flex; justify-content:space-between; align-items:center; margin:-4px 0 18px; }
  .remember-me{ display:flex; align-items:center; gap:7px; font-size:13px; color:var(--ink-soft); }
  .remember-me input{ width:15px; height:15px; accent-color:var(--green); }
  .forgot-link{ font-size:13px; color:var(--green); text-decoration:none; font-weight:500; }
  .forgot-link:hover{ text-decoration:underline; }
  .modal-submit{
    width:100%; background:var(--ink); color:#fff; border:none; padding:14px; border-radius:100px;
    font-size:15px; font-weight:500; cursor:pointer; transition:background .15s ease, transform .15s ease;
    font-family:'IBM Plex Sans'; margin-top:4px;
  }
  .modal-submit:hover{ background:var(--green-deep); transform:translateY(-1px); }
  .modal-switch{ text-align:center; font-size:13.5px; color:var(--ink-soft); margin-top:22px; }
  .modal-switch button{
    background:none; border:none; color:var(--green); font-weight:600; cursor:pointer;
    font-family:'IBM Plex Sans'; font-size:13.5px; padding:0;
  }
  .modal-switch button:hover{ text-decoration:underline; }
  .nav-login, .nav-cta{ cursor:pointer; }
  button.nav-login, button.nav-cta{ font-family:'IBM Plex Sans'; }

  /* ---------- HERO ---------- */
  .hero{ padding:44px 0 72px; overflow:hidden; position:relative; }
  .hero-bg-photo{
    position:absolute; inset:0; z-index:0;
    width:100%; height:100%; object-fit:cover; object-position:center;
    pointer-events:none;
    mask-image:linear-gradient(90deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.55) 55%, rgba(0,0,0,0) 100%);
    -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.55) 55%, rgba(0,0,0,0) 100%);
    opacity:0; transform:scale(1.08);
    animation: hero-bg-in 1.6s cubic-bezier(.2,.8,.3,1) forwards;
  }
  @keyframes hero-bg-in{
    from{ opacity:0; transform:scale(1.08); }
    to{ opacity:0.8; transform:scale(1); }
  }
  @media (prefers-reduced-motion: reduce){
    .hero-bg-photo{ animation:none; opacity:0.8; transform:none; }
  }
  .hero-bg-overlay{
    position:absolute; inset:0; z-index:0; pointer-events:none;
    background:linear-gradient(180deg, rgba(250,250,247,0.25) 0%, var(--bg) 96%);
  }
  .hero-grid-bg{
    position:absolute; inset:-30px -8% auto auto; width:460px; height:460px;
    pointer-events:none; z-index:1;
  }
  .hero-grid-bg rect.cell{
    opacity:0; transform-origin:center; transform-box:fill-box;
    animation: cell-draw .5s ease forwards;
  }
  .hero-grid-bg rect.cell.hero-parcel{
    animation: cell-draw .5s ease forwards, parcel-pulse 2.6s ease-in-out 1.4s infinite;
  }
  @keyframes cell-draw{
    from{ opacity:0; transform:scale(.6); }
    to{ opacity:1; transform:scale(1); }
  }
  @keyframes parcel-pulse{
    0%,100%{ fill-opacity:0.9; }
    50%{ fill-opacity:0.35; }
  }
  @media (prefers-reduced-motion: reduce){
    .hero-grid-bg rect.cell{ animation:none; opacity:1; }
  }
  .hero .wrap{ display:grid; grid-template-columns:1.05fr 0.95fr; gap:60px; align-items:center; position:relative; z-index:2; }
  .eyebrow{
    display:inline-flex; align-items:center; gap:8px;
    font-family:'IBM Plex Mono'; font-size:12px; letter-spacing:.06em; text-transform:uppercase;
    color:var(--green); background:var(--green-soft);
    padding:7px 12px; border-radius:100px; margin-bottom:24px;
  }
  .eyebrow .dot{ width:6px;height:6px;border-radius:50%; background:var(--green); }
  .hero h1{ font-size:50px; line-height:1.08; }
  .hero h1 em{ font-style:normal; color:var(--green); }
  .hero p.lede{ font-size:17.5px; color:var(--ink-soft); max-width:470px; margin:22px 0 32px; line-height:1.6; }
  .btn-row{ display:flex; gap:22px; align-items:center; flex-wrap:wrap; }
  .btn-primary{
    background:var(--ink); color:#fff; padding:15px 26px; border-radius:100px; font-weight:500;
    text-decoration:none; font-size:15.5px; border:1px solid var(--ink); display:inline-block;
    transition:background .15s ease, transform .15s ease;
  }
  .btn-primary:hover{ background:var(--green-deep); border-color:var(--green-deep); transform:translateY(-1px); }
  .btn-ghost{
    color:var(--ink); text-decoration:none; font-size:15px; font-weight:500;
    border-bottom:1px solid var(--ink-faint); padding-bottom:2px;
    transition:border-color .15s ease, color .15s ease;
  }
  .btn-ghost:hover{ color:var(--green); border-color:var(--green); }
  .hero-stats{ display:flex; gap:38px; margin-top:52px; }
  .hero-stats div span{ display:block; }
  .hero-stats .num{ font-family:'IBM Plex Mono'; font-size:21px; font-weight:600; color:var(--ink); }
  .hero-stats .lbl{ font-size:12.5px; color:var(--ink-faint); margin-top:3px; }

  /* Signature element: bill lookup card */
  .card-stage{ display:flex; justify-content:center; position:relative; }
  .bill-card{
    width:100%; max-width:360px; background:var(--surface);
    border:1px solid var(--line);
    box-shadow:var(--shadow);
    border-radius:20px;
    padding:26px 26px 24px;
    position:relative;
    opacity:0; transform:translateY(28px) scale(.96);
    animation: card-in .7s cubic-bezier(.2,.8,.3,1) .3s forwards, card-float 5s ease-in-out 1.1s infinite;
  }
  @keyframes card-in{
    to{ opacity:1; transform:translateY(0) scale(1); }
  }
  @keyframes card-float{
    0%,100%{ transform:translateY(0); }
    50%{ transform:translateY(-8px); }
  }
  @media (prefers-reduced-motion: reduce){
    .bill-card{ animation:none; opacity:1; transform:none; }
  }
  .bill-top{ display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
  .bill-top .k{ font-family:'IBM Plex Mono'; font-size:11px; color:var(--ink-faint); text-transform:uppercase; letter-spacing:.07em; }
  .bill-status{
    display:inline-flex; align-items:center; gap:6px;
    font-family:'IBM Plex Mono'; font-size:11px; font-weight:500; letter-spacing:.03em;
    background:var(--gold-soft); color:#8A6414; padding:5px 10px; border-radius:100px;
  }
  .bill-status .pulse-dot{ width:6px; height:6px; border-radius:50%; background:#C99A2E; animation:dot-pulse 1.8s ease-in-out infinite; }
  @keyframes dot-pulse{
    0%,100%{ opacity:1; transform:scale(1); }
    50%{ opacity:.4; transform:scale(.7); }
  }
  @media (prefers-reduced-motion: reduce){ .bill-status .pulse-dot{ animation:none; } }
  .bill-rows{ border-top:1px solid var(--line); border-bottom:1px solid var(--line); padding:4px 0; margin-bottom:20px; }
  .bill-row{ display:flex; justify-content:space-between; font-size:13.5px; padding:10px 0; }
  .bill-row .l{ color:var(--ink-soft); }
  .bill-row .v{ font-family:'IBM Plex Mono'; font-weight:500; text-align:right; }
  .bill-due-lbl{ font-size:12.5px; color:var(--ink-soft); margin-bottom:4px; }
  .bill-due-amt{ font-family:'IBM Plex Mono'; font-size:34px; font-weight:600; color:var(--ink); letter-spacing:-0.01em; }
  .bill-pay-btn{
    display:block; width:100%; text-align:center; margin-top:18px;
    background:var(--green); color:#fff; padding:14px; border-radius:100px;
    font-size:14.5px; font-weight:500; text-decoration:none; border:none;
  }
  .bill-fine-print{ text-align:center; font-size:12px; color:var(--ink-faint); margin-top:12px; }

  @media (max-width:980px){
    .hero .wrap{ grid-template-columns:1fr; }
    .hero h1{ font-size:36px; }
    .card-stage{ margin-top:12px; }
    .hero-grid-bg{ display:none; }
  }
  @media (max-width:560px){
    .wrap{ padding:0 20px; }
    .hero{ padding:32px 0 56px; }
    .hero h1{ font-size:30px; }
    .hero p.lede{ font-size:12px; margin:16px 0 28px; }
    .hero-stats{ gap:22px; flex-wrap:wrap; margin-top:36px; }
    .btn-row{ gap:16px; }
    .bill-card{ padding:22px 20px 20px; }
  }

  /* ---------- TRUST BAR ---------- */
  .trust{ background:var(--bg); border-top:1px solid var(--line); border-bottom:1px solid var(--line); padding:30px 0; }
  .trust .wrap{ display:flex; justify-content:space-between; flex-wrap:wrap; gap:24px; }
  .trust .item{ text-align:left; }
  .trust .num{ font-family:'IBM Plex Mono'; font-size:21px; font-weight:600; color:var(--ink); }
  .trust .lbl{ font-size:12.5px; color:var(--ink-faint); margin-top:3px; }

  /* ---------- SECTION HEADERS ---------- */
  .sec{ padding:76px 0; }
  .sec-head{ max-width:600px; margin-bottom:56px; }
  .sec-eyebrow{ font-family:'IBM Plex Mono'; font-size:12px; letter-spacing:.06em; text-transform:uppercase; color:var(--green); margin-bottom:14px; display:block; }
  .sec-head h2{ font-size:32px; line-height:1.18; }
  .sec-head p{ color:var(--ink-soft); font-size:15.5px; margin-top:14px; line-height:1.6; }
  @media (max-width:560px){
    .sec{ padding:52px 0; }
    .sec-head{ margin-bottom:36px; }
    .sec-head h2{ font-size:25px; }
  }

  /* ---------- HOW IT WORKS ---------- */
  .steps{ display:grid; grid-template-columns:repeat(4,1fr); gap:36px; }
  .step{ position:relative; }
  .step .idx{
    font-family:'IBM Plex Mono'; font-size:42px; font-weight:500; color:var(--line-strong);
    line-height:1; margin-bottom:18px; display:block;
  }
  .step h3{ font-size:16.5px; font-family:'Space Grotesk'; font-weight:600; margin-bottom:8px; }
  .step p{ font-size:14px; color:var(--ink-soft); line-height:1.55; margin:0; }
  @media (max-width:900px){ .steps{ grid-template-columns:1fr 1fr; row-gap:40px; } }

  /* ---------- FEATURES ---------- */
  .features{ background:var(--surface-2); position:relative; overflow:hidden; }
  .features-bg-photo{
    position:absolute; inset:0; z-index:0;
    width:100%; height:100%; object-fit:cover; object-position:center;
    opacity:0.3; pointer-events:none;
    mask-image:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.9) 35%, rgba(0,0,0,0.9) 65%, rgba(0,0,0,0) 100%);
    -webkit-mask-image:linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.9) 35%, rgba(0,0,0,0.9) 65%, rgba(0,0,0,0) 100%);
  }
  .features-bg-overlay{
    position:absolute; inset:0; z-index:1; pointer-events:none;
    background:var(--surface-2);
    opacity:0.55;
  }
  .features .wrap{ position:relative; z-index:2; }
  .feat-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
  .feat-card{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:28px 26px; transition:transform .18s ease, box-shadow .18s ease; }
  .feat-card:hover{ transform:translateY(-3px); box-shadow:0 16px 34px -22px rgba(20,23,31,0.35); }
  .feat-card .ico{ width:34px; height:34px; margin-bottom:18px; color:var(--green); }
  .feat-card h3{ font-size:16px; font-weight:600; font-family:'Space Grotesk'; margin-bottom:8px; }
  .feat-card p{ font-size:13.5px; color:var(--ink-soft); line-height:1.55; margin:0; }
  @media (max-width:900px){ .feat-grid{ grid-template-columns:1fr 1fr; } }
  @media (max-width:600px){ .feat-grid{ grid-template-columns:1fr; } }

  /* ---------- FEES ---------- */
  .fees{ background:var(--dark); color:#fff; }
  .fees .sec-eyebrow{ color:var(--gold); }
  .fees .sec-head p{ color:var(--dark-soft); }
  .fee-table{ display:flex; flex-direction:column; gap:1px; background:var(--dark-line); border-radius:var(--radius); overflow:hidden; }
  .fee-row{ display:flex; justify-content:space-between; align-items:center; padding:22px 26px; background:var(--dark); }
  .fee-row .name{ font-size:15.5px; font-weight:500; }
  .fee-row .desc{ font-size:13px; color:var(--dark-soft); margin-top:4px; }
  .fee-row .amt{ font-family:'IBM Plex Mono'; font-size:18px; color:var(--gold); font-weight:600; white-space:nowrap; margin-left:20px; }
  .fee-note{ margin-top:22px; font-size:13.5px; color:var(--dark-soft); display:flex; gap:10px; align-items:flex-start; }
  .fee-note .flag{ color:var(--gold); flex-shrink:0; }

  /* ---------- FAQ ---------- */
  .faq-item{ border-bottom:1px solid var(--line); }
  .faq-q{
    display:flex; justify-content:space-between; align-items:center; gap:20px;
    padding:22px 2px; cursor:pointer; font-size:15.5px; font-weight:500; background:none; border:none;
    width:100%; text-align:left; color:white; font-family:'IBM Plex Sans';
  }
  .faq-q .plus{ font-family:'IBM Plex Mono'; color:var(--green); font-size:19px; transition:transform .2s ease; flex-shrink:0; }
  .faq-item.open .plus{ transform:rotate(45deg); }
  .faq-a{ max-height:0; overflow:hidden; transition:max-height .3s ease; }
  .faq-a p{ padding:0 2px 22px; color:var(--ink-soft); font-size:14.5px; line-height:1.65; margin:0; max-width:640px; }

  /* ---------- FINAL CTA ---------- */
  .final-cta{ text-align:center; padding:100px 0; background:var(--surface-2); }
  .final-cta h2{ font-size:34px; max-width:580px; margin:0 auto 16px; line-height:1.2; }
  .final-cta p{ color:var(--ink-soft); font-size:15.5px; margin-bottom:30px; }
  @media (max-width:560px){
    .final-cta{ padding:64px 0; }
    .final-cta h2{ font-size:26px; }
  }

  /* ---------- FOOTER ---------- */
  footer{ padding:36px 0 44px; }
  footer .wrap{ display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px; }
  .foot-links{ display:flex; gap:24px; font-size:13.5px; color:var(--ink-soft); }
  .foot-links a{ text-decoration:none; }
  .foot-links a:hover{ color:var(--ink); }
  .foot-badges{ display:flex; gap:12px; font-family:'IBM Plex Mono'; font-size:10.5px; color:var(--ink-faint); }
  .foot-badges span{ border:1px solid var(--line); padding:5px 10px; border-radius:100px; }
  .foot-partners{
    display:flex; align-items:center; gap:22px; flex-wrap:wrap; padding-bottom:28px; margin-bottom:28px;
    border-bottom:1px solid var(--line);
  }
  .foot-partners img{ height:42px; width:auto; object-fit:contain; flex-shrink:0; }
  .foot-partners .divider{ width:1px; height:34px; background:var(--line-strong); }
  .foot-partners .cap{ font-family:'IBM Plex Mono'; font-size:10.5px; letter-spacing:.05em; text-transform:uppercase; color:var(--ink-faint); }
  @media (max-width:560px){
    .foot-partners{ gap:14px; }
    .foot-partners img{ height:32px; }
    .foot-partners .divider{ display:none; }
    .foot-partners .cap{ flex-basis:100%; }
  }
</style>
</head>
<body>

<nav class="nav">
  <div class="wrap">
    <x-brand-mark />
    <div class="nav-links">
      <a href="#how">How it works</a>
      <a href="#features">Features</a>
      <a href="#faq">FAQ</a>
    </div>
    <div class="nav-right">
      @auth
        <a href="{{ route('dashboard') }}" class="nav-cta">Dashboard</a>
      @else
        <button type="button" class="nav-login" data-open-modal="login">Log in</button>
        <button type="button" class="nav-cta" data-open-modal="register">Get started</button>
      @endauth
      <button type="button" class="nav-burger" id="navBurger" aria-label="Open menu" aria-expanded="false" aria-controls="navMobilePanel">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>

  <div class="nav-mobile-panel" id="navMobilePanel">
    @guest
      <button type="button" class="nav-mobile-register" data-open-modal="register">Register</button>
    @endguest
    <a href="#how">How it works</a>
    <a href="#features">Features</a>
    <a href="#faq">FAQ</a>
    @guest
      <button type="button" class="nav-mobile-login" data-open-modal="login">Log in</button>
    @endguest
  </div>
</nav>

<section class="hero">
  <img src="{{ asset('images/hero-bg.jpg') }}" alt="" class="hero-bg-photo" aria-hidden="true">
  <div class="hero-bg-overlay" aria-hidden="true"></div>
  <svg class="hero-grid-bg" id="parcelSvg" viewBox="0 0 460 460" fill="none" aria-hidden="true"></svg>
  <div class="wrap">
    <div>
      <span class="eyebrow"><span class="dot"></span>Now live in 48 municipalities</span>
      <h1>Real property tax,<br>made <em>easy to pay.</em></h1>
      <p class="lede">Manage your real property tax online. View your tax balance, verify your property details, and pay securely—all in one place.</p>
      <div class="btn-row">
        @guest
          <a data-open-modal="register" class="btn-primary">Look up my property →</a>
        @endguest
        <a href="#how" class="btn-ghost">See how it works</a>
      </div>
      <div class="hero-stats">
        <div><span class="num count-up" data-target="2.1" data-decimals="1" data-suffix="M">0M</span><span class="lbl">properties on file</span></div>
        <div><span class="num count-up" data-prefix="₱" data-target="890" data-suffix="M">₱0M</span><span class="lbl">collected in 2025</span></div>
        <div><span class="num count-up" data-target="99.98" data-decimals="2" data-suffix="%">0%</span><span class="lbl">payment uptime</span></div>
      </div>
    </div>
    <div class="card-stage">
      <div class="bill-card">
        <div class="bill-top">
          <div>
            <div class="k">Real Property Tax · 2026</div>
          </div>
          <div class="bill-status"><span class="pulse-dot"></span>Due Sep 30</div>
        </div>
        <div class="bill-rows">
          <div class="bill-row"><span class="l">Property</span><span class="v">Land, Lot 5</span></div>
          <div class="bill-row"><span class="l">Location</span><span class="v">Robles, La Castellana</span></div>
          <div class="bill-row"><span class="l">Account no.</span><span class="v">0421-4000-106723</span></div>
        </div>
        <div class="bill-due-lbl">Amount due</div>
        <div class="bill-due-amt">₱4,812.60</div>
        <a href="#" class="bill-pay-btn">Pay now</a>
        <div class="bill-fine-print">Bank transfer fees may apply</div>
      </div>
    </div>
  </div>
</section>

<div class="trust">
  <div class="wrap">
    <div class="item"><div class="num count-up" data-target="48" data-suffix="">0</div><div class="lbl">partner LGUs</div></div>
    <div class="item"><div class="num count-up" data-target="2.1" data-decimals="1" data-suffix="M">0M</div><div class="lbl">property accounts served yearly</div></div>
    <div class="item"><div class="num count-up" data-prefix="₱" data-target="890" data-suffix="M">₱0M</div><div class="lbl">RPT collected in 2025</div></div>
    <div class="item"><div class="num count-up" data-target="4.8" data-decimals="1" data-suffix="/5">0/5</div><div class="lbl">resident satisfaction</div></div>
  </div>
</div>

<section class="sec" id="how">
  <div class="wrap">
    <div class="sec-head reveal">
      <span class="sec-eyebrow">How it works</span>
      <h2>From property account to receipt in four steps.</h2>
    </div>
    <div class="steps reveal">
      <div class="step">
        <span class="idx">01</span>
        <h3>Create your account</h3>
        <p>Enroll with your email and name — the same record the LGU uses to link every property you own.</p>
      </div>
      <div class="step">
        <span class="idx">02</span>
        <h3>Link your properties</h3>
        <p>Add each land or building account by RPT number, or search by lot number and barangay.</p>
      </div>
      <div class="step">
        <span class="idx">03</span>
        <h3>Review what's owed</h3>
        <p>See the type, lot, barangay, and exact amount due for each property before you pay.</p>
      </div>
      <div class="step">
        <span class="idx">04</span>
        <h3>Pay and get your receipt</h3>
        <p>Bank transfer, GCash, Maya, or card — with an instant digital receipt for every property.</p>
      </div>
    </div>
  </div>
</section>

<section class="features" id="features">
  <img src="{{ asset('images/features-bg.jpg') }}" alt="" class="features-bg-photo" aria-hidden="true">
  <div class="features-bg-overlay" aria-hidden="true"></div>
  <div class="wrap sec">
    <div class="sec-head reveal">
      <span class="sec-eyebrow">Built for the way people actually pay</span>
      <h2>Everything a tax office does at the counter — online.</h2>
      <p>No new habits to learn. Just the same bill, the same options, without the trip.</p>
    </div>
    <div class="feat-grid reveal">
      <div class="feat-card">
        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="10" cy="10" r="6.5"/><path d="M15 15L21 21"/></svg>
        <h3>Property & barangay search</h3>
        <p>Look up any property in seconds by RPT account number, lot number, or barangay name.</p>
      </div>
      <div class="feat-card">
        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2" y="6" width="20" height="14" rx="1.5"/><path d="M2 10h20"/></svg>
        <h3>Land and building, one account</h3>
        <p>Every property you own sits under one email — pay one, or all of them together.</p>
      </div>
      <div class="feat-card">
        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="17" rx="1.5"/><path d="M3 9h18M8 3v3M16 3v3"/></svg>
        <h3>GCash, Maya & bank transfer</h3>
        <p>Pay however you already pay everything else, plus debit and credit for a small fee.</p>
      </div>
      <div class="feat-card">
        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/></svg>
        <h3>Due-date reminders</h3>
        <p>Email and text alerts before the deadline, so a penalty never sneaks up on you.</p>
      </div>
      <div class="feat-card">
        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 12l5 5L20 6"/></svg>
        <h3>Instant digital receipts</h3>
        <p>A confirmed receipt the moment payment clears, filed per property for your records.</p>
      </div>
      <div class="feat-card">
        <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 2l8 3.5v6c0 5.5-3.4 8.8-8 10.5-4.6-1.7-8-5-8-10.5v-6L12 2z"/></svg>
        <h3>Bank-level security</h3>
        <p>256-bit encryption and PCI DSS Level 1 compliance on every transaction.</p>
      </div>
    </div>
  </div>
</section>

<section class="fees" id="fees">
  <div class="wrap sec">
    <div class="sec-head reveal" id="faq" style="margin-top:64px;">
      <span class="sec-eyebrow">Questions</span>
      <h2>Common questions from residents.</h2>
    </div>
    <div class="reveal" style="max-width:760px;">
      <div class="faq-item">
        <button class="faq-q">What if I don't know my RPT account number? <span class="plus">+</span></button>
        <div class="faq-a"><p>Search by lot number and barangay instead — your account number will show up in the results and on every receipt afterward.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q">What property types can I pay for? <span class="plus">+</span></button>
        <div class="faq-a"><p>Land and building accounts — both appear under the same property search and can be paid together or separately, whichever you prefer.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q">Can I link more than one property to my account? <span class="plus">+</span></button>
        <div class="faq-a"><p>Yes. One email address can hold multiple property accounts — land, improvements, or buildings — and you can pay one lot or all of them in a single transaction.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q">Can I pay for a property that isn't registered under my name? <span class="plus">+</span></button>
        <div class="faq-a"><p>Yes. Anyone can look up and pay using the RPT account number or lot number — you don't need to be the registered owner to submit payment.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q">What happens if I pay after the due date? <span class="plus">+</span></button>
        <div class="faq-a"><p>Your LGU applies penalties and interest according to its own schedule. The system shows the updated total, including any penalty, before you confirm payment.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q">Is there really no fee for bank transfer? <span class="plus">+</span></button>
        <div class="faq-a"><p>Correct — bank transfer is free. GCash, Maya, debit, and credit carry small processing fees because those go to the payment networks, not to your LGU.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="final-cta reveal">
  <div class="wrap">
    <h2>Your bill is already sitting there. Might as well handle it now.</h2>
    <p>Two minutes, no envelope, no trip downtown.</p>
    <a href="#" class="btn-primary">Look up my property →</a>
  </div>
</section>

<footer>
  <div class="wrap" style="display:block;">
    <div class="foot-partners">
      <a href="https://www.negros-occ.gov.ph/assets/RegistrationCert.pdf"><img src="{{ asset('images/footer-logo-1.png') }}" alt="{{ __('National Privacy Commission seal') }}"></a>
      <div class="divider"></div>
      <a href="https://www.negros-occ.gov.ph/" target="_blank" rel="noopener noreferrer"><img src="{{ asset('images/footer-logo-2.png') }}" alt="{{ __('Partner agency logo') }}"></a>
      <span class="cap">{{ __('A joint initiative with the provincial treasurer\'s office') }}</span>
    </div>
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
      <x-brand-mark size="sm" />
      <div class="foot-links">
        <a href="{{ route('privacy-policy') }}">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Accessibility</a>
        <a href="#">Contact</a>
      </div>
      <div class="foot-badges">
        <span>SSL SECURED</span>
        <span>PCI DSS COMPLIANT</span>
      </div>
    </div>
  </div>
</footer>

@guest
<div class="modal-overlay" id="loginModal" aria-hidden="true">
  <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
    <button type="button" class="modal-close" data-close-modal aria-label="Close">✕</button>
    <x-brand-mark size="sm" class="modal-mark" />
    <h2 id="loginModalTitle">Welcome back</h2>
    <p class="modal-sub">Log in to view your properties and pay your bill.</p>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="field-group">
        <label for="login-email">Email</label>
        <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email', 'login')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>
      <div class="field-group">
        <label for="login-password">Password</label>
        <input id="login-password" type="password" name="password" required>
        @error('password', 'login')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>
      <div class="field-row-between">
        <label class="remember-me">
          <input type="checkbox" name="remember">
          Remember me
        </label>
        @if (Route::has('password.request'))
          <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
        @endif
      </div>
      <button type="submit" class="modal-submit">Log in</button>
    </form>
    <div class="modal-switch">Don't have an account? <button type="button" data-switch-to="register">Sign up</button></div>
  </div>
</div>

<div class="modal-overlay" id="registerModal" aria-hidden="true">
  <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="registerModalTitle">
    <button type="button" class="modal-close" data-close-modal aria-label="Close">✕</button>
    <x-brand-mark size="sm" class="modal-mark" />
    <h2 id="registerModalTitle">Create your account</h2>
    <p class="modal-sub">One account holds every property you own. Your name is filled in automatically once the admin office links your account.</p>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="field-group">
        <label for="register-email">Email</label>
        <input id="register-email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email', 'register')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>
      <div class="field-group">
  <label for="register-tin">TIN number</label>
  <input
    id="register-tin"
    type="text"
    name="tin"
    inputmode="numeric"
    pattern="\d{3}-\d{3}-\d{3}(-\d{3,5})?"
    placeholder="123-456-789-000"
    maxlength="20"
    value="{{ old('tin') }}"
    required
  >
  @error('tin', 'register')
    <div class="field-error">{{ $message }}</div>
  @enderror
</div>
      <div class="field-group">
        <label for="register-password">Password</label>
        <input id="register-password" type="password" name="password" required>
        @error('password', 'register')
          <div class="field-error">{{ $message }}</div>
        @enderror
      </div>
      <div class="field-group">
        <label for="register-password-confirm">Confirm password</label>
        <input id="register-password-confirm" type="password" name="password_confirmation" required>
      </div>
      <div class="field-row-between" style="justify-content:flex-start;">
        <label class="remember-me" style="align-items:flex-start;">
          <input type="checkbox" name="privacy_policy" value="1" required style="margin-top:2px;">
          <span>I have read and agree to the <a href="{{ route('privacy-policy') }}" target="_blank" rel="noopener" style="color:var(--green); font-weight:500;">Privacy Policy</a>.</span>
        </label>
      </div>
      @error('privacy_policy', 'register')
        <div class="field-error" style="margin:-10px 0 14px;">{{ $message }}</div>
      @enderror
      <button type="submit" class="modal-submit">Create account</button>
    </form>
    <div class="modal-switch">Already have an account? <button type="button" data-switch-to="login">Log in</button></div>
  </div>
</div>
@endguest

<!-- toast notifications -->
<div id="toastStack" aria-live="polite" aria-atomic="true"></div>
<style>
  #toastStack{
    position:fixed; top:20px; right:20px; z-index:200;
    display:flex; flex-direction:column; gap:10px; max-width:360px;
    width:calc(100% - 40px);
  }
  @media (max-width:520px){ #toastStack{ left:20px; right:20px; max-width:none; } }
  .toast{
    display:flex; align-items:flex-start; gap:10px;
    background:var(--surface); border:1px solid var(--line); border-radius:12px;
    box-shadow:var(--shadow); padding:14px 14px 14px 16px; font-size:13.5px; line-height:1.5;
    border-left:4px solid var(--green);
    opacity:0; transform:translateY(-8px) scale(.98);
    transition:opacity .2s ease, transform .2s ease;
  }
  .toast.is-visible{ opacity:1; transform:translateY(0) scale(1); }
  .toast.toast-error{ border-left-color:#B3402A; }
  .toast.toast-success{ border-left-color:var(--green); }
  .toast-msg{ flex:1; color:var(--ink); }
  .toast-close{
    background:none; border:none; cursor:pointer; color:var(--ink-faint); opacity:.7;
    padding:2px; line-height:1; flex-shrink:0; font-size:14px;
  }
  .toast-close:hover{ opacity:1; }
</style>
<script>
  function showToast(message, type){
    if(!message) return;
    const stack = document.getElementById('toastStack');
    if(!stack) return;
    const el = document.createElement('div');
    el.className = 'toast' + (type === 'error' ? ' toast-error' : ' toast-success');
    el.innerHTML = '<span class="toast-msg"></span><button type="button" class="toast-close" aria-label="Dismiss">✕</button>';
    el.querySelector('.toast-msg').textContent = message;
    stack.appendChild(el);
    requestAnimationFrame(()=> el.classList.add('is-visible'));

    function dismiss(){
      el.classList.remove('is-visible');
      setTimeout(()=> el.remove(), 200);
    }
    el.querySelector('.toast-close').addEventListener('click', dismiss);
    setTimeout(dismiss, 6000);
  }
</script>

<script>
  // mobile nav
  (function(){
    const nav = document.querySelector('.nav');
    const burger = document.getElementById('navBurger');
    const panel = document.getElementById('navMobilePanel');
    if(!nav || !burger || !panel) return;

    function closeNav(){
      nav.classList.remove('is-open');
      burger.setAttribute('aria-expanded', 'false');
    }
    function toggleNav(){
      const open = nav.classList.toggle('is-open');
      burger.setAttribute('aria-expanded', String(open));
    }

    burger.addEventListener('click', toggleNav);
    panel.querySelectorAll('a, button').forEach(el=> el.addEventListener('click', closeNav));
    document.addEventListener('keydown', (e)=>{ if(e.key === 'Escape') closeNav(); });
    document.addEventListener('click', (e)=>{
      if(nav.classList.contains('is-open') && !nav.contains(e.target)) closeNav();
    });
  })();
</script>

<script>
  // auth modals
  (function(){
    const modals = { login: document.getElementById('loginModal'), register: document.getElementById('registerModal') };
    if(!modals.login && !modals.register) return; // user is authenticated, modals not rendered

    let lastFocused = null;

    function openModal(name){
      const modal = modals[name];
      if(!modal) return;
      lastFocused = document.activeElement;
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
      const firstField = modal.querySelector('input');
      if(firstField) setTimeout(()=> firstField.focus(), 200);
    }
    function closeModal(modal){
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      if(lastFocused) lastFocused.focus();
    }
    function closeAll(){
      Object.values(modals).forEach(m=> m && closeModal(m));
    }

    // expose globally so the server-driven "reopen on error" script below,
    // and any future inline script, can call these reliably
    window.openAuthModal = openModal;
    window.closeAuthModal = closeModal;
    window.closeAllAuthModals = closeAll;

    document.querySelectorAll('[data-open-modal]').forEach(btn=>{
      btn.addEventListener('click', ()=> openModal(btn.dataset.openModal));
    });
    document.querySelectorAll('[data-close-modal]').forEach(btn=>{
      btn.addEventListener('click', ()=> closeModal(btn.closest('.modal-overlay')));
    });
    document.querySelectorAll('[data-switch-to]').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        closeAll();
        openModal(btn.dataset.switchTo);
      });
    });
    Object.values(modals).forEach(modal=>{
      if(!modal) return;
      modal.addEventListener('click', (e)=>{ if(e.target === modal) closeModal(modal); });
    });
    document.addEventListener('keydown', (e)=>{
      if(e.key === 'Escape') closeAll();
    });

    // Auto-dash the TIN field as the resident types (###-###-###-#####)
    const tinField = document.getElementById('register-tin');
    if(tinField){
      tinField.addEventListener('input', ()=>{
        const digits = tinField.value.replace(/\D/g, '').slice(0, 15);
        const parts = [digits.slice(0, 3), digits.slice(3, 6), digits.slice(6, 9), digits.slice(9, 15)]
          .filter(p => p.length > 0);
        tinField.value = parts.join('-');
      });
    }
  })();
</script>

<!-- reopen the relevant modal and show a toast if the server redirected back with an error or status -->
@php
  $loginHasErrors = isset($errors) && $errors->hasBag('login') && $errors->login->any();
  $registerHasErrors = isset($errors) && $errors->hasBag('register') && $errors->register->any();
@endphp
<script>
  document.addEventListener('DOMContentLoaded', function(){
    @if ($loginHasErrors)
      if (window.openAuthModal) window.openAuthModal('login');
      showToast(@json($errors->login->first()), 'error');
    @elseif ($registerHasErrors)
      if (window.openAuthModal) window.openAuthModal('register');
      showToast(@json($errors->register->first()), 'error');
    @endif

    @if (session('status'))
      showToast(@json(session('status')), 'success');
    @endif
  });
</script>

<script>
  // signature visual: self-drawing cadastral parcel grid
  (function(){
    const svg = document.getElementById('parcelSvg');
    if(!svg) return;
    const size = 460, cell = 46, gap = 4, cols = Math.floor(size/cell);
    const goldCol = Math.floor(cols*0.62), goldRow = Math.floor(cols*0.28);
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let i = 0;
    for(let r=0;r<cols;r++){
      for(let c=0;c<cols;c++){
        const isGold = r===goldRow && c===goldCol;
        const rect = document.createElementNS('http://www.w3.org/2000/svg','rect');
        rect.setAttribute('x', c*cell+gap/2);
        rect.setAttribute('y', r*cell+gap/2);
        rect.setAttribute('width', cell-gap);
        rect.setAttribute('height', cell-gap);
        rect.setAttribute('rx', 3);
        rect.setAttribute('fill', isGold ? '#C99A2E' : 'none');
        rect.setAttribute('fill-opacity', isGold ? '0.9' : '1');
        rect.setAttribute('stroke', isGold ? 'none' : '#0E6B52');
        rect.setAttribute('stroke-opacity', '0.14');
        rect.classList.add('cell');
        if(isGold) rect.classList.add('hero-parcel');
        if(!reduceMotion){
          const delay = (Math.abs(r-goldRow)+Math.abs(c-goldCol)) * 0.025;
          rect.style.animationDelay = delay+'s, '+(delay+1.2)+'s';
        }
        svg.appendChild(rect);
        i++;
      }
    }
  })();

  // count-up numbers
  const counters = document.querySelectorAll('.count-up');
  const fmt = (val, decimals) => decimals ? val.toFixed(decimals) : Math.round(val).toString();
  const countIO = new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
      if(!entry.isIntersecting) return;
      const el = entry.target;
      countIO.unobserve(el);
      const target = parseFloat(el.dataset.target);
      const decimals = parseInt(el.dataset.decimals || '0', 10);
      const prefix = el.dataset.prefix || '';
      const suffix = el.dataset.suffix || '';
      if(window.matchMedia('(prefers-reduced-motion: reduce)').matches){
        el.textContent = prefix+fmt(target, decimals)+suffix;
        return;
      }
      const duration = 1100, start = performance.now();
      function tick(now){
        const p = Math.min(1, (now-start)/duration);
        const eased = 1 - Math.pow(1-p, 3);
        el.textContent = prefix+fmt(target*eased, decimals)+suffix;
        if(p<1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    });
  }, { threshold: 0.6 });
  counters.forEach(el=> countIO.observe(el));

  // scroll reveal
  const reveals = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries)=>{
    entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('is-visible'); io.unobserve(e.target); } });
  }, { threshold: 0.15 });
  reveals.forEach(el=> io.observe(el));

  // faq accordion
  document.querySelectorAll('.faq-item').forEach(item=>{
    const q = item.querySelector('.faq-q');
    const a = item.querySelector('.faq-a');
    q.addEventListener('click', ()=>{
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(o=>{
        if(o!==item){ o.classList.remove('open'); o.querySelector('.faq-a').style.maxHeight=null; }
      });
      if(isOpen){ item.classList.remove('open'); a.style.maxHeight=null; }
      else{ item.classList.add('open'); a.style.maxHeight = a.scrollHeight+'px'; }
    });
  });
</script>

</body>
</html>