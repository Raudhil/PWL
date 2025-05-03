<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenjualanDetailModel;

class PenjualanDetailController extends Controller
{
    // GET semua penjualan detail + join ke barang
    public function index()
    {
        $details = PenjualanDetailModel::with('barang')->get();

        $result = $details->map(function ($detail) {
            return [
                'penjualan_id' => $detail->penjualan_id,
                'barang_nama' => $detail->barang->barang_nama,
                'harga' => $detail->harga,
                'jumlah' => $detail->jumlah,
                'gambar' => $detail->barang->image 
                    ? asset('storage/' . $detail->barang->image)
                    : null,
            ];
        });

        return response()->json($result);
    }

    // POST untuk tambah penjualan detail
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penjualan_id' => 'required|integer',
            'barang_id' => 'required|exists:m_barang,barang_id',
            'harga' => 'required|numeric',
            'jumlah' => 'required|integer',
        ]);

        $detail = PenjualanDetailModel::create($validated);

        return response()->json($detail, 201);
    }
}
