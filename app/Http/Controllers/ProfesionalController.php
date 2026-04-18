<?php

namespace App\Http\Controllers;

use App\Models\Profesional;
use App\Models\User;
use Illuminate\Http\Request;

class ProfesionalController extends Controller
{
    public function index()
    {
        $profesionales = Profesional::with('user')->latest()->paginate(10);
        return view('profesionales.index', compact('profesionales'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('profesional')->orderBy('name')->get();
        return view('profesionales.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id|unique:profesionales',
            'nombre'      => 'required|string',
            'apellido'    => 'required|string',
            'especialidad'=> 'required|string',
            'rol'         => 'required|in:profesional,admin,direccion',
        ]);

        Profesional::create($request->all());

        return redirect()->route('profesionales.index')->with('success', 'Profesional registrado correctamente.');
    }

    public function edit(Profesional $profesional)
    {
        return view('profesionales.edit', compact('profesional'));
    }

    public function update(Request $request, Profesional $profesional)
    {
        $request->validate([
            'nombre'      => 'required|string',
            'apellido'    => 'required|string',
            'especialidad'=> 'required|string',
            'rol'         => 'required|in:profesional,admin,direccion',
        ]);

        $profesional->update($request->all());

        return redirect()->route('profesionales.index')->with('success', 'Profesional actualizado correctamente.');
    }

    public function destroy(Profesional $profesional)
    {
        $profesional->delete();
        return redirect()->route('profesionales.index')->with('success', 'Profesional eliminado correctamente.');
    }

    public function show(Profesional $profesional) {}
}