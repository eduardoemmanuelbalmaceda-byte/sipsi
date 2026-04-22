<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatbotController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/chatbot', [ChatbotController::class, 'responder'])->name('chatbot.responder');

    Route::resource('oficios', OficioController::class);
    Route::resource('pacientes', PacienteController::class);
    Route::resource('juzgados', JuzgadoController::class);
    Route::resource('profesionales', ProfesionalController::class)->parameters([
        'profesionales' => 'profesional'
    ]);

    Route::get('oficios/{oficio}/turno/create', [TurnoController::class, 'create'])->name('turnos.create');
    Route::post('turnos', [TurnoController::class, 'store'])->name('turnos.store');
    Route::get('turnos/{turno}/edit', [TurnoController::class, 'edit'])->name('turnos.edit');
    Route::put('turnos/{turno}', [TurnoController::class, 'update'])->name('turnos.update');
    Route::delete('turnos/{turno}', [TurnoController::class, 'destroy'])->name('turnos.destroy');

    Route::get('oficios/{oficio}/informe/create', [InformeController::class, 'create'])->name('informes.create');
    Route::post('informes', [InformeController::class, 'store'])->name('informes.store');
    Route::get('informes/{informe}/edit', [InformeController::class, 'edit'])->name('informes.edit');
    Route::put('informes/{informe}', [InformeController::class, 'update'])->name('informes.update');
    Route::delete('informes/{informe}', [InformeController::class, 'destroy'])->name('informes.destroy');
});

require __DIR__.'/auth.php';