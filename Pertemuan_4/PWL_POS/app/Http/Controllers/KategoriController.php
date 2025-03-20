<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori yang tersedia'
        ];

        $activeMenu = 'kategori';

        // Ambil semua data kategori dari tabel kategori
        $kategori = Kategori::all();

        return view('kategori.index', compact('breadcrumb', 'page', 'kategori', 'activeMenu'));
    }



    public function list(Request $request)
    {
        $kategoris = Kategori::select('kategori_id', 'kategori_kode', 'kategori_nama');

        // Filter data kategori berdasarkan kategori_id (jika ada)
        if ($request->kategori_id) {
            $kategoris->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($kategoris)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($kategori) {
                $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Agar tombol HTML bisa ditampilkan
            ->make(true);
    }


    public function edit(string $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Kategori tidak ditemukan');
        }

        $breadcrumb = (object)[
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';

        return view('kategori.edit', compact('breadcrumb', 'page', 'kategori', 'activeMenu'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100',
        ]);


        Kategori::where('kategori_id', $id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
            'updated_at' => now(),
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    public function destroy(string $id)
    {

        $check = Kategori::find($id);
        if (!$check) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        try {
            Kategori::destroy($id);
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.');
        }
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori';

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required|string|min:3|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100'
        ]);

        Kategori::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    public function create_ajax()
    {
        $kategoris = Kategori::select('kategori_id', 'kategori_nama')->get();

        return view('kategori.create_ajax')->with('kategoris', $kategoris);
    }


    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer|unique:m_kategori,kategori_id',
                'kategori_kode' => 'required|string|unique:m_kategori,kategori_kode|max:10',
                'kategori_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            Kategori::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan'
            ]);
        }
        return redirect('/kategori');
    }



    public function edit_ajax(String $id)
    {
        $kategori = Kategori::find($id);

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = Kategori::find($id);

            if ($check) {
                $check->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data kategori berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data kategori tidak ditemukan'
                ]);
            }
        }
    }

    public function confirm_ajax(String $id)
    {
        $kategori = Kategori::find($id);

        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") { // Code 23000 = Foreign Key Constraint
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori tidak bisa dihapus karena masih memiliki data terkait'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus kategori'
            ]);
        }
    }
}
