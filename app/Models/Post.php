<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['grupo_id', 'user_id', 'contenido'];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
