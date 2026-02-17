<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PengaturanTokoController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('produk', ProdukController::class);
    // Hapus baris: Route::resource('pengaturan_toko', PengaturanTokoController::class);

// Ganti dengan ini:
Route::prefix('pengaturan_toko')->name('pengaturan_toko.')->group(function () {
    Route::get('/', [PengaturanTokoController::class, 'index'])->name('index');
    Route::put('/{id}', [PengaturanTokoController::class, 'update'])->name('update');
});
    Route::resource('buat_struk', StrukController::class);
    Route::resource('dashboard', DashboardController::class);
});

require __DIR__.'/auth.php';
