@empty($alat) <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5> <button type="button" class="close"
                    data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5> Data yang anda cari tidak ditemukan
                </div> <a href="{{ url('/alat') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/alat/' . $alat->alat_id . '/update_ajax') }}" method="POST" id="form-edit"> @csrf
        @method('PUT') <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Alat</h5> <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group"> <label>Kode Alat</label> <input value="{{ $alat->alat_kode }}" type="text"
                            name="alat_kode" id="alat_kode" class="form-control" required> <small id="error-alat_kode"
                            class="error-text form-text text-danger"></small> </div>
                    <div class="form-group"> <label>Kategori Alat</label> <select name="kategori_id" id="kategori_id"
                            class="form-control" required>
                            <option value="">- Pilih Kategori -</option>
                            @foreach ($kategori as $l)
                                <option {{ $l->kategori_id == $alat->kategori_id ? 'selected' : '' }}
                                    value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                            @endforeach
                        </select> <small id="error-kategori_id" class="error-text form-text text-danger"></small> </div>
                    <div class="form-group"> <label>Nama Alat</label> <input value="{{ $alat->alat_nama }}" type="text"
                            name="alat_nama" id="alat_nama" class="form-control" required> <small id="error-alat_nama"
                            class="error-text form-text text-danger"></small> </div>
                    <div class="form-group"> <label>Harga Sewa</label> <input value="{{ $alat->harga_sewa }}" type="text"
                            name="harga_sewa" id="harga_sewa" class="form-control" required> <small id="error-harga_sewa"
                            class="error-text form-text text-danger"></small> </div>
                </div>
                <div class="modal-footer"> <button type="button" data-dismiss="modal"
                        class="btn btn-warning">Batal</button> <button type="submit"
                        class="btn btn-primary">Simpan</button> </div>
            </div>
        </div>
    </form>
<script>
    $(document).ready(function() {
        $("#form-edit").validate({
            rules: {
                kategori_id: {
                    required: true,
                    number: true
                },
                alat_kode: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                alat_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                harga_sewa: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
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
                            dataAlat.ajax.reload();
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
</script> @endempty
