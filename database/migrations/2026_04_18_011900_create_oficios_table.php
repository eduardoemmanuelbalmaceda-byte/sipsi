<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oficios', function (Blueprint $table) {
            $table->id();
            $table->string('numero_oficio')->unique();
            $table->foreignId('juzgado_id')->constrained()->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->date('fecha_recepcion');
            $table->enum('medio_recepcion', ['papel', 'email', 'whatsapp']);
            $table->string('tipo_pedido');
            $table->enum('estado', ['pendiente', 'en_curso', 'cerrado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oficios');
    }
};