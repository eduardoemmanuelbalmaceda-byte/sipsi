<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Oficio;
use App\Models\User;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::with(['oficio.paciente', 'profesional'])->get();
        return view('turnos.index', compact('turnos'));
    }

    public function create()
    {
        $oficios = Oficio::where('estado', '!=', 'finalizado')->get();
        $profesionales = User::all();
        return view('turnos.create', compact('oficios', 'profesionales'));
    }

    public function store(Request $request)
    {
        Turno::create($request->all());
        return redirect()->route('turnos.index');
    }
}
