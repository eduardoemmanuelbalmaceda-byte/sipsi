<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficio extends Model
{
    protected $fillable = ['nro_expediente', 'juzgado', 'fecha_recepcion', 'estado', 'paciente_id'];

    // Un oficio pertenece a un paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
