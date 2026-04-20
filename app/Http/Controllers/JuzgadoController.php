<?php

namespace App\Http\Controllers;

use App\Models\Juzgado;
use Illuminate\Http\Request;

class JuzgadoController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $juzgados = Juzgado::when($q, fn($query) => $query
                ->where('nombre',   'like', "%$q%")
                ->orWhere('ciudad', 'like', "%$q%")
                ->orWhere('contacto','like', "%$q%")
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('juzgados.index', compact('juzgados', 'q'));
    }

    public function create()
    {
        return view('juzgados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string',
            'ciudad'   => 'required|string',
            'contacto' => 'nullable|string',
        ]);

        Juzgado::create($request->all());

        return redirect()->route('juzgados.index')->with('success', 'Juzgado registrado correctamente.');
    }

    public function show(Juzgado $juzgado)
    {
        $juzgado->load('oficios');
        return view('juzgados.show', compact('juzgado'));
    }

    public function edit(Juzgado $juzgado)
    {
        return view('juzgados.edit', compact('juzgado'));
    }

    public function update(Request $request, Juzgado $juzgado)
    {
        $request->validate([
            'nombre'   => 'required|string',
            'ciudad'   => 'required|string',
            'contacto' => 'nullable|string',
        ]);

        $juzgado->update($request->all());

        return redirect()->route('juzgados.index')->with('success', 'Juzgado actualizado correctamente.');
    }

    public function destroy(Juzgado $juzgado)
    {
        $juzgado->delete();
        return redirect()->route('juzgados.index')->with('success', 'Juzgado eliminado correctamente.');
    }
}