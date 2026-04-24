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

{{-- ── Filtros ── --}}
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
                            <th style="background:linear-gradient(135deg,#f5c518,#e6b800)!important;color:#333!important;">
                                OFICINA / JUZGADO
                            </th>
                            <th style="background:linear-gradient(135deg,#f5c518,#e6b800)!important;color:#333!important;text-align:center;">
                                TOTAL
                            </th>
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
(function() {
    const labels = @json($datos->pluck('nombre'));
    const values = @json($datos->pluck('total'));

    const palette = [
        '#f0a030','#a0c060','#6090d0','#e06060','#80c0a0',
        '#c080d0','#f0d060','#60b0c0','#d09060','#90a0d0',
        '#e08090','#70c090'
    ];

    const colors = labels.map((_, i) => palette[i % palette.length]);

    new Chart(document.getElementById('pieChart'), {
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
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: { size: 11 },
                        boxWidth: 14,
                        padding: 10,
                        color: '#444',
                    }
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
</script>

@endsection
