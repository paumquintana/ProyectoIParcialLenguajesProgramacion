<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    // el que tiene la llave foránea autor_id
    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    // un Libro tiene muchos Géneros y viceversa
    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'libro_genero');
    }

    public function getProgresoAttribute()
    {
        if (!$this->libro || !$this->libro->total_paginas) return 0;
        return round(($this->paginas_leidas / $this->libro->total_paginas) * 100, 1);
    }
}
