<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Jadikan halaman dokumentasi sebagai halaman utama
Route::get('/', [DocumentationController::class, 'show'])->name('home');

// Rute untuk kategori dokumentasi spesifik (publik)
Route::get('/docs/{category?}/{page?}', [DocumentationController::class, 'show'])->name('docs');

// Rute untuk otentikasi (login & logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
