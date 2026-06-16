@extends('layouts.app')
@section('titulo', 'Configuración')

@section('contenido')
<h1 class="section-title mb-4"><i class="bi bi-gear"></i> Configuración</h1>

<div class="row g-3" style="max-width:720px">
    <div class="col-12">
        <a href="{{ route('configuracion.perfil.edit') }}" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="brand-badge" style="margin:0;width:48px;height:48px;border-radius:14px;font-size:1.2rem"><i class="bi bi-person"></i></span>
                <div class="flex-grow-1">
                    <div class="fw-display" style="color:var(--ink)">Editar perfil</div>
                    <div class="text-muted small">Cambia tu alias, nombre, apellido, correo y fecha de nacimiento.</div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        </a>
    </div>

    <div class="col-12">
        <a href="{{ route('configuracion.password.edit') }}" class="card text-decoration-none">
            <div class="card-body d-flex align-items-center gap-3">
                <span class="brand-badge" style="margin:0;width:48px;height:48px;border-radius:14px;font-size:1.2rem"><i class="bi bi-shield-lock"></i></span>
                <div class="flex-grow-1">
                    <div class="fw-display" style="color:var(--ink)">Cambiar contraseña</div>
                    <div class="text-muted small">Actualiza tu contraseña ingresando la actual por seguridad.</div>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
            </div>
        </a>
    </div>
</div>

<a href="{{ route('perfil.show') }}" class="pill-btn d-inline-flex align-items-center gap-1 mt-4">
    <i class="bi bi-arrow-left"></i> Volver al perfil
</a>
@endsection
