<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resenia extends Model
{
    protected $fillable = ['lector_libro_id', 'puntuacion', 'comentario', 'fecha'];

    public function lectorLibro()
    {
        return $this->belongsTo(LectorLibro::class);
    }
}
