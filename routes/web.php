<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AlokasiController;

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
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Akun
    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::get('/akun/edit', [AkunController::class, 'edit'])->name('akun.edit');
    Route::post('/akun/update', [AkunController::class, 'update'])->name('akun.update');


    // ==============================
    //        FITUR ALOKASI
    // ==============================

    // Halaman alokasi
    Route::get('/alokasi', [AlokasiController::class, 'index'])->name('alokasi.index');

    // Simpan plan pembagian gaji
    Route::post('/alokasi/plan', [AlokasiController::class, 'storePlan'])->name('alokasi.storePlan');

    // Simpan GAJI PER BULAN
    Route::post('/alokasi/salary', [AlokasiController::class, 'storeSalary'])->name('alokasi.storeSalary');

    // CRUD Transaksi
    Route::post('/alokasi/transaction', [AlokasiController::class, 'storeTransaction'])->name('alokasi.storeTransaction');
    Route::get('/alokasi/{id}/edit', [AlokasiController::class, 'edit'])->name('alokasi.edit');
    Route::post('/alokasi/{id}', [AlokasiController::class, 'update'])->name('alokasi.update');
    Route::delete('/alokasi/{id}', [AlokasiController::class, 'destroy'])->name('alokasi.destroy');

    // CRUD PLAN (EDIT / DELETE KHUSUS PLAN)
    Route::get('/plan/{id}/edit', [AlokasiController::class, 'editPlan'])->name('plan.edit');
    Route::put('/plan/{id}', [AlokasiController::class, 'updatePlan'])->name('plan.update');
    Route::delete('/plan/{id}', [AlokasiController::class, 'destroyPlan'])->name('plan.destroy');

});
