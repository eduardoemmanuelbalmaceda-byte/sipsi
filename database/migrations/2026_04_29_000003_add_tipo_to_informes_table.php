<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->enum('tipo', ['clinico', 'inasistencia'])->default('clinico')->after('oficio_id');
            $table->boolean('enviado_direccion')->default(false)->after('enviado_juzgado');
            $table->date('fecha_envio_direccion')->nullable()->after('enviado_direccion');
        });
    }

    public function down(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'enviado_direccion', 'fecha_envio_direccion']);
        });
    }
};
