<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPSI - {{ $title ?? 'Sistema' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">SIPSI</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
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

</body>
</html>