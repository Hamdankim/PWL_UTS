<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    WelcomeController,
    KategoriModelController,
    AlatModelController,
    StokModelController,
    TransaksiModelController
};

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriModelController::class, 'index']);
    Route::post('/list', [KategoriModelController::class, 'list']);
    Route::get('/create_ajax', [KategoriModelController::class, 'create_ajax']);
    Route::post('/ajax', [KategoriModelController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [KategoriModelController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [KategoriModelController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [KategoriModelController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [KategoriModelController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [KategoriModelController::class, 'delete_ajax']);
});

Route::group(['prefix' => 'transaksi'], function () {
    Route::get('/', [TransaksiModelController::class, 'index']);
    Route::post('/list', [TransaksiModelController::class, 'list']);
    Route::get('/create_ajax', [TransaksiModelController::class, 'create_ajax']);
    Route::post('/store_ajax', [TransaksiModelController::class, 'store_ajax'])->name('transaksi.store_ajax');
    Route::get('/{id}/show_ajax', [TransaksiModelController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [TransaksiModelController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [TransaksiModelController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [TransaksiModelController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [TransaksiModelController::class, 'delete_ajax']);
    Route::get('/{id}/detail', [TransaksiModelController::class, 'showDetail']);
    Route::get('/{id}/detail/list', [TransaksiModelController::class, 'listDetail']);
});

Route::group(['prefix' => 'alat'], function () {
    Route::get('/', [AlatModelController::class, 'index']);
    Route::post('/list', [AlatModelController::class, 'list']);
    Route::get('/create_ajax', [AlatModelController::class, 'create_ajax']);
    Route::post('/ajax', [AlatModelController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [AlatModelController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [AlatModelController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [AlatModelController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [AlatModelController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [AlatModelController::class, 'delete_ajax']);
});

Route::group(['prefix' => 'stok'], function () {
    Route::get('/', [StokModelController::class, 'index']);
    Route::post('/list', [StokModelController::class, 'list']);
    Route::get('/create_ajax', [StokModelController::class, 'create_ajax']);
    Route::post('/ajax', [StokModelController::class, 'store_ajax']);
    Route::get('/{id}/show_ajax', [StokModelController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [StokModelController::class, 'edit_ajax']);
    Route::put('/{id}/update_ajax', [StokModelController::class, 'update_ajax']);
    Route::get('/{id}/delete_ajax', [StokModelController::class, 'confirm_ajax']);
    Route::delete('/{id}/delete_ajax', [StokModelController::class, 'delete_ajax']);
});
