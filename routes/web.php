<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OficioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\JuzgadoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('oficios', OficioController::class);
    Route::resource('pacientes', PacienteController::class);
    Route::resource('juzgados', JuzgadoController::class);
});

require __DIR__.'/auth.php';