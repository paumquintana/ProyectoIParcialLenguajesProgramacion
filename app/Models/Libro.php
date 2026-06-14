<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = ['titulo', 'autor_id', 'isbn', 'total_paginas', 'ol_key', 'sinopsis', 'anio_publicacion', 'cover_id'];

    /**
     * URL de la portada del libro (tamaño mediano).
     * Devuelve null si el libro no tiene cover_id; en la vista puedes
     * mostrar una imagen de reemplazo cuando sea null.
     */
    public function getPortadaUrlAttribute(): ?string
    {
        if (!$this->cover_id) {
            return null;
        }

        return "https://covers.openlibrary.org/b/id/{$this->cover_id}-M.jpg";
    }

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