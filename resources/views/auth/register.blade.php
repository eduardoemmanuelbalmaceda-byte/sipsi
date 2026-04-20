<x-guest-layout>

    <div style="text-align:center;margin-bottom:1.5rem;">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:linear-gradient(135deg,#2ab8a0,#1a9e88);border-radius:50%;margin-bottom:0.85rem;box-shadow:0 4px 15px rgba(42,184,160,0.4);">
            <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <h2 class="title-sipsi">CREAR CUENTA</h2>
        <p class="subtitle-sipsi">Sistema de Salud Mental — SIPSI</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div style="margin-bottom:1rem;">
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                required autofocus autocomplete="name"
                placeholder="Nombre completo"
                class="sipsi-input" />
            @error('name') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom:1rem;">
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                required autocomplete="username"
                placeholder="Correo electrónico"
                class="sipsi-input" />
            @error('email') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom:1rem;">
            <input id="password" type="password" name="password"
                required autocomplete="new-password"
                placeholder="Contraseña"
                class="sipsi-input" />
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom:1.5rem;">
            <input id="password_confirmation" type="password" name="password_confirmation"
                required autocomplete="new-password"
                placeholder="Confirmar contraseña"
                class="sipsi-input" />
            @error('password_confirmation') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-sipsi">
            Registrarse
        </button>

        <div style="text-align:center;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid rgba(42,184,160,0.2);">
            <span style="color:#4a9a9a;font-size:0.875rem;">¿Ya tenés cuenta? </span>
            <a href="{{ route('login') }}" class="link-sipsi" style="font-weight:600;">Iniciar sesión</a>
        </div>

    </form>

</x-guest-layout>
