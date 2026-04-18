<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Juzgado extends Model
{
    protected $fillable = ['nombre', 'ciudad', 'contacto'];

    public function oficios()
    {
        return $this->hasMany(Oficio::class);
    }
}