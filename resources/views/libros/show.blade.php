@extends('layouts.app')
@section('titulo', $libro->titulo)

@section('contenido')
<a href="{{ route('libros.index') }}" class="pill-btn d-inline-flex align-items-center gap-1 mb-3">
    <i class="bi bi-arrow-left"></i> Volver al catálogo
</a>

<div class="row g-4">
    <div class="col-md-3">
        <div class="book">
            @include('partials.portada', ['libro' => $libro])
        </div>
    </div>
    <div class="col-md-9">
        <h1 class="fw-display" style="color:var(--coral)">{{ $libro->titulo }}</h1>
        <p class="text-muted mb-2">
            {{ $libro->autor->nombre ?? '' }} {{ $libro->autor->apellido ?? '' }}
            @if ($libro->anio_publicacion) · {{ $libro->anio_publicacion }} @endif
            @if ($libro->total_paginas) · {{ $libro->total_paginas }} págs @endif
        </p>
        <div class="mb-3 d-flex flex-wrap gap-2">
            @foreach ($libro->generos as $g)
                <span class="badge text-bg-secondary rounded-pill px-3 py-2">{{ $g->nombre }}</span>
            @endforeach
        </div>
        <p style="max-width:60ch">{{ $libro->sinopsis ?: 'Sin sinopsis disponible.' }}</p>

        {{-- Agregar a mi biblioteca o gestionar progreso --}}
        @if (! $miLectura)
            <form method="POST" action="{{ route('biblioteca.store') }}" class="d-flex flex-wrap gap-2 align-items-end">
                @csrf
                <input type="hidden" name="libro_id" value="{{ $libro->id }}">
                <div>
                    <label class="form-label small mb-1">Agregar a mi biblioteca como:</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="por_leer">Por leer</option>
                        <option value="leyendo">Leyendo</option>
                        <option value="terminado">Terminado</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Agregar</button>
            </form>
        @else
            <div class="promo">
                <h2 class="section-title mb-3" style="font-size:1.1rem"><i class="bi bi-graph-up-arrow"></i> Mi progreso</h2>
                <form method="POST" action="{{ route('biblioteca.progreso', $miLectura) }}" class="row g-2 align-items-end">
                    @csrf
                    @method('PATCH')
                    <div class="col-auto">
                        <label class="form-label small mb-1">Páginas leídas</label>
                        <input type="number" name="paginas_leidas" value="{{ $miLectura->paginas_leidas }}"
                               min="0" max="{{ $libro->total_paginas }}" class="form-control form-control-sm" style="width:120px">
                    </div>
                    <div class="col-auto">
                        <label class="form-label small mb-1">Estado</label>
                        <select name="estado" class="form-select form-select-sm">
                            <option value="por_leer" @selected($miLectura->estado=='por_leer')>Por leer</option>
                            <option value="leyendo" @selected($miLectura->estado=='leyendo')>Leyendo</option>
                            <option value="terminado" @selected($miLectura->estado=='terminado')>Terminado</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success btn-sm">Actualizar</button>
                    </div>
                </form>
                <div class="progress mt-3" style="height:14px">
                    <div class="progress-bar" style="width: {{ $miLectura->progreso }}%">{{ $miLectura->progreso }}%</div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ---------- Reseñas ---------- --}}
<hr class="my-4" style="border-color:var(--line)">
<h2 class="section-title mb-3"><i class="bi bi-chat-heart"></i> Reseñas</h2>

@if ($miLectura)
    <form method="POST" action="{{ route('resenias.store') }}" class="card card-body mb-4">
        @csrf
        <input type="hidden" name="lector_libro_id" value="{{ $miLectura->id }}">
        <div class="mb-2">
            <label class="form-label small mb-1">Tu puntuación</label>
            <select name="puntuacion" class="form-select form-select-sm" style="width:140px" required>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ str_repeat('★', $i) }} ({{ $i }})</option>
                @endfor
            </select>
        </div>
        <div class="mb-2">
            <label class="form-label small mb-1">Comentario</label>
            <textarea name="comentario" class="form-control" rows="2" placeholder="¿Qué te pareció?"></textarea>
        </div>
        <div><button class="btn btn-primary btn-sm">Publicar reseña</button></div>
    </form>
@else
    <p class="text-muted small">Agrega el libro a tu biblioteca para poder reseñarlo.</p>
@endif

@forelse ($resenias as $r)
    <div class="card mb-2">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{ $r->lectorLibro->lector->alias ?? 'Lector' }}</strong>
                <span style="color:var(--ribbon)">{{ str_repeat('★', $r->puntuacion) }}<span class="text-muted">{{ str_repeat('☆', 5 - $r->puntuacion) }}</span></span>
            </div>
            @if ($r->comentario)<p class="mb-1">{{ $r->comentario }}</p>@endif
            <small class="text-muted">{{ $r->fecha }}</small>
        </div>
    </div>
@empty
    <p class="text-muted">Todavía no hay reseñas. ¡Sé el primero!</p>
@endforelse
@endsection
