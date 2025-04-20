@empty($transaksi)
    <div class="modal-header bg-danger">
        <h5 class="modal-title text-white">Kesalahan</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data yang anda cari tidak ditemukan
        </div>
        <a href="{{ url('/transaksi') }}" class="btn btn-warning">Kembali</a>
    </div>
@else
    <form action="{{ url('/transaksi/' . $transaksi->transaksi_id . '/delete_ajax') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div class="modal-header">
            <h5 class="modal-title">Hapus Data Transaksi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                Apakah Anda yakin ingin menghapus data berikut?
            </div>
            <table class="table table-sm table-bordered table-striped">
                <tr>
                    <th class="text-right col-3">ID Transaksi:</th>
                    <td class="col-9">{{ $transaksi->transaksi_id }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nama Penyewa:</th>
                    <td class="col-9">{{ $transaksi->nama_penyewa }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jenis Identitas:</th>
                    <td class="col-9">{{ $transaksi->jenis_identitas }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Nomor Identitas:</th>
                    <td class="col-9">{{ $transaksi->nomor_identitas }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Mulai:</th>
                    <td class="col-9">{{ $transaksi->tanggal_mulai }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Selesai:</th>
                    <td class="col-9">{{ $transaksi->tanggal_selesai }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Durasi (Hari):</th>
                    <td class="col-9">{{ $transaksi->durasi_hari }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Total Harga:</th>
                    <td class="col-9">Rp {{ number_format($transaksi->total_harga, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Status:</th>
                    <td class="col-9">{{ ucfirst($transaksi->status) }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
            <button type="submit" class="btn btn-primary">Ya, Hapus</button>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $("#form-delete").validate({
                rules: {},
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
