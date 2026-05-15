@extends('layouts.app')
@section('content')

<style>
    /* ── Barra superior ── */
    .manual-topbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem;
    }
    .manual-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.3rem 0.85rem; border-radius: 20px;
        font-size: 0.78rem; font-weight: 600;
    }
    .manual-badge.usuario  { background: rgba(139,167,217,0.15); color: var(--lavender); }
    .manual-badge.procesos { background: rgba(126,200,164,0.15); color: var(--green); }

    /* ── Contenido Markdown ── */
    .manual-body {
        background: var(--surface);
        border-radius: 0.85rem;
        box-shadow: 0 1px 6px var(--shadow);
        padding: 2.5rem 3rem;
        max-width: 860px;
        margin: 0 auto;
    }
    @media (max-width: 600px) { .manual-body { padding: 1.25rem 1rem; } }

    .manual-body h1 {
        font-size: 1.6rem; font-weight: 800; color: var(--text);
        border-bottom: 2px solid var(--border); padding-bottom: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .manual-body h2 {
        font-size: 1.2rem; font-weight: 700; color: var(--text);
        margin-top: 2rem; margin-bottom: 0.75rem;
        padding-left: 0.75rem;
        border-left: 3px solid var(--lavender);
    }
    .manual-body h3 {
        font-size: 1rem; font-weight: 700; color: var(--text-soft);
        margin-top: 1.5rem; margin-bottom: 0.5rem;
    }
    .manual-body p {
        font-size: 0.9rem; color: var(--text); line-height: 1.75;
        margin-bottom: 0.75rem;
    }
    .manual-body ul, .manual-body ol {
        padding-left: 1.5rem; margin-bottom: 0.75rem;
    }
    .manual-body li {
        font-size: 0.9rem; color: var(--text); line-height: 1.7;
        margin-bottom: 0.25rem;
    }
    .manual-body strong { color: var(--text); font-weight: 700; }
    .manual-body em { color: var(--text-muted); }
    .manual-body code {
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 4px; padding: 1px 6px;
        font-size: 0.82rem; color: var(--indigo);
        font-family: 'Courier New', monospace;
    }
    .manual-body pre {
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 0.6rem; padding: 1rem 1.25rem;
        overflow-x: auto; margin-bottom: 1rem;
    }
    .manual-body pre code {
        background: none; border: none; padding: 0;
        font-size: 0.85rem; color: var(--text);
    }
    .manual-body table {
        width: 100%; border-collapse: collapse;
        margin-bottom: 1rem; font-size: 0.875rem;
    }
    .manual-body table th {
        background: linear-gradient(135deg, var(--sidebar-bg), var(--sidebar-bg2));
        color: rgba(255,255,255,0.85);
        padding: 0.6rem 1rem; text-align: left;
        font-size: 0.78rem; font-weight: 600;
        letter-spacing: 0.05em; text-transform: uppercase;
    }
    .manual-body table td {
        padding: 0.6rem 1rem; border-bottom: 1px solid var(--table-border);
        color: var(--text);
    }
    .manual-body table tr:hover td { background: var(--table-row-hover); }
    .manual-body blockquote {
        border-left: 3px solid var(--lavender);
        background: var(--surface2);
        padding: 0.75rem 1rem; border-radius: 0 0.5rem 0.5rem 0;
        margin-bottom: 0.75rem;
    }
    .manual-body blockquote p { margin: 0; color: var(--text-soft); }
    .manual-body hr {
        border: none; border-top: 1px solid var(--border);
        margin: 1.5rem 0;
    }

    /* ── Botón imprimir ── */
    @media print {
        .manual-topbar, .sidebar, .main-wrapper > .topbar { display: none !important; }
        .manual-body { box-shadow: none; padding: 0; }
    }
</style>

<div class="manual-topbar">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <a href="{{ route('ayuda.index') }}" class="btn btn-secondary" style="display:flex;align-items:center;gap:0.4rem;padding:0.45rem 0.9rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
        <span class="manual-badge {{ $tipo }}">
            {{ $tipo === 'usuario' ? '📖 Manual de Usuario' : '⚙️ Manual de Procesos' }}
        </span>
    </div>
    <button onclick="window.print()" class="btn btn-secondary" style="display:flex;align-items:center;gap:0.4rem;padding:0.45rem 0.9rem;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Imprimir
    </button>
</div>

<div class="manual-body">
    {!! $contenido !!}
</div>

@endsection
