<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\OficioController;
use App\Http\Controllers\TurnoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pacientes', PacienteController::class);

Route::resource('oficios', OficioController::class);

Route::resource('turnos', TurnoController::class);
