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
    Route::get('/chatbot/page', function () {
        return view('chatbot');
    })->name('chatbot.page');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/chatbot', [ChatbotController::class, 'responder'])->name('chatbot.responder');
    Route::get('/chatbot/alertas', [ChatbotController::class, 'alertas'])->name('chatbot.alertas');

    Route::post('oficios/importar', [OficioController::class, 'importar'])->name('oficios.importar');
    Route::get('oficios/plantilla', [OficioController::class, 'plantilla'])->name('oficios.plantilla');
    Route::resource('oficios', OficioController::class);
    Route::post('pacientes/importar', [PacienteController::class, 'importar'])->name('pacientes.importar');
    Route::get('pacientes/plantilla', [PacienteController::class, 'plantilla'])->name('pacientes.plantilla');
    Route::resource('pacientes', PacienteController::class);
    Route::resource('juzgados', JuzgadoController::class);
    Route::get('juzgados-estadisticas', [JuzgadoController::class, 'estadisticas'])->name('juzgados.estadisticas');
    Route::post('juzgados-estadisticas/excel', [JuzgadoController::class, 'exportarExcel'])->name('juzgados.estadisticas.excel');
    Route::post('juzgados-estadisticas/pdf',   [JuzgadoController::class, 'exportarPdf'])->name('juzgados.estadisticas.pdf');
    Route::post('juzgados-estadisticas/word',  [JuzgadoController::class, 'exportarWord'])->name('juzgados.estadisticas.word');
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
    Route::get('informes/{informe}/pdf', [InformeController::class, 'pdf'])->name('informes.pdf');
    Route::patch('informes/{informe}/marcar-enviado', [InformeController::class, 'marcarEnviado'])->name('informes.marcarEnviado');
});

require __DIR__.'/auth.php';