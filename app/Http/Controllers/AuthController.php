<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // ======================
    // LOGIN
    // ======================
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ✅ VALIDASI
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // ❌ EMAIL TIDAK TERDAFTAR
        if (!User::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email belum terdaftar.');
        }

        // ❌ PASSWORD SALAH
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->with('error', 'Password yang kamu masukkan salah.');
        }

        // ✅ LOGIN BERHASIL
        return redirect()->route('dashboard')
            ->with('success', 'Login berhasil. Selamat datang!');
    }

    // ======================
    // REGISTER
    // ======================
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // ✅ VALIDASI REGISTER
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // ✅ REGISTER BERHASIL
        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}
