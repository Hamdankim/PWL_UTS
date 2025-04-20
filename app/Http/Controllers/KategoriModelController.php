<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KategoriModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list'  => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        $kategori = KategoriModel::all(); // Ambil data kategori

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'kategori'   => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        // Filter berdasarkan kategori_id jika ada
        if ($request->kategori_id) {
            $kategoris->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($kategoris)
            ->addIndexColumn() // Menambahkan kolom index otomatis
            ->addColumn('aksi', function ($kategori) {
                $btn  = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi berisi HTML
            ->make(true);
    }

    // Fungsi AJAX untuk menampilkan form tambah kategori
    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_kode', 'kategori_nama')->get(); // Ambil data kategori untuk ditampilkan di form
        return view('kategori.create_ajax')
            ->with('kategori', $kategori);
    }

    // Fungsi AJAX untuk menyimpan kategori baru
    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa AJAX atau JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi data input
            $rules = [
                'kategori_kode' => 'required|string|min:6|unique:kategori_models,kategori_kode',
                'kategori_nama' => 'required|string|max:100'
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
            KategoriModel::create($request->all());

            // Kirim respon sukses
            return response()->json([
                'status'  => true,
                'message' => 'Data kategori berhasil disimpan',
            ]);
        }

        // Redirect ke halaman utama jika bukan request AJAX
        return redirect('/');
    }

    // Fungsi AJAX untuk menampilkan form edit kategori
    public function edit_ajax(string $id)
    {
        // Ambil data kategori berdasarkan ID
        $kategori = KategoriModel::find($id);

        // Kirim data ke view edit_ajax
        return view('kategori.edit_ajax', [
            'kategori'  => $kategori,
        ]);
    }

    // Fungsi AJAX untuk menyimpan perubahan data kategori
    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            // Aturan validasi
            $rules = [
                'kategori_kode' => 'required|string|min:6|unique:kategori_models,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|max:100'
            ];

            // Validasi request
            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal, kirim respon JSON
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false, // Respon JSON: true = berhasil, false = gagal
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(), // Menunjukkan field yang error
                ]);
            }

            // Cari kategori berdasarkan ID
            $kategori = KategoriModel::find($id);

            if ($kategori) {
                // Update data kategori
                $kategori->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate',
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }

        return redirect('/');
    }

    // Fungsi AJAX untuk menampilkan konfirmasi hapus kategori
    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    // Fungsi AJAX untuk menghapus kategori
    public function delete_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            // Cari kategori berdasarkan ID
            $kategori = KategoriModel::find($id);

            if ($kategori) {
                // Hapus data kategori
                $kategori->delete();

                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus',
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }

        return redirect('/');
    }

    /**
     * Fungsi AJAX untuk menampilkan detail kategori
     */
    public function show_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);

        return view('kategori.show_ajax', [
            'kategori' => $kategori
        ]);
    }
}
