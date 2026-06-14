<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectorLibro extends Model
{
    protected $table = 'lector_libro';         
    protected $fillable = ['user_id', 'libro_id', 'estado', 'paginas_leidas', 'fecha_comienzo', 'fecha_fin'];

    public function lector()   { return $this->belongsTo(User::class, 'user_id'); }
    public function libro()    { return $this->belongsTo(Libro::class); }
    public function resenias() { return $this->hasMany(Resenia::class); }

    public function getProgresoAttribute()
    {
        if (!$this->libro || !$this->libro->total_paginas) return 0;
        return round(($this->paginas_leidas / $this->libro->total_paginas) * 100, 1);
    }
}
