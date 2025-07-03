<?php

namespace App\Http\Controllers;

use App\Models\NavMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class NavmenuController extends Controller
{
    /**
     * Mengambil data satu menu untuk form edit.
     * Method ini ditambahkan untuk memperbaiki error.
     */
    public function getMenuData(NavMenu $navMenu)
    {
        return response()->json($navMenu);
    }

    /**
     * Mengambil daftar parent menu untuk dropdown.
     */
    public function getParentMenus($category)
    {
        $parents = NavMenu::where('category', $category)
            ->orderBy('menu_nama')
            ->get(['menu_id', 'menu_nama']);
    }

    /**
     * Mengambil semua menu untuk refresh sidebar (dalam bentuk HTML).
     */
    public function getAllMenusForSidebar($category)
    {
        $allMenus = NavMenu::where('category', $category)->orderBy('menu_order')->get();
        $navigation = NavMenu::buildTree($allMenus);

        $html = View::make('docs._menu_item', [
            'items' => $navigation,
            'editorMode' => true,
            'selectedNavItemId' => null,
        ])->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Menyimpan menu baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_nama' => 'required|string|max:50',
            'menu_child' => 'required|integer',
            'menu_order' => 'required|integer',
            'category' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            $menu = NavMenu::create([
                'menu_nama' => $request->menu_nama,
                'menu_link' => '#',
                'menu_icon' => $request->menu_icon,
                'menu_child' => $request->menu_child,
                'menu_order' => $request->menu_order,
                'menu_status' => $request->has('menu_status') ? 1 : 0,
                'category' => $request->category,
            ]);

            $menu->docsContent()->create(['content' => '# ' . $request->menu_nama]);
        });

        return response()->json(['success' => 'Menu berhasil ditambahkan!']);
    }

    /**
     * Memperbarui menu yang ada.
     */
    public function update(Request $request, NavMenu $navMenu)
    {
        $request->validate([
            'menu_nama' => 'required|string|max:50',
            'menu_child' => 'required|integer',
            'menu_order' => 'required|integer',
        ]);

        $navMenu->update([
            'menu_nama' => $request->menu_nama,
            'menu_icon' => $request->menu_icon,
            'menu_child' => $request->menu_child,
            'menu_order' => $request->menu_order,
            'menu_status' => $request->has('menu_status') ? 1 : 0,
        ]);

        return response()->json(['success' => 'Menu berhasil diperbarui!']);
    }

    /**
     * Menghapus menu.
     */
    public function destroy(NavMenu $navMenu)
    {
        $navMenu->delete();
        return response()->json(['success' => 'Menu dan semua sub-menu berhasil dihapus!']);
    }

    /**
     * Update konten dari CKEditor.
     * Method ini ada di rute Anda, jadi ditambahkan di sini.
     */
    public function updateMenuContent(Request $request, NavMenu $navMenu)
    {
        // Anda bisa menambahkan logika untuk update konten di sini nanti.
        // Contoh:
        // $request->validate(['content' => 'required|string']);
        // $navMenu->docsContent()->update(['content' => $request->content]);
        // return response()->json(['success' => 'Konten berhasil diperbarui']);

        return response()->json(['message' => 'Fungsi update konten belum diimplementasikan'], 501);
    }
}
