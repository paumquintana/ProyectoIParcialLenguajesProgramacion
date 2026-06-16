<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Genero;
use App\Models\Libro;
use App\Models\Resenia;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /** Formulario para agregar un libro nuevo (estilo Goodreads). */
    public function create()
    {
        $generos = Genero::orderBy('nombre')->get();

        return view('libros.create', compact('generos'));
    }

    /** Guarda el libro nuevo (con su autor y géneros) en la base de datos. */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo'           => ['required', 'string', 'max:255'],
            'autor_nombre'     => ['required', 'string', 'max:255'],
            'autor_apellido'   => ['nullable', 'string', 'max:255'],
            'anio_publicacion' => ['nullable', 'integer', 'min:0', 'max:' . date('Y')],
            'total_paginas'    => ['nullable', 'integer', 'min:1', 'max:100000'],
            'isbn'             => ['nullable', 'string', 'max:20'],
            'sinopsis'         => ['nullable', 'string'],
            'cover_url'        => ['nullable', 'url', 'max:2048'],
            'generos'          => ['nullable', 'array'],
            'generos.*'        => ['integer', 'exists:generos,id'],
            'genero_nuevo'     => ['nullable', 'string', 'max:255'],
        ]);

        // Autor: reutiliza uno existente con el mismo nombre o lo crea.
        $autor = Autor::firstOrCreate([
            'nombre'   => $datos['autor_nombre'],
            'apellido' => $datos['autor_apellido'] ?? null,
        ]);

        $libro = Libro::create([
            'titulo'           => $datos['titulo'],
            'autor_id'         => $autor->id,
            'anio_publicacion' => $datos['anio_publicacion'] ?? null,
            'total_paginas'    => $datos['total_paginas'] ?? null,
            'isbn'             => $datos['isbn'] ?? null,
            'sinopsis'         => $datos['sinopsis'] ?? null,
            'cover_url'        => $datos['cover_url'] ?? null,
        ]);

        // Géneros seleccionados + uno nuevo escrito a mano (opcional).
        $generosIds = $datos['generos'] ?? [];
        if (! empty($datos['genero_nuevo'])) {
            $generosIds[] = Genero::firstOrCreate(['nombre' => trim($datos['genero_nuevo'])])->id;
        }
        $libro->generos()->sync($generosIds);

        return redirect()->route('libros.show', $libro)
            ->with('status', '¡Libro "' . $libro->titulo . '" agregado al catálogo!');
    }

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
