<?php

use App\Http\Controllers\KategoriMainanController;
use App\Http\Controllers\MainanController;
use App\Http\Controllers\ProfileController;
use App\Models\KategoriMainan;
use App\Models\Mainan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $mainan = Mainan::with('kategori')->get();
    $kategori = KategoriMainan::all();
    return view('welcome', compact('mainan', 'kategori'));
})->name('welcome');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {
    Route::group(['prefix' => 'mainan'], function () {
        Route::get('/', [MainanController::class, 'index'])->name('mainan.index');
        Route::post('/store', [MainanController::class, 'store'])->name('mainan.store');
        Route::put('/update', [MainanController::class, 'update'])->name('mainan.update');
        Route::delete('/destroy', [MainanController::class, 'destroy'])->name('mainan.destroy');
    });
    Route::group(['prefix' => 'kategori'], function () {
        Route::get('/', [KategoriMainanController::class, 'index'])->name('kategori.index');
        Route::post('/store', [KategoriMainanController::class, 'store'])->name('kategori.store');
        Route::put('/update', [KategoriMainanController::class, 'update'])->name('kategori.update');
        Route::delete('/destroy', [KategoriMainanController::class, 'destroy'])->name('kategori.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
