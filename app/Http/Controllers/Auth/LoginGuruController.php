<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginGuruController extends Controller
{
    /**
     * Menampilkan formulir login guru/admin berbasis web.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check() && in_array(Auth::user()->role, ['guru', 'admin'])) {
            return redirect()->route('guru.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi sesi masuk guru/admin.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Format email tidak valid!',
            'password.required' => 'Kata sandi wajib diisi!',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            $user = Auth::user();

            if (in_array($user->role, ['guru', 'admin'])) {
                $request->session()->regenerate();

                return redirect()->intended(route('guru.dashboard'));
            }

            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun Anda tidak memiliki hak akses sebagai Guru atau Admin!',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok!',
        ])->onlyInput('email');
    }

    /**
     * Logout sesi guru dari website.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
