<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    protected $table = 'profesionales';

    // Fix para que Laravel use 'profesional' como parámetro de ruta
    public function getRouteKeyName(): string
    {
        return 'id';
    }
    
    protected $fillable = ['user_id', 'nombre', 'apellido', 'especialidad', 'rol'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }

    public function informes()
    {
        return $this->hasMany(Informe::class);
    }
}