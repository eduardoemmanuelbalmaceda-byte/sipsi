<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Paciente;
use Illuminate\Http\Request;

class OficioController extends Controller
{
    public function index()
    {
        $oficios = Oficio::with('paciente')->get(); // Trae oficios con sus pacientes
        return view('oficios.index', compact('oficios'));
    }

    public function create()
    {
        $pacientes = Paciente::all(); // Necesitamos la lista para el desplegable
        return view('oficios.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        Oficio::create($request->all());
        return redirect()->route('oficios.index');
    }
}
