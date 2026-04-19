<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPSI - {{ $title ?? 'Sistema' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            min-height: 100vh;
            position: relative;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 18vw;
            font-weight: 900;
            color: rgba(37, 99, 235, 0.04);
            letter-spacing: -0.05em;
            pointer-events: none;
            z-index: 0;
            user-select: none;
            white-space: nowrap;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 100%);
            box-shadow: 0 2px 10px rgba(30, 58, 95, 0.3);
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .navbar-custom .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
        }
        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .brand-text {
            font-size: 1.4rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.03em;
        }
        .brand-sub {
            font-size: 0.6rem;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            display: block;
            line-height: 1;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }
        .alert-success {
            background: #ecfdf5;
            border-color: #6ee7b7;
            color: #065f46;
            border-radius: 10px;
        }
        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>

<div class="watermark">SIPSI</div>

<div class="content-wrapper">
<nav class="navbar navbar-expand-lg navbar-custom py-2">
    <div class="container">
        <a class="navbar-brand-custom" href="{{ route('dashboard') }}">
            <svg width="36" height="36" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,15 C30,15 15,32 15,50 C15,68 30,85 50,85 C55,85 62,82 68,78 C82,72 90,58 90,45 C90,28 75,15 50,15 Z" stroke="white" stroke-width="5" stroke-linecap="round" fill="rgba(255,255,255,0.1)"/>
                <path d="M40,30 C32,35 30,48 35,58" stroke="white" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.8"/>
                <path d="M60,25 C70,30 75,45 70,60" stroke="white" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.8"/>
                <circle cx="50" cy="50" r="4" fill="#10b981"/>
            </svg>
            <div>
                <span class="brand-text">SIPSI</span>
                <span class="brand-sub">Psiquiatría Hospitalaria</span>
            </div>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto ms-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('oficios.index') }}">Oficios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pacientes.index') }}">Pacientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('juzgados.index') }}">Juzgados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profesionales.index') }}">Profesionales</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item d-flex align-items-center me-3">
                    <span style="color:rgba(255,255,255,0.7); font-size:0.85rem;">{{ auth()->user()->name }}</span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</div>
</div>

</body>
</html>