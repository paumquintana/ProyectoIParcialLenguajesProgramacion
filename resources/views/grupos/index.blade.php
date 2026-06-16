@extends('layouts.app')
@section('titulo', 'Grupos de lectura')

@section('contenido')
<h1 class="section-title mb-4"><i class="bi bi-people"></i> Grupos de lectura</h1>

<div class="row g-4">
    @forelse ($grupos as $grupo)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h2 class="fw-display h5 mb-0">
                            <a href="{{ route('grupos.show', $grupo) }}" class="text-decoration-none" style="color:var(--coral)">{{ $grupo->nombre }}</a>
                        </h2>
                        @if ($misGrupos->contains($grupo->id))
                            <span class="badge rounded-pill" style="background:var(--green)">Miembro</span>
                        @endif
                    </div>
                    <p class="text-muted small">{{ $grupo->descripcion }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
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
        <div class="col-12"><div class="promo text-muted text-center py-5">Aún no hay grupos.</div></div>
    @endforelse
</div>
@endsection
