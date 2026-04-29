<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Paciente;
use App\Models\Juzgado;
use App\Imports\OficiosImport;
use Maatwebsite\Excel\Facades\Excel;
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

    public function registrarNotificacion(Request $request, Oficio $oficio)
    {
        $request->validate([
            'notificado_por' => 'required|in:direccion,juzgado,conflicto',
        ]);

        $oficio->update(['notificado_por' => $request->notificado_por]);

        return redirect()->route('oficios.show', $oficio)
            ->with('success', 'Notificación registrada correctamente.');
    }

    public function confirmarJuzgado(Oficio $oficio)
    {
        $oficio->update([
            'confirmacion_juzgado'        => true,
            'fecha_confirmacion_juzgado'  => now()->toDateString(),
        ]);

        return redirect()->route('oficios.show', $oficio)
            ->with('success', 'Confirmación del juzgado registrada.');
    }

    public function plantilla()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla-oficios.csv"',
        ];

        $columnas = ['numero_oficio','juzgado','dni_paciente','nombre_paciente','apellido_paciente','fecha_recepcion','medio_recepcion','tipo_pedido','observaciones'];
        $ejemplo  = ['OF-2024-001','2° Juzgado de Familia','12345678','Juan','García','18/04/2026','email','Informe pericial',''];

        $callback = function () use ($columnas, $ejemplo) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $columnas);
            fputcsv($out, $ejemplo);
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $import = new OficiosImport();
        Excel::import($import, $request->file('archivo'));

        $importados = $import->getImportados();
        $omitidos   = $import->getOmitidos();
        $errores    = $import->getErrores();

        $msg = "Importación completada: $importados oficio" . ($importados !== 1 ? 's' : '') . " importado" . ($importados !== 1 ? 's' : '') . ".";
        if ($omitidos > 0) {
            $msg .= " $omitidos omitido" . ($omitidos !== 1 ? 's' : '') . ".";
        }
        if (!empty($errores)) {
            $msg .= " Detalles: " . implode(' | ', array_slice($errores, 0, 3));
        }

        return redirect()->route('oficios.index')->with('success', $msg);
    }
}