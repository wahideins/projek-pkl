<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');

// Rute untuk memproses data login
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

// Rute untuk logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Contoh rute dashboard yang hanya bisa diakses setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');