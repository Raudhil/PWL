<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        [
        $data = [
            ['kategori_id' => 1, 'barang_kode' => 'MKN001', 'barang_nama' => 'Nasi Goreng', 'harga_beli' => 15000, 'harga_jual' => 20000],
            ['kategori_id' => 1, 'barang_kode' => 'MKN002', 'barang_nama' => 'Mie Goreng', 'harga_beli' => 12000, 'harga_jual' => 17000],
            ['kategori_id' => 1, 'barang_kode' => 'MKN003', 'barang_nama' => 'Ayam Goreng', 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['kategori_id' => 1, 'barang_kode' => 'MKN004', 'barang_nama' => 'Es Teh Manis', 'harga_beli' => 3000, 'harga_jual' => 5000],
            ['kategori_id' => 1, 'barang_kode' => 'MKN005', 'barang_nama' => 'Kopi Susu', 'harga_beli' => 7000, 'harga_jual' => 10000],
            ['kategori_id' => 2, 'barang_kode' => 'SNK001', 'barang_nama' => 'Keripik Singkong', 'harga_beli' => 5000, 'harga_jual' => 8000],
            ['kategori_id' => 2, 'barang_kode' => 'SNK002', 'barang_nama' => 'Kacang Atom', 'harga_beli' => 4000, 'harga_jual' => 7000],
            ['kategori_id' => 2, 'barang_kode' => 'SNK003', 'barang_nama' => 'Wafer Coklat', 'harga_beli' => 6000, 'harga_jual' => 9000],
            ['kategori_id' => 3, 'barang_kode' => 'RUM001', 'barang_nama' => 'Sapu Lidi', 'harga_beli' => 15000, 'harga_jual' => 20000],
            ['kategori_id' => 3, 'barang_kode' => 'RUM002', 'barang_nama' => 'Pel Lantai', 'harga_beli' => 25000, 'harga_jual' => 30000],
            ['kategori_id' => 3, 'barang_kode' => 'RUM003', 'barang_nama' => 'Ember Plastik', 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['kategori_id' => 4, 'barang_kode' => 'PRB001', 'barang_nama' => 'Meja Kayu', 'harga_beli' => 100000, 'harga_jual' => 150000],
            ['kategori_id' => 4, 'barang_kode' => 'PRB002', 'barang_nama' => 'Kursi Plastik', 'harga_beli' => 30000, 'harga_jual' => 50000],
            ['kategori_id' => 4, 'barang_kode' => 'PRB003', 'barang_nama' => 'Rak Piring', 'harga_beli' => 75000, 'harga_jual' => 100000],
            ['kategori_id' => 4, 'barang_kode' => 'PRB003', 'barang_nama' => 'Rak Sepatu', 'harga_beli' => 80000, 'harga_jual' => 105000],
        ]
    ];

    DB::table('m_barang')->insert($data);
    }
}
