<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentationController extends Controller
{
    /**
     * Menampilkan halaman dokumentasi berdasarkan kategori yang dipilih.
     *
     * @param string $category Kategori dokumentasi ('epesantren' atau 'adminsekolah').
     * @return View
     */
    public function show($category = 'epesantren'): View
    {
        // Tentukan data navigasi dan konten berdasarkan kategori
        if ($category === 'epesantren') {
            $navigation = $this->getEpesantrenNavigation();
            $title = 'Dokumentasi Epesantren';
            $content = '<h1>Selamat Datang di Dokumentasi Epesantren</h1><p>Pilih topik dari menu di sebelah kiri untuk memulai.</p>';
        } elseif ($category === 'adminsekolah') {
            $navigation = $this->getAdminSekolahNavigation();
            $title = 'Dokumentasi Admin Sekolah';
            $content = '<h1>Selamat Datang di Dokumentasi Admin Sekolah</h1><p>Pilih topik dari menu di sebelah kiri untuk memulai.</p>';
        } else {
            // Jika kategori tidak valid, tampilkan halaman 404
            abort(404);
        }

        // Kirim data ke view
        return view('docs.index', [
            'title' => $title,
            'navigation' => $navigation,
            'content' => $content,
            'currentCategory' => $category,
        ]);
    }

    /**
     * Menyediakan data navigasi untuk Epesantren.
     * (Di aplikasi nyata, ini bisa berasal dari database atau file)
     */
    private function getEpesantrenNavigation(): array
    {
        return [
            ['title' => 'Instalasi', 'url' => '#'],
            ['title' => 'Manajemen Santri', 'url' => '#'],
            ['title' => 'Manajemen Pengajar', 'url' => '#'],
            ['title' => 'Laporan Keuangan', 'url' => '#'],
        ];
    }

    /**
     * Menyediakan data navigasi untuk Admin Sekolah.
     */
    private function getAdminSekolahNavigation(): array
    {
        return [
            ['title' => 'Setup Awal', 'url' => '#'],
            ['title' => 'Manajemen Siswa', 'url' => '#'],
            ['title' => 'Manajemen Guru', 'url' => '#'],
            ['title' => 'Pengaturan Kurikulum', 'url' => '#'],
            ['title' => 'Laporan Akademik', 'url' => '#'],
        ];
    }
}
