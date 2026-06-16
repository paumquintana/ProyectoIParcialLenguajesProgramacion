@extends('layouts.app')
@section('titulo', 'Configuración · Editar perfil')

@section('contenido')
<h1 class="section-title mb-4"><i class="bi bi-gear"></i> Configuración</h1>

<div class="row g-4">
    <div class="col-md-4 col-lg-3">
        @include('configuracion._nav')
    </div>

    <div class="col-md-8 col-lg-9">
        <div class="card">
            <div class="card-body">
                <h2 class="section-title mb-1" style="font-size:1.2rem">Editar perfil</h2>
                <p class="text-muted small mb-4">Actualiza la información de tu cuenta.</p>

                <form method="POST" action="{{ route('configuracion.perfil.update') }}" style="max-width:560px">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label small">Alias *</label>
                        <input type="text" name="alias" value="{{ old('alias', $user->alias) }}"
                               class="form-control @error('alias') is-invalid @enderror" required>
                        @error('alias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label small">Nombre *</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}"
                                   class="form-control @error('nombre') is-invalid @enderror" required>
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col mb-3">
                            <label class="form-label small">Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido', $user->apellido) }}" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Correo *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control @error('email') is-invalid @enderror" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label small">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->format('Y-m-d')) }}"
                               class="form-control" style="max-width:240px">
                    </div>
                    <button class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
