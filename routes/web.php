<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataMenuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiDetailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/kategori', KategoriController::class);
    Route::resource('/DataMenu', DataMenuController::class);
    Route::get('/laporan', [TransaksiDetailController::class, 'index'])->name('laporan.index');

    Route::get('/transaksi', [PosController::class, 'index'])
        ->name('pos.index');

    Route::post('/transaksi/tambah', [PosController::class, 'add'])
        ->name('pos.add');

    Route::put('/transaksi/update/{id}', [PosController::class, 'update'])
        ->name('pos.update');

    Route::delete('/transaksi/hapus/{id}', [PosController::class, 'remove'])
        ->name('pos.remove');

    Route::post('/transaksi/checkout', [PosController::class, 'checkout'])
        ->name('pos.checkout');

    Route::get('/reports', [ReportsController::class, 'index'])
        ->name('reports.index');

    Route::get('/reports/{id}', [ReportsController::class, 'show'])
        ->name('reports.show');

    Route::get('/reports/{id}/pdf', [ReportsController::class, 'pdf'])
        ->name('reports.pdf');

});

require __DIR__.'/auth.php';
