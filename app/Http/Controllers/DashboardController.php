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
        // ── Contadores principales ──
        $totalOficios    = Oficio::count();
        $pendientes      = Oficio::where('estado', 'pendiente')->count();
        $enCurso         = Oficio::where('estado', 'en_curso')->count();
        $cerrados        = Oficio::where('estado', 'cerrado')->count();
        $totalPacientes  = Paciente::count();
        $totalProfesionales = Profesional::count();

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
            'chartLabels', 'chartData'
        ));
    }
}
