<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        return Kategori::all();
    }

    public function store(Request $request)
    {
        $kategori = Kategori::create($request->all());
        return response()->json($kategori, 201);
    }

    public function show(Kategori $kategori)
    {
        return $kategori;
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($request->all());
        return $kategori;
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
