{{-- Tarjeta reutilizable de un libro. Espera $libro. --}}
<div class="card h-100">
  <a href="{{ route('libros.show', $libro) }}" class="text-decoration-none">
    @if ($libro->portada_url)
      <img src="{{ $libro->portada_url }}" class="cover card-img-top" alt="{{ $libro->titulo }}">
    @else
      <div class="cover cover-ph card-img-top"><i class="bi bi-book"></i></div>
    @endif
  </a>
  <div class="card-body p-2">
    <a href="{{ route('libros.show', $libro) }}" class="text-decoration-none text-dark">
      <h6 class="card-title mb-1 text-truncate" title="{{ $libro->titulo }}">{{ $libro->titulo }}</h6>
    </a>
    <p class="card-text small text-muted mb-0">
      {{ $libro->autor?->nombre }} {{ $libro->autor?->apellido }}
    </p>
  </div>
</div>
