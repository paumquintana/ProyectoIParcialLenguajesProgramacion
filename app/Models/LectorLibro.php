<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectorLibro extends Model
{
    public function lector()  { return $this->belongsTo(User::class, 'user_id'); }
    public function libro()   { return $this->belongsTo(Libro::class); }
    public function resenias(){ return $this->hasMany(Resenia::class); }
}
