<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['user_id' => 1, 'pembeli' => 'Budi', 'penjualan_kode' => 'PJ001', 'penjualan_tanggal' => '2024-02-01 10:00:00'],
            ['user_id' => 2, 'pembeli' => 'Ani', 'penjualan_kode' => 'PJ002', 'penjualan_tanggal' => '2024-02-02 11:00:00'],
            ['user_id' => 3, 'pembeli' => 'Siti', 'penjualan_kode' => 'PJ003', 'penjualan_tanggal' => '2024-02-03 12:00:00'],
            ['user_id' => 1, 'pembeli' => 'Joko', 'penjualan_kode' => 'PJ004', 'penjualan_tanggal' => '2024-02-04 13:00:00'],
            ['user_id' => 2, 'pembeli' => 'Rina', 'penjualan_kode' => 'PJ005', 'penjualan_tanggal' => '2024-02-05 14:00:00'],
            ['user_id' => 3, 'pembeli' => 'Dewi', 'penjualan_kode' => 'PJ006', 'penjualan_tanggal' => '2024-02-06 15:00:00'],
            ['user_id' => 1, 'pembeli' => 'Agus', 'penjualan_kode' => 'PJ007', 'penjualan_tanggal' => '2024-02-07 16:00:00'],
            ['user_id' => 2, 'pembeli' => 'Sari', 'penjualan_kode' => 'PJ008', 'penjualan_tanggal' => '2024-02-08 17:00:00'],
            ['user_id' => 3, 'pembeli' => 'Bayu', 'penjualan_kode' => 'PJ009', 'penjualan_tanggal' => '2024-02-09 18:00:00'],
            ['user_id' => 1, 'pembeli' => 'Eka', 'penjualan_kode' => 'PJ010', 'penjualan_tanggal' => '2024-02-10 19:00:00'],
        ];

        DB::table('m_penjualan')->insert($data);
    }
}
