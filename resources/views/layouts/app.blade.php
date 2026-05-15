<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPSI — {{ $title ?? 'Sistema' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        /* ══════════════════════════════════════
           TOKENS — LIGHT
        ══════════════════════════════════════ */
        :root, [data-theme="light"] {
            --mint:        #7ec8a4;
            --lavender:    #8ba7d9;
            --slate:       #7a8fbf;
            --green:       #5a9e7a;
            --indigo:      #6b5fb5;
            --sidebar-bg:  #2c3e6b;
            --sidebar-bg2: #354880;

            --bg:          #f4f6fb;
            --surface:     #ffffff;
            --surface2:    #f0f3fa;
            --border:      #d4daf0;

            --text:        #2d3a6b;
            --text-muted:  #8090b8;
            --text-soft:   #4a5a8a;

            --input-bg:    #fafbff;
            --input-border:#d4daf0;
            --input-focus: rgba(139,167,217,0.25);

            --table-row-hover: rgba(139,167,217,0.07);
            --table-border:    #eef0f8;

            --shadow:      rgba(44,62,107,0.08);
            --watermark:   rgba(107,95,181,0.04);
        }

        /* ══════════════════════════════════════
           TOKENS — DARK
        ══════════════════════════════════════ */
        [data-theme="dark"] {
            --sidebar-bg:  #1a1f35;
            --sidebar-bg2: #222840;

            --bg:          #12151f;
            --surface:     #1c2030;
            --surface2:    #222840;
            --border:      #2e3550;

            --text:        #e2e8f8;
            --text-muted:  #6878a8;
            --text-soft:   #8898c8;

            --input-bg:    #1a1f35;
            --input-border:#2e3550;
            --input-focus: rgba(139,167,217,0.15);

            --table-row-hover: rgba(139,167,217,0.06);
            --table-border:    #2a3048;

            --shadow:      rgba(0,0,0,0.3);
            --watermark:   rgba(139,167,217,0.03);
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Figtree', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
            transition: background 0.25s, color 0.25s;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 235px; height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 100;
            transition: transform 0.3s ease, background 0.25s;
            box-shadow: 4px 0 24px rgba(0,0,0,0.2);
        }
        .sidebar-logo {
            padding: 1.4rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex; align-items: center; gap: 0.7rem;
            transition: all 0.3s ease;
        }
        .logo-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--mint), var(--green));
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; box-shadow: 0 4px 12px rgba(126,200,164,0.3);
        }
        .brand-name { font-size: 1.25rem; font-weight: 800; color: #fff; letter-spacing: 0.04em; }
        .brand-sub  { font-size: 0.6rem; color: rgba(255,255,255,0.3); letter-spacing: 0.14em; text-transform: uppercase; }

        .sidebar-nav { flex: 1; padding: 1rem 0.75rem; overflow-y: auto; }
        .nav-label {
            font-size: 0.62rem; font-weight: 700;
            color: rgba(255,255,255,0.22);
            letter-spacing: 0.14em; text-transform: uppercase;
            padding: 0.8rem 0.6rem 0.35rem;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.6rem 0.85rem; border-radius: 0.55rem;
            color: rgba(255,255,255,0.5);
            text-decoration: none; font-size: 0.875rem; font-weight: 500;
            transition: all 0.18s; margin-bottom: 0.1rem;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
        .sidebar-link.active {
            background: rgba(126,200,164,0.15);
            color: var(--mint); font-weight: 600;
            border-left: 3px solid var(--mint);
            padding-left: calc(0.85rem - 3px);
        }
        .sidebar-link svg { flex-shrink: 0; }

        /* ── Sidebar colapsado ── */
        .sidebar { overflow: hidden; }
        .sidebar-link, .sidebar-link * { overflow: hidden; }
        .sidebar.collapsed { width: 64px; }
        .sidebar.collapsed .brand-name,
        .sidebar.collapsed .brand-sub,
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .link-text,
        .sidebar.collapsed .user-name,
        .sidebar.collapsed .user-email,
        .sidebar.collapsed .btn-logout-text,
        .sidebar.collapsed .theme-toggle { opacity: 0; width: 0; overflow: hidden; white-space: nowrap; }
        .sidebar.collapsed .sidebar-link { justify-content: center; padding: 0.65rem 0; gap: 0; }
        .sidebar.collapsed .sidebar-logo { 
            justify-content: center; 
            padding: 1rem 0; 
            gap: 0;
        }
        .sidebar.collapsed .sidebar-logo > div { 
            flex: 0 !important;
            width: 0; 
            overflow: hidden; 
            opacity: 0;
            margin: 0;
            padding: 0;
        }
        .sidebar.collapsed .sidebar-logo .logo-icon { 
            flex: 0 0 auto !important;
            width: 38px !important;
            opacity: 1 !important;
            overflow: visible !important;
        }
        .sidebar.collapsed .user-card    { justify-content: center; gap: 0; padding: 0.6rem 0; }
        .sidebar.collapsed .user-card > div:last-child { width: 0; overflow: hidden; }
        .sidebar.collapsed .btn-logout   { justify-content: center; padding: 0.55rem 0; }
        .sidebar.collapsed .sidebar-nav  { padding: 1rem 0.5rem; }
        .main-wrapper { transition: margin-left 0.3s ease; }
        .main-wrapper.collapsed { margin-left: 64px; }
        .link-text { transition: opacity 0.2s, width 0.3s; white-space: nowrap; }

        /* ── Botón toggle sidebar (desktop) ── */
        .sidebar-toggle-btn {
            position: fixed; top: 1rem; left: 220px;
            width: 26px; height: 26px; border-radius: 50%;
            background: var(--sidebar-bg2); border: 2px solid rgba(255,255,255,0.15);
            cursor: pointer; z-index: 200;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.7); transition: left 0.3s ease, background 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }
        .sidebar-toggle-btn:hover { background: var(--mint); color: #fff; border-color: var(--mint); }
        .sidebar-toggle-btn.collapsed { left: 50px; }
        @media (max-width: 768px) { .sidebar-toggle-btn { display: none; } }

        .sidebar-footer {
            padding: 0.85rem 0.75rem 1rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .user-card {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 0.75rem; border-radius: 0.55rem;
            background: rgba(255,255,255,0.05); margin-bottom: 0.65rem;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, var(--lavender), var(--indigo));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; color: white; flex-shrink: 0;
        }
        .user-name  { font-size: 0.82rem; font-weight: 600; color: #fff; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; }
        .user-email { font-size: 0.68rem; color: rgba(255,255,255,0.28); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px; }
        .btn-logout {
            display: flex; align-items: center; gap: 0.6rem;
            width: 100%; padding: 0.55rem 0.85rem;
            background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);
            border-radius: 0.55rem; color: #fca5a5;
            font-size: 0.82rem; font-weight: 500; cursor: pointer;
            transition: all 0.18s; text-align: left;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.16); color: #fecaca; }

        /* ── Theme toggle ── */
        .theme-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 50%;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
            cursor: pointer; color: rgba(255,255,255,0.7);
            transition: all 0.18s; flex-shrink: 0;
        }
        .theme-toggle:hover { background: rgba(255,255,255,0.15); color: #fff; }

        /* ── Main ── */
        .main-wrapper { margin-left: 235px; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Topbar mobile ── */
        .topbar {
            display: none; align-items: center; justify-content: space-between;
            padding: 0.8rem 1.25rem; background: var(--sidebar-bg);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            position: sticky; top: 0; z-index: 99;
        }
        .hamburger { background: none; border: none; cursor: pointer; color: var(--mint); padding: 0.2rem; }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.55); z-index: 99;
        }
        .sidebar-overlay.active { display: block; }

        /* ── Page ── */
        .page-content { flex: 1; padding: 1.75rem 2rem; position: relative; z-index: 1; }
        .watermark {
            position: fixed; top: 50%; left: 55%;
            transform: translate(-50%, -50%);
            font-size: 18vw; font-weight: 900;
            color: var(--watermark);
            pointer-events: none; z-index: 0; user-select: none; white-space: nowrap;
        }

        /* ── Page header ── */
        .page-header-title { font-size: 1.5rem; font-weight: 700; color: var(--text); margin: 0 0 0.2rem; }
        .page-header-sub   { font-size: 0.85rem; color: var(--text-muted); margin: 0; }

        /* ── Tables ── */
        .table {
            border-radius: 0.75rem; overflow: hidden;
            background: var(--surface);
            box-shadow: 0 1px 6px var(--shadow);
        }
        .table thead th {
            background: linear-gradient(135deg, var(--sidebar-bg), var(--sidebar-bg2)) !important;
            color: rgba(255,255,255,0.82) !important;
            font-size: 0.78rem !important; font-weight: 600 !important;
            letter-spacing: 0.07em !important; text-transform: uppercase !important;
            border: none !important; padding: 0.85rem 1rem !important;
        }
        .table tbody tr { transition: background 0.15s; }
        .table tbody tr:hover { background: var(--table-row-hover) !important; }
        .table tbody td {
            vertical-align: middle; font-size: 0.9rem;
            color: var(--text) !important;
            background: var(--surface) !important;
            border-color: var(--table-border) !important;
            padding: 0.75rem 1rem !important;
        }
        .table tbody tr:hover td { background: var(--table-row-hover) !important; }
        .table-bordered { border: none !important; }
        .table-hover > tbody > tr:hover > * { --bs-table-accent-bg: var(--table-row-hover) !important; color: var(--text) !important; }

        /* ── Forms ── */
        .form-label { font-size: 0.82rem; font-weight: 600; color: var(--text-soft); margin-bottom: 0.35rem; }
        .form-control, .form-select {
            border: 1.5px solid var(--input-border) !important;
            border-radius: 0.55rem !important; font-size: 0.9rem !important;
            color: var(--text) !important; padding: 0.6rem 0.85rem !important;
            background: var(--input-bg) !important;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.25s !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--lavender) !important;
            box-shadow: 0 0 0 3px var(--input-focus) !important;
            background: var(--surface) !important;
        }
        .form-control::placeholder { color: var(--text-muted) !important; }
        .form-select option { background: var(--surface); color: var(--text); }

        /* ── Buttons ── */
        .btn { border-radius: 0.5rem !important; font-weight: 500 !important; font-size: 0.875rem !important; }
        .btn-primary {
            background: linear-gradient(135deg, var(--lavender), var(--slate)) !important;
            border-color: transparent !important; color: white !important;
        }
        .btn-primary:hover { background: linear-gradient(135deg, var(--slate), #6070af) !important; border-color: transparent !important; }
        .btn-success {
            background: linear-gradient(135deg, var(--mint), var(--green)) !important;
            border-color: transparent !important; color: white !important;
        }
        .btn-warning {
            background: linear-gradient(135deg, #f0c060, #e0a840) !important;
            border-color: transparent !important; color: white !important;
        }
        .btn-info {
            background: linear-gradient(135deg, var(--lavender), #7090c8) !important;
            border-color: transparent !important; color: white !important;
        }
        .btn-secondary {
            background: var(--surface2) !important;
            border-color: var(--border) !important; color: var(--text-soft) !important;
        }
        .btn-secondary:hover { background: var(--border) !important; }

        /* ── Badges ── */
        .badge { border-radius: 20px !important; padding: 4px 10px !important; font-size: 0.75rem !important; font-weight: 600 !important; }
        .bg-success   { background: rgba(90,158,122,0.15) !important; color: var(--green) !important; }
        .bg-warning   { background: rgba(240,192,96,0.15) !important; color: #b07820 !important; }
        .bg-secondary { background: rgba(122,143,191,0.15) !important; color: var(--slate) !important; }
        .bg-info      { background: rgba(139,167,217,0.15) !important; color: var(--slate) !important; }

        /* ── Alerts ── */
        .alert-success {
            background: rgba(126,200,164,0.12) !important; border-color: var(--mint) !important;
            color: var(--green) !important; border-radius: 0.6rem !important;
        }

        /* ── Card / form wrapper ── */
        .card {
            border: none !important; border-radius: 0.85rem !important;
            box-shadow: 0 1px 6px var(--shadow) !important;
            background: var(--surface) !important;
        }
        .form-card {
            background: var(--surface);
            border-radius: 0.85rem; padding: 2rem;
            box-shadow: 0 1px 6px var(--shadow); max-width: 600px;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .topbar { display: flex; }
            .page-content { padding: 1.25rem 1rem; }
        }

        /* ── Modal confirmación ── */
        .confirm-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 9999;
            align-items: center; justify-content: center;
        }
        .confirm-overlay.active { display: flex; }
        .confirm-box {
            background: var(--surface); border-radius: 1rem;
            padding: 2rem; max-width: 380px; width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border: 1px solid var(--border);
            text-align: center;
        }
        .confirm-icon {
            width: 52px; height: 52px; border-radius: 50%;
            background: rgba(239,68,68,0.1);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
        }
        .confirm-title { font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 0.4rem; }
        .confirm-msg   { font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem; }
        .confirm-actions { display: flex; gap: 0.75rem; justify-content: center; }

        /* ── Search bar ── */
        .search-bar {
            display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;
        }
        .search-input-wrap {
            position: relative; flex: 1; min-width: 220px;
        }
        .search-icon {
            position: absolute; left: 0.75rem; top: 50%;
            transform: translateY(-50%); color: var(--text-muted); pointer-events: none;
        }
        .search-input {
            width: 100%;
            padding: 0.55rem 2.2rem 0.55rem 2.4rem;
            border: 1.5px solid var(--input-border);
            border-radius: 0.55rem;
            background: var(--input-bg);
            color: var(--text);
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .search-input:focus {
            border-color: var(--lavender);
            box-shadow: 0 0 0 3px var(--input-focus);
            background: var(--surface);
        }
        .search-input::placeholder { color: var(--text-muted); }
        .search-clear {
            position: absolute; right: 0.75rem; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted); text-decoration: none;
            font-size: 0.8rem; line-height: 1;
            transition: color 0.15s;
        }
        .search-clear:hover { color: var(--text); }
        .filter-select {
            padding: 0.55rem 0.85rem;
            border: 1.5px solid var(--input-border);
            border-radius: 0.55rem;
            background: var(--input-bg);
            color: var(--text);
            font-size: 0.875rem;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .filter-select:focus { border-color: var(--lavender); }
        .search-results-info {
            font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.75rem;
        }
        .search-results-info strong { color: var(--text); }

        /* ── Dark mode overrides para Bootstrap ── */
        [data-theme="dark"] .table,
        [data-theme="dark"] .table td,
        [data-theme="dark"] .table th,
        [data-theme="dark"] .table tbody,
        [data-theme="dark"] .table-hover tbody tr {
            background-color: var(--surface) !important;
            color: var(--text) !important;
            border-color: var(--table-border) !important;
        }
        [data-theme="dark"] .table-hover tbody tr:hover td {
            background-color: var(--table-row-hover) !important;
            color: var(--text) !important;
        }
        [data-theme="dark"] .form-card,
        [data-theme="dark"] .card {
            background: var(--surface) !important;
            color: var(--text) !important;
        }
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] p,
        [data-theme="dark"] label,
        [data-theme="dark"] span:not(.badge) {
            color: var(--text);
        }
        [data-theme="dark"] .page-header-title { color: var(--text) !important; }
        [data-theme="dark"] .page-header-sub   { color: var(--text-muted) !important; }
        [data-theme="dark"] .form-label        { color: var(--text-soft) !important; }
        [data-theme="dark"] strong             { color: var(--text) !important; }
    </style>
    {{-- Aplicar tema antes de render para evitar flash --}}
    <script>
        (function() {
            const t = localStorage.getItem('sipsi-theme') || 'light';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body>

<div class="watermark">SIPSI</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

{{-- ── Botón toggle sidebar (desktop) ── --}}
<button class="sidebar-toggle-btn" id="sidebarToggleBtn" onclick="toggleSidebarDesktop()" title="Ocultar/mostrar menú">
    <svg id="toggleIcon" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
    </svg>
</button>

{{-- ── Sidebar ── --}}
<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg width="20" height="20" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <div style="flex:1;">
            <div class="brand-name">SIPSI</div>
            <div class="brand-sub">Psiquiatría Hospitalaria</div>
        </div>
        {{-- Toggle desktop --}}
        <button class="theme-toggle" onclick="toggleTheme()" title="Cambiar tema" id="themeBtn">
            <svg id="iconSun" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
            <svg id="iconMoon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Gestión clínica</div>

        <a href="{{ route('chatbot.page') }}" class="sidebar-link {{ request()->routeIs('chatbot.page') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="link-text">Asistente</span>
        </a>

        <a href="{{ route('juzgados.index') }}" class="sidebar-link {{ request()->routeIs('juzgados.index') || request()->routeIs('juzgados.create') || request()->routeIs('juzgados.edit') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
            <span class="link-text">Juzgados</span>
        </a>

        <a href="{{ route('pacientes.index') }}" class="sidebar-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="link-text">Pacientes</span>
        </a>

        <a href="{{ route('oficios.index') }}" class="sidebar-link {{ request()->routeIs('oficios.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="link-text">Oficios</span>
        </a>

        <a href="{{ route('profesionales.index') }}" class="sidebar-link {{ request()->routeIs('profesionales.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="link-text">Profesionales</span>
        </a>

        <div class="nav-label" style="margin-top:0.5rem;">Informes</div>

        <a href="{{ route('juzgados.estadisticas') }}" class="sidebar-link {{ request()->routeIs('juzgados.estadisticas') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            <span class="link-text">Est. Juzgados</span>
        </a>

        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="link-text">Estadísticas</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="btn-logout-text">Cerrar sesión</span>
            </button>
        </form>
    </div>
</aside>

{{-- ── Main ── --}}
<div class="main-wrapper">

    {{-- Topbar mobile --}}
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:0.6rem;">
            <div class="logo-icon" style="width:30px;height:30px;border-radius:8px;">
                <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <span class="brand-name">SIPSI</span>
        </div>
        <div style="display:flex;gap:0.5rem;align-items:center;">
            <button class="theme-toggle" onclick="toggleTheme()">
                <svg id="iconSunMobile" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
                <svg id="iconMoonMobile" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
            <button class="hamburger" onclick="toggleSidebar()">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</div>

{{-- ── Modal confirmación eliminar ── --}}
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon">
            <svg width="24" height="24" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <div class="confirm-title">¿Eliminar registro?</div>
        <div class="confirm-msg">Esta acción no se puede deshacer.</div>
        <div class="confirm-actions">
            <button class="btn btn-secondary" onclick="closeConfirm()">Cancelar</button>
            <button class="btn btn-danger" id="confirmBtn" style="background:linear-gradient(135deg,#ef4444,#dc2626)!important;border-color:transparent!important;color:white!important;">Eliminar</button>
        </div>
    </div>
</div>

<script>
    let _pendingForm = null;

    function confirmDelete(formEl) {
        _pendingForm = formEl;
        document.getElementById('confirmOverlay').classList.add('active');
    }

    function closeConfirm() {
        _pendingForm = null;
        document.getElementById('confirmOverlay').classList.remove('active');
    }

    document.getElementById('confirmBtn').addEventListener('click', function() {
        if (_pendingForm) _pendingForm.submit();
        closeConfirm();
    });

    document.getElementById('confirmOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeConfirm();
    });

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    function toggleSidebarDesktop() {
        const sidebar  = document.getElementById('sidebar');
        const main     = document.querySelector('.main-wrapper');
        const btn      = document.getElementById('sidebarToggleBtn');
        const icon     = document.getElementById('toggleIcon');
        const collapsed = sidebar.classList.toggle('collapsed');
        main.classList.toggle('collapsed', collapsed);
        btn.classList.toggle('collapsed', collapsed);
        icon.innerHTML = collapsed
            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>';
        localStorage.setItem('sipsi-sidebar', collapsed ? 'collapsed' : 'open');
    }

    // Restaurar estado del sidebar al cargar
    (function() {
        if (localStorage.getItem('sipsi-sidebar') === 'collapsed') {
            const sidebar = document.getElementById('sidebar');
            const main    = document.querySelector('.main-wrapper');
            const btn     = document.getElementById('sidebarToggleBtn');
            const icon    = document.getElementById('toggleIcon');
            sidebar.classList.add('collapsed');
            main.classList.add('collapsed');
            btn.classList.add('collapsed');
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>';
        }
    })();

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('sipsi-theme', theme);
        const isDark = theme === 'dark';
        document.getElementById('iconSun').style.display  = isDark ? 'block' : 'none';
        document.getElementById('iconMoon').style.display = isDark ? 'none'  : 'block';
        document.getElementById('iconSunMobile').style.display  = isDark ? 'block' : 'none';
        document.getElementById('iconMoonMobile').style.display = isDark ? 'none'  : 'block';
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme');
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    applyTheme(localStorage.getItem('sipsi-theme') || 'light');
</script>

{{-- ══════════════════════════════════════
     CHATBOT PANEL LATERAL
══════════════════════════════════════ --}}
<style>
    /* ── Botón flotante ── */
    .chat-fab {
        position: fixed; bottom: 1.75rem; right: 1.75rem;
        width: 52px; height: 52px; border-radius: 50%;
        background: linear-gradient(135deg, var(--lavender), var(--indigo));
        border: none; cursor: pointer; z-index: 9000;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 20px rgba(107,95,181,0.45);
        transition: transform 0.2s, box-shadow 0.2s;
        color: white;
    }
    .chat-fab:hover { transform: scale(1.08); box-shadow: 0 6px 28px rgba(107,95,181,0.55); }
    .chat-fab .badge-dot {
        position: absolute; top: 3px; right: 3px;
        width: 10px; height: 10px; border-radius: 50%;
        background: #ef4444; border: 2px solid var(--bg);
        display: none;
    }
    .chat-fab.has-notif .badge-dot { display: block; }

    /* ── Ventana ── */
    .chat-window {
        position: fixed; bottom: 5.5rem; right: 1.75rem;
        width: 340px; max-height: 480px;
        background: var(--surface);
        border-radius: 1.1rem;
        box-shadow: 0 12px 48px rgba(0,0,0,0.22);
        border: 1px solid var(--border);
        display: flex; flex-direction: column;
        z-index: 8999;
        transform: scale(0.92) translateY(12px);
        opacity: 0; pointer-events: none;
        transition: transform 0.22s cubic-bezier(.34,1.56,.64,1), opacity 0.18s;
    }
    .chat-window.open {
        transform: scale(1) translateY(0);
        opacity: 1; pointer-events: all;
    }
    @media (max-width: 480px) {
        .chat-window { width: calc(100vw - 2rem); right: 1rem; }
    }

    /* ── Header ── */
    .chat-header {
        padding: 0.85rem 1rem;
        background: linear-gradient(135deg, var(--sidebar-bg), var(--sidebar-bg2));
        border-radius: 1.1rem 1.1rem 0 0;
        display: flex; align-items: center; gap: 0.65rem;
    }
    .chat-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, var(--mint), var(--green));
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .chat-header-info { flex: 1; }
    .chat-header-name   { font-size: 0.875rem; font-weight: 700; color: #fff; }
    .chat-header-status { font-size: 0.7rem; color: var(--mint); display: flex; align-items: center; gap: 0.3rem; }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--mint); }
    .chat-close {
        background: none; border: none; cursor: pointer;
        color: rgba(255,255,255,0.5); padding: 0.2rem;
        transition: color 0.15s;
    }
    .chat-close:hover { color: #fff; }

    /* ── Mensajes ── */
    .chat-messages {
        flex: 1; overflow-y: auto; padding: 0.85rem;
        display: flex; flex-direction: column; gap: 0.6rem;
        scroll-behavior: smooth;
    }
    .chat-messages::-webkit-scrollbar { width: 4px; }
    .chat-messages::-webkit-scrollbar-track { background: transparent; }
    .chat-messages::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

    .msg {
        max-width: 85%; padding: 0.55rem 0.85rem;
        border-radius: 1rem; font-size: 0.845rem; line-height: 1.45;
        white-space: pre-wrap; word-break: break-word;
        animation: msgIn 0.18s ease;
    }
    @keyframes msgIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }

    .msg.bot {
        background: var(--surface2);
        color: var(--text);
        border-bottom-left-radius: 4px;
        align-self: flex-start;
    }
    .msg.user {
        background: linear-gradient(135deg, var(--lavender), var(--indigo));
        color: white;
        border-bottom-right-radius: 4px;
        align-self: flex-end;
    }
    .msg-typing {
        display: flex; gap: 4px; align-items: center;
        padding: 0.6rem 0.85rem;
        background: var(--surface2); border-radius: 1rem; border-bottom-left-radius: 4px;
        align-self: flex-start;
    }
    .msg-typing span {
        width: 6px; height: 6px; border-radius: 50%;
        background: var(--text-muted);
        animation: bounce 1.2s infinite;
    }
    .msg-typing span:nth-child(2) { animation-delay: 0.2s; }
    .msg-typing span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounce {
        0%,60%,100% { transform: translateY(0); }
        30%          { transform: translateY(-5px); }
    }

    /* ── Sugerencias ── */
    .chat-suggestions {
        padding: 0 0.85rem 0.5rem;
        display: flex; flex-wrap: wrap; gap: 0.4rem;
    }
    .sug-btn {
        font-size: 0.72rem; padding: 0.3rem 0.65rem;
        border-radius: 20px; border: 1px solid var(--border);
        background: var(--surface2); color: var(--text-soft);
        cursor: pointer; transition: all 0.15s; white-space: nowrap;
    }
    .sug-btn:hover {
        background: var(--lavender); color: white; border-color: var(--lavender);
    }

    /* ── Input ── */
    .chat-input-row {
        padding: 0.65rem 0.85rem 0.85rem;
        display: flex; gap: 0.5rem; align-items: center;
        border-top: 1px solid var(--border);
    }
    .chat-input {
        flex: 1; padding: 0.55rem 0.85rem;
        border: 1.5px solid var(--input-border);
        border-radius: 2rem; font-size: 0.875rem;
        background: var(--input-bg); color: var(--text);
        outline: none; transition: border-color 0.2s;
    }
    .chat-input:focus { border-color: var(--lavender); }
    .chat-input::placeholder { color: var(--text-muted); }
    .chat-send {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, var(--lavender), var(--indigo));
        border: none; cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: white; transition: transform 0.15s, opacity 0.15s;
    }
    .chat-send:hover { transform: scale(1.08); }
    .chat-send:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

    .chat-mic {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--surface2);
        border: 1.5px solid var(--border);
        cursor: pointer; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); transition: all 0.15s;
    }
    .chat-mic:hover { background: var(--lavender); color: white; border-color: var(--lavender); }
    .chat-mic.recording {
        background: #ef4444; border-color: #ef4444; color: white;
        animation: pulse-mic 1s infinite;
    }
    @keyframes pulse-mic {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.4); }
        50%       { box-shadow: 0 0 0 7px rgba(239,68,68,0); }
    }

    /* ── Alertas proactivas ── */
    .msg-alerta {
        align-self: flex-start;
        max-width: 95%;
        border-radius: 0.85rem;
        border-bottom-left-radius: 4px;
        padding: 0.6rem 0.85rem;
        font-size: 0.82rem;
        line-height: 1.5;
        animation: msgIn 0.18s ease;
    }
    .msg-alerta.critico    { background: #fef2f2; border-left: 3px solid #ef4444; color: #7f1d1d; }
    .msg-alerta.advertencia{ background: #fffbeb; border-left: 3px solid #f59e0b; color: #78350f; }
    .msg-alerta.info       { background: #eff6ff; border-left: 3px solid #3b82f6; color: #1e3a5f; }
    .msg-alerta .alerta-titulo { font-weight: 700; margin-bottom: 0.3rem; }
    .msg-alerta .alerta-item   { margin: 0.2rem 0 0 0.5rem; }
    .msg-alerta .alerta-detalle{ font-size: 0.75rem; opacity: 0.75; margin-left: 0.9rem; }
</style>

{{-- Botón flotante ── abre panel lateral --}}
@if(!request()->routeIs('chatbot.page'))
<button class="chat-fab" id="chatFab" onclick="toggleChatPanel()" title="Asistente SIPSI">
    <div class="badge-dot"></div>
    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
    </svg>
</button>

{{-- Panel lateral del chat --}}
<div id="chatPanel" style="
    position:fixed; top:0; right:-420px; width:400px; height:100vh;
    background:var(--surface); border-left:1px solid var(--border);
    box-shadow:-8px 0 32px rgba(0,0,0,0.18);
    z-index:9999; display:flex; flex-direction:column;
    transition:right 0.3s cubic-bezier(.4,0,.2,1);
">
    {{-- Header --}}
    <div style="padding:1rem 1.1rem; background:linear-gradient(135deg,var(--sidebar-bg),var(--sidebar-bg2)); display:flex; align-items:center; gap:0.65rem; flex-shrink:0;">
        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--mint),var(--green));display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:0.875rem;font-weight:700;color:#fff;">Asistente SIPSI</div>
            <div style="font-size:0.7rem;color:var(--mint);display:flex;align-items:center;gap:0.3rem;">
                <div style="width:6px;height:6px;border-radius:50%;background:var(--mint);"></div> En línea
            </div>
        </div>
        <button onclick="toggleChatPanel()" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.5);padding:0.2rem;transition:color .15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mensajes --}}
    <div id="chatMessages" style="flex:1;overflow-y:auto;padding:0.85rem;display:flex;flex-direction:column;gap:0.6rem;scroll-behavior:smooth;"></div>

    {{-- Sugerencias --}}
    <div id="chatSuggestions" style="padding:0 0.85rem 0.5rem;display:flex;flex-wrap:wrap;gap:0.4rem;">
        <button class="sug-btn" onclick="enviarSugerencia(this)">Alertas</button>
        <button class="sug-btn" onclick="enviarSugerencia(this)">Resumen general</button>
        <button class="sug-btn" onclick="enviarSugerencia(this)">Oficios pendientes</button>
        <button class="sug-btn" onclick="enviarSugerencia(this)">Próximos turnos</button>
        <button class="sug-btn" onclick="enviarSugerencia(this)">Informes sin enviar</button>
    </div>

    {{-- Input --}}
    <div style="padding:0.65rem 0.85rem 0.85rem;display:flex;gap:0.5rem;align-items:center;border-top:1px solid var(--border);flex-shrink:0;">
        <input type="text" id="chatInput" placeholder="Escribí tu consulta..."
            onkeydown="if(event.key==='Enter') enviarMensaje()"
            style="flex:1;padding:0.55rem 0.85rem;border:1.5px solid var(--input-border);border-radius:2rem;font-size:0.875rem;background:var(--input-bg);color:var(--text);outline:none;transition:border-color .2s;"
            onfocus="this.style.borderColor='var(--lavender)'" onblur="this.style.borderColor='var(--input-border)'">
        <button class="chat-send" id="chatSendBtn" onclick="enviarMensaje()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </div>
