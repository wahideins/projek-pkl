<?php

namespace App\Http\Controllers;

use App\Models\NavMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentationController extends Controller
{
    /**
     * Menampilkan halaman dokumentasi.
     */
    public function show($category = 'epesantren', $page = 'introduction'): View
    {
        $allMenus = NavMenu::where('category', $category)->orderBy('menu_order')->get();
        
        // GANTI BARIS INI
        $navigation = NavMenu::buildTree($allMenus); // Memanggil static method dari Model

        $selectedNavItem = $allMenus->firstWhere('menu_link', route('docs', ['category' => $category, 'page' => $page]));
        
        $content = $selectedNavItem ? ($selectedNavItem->docsContent->content ?? '# Halaman Belum Ada Konten') : '# Selamat Datang';
        $contentHtml = Str::markdown($content);

        $allParentMenus = NavMenu::where('category', $category)->where('menu_child', 0)->orderBy('menu_nama')->get();

        return view('docs.index', [
            'title' => 'Dokumentasi ' . Str::headline($category),
            'navigation' => $navigation,
            'content' => $contentHtml,
            'currentCategory' => $category,
            'currentPage' => $page,
            'selectedNavItem' => $selectedNavItem,
            'allParentMenus' => $allParentMenus,
        ]);
    }

    // private function buildMenuTree(...) <-- HAPUS SELURUH FUNGSI INI
}