<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = ['dni', 'nombre', 'apellido', 'fecha_nacimiento', 'telefono'];
}
