@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="page-header-title">Estadísticas — Oficios Judiciales</h2>
        <p class="page-header-sub">
            Período: {{ $mesesNombres[$mes_desde] }} – {{ $mesesNombres[$mes_hasta] }} {{ $anio }}
        </p>
    </div>
    <a href="{{ route('juzgados.index') }}" class="btn btn-secondary btn-sm">← Volver a Juzgados</a>
</div>

{{-- Formularios ocultos para exportar con imagen --}}
<form id="formExcel" method="POST" action="{{ route('juzgados.estadisticas.excel') }}">
    @csrf
    <input type="hidden" name="anio"      value="{{ $anio }}">
    <input type="hidden" name="mes_desde" value="{{ $mes_desde }}">
    <input type="hidden" name="mes_hasta" value="{{ $mes_hasta }}">
    <input type="hidden" name="chart_img" id="chartImgExcel">
</form>
<form id="formPdf" method="POST" action="{{ route('juzgados.estadisticas.pdf') }}">
    @csrf
    <input type="hidden" name="anio"      value="{{ $anio }}">
    <input type="hidden" name="mes_desde" value="{{ $mes_desde }}">
    <input type="hidden" name="mes_hasta" value="{{ $mes_hasta }}">
    <input type="hidden" name="chart_img" id="chartImgPdf">
</form>
<form id="formWord" method="POST" action="{{ route('juzgados.estadisticas.word') }}">
    @csrf
    <input type="hidden" name="anio"      value="{{ $anio }}">
    <input type="hidden" name="mes_desde" value="{{ $mes_desde }}">
    <input type="hidden" name="mes_hasta" value="{{ $mes_hasta }}">
    <input type="hidden" name="chart_img" id="chartImgWord">
</form>

{{-- ── Botones exportar ── --}}
<div class="mb-4">
    <p style="font-size:0.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.5rem;">Descargas</p>
    <div class="d-flex gap-2 flex-wrap align-items-center">

        {{-- Excel --}}
        <button onclick="exportar('formExcel','chartImgExcel')" title="Descargar Excel"
            style="display:flex;align-items:center;gap:8px;padding:7px 14px;border:1.5px solid #d4daf0;border-radius:8px;background:var(--surface);cursor:pointer;transition:all .18s;box-shadow:0 1px 4px var(--shadow);"
            onmouseover="this.style.borderColor='#21A366';this.style.background='rgba(33,163,102,0.07)'"
            onmouseout="this.style.borderColor='#d4daf0';this.style.background='var(--surface)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 44 44">
                <rect x="14" y="4" width="22" height="36" rx="2" fill="#21A366"/>
                <rect x="20" y="4" width="16" height="36" rx="2" fill="#33C481"/>
                <rect x="20" y="4" width="16" height="18" rx="2" fill="#107C41"/>
                <rect x="4" y="14" width="22" height="20" rx="3" fill="#185C37"/>
                <text x="15" y="28" font-family="Arial" font-weight="bold" font-size="13" fill="white" text-anchor="middle">X</text>
            </svg>
            <span style="font-size:0.82rem;font-weight:600;color:var(--text-soft);">Excel</span>
        </button>

        {{-- PDF --}}
        <button onclick="exportar('formPdf','chartImgPdf')" title="Descargar PDF"
            style="display:flex;align-items:center;gap:8px;padding:7px 14px;border:1.5px solid #d4daf0;border-radius:8px;background:var(--surface);cursor:pointer;transition:all .18s;box-shadow:0 1px 4px var(--shadow);"
            onmouseover="this.style.borderColor='#C0392B';this.style.background='rgba(192,57,43,0.07)'"
            onmouseout="this.style.borderColor='#d4daf0';this.style.background='var(--surface)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 44 44">
                <path d="M8 4 H28 L36 12 V40 a2 2 0 0 1-2 2 H10 a2 2 0 0 1-2-2 Z" fill="#C0392B"/>
                <path d="M28 4 L36 12 H30 a2 2 0 0 1-2-2 Z" fill="#E74C3C"/>
                <text x="22" y="30" font-family="Arial" font-weight="bold" font-size="11" fill="white" text-anchor="middle">PDF</text>
            </svg>
            <span style="font-size:0.82rem;font-weight:600;color:var(--text-soft);">PDF</span>
        </button>

        {{-- Word --}}
        <button onclick="exportar('formWord','chartImgWord')" title="Descargar Word"
            style="display:flex;align-items:center;gap:8px;padding:7px 14px;border:1.5px solid #d4daf0;border-radius:8px;background:var(--surface);cursor:pointer;transition:all .18s;box-shadow:0 1px 4px var(--shadow);"
            onmouseover="this.style.borderColor='#185ABD';this.style.background='rgba(24,90,189,0.07)'"
            onmouseout="this.style.borderColor='#d4daf0';this.style.background='var(--surface)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 44 44">
                <rect x="14" y="4" width="22" height="36" rx="2" fill="#2B7CD3"/>
                <rect x="20" y="4" width="16" height="36" rx="2" fill="#41A5EE"/>
                <rect x="20" y="4" width="16" height="18" rx="2" fill="#2B7CD3"/>
                <rect x="4" y="14" width="22" height="20" rx="3" fill="#185ABD"/>
                <text x="15" y="28" font-family="Arial" font-weight="bold" font-size="13" fill="white" text-anchor="middle">W</text>
            </svg>
            <span style="font-size:0.82rem;font-weight:600;color:var(--text-soft);">Word</span>
        </button>

    </div>
