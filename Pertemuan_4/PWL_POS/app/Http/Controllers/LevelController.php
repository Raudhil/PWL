<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelController extends Controller
{

    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level pengguna dalam sistem'
        ];

        $activeMenu = 'level';

        $levels = Level::all();

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'levels' => $levels, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $levels = Level::select('level_id', 'level_kode', 'level_nama');

        // Filter data level berdasarkan level_id (jika ada)
        if ($request->level_id) {
            $levels->where('level_id', $request->level_id);
        }

        return DataTables::of($levels)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($level) {
                $btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // Agar tombol HTML bisa ditampilkan
            ->make(true);
    }


    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah level baru'
        ];

        $activeMenu = 'level';

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }


    public function edit(string $id)
    {
        $level = Level::find($id);

        if (!$level) {
            return redirect('/level')->with('error', 'Level tidak ditemukan');
        }

        $breadcrumb = (object)[
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Edit Level'
        ];

        $activeMenu = 'level';

        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required|string|min:2|max:10|unique:m_level,level_kode,' . $id . ',level_id',
            'level_nama' => 'required|string|max:100',
        ]);

        Level::where('level_id', $id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
            'updated_at' => now(),
        ]);

        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    public function destroy(string $id)
    {
        // Cek apakah data level dengan ID tersebut ada
        $level = Level::find($id);
        if (!$level) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            Level::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini.');
        }
    }

    public function create_ajax()
    {
        $levels = LevelModel::select('level_id', 'level_nama')->get();

        return view('level.create_ajax')->with('levels', $levels);
    }


    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            LevelModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan'
            ]);
        }
        return redirect('/level');
    }

    public function edit_ajax(String $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $level = LevelModel::find($id);

            if ($level) {
                $level->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data level berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/level');
    }

    public function confirm_ajax(String $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }

    public function delete_ajax($id)
    {
        try {
            $user = LevelModel::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'Level berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'status' => false,
                    'message' => 'Level tidak bisa dihapus karena memiliki data terkait'
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menghapus Level'
            ]);
        }
    }

    public function import()
    {
        return view('level.import'); // ubah sesuai view untuk import user
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_level' => ['required', 'mimes:xlsx', 'max:1024'] // Validasi file Excel, max 1MB
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
                $file = $request->file('file_level');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true); // Ambil data per kolom dengan huruf

                $insert = [];
                if (count($data) > 1) {
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) { // Lewati header
                            // Validasi isi kolom A, B, C
                            if (empty($value['A']) || empty($value['B']) || empty($value['C'])) {
                                throw new \Exception("Data tidak lengkap pada baris $baris.");
                            }

                            // Validasi level_id harus angka
                            if (!is_numeric($value['A'])) {
                                throw new \Exception("Level ID pada baris $baris harus berupa angka.");
                            }

                            // Cek duplikat berdasarkan level_id atau level_kode
                            $exists = LevelModel::where('level_id', $value['A'])
                                ->orWhere('level_kode', $value['B'])
                                ->exists();
                            if(!$exists) {
                                $insert[] = [
                                    'level_id' => $value['A'],
                                    'level_kode' => $value['B'],
                                    'level_nama' => $value['C'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                                
                            }

                        }
                    }

                    if (count($insert) > 0) {
                        $inserted = LevelModel::insert($insert);

                        return response()->json([
                            'status' => true,
                            'message' => "Data level berhasil diimport (" . count($insert) . " baris)"
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => 'Tidak ada data yang diimport'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'File Excel kosong atau tidak memiliki data'
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Import level gagal', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengimpor data level: ' . $e->getMessage()
                ], 500);
            }
        }

        return redirect('/level');
    }


    public function export_excel()
    {
        $levels = Level::select('level_id', 'level_kode', 'level_nama')
            ->orderBy('level_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Level ID');
        $sheet->setCellValue('C1', 'Kode Level');
        $sheet->setCellValue('D1', 'Nama Level');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // bold header

        $no = 1;
        $baris = 2;

        foreach ($levels as $level) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $level->level_id);
            $sheet->setCellValue('C' . $baris, $level->level_kode);
            $sheet->setCellValue('D' . $baris, $level->level_nama);

            $baris++;
            $no++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Level');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Level ' . date('Y-m-d H-i-s') . '.xlsx';

        // Output ke browser
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
        $level = Level::select('level_id', 'level_kode', 'level_nama')
            ->orderBy('level_id')
            ->get();

        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Level ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
