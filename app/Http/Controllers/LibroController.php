<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\Libro;
use App\Models\Resenia;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /** Catálogo: búsqueda por título/autor y filtro por género. */
    public function index(Request $request)
    {
        $generos = Genero::orderBy('nombre')->get();

        $libros = Libro::with('autor', 'generos')
            ->when($request->q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('titulo', 'like', "%{$q}%")
                        ->orWhereHas('autor', fn ($a) => $a
                            ->where('nombre', 'like', "%{$q}%")
                            ->orWhere('apellido', 'like', "%{$q}%"));
                });
            })
            ->when($request->genero, fn ($query, $g) => $query
                ->whereHas('generos', fn ($x) => $x->where('generos.id', $g)))
            ->orderBy('titulo')
            ->paginate(12)
            ->withQueryString();

        return view('libros.index', compact('libros', 'generos'));
    }

    /** Detalle del libro: sinopsis, géneros, mi lectura y reseñas. */
    public function show(Request $request, Libro $libro)
    {
        $libro->load('autor', 'generos');

        $miLectura = $request->user()->lecturas()
            ->where('libro_id', $libro->id)
            ->first();

        // Reseñas de TODOS los lectores para este libro.
        $resenias = Resenia::whereHas('lectorLibro', fn ($q) => $q->where('libro_id', $libro->id))
            ->with('lectorLibro.lector')
            ->latest()
            ->get();

        return view('libros.show', compact('libro', 'miLectura', 'resenias'));
    }
}
