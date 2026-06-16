{{-- Tarjeta reutilizable de un libro. Espera $libro. --}}
<a href="{{ route('libros.show', $libro) }}" class="book mb-2">
  @if ($libro->portada_url)
    <img src="{{ $libro->portada_url }}" alt="{{ $libro->titulo }}">
  @else
    <span class="ph"><i class="bi bi-book"></i></span>
  @endif
</a>
<a href="{{ route('libros.show', $libro) }}" class="book-title d-block text-truncate text-decoration-none" title="{{ $libro->titulo }}">
  {{ $libro->titulo }}
</a>
<p class="small text-muted mb-0">
  {{ $libro->autor?->nombre }} {{ $libro->autor?->apellido }}
</p>
