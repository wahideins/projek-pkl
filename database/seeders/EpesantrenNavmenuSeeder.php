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

        // Data menu Epesantren dalam bentuk array PHP
        $epesantrenMenuData = [
            ['menu_id' => 1, 'menu_nama' => 'Dashboard', 'menu_link' => 'manage', 'menu_icon' => 'fa fa-th', 'menu_child' => 0, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 2, 'menu_nama' => 'Kesantrian', 'menu_link' => '#', 'menu_icon' => 'fa fa-users text-stock', 'menu_child' => 0, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 3, 'menu_nama' => 'Kepegawaian', 'menu_link' => '#', 'menu_icon' => 'fa fa-suitcase text-stock', 'menu_child' => 0, 'menu_order' => 4, 'menu_status' => 0],
            ['menu_id' => 4, 'menu_nama' => 'Akademik', 'menu_link' => '#', 'menu_icon' => 'fa fa-graduation-cap text-stoc', 'menu_child' => 0, 'menu_order' => 5, 'menu_status' => 0],
            ['menu_id' => 5, 'menu_nama' => 'Keuangan', 'menu_link' => '#', 'menu_icon' => 'fa fa-money text-stock', 'menu_child' => 0, 'menu_order' => 7, 'menu_status' => 0],
            ['menu_id' => 6, 'menu_nama' => 'Laporan', 'menu_link' => '#', 'menu_icon' => 'fa fa-file-text-o text-stock', 'menu_child' => 0, 'menu_order' => 10, 'menu_status' => 0],
            ['menu_id' => 7, 'menu_nama' => 'Pengaturan', 'menu_link' => '#', 'menu_icon' => 'fa fa-gear text-stock', 'menu_child' => 0, 'menu_order' => 12, 'menu_status' => 0],
            ['menu_id' => 8, 'menu_nama' => 'Unit', 'menu_link' => 'manage/majors', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 2, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 9, 'menu_nama' => 'Kelas', 'menu_link' => 'manage/class', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 2, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 10, 'menu_nama' => 'Santri', 'menu_link' => 'manage/student', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 2, 'menu_order' => 4, 'menu_status' => 0],
            ['menu_id' => 11, 'menu_nama' => 'Jabatan Pegawai', 'menu_link' => 'manage/position', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 3, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 12, 'menu_nama' => 'Pegawai', 'menu_link' => 'manage/employees', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 3, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 13, 'menu_nama' => 'Tahun Ajaran', 'menu_link' => 'manage/period', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 4, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 14, 'menu_nama' => 'Pindah-Naik Kelas', 'menu_link' => 'manage/student/upgrade', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 4, 'menu_order' => 3, 'menu_status' => 0],
            ['menu_id' => 15, 'menu_nama' => 'Kelulusan', 'menu_link' => 'manage/student/pass', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 4, 'menu_order' => 5, 'menu_status' => 0],
            ['menu_id' => 16, 'menu_nama' => 'Pembayaran Santri', 'menu_link' => 'manage/payout', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 5, 'menu_order' => 1, 'menu_status' => 0],
            ['menu_id' => 17, 'menu_nama' => 'Setting Pembayaran', 'menu_link' => '#', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 5, 'menu_order' => 2, 'menu_status' => 0],
            ['menu_id' => 18, 'menu_nama' => 'Kas & Bank', 'menu_link' => '#', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 5, 'menu_order' => 6, 'menu_status' => 0],
            ['menu_id' => 19, 'menu_nama' => 'Penggajian', 'menu_link' => '#', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 5, 'menu_order' => 7, 'menu_status' => 0],
            ['menu_id' => 20, 'menu_nama' => 'Akun Biaya', 'menu_link' => 'manage/account', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 17, 'menu_order' => 1, 'menu_status' => 0],
            // ... (Lanjutkan dengan sisa data Anda)
            // Pastikan semua 223 baris data Anda ada di sini
            ['menu_id' => 230, 'menu_nama' => 'Pelanggaran', 'menu_link' => 'manage/pelanggaran', 'menu_icon' => 'fa fa-circle-o', 'menu_child' => 95, 'menu_order' => 5, 'menu_status' => 0]
        ];

        // Transformasi data untuk menyesuaikan ID dan ID child
        $transformedData = array_map(function($item) use ($offset) {
            // Tambahkan offset ke ID utama
            $item['menu_id'] += $offset;
            
            // Jika item ini adalah child, tambahkan offset ke ID parent-nya juga
            if ($item['menu_child'] != 0) {
                $item['menu_child'] += $offset;
            }
            
            // Pastikan kategori diatur dengan benar
            $item['category'] = 'epesantren';
            
            return $item;
        }, $epesantrenMenuData);

        // Hapus data lama (jika ada) dan masukkan data baru yang sudah ditransformasi
        DB::table('navmenu')->where('category', 'epesantren')->delete();
        DB::table('navmenu')->insert($transformedData);
    }
}
