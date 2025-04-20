<?php

namespace App\Http\Controllers;

use App\Models\TransaksiModel;
use App\Models\AlatModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiModelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Transaksi',
            'list' => ['Home', 'Transaksi']
        ];

        $page = (object) [
            'title' => 'Daftar transaksi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'transaksi';
        $alat = AlatModel::all();

        return view('transaksi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'alat' => $alat,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $query = TransaksiModel::query();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($transaksi) {
                $btn = '<button onclick="modalAction(\'' . url('/transaksi/' . $transaksi->transaksi_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/transaksi/' . $transaksi->transaksi_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/transaksi/' . $transaksi->transaksi_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $alat = AlatModel::all();
        return view('transaksi.create_ajax', ['alat' => $alat]);
    }

    public function store_ajax(Request $request)
    {
        $rules = [
            'nama_penyewa' => 'required|string|max:100',
            'jenis_identitas' => 'required|in:KTP,SIM,Paspor',
            'nomor_identitas' => 'required|string|max:50',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'total_harga' => 'required|numeric|min:0',
            'detail_transaksi' => 'required|json',
            'status' => 'nullable|in:pending,disetujui,dibatalkan,selesai'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $detailTransaksi = json_decode($request->detail_transaksi, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'status' => false,
                'message' => 'Format detail transaksi tidak valid',
            ], 422);
        }

        $durasi = Carbon::parse($request->tanggal_mulai)
            ->diffInDays(Carbon::parse($request->tanggal_selesai)) + 1;

        DB::beginTransaction();
        try {
            $transaksi = TransaksiModel::create([
                'nama_penyewa' => $request->nama_penyewa,
                'jenis_identitas' => $request->jenis_identitas,
                'nomor_identitas' => $request->nomor_identitas,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'durasi_hari' => $durasi,
                'total_harga' => $request->total_harga,
                'status' => $request->status ?? 'pending',
            ]);

            foreach ($detailTransaksi as $detail) {
                DB::table('detail_transaksis')->insert([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'alat_id' => $detail['alat_id'],
                    'jumlah' => $detail['jumlah'],
                    'harga_sewa_saat_ini' => $detail['harga_sewa_saat_ini'],
                    'subtotal' => $detail['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                DB::table('stok_models')
                    ->where('alat_id', $detail['alat_id'])
                    ->increment('jumlah_disewa', $detail['jumlah']);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data transaksi berhasil disimpan',
                'data' => $transaksi
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit_ajax(string $id)
    {
        $transaksi = TransaksiModel::find($id);

        return view('transaksi.edit_ajax', [
            'transaksi' => $transaksi,
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'status' => 'required|in:pending,disetujui,dibatalkan,selesai'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $transaksi = TransaksiModel::find($id);

            if ($transaksi) {
                $transaksi->update([
                    'status' => $request->status
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Status transaksi berhasil diupdate',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $transaksi = TransaksiModel::find($id);
        return view('transaksi.confirm_ajax', ['transaksi' => $transaksi]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $transaksi = TransaksiModel::find($id);

            if ($transaksi) {
                $transaksi->delete();

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
        $transaksi = TransaksiModel::with('detailTransaksi.alat')->find($id);
        return view('transaksi.show_ajax', [
            'transaksi' => $transaksi
        ]);
    }

    public function showDetail(string $id)
    {
        $transaksi = TransaksiModel::with('detailTransaksi.alat')->find($id);

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Data transaksi tidak ditemukan'
            ]);
        }

        return view('transaksi.detail', [
            'transaksi' => $transaksi
        ]);
    }

    public function listDetail(Request $request, string $id)
    {
        $transaksi = TransaksiModel::with('detailTransaksi.alat')->find($id);

        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Data transaksi tidak ditemukan'
            ]);
        }

        return DataTables::of($transaksi->detailTransaksi)
            ->addIndexColumn()
            ->addColumn('nama_alat', function ($detail) {
                return $detail->alat->alat_nama;
            })
            ->addColumn('harga_sewa', function ($detail) {
                return 'Rp ' . number_format($detail->harga_sewa_saat_ini, 0, ',', '.');
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp ' . number_format($detail->subtotal, 0, ',', '.');
            })
            ->rawColumns(['nama_alat', 'harga_sewa', 'subtotal'])
            ->make(true);
    }
}
