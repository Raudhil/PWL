<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        $users = UserModel::all();

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'users' => $users,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
            ->with(['user']);

        // Filter data penjualan
        if ($request->user_id) {
            $penjualans->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) {
                $btn = '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->editColumn('penjualan_tanggal', function ($penjualan) {
                return $penjualan->penjualan_tanggal ? $penjualan->penjualan_tanggal->format('Y-m-d H:i:s') : '-';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Penjualan',
            'list' => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah penjualan baru'
        ];

        $users = UserModel::all();
        $activeMenu = 'penjualan';

        return view('penjualan.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'users' => $users,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:50|unique:t_penjualan,penjualan_kode'
        ]);

        PenjualanModel::create([
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_kode' => $request->penjualan_kode,
            'penjualan_tanggal' => now()
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }

    public function show($id)
    {
        $penjualan = PenjualanModel::with(['user', 'details.barang'])->findOrFail($id);

        return view('penjualan.show', compact('penjualan'));
    }


    public function edit(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $users = UserModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Penjualan',
            'list' => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'penjualan' => $penjualan,
            'users' => $users,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'pembeli' => 'required|string|max:100',
            'penjualan_kode' => 'required|string|max:50|unique:m_penjualan,penjualan_kode,' . $id . ',penjualan_id'
        ]);

        PenjualanModel::where('penjualan_id', $id)->update([
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_kode' => $request->penjualan_kode
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = PenjualanModel::find($id);
        if (!$check) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            PenjualanModel::destroy($id);
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/penjualan')->with('error', 'Data penjualan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.');
        }
    }

    public function create_ajax()
    {
        $users = UserModel::all();

        return view('penjualan.create_ajax', [
            'users' => $users
        ]);
    }



    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer|exists:users,id',
                'pembeli' => 'required|string|max:100',
                'penjualan_kode' => 'required|string|max:50|unique:m_penjualan,penjualan_kode',
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
                $penjualan = PenjualanModel::create([
                    'user_id' => $request->user_id,
                    'pembeli' => $request->pembeli,
                    'penjualan_kode' => $request->penjualan_kode,
                    'penjualan_tanggal' => now()
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil disimpan',
                    'data' => $penjualan
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal simpan penjualan: ' . $e->getMessage());
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        }

        return redirect('/');
    }



    public function edit_ajax(String $id)
    {
        $penjualan = PenjualanModel::find($id);
        $users = UserModel::all();

        return view('penjualan.edit_ajax', [
            'penjualan' => $penjualan,
            'users' => $users
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'user_id' => 'required|integer',
                'pembeli' => 'required|string|max:100',
                'penjualan_kode' => 'required|string|max:50|unique:m_penjualan,penjualan_kode,' . $id . ',penjualan_id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $penjualan = PenjualanModel::find($id);

            if ($penjualan) {
                $penjualan->update([
                    'user_id' => $request->user_id,
                    'pembeli' => $request->pembeli,
                    'penjualan_kode' => $request->penjualan_kode
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data penjualan berhasil diupdate'
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
        $penjualan = PenjualanModel::find($id);

        return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
    }

    public function delete_ajax($id)
    {
        try {
            $penjualan = PenjualanModel::findOrFail($id);
            $penjualan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Penjualan berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'status' => false,
                    'message' => 'Penjualan tidak bisa dihapus karena memiliki data terkait'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus penjualan'
            ]);
        }
    }

    public function import()
    {
        return view('penjualan.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024']
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
                $file = $request->file('file_penjualan');
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
                            if (empty($value['A']) || empty($value['B']) || empty($value['C'])) {
                                throw new \Exception("Data pada baris $baris tidak lengkap.");
                            }

                            // Validasi user
                            if (!UserModel::find($value['A'])) {
                                throw new \Exception("User dengan ID {$value['A']} pada baris $baris tidak ditemukan.");
                            }

                            $insert[] = [
                                'user_id' => $value['A'],
                                'pembeli' => $value['B'],
                                'penjualan_kode' => $value['C'],
                                'penjualan_tanggal' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        $insertedCount = PenjualanModel::insertOrIgnore($insert);
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
        return redirect('/penjualan');
    }

    public function export_excel()
    {
        $penjualans = PenjualanModel::with(['user'])
            ->orderBy('penjualan_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'User');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Kode Penjualan');
        $sheet->setCellValue('E1', 'Tanggal Penjualan');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;

        foreach ($penjualans as $penjualan) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $penjualan->user->nama ?? '-');
            $sheet->setCellValue('C' . $baris, $penjualan->pembeli);
            $sheet->setCellValue('D' . $baris, $penjualan->penjualan_kode);
            $sheet->setCellValue('E' . $baris, $penjualan->penjualan_tanggal);

            $baris++;
            $no++;
        }

        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan ' . date('Y-m-d H-i-s') . '.xlsx';

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
        $penjualans = PenjualanModel::with(['user'])
            ->orderBy('penjualan_id')
            ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['penjualans' => $penjualans]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Penjualan ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
