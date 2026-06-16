@extends('layouts.app')
@section('titulo', $libro->titulo)

@section('contenido')
<a href="{{ route('libros.index') }}" class="small text-decoration-none">&larr; Volver al catálogo</a>

<div class="row mt-3">
    <div class="col-md-3 mb-3">
        @if ($libro->portada_url)
            <img src="{{ $libro->portada_url }}" class="img-fluid rounded shadow-sm" alt="">
        @else
            <div class="card-portada rounded d-flex align-items-center justify-content-center"><i class="bi bi-book fs-1 text-muted"></i></div>
        @endif
    </div>
    <div class="col-md-9">
        <h1 class="h3">{{ $libro->titulo }}</h1>
        <p class="text-muted">
            {{ $libro->autor->nombre ?? '' }} {{ $libro->autor->apellido ?? '' }}
            @if ($libro->anio_publicacion) · {{ $libro->anio_publicacion }} @endif
            @if ($libro->total_paginas) · {{ $libro->total_paginas }} págs @endif
        </p>
        <div class="mb-3">
            @foreach ($libro->generos as $g)
                <span class="badge text-bg-secondary">{{ $g->nombre }}</span>
            @endforeach
        </div>
        <p>{{ $libro->sinopsis ?: 'Sin sinopsis disponible.' }}</p>

        {{-- Agregar a mi biblioteca o gestionar progreso --}}
        @if (! $miLectura)
            <form method="POST" action="{{ route('biblioteca.store') }}" class="d-flex gap-2 align-items-end">
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
                <button class="btn btn-primary btn-sm">Agregar</button>
            </form>
        @else
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h2 class="h6">Mi progreso</h2>
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
                    <div class="progress mt-2" style="height:16px">
                        <div class="progress-bar" style="width: {{ $miLectura->progreso }}%">{{ $miLectura->progreso }}%</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Reseñas --}}
<hr class="my-4">
<h2 class="h5 mb-3">Reseñas</h2>

@if ($miLectura)
    <form method="POST" action="{{ route('resenias.store') }}" class="card card-body mb-4">
        @csrf
        <input type="hidden" name="lector_libro_id" value="{{ $miLectura->id }}">
        <div class="mb-2">
            <label class="form-label small mb-1">Tu puntuación</label>
            <select name="puntuacion" class="form-select form-select-sm" style="width:120px" required>
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
    <div class="border-bottom py-2">
        <div class="d-flex justify-content-between">
            <strong>{{ $r->lectorLibro->lector->alias ?? 'Lector' }}</strong>
            <span class="text-warning">{{ str_repeat('★', $r->puntuacion) }}<span class="text-muted">{{ str_repeat('☆', 5 - $r->puntuacion) }}</span></span>
        </div>
        @if ($r->comentario)<p class="mb-1">{{ $r->comentario }}</p>@endif
        <small class="text-muted">{{ $r->fecha }}</small>
    </div>
@empty
    <p class="text-muted">Todavía no hay reseñas. ¡Sé el primero!</p>
@endforelse
@endsection
