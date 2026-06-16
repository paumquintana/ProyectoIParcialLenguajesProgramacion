<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /** Muestra el formulario de login. */
    public function create()
    {
        return view('auth.login');
    }

    /** Procesa el login. Acepta ALIAS o CORREO en el campo "login". */
    public function store(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Si lo escrito es un correo válido buscamos por email, si no por alias.
        $campo = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'alias';

        $credenciales = [
            $campo     => $request->login,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credenciales, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /** Cierra la sesión. */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
