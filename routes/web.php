<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationController;

// Jadikan halaman dokumentasi sebagai halaman utama
Route::get('/', [DocumentationController::class, 'show'])->name('home');

// Rute untuk kategori dokumentasi spesifik (publik)
Route::get('/docs/{category?}/{page?}', [DocumentationController::class, 'show'])->name('docs');

// Rute untuk otentikasi (login & logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// routes/web.php
use App\Http\Controllers\NavMenuController;
use App\Http\Controllers\DocsController; // Pastikan DocsController sudah di-import

// ... (routes yang sudah ada, misalnya route untuk home)

// Kamu bisa sesuaikan path 'navigasi' sesuai keinginanmu
Route::resource('navigasi', NavmenuController::class)->except(['show']);

