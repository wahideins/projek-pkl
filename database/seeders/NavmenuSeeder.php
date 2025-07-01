<?php

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
        DB::table('navmenu')->insert([
            ['menu_id' => 1, 'menu_nama' => 'Dashboard', 'menu_link' => 'manage', 'menu_icon' => 'fa fa-th', 'menu_child' => 0, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 2, 'menu_nama' => 'Kesiswaan', 'menu_link' => '#', 'menu_icon' => 'fa fa-users text-stock', 'menu_child' => 0, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 3, 'menu_nama' => 'Kepegawaian', 'menu_link' => '#', 'menu_icon' => 'fa fa-suitcase text-stock', 'menu_child' => 0, 'menu_order' => 3, 'menu_status' => 0],
            ['menu_id' => 4, 'menu_nama' => 'Akademik', 'menu_link' => '#', 'menu_icon' => 'fa fa-graduation-cap text-stoc', 'menu_child' => 0, 'menu_order' => 4, 'menu_status' => 0],
            ['menu_id' => 5, 'menu_nama' => 'Keuangan', 'menu_link' => '#', 'menu_icon' => 'fa fa-money text-stock', 'menu_child' => 0, 'menu_order' => 5, 'menu_status' => 0],
            ['menu_id' => 6, 'menu_nama' => 'Laporan', 'menu_link' => '#', 'menu_icon' => 'fa fa-file-text-o text-stock', 'menu_child' => 0, 'menu_order' => 7, 'menu_status' => 0],
            ['menu_id' => 7, 'menu_nama' => 'Pengaturan', 'menu_link' => '#', 'menu_icon' => 'fa fa-gear text-stock', 'menu_child' => 0, 'menu_order' => 8, 'menu_status' => 0],
            ['menu_id' => 8, 'menu_nama' => 'Unit', 'menu_link' => 'manage/majors', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 2, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 9, 'menu_nama' => 'Kelas', 'menu_link' => 'manage/class', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 2, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 10, 'menu_nama' => 'Siswa', 'menu_link' => 'manage/student', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 2, 'menu_order' => 3, 'menu_status' => 0],
            // ... (Tambahkan sisa data Anda di sini)
            ['menu_id' => 198, 'menu_nama' => 'Limit Tarik Tabungan', 'menu_link' => 'manage/banking_limit', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 5, 'menu_order' => 5, 'menu_status' => 0]
        ]);
    }
}
