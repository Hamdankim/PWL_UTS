@empty($alat)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Kesalahan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data alat tidak ditemukan.
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
                <h5 class="modal-title text-white">Detail Alat</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th width="30%">ID Alat</th>
                        <td>{{ $alat->alat_id }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Alat :</th>
                        <td>{{ $alat->kategori->kategori_nama }}</td>
                    </tr>
                    <tr>
                        <th>Kode Alat</th>
                        <td>{{ $alat->alat_kode }}</td>
                    </tr>
                    <tr>
                        <th>Nama Alat</th>
                        <td>{{ $alat->alat_nama }}</td>
                    </tr>
                    <tr>
                        <th>Harga Sewa</th>
                        <td>{{ $alat->harga_sewa }}</td>
                    </tr>
                    <!-- Kolom tambahan jika ada -->
                    @if (isset($alat->created_at))
                        <tr>
                            <th>Dibuat Pada</th>
                            <td>{{ $alat->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endif
                    @if (isset($alat->updated_at))
                        <tr>
                            <th>Diupdate Pada</th>
                            <td>{{ $alat->updated_at->format('d/m/Y H:i') }}</td>
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
