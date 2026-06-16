{{-- Menú lateral de la sección Configuración (estilo ajustes tipo Chrome). --}}
<nav class="settings-nav">
    <a href="{{ route('configuracion.perfil.edit') }}"
       class="settings-link {{ request()->routeIs('configuracion.perfil.*') ? 'active' : '' }}">
        <i class="bi bi-person"></i> Editar perfil
    </a>
    <a href="{{ route('configuracion.password.edit') }}"
       class="settings-link {{ request()->routeIs('configuracion.password.*') ? 'active' : '' }}">
        <i class="bi bi-shield-lock"></i> Contraseña
    </a>
    <hr style="border-color:var(--line)">
    <a href="{{ route('perfil.show') }}" class="settings-link">
        <i class="bi bi-arrow-left"></i> Volver al perfil
    </a>
</nav>
