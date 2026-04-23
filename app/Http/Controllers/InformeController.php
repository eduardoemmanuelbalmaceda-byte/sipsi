<?php

namespace App\Http\Controllers;

use App\Models\Informe;
use App\Models\Oficio;
use App\Models\Profesional;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function pdf(Informe $informe)
    {
        $informe->load(['oficio.paciente', 'oficio.juzgado', 'profesional']);

        $pdf = Pdf::loadView('informes.pdf', compact('informe'))
            ->setPaper('a4', 'portrait');

        $filename = 'informe-oficio-' . $informe->oficio->numero_oficio . '.pdf';

        return $pdf->download($filename);
    }

    public function marcarEnviado(Informe $informe)
    {
        $informe->update([
            'enviado_juzgado' => true,
            'fecha_envio'     => now()->toDateString(),
        ]);

        return redirect()->route('oficios.show', $informe->oficio_id)
            ->with('success', 'Informe marcado como enviado al juzgado.');
    }

    public function destroy(Informe $informe)
    {
        $oficio_id = $informe->oficio_id;
        $informe->delete();

        // Al borrar el informe, el oficio vuelve a en_curso (tiene turno)
        $oficio = Oficio::find($oficio_id);
        if ($oficio && $oficio->turno) {
            $oficio->update(['estado' => 'en_curso']);
        }

        return redirect()->route('oficios.show', $oficio_id)->with('success', 'Informe eliminado correctamente.');
    }

    public function index() {}
    public function show(Informe $informe) {}
}