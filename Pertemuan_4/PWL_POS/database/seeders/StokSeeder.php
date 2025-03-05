<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['supplier_id' => 1, 'barang_id' => 1, 'user_id' => 1, 'stok_jumlah' => 10, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 2, 'user_id' => 1, 'stok_jumlah' => 20, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 3, 'user_id' => 1, 'stok_jumlah' => 15, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 4, 'user_id' => 1, 'stok_jumlah' => 30, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 5, 'user_id' => 1, 'stok_jumlah' => 25, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 6, 'user_id' => 1, 'stok_jumlah' => 12, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 7, 'user_id' => 1, 'stok_jumlah' => 18, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 8, 'user_id' => 1, 'stok_jumlah' => 22, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 9, 'user_id' => 1, 'stok_jumlah' => 8, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 10, 'user_id' => 1, 'stok_jumlah' => 14, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 11, 'user_id' => 1, 'stok_jumlah' => 28, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 12, 'user_id' => 1, 'stok_jumlah' => 5, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 13, 'user_id' => 1, 'stok_jumlah' => 7, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 14, 'user_id' => 1, 'stok_jumlah' => 11, 'stok_tanggal' => now()],
            ['supplier_id' => 1, 'barang_id' => 15, 'user_id' => 1, 'stok_jumlah' => 9, 'stok_tanggal' => now()],
        ];

        DB::table('m_stok')->insert($data);
    }
}
