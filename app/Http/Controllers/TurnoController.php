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

    public function registrarAsistencia(Request $request, Turno $turno)
    {
        $request->validate([
            'asistencia'          => 'required|in:asistio,no_asistio',
            'motivo_inasistencia' => 'nullable|string|max:500',
        ]);

        $turno->update([
            'asistencia'          => $request->asistencia,
            'motivo_inasistencia' => $request->motivo_inasistencia,
            'estado'              => $request->asistencia === 'asistio' ? 'realizado' : 'ausente',
        ]);

        if ($request->asistencia === 'no_asistio') {
            return redirect()->route('informes.createInasistencia', $turno->oficio_id)
                ->with('success', 'Inasistencia registrada. Completá el informe.');
        }

        return redirect()->route('oficios.show', $turno->oficio_id)
            ->with('success', 'Asistencia registrada. Podés cargar el informe clínico.');
    }

    public function destroy(Turno $turno)
    {
        $oficio_id = $turno->oficio_id;
        $turno->delete();

        // Si el oficio no tiene informe, vuelve a pendiente
        $oficio = Oficio::find($oficio_id);
        if ($oficio && !$oficio->informe) {
            $oficio->update(['estado' => 'pendiente']);
        }

        return redirect()->route('oficios.show', $oficio_id)->with('success', 'Turno eliminado correctamente.');
    }

    public function index() {}
    public function show(Turno $turno) {}
}