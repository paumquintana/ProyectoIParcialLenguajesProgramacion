<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ConfiguracionController extends Controller
{
    /** Abre la configuración en la primera sección (como un panel de ajustes). */
    public function index()
    {
        return redirect()->route('configuracion.perfil.edit');
    }

    /** Formulario para editar los datos del perfil. */
    public function editarPerfil(Request $request)
    {
        return view('configuracion.perfil', ['user' => $request->user()]);
    }

    /** Guarda los cambios del perfil. */
    public function actualizarPerfil(Request $request)
    {
        $user = $request->user();

        $datos = $request->validate([
            'alias'            => ['required', 'string', 'max:255', Rule::unique('users', 'alias')->ignore($user->id)],
            'nombre'           => ['required', 'string', 'max:255'],
            'apellido'         => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'email'            => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($datos);

        return redirect()->route('perfil.show')
            ->with('status', 'Tu perfil se actualizó correctamente.');
    }

    /** Formulario para cambiar la contraseña. */
    public function editarPassword()
    {
        return view('configuracion.password');
    }

    /** Cambia la contraseña (pide la actual por seguridad). */
    public function actualizarPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => $request->password,
        ]);

        return redirect()->route('configuracion.index')
            ->with('status', 'Tu contraseña se cambió correctamente.');
    }
}
