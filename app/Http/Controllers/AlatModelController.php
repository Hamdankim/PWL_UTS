<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlatModel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Validator;

class AlatModelController extends Controller
{
    // Menampilkan halaman utama alat
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Alat',
            'list'  => ['Home', 'Alat']
        ];

        $page = (object) [
            'title' => 'Daftar alat yang terdaftar dalam sistem'
        ];

        $activeMenu = 'alat'; // Set menu yang sedang aktif

        $kategori = KategoriModel::all(); // Ambil data kategori untuk filter kategori

        return view('alat.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'kategori'   => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Mengambil data alat dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $alats = AlatModel::select('alat_id', 'alat_kode', 'alat_nama', 'harga_sewa', 'kategori_id')
            ->with('kategori');

        // Filter berdasarkan kategori_id jika ada
        if ($request->kategori_id) {
            $alats->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($alats)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($alat) {
                $btn  = '<button onclick="modalAction(\'' . url('/alat/' . $alat->alat_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/alat/' . $alat->alat_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/alat/' . $alat->alat_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi berisi HTML
            ->make(true);
    }

    // Fungsi AJAX untuk menampilkan form tambah alat
    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get(); // Ambil data kategori untuk ditampilkan di form
        return view('alat.create_ajax')
            ->with('kategori', $kategori);
    }

    // Fungsi AJAX untuk menyimpan alat baru
    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi data input
            $rules = [
                'alat_kode'  => 'required|string|min:6|unique:alat_models,alat_kode', // Alat kode harus diisi, minimal 3 karakter, dan unik
                'alat_nama'  => 'required|string|max:100', // Nama alat harus diisi, berupa string, maksimal 100 karakter
                'harga_sewa'   => 'required|numeric|min:0', // Harga beli harus diisi, berupa angka, dan minimal 0
                'kategori_id'  => 'required|integer|exists:kategori_models,kategori_id' // Kategori ID harus diisi, berupa angka, dan harus ada di tabel kategori
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal, kirim respon JSON dengan error
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false, // false menunjukkan validasi gagal
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // Pesan error validasi
                ]);
            }

            // Simpan data ke database
            AlatModel::create($request->all());

            // Kirim respon sukses
            return response()->json([
                'status'  => true,
                'message' => 'Data alat berhasil disimpan',
            ]);
        }

        // Redirect ke halaman utama jika bukan request AJAX
        return redirect('/');
    }

    public function edit_ajax($id)
    {
        $alat = AlatModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('alat.edit_ajax', [
            'alat' => $alat,
            'kategori' => $kategori
        ]);
    }


    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'alat_kode'  => 'required|string|min:6|unique:alat_models,alat_kode,' . $id . ',alat_id',
                'alat_nama'  => 'required|string|max:100',
                'harga_sewa' => 'required|numeric|min:0',
                'kategori_id' => 'required|integer|exists:kategori_models,kategori_id'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $alat = AlatModel::find($id);
            if ($alat) {
                $alat->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }


    public function confirm_ajax($id)
    {
        $alat = AlatModel::find($id);
        return view('alat.confirm_ajax', ['alat' => $alat]);
    }


    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $alat = AlatModel::find($id);
            if ($alat) {
                $alat->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/');
    }


    public function show_ajax(string $id)
    {
        $alat = AlatModel::with('kategori')->find($id);
        return view('alat.show_ajax', [
            'alat' => $alat
        ]);
    }

    public function import()
    {
        return view('alat.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_alat' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_alat');
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $index => $row) {
                    if ($index > 1) {
                        $insert[] = [
                            'kategori_id' => $row['A'],
                            'alat_kode'   => $row['B'],
                            'alat_nama'   => $row['C'],
                            'harga_sewa'  => $row['D'],
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert)) {
                    AlatModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }

        return redirect('/');
    }
}
