@extends('layouts.app')
@section('titulo', 'Crear cuenta')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Crear cuenta</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Alias *</label>
                        <input type="text" name="alias" value="{{ old('alias') }}"
                               class="form-control @error('alias') is-invalid @enderror" required>
                        @error('alias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Contraseña *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col mb-3">
                            <label class="form-label">Confirmar *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100">Crear cuenta</button>
                </form>
                <p class="text-center mt-3 mb-0 small">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
