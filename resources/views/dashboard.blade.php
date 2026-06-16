@extends('layouts.app')
@section('titulo', 'Inicio')

@section('contenido')
<h1 class="section-title mb-4">Hola, {{ auth()->user()->nombre }}</h1>

{{-- ---------- Leyendo ahora ---------- --}}
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title"><i class="bi bi-bookmark-check text-coral"></i> Leyendo ahora</h2>
        <a href="{{ route('biblioteca.index') }}" class="pill-btn">Ver biblioteca</a>
    </div>

    @forelse ($leyendo as $lectura)
        <div class="card mb-3">
            <div class="card-body d-flex gap-3 align-items-center">
                <a href="{{ route('libros.show', $lectura->libro) }}" class="book flex-shrink-0" style="width:56px">
                    @include('partials.portada', ['libro' => $lectura->libro])
                </a>
                <div class="flex-grow-1">
                    <a href="{{ route('libros.show', $lectura->libro) }}" class="book-title text-decoration-none">
                        {{ $lectura->libro->titulo }}
                    </a>
                    <div class="text-muted small mb-2">
                        {{ $lectura->libro->autor->nombre ?? '' }} {{ $lectura->libro->autor->apellido ?? '' }}
                    </div>
                    <div class="progress" style="height:14px">
                        <div class="progress-bar" role="progressbar" style="width: {{ $lectura->progreso }}%"></div>
                    </div>
                    <div class="small text-muted mt-1">
                        {{ $lectura->progreso }}% · {{ $lectura->paginas_leidas }} / {{ $lectura->libro->total_paginas ?? '?' }} páginas
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="promo text-muted">
            No tienes libros en curso. Explora el <a href="{{ route('libros.index') }}">catálogo</a> para empezar.
        </div>
    @endforelse
</section>

{{-- ---------- Próximos a leer ---------- --}}
<section class="mb-5">
    <h2 class="section-title mb-3"><i class="bi bi-hourglass-split"></i> Próximos a leer</h2>
    @forelse ($porLeer as $lectura)
        <a href="{{ route('libros.show', $lectura->libro) }}" class="pill-btn d-inline-flex align-items-center gap-1 me-2 mb-2">
            <i class="bi bi-bookmark"></i> {{ $lectura->libro->titulo }}
        </a>
    @empty
        <p class="text-muted">Nada pendiente por ahora.</p>
    @endforelse
</section>

{{-- ---------- Recomendados ---------- --}}
<section>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title"><i class="bi bi-stars"></i> Recomendados para ti</h2>
        <a href="{{ route('libros.index') }}" class="pill-btn">Ver todo</a>
    </div>
    <div class="row g-4">
        @forelse ($recomendaciones as $libro)
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                @include('partials.libro-card', ['libro' => $libro])
            </div>
        @empty
            <p class="text-muted">Aún no hay recomendaciones.</p>
        @endforelse
    </div>
</section>
@endsection
