<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SIPSI — Acceso</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Figtree', sans-serif;
                min-height: 100vh;
                background: linear-gradient(135deg, #c8dff0 0%, #b8d4e8 20%, #a8c8d8 40%, #9ec8b8 65%, #b8d9c8 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            /* Cerebro SVG de fondo */
            .brain-bg {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 75vmin;
                height: 75vmin;
                opacity: 0.12;
                pointer-events: none;
                z-index: 0;
            }

            /* Círculos decorativos */
            .deco-circle {
                position: fixed;
                border-radius: 50%;
                pointer-events: none;
                z-index: 0;
            }

            /* Card principal */
            .login-card {
                position: relative;
                z-index: 1;
                background: rgba(255, 255, 255, 0.65);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.8);
                border-radius: 1.5rem;
                padding: 2.75rem 2.5rem;
                width: 100%;
                max-width: 420px;
                box-shadow: 0 8px 40px rgba(107,95,181,0.15), 0 2px 8px rgba(0,0,0,0.06);
            }

            /* Inputs */
            .sipsi-input {
                width: 100% !important;
                background: rgba(255,255,255,0.7) !important;
                border: 1.5px solid rgba(100,180,180,0.4) !important;
                border-radius: 2rem !important;
                padding: 0.75rem 1.25rem !important;
                font-size: 0.95rem !important;
                color: #2d6a6a !important;
                outline: none !important;
                transition: border-color 0.2s, box-shadow 0.2s !important;
                box-shadow: none !important;
                -webkit-text-fill-color: #2d6a6a !important;
            }
            .sipsi-input::placeholder { color: #8bbcbc !important; }
            .sipsi-input:focus {
                border-color: #2ab8a0 !important;
                background: rgba(255,255,255,0.9) !important;
                box-shadow: 0 0 0 3px rgba(42,184,160,0.15) !important;
            }
            .sipsi-input:-webkit-autofill,
            .sipsi-input:-webkit-autofill:focus {
                -webkit-box-shadow: 0 0 0px 1000px rgba(255,255,255,0.85) inset !important;
                -webkit-text-fill-color: #2d6a6a !important;
            }

            /* Botón */
            .btn-sipsi {
                width: 100%;
                padding: 0.8rem;
                background: linear-gradient(135deg, #6b5fb5, #5a4fa0);
                border: none;
                border-radius: 2rem;
                color: white !important;
                font-weight: 700;
                font-size: 0.95rem;
                letter-spacing: 0.05em;
                cursor: pointer;
                transition: all 0.2s;
                box-shadow: 0 4px 15px rgba(107,95,181,0.4);
                text-transform: uppercase;
            }
            .btn-sipsi:hover {
                background: linear-gradient(135deg, #5a4fa0, #4a3f90);
                box-shadow: 0 6px 20px rgba(107,95,181,0.5);
                transform: translateY(-1px);
                color: white !important;
            }

            /* Textos */
            .title-sipsi {
                font-size: 1.9rem;
                font-weight: 800;
                color: #2d3a6b;
                letter-spacing: 0.04em;
                text-align: center;
                margin-bottom: 0.2rem;
            }
            .subtitle-sipsi {
                font-size: 0.9rem;
                color: #6b7abf;
                text-align: center;
                margin-bottom: 1.75rem;
            }
            .label-sipsi {
                display: block;
                font-size: 0.82rem;
                font-weight: 600;
                color: #4a5a9a;
                margin-bottom: 0.4rem;
                padding-left: 0.25rem;
            }
            .link-sipsi {
                color: #6b5fb5;
                font-size: 0.85rem;
                text-decoration: none;
            }
            .link-sipsi:hover { color: #5a4fa0; text-decoration: underline; }
            .check-label { color: #5a6a9a; font-size: 0.85rem; }
            .error-msg { color: #e05555; font-size: 0.78rem; margin-top: 0.3rem; padding-left: 0.25rem; }
            .footer-text { color: rgba(255,255,255,0.5); font-size: 0.75rem; text-align: center; margin-top: 1.25rem; }

            /* Íconos decorativos flotantes */
            .float-icon {
                position: fixed;
                opacity: 0.18;
                pointer-events: none;
                z-index: 0;
                animation: floatAnim 6s ease-in-out infinite;
            }
            @keyframes floatAnim {
                0%,100% { transform: translateY(0); }
                50% { transform: translateY(-12px); }
            }
        </style>
    </head>
    <body>

        {{-- Cerebro de fondo --}}
        <svg class="brain-bg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" fill="none">
            <ellipse cx="100" cy="95" rx="72" ry="62" stroke="#1a6b6b" stroke-width="3"/>
            <path d="M100 33 C100 33 85 45 82 60 C79 75 88 82 88 95 C88 108 80 115 80 128" stroke="#1a6b6b" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M100 33 C100 33 115 45 118 60 C121 75 112 82 112 95 C112 108 120 115 120 128" stroke="#1a6b6b" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M60 70 C70 65 80 72 88 68 C96 64 100 55 108 60 C116 65 118 75 128 72" stroke="#1a6b6b" stroke-width="2" stroke-linecap="round"/>
            <path d="M55 95 C65 90 75 98 88 95 C101 92 99 85 112 88 C125 91 132 100 142 97" stroke="#1a6b6b" stroke-width="2" stroke-linecap="round"/>
            <path d="M62 118 C72 112 82 120 92 116 C102 112 105 105 115 110 C125 115 130 122 138 118" stroke="#1a6b6b" stroke-width="2" stroke-linecap="round"/>
            <circle cx="100" cy="95" r="4" fill="#2ab8a0" opacity="0.6"/>
            <circle cx="88" cy="68" r="3" fill="#2ab8a0" opacity="0.5"/>
            <circle cx="112" cy="88" r="3" fill="#2ab8a0" opacity="0.5"/>
            <circle cx="80" cy="118" r="2.5" fill="#2ab8a0" opacity="0.4"/>
            <circle cx="120" cy="112" r="2.5" fill="#2ab8a0" opacity="0.4"/>
        </svg>

        {{-- Círculos decorativos --}}
        <div class="deco-circle" style="width:350px;height:350px;top:-80px;right:-80px;background:radial-gradient(circle,rgba(42,184,160,0.2),transparent 70%);"></div>
        <div class="deco-circle" style="width:280px;height:280px;bottom:-60px;left:-60px;background:radial-gradient(circle,rgba(100,200,180,0.18),transparent 70%);"></div>

        {{-- Íconos flotantes --}}
        <svg class="float-icon" style="top:8%;left:6%;width:40px;animation-delay:0s;" viewBox="0 0 24 24" fill="none" stroke="#1a6b6b" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <svg class="float-icon" style="top:12%;right:8%;width:36px;animation-delay:1.5s;" viewBox="0 0 24 24" fill="none" stroke="#1a6b6b" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <svg class="float-icon" style="bottom:15%;right:6%;width:38px;animation-delay:3s;" viewBox="0 0 24 24" fill="none" stroke="#1a6b6b" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <svg class="float-icon" style="bottom:18%;left:7%;width:34px;animation-delay:2s;" viewBox="0 0 24 24" fill="none" stroke="#1a6b6b" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>

        {{-- Card --}}
        <div class="login-card">
            {{ $slot }}
        </div>

    </body>
</html>
