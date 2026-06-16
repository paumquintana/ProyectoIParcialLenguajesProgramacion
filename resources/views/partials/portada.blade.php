{{-- Portada de un libro. Espera $libro.
     Si el libro tiene cover_id (portada real de Open Library) muestra la imagen;
     si no, genera una portada con el título y un color estable según el título. --}}
@php
    $palette = ['#3d5a80','#9b8bbd','#d8584f','#2f7d5b','#c2557f','#3f7f8c','#c98a3b','#46857a','#7d6bb0','#b5503f'];
    $color = $palette[abs(crc32($libro->titulo)) % count($palette)];
@endphp
@if ($libro->portada_url)
    <img src="{{ $libro->portada_url }}" alt="Portada de {{ $libro->titulo }}">
@else
    <span class="ph-cover" style="background:{{ $color }}">
        <span class="ph-cover-title">{{ $libro->titulo }}</span>
        <span class="ph-cover-author">{{ $libro->autor?->nombre }} {{ $libro->autor?->apellido }}</span>
    </span>
@endif
