<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function showLoginForm(): Response
    {
        return Inertia::render('Auth/Signin', [
            'title' => 'Iniciar sesión',
            'loginUrl' => route('login'),
            'dashboardUrl' => route('dashboard'),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'usuario' => ['required', 'string', 'max:50'],
            'clave'   => ['required', 'string'],
        ], [
            'usuario.required' => 'El usuario es obligatorio.',
            'clave.required'  => 'La contraseña es obligatoria.',
        ]);

        $user = Usuario::where('usuario', $request->input('usuario'))->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'usuario' => [__('Usuario y/o contraseña incorrectos.')],
            ]);
        }

        if ($user->estado === 'Inactivo') {
            throw ValidationException::withMessages([
                'usuario' => [__('Su cuenta no está activa. Contacte al administrador.')],
            ]);
        }

        $clave = $request->input('clave');
        $valid = false;

        if ($user->hasLegacyPassword()) {
            $valid = md5($clave) === $user->getAuthPassword();
        } else {
            $valid = Hash::check($clave, $user->getAuthPassword());
        }

        if (!$valid) {
            throw ValidationException::withMessages([
                'usuario' => [__('Usuario y/o contraseña incorrectos.')],
            ]);
        }

        Auth::login($user, false);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
