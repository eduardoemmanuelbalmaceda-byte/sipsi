<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('oficios', function (Blueprint $table) {
            $table->id();
            $table->string('nro_expediente');
            $table->string('juzgado');
            $table->date('fecha_recepcion');
            $table->enum('estado', ['pendiente', 'en_proceso', 'finalizado'])->default('pendiente');
            $table->foreignId('paciente_id')->constrained('pacientes'); // Vincula con el paciente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oficios');
    }
};
