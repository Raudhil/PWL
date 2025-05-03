<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan semua barang dari m_barang
        $barang = DB::table('m_barang')->get();

        // Membuat data stok berdasarkan barang
        $data = [];
        foreach ($barang as $item) {
            $data[] = [
                'barang_id' => $item->barang_id,
                'barang_nama' => $item->barang_nama, // menambahkan nama barang dari m_barang
                'jumlah_stok' => rand(5, 30), // Misalkan jumlah stoknya diisi secara acak
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Menyisipkan data ke dalam tabel stok
        DB::table('m_stok')->insert($data);
    }
}
