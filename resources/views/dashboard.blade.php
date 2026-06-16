@extends('layouts.app')
@section('titulo', 'Inicio')

@section('contenido')
<h1 class="section-title mb-4">Hola, {{ auth()->user()->nombre }}</h1>

<div class="row g-4">
    {{-- ===================== IZQUIERDA: leyendo ahora ===================== --}}
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title"><i class="bi bi-bookmark-check"></i> Leyendo ahora</h2>
            <a href="{{ route('biblioteca.index') }}" class="pill-btn">Ver biblioteca</a>
        </div>

        <div class="row g-3">
            @forelse ($leyendo as $lectura)
                <div class="col-sm-6">
                    <div class="card h-100">
                        <div class="card-body d-flex gap-3">
                            <a href="{{ route('libros.show', $lectura->libro) }}" class="book flex-shrink-0" style="width:60px">
                                @include('partials.portada', ['libro' => $lectura->libro])
                            </a>
                            <div class="flex-grow-1 d-flex flex-column" style="min-width:0">
                                <a href="{{ route('libros.show', $lectura->libro) }}" class="book-title text-decoration-none text-truncate" title="{{ $lectura->libro->titulo }}">
                                    {{ $lectura->libro->titulo }}
                                </a>
                                <div class="text-muted small text-truncate mb-2">
                                    {{ $lectura->libro->autor->nombre ?? '' }} {{ $lectura->libro->autor->apellido ?? '' }}
                                </div>
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-end mb-1">
                                        <span class="fw-display" style="font-size:1.05rem;color:var(--coral);line-height:1">{{ $lectura->progreso }}%</span>
                                        <span class="text-muted" style="font-size:.7rem">{{ $lectura->paginas_leidas }} / {{ $lectura->libro->total_paginas ?? '?' }} pág.</span>
                                    </div>
                                    <div class="progress" style="height:8px">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $lectura->progreso }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="promo text-muted">
                        No tienes libros en curso. Explora el <a href="{{ route('libros.index') }}">catálogo</a> para empezar.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ===================== DERECHA: próximos + recomendados ===================== --}}
    <div class="col-lg-4">
        {{-- ---------- Próximos a leer ---------- --}}
        <section class="mb-4">
            <h2 class="section-title mb-3" style="font-size:1.2rem"><i class="bi bi-hourglass-split"></i> Próximos a leer</h2>
            @forelse ($porLeer as $lectura)
                <a href="{{ route('libros.show', $lectura->libro) }}" class="card mb-2 text-decoration-none">
                    <div class="card-body py-2 d-flex align-items-center gap-2">
                        <span class="book flex-shrink-0" style="width:32px">
                            @include('partials.portada', ['libro' => $lectura->libro])
                        </span>
                        <span class="flex-grow-1" style="min-width:0">
                            <span class="book-title d-block text-truncate" style="font-size:.9rem">{{ $lectura->libro->titulo }}</span>
                            <span class="text-muted d-block text-truncate" style="font-size:.7rem">{{ $lectura->libro->autor->nombre ?? '' }} {{ $lectura->libro->autor->apellido ?? '' }}</span>
                        </span>
                    </div>
                </a>
            @empty
                <p class="text-muted small">Nada pendiente por ahora.</p>
            @endforelse
        </section>

        {{-- ---------- Recomendados ---------- --}}
        <section>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title" style="font-size:1.2rem"><i class="bi bi-stars"></i> Recomendados</h2>
                <a href="{{ route('libros.index') }}" class="pill-btn">Ver todo</a>
            </div>
            <div class="row g-3">
                @forelse ($recomendaciones->take(6) as $libro)
                    <div class="col-6">
                        @include('partials.libro-card', ['libro' => $libro])
                    </div>
                @empty
                    <p class="text-muted small">Aún no hay recomendaciones.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
