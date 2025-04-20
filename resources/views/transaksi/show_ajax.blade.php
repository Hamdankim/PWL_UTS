@empty($transaksi)
    <div class="modal-header bg-danger">
        <h5 class="modal-title text-white">Kesalahan</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <i class="icon fas fa-ban"></i> Data transaksi tidak ditemukan
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
@else
    <div class="modal-header">
        <h5 class="modal-title">Detail Transaksi #{{ $transaksi->transaksi_id }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Informasi Penyewa</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Penyewa</th>
                        <td>{{ $transaksi->nama_penyewa }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Identitas</th>
                        <td>{{ $transaksi->jenis_identitas }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Identitas</th>
                        <td>{{ $transaksi->nomor_identitas }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Informasi Transaksi</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_mulai)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_selesai)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Durasi (Hari)</th>
                        <td>{{ $transaksi->durasi_hari }}</td>
                    </tr>
                    <tr>
                        <th>Total Harga</th>
                        <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span
                                class="badge badge-{{ $transaksi->status == 'pending' ? 'warning' : ($transaksi->status == 'disetujui' ? 'success' : ($transaksi->status == 'dibatalkan' ? 'danger' : 'info')) }}">
                                {{ ucfirst($transaksi->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="text-center mt-3">
            <button type="button" class="btn btn-info"
                onclick="modalAction('{{ url('/transaksi/' . $transaksi->transaksi_id . '/detail') }}')">
                Lihat Detail Alat yang Disewa
            </button>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
@endempty
