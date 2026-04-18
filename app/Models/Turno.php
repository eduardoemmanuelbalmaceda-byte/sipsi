<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $fillable = ['oficio_id', 'profesional_id', 'fecha_turno', 'hora', 'estado'];

    public function oficio()
    {
        return $this->belongsTo(Oficio::class);
    }

    public function profesional()
    {
        return $this->belongsTo(Profesional::class);
    }
}