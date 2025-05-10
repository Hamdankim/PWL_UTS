<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    WelcomeController,
    KategoriModelController,
    AlatModelController,
    StokModelController,
    TransaksiModelController,
    AuthController,
    ProfileController
};
use App\Models\AlatModel;
use App\Models\KategoriModel;

// Jika ada parameter {id}, maka nilainya harus berupa angka
Route::pattern('id', '[0-9]+');

// Route login
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);

// Route logout (harus sudah login/authenticated)
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

// Semua route dalam grup ini hanya bisa diakses jika sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:admin'])->group(function () {
        Route::get('/kategori', [KategoriModelController::class, 'index']);
        Route::post('/kategori/list', [KategoriModelController::class, 'list']);
        Route::get('/kategori/create_ajax', [KategoriModelController::class, 'create_ajax']);
        Route::post('/kategori/ajax', [KategoriModelController::class, 'store_ajax']);
        Route::get('/kategori/{id}/show_ajax', [KategoriModelController::class, 'show_ajax']);
        Route::get('/kategori/{id}/edit_ajax', [KategoriModelController::class, 'edit_ajax']);
        Route::put('/kategori/{id}/update_ajax', [KategoriModelController::class, 'update_ajax']);
        Route::get('/kategori/{id}/delete_ajax', [KategoriModelController::class, 'confirm_ajax']);
        Route::delete('/kategori/{id}/delete_ajax', [KategoriModelController::class, 'delete_ajax']);
        Route::get('/kategori/import', [KategoriModelController::class, 'import']);
        Route::post('/kategori/import_ajax', [KategoriModelController::class, 'import_ajax']);
        Route::get('/kategori/export_excel', [KategoriModelController::class, 'export_excel']);
    });

    Route::middleware(['authorize:admin'])->group(function () {
        Route::get('/transaksi', [TransaksiModelController::class, 'index']);
        Route::post('/transaksi/list', [TransaksiModelController::class, 'list']);
        Route::get('/transaksi/create_ajax', [TransaksiModelController::class, 'create_ajax']);
        Route::post('/transaksi/store_ajax', [TransaksiModelController::class, 'store_ajax'])->name('transaksi.store_ajax');
        Route::get('/transaksi/{id}/show_ajax', [TransaksiModelController::class, 'show_ajax']);
        Route::get('/transaksi/{id}/edit_ajax', [TransaksiModelController::class, 'edit_ajax']);
        Route::put('/transaksi/{id}/update_ajax', [TransaksiModelController::class, 'update_ajax']);
        Route::get('/transaksi/{id}/delete_ajax', [TransaksiModelController::class, 'confirm_ajax']);
        Route::delete('/transaksi/{id}/delete_ajax', [TransaksiModelController::class, 'delete_ajax']);
        Route::get('/transaksi/{id}/detail', [TransaksiModelController::class, 'showDetail']);
        Route::get('/transaksi/{id}/detail/list', [TransaksiModelController::class, 'listDetail']);
    });

    Route::middleware(['authorize:admin,user'])->group(function () {
        Route::get('/alat', [AlatModelController::class, 'index']);
        Route::post('/alat/list', [AlatModelController::class, 'list']);
        Route::get('/alat/create_ajax', [AlatModelController::class, 'create_ajax']);
        Route::post('/alat/ajax', [AlatModelController::class, 'store_ajax']);
        Route::get('/alat/{id}/show_ajax', [AlatModelController::class, 'show_ajax']);
        Route::get('/alat/{id}/edit_ajax', [AlatModelController::class, 'edit_ajax']);
        Route::put('/alat/{id}/update_ajax', [AlatModelController::class, 'update_ajax']);
        Route::get('/alat/{id}/delete_ajax', [AlatModelController::class, 'confirm_ajax']);
        Route::delete('/alat/{id}/delete_ajax', [AlatModelController::class, 'delete_ajax']);
        Route::get('/alat/import', [AlatModelController::class, 'import']);
        Route::post('/alat/import_ajax', [AlatModelController::class, 'import_ajax']);
        Route::get('/alat/export_excel', [AlatModelController::class, 'export_excel']);
        Route::get('/alat/export_pdf', [AlatModelController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:admin,user'])->group(function () {
        Route::get('/stok', [StokModelController::class, 'index']);
        Route::post('/stok/list', [StokModelController::class, 'list']);
        Route::get('/stok/create_ajax', [StokModelController::class, 'create_ajax']);
        Route::post('/stok/ajax', [StokModelController::class, 'store_ajax']);
        Route::get('/stok/{id}/show_ajax', [StokModelController::class, 'show_ajax']);
        Route::get('/stok/{id}/edit_ajax', [StokModelController::class, 'edit_ajax']);
        Route::put('/stok/{id}/update_ajax', [StokModelController::class, 'update_ajax']);
        Route::get('/stok/{id}/delete_ajax', [StokModelController::class, 'confirm_ajax']);
        Route::delete('/stok/{id}/delete_ajax', [StokModelController::class, 'delete_ajax']);
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/delete-foto', [ProfileController::class, 'deleteFoto'])->name('profile.delete-foto');
    });
});