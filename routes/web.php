<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AlokasiController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\DanaDaruratController; // kamu lupa import ini

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Semua halaman setelah login
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Akun
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::get('/akun/edit', [AkunController::class, 'edit'])->name('akun.edit');
    Route::post('/akun/update', [AkunController::class, 'update'])->name('akun.update');

    // ==============================
    //            ALOKASI
    // ==============================
    Route::get('/alokasi', [AlokasiController::class, 'index'])->name('alokasi.index');
    Route::post('/alokasi/plan', [AlokasiController::class, 'storePlan'])->name('alokasi.plan.store');

    // ==============================
    //       CATATAN TRANSAKSI
    // ==============================
    Route::get('/catatan', [CatatanController::class, 'index'])->name('catatan.index');
    Route::get('/catatan/create', [CatatanController::class, 'create'])->name('catatan.create');
    Route::post('/catatan/store', [CatatanController::class, 'store'])->name('catatan.store');

    Route::put('/catatan/{id}', [CatatanController::class, 'update'])->name('catatan.update');
    Route::delete('/catatan/{id}', [CatatanController::class, 'destroy'])->name('catatan.destroy');


    // ==============================
    //         DANA DARURAT
    // ==============================
    Route::post('/dana/store', [DanaDaruratController::class, 'store'])->name('dana.store');

});
