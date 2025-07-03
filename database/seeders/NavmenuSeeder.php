<?php
// File: database/seeders/NavmenuSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavmenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminSekolahMenuData = [
            ['menu_id' => 1, 'menu_nama' => 'Dashboard', 'menu_link' => 'manage', 'menu_icon' => 'fa fa-th', 'menu_child' => 0, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 2, 'menu_nama' => 'Kesiswaan', 'menu_link' => '#', 'menu_icon' => 'fa fa-users text-stock', 'menu_child' => 0, 'menu_order' => 2, 'menu_status' => 0],
            // ... (semua data Anda yang lain sampai ID 198 ada di sini) ...
            ['menu_id' => 198, 'menu_nama' => 'Limit Tarik Tabungan', 'menu_link' => 'manage/banking_limit', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 5, 'menu_order' => 5, 'menu_status' => 0]
        ];
        
        $dokumentasiMenuData = [
            ['menu_id' => 501, 'menu_nama' => 'Dok. Admin Sekolah', 'menu_link' => '#', 'menu_icon' => 'fa fa-book text-info', 'menu_child' => 0, 'menu_order' => 102, 'menu_status' => 0],
            ['menu_id' => 502, 'menu_nama' => 'Pendahuluan', 'menu_link' => 'docs/adminsekolah/welcome', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 501, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 503, 'menu_nama' => 'Panduan Kesiswaan', 'menu_link' => 'docs/adminsekolah/kesiswaan', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 501, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 504, 'menu_nama' => 'Panduan Keuangan', 'menu_link' => 'docs/adminsekolah/keuangan', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 501, 'menu_order' => 3, 'menu_status' => 0],
            ['menu_id' => 505, 'menu_nama' => 'Panduan Akademik', 'menu_link' => 'docs/adminsekolah/akademik', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 501, 'menu_order' => 4, 'menu_status' => 0],
        ];

        $finalData = array_merge($adminSekolahMenuData, $dokumentasiMenuData);

        DB::table('navmenu')->insert($finalData);
    }
}