<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    /** Lista de grupos de lectura. */
    public function index(Request $request)
    {
        $grupos = Grupo::withCount('lectores')->orderBy('nombre')->get();
        $misGrupos = $request->user()->grupos()->pluck('grupos.id');

        return view('grupos.index', compact('grupos', 'misGrupos'));
    }

    /** Foro del grupo con sus posts. */
    public function show(Request $request, Grupo $grupo)
    {
        $grupo->load(['posts.autor', 'lectores']);
        $esMiembro = $grupo->lectores->contains($request->user()->id);

        return view('grupos.show', compact('grupo', 'esMiembro'));
    }

    /** Unirse a un grupo. */
    public function join(Request $request, Grupo $grupo)
    {
        $grupo->lectores()->syncWithoutDetaching([$request->user()->id]);

        return back()->with('status', 'Te uniste al grupo «' . $grupo->nombre . '».');
    }
}
