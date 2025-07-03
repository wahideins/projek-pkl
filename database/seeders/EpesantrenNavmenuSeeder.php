<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EpesantrenNavmenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID tertinggi yang sudah ada di tabel untuk menghindari konflik
        $offset = DB::table('navmenu')->max('menu_id') ?? 0;

        // Data menu Epesantren yang sudah ada
        $epesantrenMenuData = [
            ['menu_id' => 1, 'menu_nama' => 'Dashboard', 'menu_link' => 'manage', 'menu_icon' => 'fa fa-th', 'menu_child' => 0, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 2, 'menu_nama' => 'Kesantrian', 'menu_link' => '#', 'menu_icon' => 'fa fa-users text-stock', 'menu_child' => 0, 'menu_order' => 2, 'menu_status' => 0],
            // ... (semua 223 baris data Anda yang lain ada di sini) ...
            ['menu_id' => 230, 'menu_nama' => 'Pelanggaran', 'menu_link' => 'manage/pelanggaran', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 95, 'menu_order' => 5, 'menu_status' => 0]
        ];
        
        // --- DATA DOKUMENTASI EPESANTREN DIMULAI DI SINI ---
        $dokumentasiMenuData = [
            // ID sengaja dibuat tinggi untuk menghindari konflik internal sebelum offset diterapkan
            ['menu_id' => 401, 'menu_nama' => 'Dok. ePesantren', 'menu_link' => '#', 'menu_icon' => 'fa fa-book text-success', 'menu_child' => 0, 'menu_order' => 101, 'menu_status' => 0],
            ['menu_id' => 402, 'menu_nama' => 'Pendahuluan', 'menu_link' => 'docs/epesantren/welcome', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 401, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 403, 'menu_nama' => 'Panduan Kesantrian', 'menu_link' => 'docs/epesantren/kesantrian', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 401, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 404, 'menu_nama' => 'Panduan Keuangan', 'menu_link' => 'docs/epesantren/keuangan', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 401, 'menu_order' => 3, 'menu_status' => 0],
            ['menu_id' => 405, 'menu_nama' => 'Panduan Kepengasuhan', 'menu_link' => 'docs/epesantren/kepengasuhan', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 401, 'menu_order' => 4, 'menu_status' => 0],
        ];

        // --- GABUNGKAN DATA LAMA DENGAN DATA DOKUMENTASI ---
        $finalData = array_merge($epesantrenMenuData, $dokumentasiMenuData);

        // Transformasi data untuk menyesuaikan ID dan ID child
        $transformedData = array_map(function($item) use ($offset) {
            // Tambahkan offset ke ID utama
            $item['menu_id'] += $offset;
            
            // Jika item ini adalah child, tambahkan offset ke ID parent-nya juga
            if ($item['menu_child'] != 0) {
                $item['menu_child'] += $offset;
            }
            
            // Pastikan kategori diatur dengan benar
            $item['category'] = 'epesantren'; // Tetap gunakan kategori ini
            
            return $item;
        }, $finalData); // Gunakan data yang sudah digabung

        // Hapus data lama (jika ada) dan masukkan data baru yang sudah ditransformasi
        DB::table('navmenu')->where('category', 'epesantren')->delete();
        DB::table('navmenu')->insert($transformedData);
    }
}