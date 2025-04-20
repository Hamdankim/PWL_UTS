@empty($transaksi)
    <div class="modal-header bg-danger">
        <h5 class="modal-title text-white">Kesalahan</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5> Data transaksi tidak ditemukan.
        </div>
        <a href="{{ url('/transaksi') }}" class="btn btn-warning">Kembali</a>
    </div>
@else
    <form action="{{ url('/transaksi/' . $transaksi->transaksi_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')

        <div class="modal-header">
            <h5 class="modal-title">Edit Status Transaksi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <!-- Nama Penyewa (Read Only) -->
            <div class="form-group">
                <label>Nama Penyewa</label>
                <input type="text" class="form-control" value="{{ $transaksi->nama_penyewa }}" readonly>
            </div>

            <!-- Jenis Identitas (Read Only) -->
            <div class="form-group">
                <label>Jenis Identitas</label>
                <input type="text" class="form-control" value="{{ $transaksi->jenis_identitas }}" readonly>
            </div>

            <!-- Nomor Identitas (Read Only) -->
            <div class="form-group">
                <label>Nomor Identitas</label>
                <input type="text" class="form-control" value="{{ $transaksi->nomor_identitas }}" readonly>
            </div>

            <!-- Tanggal Mulai (Read Only) -->
            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" class="form-control" value="{{ date('Y-m-d', strtotime($transaksi->tanggal_mulai)) }}" readonly>
            </div>

            <!-- Tanggal Selesai (Read Only) -->
            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" class="form-control" value="{{ date('Y-m-d', strtotime($transaksi->tanggal_selesai)) }}" readonly>
            </div>

            <!-- Total Harga (Read Only) -->
            <div class="form-group">
                <label>Total Harga</label>
                <input type="text" class="form-control"
                    value="Rp {{ number_format($transaksi->total_harga, 2, ',', '.') }}" readonly>
            </div>

            <!-- Status (Editable) -->
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" {{ $transaksi->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="disetujui" {{ $transaksi->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="dibatalkan" {{ $transaksi->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                    </option>
                    <option value="selesai" {{ $transaksi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <small id="error-status" class="error-text form-text text-danger"></small>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    status: {
                        required: true
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataTransaksi.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
