<?php

namespace App\Http\Controllers;

use App\Models\NavMenu;
use Illuminate\Http\Request;

class NavmenuController extends Controller
{
    /**
     * Menampilkan daftar navigasi.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $navigationItems = NavMenu::where('menu_child', 0) // Asumsi 0 untuk menu utama
                                ->orderBy('menu_order')
                                ->get();
        return view('navigasi.index', compact('navigationItems'));
    }

    /**
     * Menampilkan form untuk membuat menu baru.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $parentMenus = NavMenu::where('menu_child', 0)->get(); // Ambil menu utama untuk dropdown parent
        return view('navigasi.create', compact('parentMenus'));
    }

    /**
     * Menyimpan menu baru ke database.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_nama' => 'required|string|max:50',
            'menu_link' => 'required|string|max:100',
            'menu_icon' => 'nullable|string|max:30',
            'menu_child' => 'required|integer', // Sesuaikan validasi jika menu_child bisa null
            'menu_order' => 'required|integer',
            'menu_status' => 'nullable|boolean',
        ]);

        NavMenu::create([
            'menu_nama' => $request->menu_nama,
            'menu_link' => $request->menu_link,
            'menu_icon' => $request->menu_icon,
            'menu_child' => $request->menu_child,
            'menu_order' => $request->menu_order,
            'menu_status' => $request->has('menu_status') ? 1 : 0, // Checkbox
        ]);

        return redirect()->route('navigasi.index')->with('success', 'Menu navigasi berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit menu.
     * @param  \App\Models\NavMenu  $navigasi
     * @return \Illuminate\View\View
     */
    public function edit(NavMenu $navigasi) // Menggunakan route model binding
    {
        $parentMenus = NavMenu::where('menu_child', 0)->where('menu_id', '!=', $navigasi->menu_id)->get();
        return view('navigasi.edit', compact('navigasi', 'parentMenus'));
    }

    /**
     * Memperbarui menu di database.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NavMenu  $navigasi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, NavMenu $navigasi)
    {
        $request->validate([
            'menu_nama' => 'required|string|max:50',
            'menu_link' => 'required|string|max:100',
            'menu_icon' => 'nullable|string|max:30',
            'menu_child' => 'required|integer',
            'menu_order' => 'required|integer',
            'menu_status' => 'nullable|boolean',
        ]);

        $navigasi->update([
            'menu_nama' => $request->menu_nama,
            'menu_link' => $request->menu_link,
            'menu_icon' => $request->menu_icon,
            'menu_child' => $request->menu_child,
            'menu_order' => $request->menu_order,
            'menu_status' => $request->has('menu_status') ? 1 : 0,
        ]);

        return redirect()->route('navigasi.index')->with('success', 'Menu navigasi berhasil diperbarui!');
    }

    /**
     * Menghapus menu dari database.
     * @param  \App\Models\NavMenu  $navigasi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(NavMenu $navigasi)
    {
        $navigasi->delete();
        return redirect()->route('navigasi.index')->with('success', 'Menu navigasi berhasil dihapus!');
    }
}