<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $fillable = ['fecha_hora', 'estado', 'oficio_id', 'user_id'];

    // Relación: Un turno pertenece a un oficio
    public function oficio()
    {
        return $this->belongsTo(Oficio::class);
    }

    // Relación: Un turno pertenece a un profesional (usuario)
    public function profesional()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
