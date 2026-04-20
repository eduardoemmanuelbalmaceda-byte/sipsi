<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $pacientes = Paciente::when($q, fn($query) => $query
                ->where('nombre',   'like', "%$q%")
                ->orWhere('apellido','like', "%$q%")
                ->orWhere('dni',     'like', "%$q%")
                ->orWhere('telefono','like', "%$q%")
            )
            ->orderBy('apellido')
            ->paginate(10)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'q'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string',
            'apellido'         => 'required|string',
            'dni'              => 'required|unique:pacientes',
            'fecha_nacimiento' => 'nullable|date',
            'telefono'         => 'nullable|string',
            'direccion'        => 'nullable|string',
        ]);

        Paciente::create($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente)
    {
        $paciente->load('oficios.juzgado');
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'nombre'           => 'required|string',
            'apellido'         => 'required|string',
            'dni'              => 'required|unique:pacientes,dni,'.$paciente->id,
            'fecha_nacimiento' => 'nullable|date',
            'telefono'         => 'nullable|string',
            'direccion'        => 'nullable|string',
        ]);

        $paciente->update($request->all());

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado correctamente.');
    }
}