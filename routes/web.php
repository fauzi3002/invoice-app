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

    Route::get('/struk/{id}/export-excel', [StrukController::class, 'exportExcel'])
    ->name('struk.export.excel');
    Route::resource('produk', ProdukController::class);
    Route::resource('pengaturan_toko', PengaturanTokoController::class);
    Route::post('/pengaturan_toko', [PengaturanTokoController::class, 'update'])->name('pengaturan_toko.update');
    Route::resource('buat_struk', StrukController::class);
    Route::resource('dashboard', DashboardController::class);
});

require __DIR__.'/auth.php';
