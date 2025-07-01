<?php

namespace App\Http\Controllers;

use App\Models\Navmenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentationController extends Controller
{
    /**
     * Menampilkan halaman dokumentasi.
     */
    public function show($category = 'epesantren', $page = 'introduction'): View
    {
        $navigation = [];
        $title = '';
        
        if ($category === 'adminsekolah') {
            $allMenus = Navmenu::orderBy('menu_order')->get();
            $navigation = $this->buildMenuTree($allMenus);
            $title = 'Dokumentasi Admin Sekolah';
        } else { // Logika untuk Epesantren
            $navigation = $this->getEpesantrenNavigation();
            $title = 'Dokumentasi Epesantren';
        }

        $pageSlug = Str::slug($page);
        $path = resource_path("docs/{$category}/{$pageSlug}.md");

        if (!File::exists($path)) {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, "# " . Str::headline($page) . "\n\nKonten untuk halaman ini belum dibuat.");
        }
        $content = File::get($path);

        return view('docs.index', [
            'title' => $title,
            'navigation' => $navigation,
            'content' => $content,
            'currentCategory' => $category,
            'currentPage' => $pageSlug,
        ]);
    }

    /**
     * Membangun menu untuk Admin Sekolah dari database.
     */
    private function buildMenuTree($elements, $parentId = 0): array
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element->menu_child == $parentId) {
                $pageSlug = Str::slug($element->menu_nama);
                $element->menu_link = route('docs', ['category' => 'adminsekolah', 'page' => $pageSlug]);
                $children = $this->buildMenuTree($elements, $element->menu_id);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    /**
     * Menyediakan menu statis untuk Epesantren.
     */
    private function getEpesantrenNavigation(): array
    {
        return [
            (object)[
                'menu_nama' => 'Pendahuluan', 
                'menu_link' => route('docs', ['category' => 'epesantren', 'page' => 'introduction']), 
                'menu_icon' => 'fa fa-book', 
            ],
            (object)[
                'menu_nama' => 'Instalasi', 
                'menu_link' => route('docs', ['category' => 'epesantren', 'page' => 'instalasi']), 
                'menu_icon' => 'fa fa-download',
            ],
        ];
    }
}
