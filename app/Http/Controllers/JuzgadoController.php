<?php

namespace App\Http\Controllers;

use App\Models\Juzgado;
use App\Models\Oficio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JuzgadoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $juzgados = Juzgado::when($q, fn($query) => $query
                ->where('nombre',   'like', "%$q%")
                ->orWhere('ciudad', 'like', "%$q%")
                ->orWhere('contacto','like', "%$q%")
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('juzgados.index', compact('juzgados', 'q'));
    }

    public function create()
    {
        return view('juzgados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string',
            'ciudad'   => 'required|string',
            'contacto' => 'nullable|string',
        ]);

        Juzgado::create($request->all());

        return redirect()->route('juzgados.index')->with('success', 'Juzgado registrado correctamente.');
    }

    public function show(Juzgado $juzgado)
    {
        $juzgado->load('oficios');
        return view('juzgados.show', compact('juzgado'));
    }

    public function edit(Juzgado $juzgado)
    {
        return view('juzgados.edit', compact('juzgado'));
    }

    public function update(Request $request, Juzgado $juzgado)
    {
        $request->validate([
            'nombre'   => 'required|string',
            'ciudad'   => 'required|string',
            'contacto' => 'nullable|string',
        ]);

        $juzgado->update($request->all());

        return redirect()->route('juzgados.index')->with('success', 'Juzgado actualizado correctamente.');
    }

    public function destroy(Juzgado $juzgado)
    {
        $juzgado->delete();
        return redirect()->route('juzgados.index')->with('success', 'Juzgado eliminado correctamente.');
    }

    public function estadisticas(Request $request)
    {
        $anio = $request->input('anio', Carbon::now()->year);
        $mes_desde = $request->input('mes_desde', 1);
        $mes_hasta = $request->input('mes_hasta', 12);

        $desde = Carbon::create($anio, $mes_desde, 1)->startOfMonth();
        $hasta = Carbon::create($anio, $mes_hasta, 1)->endOfMonth();

        // Oficios por juzgado en el período
        $datos = Juzgado::withCount(['oficios as total' => function ($q) use ($desde, $hasta) {
                $q->whereBetween('fecha_recepcion', [$desde, $hasta]);
            }])
            ->orderByDesc('total')
            ->get()
            ->filter(fn($j) => $j->total > 0)
            ->values();

        $totalGeneral = $datos->sum('total');

        // Años disponibles para el filtro
        $aniosDisponibles = Oficio::selectRaw("strftime('%Y', fecha_recepcion) as anio")
            ->groupBy('anio')
            ->orderByDesc('anio')
            ->pluck('anio');

        $mesesNombres = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
            5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
            9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
        ];

        return view('juzgados.estadisticas', compact(
            'datos', 'totalGeneral', 'anio', 'mes_desde', 'mes_hasta',
            'aniosDisponibles', 'mesesNombres', 'desde', 'hasta'
        ));
    }
}