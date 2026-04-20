<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Paciente;
use App\Models\Juzgado;
use Illuminate\Http\Request;

class OficioController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->input('q');
        $estado = $request->input('estado');

        $oficios = Oficio::with(['paciente', 'juzgado'])
            ->when($q, fn($query) => $query
                ->where('numero_oficio', 'like', "%$q%")
                ->orWhereHas('paciente', fn($p) => $p
                    ->where('nombre',   'like', "%$q%")
                    ->orWhere('apellido','like', "%$q%")
                    ->orWhere('dni',     'like', "%$q%"))
                ->orWhereHas('juzgado', fn($j) => $j
                    ->where('nombre', 'like', "%$q%"))
            )
            ->when($estado, fn($query) => $query->where('estado', $estado))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('oficios.index', compact('oficios', 'q', 'estado'));
    }

    public function create()
    {
        $pacientes = Paciente::orderBy('apellido')->get();
        $juzgados = Juzgado::orderBy('nombre')->get();
        return view('oficios.create', compact('pacientes', 'juzgados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_oficio'   => 'required|unique:oficios',
            'juzgado_id'      => 'required|exists:juzgados,id',
            'paciente_id'     => 'required|exists:pacientes,id',
            'fecha_recepcion' => 'required|date',
            'medio_recepcion' => 'required|in:papel,email,whatsapp',
            'tipo_pedido'     => 'required|string',
        ]);

        Oficio::create($request->all());

        return redirect()->route('oficios.index')->with('success', 'Oficio registrado correctamente.');
    }

    public function show(Oficio $oficio)
    {
        $oficio->load(['paciente', 'juzgado', 'turno.profesional', 'informe.profesional']);
        return view('oficios.show', compact('oficio'));
    }

    public function edit(Oficio $oficio)
    {
        $pacientes = Paciente::orderBy('apellido')->get();
        $juzgados = Juzgado::orderBy('nombre')->get();
        return view('oficios.edit', compact('oficio', 'pacientes', 'juzgados'));
    }

    public function update(Request $request, Oficio $oficio)
    {
        $request->validate([
            'numero_oficio'   => 'required|unique:oficios,numero_oficio,'.$oficio->id,
            'juzgado_id'      => 'required|exists:juzgados,id',
            'paciente_id'     => 'required|exists:pacientes,id',
            'fecha_recepcion' => 'required|date',
            'medio_recepcion' => 'required|in:papel,email,whatsapp',
            'tipo_pedido'     => 'required|string',
            'estado'          => 'required|in:pendiente,en_curso,cerrado',
        ]);

        $oficio->update($request->all());

        return redirect()->route('oficios.index')->with('success', 'Oficio actualizado correctamente.');
    }

    public function destroy(Oficio $oficio)
    {
        $oficio->delete();
        return redirect()->route('oficios.index')->with('success', 'Oficio eliminado correctamente.');
    }
}