<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\NavmenuController;

// Halaman utama dan dokumentasi
Route::get('/', [DocumentationController::class, 'show'])->name('home');
Route::get('/docs/{category?}/{page?}', [DocumentationController::class, 'show'])->name('docs');

// Upload gambar untuk CKEditor
Route::post('/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

// Konten Dokumentasi
// routes/web.php
Route::post('/docs/save/{menu_id}', [DocumentationController::class, 'saveContent'])->name('docs.save');
Route::delete('/docs/delete/{menu_id}', [DocumentationController::class, 'deleteContent'])->name('docs.delete');


// Otentikasi
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// --- API Routes untuk CRUD Navigasi via AJAX ---
Route::middleware('auth')->prefix('api/navigasi')->group(function () {
    // Mengambil semua menu untuk refresh sidebar
    Route::get('/all/{category}', [NavmenuController::class, 'getAllMenusForSidebar'])->name('api.navigasi.all');
    
    // Mengambil daftar parent menu untuk dropdown
    Route::get('/parents/{category}', [NavmenuController::class, 'getParentMenus'])->name('api.navigasi.parents');

    // Mengambil data satu menu untuk form edit
    Route::get('/{navMenu}', [NavmenuController::class, 'getMenuData'])->name('api.navigasi.get');

    // CRUD Operations
    Route::post('/', [NavmenuController::class, 'store'])->name('api.navigasi.store');
    Route::put('/{navMenu}', [NavmenuController::class, 'update'])->name('api.navigasi.update');
    Route::delete('/{navMenu}', [NavmenuController::class, 'destroy'])->name('api.navigasi.destroy');
    
    // Update konten dari CKEditor
    Route::put('/{navMenu}/content', [NavmenuController::class, 'updateMenuContent'])->name('api.navigasi.content.update');
});