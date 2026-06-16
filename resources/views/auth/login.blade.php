@extends('layouts.app')
@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="guest-card p-4 p-md-5 w-100" style="max-width:440px">
    <div class="text-center mb-4">
        <div class="brand-badge mb-3"><i class="bi bi-book-half"></i></div>
        <h1 class="fw-display mb-1">LecturaApp</h1>
        <p class="text-muted mb-0">Tu seguimiento de lecturas</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Alias o correo</label>
            <input type="text" name="login" value="{{ old('login') }}"
                   class="form-control @error('login') is-invalid @enderror" autofocus required>
        </div>
        <div class="mb-3">
            <label class="form-label small">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="remember" id="remember" class="form-check-input">
            <label for="remember" class="form-check-label small">Recordarme</label>
        </div>
        <button class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('password.request') }}" class="small">¿Olvidaste tu contraseña?</a>
        <a href="{{ route('register') }}" class="small">Crear cuenta</a>
    </div>
</div>
@endsection
