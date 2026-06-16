<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BibliotecaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReseniaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Página de inicio
|--------------------------------------------------------------------------
| Si ya inició sesión va al dashboard; si no, al login.
*/
Route::get('/', fn () => Auth::check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Rutas para invitados (no autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Registro
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Reseteo de contraseña
    Route::get('forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'email'])->name('password.email');
    Route::get('reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren sesión iniciada)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil del usuario (solo lectura)
    Route::get('perfil', [ProfileController::class, 'show'])->name('perfil.show');

    // Configuración: editar perfil y cambiar contraseña en páginas separadas
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::get('configuracion/perfil', [ConfiguracionController::class, 'editarPerfil'])->name('configuracion.perfil.edit');
    Route::patch('configuracion/perfil', [ConfiguracionController::class, 'actualizarPerfil'])->name('configuracion.perfil.update');
    Route::get('configuracion/password', [ConfiguracionController::class, 'editarPassword'])->name('configuracion.password.edit');
    Route::put('configuracion/password', [ConfiguracionController::class, 'actualizarPassword'])->name('configuracion.password.update');

    // Catálogo y detalle de libros
    Route::get('catalogo', [LibroController::class, 'index'])->name('libros.index');
    // Agregar un libro nuevo (debe ir ANTES de libros/{libro} para no confundir "crear" con un id).
    Route::get('libros/crear', [LibroController::class, 'create'])->name('libros.create');
    Route::post('libros', [LibroController::class, 'store'])->name('libros.store');
    Route::get('libros/{libro}', [LibroController::class, 'show'])->name('libros.show');

    // Mi biblioteca
    Route::get('biblioteca', [BibliotecaController::class, 'index'])->name('biblioteca.index');
    Route::post('biblioteca', [BibliotecaController::class, 'store'])->name('biblioteca.store');
    Route::patch('biblioteca/{lectura}/progreso', [BibliotecaController::class, 'updateProgress'])->name('biblioteca.progreso');

    // Reseñas
    Route::post('resenias', [ReseniaController::class, 'store'])->name('resenias.store');

    // Grupos de lectura y foro
    Route::get('grupos', [GrupoController::class, 'index'])->name('grupos.index');
    Route::get('grupos/{grupo}', [GrupoController::class, 'show'])->name('grupos.show');
    Route::post('grupos/{grupo}/unirse', [GrupoController::class, 'join'])->name('grupos.join');
    Route::post('grupos/{grupo}/posts', [PostController::class, 'store'])->name('posts.store');
});
