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
        .user-name  { font-size: 0.82rem; font-weight: 600; color: #fff; line-height: 1.2; }
        .user-email { font-size: 0.68rem; color: rgba(255,255,255,0.28); }
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

        <a href="{{ route('oficios.index') }}" class="sidebar-link {{ request()->routeIs('oficios.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Oficios
        </a>

        <a href="{{ route('pacientes.index') }}" class="sidebar-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Pacientes
        </a>

        <a href="{{ route('juzgados.index') }}" class="sidebar-link {{ request()->routeIs('juzgados.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
            </svg>
            Juzgados
        </a>

        <a href="{{ route('profesionales.index') }}" class="sidebar-link {{ request()->routeIs('profesionales.*') ? 'active' : '' }}">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Profesionales
        </a>

        <div class="nav-label" style="margin-top:0.5rem;">Informes</div>

        <a href="{{ route('oficios.index') }}" class="sidebar-link">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Estadísticas
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
                Cerrar sesión
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

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('sipsi-theme', theme);
        const isDark = theme === 'dark';
        // desktop icons
        document.getElementById('iconSun').style.display  = isDark ? 'block' : 'none';
        document.getElementById('iconMoon').style.display = isDark ? 'none'  : 'block';
        // mobile icons
        document.getElementById('iconSunMobile').style.display  = isDark ? 'block' : 'none';
        document.getElementById('iconMoonMobile').style.display = isDark ? 'none'  : 'block';
    }

    function toggleTheme() {
        const current = document.documentElement.getAttribute('data-theme');
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    // Init icons on load
    applyTheme(localStorage.getItem('sipsi-theme') || 'light');
</script>
</body>
</html>
