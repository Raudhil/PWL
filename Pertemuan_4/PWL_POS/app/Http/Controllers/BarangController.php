<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang yang tersedia'
        ];

        $activeMenu = 'barang';

        // Mengambil semua data barang beserta kategori
        $barang = Barang::with('kategori')->get();

        return view('barang.index', compact('breadcrumb', 'page', 'barang', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $barangs = Barang::with('kategori')->select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');

        // Filter data barang berdasarkan kategori_id (jika ada)
        if ($request->kategori_id) {
            $barangs->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barangs)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori->kategori_nama ?? '-';
            })
            ->addColumn('aksi', function ($barang) {
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Agar tombol HTML bisa ditampilkan
            ->make(true);
    }


    public function edit(string $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return redirect('/barang')->with('error', 'Barang tidak ditemukan');
        }

        $breadcrumb = (object)[
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Edit Barang'
        ];

        $kategori = Kategori::all(); // Untuk dropdown kategori
        $activeMenu = 'barang';

        return view('barang.edit', compact('breadcrumb', 'page', 'barang', 'kategori', 'activeMenu'));
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_kode' => 'required|string|min:2|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:100',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        Barang::where('barang_id', $id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'kategori_id' => $request->kategori_id,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'updated_at' => now(),
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = Barang::find($id);
        if (!$check) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        try {
            Barang::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.');
        }
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah barang baru'
        ];

        $activeMenu = 'barang';

        // Ambil daftar kategori untuk dropdown
        $kategori = Kategori::all();

        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0'
        ]);

        Barang::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    public function create_ajax()
    {
        $kategori = Kategori::select('kategori_id', 'kategori_nama')->get();

        return view('barang.create_ajax')->with('kategori', $kategori);
    }


    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'barang_kode' => 'required|string|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|integer|min:0',
                'harga_jual' => 'required|integer|min:0'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Simpan data ke database
            Barang::create($request->only([
                'kategori_id',
                'barang_kode',
                'barang_nama',
                'harga_beli',
                'harga_jual'
            ]));

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(String $id)
    {
        $barang = Barang::find($id);
        return view('barang.edit_ajax', ['barang' => $barang]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_kode' => 'required|string|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|integer|min:0',
                'harga_jual' => 'required|integer|min:0'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'errors'   => $validator->errors(), // Tambahkan ini
                    'request'  => $request->all() // Tambahkan ini untuk melihat data yang dikirim
                ]);
            }


            $barang = Barang::find($id);

            if ($barang) {
                $barang->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
    }

    public function confirm_ajax(String $id)
    {
        $barang = Barang::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax($id)
    {
        try {
            $barang = Barang::findOrFail($id);
            $barang->delete();

            return response()->json([
                'status' => true,
                'message' => 'Barang berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'status' => false,
                    'message' => 'Barang tidak bisa dihapus karena memiliki data terkait'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus barang'
            ]);
        }
    }
}
