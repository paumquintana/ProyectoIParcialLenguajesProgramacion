<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /** Publica un mensaje en el foro de un grupo (solo miembros). */
    public function store(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'contenido' => ['required', 'string', 'max:2000'],
        ]);

        $esMiembro = $grupo->lectores()->where('users.id', $request->user()->id)->exists();
        abort_unless($esMiembro, 403, 'Debes unirte al grupo para publicar.');

        $grupo->posts()->create([
            'user_id'   => $request->user()->id,
            'contenido' => $data['contenido'],
        ]);

        return back()->with('status', 'Mensaje publicado.');
    }
}
