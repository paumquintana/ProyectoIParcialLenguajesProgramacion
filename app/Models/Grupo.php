<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Lectores (usuarios) que pertenecen al grupo.
     * Relación muchos a muchos a través de la tabla pivote 'lector_grupo'.
     */
    public function lectores()
    {
        return $this->belongsToMany(User::class, 'lector_grupo', 'grupo_id', 'user_id');
    }
}
