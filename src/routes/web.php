<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\GuestbookController;

// 🚪 PORTAL AUTHENTICATION (WEB PUBLIK)
Route::get('/', [PenggunaController::class, 'showAuthForm'])->name('login');
Route::post('/register-manual', [PenggunaController::class, 'registerManual'])->name('register.manual');
Route::post('/login-manual', [PenggunaController::class, 'loginManual'])->name('login.manual');

Route::get('auth/google', [PenggunaController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [PenggunaController::class, 'handleGoogleCallback']);

// 🔐 RUANG KERJA OWNER/TENANT (WAJIB LOGIN)
Route::middleware(['auth:pengguna'])->group(function () {
    Route::get('/pilih-tema', [GuestbookController::class, 'showThemePicker'])->name('pengguna.pilih_tema');
    Route::post('/pilih-tema/save', [GuestbookController::class, 'saveInitialTheme'])->name('pengguna.pilih_tema.save');

    Route::get('/dashboard', [GuestbookController::class, 'index'])->name('pengguna.dashboard');
    Route::post('/dashboard/settings', [GuestbookController::class, 'updateSettings'])->name('pengguna.settings.update');
    Route::get('/dashboard/export', [GuestbookController::class, 'exportGuests'])->name('pengguna.guests.export');

    Route::post('/logout', [PenggunaController::class, 'logout'])->name('pengguna.logout');
});

// 🌍 JALUR WEBPAGE PUBLIK (Kiosk / Meja Tamu - Tanpa Auth Pengguna)
// Kita gunakan ID owner atau Slug biar data tamu masuk ke owner yang tepat!
Route::get('/kiosk/{id}', [GuestbookController::class, 'showKiosk'])->name('pengguna.kiosk');
Route::post('/kiosk/{id}/submit', [GuestbookController::class, 'submitKiosk'])->name('pengguna.kiosk.submit');