</div>

<div class="card mb-4 p-3">
    <form method="GET" action="{{ route('juzgados.estadisticas') }}" class="d-flex flex-wrap gap-3 align-items-end">
        <div>
            <label class="form-label">Año</label>
            <select name="anio" class="filter-select">
                @foreach($aniosDisponibles as $a)
                    <option value="{{ $a }}" {{ $a == $anio ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Mes desde</label>
            <select name="mes_desde" class="filter-select">
                @foreach($mesesNombres as $num => $nombre)
                    <option value="{{ $num }}" {{ $num == $mes_desde ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Mes hasta</label>
            <select name="mes_hasta" class="filter-select">
                @foreach($mesesNombres as $num => $nombre)
                    <option value="{{ $num }}" {{ $num == $mes_hasta ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="btn btn-primary btn-sm px-4">Filtrar</button>
        </div>
    </form>
</div>

@if($datos->isEmpty())
    <div class="text-center py-5" style="color:var(--text-muted);">
        No hay oficios registrados para el período seleccionado.
    </div>
@else

<div class="row g-4">

    {{-- ── Tabla ── --}}
    <div class="col-lg-5">
        <div class="card p-0 h-100">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="background:linear-gradient(135deg,#f5c518,#e6b800)!important;color:#333!important;">OFICINA / JUZGADO</th>
                            <th style="background:linear-gradient(135deg,#f5c518,#e6b800)!important;color:#333!important;text-align:center;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datos as $row)
                        <tr>
                            <td style="font-size:0.82rem;">{{ $row->nombre }}</td>
                            <td style="text-align:center;font-weight:700;">{{ $row->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:linear-gradient(135deg,#5cb85c,#4cae4c)!important;">
                            <td style="font-weight:700;color:#fff;font-size:0.85rem;background:transparent!important;">TOTAL</td>
                            <td style="font-weight:800;color:#fff;text-align:center;font-size:1rem;background:transparent!important;">{{ $totalGeneral }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Gráfico ── --}}
    <div class="col-lg-7">
        <div class="card p-4 h-100 d-flex flex-column align-items-center justify-content-center"
             style="background:#fffef0!important;">
            <div style="font-size:1.1rem;font-weight:800;color:#e00;text-align:center;letter-spacing:0.04em;margin-bottom:1.25rem;text-transform:uppercase;">
                Oficios Judiciales
                {{ $mesesNombres[$mes_desde] }}@if($mes_desde !== $mes_hasta) – {{ $mesesNombres[$mes_hasta] }}@endif
                {{ $anio }}
            </div>
            <div style="position:relative;width:100%;max-width:380px;">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

</div>

@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let chartInstance = null;

(function() {
    const labels = @json($datos->pluck('nombre'));
    const values = @json($datos->pluck('total'));

    const palette = [
        '#f0a030','#a0c060','#6090d0','#e06060','#80c0a0',
        '#c080d0','#f0d060','#60b0c0','#d09060','#90a0d0',
        '#e08090','#70c090'
    ];

    const colors = labels.map((_, i) => palette[i % palette.length]);

    chartInstance = new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderColor: '#fff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            animation: { duration: 800 },
            plugins: {
                legend: {
                    position: 'right',
                    labels: { font: { size: 11 }, boxWidth: 14, padding: 10, color: '#444' }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                            const pct = ((ctx.parsed / total) * 100).toFixed(1);
                            return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
})();

function exportar(formId, inputId) {
    const canvas = document.getElementById('pieChart');
    const img = canvas ? canvas.toDataURL('image/png') : '';
    document.getElementById(inputId).value = img;
    document.getElementById(formId).submit();
}
</script>

@endsection
