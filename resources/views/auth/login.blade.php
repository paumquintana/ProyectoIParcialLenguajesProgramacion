@extends('layouts.app')
@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <h1 class="h3"><i class="bi bi-book-half"></i> LecturaApp</h1>
            <p class="text-muted">Tu seguimiento de lecturas</p>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Iniciar sesión</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Alias o correo</label>
                        <input type="text" name="login" value="{{ old('login') }}"
                               class="form-control @error('login') is-invalid @enderror" autofocus required>
                        @error('login') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Recordarme</label>
                    </div>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('password.request') }}" class="small">¿Olvidaste tu contraseña?</a>
                    <a href="{{ route('register') }}" class="small">Crear cuenta</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
