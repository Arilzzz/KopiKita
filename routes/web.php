<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMenuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::resource('/kategori', KategoriController::class);
    Route::resource('/DataMenu', DataMenuController::class);

    // POS / Transaksi Kasir
    Route::get('/transaksi', [PosController::class, 'index'])->name('pos.index');
    Route::post('/transaksi/tambah', [PosController::class, 'add'])->name('pos.add');
    Route::put('/transaksi/update/{id}', [PosController::class, 'update'])->name('pos.update');
    Route::delete('/transaksi/hapus/{id}', [PosController::class, 'remove'])->name('pos.remove');
    Route::post('/transaksi/clear', [PosController::class, 'clear'])->name('pos.clear');
    Route::post('/transaksi/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

    // Laporan & Detail Struk
    Route::get('/laporan', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export-pdf', [ReportsController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/laporan/{id}', [ReportsController::class, 'show'])->name('reports.show');
    Route::get('/laporan/{id}/pdf', [ReportsController::class, 'pdf'])->name('reports.pdf');

    // Alias routes untuk kompatibilitas nama & URL
    Route::get('/reports', [ReportsController::class, 'index']);
    Route::get('/reports/export-pdf', [ReportsController::class, 'exportPdf']);
    Route::get('/reports/{id}', [ReportsController::class, 'show']);
    Route::get('/reports/{id}/pdf', [ReportsController::class, 'pdf']);
});

// Alias route name untuk laporan.index jika ada view yang menggunakannya
Route::get('/laporan-alias', fn () => redirect()->route('reports.index'))->name('laporan.index');

require __DIR__.'/auth.php';
