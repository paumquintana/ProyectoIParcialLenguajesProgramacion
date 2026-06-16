@extends('layouts.app')
@section('titulo', 'Grupos de lectura')

@section('contenido')
<h1 class="h3 mb-4">Grupos de lectura</h1>

<div class="row g-3">
    @forelse ($grupos as $grupo)
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h2 class="h5 mb-1"><a href="{{ route('grupos.show', $grupo) }}" class="text-decoration-none">{{ $grupo->nombre }}</a></h2>
                        @if ($misGrupos->contains($grupo->id))
                            <span class="badge text-bg-success">Miembro</span>
                        @endif
                    </div>
                    <p class="text-muted small">{{ $grupo->descripcion }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small"><i class="bi bi-people"></i> {{ $grupo->lectores_count }} miembros</span>
                        @if ($misGrupos->contains($grupo->id))
                            <a href="{{ route('grupos.show', $grupo) }}" class="btn btn-outline-primary btn-sm">Ver foro</a>
                        @else
                            <form method="POST" action="{{ route('grupos.join', $grupo) }}">
                                @csrf
                                <button class="btn btn-primary btn-sm">Unirse</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Aún no hay grupos.</p>
    @endforelse
</div>
@endsection
