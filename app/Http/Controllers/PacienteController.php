<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente; // Importamos el modelo para usarlo

class PacienteController extends Controller
{
    /**
     * Muestra la lista de pacientes.
     */
    public function index()
    {
        // 1. Pedimos todos los pacientes a la base de datos
        $pacientes = Paciente::all();

        // 2. Cargamos la vista y le pasamos los pacientes
        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Muestra el formulario para crear un paciente nuevo.
     */
    public function create()
    {
        return view('pacientes.create');
    }

    public function store(Request $request)
    {
        // 1. Recibe y crea el paciente con los datos del formulario
        Paciente::create($request->all());

        // 2. Redirige al listado con un mensaje de éxito
        return redirect()->route('pacientes.index');
    }
}
