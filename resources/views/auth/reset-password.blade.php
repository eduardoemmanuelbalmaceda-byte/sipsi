<x-guest-layout>

    <div style="text-align:center;margin-bottom:1.5rem;">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:linear-gradient(135deg,#2ab8a0,#1a9e88);border-radius:50%;margin-bottom:0.85rem;box-shadow:0 4px 15px rgba(42,184,160,0.4);">
            <svg width="28" height="28" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h2 class="title-sipsi">NUEVA CONTRASEÑA</h2>
        <p class="subtitle-sipsi">Sistema de Salud Mental — SIPSI</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div style="margin-bottom:1rem;">
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                required autofocus autocomplete="username"
                placeholder="Correo electrónico"
                class="sipsi-input" />
            @error('email') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom:1rem;">
            <input id="password" type="password" name="password"
                required autocomplete="new-password"
                placeholder="Nueva contraseña"
                class="sipsi-input" />
            @error('password') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="margin-bottom:1.5rem;">
            <input id="password_confirmation" type="password" name="password_confirmation"
                required autocomplete="new-password"
                placeholder="Confirmar nueva contraseña"
                class="sipsi-input" />
            @error('password_confirmation') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-sipsi">
            Restablecer contraseña
        </button>

    </form>

</x-guest-layout>
