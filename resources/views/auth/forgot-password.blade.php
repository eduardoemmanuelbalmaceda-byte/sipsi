<x-guest-layout>

    <div style="text-align:center;margin-bottom:1.5rem;">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:linear-gradient(135deg,#2ab8a0,#1a9e88);border-radius:50%;margin-bottom:0.85rem;box-shadow:0 4px 15px rgba(42,184,160,0.4);">
            <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>
        <h2 class="title-sipsi">RECUPERAR ACCESO</h2>
        <p class="subtitle-sipsi">Sistema de Salud Mental — SIPSI</p>
    </div>

    <p style="color:#4a9a9a;font-size:0.875rem;text-align:center;margin-bottom:1.5rem;line-height:1.6;">
        Ingresá tu correo y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div style="margin-bottom:1.5rem;">
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                required autofocus
                placeholder="Correo electrónico"
                class="sipsi-input" />
            @error('email') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-sipsi">
            Enviar enlace de recuperación
        </button>

        <div style="text-align:center;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid rgba(42,184,160,0.2);">
            <a href="{{ route('login') }}" class="link-sipsi" style="font-weight:600;">← Volver al inicio de sesión</a>
        </div>

    </form>

</x-guest-layout>
