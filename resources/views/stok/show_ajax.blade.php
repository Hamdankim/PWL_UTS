@empty($stok)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Kesalahan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data stok tidak ditemukan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Detail Stok</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th width="30%">ID Stok</th>
                        <td>{{ $stok->stok_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Alat</th>
                        <td>{{ $stok->alat->alat_nama }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Stok</th>
                        <td>{{ $stok->jumlah_stok }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Disewa</th>
                        <td>{{ $stok->jumlah_disewa }}</td>
                    </tr>
                    <!-- Kolom tambahan jika ada -->
                    @if (isset($stok->created_at))
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $stok->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endif
                    @if (isset($stok->updated_at))
                        <tr>
                            <th>Diupdate Pada</th>
                            <td>{{ $stok->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@endempty
