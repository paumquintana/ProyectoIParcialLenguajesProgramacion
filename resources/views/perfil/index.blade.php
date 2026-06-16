@extends('layouts.app')
@section('titulo', 'Mi perfil')

@section('contenido')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="section-title mb-0"><i class="bi bi-person-circle"></i> Mi perfil</h1>
    <a href="{{ route('configuracion.index') }}" class="pill-btn"><i class="bi bi-gear"></i> Configuración</a>
</div>

{{-- ---------- Cabecera del perfil ---------- --}}
<div class="promo d-flex flex-wrap align-items-center gap-3 mb-4">
    <div class="brand-badge" style="margin:0;font-family:'Fraunces',serif">
        {{ mb_strtoupper(mb_substr($user->nombre ?? $user->alias, 0, 1)) }}
    </div>
    <div class="flex-grow-1">
        <h2 class="fw-display mb-0" style="color:var(--coral)">{{ $user->nombre }} {{ $user->apellido }}</h2>
        <div class="text-muted">{{ '@' . $user->alias }} · {{ $user->email }}</div>
        @if ($user->created_at)
            <div class="text-muted small">Miembro desde {{ $user->created_at->format('d/m/Y') }}</div>
        @endif
    </div>
</div>

{{-- ---------- Estadísticas ---------- --}}
@php
    $tarjetas = [
        ['Leyendo',    $stats['leyendo'],   'bi-book-half',      'var(--coral)'],
        ['Por leer',   $stats['por_leer'],  'bi-hourglass-split','var(--ribbon)'],
        ['Terminados', $stats['terminado'], 'bi-check2-circle',  'var(--green)'],
        ['Reseñas',    $stats['resenias'],  'bi-chat-heart',     'var(--purple)'],
        ['Grupos',     $stats['grupos'],    'bi-people',         'var(--blue)'],
    ];
@endphp
<div class="row g-3 mb-4">
    @foreach ($tarjetas as [$label, $valor, $icono, $color])
        <div class="col-6 col-md">
            <div class="card h-100 text-center">
                <div class="card-body py-3">
                    <i class="bi {{ $icono }} fs-4" style="color:{{ $color }}"></i>
                    <div class="fw-display" style="font-size:1.8rem;line-height:1.1">{{ $valor }}</div>
                    <div class="text-muted small">{{ $label }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- ---------- Leyendo ahora ---------- --}}
<section class="mb-5">
    <h2 class="section-title mb-3" style="font-size:1.2rem"><i class="bi bi-bookmark-check"></i> Leyendo ahora</h2>
    <div class="row g-3">
        @forelse ($leyendo as $lectura)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body d-flex gap-3 align-items-center">
                        <a href="{{ route('libros.show', $lectura->libro) }}" class="book flex-shrink-0" style="width:48px">
                            @include('partials.portada', ['libro' => $lectura->libro])
                        </a>
                        <div class="flex-grow-1">
                            <a href="{{ route('libros.show', $lectura->libro) }}" class="book-title text-decoration-none">{{ $lectura->libro->titulo }}</a>
                            <div class="progress mt-1" style="height:12px">
                                <div class="progress-bar" style="width: {{ $lectura->progreso }}%"></div>
                            </div>
                            <div class="small text-muted mt-1">{{ $lectura->progreso }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="promo text-muted">No tienes libros en curso. Explora el <a href="{{ route('libros.index') }}">catálogo</a>.</div>
            </div>
        @endforelse
    </div>
</section>

{{-- ---------- Mis grupos (galería de tarjetas) ---------- --}}
@php
    $grupoColores = [
        ['#fdf0c9', '#d9a227'],
        ['#fbe0e6', '#d4537e'],
        ['#d9f0e6', '#1d9e75'],
        ['#eceafb', '#7f77dd'],
        ['#e2eefb', '#378add'],
        ['#fae7dc', '#d85a30'],
    ];
@endphp
<section>
    <h2 class="section-title mb-3" style="font-size:1.2rem"><i class="bi bi-people"></i> Mis grupos</h2>
    <div class="row g-3">
        @forelse ($grupos as $i => $grupo)
            @php [$bg, $fg] = $grupoColores[$i % count($grupoColores)]; @endphp
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('grupos.show', $grupo) }}" class="card h-100 text-center text-decoration-none">
                    <div class="card-body">
                        <span class="d-inline-grid mb-2" style="place-items:center;width:60px;height:60px;border-radius:50%;background:{{ $bg }};color:{{ $fg }};font-size:1.5rem">
                            <i class="bi bi-people-fill"></i>
                        </span>
                        <div class="fw-display text-truncate" style="color:var(--coral)" title="{{ $grupo->nombre }}">{{ $grupo->nombre }}</div>
                        <div class="text-muted small">{{ $grupo->lectores_count }} {{ $grupo->lectores_count == 1 ? 'miembro' : 'miembros' }}</div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Aún no perteneces a ningún grupo. <a href="{{ route('grupos.index') }}">Únete a uno</a>.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
