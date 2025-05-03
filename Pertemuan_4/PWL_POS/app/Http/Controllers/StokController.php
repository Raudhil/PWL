<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok yang terdaftar dalam sistem'
        ];

        $activeMenu = 'stok';

        $suppliers = Supplier::all();
        $barangs = Barang::all();
        $users = UserModel::all();

        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'suppliers' => $suppliers,
            'barangs' => $barangs,
            'users' => $users,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $stoks = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_jumlah', 'stok_tanggal')
            ->with(['supplier', 'barang', 'user']);

        // Filter data stok
        if ($request->barang_id) {
            $stoks->where('barang_id', $request->barang_id);
        }
        if ($request->user_id) {
            $stoks->where('user_id', $request->user_id);
        }

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->editColumn('stok_tanggal', function ($stok) {
                return $stok->stok_tanggal ? $stok->stok_tanggal->format('Y-m-d H:i:s') : '-';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Stok',
            'list' => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah stok baru'
        ];

        $suppliers = Supplier::all();
        $barangs = Barang::all();
        $users = UserModel::all();
        $activeMenu = 'stok';

        return view('stok.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'suppliers' => $suppliers,
            'barangs' => $barangs,
            'users' => $users,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_jumlah' => 'required|integer|min:1'
        ]);

        StokModel::create([
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'stok_jumlah' => $request->stok_jumlah,
            'stok_tanggal' => now()
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    public function show(string $id)
    {
        $stok = StokModel::with(['supplier', 'barang', 'user'])->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail stok'
        ];

        $activeMenu = 'stok';

        return view('stok.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'stok' => $stok,
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $stok = StokModel::find($id);
        $suppliers = Supplier::all();
        $barangs = Barang::all();
        $users = UserModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Stok',
            'list' => ['Home', 'Stok', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit stok'
        ];

        $activeMenu = 'stok';

        return view('stok.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'stok' => $stok,
            'suppliers' => $suppliers,
            'barangs' => $barangs,
            'users' => $users,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_id' => 'required|integer',
            'barang_id' => 'required|integer',
            'user_id' => 'required|integer',
            'stok_jumlah' => 'required|integer|min:1'
        ]);

        StokModel::where('stok_id', $id)->update([
            'supplier_id' => $request->supplier_id,
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'stok_jumlah' => $request->stok_jumlah
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = StokModel::find($id);
        if (!$check) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id);
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.');
        }
    }

    public function create_ajax()
    {
        $barangs = Barang::all();
        $users = UserModel::all();

        return view('stok.create_ajax', [
            'barangs' => $barangs,
            'users' => $users
        ]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id' => 'required|integer',
                'stok_jumlah' => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $userId = auth()->id(); // Ambil ID user yang sedang login

            // Cek apakah sudah ada stok untuk barang yang sama
            $stok = StokModel::where('barang_id', $request->barang_id)->first();

            if ($stok) {
                // Update jumlah stok dan tanggal
                $stok->stok_jumlah += $request->stok_jumlah;
                $stok->stok_tanggal = now();
                $stok->user_id = $userId; // Update user_id juga
                $stok->save();
            } else {
                // Jika belum ada, buat baru
                $stok = StokModel::create([
                    'barang_id' => $request->barang_id,
                    'stok_jumlah' => $request->stok_jumlah,
                    'stok_tanggal' => now(),
                    'user_id' => $userId
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan',
                'data' => $stok
            ]);
        }

        return redirect('/');
    }



    public function edit_ajax($id)
    {
        try {
            $stok = StokModel::find($id);
            $barang = Barang::all();

            if (!$stok) {
                return view('stok.edit_ajax', compact('stok'));
            }

            return view('stok.edit_ajax', compact('stok', 'barang'));
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }



    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_id' => 'required|integer',
                'barang_id' => 'required|integer',
                'user_id' => 'required|integer',
                'stok_jumlah' => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $stok = StokModel::find($id);

            if ($stok) {
                $stok->update([
                    'supplier_id' => $request->supplier_id,
                    'barang_id' => $request->barang_id,
                    'user_id' => $request->user_id,
                    'stok_jumlah' => $request->stok_jumlah
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(String $id)
    {
        $stok = StokModel::find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax($id)
    {
        try {
            $stok = StokModel::findOrFail($id);
            $stok->delete();

            return response()->json([
                'status' => true,
                'message' => 'Stok berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'status' => false,
                    'message' => 'Stok tidak bisa dihapus karena memiliki data terkait'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus stok'
            ]);
        }
    }

    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $file = $request->file('file_stok');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);

                $insert = [];
                if (count($data) > 1) {
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) {
                            // Validasi data
                            if (empty($value['A']) || empty($value['B']) || empty($value['C']) || empty($value['D'])) {
                                throw new \Exception("Data pada baris $baris tidak lengkap.");
                            }

                            // Validasi supplier, barang, user
                            if (!Supplier::find($value['A'])) {
                                throw new \Exception("Supplier dengan ID {$value['A']} pada baris $baris tidak ditemukan.");
                            }
                            if (!Barang::find($value['B'])) {
                                throw new \Exception("Barang dengan ID {$value['B']} pada baris $baris tidak ditemukan.");
                            }
                            if (!UserModel::find($value['C'])) {
                                throw new \Exception("User dengan ID {$value['C']} pada baris $baris tidak ditemukan.");
                            }

                            $insert[] = [
                                'supplier_id' => $value['A'],
                                'barang_id' => $value['B'],
                                'user_id' => $value['C'],
                                'stok_jumlah' => $value['D'],
                                'stok_tanggal' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        $insertedCount = StokModel::insertOrIgnore($insert);
                        if ($insertedCount === 0) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Tidak ada data yang berhasil diimport. Pastikan data valid dan tidak duplikat.'
                            ]);
                        }

                        return response()->json([
                            'status' => true,
                            'message' => "Data berhasil diimport ($insertedCount baris)"
                        ]);
                    }
                }

                return response()->json([
                    'status' => false,
                    'message' => 'File Excel kosong atau tidak memiliki data'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengimpor data: ' . $e->getMessage()
                ], 500);
            }
        }
        return redirect('/stok');
    }

    public function export_excel()
    {
        $stoks = StokModel::with(['supplier', 'barang', 'user'])
            ->orderBy('stok_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Supplier');
        $sheet->setCellValue('C1', 'Barang');
        $sheet->setCellValue('D1', 'User');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'Tanggal');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;

        foreach ($stoks as $stok) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $stok->supplier->nama_supplier ?? '');
            $sheet->setCellValue('C' . $baris, $stok->barang->barang_nama ?? '');
            $sheet->setCellValue('D' . $baris, $stok->user->nama ?? '');
            $sheet->setCellValue('E' . $baris, $stok->stok_jumlah);
            $sheet->setCellValue('F' . $baris, $stok->stok_tanggal);

            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Stok');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok ' . date('Y-m-d H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $stoks = StokModel::with(['supplier', 'barang', 'user'])
            ->orderBy('stok_id')
            ->get();

        $pdf = Pdf::loadView('stok.export_pdf', ['stoks' => $stoks]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
