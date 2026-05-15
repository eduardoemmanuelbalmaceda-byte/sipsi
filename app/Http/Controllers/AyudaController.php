<?php

namespace App\Http\Controllers;

use Parsedown;

class AyudaController extends Controller
{
    public function index()
    {
        return view('ayuda.index');
    }

    public function manual(string $tipo)
    {
        $archivos = [
            'usuario'   => base_path('MANUAL_USUARIO.md'),
            'procesos'  => base_path('MANUAL_PROCESOS.md'),
        ];

        if (!isset($archivos[$tipo]) || !file_exists($archivos[$tipo])) {
            abort(404);
        }

        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);

        $contenido = $parsedown->text(file_get_contents($archivos[$tipo]));

        $titulo = $tipo === 'usuario' ? 'Manual de Usuario' : 'Manual de Procesos';

        return view('ayuda.manual', compact('contenido', 'titulo', 'tipo'));
    }
}
