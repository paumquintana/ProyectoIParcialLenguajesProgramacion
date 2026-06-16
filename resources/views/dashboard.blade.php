@extends('layouts.app')
@section('titulo', 'Inicio')

@section('contenido')
<h1 class="h3 mb-4">Hola, {{ auth()->user()->nombre }} 👋</h1>

{{-- Libros que estoy leyendo, con barra de progreso --}}
<section class="mb-5">
    <h2 class="h5 mb-3"><i class="bi bi-bookmark-check"></i> Leyendo ahora</h2>
    @forelse ($leyendo as $lectura)
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex gap-3 align-items-center">
                @if ($lectura->libro->portada_url)
                    <img src="{{ $lectura->libro->portada_url }}" alt="" style="width:60px" class="rounded">
                @else
                    <div class="bg-secondary-subtle rounded d-flex align-items-center justify-content-center" style="width:60px;height:90px">
                        <i class="bi bi-book"></i>
                    </div>
                @endif
                <div class="flex-grow-1">
                    <a href="{{ route('libros.show', $lectura->libro) }}" class="fw-semibold text-decoration-none">
                        {{ $lectura->libro->titulo }}
                    </a>
                    <div class="text-muted small mb-1">
                        {{ $lectura->libro->autor->nombre ?? '' }} {{ $lectura->libro->autor->apellido ?? '' }}
                    </div>
                    <div class="progress" style="height:18px">
                        <div class="progress-bar" role="progressbar"
                             style="width: {{ $lectura->progreso }}%">{{ $lectura->progreso }}%</div>
                    </div>
                    <div class="small text-muted mt-1">
                        {{ $lectura->paginas_leidas }} / {{ $lectura->libro->total_paginas ?? '?' }} páginas
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">No tienes libros en curso. Explora el <a href="{{ route('libros.index') }}">catálogo</a>.</p>
    @endforelse
</section>

{{-- Próximos a leer --}}
<section class="mb-5">
    <h2 class="h5 mb-3"><i class="bi bi-hourglass-split"></i> Próximos a leer</h2>
    @forelse ($porLeer as $lectura)
        <span class="badge text-bg-light border me-2 mb-2 p-2">
            <a href="{{ route('libros.show', $lectura->libro) }}" class="text-decoration-none">{{ $lectura->libro->titulo }}</a>
        </span>
    @empty
        <p class="text-muted">Nada pendiente por ahora.</p>
    @endforelse
</section>

{{-- Recomendaciones según géneros que más lees --}}
<section>
    <h2 class="h5 mb-3"><i class="bi bi-stars"></i> Recomendados para ti</h2>
    <div class="row g-3">
        @forelse ($recomendaciones as $libro)
            <div class="col-6 col-md-3">
                <div class="card h-100 shadow-sm">
                    <a href="{{ route('libros.show', $libro) }}">
                        @if ($libro->portada_url)
                            <img src="{{ $libro->portada_url }}" class="card-img-top card-portada" alt="">
                        @else
                            <div class="card-portada d-flex align-items-center justify-content-center"><i class="bi bi-book fs-1 text-muted"></i></div>
                        @endif
                    </a>
                    <div class="card-body p-2">
                        <a href="{{ route('libros.show', $libro) }}" class="small fw-semibold text-decoration-none d-block text-truncate">{{ $libro->titulo }}</a>
                        <span class="text-muted" style="font-size:.75rem">{{ $libro->autor->nombre ?? '' }} {{ $libro->autor->apellido ?? '' }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Aún no hay recomendaciones.</p>
        @endforelse
    </div>
</section>
@endsection
