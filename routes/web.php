<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\NavmenuController;

// Jadikan halaman dokumentasi sebagai halaman utama
Route::get('/', [DocumentationController::class, 'show'])->name('home');

// Rute untuk kategori dokumentasi spesifik (publik)
Route::get('/docs/{category?}/{page?}', [DocumentationController::class, 'show'])->name('docs');

// Rute untuk otentikasi (login & logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// --- API Routes for AJAX CRUD ---
Route::prefix('api/navigasi')->group(function () {
    // Mengambil semua menu untuk sidebar (untuk refresh)
    Route::get('/all', [NavmenuController::class, 'getAllMenusForSidebar'])->name('api.navigasi.all');
    // Mengambil daftar parent menu untuk dropdown
    Route::get('/parents', [NavmenuController::class, 'getParentMenus'])->name('api.navigasi.parents');
    // Mengambil data satu menu untuk edit
    Route::get('/{navMenu}', [NavmenuController::class, 'getMenuData'])->name('api.navigasi.get');

    // CRUD Operations (menggunakan POST dengan _method untuk PUT/DELETE)
    Route::post('/', [NavMenuController::class, 'store'])->name('api.navigasi.store');
    Route::post('/{navMenu}', [NavMenuController::class, 'update'])->name('api.navigasi.update'); // Menggunakan POST untuk PUT
    Route::delete('/{navMenu}', [NavMenuController::class, 'destroy'])->name('api.navigasi.destroy'); // Rute DELETE eksplisit
    
    // Rute khusus untuk update konten CKEditor
    Route::put('/{navMenu}/content', [NavMenuController::class, 'updateMenuContent'])->name('api.navigasi.content.update');
});



