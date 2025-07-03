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
}
