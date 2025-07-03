<?php

namespace App\Http\Controllers;

use App\Models\NavMenu;
use App\Models\DocsContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class DocumentationController extends Controller
{
    public function show($category = 'epesantren', $page = 'introduction'): View
    {
        $allMenus = NavMenu::where('category', $category)->orderBy('menu_order')->get();
        $navigation = NavMenu::buildTree($allMenus);
        $selectedNavItem = $allMenus->firstWhere('menu_link', route('docs', ['category' => $category, 'page' => $page]));

        if (isset($selectedNavItem)) {
            $menuIds = $selectedNavItem->menu_id;
        } else {
            $menuIds = 0;
        }


        $menusWithDocs = NavMenu::with('docsContent')->where('menu_id', $menuIds)->first();


        // Path view yang ingin dirender
        $viewPath = "docs.pages.{$category}.{$page}";
        $filePath = resource_path("views/docs/pages/{$category}/{$page}.blade.php");

        // Jika belum ada, generate file otomatis
        if (!File::exists($filePath)) {
            File::ensureDirectoryExists(resource_path("views/docs/pages/{$category}"));

            File::put(
                $filePath,
                <<<BLADE
@guest
<div class="contents">
    {!! \$contentDocs->docsContent->content ?? "Konten Belum Tersedia" !!}
</div>
@endguest

@auth
<div class="menuid">
    </div>
    <div class="main-container">
        <div class="editor-container" id="editor-container">
            <form action="{{ route('docs.save', ['menu_id' => \$menu_id]) }}" method="POST">
                @csrf
                <textarea name="content" id="editor" class="ckeditor">
                    {{ \$contentDocs->docsContent->content ?? "Konten Belum Tersedia" }}
                </textarea>
                <div class="buttons">
                    <button type="submit" class="btn btn-simpan">Simpan</button>
                    <a href="{{ route('docs', ['category' => \$currentCategory, 'page' => \$currentPage]) }}" class="btn btn-batal">Batal</a>

                </form>
                <form action="{{ route('docs.delete', ['menu_id' => \$menu_id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-hapus">Hapus</button>
                </form>
            </div>

</div>
@endauth
BLADE
            );
        }

        $allParentMenus = NavMenu::where('category', $category)
            ->where('menu_child', 0)
            ->orderBy('menu_nama')->get();

        return view('docs.index', [
            'title' => 'Dokumentasi ' . Str::headline($category),
            'navigation' => $navigation,
            'currentCategory' => $category,
            'currentPage' => $page,
            'selectedNavItem' => $selectedNavItem,
            'menu_id' => $menuIds,
            'allParentMenus' => $allParentMenus,
            'viewPath' => $viewPath,
            'contentDocs' => $menusWithDocs,
        ]);
    }

    // Fungsi simpan/update konten dokumentasi
    public function saveContent(Request $request, $menu_id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $content = DocsContent::updateOrCreate(
            ['menu_id' => $menu_id],
            ['content' => $request->input('content')]
        );

        return redirect()->back()->with('success', 'Konten berhasil disimpan.');
    }

    // Fungsi hapus konten dokumentasi
    public function deleteContent($menu_id)
    {
        $doc = DocsContent::where('menu_id', $menu_id)->first();

        if ($doc) {
            $doc->delete();
            return redirect()->back()->with('success', 'Konten berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Konten tidak ditemukan.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $category = $request->input('category', 'epesantren');
    
        if (!$query) {
            return response()->json(['results' => []]);
        }
    
        $results = [];
    
        // TAHAP 1: Cari berdasarkan nama menu
        $menuMatches = \App\Models\NavMenu::where('category', $category)
            ->where('menu_nama', 'LIKE', "%{$query}%")
            ->get();
    
        foreach ($menuMatches as $menu) {
            // Simpan hasil ke dalam array, gunakan menu_id sebagai kunci untuk menghindari duplikat
            $results[$menu->menu_id] = [
                'id' => $menu->menu_id,
                'name' => $menu->menu_nama,
                'url' => route('docs', ['category' => $menu->category, 'page' => \Illuminate\Support\Str::slug($menu->menu_nama)]),
                'context' => 'Judul Menu', // Konteks bahwa ini adalah judul
            ];
        }
    
        // TAHAP 2: Cari berdasarkan isi konten dari setiap halaman
        $contentMatches = \App\Models\DocsContent::with('menu')
            ->whereHas('menu', function ($q) use ($category) {
                $q->where('category', $category);
            })
            ->where('content', 'LIKE', "%{$query}%") // Baris ini yang mencari di dalam konten
            ->get();
    
        foreach ($contentMatches as $content) {
            // Hanya tambahkan jika belum ada dari hasil pencarian judul
            if ($content->menu && !isset($results[$content->menu->menu_id])) {
                $results[$content->menu->menu_id] = [
                    'id' => $content->menu->menu_id,
                    'name' => $content->menu->menu_nama,
                    'url' => route('docs', ['category' => $content->menu->category, 'page' => \Illuminate\Support\Str::slug($content->menu->menu_nama)]),
                    'context' => \Illuminate\Support\Str::limit(strip_tags($content->content), 100), // Tampilkan sedikit cuplikan konten
                ];
            }
        }
    
        // Kembalikan hasil gabungan sebagai JSON
        return response()->json(['results' => array_values($results)]);
    }

}
