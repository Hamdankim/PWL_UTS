<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\AlatModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StokModelController extends Controller
{
    // Menampilkan halaman stok
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok stok dalam sistem'
        ];

        $activeMenu = 'stok';

        $alat = AlatModel::all();
        return view('stok.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'alat' => $alat,
            'activeMenu' => $activeMenu
        ]);
    }

    // Mengambil data stok dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $stoks = StokModel::select('stok_id', 'alat_id', 'jumlah_stok', 'jumlah_disewa')
            ->with('alat');

        // Filter berdasarkan alat_id jika ada
        if ($request->alat_id) {
            $stoks->where('alat_id', $request->alat_id);
        }

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url('stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Fungsi AJAX untuk menampilkan form tambah stok
    public function create_ajax()
    {
        $alat = AlatModel::select('alat_id', 'alat_nama')->get(); // Ambil data alat untuk ditampilkan di form
        return view('stok.create_ajax')
            ->with('alat', $alat);
    }

    // Fungsi AJAX untuk menyimpan stok baru
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'alat_id' => 'required|exists:alat_models,alat_id',
                'jumlah_stok' => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Set jumlah_disewa default ke 0 saat membuat stok baru
            $data = $request->all();
            $data['jumlah_disewa'] = 0;

            StokModel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);
        }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        // Ambil data stok berdasarkan ID dengan relasi
        $stok = StokModel::find($id);

        // Ambil data untuk dropdown
        $alat = AlatModel::all();

        // Perbaiki array yang dikirim ke view
        return view('stok.edit_ajax', [
            'stok' => $stok,
            'alat' => $alat
        ]);
    }

    // Fungsi AJAX untuk menyimpan perubahan data stok
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'alat_id' => 'required|exists:alat_models,alat_id',
                'jumlah_stok' => 'required|integer|min:1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $stok = StokModel::find($id);

            if ($stok) {
                $stok->update([
                    'alat_id' => $request->alat_id,
                    'jumlah_stok' => $request->jumlah_stok
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return redirect('/');
    }

    // Fungsi AJAX untuk menampilkan konfirmasi hapus stok
    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    // Fungsi AJAX untuk menghapus stok
    public function delete_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            // Cari stok berdasarkan ID
            $stok = StokModel::find($id);

            if ($stok) {
                // Hapus data stok
                $stok->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $stok = StokModel::with('alat')->find($id);
        return view('stok.show_ajax', [
            'stok' => $stok
        ]);
    }
}
