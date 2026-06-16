<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /** Muestra el formulario de registro. */
    public function create()
    {
        return view('auth.register');
    }

    /** Crea la cuenta nueva y deja al usuario logueado. */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'alias'            => ['required', 'string', 'max:255', 'unique:users,alias'],
            'nombre'           => ['required', 'string', 'max:255'],
            'apellido'         => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'alias'            => $datos['alias'],
            'nombre'           => $datos['nombre'],
            'apellido'         => $datos['apellido'] ?? null,
            'fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
            'email'            => $datos['email'],
            'password'         => Hash::make($datos['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('status', '¡Bienvenido/a, ' . $user->nombre . '! Tu cuenta fue creada.');
    }
}
