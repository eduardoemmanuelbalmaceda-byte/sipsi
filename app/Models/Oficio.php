<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oficio extends Model
{
    protected $fillable = [
        'numero_oficio', 'juzgado_id', 'paciente_id',
        'fecha_recepcion', 'medio_recepcion', 'tipo_pedido',
        'estado', 'observaciones'
    ];

    public function juzgado()
    {
        return $this->belongsTo(Juzgado::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function turno()
    {
        return $this->hasOne(Turno::class);
    }

    public function informe()
    {
        return $this->hasOne(Informe::class);
    }
}