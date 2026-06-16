@extends('layouts.app')
@section('titulo', 'Nueva contraseña')

@section('contenido')
<div class="guest-card p-4 p-md-5 w-100" style="max-width:440px">
    <div class="text-center mb-4">
        <div class="brand-badge mb-3"><i class="bi bi-shield-lock"></i></div>
        <h1 class="fw-display mb-1">Nueva contraseña</h1>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3">
            <label class="form-label small">Correo</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small">Nueva contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label small">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Guardar</button>
    </form>
</div>
@endsection
