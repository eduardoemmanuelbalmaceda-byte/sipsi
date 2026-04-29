<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Paciente;
use App\Models\Profesional;
use App\Models\Turno;
use App\Models\Informe;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // ── Contadores principales ──
        $totalOficios    = Oficio::count();
        $pendientes      = Oficio::where('estado', 'pendiente')->count();
        $enCurso         = Oficio::where('estado', 'en_curso')->count();
        $cerrados        = Oficio::where('estado', 'cerrado')->count();
        $totalPacientes  = Paciente::count();
        $totalProfesionales = Profesional::count();

        // ── Alertas proactivas ──
        $alertas = [];

        // 1. Oficios vencidos
        $vencidos = Oficio::with('paciente')
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', $hoy)
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->orderBy('fecha_vencimiento')
            ->get();
        if ($vencidos->isNotEmpty()) {
            $alertas[] = [
                'nivel'  => 'critico',
                'icono'  => '🔴',
                'titulo' => 'Oficios vencidos',
                'items'  => $vencidos->map(fn($o) => [
                    'texto'   => "Oficio {$o->numero_oficio} — {$o->paciente->apellido}, {$o->paciente->nombre}",
                    'detalle' => "Venció el " . Carbon::parse($o->fecha_vencimiento)->format('d/m/Y') . " (" . Carbon::parse($o->fecha_vencimiento)->diffForHumans() . ")",
                    'link'    => route('oficios.show', $o->id),
                ])->toArray(),
            ];
        }

        // 2. Oficios por vencer en 7 días
        $porVencer = Oficio::with('paciente')
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(7)])
            ->whereIn('estado', ['pendiente', 'en_curso'])
            ->orderBy('fecha_vencimiento')
            ->get();
        if ($porVencer->isNotEmpty()) {
            $alertas[] = [
                'nivel'  => 'advertencia',
                'icono'  => '🟡',
                'titulo' => 'Por vencer esta semana',
                'items'  => $porVencer->map(fn($o) => [
                    'texto'   => "Oficio {$o->numero_oficio} — {$o->paciente->apellido}, {$o->paciente->nombre}",
                    'detalle' => "Vence el " . Carbon::parse($o->fecha_vencimiento)->format('d/m/Y') . " (" . Carbon::parse($o->fecha_vencimiento)->diffForHumans() . ")",
                    'link'    => route('oficios.show', $o->id),
                ])->toArray(),
            ];
        }

        // 3. Turnos pendientes hoy
        $turnosHoy = Turno::with(['oficio.paciente', 'profesional'])
            ->whereDate('fecha_turno', $hoy)
            ->where('estado', 'pendiente')
            ->orderBy('hora')
            ->get();
        if ($turnosHoy->isNotEmpty()) {
            $alertas[] = [
                'nivel'  => 'info',
                'icono'  => '📅',
                'titulo' => 'Turnos pendientes hoy',
                'items'  => $turnosHoy->map(fn($t) => [
                    'texto'   => substr($t->hora, 0, 5) . "hs — {$t->oficio->paciente->apellido}, {$t->oficio->paciente->nombre}",
                    'detalle' => "Dr/a. {$t->profesional->apellido}",
                    'link'    => route('oficios.show', $t->oficio_id),
                ])->toArray(),
            ];
        }

        // 4. Oficios sin turno hace +15 días
        $sinTurno = Oficio::with('paciente')
            ->where('estado', 'pendiente')
            ->where('fecha_recepcion', '<', $hoy->copy()->subDays(15))
            ->whereDoesntHave('turno')
            ->orderBy('fecha_recepcion')
            ->get();
        if ($sinTurno->isNotEmpty()) {
            $alertas[] = [
                'nivel'  => 'advertencia',
                'icono'  => '⏳',
                'titulo' => 'Sin turno asignado (+15 días)',
                'items'  => $sinTurno->map(fn($o) => [
                    'texto'   => "Oficio {$o->numero_oficio} — {$o->paciente->apellido}, {$o->paciente->nombre}",
                    'detalle' => "Recibido " . Carbon::parse($o->fecha_recepcion)->format('d/m/Y') . " (" . Carbon::parse($o->fecha_recepcion)->diffForHumans() . ")",
                    'link'    => route('oficios.show', $o->id),
                ])->toArray(),
            ];
        }

        // 5. Informes sin enviar +7 días
        $informesSinEnviar = Informe::with(['oficio.paciente', 'oficio.juzgado'])
            ->where('enviado_juzgado', false)
            ->where('fecha_informe', '<', $hoy->copy()->subDays(7))
            ->orderBy('fecha_informe')
            ->get();
        if ($informesSinEnviar->isNotEmpty()) {
            $alertas[] = [
                'nivel'  => 'advertencia',
                'icono'  => '📄',
                'titulo' => 'Informes sin enviar al juzgado (+7 días)',
                'items'  => $informesSinEnviar->map(fn($i) => [
                    'texto'   => "Oficio {$i->oficio->numero_oficio} — {$i->oficio->paciente->apellido}",
                    'detalle' => "Juzgado: {$i->oficio->juzgado->nombre} | Informe del " . Carbon::parse($i->fecha_informe)->format('d/m/Y'),
                    'link'    => route('informes.edit', $i->id),
                ])->toArray(),
            ];
        }

        // ── Turnos próximos (hoy en adelante, pendientes) ──
        $turnosProximos = Turno::with(['oficio.paciente', 'profesional'])
            ->where('estado', 'pendiente')
            ->where('fecha_turno', '>=', Carbon::today())
            ->orderBy('fecha_turno')
            ->orderBy('hora')
            ->take(5)
            ->get();

        // ── Oficios recientes ──
        $oficiosRecientes = Oficio::with(['paciente', 'juzgado'])
            ->latest()
            ->take(5)
            ->get();

        // ── Informes pendientes de envío al juzgado ──
        $informesPendientesEnvio = Informe::with(['oficio.paciente', 'oficio.juzgado'])
            ->where('enviado_juzgado', false)
            ->latest()
            ->take(5)
            ->get();

        // ── Oficios por mes (últimos 6 meses) ──
        $oficiosPorMes = Oficio::select(
                DB::raw("strftime('%Y-%m', fecha_recepcion) as mes"),
                DB::raw('count(*) as total')
            )
            ->where('fecha_recepcion', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Rellenar meses sin datos
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = Carbon::now()->subMonths($i)->format('Y-m');
            $meses[$key] = 0;
        }
        foreach ($oficiosPorMes as $row) {
            $meses[$row->mes] = $row->total;
        }

        $chartLabels = array_map(function($ym) {
            [$y, $m] = explode('-', $ym);
            $mesesNombres = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
            return $mesesNombres[(int)$m - 1] . ' ' . $y;
        }, array_keys($meses));

        $chartData = array_values($meses);

        return view('dashboard', compact(
            'totalOficios', 'pendientes', 'enCurso', 'cerrados',
            'totalPacientes', 'totalProfesionales',
            'turnosProximos', 'oficiosRecientes', 'informesPendientesEnvio',
            'chartLabels', 'chartData', 'alertas'
        ));
    }
}
