<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->enum('asistencia', ['pendiente', 'asistio', 'no_asistio'])->default('pendiente')->after('estado');
            $table->text('motivo_inasistencia')->nullable()->after('asistencia');
        });
    }

    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropColumn(['asistencia', 'motivo_inasistencia']);
        });
    }
};
