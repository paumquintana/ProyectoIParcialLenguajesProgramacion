<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'LecturaApp')</title>

    {{-- Bootstrap + iconos por CDN: no hace falta npm run dev --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Tipografía redondeada para el look cálido --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg:        #d9c9b3;   /* fondo exterior (taupe cálido) */
            --panel:     #faf5ec;   /* superficie principal (crema) */
            --panel-2:   #f3ecde;   /* superficie secundaria */
            --ink:       #4a4239;   /* texto principal */
            --muted:     #9c9385;   /* texto suave */
            --line:      #ece3d4;   /* bordes */
            --coral:     #ef5d60;   /* acento rojo coral (títulos / botones) */
            --coral-d:   #e0484b;
            --ribbon:    #f3a83b;   /* marcador naranja */
            --purple:    #8a7fd6;   /* acento morado */
            --green:     #6bbf8a;
            --blue:      #5aa9e6;
            --radius:    1.25rem;
            --radius-sm: .85rem;
            --shadow:    0 14px 30px -18px rgba(74,66,57,.45);
            --grad:      linear-gradient(90deg,#f6a740,#ef5d60,#e0568f,#8a7fd6,#5aa9e6);
        }

        * { -webkit-font-smoothing: antialiased; }

        body {
            background: var(--bg);
            color: var(--ink);
            font-family: 'Quicksand', system-ui, sans-serif;
            font-weight: 500;
        }

        h1,h2,h3,h4,h5,h6,.fw-display { font-family: 'Fraunces', Georgia, serif; }
        a { color: var(--coral); }
        a:hover { color: var(--coral-d); }

        /* ---------- Estructura general ---------- */
        .app-shell {
            display: flex;
            gap: 1rem;
            max-width: 1320px;
            margin: 0 auto;
            padding: 1rem;
            min-height: 100vh;
        }

        /* ---------- Barra lateral ---------- */
        .sidebar {
            flex: 0 0 84px;
            background: var(--panel);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.1rem .6rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .55rem;
            position: sticky;
            top: 1rem;
            height: calc(100vh - 2rem);
        }
        .sidebar .avatar {
            width: 46px; height: 46px; border-radius: 50%;
            background: var(--grad);
            display: grid; place-items: center;
            color: #fff; font-weight: 700; font-family: 'Fraunces', serif;
            margin-bottom: .4rem; text-decoration: none; flex: 0 0 auto;
        }
        .sidebar .nav-ico {
            width: 48px; height: 48px; border-radius: 16px;
            display: grid; place-items: center;
            color: var(--muted); font-size: 1.25rem;
            background: transparent; border: 0; text-decoration: none;
            transition: .18s ease;
        }
        .sidebar .nav-ico:hover { background: var(--panel-2); color: var(--ink); }
        .sidebar .nav-ico.active { background: var(--coral); color: #fff; box-shadow: 0 8px 16px -8px var(--coral); }
        .sidebar .spacer { flex: 1 1 auto; }
        .sidebar .subscribe {
            writing-mode: vertical-rl; transform: rotate(180deg);
            background: var(--purple); color: #fff;
            border-radius: 999px; padding: 1rem .55rem;
            font-weight: 600; letter-spacing: .04em; font-size: .8rem;
            border: 0;
        }

        /* ---------- Zona principal ---------- */
        .app-main { flex: 1 1 auto; min-width: 0; }
        .panel {
            background: var(--panel);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 1.4rem 1.6rem;
        }

        /* ---------- Barra superior ---------- */
        .topbar {
            display: flex; align-items: center; gap: 1rem;
            margin-bottom: 1.1rem;
        }
        .search-box {
            flex: 1 1 auto;
            display: flex; align-items: center;
            background: var(--panel);
            border-radius: 999px;
            box-shadow: var(--shadow);
            padding: .35rem .35rem .35rem 1.25rem;
        }
        .search-box i { color: var(--muted); font-size: 1.1rem; }
        .search-box input {
            border: 0; background: transparent; outline: none;
            flex: 1 1 auto; padding: .55rem .8rem; font-size: .95rem; color: var(--ink);
            font-family: 'Quicksand', sans-serif; font-weight: 500;
        }
        .btn-grad {
            background: var(--grad); color: #fff; border: 0;
            border-radius: 999px; padding: .6rem 1.6rem; font-weight: 600;
        }
        .btn-grad:hover { color: #fff; filter: brightness(1.05); }
        .topbar .user-chip {
            display: flex; align-items: center; gap: .6rem;
            background: var(--panel); border-radius: 999px;
            padding: .3rem .35rem; box-shadow: var(--shadow); text-decoration: none; color: var(--ink);
        }
        .topbar .user-chip .ava {
            width: 40px; height: 40px; border-radius: 50%; background: var(--grad);
            display: grid; place-items: center; color: #fff; font-weight: 700; font-family: 'Fraunces', serif;
        }

        /* ---------- Tarjetas / componentes ---------- */
        .card {
            border: 0; border-radius: var(--radius-sm);
            background: #fff; box-shadow: 0 10px 24px -18px rgba(74,66,57,.5);
        }
        .section-title { font-size: 1.4rem; margin-bottom: 0; }
        .pill-btn {
            background: var(--panel-2); border: 0; color: var(--ink);
            border-radius: 999px; padding: .45rem 1.1rem; font-weight: 600; text-decoration: none;
        }
        .pill-btn:hover { background: var(--line); color: var(--ink); }

        /* Menú lateral de la sección Configuración (estilo ajustes) */
        .settings-nav { display: flex; flex-direction: column; gap: .25rem; }
        .settings-link {
            display: flex; align-items: center; gap: .65rem;
            padding: .65rem .9rem; border-radius: 12px;
            color: var(--ink); text-decoration: none; font-weight: 600;
        }
        .settings-link i { font-size: 1.1rem; color: var(--muted); }
        .settings-link:hover { background: var(--panel-2); }
        .settings-link.active { background: var(--coral); color: #fff; }
        .settings-link.active i { color: #fff; }

        .btn-primary {
            --bs-btn-bg: var(--coral); --bs-btn-border-color: var(--coral);
            --bs-btn-hover-bg: var(--coral-d); --bs-btn-hover-border-color: var(--coral-d);
            --bs-btn-active-bg: var(--coral-d); --bs-btn-active-border-color: var(--coral-d);
            --bs-btn-focus-shadow-rgb: 239,93,96;
            border-radius: 999px; font-weight: 600;
        }
        .btn-outline-primary {
            --bs-btn-color: var(--coral); --bs-btn-border-color: var(--coral);
            --bs-btn-hover-bg: var(--coral); --bs-btn-hover-border-color: var(--coral);
            border-radius: 999px; font-weight: 600;
        }
        .btn-success { --bs-btn-bg: var(--green); --bs-btn-border-color: var(--green); border-radius:999px; font-weight:600; }
        .btn-sm { border-radius: 999px; }
        .form-control, .form-select { border-radius: var(--radius-sm); border-color: var(--line); }
        .form-control:focus, .form-select:focus { border-color: var(--purple); box-shadow: 0 0 0 .2rem rgba(138,127,214,.18); }

        /* Portada de libro + marcador estilo foto */
        .book {
            position: relative; display: block; border-radius: var(--radius-sm);
            overflow: hidden; aspect-ratio: 2/3; background: var(--panel-2);
            box-shadow: 0 12px 22px -14px rgba(74,66,57,.55);
        }
        .book img, .book .ph {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }
        .book .ph { display: grid; place-items: center; color: var(--muted); font-size: 2rem; }
        .book .ph-cover {
            width: 100%; height: 100%; display: flex; flex-direction: column;
            justify-content: center; gap: .25rem; padding: .7rem; text-align: center;
        }
        .ph-cover-title {
            font-family: 'Fraunces', serif; color: #fff; font-weight: 600;
            font-size: .9rem; line-height: 1.15;
            display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;
        }
        .ph-cover-author { color: rgba(255,255,255,.82); font-size: .72rem; font-weight: 500; }
        .book::after {
            content: ""; position: absolute; top: -2px; right: 16px;
            width: 18px; height: 30px; background: var(--ribbon);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 78%, 0 100%);
        }
        .card-portada { width: 100%; aspect-ratio: 2/3; object-fit: cover; background: var(--panel-2); }
        .cover { width: 100%; aspect-ratio: 2/3; object-fit: cover; background: var(--panel-2); }
        .cover-ph { display: grid; place-items: center; color: var(--muted); font-size: 1.8rem; }

        .book-title { font-family: 'Fraunces', serif; color: var(--coral); font-weight: 600; }

        .chip-cat {
            display: flex; flex-direction: column; align-items: center; gap: .35rem;
            text-decoration: none; color: var(--muted); font-weight: 600; font-size: .78rem;
            min-width: 64px;
        }
        .chip-cat .ico {
            width: 52px; height: 52px; border-radius: 16px; display: grid; place-items: center;
            font-size: 1.3rem; color: #fff;
        }
        .chip-cat.active { color: var(--coral); }
        .chip-cat.active .ico { outline: 3px solid rgba(239,93,96,.25); }

        .progress { background: var(--panel-2); border-radius: 999px; }
        .progress-bar { background: var(--coral); }
        .badge.text-bg-secondary { background: var(--purple) !important; }
        .alert { border-radius: var(--radius-sm); border: 0; }

        .promo {
            background: linear-gradient(120deg,#f3ecde,#faf5ec);
            border-radius: var(--radius); padding: 1.4rem 1.6rem;
        }

        /* ---------- Pantallas de invitado (login/registro) ---------- */
        .guest-wrap { min-height: 100vh; display: grid; place-items: center; padding: 1.5rem; }
        .guest-card { background: var(--panel); border-radius: var(--radius); box-shadow: var(--shadow); }
        .brand-badge {
            width: 64px; height: 64px; border-radius: 20px; background: var(--grad);
            display: grid; place-items: center; color: #fff; font-size: 1.8rem; margin: 0 auto;
        }

        @media (max-width: 768px) {
            .app-shell { flex-direction: column; }
            .sidebar {
                flex-direction: row; height: auto; position: static; width: 100%;
                overflow-x: auto; gap: .35rem;
            }
            .sidebar .subscribe { display: none; }
            .sidebar .spacer { display: none; }
        }
    </style>
</head>
<body>
@auth
    @php $route = Route::currentRouteName(); @endphp
    <div class="app-shell">
        {{-- ---------- Barra lateral de iconos ---------- --}}
        <aside class="sidebar">
            <a href="{{ route('perfil.show') }}" class="avatar" title="Mi perfil">
                {{ mb_strtoupper(mb_substr(auth()->user()->nombre ?? auth()->user()->alias, 0, 1)) }}
            </a>

            <a href="{{ route('dashboard') }}" class="nav-ico {{ $route === 'dashboard' ? 'active' : '' }}" title="Inicio"><i class="bi bi-house-door"></i></a>
            <a href="{{ route('libros.index') }}" class="nav-ico {{ str_starts_with($route ?? '', 'libros') ? 'active' : '' }}" title="Catálogo"><i class="bi bi-grid"></i></a>
            <a href="{{ route('biblioteca.index') }}" class="nav-ico {{ $route === 'biblioteca.index' ? 'active' : '' }}" title="Mi biblioteca"><i class="bi bi-bookmark-heart"></i></a>
            <a href="{{ route('grupos.index') }}" class="nav-ico {{ str_starts_with($route ?? '', 'grupos') || str_starts_with($route ?? '', 'posts') ? 'active' : '' }}" title="Grupos de lectura"><i class="bi bi-chat-square-text"></i></a>

            <div class="spacer"></div>

            <a href="{{ route('configuracion.index') }}" class="nav-ico {{ str_starts_with($route ?? '', 'configuracion') ? 'active' : '' }}" title="Configuración"><i class="bi bi-gear"></i></a>
            <form method="POST" action="{{ route('logout') }}" class="d-grid">
                @csrf
                <button type="submit" class="nav-ico" title="Cerrar sesión"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </aside>

        {{-- ---------- Zona principal ---------- --}}
        <div class="app-main">
            <header class="topbar">
                <form class="search-box" method="GET" action="{{ route('libros.index') }}" role="search">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar libros o autores...">
                    <button class="btn-grad" type="submit">Buscar</button>
                </form>

                <div class="dropdown">
                    <a href="#" class="user-chip dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                        <span class="ava">{{ mb_strtoupper(mb_substr(auth()->user()->nombre ?? auth()->user()->alias, 0, 1)) }}</span>
                        <span class="d-none d-md-block pe-2">
                            <span class="d-block fw-semibold" style="line-height:1.1">{{ auth()->user()->alias }}</span>
                            <span class="d-block text-muted" style="font-size:.75rem">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:1rem">
                        <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->nombre }} {{ auth()->user()->apellido }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('perfil.show') }}"><i class="bi bi-person me-1"></i> Mi perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('configuracion.index') }}"><i class="bi bi-gear me-1"></i> Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit"><i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="panel">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Revisa los datos:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('contenido')
            </div>
        </div>
    </div>
@else
    {{-- ---------- Layout para invitados ---------- --}}
    <div class="guest-wrap">
        @if (session('status'))
            <div class="alert alert-success w-100" style="max-width:480px" role="alert">{{ session('status') }}</div>
        @endif
        @yield('contenido')
    </div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
