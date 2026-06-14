<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    public function libros()
    // un Autor tiene muchos Libros
    {
        return $this->hasMany(Libro::class);
    }
}
