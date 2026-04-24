@extends('layouts.app')
@section('content')

<style>
    /* ── KPI Cards ── */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .kpi-card {
        background: var(--surface);
        border-radius: 0.85rem;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 1px 6px var(--shadow);
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px var(--shadow); }
    .kpi-card::before {
        content: '';
        position: absolute; top: 0; left: 0;
        width: 4px; height: 100%;
        border-radius: 4px 0 0 4px;
    }
    .kpi-card.total::before   { background: linear-gradient(180deg, var(--lavender), var(--slate)); }
    .kpi-card.pend::before    { background: linear-gradient(180deg, #f0c060, #e0a840); }
    .kpi-card.curso::before   { background: linear-gradient(180deg, var(--lavender), var(--indigo)); }
    .kpi-card.cerrado::before { background: linear-gradient(180deg, var(--mint), var(--green)); }
    .kpi-card.pac::before     { background: linear-gradient(180deg, #a78bfa, #7c3aed); }
    .kpi-card.prof::before    { background: linear-gradient(180deg, #67e8f9, #0891b2); }

    .kpi-icon {
        width: 36px; height: 36px; border-radius: 0.55rem;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 0.25rem;
    }
    .kpi-card.total .kpi-icon   { background: rgba(139,167,217,0.15); color: var(--lavender); }
    .kpi-card.pend .kpi-icon    { background: rgba(240,192,96,0.15);  color: #e0a840; }
    .kpi-card.curso .kpi-icon   { background: rgba(107,95,181,0.15);  color: var(--indigo); }
    .kpi-card.cerrado .kpi-icon { background: rgba(126,200,164,0.15); color: var(--green); }
    .kpi-card.pac .kpi-icon     { background: rgba(167,139,250,0.15); color: #7c3aed; }
    .kpi-card.prof .kpi-icon    { background: rgba(103,232,249,0.15); color: #0891b2; }

    .kpi-value { font-size: 2rem; font-weight: 800; color: var(--text); line-height: 1; }
    .kpi-label { font-size: 0.78rem; color: var(--text-muted); font-weight: 500; }

    /* ── Dashboard grid ── */
    .dash-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    .dash-grid.three { grid-template-columns: 1fr 1fr 1fr; }
    @media (max-width: 900px) {
        .dash-grid, .dash-grid.three { grid-template-columns: 1fr; }
    }

    /* ── Panel ── */
    .panel {
        background: var(--surface);
        border-radius: 0.85rem;
        box-shadow: 0 1px 6px var(--shadow);
        overflow: hidden;
    }
    .panel-header {
        padding: 1rem 1.25rem 0.75rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .panel-title {
        font-size: 0.9rem; font-weight: 700; color: var(--text);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .panel-title svg { color: var(--text-muted); }
    .panel-link {
        font-size: 0.78rem; color: var(--lavender);
        text-decoration: none; font-weight: 500;
    }
    .panel-link:hover { text-decoration: underline; }
    .panel-body { padding: 0; }

    /* ── Mini list ── */
    .mini-list { list-style: none; margin: 0; padding: 0; }
    .mini-list li {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.7rem 1.25rem;
        border-bottom: 1px solid var(--table-border);
        transition: background 0.15s;
    }
    .mini-list li:last-child { border-bottom: none; }
    .mini-list li:hover { background: var(--table-row-hover); }
    .mini-dot {
        width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
    }
    .mini-dot.pend    { background: #e0a840; }
    .mini-dot.curso   { background: var(--indigo); }
    .mini-dot.cerrado { background: var(--green); }
    .mini-main { flex: 1; min-width: 0; }
    .mini-name { font-size: 0.875rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mini-sub  { font-size: 0.75rem; color: var(--text-muted); }
    .mini-badge {
        font-size: 0.7rem; font-weight: 600; padding: 2px 8px;
        border-radius: 20px; flex-shrink: 0;
    }
    .mini-badge.pend    { background: rgba(240,192,96,0.15);  color: #b07820; }
    .mini-badge.curso   { background: rgba(107,95,181,0.15);  color: var(--indigo); }
    .mini-badge.cerrado { background: rgba(90,158,122,0.15);  color: var(--green); }
    .mini-badge.alerta  { background: rgba(239,68,68,0.12);   color: #dc2626; }

    .empty-state {
        padding: 2rem 1.25rem; text-align: center;
        color: var(--text-muted); font-size: 0.85rem;
    }

    /* ── Chart ── */
    .chart-wrap { padding: 1.25rem; }
    canvas { max-height: 200px; }

    /* ── Donut ── */
    .donut-wrap {
        display: flex; align-items: center; justify-content: center;
        gap: 1.5rem; padding: 1.25rem; flex-wrap: wrap;
    }
    .donut-legend { display: flex; flex-direction: column; gap: 0.5rem; }
    .legend-item  { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: var(--text); }
    .legend-dot   { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
</style>

{{-- ── Header ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.75rem;">
    <div>
        <h1 class="page-header-title">Dashboard</h1>
        <p class="page-header-sub">Resumen general del sistema — {{ now()->format('d/m/Y') }}</p>
    </div>
    <a href="{{ route('oficios.create') }}" class="btn btn-primary" style="display:flex;align-items:center;gap:0.4rem;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo oficio
    </a>
</div>

{{-- ── Alertas proactivas ── --}}
@if(count($alertas) > 0)
<div id="alertasPanel" style="margin-bottom:1.5rem;">
    <style>
        .alertas-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .alertas-header-title {
            font-size: 0.85rem; font-weight: 700; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.05em;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .alertas-dismiss {
            font-size: 0.75rem; color: var(--text-muted); background: none;
            border: none; cursor: pointer; padding: 0.2rem 0.5rem;
            border-radius: 0.4rem; transition: background 0.15s;
        }
        .alertas-dismiss:hover { background: var(--surface2); color: var(--text); }

        .alerta-card {
            border-radius: 0.75rem;
            border: 1px solid;
            overflow: hidden;
            margin-bottom: 0.75rem;
            animation: alertaIn 0.3s ease both;
        }
        @keyframes alertaIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alerta-card.critico     { background: #fef2f2; border-color: #fecaca; }
        .alerta-card.advertencia { background: #fffbeb; border-color: #fde68a; }
        .alerta-card.info        { background: #eff6ff; border-color: #bfdbfe; }

        .alerta-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.65rem 1rem; cursor: pointer; user-select: none;
        }
        .alerta-card.critico     .alerta-card-header { border-bottom: 1px solid #fecaca; }
        .alerta-card.advertencia .alerta-card-header { border-bottom: 1px solid #fde68a; }
        .alerta-card.info        .alerta-card-header { border-bottom: 1px solid #bfdbfe; }

        .alerta-card-title {
            font-size: 0.875rem; font-weight: 700;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .alerta-card.critico     .alerta-card-title { color: #991b1b; }
        .alerta-card.advertencia .alerta-card-title { color: #92400e; }
        .alerta-card.info        .alerta-card-title { color: #1e40af; }

        .alerta-badge {
            font-size: 0.7rem; font-weight: 700; padding: 2px 8px;
            border-radius: 20px; margin-left: 0.4rem;
        }
        .alerta-card.critico     .alerta-badge { background: #fee2e2; color: #991b1b; }
        .alerta-card.advertencia .alerta-badge { background: #fef3c7; color: #92400e; }
        .alerta-card.info        .alerta-badge { background: #dbeafe; color: #1e40af; }

        .alerta-chevron {
            transition: transform 0.2s;
            color: currentColor; opacity: 0.5;
        }
        .alerta-card.collapsed .alerta-chevron { transform: rotate(-90deg); }

        .alerta-card-body { padding: 0.5rem 0; }
        .alerta-card.collapsed .alerta-card-body { display: none; }

        .alerta-item {
            display: flex; align-items: flex-start; gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            transition: background 0.12s;
        }
        .alerta-item:last-child { border-bottom: none; }
        .alerta-card.critico     .alerta-item:hover { background: rgba(239,68,68,0.06); }
        .alerta-card.advertencia .alerta-item:hover { background: rgba(245,158,11,0.06); }
        .alerta-card.info        .alerta-item:hover { background: rgba(59,130,246,0.06); }

        .alerta-item-dot {
            width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; margin-top: 5px;
        }
        .alerta-card.critico     .alerta-item-dot { background: #ef4444; }
        .alerta-card.advertencia .alerta-item-dot { background: #f59e0b; }
        .alerta-card.info        .alerta-item-dot { background: #3b82f6; }

        .alerta-item-main { flex: 1; min-width: 0; }
        .alerta-item-texto  { font-size: 0.845rem; font-weight: 600; color: var(--text); }
        .alerta-item-detalle{ font-size: 0.76rem; color: var(--text-muted); margin-top: 1px; }
        .alerta-item-link {
            font-size: 0.75rem; font-weight: 600; flex-shrink: 0;
            padding: 2px 10px; border-radius: 20px; text-decoration: none;
            transition: opacity 0.15s;
        }
        .alerta-item-link:hover { opacity: 0.75; }
        .alerta-card.critico     .alerta-item-link { background: #fee2e2; color: #991b1b; }
        .alerta-card.advertencia .alerta-item-link { background: #fef3c7; color: #92400e; }
        .alerta-card.info        .alerta-item-link { background: #dbeafe; color: #1e40af; }

        .alerta-more {
            font-size: 0.76rem; color: var(--text-muted);
            padding: 0.4rem 1rem 0.5rem 2.25rem;
        }
    </style>

    <div class="alertas-header">
        <div class="alertas-header-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            {{ collect($alertas)->sum(fn($a) => count($a['items'])) }} alerta{{ collect($alertas)->sum(fn($a) => count($a['items'])) !== 1 ? 's' : '' }} activa{{ collect($alertas)->sum(fn($a) => count($a['items'])) !== 1 ? 's' : '' }}
        </div>
        <button class="alertas-dismiss" onclick="document.getElementById('alertasPanel').style.display='none'">
            Ocultar
        </button>
    </div>

    @foreach($alertas as $alerta)
    @php $max = 3; $resto = count($alerta['items']) - $max; @endphp
    <div class="alerta-card {{ $alerta['nivel'] }}" onclick="this.classList.toggle('collapsed')">
        <div class="alerta-card-header">
            <div class="alerta-card-title">
                {{ $alerta['icono'] }} {{ $alerta['titulo'] }}
                <span class="alerta-badge">{{ count($alerta['items']) }}</span>
            </div>
            <svg class="alerta-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        <div class="alerta-card-body" onclick="event.stopPropagation()">
            @foreach(array_slice($alerta['items'], 0, $max) as $item)
            <div class="alerta-item">
                <div class="alerta-item-dot"></div>
                <div class="alerta-item-main">
                    <div class="alerta-item-texto">{{ $item['texto'] }}</div>
                    <div class="alerta-item-detalle">{{ $item['detalle'] }}</div>
                </div>
                <a href="{{ $item['link'] }}" class="alerta-item-link">Ver</a>
            </div>
            @endforeach
            @if($resto > 0)
            <div class="alerta-more">...y {{ $resto }} más</div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ── KPIs ── --}}
<div class="kpi-grid">
    <div class="kpi-card total">
        <div class="kpi-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div class="kpi-value">{{ $totalOficios }}</div>
        <div class="kpi-label">Total oficios</div>
    </div>
    <div class="kpi-card pend">
        <div class="kpi-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="kpi-value">{{ $pendientes }}</div>
        <div class="kpi-label">Pendientes</div>
    </div>
    <div class="kpi-card curso">
        <div class="kpi-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div class="kpi-value">{{ $enCurso }}</div>
        <div class="kpi-label">En curso</div>
    </div>
    <div class="kpi-card cerrado">
        <div class="kpi-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="kpi-value">{{ $cerrados }}</div>
        <div class="kpi-label">Cerrados</div>
    </div>
    <div class="kpi-card pac">
        <div class="kpi-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="kpi-value">{{ $totalPacientes }}</div>
        <div class="kpi-label">Pacientes</div>
    </div>
    <div class="kpi-card prof">
        <div class="kpi-icon">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="kpi-value">{{ $totalProfesionales }}</div>
        <div class="kpi-label">Profesionales</div>
    </div>
</div>

{{-- ── Fila 1: Gráfico + Donut ── --}}
<div class="dash-grid" style="margin-bottom:1.25rem;">

    {{-- Gráfico de barras: oficios por mes --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Oficios por mes (últimos 6 meses)
            </span>
        </div>
        <div class="chart-wrap">
            <canvas id="chartMeses"></canvas>
        </div>
    </div>

    {{-- Donut: distribución de estados --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                Distribución de estados
            </span>
        </div>
        <div class="donut-wrap">
            <canvas id="chartDonut" style="max-width:160px;max-height:160px;"></canvas>
            <div class="donut-legend">
                <div class="legend-item">
                    <div class="legend-dot" style="background:#e0a840;"></div>
                    Pendientes <strong style="margin-left:4px;">{{ $pendientes }}</strong>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#6b5fb5;"></div>
                    En curso <strong style="margin-left:4px;">{{ $enCurso }}</strong>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background:#5a9e7a;"></div>
                    Cerrados <strong style="margin-left:4px;">{{ $cerrados }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Fila 2: Turnos próximos + Oficios recientes + Informes sin enviar ── --}}
<div class="dash-grid three">

    {{-- Próximos turnos --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Próximos turnos
            </span>
            <a href="{{ route('oficios.index') }}" class="panel-link">Ver todos</a>
        </div>
        <div class="panel-body">
            @if($turnosProximos->isEmpty())
                <div class="empty-state">Sin turnos próximos</div>
            @else
                <ul class="mini-list">
                    @foreach($turnosProximos as $turno)
                    <li>
                        <div class="mini-dot pend"></div>
                        <div class="mini-main">
                            <div class="mini-name">
                                {{ $turno->oficio->paciente->apellido }}, {{ $turno->oficio->paciente->nombre }}
                            </div>
                            <div class="mini-sub">
                                {{ \Carbon\Carbon::parse($turno->fecha_turno)->format('d/m/Y') }}
                                {{ substr($turno->hora, 0, 5) }}hs
                                · {{ $turno->profesional->apellido }}
                            </div>
                        </div>
                        <a href="{{ route('oficios.show', $turno->oficio_id) }}" class="mini-badge curso">Ver</a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Oficios recientes --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Oficios recientes
            </span>
            <a href="{{ route('oficios.index') }}" class="panel-link">Ver todos</a>
        </div>
        <div class="panel-body">
            @if($oficiosRecientes->isEmpty())
                <div class="empty-state">Sin oficios registrados</div>
            @else
                <ul class="mini-list">
                    @foreach($oficiosRecientes as $oficio)
                    <li>
                        <div class="mini-dot {{ $oficio->estado == 'cerrado' ? 'cerrado' : ($oficio->estado == 'en_curso' ? 'curso' : 'pend') }}"></div>
                        <div class="mini-main">
                            <div class="mini-name">Nº {{ $oficio->numero_oficio }} — {{ $oficio->paciente->apellido }}</div>
                            <div class="mini-sub">{{ $oficio->juzgado->nombre }} · {{ \Carbon\Carbon::parse($oficio->fecha_recepcion)->format('d/m/Y') }}</div>
                        </div>
                        <span class="mini-badge {{ $oficio->estado == 'cerrado' ? 'cerrado' : ($oficio->estado == 'en_curso' ? 'curso' : 'pend') }}">
                            {{ ucfirst(str_replace('_', ' ', $oficio->estado)) }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- Informes sin enviar --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Informes sin enviar
            </span>
        </div>
        <div class="panel-body">
            @if($informesPendientesEnvio->isEmpty())
                <div class="empty-state">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 0.5rem;display:block;color:var(--mint);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Todo enviado al juzgado
                </div>
            @else
                <ul class="mini-list">
                    @foreach($informesPendientesEnvio as $informe)
                    <li>
                        <div class="mini-dot pend"></div>
                        <div class="mini-main">
                            <div class="mini-name">{{ $informe->oficio->paciente->apellido }}, {{ $informe->oficio->paciente->nombre }}</div>
                            <div class="mini-sub">{{ $informe->oficio->juzgado->nombre }} · {{ \Carbon\Carbon::parse($informe->fecha_informe)->format('d/m/Y') }}</div>
                        </div>
                        <a href="{{ route('informes.edit', $informe) }}" class="mini-badge alerta">Pendiente</a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    const isDark = () => document.documentElement.getAttribute('data-theme') === 'dark';

    const textColor  = () => isDark() ? '#8898c8' : '#8090b8';
    const gridColor  = () => isDark() ? '#2a3048' : '#eef0f8';
    const barColor   = 'rgba(139,167,217,0.75)';
    const barHover   = 'rgba(139,167,217,1)';

    // ── Barras: oficios por mes ──
    const ctxBar = document.getElementById('chartMeses').getContext('2d');
    const chartBar = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Oficios',
                data: @json($chartData),
                backgroundColor: barColor,
                hoverBackgroundColor: barHover,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} oficio${ctx.parsed.y !== 1 ? 's' : ''}`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor() },
                    ticks: { color: textColor(), font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor() },
                    ticks: { color: textColor(), font: { size: 11 }, stepSize: 1 }
                }
            }
        }
    });

    // ── Donut: estados ──
    const ctxDonut = document.getElementById('chartDonut').getContext('2d');
    const total = {{ $totalOficios }};
    const chartDonut = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Pendientes', 'En curso', 'Cerrados'],
            datasets: [{
                data: [{{ $pendientes }}, {{ $enCurso }}, {{ $cerrados }}],
                backgroundColor: ['#e0a840', '#6b5fb5', '#5a9e7a'],
                hoverOffset: 6,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0;
                            return ` ${ctx.parsed} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });

    // Actualizar colores al cambiar tema
    const observer = new MutationObserver(() => {
        chartBar.options.scales.x.grid.color = gridColor();
        chartBar.options.scales.y.grid.color = gridColor();
        chartBar.options.scales.x.ticks.color = textColor();
        chartBar.options.scales.y.ticks.color = textColor();
        chartBar.update();
    });
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
})();
</script>

@endsection
