<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\NavmenuController;

// Halaman utama dan dokumentasi
Route::get('/', [DocumentationController::class, 'show'])->name('home');
Route::get('/docs/{category?}/{page?}', [DocumentationController::class, 'show'])->name('docs');
Route::get('/api/search', [DocumentationController::class, 'search'])->name('api.search'); // <-- Pindahkan rute search ke sini


// Upload gambar untuk CKEditor
Route::post('/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

// Konten Dokumentasi
Route::post('/docs/save/{menu_id}', [DocumentationController::class, 'saveContent'])->name('docs.save');
Route::delete('/docs/delete/{menu_id}', [DocumentationController::class, 'deleteContent'])->name('docs.delete');


// Otentikasi
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// --- API Routes ---
Route::middleware('auth')->prefix('api')->group(function () {
    
    // Grup khusus untuk Navigasi
    Route::prefix('navigasi')->group(function () {
        Route::get('/all/{category}', [NavmenuController::class, 'getAllMenusForSidebar'])->name('api.navigasi.all');
        Route::get('/parents/{category}', [NavmenuController::class, 'getParentMenus'])->name('api.navigasi.parents');
        Route::get('/{navMenu}', [NavmenuController::class, 'getMenuData'])->name('api.navigasi.get');
        Route::post('/', [NavmenuController::class, 'store'])->name('api.navigasi.store');
        Route::put('/{navMenu}', [NavmenuController::class, 'update'])->name('api.navigasi.update');
        Route::delete('/{navMenu}', [NavmenuController::class, 'destroy'])->name('api.navigasi.destroy');
        Route::put('/{navMenu}/content', [NavmenuController::class, 'updateMenuContent'])->name('api.navigasi.content.update');
    });

    // Rute untuk pencarian (sekarang di URL yang benar: /api/search)

});