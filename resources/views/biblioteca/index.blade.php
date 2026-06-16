@extends('layouts.app')
@section('titulo', 'Mi biblioteca')

@section('contenido')
<h1 class="section-title mb-4"><i class="bi bi-bookmark-heart"></i> Mi biblioteca</h1>

@php
    $etiquetas = [
        'leyendo'   => ['Leyendo',     'bi-book-half',     'var(--coral)'],
        'por_leer'  => ['Por leer',    'bi-hourglass-split','var(--ribbon)'],
        'terminado' => ['Terminados',  'bi-check2-circle', 'var(--green)'],
    ];
@endphp

@foreach ($etiquetas as $clave => [$titulo, $icono, $color])
    <section class="mb-5">
        <h2 class="section-title mb-3" style="font-size:1.2rem">
            <i class="bi {{ $icono }}" style="color:{{ $color }}"></i> {{ $titulo }}
            <span class="badge text-bg-secondary align-middle">{{ optional($porEstado->get($clave))->count() ?? 0 }}</span>
        </h2>
        <div class="row g-4">
            @forelse ($porEstado->get($clave, collect()) as $lectura)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <a href="{{ route('libros.show', $lectura->libro) }}" class="book mb-2">
                        @include('partials.portada', ['libro' => $lectura->libro])
                    </a>
                    <a href="{{ route('libros.show', $lectura->libro) }}" class="book-title d-block text-truncate text-decoration-none">{{ $lectura->libro->titulo }}</a>
                    @if ($clave === 'leyendo')
                        <div class="progress mt-1" style="height:10px">
                            <div class="progress-bar" style="width: {{ $lectura->progreso }}%"></div>
                        </div>
                        <span class="text-muted" style="font-size:.72rem">{{ $lectura->progreso }}%</span>
                    @endif
                </div>
            @empty
                <div class="col-12"><p class="text-muted mb-0">Nada en esta categoría.</p></div>
            @endforelse
        </div>
    </section>
@endforeach
@endsection
