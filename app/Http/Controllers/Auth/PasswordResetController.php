<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    /** Formulario "olvidé mi contraseña" (pide el correo). */
    public function request()
    {
        return view('auth.forgot-password');
    }

    /**
     * Genera el token de reseteo y, como NO hay servidor de correo,
     * muestra el enlace en pantalla para poder probarlo.
     * El token se guarda en 'password_reset_tokens' y expira en 60 min.
     */
    public function email(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            // No revelamos si el correo existe o no (buena práctica).
            return back()->with('status', 'Si el correo existe, se generó un enlace de reseteo.');
        }

        // createToken() guarda el token (hasheado) en la tabla y devuelve el token plano.
        $token = Password::broker()->createToken($user);

        $enlace = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        // Mostramos el enlace en pantalla (en producción iría por correo).
        return back()->with('reset_link', $enlace);
    }

    /** Formulario para escribir la nueva contraseña. */
    public function reset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /** Guarda la nueva contraseña validando el token. */
    public function update(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', 'Contraseña actualizada. Ya puedes iniciar sesión.')
            : back()->withErrors(['email' => __($status)]);
    }
}
