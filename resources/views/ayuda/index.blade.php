@extends('layouts.app')
@section('content')

<style>
    .ayuda-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        max-width: 800px;
        margin: 2rem auto;
    }
    @media (max-width: 600px) { .ayuda-grid { grid-template-columns: 1fr; } }

    .ayuda-card {
        background: var(--surface);
        border-radius: 1rem;
        box-shadow: 0 2px 12px var(--shadow);
        padding: 2rem 1.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
        border: 1px solid var(--border);
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .ayuda-card:hover { transform: translateY(-3px); box-shadow: 0 6px 24px var(--shadow); }

    .ayuda-icon {
        width: 64px; height: 64px; border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
    }
    .ayuda-icon.usuario  { background: rgba(139,167,217,0.15); }
    .ayuda-icon.procesos { background: rgba(126,200,164,0.15); }

    .ayuda-title { font-size: 1.2rem; font-weight: 700; color: var(--text); }
    .ayuda-desc  { font-size: 0.875rem; color: var(--text-muted); line-height: 1.6; }

    .ayuda-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.5rem;
        border-radius: 0.6rem;
        font-size: 0.875rem; font-weight: 600;
        text-decoration: none;
        transition: all 0.15s;
        margin-top: 0.5rem;
    }
    .ayuda-btn.usuario  {
        background: linear-gradient(135deg, var(--lavender), var(--indigo));
        color: white;
    }
    .ayuda-btn.procesos {
        background: linear-gradient(135deg, var(--mint), var(--green));
        color: white;
    }
    .ayuda-btn:hover { opacity: 0.9; transform: scale(1.02); }
</style>

<div style="text-align:center; margin-bottom:2rem;">
    <h1 class="page-header-title">Centro de Ayuda</h1>
    <p class="page-header-sub">Documentación del sistema SIPSI</p>
</div>

<div class="ayuda-grid">

    <div class="ayuda-card">
        <div class="ayuda-icon usuario">📖</div>
        <div class="ayuda-title">Manual de Usuario</div>
        <div class="ayuda-desc">
            Guía paso a paso para usar el sistema. Cómo registrar oficios, asignar turnos, cargar informes, usar el chatbot y más.
        </div>
        <a href="{{ route('ayuda.manual', 'usuario') }}" class="ayuda-btn usuario">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Ver manual
        </a>
    </div>

    <div class="ayuda-card">
        <div class="ayuda-icon procesos">⚙️</div>
        <div class="ayuda-title">Manual de Procesos</div>
        <div class="ayuda-desc">
            Documentación técnica del sistema. Flujos de trabajo, estructura de la base de datos, roles y mantenimiento.
        </div>
        <a href="{{ route('ayuda.manual', 'procesos') }}" class="ayuda-btn procesos">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Ver manual
        </a>
    </div>

</div>

@endsection
