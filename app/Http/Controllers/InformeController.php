<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Models\Oficio;
use App\Models\Profesional;
use Illuminate\Http\Request;

class InformeController extends Controller
{
    public function create(Oficio $oficio)
    {
        $profesionales = Profesional::orderBy('apellido')->get();
        return view('informes.create', compact('oficio', 'profesionales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'oficio_id'      => 'required|exists:oficios,id',
            'profesional_id' => 'required|exists:profesionales,id',
            'contenido'      => 'required|string',
            'fecha_informe'  => 'required|date',
        ]);

        Informe::create($request->all());

        Oficio::find($request->oficio_id)->update(['estado' => 'cerrado']);

        return redirect()->route('oficios.show', $request->oficio_id)->with('success', 'Informe cargado correctamente.');
    }

    public function edit(Informe $informe)
    {
        $profesionales = Profesional::orderBy('apellido')->get();
        return view('informes.edit', compact('informe', 'profesionales'));
    }

    public function update(Request $request, Informe $informe)
    {
        $request->validate([
            'profesional_id'  => 'required|exists:profesionales,id',
            'contenido'       => 'required|string',
            'fecha_informe'   => 'required|date',
            'enviado_juzgado' => 'boolean',
            'fecha_envio'     => 'nullable|date',
        ]);

        $informe->update($request->all());

        return redirect()->route('oficios.show', $informe->oficio_id)->with('success', 'Informe actualizado correctamente.');
    }

    public function index() {}
    public function show(Informe $informe) {}
    public function destroy(Informe $informe) {}
}