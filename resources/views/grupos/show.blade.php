@extends('layouts.app')
@section('titulo', $grupo->nombre)

@section('contenido')
<a href="{{ route('grupos.index') }}" class="pill-btn d-inline-flex align-items-center gap-1 mb-3">
    <i class="bi bi-arrow-left"></i> Volver a grupos
</a>

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="fw-display mb-1" style="color:var(--coral)">{{ $grupo->nombre }}</h1>
        <p class="text-muted mb-0">{{ $grupo->descripcion }}</p>
    </div>
    @unless ($esMiembro)
        <form method="POST" action="{{ route('grupos.join', $grupo) }}">
            @csrf
            <button class="btn btn-primary btn-sm">Unirse al grupo</button>
        </form>
    @endunless
</div>

<h2 class="section-title mb-3"><i class="bi bi-chat-dots"></i> Foro</h2>

@if ($esMiembro)
    <form method="POST" action="{{ route('posts.store', $grupo) }}" class="card card-body mb-4">
        @csrf
        <textarea name="contenido" class="form-control mb-2" rows="2" placeholder="Escribe un mensaje..." required></textarea>
        <div><button class="btn btn-primary btn-sm">Publicar</button></div>
    </form>
@else
    <div class="promo text-muted mb-4">Únete al grupo para participar en el foro.</div>
@endif

@forelse ($grupo->posts as $post)
    <div class="card mb-2">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{ $post->autor->alias ?? 'Lector' }}</strong>
                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
            </div>
            <p class="mb-0">{{ $post->contenido }}</p>
        </div>
    </div>
@empty
    <p class="text-muted">No hay mensajes todavía.</p>
@endforelse
@endsection
