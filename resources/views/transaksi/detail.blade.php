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

    <h6>Detail Alat yang Disewa</h6>
    <table class="table table-bordered table-striped" id="table-detail">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Jumlah</th>
                <th>Harga Sewa/Hari</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->detailTransaksi as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->alat->alat_nama }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>Rp {{ number_format($detail->harga_sewa_saat_ini, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total Harga:</th>
                <th>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>
