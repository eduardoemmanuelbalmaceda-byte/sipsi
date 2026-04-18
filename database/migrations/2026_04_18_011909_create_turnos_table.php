<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oficio_id')->constrained('oficios')->onDelete('cascade');
            $table->foreignId('profesional_id')->constrained('profesionales')->onDelete('cascade');
            $table->date('fecha_turno');
            $table->time('hora');
            $table->enum('estado', ['pendiente', 'realizado', 'ausente'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};