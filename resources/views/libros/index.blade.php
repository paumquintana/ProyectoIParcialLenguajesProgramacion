@extends('layouts.app')
@section('titulo', 'Catálogo')

@section('contenido')
@php
    // Paleta e iconos que rotan para las categorías (estilo foto de referencia).
    $catColors = ['#ef5d60','#f3a83b','#6bbf8a','#5aa9e6','#8a7fd6','#e0568f','#46b1a8','#f08a5d'];
    $catIcons  = ['bi-book','bi-emoji-smile','bi-heart','bi-stars','bi-rocket','bi-magic','bi-globe-americas','bi-mortarboard'];
@endphp

{{-- ---------- Fila de categorías ---------- --}}
<div class="d-flex gap-3 overflow-auto pb-2 mb-4">
    <a href="{{ route('libros.index', array_filter(['q' => request('q')])) }}"
       class="chip-cat {{ ! request('genero') ? 'active' : '' }}">
        <span class="ico" style="background:var(--ink)"><i class="bi bi-grid-3x3-gap"></i></span>
        Todos
    </a>
    @foreach ($generos as $i => $g)
        <a href="{{ route('libros.index', array_filter(['q' => request('q'), 'genero' => $g->id])) }}"
           class="chip-cat {{ request('genero') == $g->id ? 'active' : '' }}">
            <span class="ico" style="background:{{ $catColors[$i % count($catColors)] }}">
                <i class="bi {{ $catIcons[$i % count($catIcons)] }}"></i>
            </span>
            <span class="text-truncate" style="max-width:72px">{{ $g->nombre }}</span>
        </a>
    @endforeach
</div>

{{-- ---------- Encabezado ---------- --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="section-title">
        @if (request('q'))
            Resultados para "{{ request('q') }}"
        @else
            Catálogo
        @endif
    </h1>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">{{ $libros->total() }} libros</span>
        <a href="{{ route('libros.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Agregar libro</a>
    </div>
</div>

{{-- ---------- Rejilla de libros ---------- --}}
<div class="row g-4">
    @forelse ($libros as $libro)
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            @include('partials.libro-card', ['libro' => $libro])
        </div>
    @empty
        <div class="col-12">
            <div class="promo text-center text-muted py-5">
                <i class="bi bi-search fs-2 d-block mb-2"></i>
                No se encontraron libros con esos criterios.
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $libros->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
@endsection
