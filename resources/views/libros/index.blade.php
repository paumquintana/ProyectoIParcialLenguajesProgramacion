@extends('layouts.app')
@section('titulo', 'Catálogo')

@section('contenido')
<h1 class="h3 mb-4">Catálogo</h1>

<form method="GET" action="{{ route('libros.index') }}" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar por título o autor...">
    </div>
    <div class="col-md-4">
        <select name="genero" class="form-select">
            <option value="">Todos los géneros</option>
            @foreach ($generos as $g)
                <option value="{{ $g->id }}" @selected(request('genero') == $g->id)>{{ $g->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Buscar</button>
    </div>
</form>

<div class="row g-3">
    @forelse ($libros as $libro)
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
                    <a href="{{ route('libros.show', $libro) }}" class="small fw-semibold text-decoration-none d-block">{{ $libro->titulo }}</a>
                    <span class="text-muted" style="font-size:.75rem">{{ $libro->autor->nombre ?? '' }} {{ $libro->autor->apellido ?? '' }}</span>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">No se encontraron libros con esos criterios.</p>
    @endforelse
</div>

<div class="mt-4">
    {{ $libros->links() }}
</div>
@endsection
