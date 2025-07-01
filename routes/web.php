<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationController;

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');

// Rute untuk memproses data login
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

// Rute untuk logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Tambahkan rute baru ini
Route::get('/docs/{category?}', [DocumentationController::class, 'show'])
    ->middleware('auth')
    ->name('docs');

// Anda juga bisa menambahkan redirect agar /login mengarah ke halaman dokumentasi
// setelah berhasil login, dengan mengaturnya di LoginController.
// Atau, buat redirect sederhana dari /dashboard ke /docs
Route::get('/dashboard', function() {
    return redirect()->route('docs', ['category' => 'epesantren']);
})->middleware('auth');