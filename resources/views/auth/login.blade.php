<x-guest-layout>

    {{-- Logo / título --}}
    <div style="text-align:center;margin-bottom:1.5rem;">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:linear-gradient(135deg,#7ec8a4,#5a9e7a);border-radius:50%;margin-bottom:0.85rem;box-shadow:0 4px 15px rgba(126,200,164,0.4);">
            <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <h2 class="title-sipsi">BIENVENIDO</h2>
        <p class="subtitle-sipsi">Sistema de Salud Mental — SIPSI</p>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div style="margin-bottom:1rem;">
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required autofocus autocomplete="username"
                placeholder="Usuario / Correo electrónico"
                class="sipsi-input"
            />
            @error('email') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        {{-- Password --}}
        <div style="margin-bottom:1.5rem;">
            <input
                id="password"
                type="password"
                name="password"
                required autocomplete="current-password"
                placeholder="Contraseña"
                class="sipsi-input"
            />
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        {{-- Botón --}}
        <button type="submit" class="btn-sipsi">
            Iniciar sesión
        </button>

        {{-- Links --}}
        <div style="text-align:center;margin-top:1.25rem;display:flex;flex-direction:column;gap:0.4rem;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-sipsi">¿Olvidaste tu contraseña?</a>
            @endif
            <label style="display:flex;align-items:center;justify-content:center;gap:0.5rem;cursor:pointer;margin-top:0.25rem;">
                <input type="checkbox" name="remember" style="accent-color:#2ab8a0;width:14px;height:14px;" />
                <span class="check-label">Recordarme</span>
            </label>
        </div>

        {{-- Registro --}}
        <div style="text-align:center;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid rgba(42,184,160,0.2);">
            <span style="color:#4a9a9a;font-size:0.875rem;">¿No tenés cuenta? </span>
            <a href="{{ route('register') }}" class="link-sipsi" style="font-weight:600;">Crear cuenta</a>
        </div>

    </form>

</x-guest-layout>
