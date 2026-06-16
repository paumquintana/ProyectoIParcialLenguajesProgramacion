<?php

namespace App\Http\Controllers;

use App\Models\LectorLibro;
use App\Models\Resenia;
use Illuminate\Http\Request;

class ReseniaController extends Controller
{
    /** Guarda una reseña (puntuación 1-5 + comentario) sobre una lectura propia. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'lector_libro_id' => ['required', 'exists:lector_libro,id'],
            'puntuacion'      => ['required', 'integer', 'between:1,5'],
            'comentario'      => ['nullable', 'string', 'max:2000'],
        ]);

        $lectura = LectorLibro::findOrFail($data['lector_libro_id']);
        abort_unless($lectura->user_id === $request->user()->id, 403);

        Resenia::create([
            'lector_libro_id' => $lectura->id,
            'puntuacion'      => $data['puntuacion'],
            'comentario'      => $data['comentario'] ?? null,
            'fecha'           => now()->toDateString(),
        ]);

        return back()->with('status', 'Reseña publicada. ¡Gracias por compartir!');
    }
}
