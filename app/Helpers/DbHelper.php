<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class DbHelper
{
    /**
     * Devuelve la función SQL correcta para formatear fecha como 'YYYY-MM'
     * según el motor de base de datos en uso (MySQL o SQLite).
     */
    public static function formatoMes(string $columna): string
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return "strftime('%Y-%m', {$columna})";
        }
        return "DATE_FORMAT({$columna}, '%Y-%m')";
    }

    /**
     * Devuelve la función SQL correcta para extraer el año de una fecha.
     */
    public static function formatoAnio(string $columna): string
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return "strftime('%Y', {$columna})";
        }
        return "YEAR({$columna})";
    }
}
