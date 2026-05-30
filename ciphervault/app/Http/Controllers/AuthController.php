<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login untuk pengguna.
     * Lokasi fitur: Tampilan form login (auth/login.blade.php)
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Menampilkan halaman registrasi akun baru.
     * Lokasi fitur: Tampilan form pendaftaran (auth/register.blade.php)
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Menangani proses registrasi/pendaftaran akun baru.
     * Menyimpan data user baru dan melakukan login otomatis.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('dashboard');
    }

    /**
     * Menangani proses autentikasi/login pengguna.
     * Memvalidasi kredensial dan mencatat aktivitas login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Menangani proses logout pengguna dari sistem.
     * Menghapus sesi dan mencatat aktivitas logout.
     */
    public function logout(Request $request)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'logout',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
