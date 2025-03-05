<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['penjualan_id' => 1, 'barang_id' => 1, 'jumlah' => 2, 'harga' => 20000],
            ['penjualan_id' => 1, 'barang_id' => 2, 'jumlah' => 1, 'harga' => 17000],
            ['penjualan_id' => 1, 'barang_id' => 3, 'jumlah' => 3, 'harga' => 25000],

            ['penjualan_id' => 2, 'barang_id' => 4, 'jumlah' => 5, 'harga' => 5000],
            ['penjualan_id' => 2, 'barang_id' => 5, 'jumlah' => 2, 'harga' => 10000],
            ['penjualan_id' => 2, 'barang_id' => 6, 'jumlah' => 4, 'harga' => 8000],

            ['penjualan_id' => 3, 'barang_id' => 7, 'jumlah' => 3, 'harga' => 7000],
            ['penjualan_id' => 3, 'barang_id' => 8, 'jumlah' => 2, 'harga' => 9000],
            ['penjualan_id' => 3, 'barang_id' => 9, 'jumlah' => 1, 'harga' => 20000],

            ['penjualan_id' => 4, 'barang_id' => 10, 'jumlah' => 2, 'harga' => 30000],
            ['penjualan_id' => 4, 'barang_id' => 11, 'jumlah' => 1, 'harga' => 25000],
            ['penjualan_id' => 4, 'barang_id' => 12, 'jumlah' => 3, 'harga' => 25000],

            ['penjualan_id' => 5, 'barang_id' => 13, 'jumlah' => 1, 'harga' => 150000],
            ['penjualan_id' => 5, 'barang_id' => 14, 'jumlah' => 4, 'harga' => 50000],
            ['penjualan_id' => 5, 'barang_id' => 15, 'jumlah' => 2, 'harga' => 100000],

            ['penjualan_id' => 6, 'barang_id' => 1, 'jumlah' => 2, 'harga' => 20000],
            ['penjualan_id' => 6, 'barang_id' => 3, 'jumlah' => 1, 'harga' => 25000],
            ['penjualan_id' => 6, 'barang_id' => 5, 'jumlah' => 3, 'harga' => 10000],

            ['penjualan_id' => 7, 'barang_id' => 7, 'jumlah' => 4, 'harga' => 7000],
            ['penjualan_id' => 7, 'barang_id' => 9, 'jumlah' => 2, 'harga' => 20000],
            ['penjualan_id' => 7, 'barang_id' => 11, 'jumlah' => 1, 'harga' => 25000],

            ['penjualan_id' => 8, 'barang_id' => 13, 'jumlah' => 2, 'harga' => 150000],
            ['penjualan_id' => 8, 'barang_id' => 15, 'jumlah' => 3, 'harga' => 100000],
            ['penjualan_id' => 8, 'barang_id' => 2, 'jumlah' => 2, 'harga' => 17000],

            ['penjualan_id' => 9, 'barang_id' => 4, 'jumlah' => 5, 'harga' => 5000],
            ['penjualan_id' => 9, 'barang_id' => 6, 'jumlah' => 3, 'harga' => 8000],
            ['penjualan_id' => 9, 'barang_id' => 8, 'jumlah' => 1, 'harga' => 9000],

            ['penjualan_id' => 10, 'barang_id' => 10, 'jumlah' => 2, 'harga' => 30000],
            ['penjualan_id' => 10, 'barang_id' => 12, 'jumlah' => 4, 'harga' => 25000],
            ['penjualan_id' => 10, 'barang_id' => 14, 'jumlah' => 2, 'harga' => 50000],
        ];

        DB::table('m_penjualan_detail')->insert($data);
    }
}