</div>

{{-- Overlay --}}
<div id="chatOverlay" onclick="toggleChatPanel()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.35);z-index:9998;"></div>

<style>
    .sug-btn {
        font-size:0.72rem;padding:0.3rem 0.65rem;border-radius:20px;
        border:1px solid var(--border);background:var(--surface2);
        color:var(--text-soft);cursor:pointer;transition:all .15s;white-space:nowrap;
    }
    .sug-btn:hover { background:var(--lavender);color:white;border-color:var(--lavender); }
    .msg { max-width:85%;padding:0.55rem 0.85rem;border-radius:1rem;font-size:0.845rem;line-height:1.45;white-space:pre-wrap;word-break:break-word;animation:msgIn .18s ease; }
    @keyframes msgIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
    .msg.bot  { background:var(--surface2);color:var(--text);border-bottom-left-radius:4px;align-self:flex-start; }
    .msg.user { background:linear-gradient(135deg,var(--lavender),var(--indigo));color:white;border-bottom-right-radius:4px;align-self:flex-end; }
    .msg-typing { display:flex;gap:4px;align-items:center;padding:0.6rem 0.85rem;background:var(--surface2);border-radius:1rem;border-bottom-left-radius:4px;align-self:flex-start; }
    .msg-typing span { width:6px;height:6px;border-radius:50%;background:var(--text-muted);animation:bounce 1.2s infinite; }
    .msg-typing span:nth-child(2){animation-delay:.2s} .msg-typing span:nth-child(3){animation-delay:.4s}
    @keyframes bounce{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-5px)}}
    .msg-alerta{align-self:flex-start;max-width:95%;border-radius:.85rem;border-bottom-left-radius:4px;padding:.6rem .85rem;font-size:.82rem;line-height:1.5;animation:msgIn .18s ease;}
    .msg-alerta.critico{background:#fef2f2;border-left:3px solid #ef4444;color:#7f1d1d;}
    .msg-alerta.advertencia{background:#fffbeb;border-left:3px solid #f59e0b;color:#78350f;}
    .msg-alerta.info{background:#eff6ff;border-left:3px solid #3b82f6;color:#1e3a5f;}
    .msg-alerta .alerta-titulo{font-weight:700;margin-bottom:.3rem;}
    .msg-alerta .alerta-item{margin:.2rem 0 0 .5rem;}
    .msg-alerta .alerta-detalle{font-size:.75rem;opacity:.75;margin-left:.9rem;}
</style>

<script>
(function() {
    let panelOpen = false;
    let initialized = false;

    window.toggleChatPanel = function() {
        panelOpen = !panelOpen;
        const panel   = document.getElementById('chatPanel');
        const overlay = document.getElementById('chatOverlay');
        const fab     = document.getElementById('chatFab');

        panel.style.right   = panelOpen ? '0' : '-420px';
        overlay.style.display = panelOpen ? 'block' : 'none';
        fab.classList.remove('has-notif');

        if (panelOpen && !initialized) {
            initialized = true;
            agregarMensajeBot('¡Hola! 👋 Soy el asistente de SIPSI.\n\nPodés preguntarme cosas como:\n• *buscar paciente García*\n• *oficio N° 123*\n• *turnos de hoy*\n\nEscribí *ayuda* para ver todas las opciones.');
            cargarAlertas();
        }
        if (panelOpen) setTimeout(() => document.getElementById('chatInput').focus(), 300);
    };

    window.enviarSugerencia = function(btn) {
        document.getElementById('chatInput').value = btn.textContent;
        enviarMensaje();
    };

    window.enviarMensaje = async function() {
        const input = document.getElementById('chatInput');
        const texto = input.value.trim();
        if (!texto) return;
        input.value = '';
        document.getElementById('chatSendBtn').disabled = true;
        agregarMensajeUsuario(texto);
        const typingEl = agregarTyping();
        try {
            const res  = await fetch('{{ route("chatbot.responder") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ mensaje: texto })
            });
            const data = await res.json();
            typingEl.remove();
            agregarMensajeBot(data.respuesta);
        } catch(e) {
            typingEl.remove();
            agregarMensajeBot('Ocurrió un error. Intentá de nuevo.');
        }
        document.getElementById('chatSendBtn').disabled = false;
        input.focus();
    };

    function agregarMensajeUsuario(t) {
        const el = document.createElement('div');
        el.className = 'msg user'; el.textContent = t; appendMsg(el);
    }
    function agregarMensajeBot(t) {
        const el = document.createElement('div');
        el.className = 'msg bot';
        el.innerHTML = t.replace(/\*(.*?)\*/g,'<strong>$1</strong>').replace(/\n/g,'<br>');
        appendMsg(el);
    }
    function agregarTyping() {
        const el = document.createElement('div');
        el.className = 'msg-typing';
        el.innerHTML = '<span></span><span></span><span></span>';
        appendMsg(el); return el;
    }
    function appendMsg(el) {
        const c = document.getElementById('chatMessages');
        c.appendChild(el); c.scrollTop = c.scrollHeight;
    }

    async function cargarAlertas() {
        try {
            const res  = await fetch('{{ route("chatbot.alertas") }}');
            const data = await res.json();
            if (!data.alertas || data.alertas.length === 0) return;
            await new Promise(r => setTimeout(r, 600));
            for (const alerta of data.alertas) {
                await new Promise(r => setTimeout(r, 300));
                const el = document.createElement('div');
                el.className = `msg-alerta ${alerta.nivel}`;
                let html = `<div class="alerta-titulo">${alerta.icono} ${alerta.titulo}</div>`;
                const max = Math.min(alerta.items.length, 4);
                for (let i = 0; i < max; i++) {
                    const item = alerta.items[i];
                    html += `<div class="alerta-item">• ${item.texto}</div>`;
                    if (item.detalle) html += `<div class="alerta-detalle">${item.detalle}</div>`;
                }
                if (alerta.items.length > 4) html += `<div class="alerta-detalle" style="margin-top:.3rem">...y ${alerta.items.length-4} más.</div>`;
                el.innerHTML = html; appendMsg(el);
            }
        } catch(e) {}
    }

    setTimeout(() => { if (!panelOpen) document.getElementById('chatFab').classList.add('has-notif'); }, 3000);
})();
</script>
@endif

</body>
</html>
