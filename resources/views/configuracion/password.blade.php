@extends('layouts.app')
@section('titulo', 'Configuración · Contraseña')

@section('contenido')
<h1 class="section-title mb-4"><i class="bi bi-gear"></i> Configuración</h1>

<div class="row g-4">
    <div class="col-md-4 col-lg-3">
        @include('configuracion._nav')
    </div>

    <div class="col-md-8 col-lg-9">
        <div class="card">
            <div class="card-body">
                <h2 class="section-title mb-1" style="font-size:1.2rem">Cambiar contraseña</h2>
                <p class="text-muted small mb-4">Por seguridad, ingresa tu contraseña actual antes de cambiarla.</p>

                <form method="POST" action="{{ route('configuracion.password.update') }}" style="max-width:420px">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small">Contraseña actual *</label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Nueva contraseña *</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Mínimo 8 caracteres.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small">Confirmar nueva contraseña *</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-primary">Actualizar contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
