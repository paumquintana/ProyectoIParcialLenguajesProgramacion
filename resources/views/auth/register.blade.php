@extends('layouts.app')
@section('titulo', 'Crear cuenta')

@section('contenido')
<div class="guest-card p-4 p-md-5 w-100" style="max-width:560px">
    <div class="text-center mb-4">
        <div class="brand-badge mb-3"><i class="bi bi-book-half"></i></div>
        <h1 class="fw-display mb-1">Crear cuenta</h1>
        <p class="text-muted mb-0">Únete a LecturaApp</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label small">Alias *</label>
            <input type="text" name="alias" value="{{ old('alias') }}"
                   class="form-control @error('alias') is-invalid @enderror" required>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label small">Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
            </div>
            <div class="col mb-3">
                <label class="form-label small">Apellido</label>
                <input type="text" name="apellido" value="{{ old('apellido') }}" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label small">Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label small">Correo *</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label small">Contraseña *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col mb-3">
                <label class="form-label small">Confirmar *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>
        <button class="btn btn-primary w-100">Crear cuenta</button>
    </form>

    <p class="text-center mt-3 mb-0 small">
        ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
    </p>
</div>
@endsection
