<?php

namespace App\Http\Controllers;

use App\Models\LectorLibro;
use Illuminate\Http\Request;

class BibliotecaController extends Controller
{
    /** Mi biblioteca: libros agrupados por estado. */
    public function index(Request $request)
    {
        $porEstado = $request->user()->lecturas()
            ->with('libro.autor')
            ->get()
            ->groupBy('estado');

        return view('biblioteca.index', compact('porEstado'));
    }

    /** Agrega un libro a mi biblioteca (estado por_leer por defecto). */
    public function store(Request $request)
    {
        $data = $request->validate([
            'libro_id' => ['required', 'exists:libros,id'],
            'estado'   => ['nullable', 'in:por_leer,leyendo,terminado'],
        ]);

        LectorLibro::firstOrCreate(
            ['user_id' => $request->user()->id, 'libro_id' => $data['libro_id']],
            [
                'estado'         => $data['estado'] ?? 'por_leer',
                'fecha_comienzo' => ($data['estado'] ?? null) === 'leyendo' ? now()->toDateString() : null,
            ]
        );

        return back()->with('status', 'Libro agregado a tu biblioteca.');
    }

    /** Actualiza progreso (páginas leídas) y estado de una lectura. */
    public function updateProgress(Request $request, LectorLibro $lectura)
    {
        abort_unless($lectura->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'paginas_leidas' => ['required', 'integer', 'min:0'],
            'estado'         => ['required', 'in:por_leer,leyendo,terminado'],
        ]);

        // Marcar fechas automáticamente según el estado.
        if ($data['estado'] === 'leyendo' && ! $lectura->fecha_comienzo) {
            $lectura->fecha_comienzo = now()->toDateString();
        }
        if ($data['estado'] === 'terminado') {
            $lectura->fecha_fin = now()->toDateString();
        }

        $lectura->paginas_leidas = $data['paginas_leidas'];
        $lectura->estado = $data['estado'];
        $lectura->save();

        return back()->with('status', 'Progreso actualizado.');
    }
}
