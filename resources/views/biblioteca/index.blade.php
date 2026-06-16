@extends('layouts.app')
@section('titulo', 'Mi biblioteca')

@section('contenido')
<h1 class="h3 mb-4">Mi biblioteca</h1>

@php
    $etiquetas = ['leyendo' => 'Leyendo', 'por_leer' => 'Por leer', 'terminado' => 'Terminados'];
@endphp

@foreach ($etiquetas as $clave => $titulo)
    <section class="mb-4">
        <h2 class="h5 mb-3">{{ $titulo }}
            <span class="badge text-bg-secondary">{{ optional($porEstado->get($clave))->count() ?? 0 }}</span>
        </h2>
        <div class="row g-3">
            @forelse ($porEstado->get($clave, collect()) as $lectura)
                <div class="col-6 col-md-3">
                    <div class="card h-100 shadow-sm">
                        <a href="{{ route('libros.show', $lectura->libro) }}">
                            @if ($lectura->libro->portada_url)
                                <img src="{{ $lectura->libro->portada_url }}" class="card-img-top card-portada" alt="">
                            @else
                                <div class="card-portada d-flex align-items-center justify-content-center"><i class="bi bi-book fs-1 text-muted"></i></div>
                            @endif
                        </a>
                        <div class="card-body p-2">
                            <a href="{{ route('libros.show', $lectura->libro) }}" class="small fw-semibold text-decoration-none d-block">{{ $lectura->libro->titulo }}</a>
                            @if ($clave === 'leyendo')
                                <div class="progress mt-1" style="height:12px">
                                    <div class="progress-bar" style="width: {{ $lectura->progreso }}%"></div>
                                </div>
                                <span class="text-muted" style="font-size:.7rem">{{ $lectura->progreso }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Nada en esta categoría.</p>
            @endforelse
        </div>
    </section>
@endforeach
@endsection
