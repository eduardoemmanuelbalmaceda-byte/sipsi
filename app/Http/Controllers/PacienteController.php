<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Imports\PacientesImport;
use Maatwebsite\Excel\Facades\Excel;
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

    public function plantilla()
    {
        // Genera un CSV de ejemplo para descargar
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla-pacientes.csv"',
        ];

        $columnas = ['nombre', 'apellido', 'dni', 'fecha_nacimiento', 'telefono', 'direccion'];
        $ejemplo  = ['Juan', 'García', '12345678', '1985-06-15', '2615551234', 'Av. San Martín 100'];

        $callback = function () use ($columnas, $ejemplo) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
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

        $import = new PacientesImport();
        Excel::import($import, $request->file('archivo'));

        $importados = $import->getImportados();
        $omitidos   = $import->getOmitidos();

        $msg = "Importación completada: $importados paciente" . ($importados !== 1 ? 's' : '') . " importado" . ($importados !== 1 ? 's' : '') . ".";
        if ($omitidos > 0) {
            $msg .= " $omitidos omitido" . ($omitidos !== 1 ? 's' : '') . " (DNI duplicado o fila vacía).";
        }

        return redirect()->route('pacientes.index')->with('success', $msg);
    }
}