<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = ['titulo', 'autor_id', 'isbn', 'total_paginas', 'ol_key', 'sinopsis', 'anio_publicacion', 'cover_id', 'cover_url'];

    /**
     * URL de la portada del libro.
     * 1) Si hay cover_url (portada propia elegida a mano), se usa esa.
     * 2) Si no, se arma desde cover_id (Open Library, tamaño mediano).
     * 3) Si no hay ninguna, devuelve null y la vista muestra una portada generada.
     */
    public function getPortadaUrlAttribute(): ?string
    {
        if ($this->cover_url) {
            return $this->cover_url;
        }

        if ($this->cover_id) {
            return "https://covers.openlibrary.org/b/id/{$this->cover_id}-M.jpg";
        }

        return null;
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