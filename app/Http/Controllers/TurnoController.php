<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Oficio;
use App\Models\Profesional;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function create(Oficio $oficio)
    {
        $profesionales = Profesional::orderBy('apellido')->get();
        return view('turnos.create', compact('oficio', 'profesionales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'oficio_id'      => 'required|exists:oficios,id',
            'profesional_id' => 'required|exists:profesionales,id',
            'fecha_turno'    => 'required|date',
            'hora'           => 'required',
        ]);

        Turno::create($request->all());

        Oficio::find($request->oficio_id)->update(['estado' => 'en_curso']);

        return redirect()->route('oficios.show', $request->oficio_id)->with('success', 'Turno asignado correctamente.');
    }

    public function edit(Turno $turno)
    {
        $profesionales = Profesional::orderBy('apellido')->get();
        return view('turnos.edit', compact('turno', 'profesionales'));
    }

    public function update(Request $request, Turno $turno)
    {
        $request->validate([
            'profesional_id' => 'required|exists:profesionales,id',
            'fecha_turno'    => 'required|date',
            'hora'           => 'required',
            'estado'         => 'required|in:pendiente,realizado,ausente',
        ]);

        $turno->update($request->all());

        return redirect()->route('oficios.show', $turno->oficio_id)->with('success', 'Turno actualizado correctamente.');
    }

    public function index() {}
    public function show(Turno $turno) {}
    public function destroy(Turno $turno) {}
}