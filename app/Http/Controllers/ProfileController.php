<?php

namespace App\Http\Controllers;

use App\Models\Resenia;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /** Página de perfil (solo lectura): datos, estadísticas, leyendo ahora y grupos. */
    public function show(Request $request)
    {
        $user = $request->user();

        // Conteos por estado de lectura.
        $porEstado = $user->lecturas()
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $stats = [
            'leyendo'   => $porEstado['leyendo']   ?? 0,
            'por_leer'  => $porEstado['por_leer']  ?? 0,
            'terminado' => $porEstado['terminado'] ?? 0,
            'resenias'  => Resenia::whereHas('lectorLibro', fn ($q) => $q->where('user_id', $user->id))->count(),
            'grupos'    => $user->grupos()->count(),
        ];

        // Libros que está leyendo (con barra de progreso).
        $leyendo = $user->lecturas()
            ->where('estado', 'leyendo')
            ->with('libro.autor')
            ->get();

        // Grupos a los que pertenece.
        $grupos = $user->grupos()->withCount('lectores')->get();

        return view('perfil.index', compact('user', 'stats', 'leyendo', 'grupos'));
    }
}
