<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = ['titulo', 'autor_id', 'isbn', 'total_paginas', 'ol_key', 'sinopsis', 'anio_publicacion'];

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'libro_genero');
    }

    public function lecturas()
    {
        return $this->hasMany(LectorLibro::class);
    }
}