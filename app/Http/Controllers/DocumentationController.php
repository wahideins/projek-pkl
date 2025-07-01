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
        // Ambil semua menu untuk kategori yang dipilih dari database
        $allMenus = Navmenu::where('category', $category)->orderBy('menu_order')->get();
        $navigation = $this->buildMenuTree($allMenus, 0, $category);
        
        $title = 'Dokumentasi ' . Str::headline($category);

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
     * Membangun menu hierarkis dari database.
     */
    private function buildMenuTree($elements, $parentId, $category): array
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element->menu_child == $parentId) {
                // Buat link yang benar berdasarkan kategori dan nama menu
                $pageSlug = Str::slug($element->menu_nama);
                $element->menu_link = route('docs', ['category' => $category, 'page' => $pageSlug]);
                
                $children = $this->buildMenuTree($elements, $element->menu_id, $category);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }
}
