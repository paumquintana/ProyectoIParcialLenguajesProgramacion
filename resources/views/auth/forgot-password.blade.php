@extends('layouts.app')
@section('titulo', 'Recuperar contraseña')

@section('contenido')
<div class="guest-card p-4 p-md-5 w-100" style="max-width:440px">
    <div class="text-center mb-4">
        <div class="brand-badge mb-3"><i class="bi bi-key"></i></div>
        <h1 class="fw-display mb-1">Recuperar contraseña</h1>
        <p class="text-muted mb-0 small">Escribe tu correo y te generaremos un enlace de reseteo.</p>
    </div>

    {{-- Como no hay servidor de correo, mostramos el enlace en pantalla --}}
    @if (session('reset_link'))
        <div class="alert alert-info">
            <p class="mb-1"><strong>Enlace de reseteo (demo, sin correo):</strong></p>
            <a href="{{ session('reset_link') }}" class="small text-break">{{ session('reset_link') }}</a>
            <p class="mb-0 mt-2 small text-muted">Válido por 60 minutos.</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Correo</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary w-100">Generar enlace</button>
    </form>
    <p class="text-center mt-3 mb-0 small"><a href="{{ route('login') }}">Volver al login</a></p>
</div>
@endsection
