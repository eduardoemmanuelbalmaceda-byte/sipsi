<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    protected $fillable = [
        'oficio_id', 'profesional_id', 'tipo', 'contenido',
        'fecha_informe', 'enviado_juzgado', 'fecha_envio',
        'enviado_direccion', 'fecha_envio_direccion',
    ];

    public function oficio()
    {
        return $this->belongsTo(Oficio::class);
    }

    public function profesional()
    {
        return $this->belongsTo(Profesional::class);
    }
}