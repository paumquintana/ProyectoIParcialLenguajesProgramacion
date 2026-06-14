<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model

{
    protected $table = 'autores';             // esto le dice a laravel cual es la tabla que debe llenar
    protected $fillable = ['nombre', 'apellido', 'ol_key']; // esto le dice que columnas llenar

    public function libros()
    // un Autor tiene muchos Libros
    {
        return $this->hasMany(Libro::class);
    }
}
