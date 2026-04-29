<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oficios', function (Blueprint $table) {
            $table->enum('notificado_por', ['direccion', 'juzgado', 'conflicto'])->nullable()->after('observaciones');
            $table->boolean('confirmacion_juzgado')->default(false)->after('notificado_por');
            $table->date('fecha_confirmacion_juzgado')->nullable()->after('confirmacion_juzgado');
        });
    }

    public function down(): void
    {
        Schema::table('oficios', function (Blueprint $table) {
            $table->dropColumn(['notificado_por', 'confirmacion_juzgado', 'fecha_confirmacion_juzgado']);
        });
    }
};
