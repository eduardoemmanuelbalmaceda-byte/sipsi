<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OficioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\JuzgadoController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\ProfesionalController;

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
    Route::resource('profesionales', ProfesionalController::class);

    Route::get('oficios/{oficio}/turno/create', [TurnoController::class, 'create'])->name('turnos.create');
    Route::post('turnos', [TurnoController::class, 'store'])->name('turnos.store');
    Route::get('turnos/{turno}/edit', [TurnoController::class, 'edit'])->name('turnos.edit');
    Route::put('turnos/{turno}', [TurnoController::class, 'update'])->name('turnos.update');

    Route::get('oficios/{oficio}/informe/create', [InformeController::class, 'create'])->name('informes.create');
    Route::post('informes', [InformeController::class, 'store'])->name('informes.store');
    Route::get('informes/{informe}/edit', [InformeController::class, 'edit'])->name('informes.edit');
    Route::put('informes/{informe}', [InformeController::class, 'update'])->name('informes.update');
});

require __DIR__.'/auth.php';