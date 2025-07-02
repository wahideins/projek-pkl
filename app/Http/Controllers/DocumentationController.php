<?php

namespace App\Http\Controllers;

use App\Models\Navmenu;
use App\Models\DocsContent;
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
        $allMenus = Navmenu::where('category', $category)->orderBy('menu_order')->get();
        $navigation = $this->buildMenuTree($allMenus, 0, $category);

        $menuIds = $allMenus->pluck('menu_id');

        $docsContent = DocsContent::with('menu')
            ->whereIn('menu_id', $menuIds)
            ->get();
        
        $contents = $docsContent->pluck('content');

        
        $title = 'Dokumentasi ' . Str::headline($category);

        $pageSlug = Str::slug($page);
        $path = resource_path("views/docs/{$category}/{$pageSlug}.blade.php");
        
        if (!File::exists($path)) {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, "# " . Str::headline($page) . $contents);
        }
        $viewName = $viewName = "docs.{$category}.{$pageSlug}";
        $content = $viewName;

        return view('docs.index', [
            'title' => $title,
            'navigation' => $navigation,
            'content' => $content,
            'currentCategory' => $category,
            'currentPage' => $pageSlug,
        ]);

        return view($viewName);
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
