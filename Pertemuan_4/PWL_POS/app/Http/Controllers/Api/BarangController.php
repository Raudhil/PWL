<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
        return Barang::all();
    }

    public function store(Request $request)
    {
        $barang = Barang::create($request->all());
        return response()->json($barang, 201);
    }

    public function show(Barang $barang)
    {
        return $barang;
    }

    public function update(Request $request, Barang $barang)
    {
        $barang->update($request->all());
        return $barang;
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data barang terhapus',
        ]);
    }
}
