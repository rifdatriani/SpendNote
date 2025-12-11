<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkunController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('akun.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('akun.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('akun.index')->with('success', 'Profil berhasil diperbarui');
    }
}
