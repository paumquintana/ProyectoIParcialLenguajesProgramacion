@extends('layouts.app')
@section('titulo', 'Agregar libro')

@section('contenido')
<a href="{{ route('libros.index') }}" class="pill-btn d-inline-flex align-items-center gap-1 mb-3">
    <i class="bi bi-arrow-left"></i> Volver al catálogo
</a>

<h1 class="section-title mb-1"><i class="bi bi-journal-plus"></i> Agregar un libro</h1>
<p class="text-muted mb-4">¿No encuentras un libro en el catálogo? Regístralo aquí y quedará guardado para todos.</p>

<form method="POST" action="{{ route('libros.store') }}" class="row g-4">
    @csrf

    {{-- Columna izquierda: portada + vista previa --}}
    <div class="col-md-4">
        <label class="form-label small">URL de la portada</label>
        <input type="url" name="cover_url" id="cover_url" value="{{ old('cover_url') }}"
               class="form-control mb-2" placeholder="https://...jpg">
        <div class="book" style="max-width:220px">
            <img id="cover_preview" src="{{ old('cover_url') }}" alt="Vista previa"
                 style="{{ old('cover_url') ? '' : 'display:none' }}">
            <span class="ph" id="cover_placeholder" style="{{ old('cover_url') ? 'display:none' : '' }}">
                <i class="bi bi-image"></i>
            </span>
        </div>
        <p class="text-muted mt-2" style="font-size:.75rem">Pega el enlace de una imagen. Si lo dejas vacío, se generará una portada con el título.</p>
    </div>

    {{-- Columna derecha: datos --}}
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label small">Título *</label>
            <input type="text" name="titulo" value="{{ old('titulo') }}"
                   class="form-control @error('titulo') is-invalid @enderror" required>
            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col mb-3">
                <label class="form-label small">Nombre del autor *</label>
                <input type="text" name="autor_nombre" value="{{ old('autor_nombre') }}"
                       class="form-control @error('autor_nombre') is-invalid @enderror" required>
                @error('autor_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col mb-3">
                <label class="form-label small">Apellido del autor</label>
                <input type="text" name="autor_apellido" value="{{ old('autor_apellido') }}" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col mb-3">
                <label class="form-label small">Año de publicación</label>
                <input type="number" name="anio_publicacion" value="{{ old('anio_publicacion') }}"
                       class="form-control" min="0" max="{{ date('Y') }}" placeholder="Ej. 1997">
            </div>
            <div class="col mb-3">
                <label class="form-label small">Total de páginas</label>
                <input type="number" name="total_paginas" value="{{ old('total_paginas') }}"
                       class="form-control" min="1" placeholder="Ej. 320">
            </div>
            <div class="col mb-3">
                <label class="form-label small">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn') }}" class="form-control" placeholder="Opcional">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small">Sinopsis</label>
            <textarea name="sinopsis" rows="3" class="form-control" placeholder="¿De qué trata el libro?">{{ old('sinopsis') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label small d-block">Géneros</label>
            <div class="d-flex flex-wrap gap-3">
                @forelse ($generos as $g)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="generos[]"
                               value="{{ $g->id }}" id="g{{ $g->id }}"
                               @checked(collect(old('generos'))->contains($g->id))>
                        <label class="form-check-label" for="g{{ $g->id }}">{{ $g->nombre }}</label>
                    </div>
                @empty
                    <span class="text-muted small">Aún no hay géneros; crea uno abajo.</span>
                @endforelse
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small">¿No está el género? Crea uno nuevo</label>
            <input type="text" name="genero_nuevo" value="{{ old('genero_nuevo') }}"
                   class="form-control" style="max-width:320px" placeholder="Ej. Aventura">
        </div>

        <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Guardar libro</button>
        <a href="{{ route('libros.index') }}" class="pill-btn ms-2">Cancelar</a>
    </div>
</form>

<script>
    // Vista previa de la portada al pegar el enlace.
    const url = document.getElementById('cover_url');
    const img = document.getElementById('cover_preview');
    const ph  = document.getElementById('cover_placeholder');
    url.addEventListener('input', () => {
        const v = url.value.trim();
        if (v) { img.src = v; img.style.display = 'block'; ph.style.display = 'none'; }
        else   { img.style.display = 'none'; ph.style.display = 'grid'; }
    });
    img.addEventListener('error', () => { img.style.display = 'none'; ph.style.display = 'grid'; });
</script>
@endsection
