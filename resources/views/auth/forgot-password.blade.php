@extends('layouts.app')
@section('titulo', 'Recuperar contraseña')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Recuperar contraseña</h2>
                <p class="text-muted small">Escribe tu correo y te generaremos un enlace de reseteo.</p>

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
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button class="btn btn-primary w-100">Generar enlace</button>
                </form>
                <p class="text-center mt-3 mb-0 small"><a href="{{ route('login') }}">Volver al login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
