<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\Libro;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Libros que está leyendo (para mostrar barra de progreso).
        $leyendo = $user->lecturas()
            ->where('estado', 'leyendo')
            ->with('libro.autor')
            ->get();

        // Próximos a leer.
        $porLeer = $user->lecturas()
            ->where('estado', 'por_leer')
            ->with('libro.autor')
            ->get();

        // Géneros que más lee el usuario (basado en TODA su biblioteca).
        $misLibrosIds = $user->lecturas()->pluck('libro_id');

        $generosTop = Genero::whereHas('libros', fn ($q) => $q->whereIn('libros.id', $misLibrosIds))
            ->withCount(['libros' => fn ($q) => $q->whereIn('libros.id', $misLibrosIds)])
            ->orderByDesc('libros_count')
            ->take(3)
            ->pluck('id');

        // Recomendaciones: libros de esos géneros que aún NO tiene en su biblioteca.
        $recomendaciones = Libro::with('autor', 'generos')
            ->whereHas('generos', fn ($q) => $q->whereIn('generos.id', $generosTop))
            ->whereNotIn('id', $misLibrosIds)
            ->take(8)
            ->get();

        // Si el usuario es nuevo (sin historial), recomendamos libros variados.
        if ($recomendaciones->isEmpty()) {
            $recomendaciones = Libro::with('autor', 'generos')
                ->inRandomOrder()
                ->take(8)
                ->get();
        }

        return view('dashboard', compact('leyendo', 'porLeer', 'recomendaciones'));
    }
}
