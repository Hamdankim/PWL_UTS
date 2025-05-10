@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                {{-- Tombol Import Kategori (opsional) --}}
                <button onclick="modalAction('{{ url('kategori/import') }}')" class="btn btn-sm btn-info mt-1">Import Kategori</button>

                {{-- Tombol Tambah Kategori --}}
                <button onclick="modalAction('{{ url('kategori/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Kategori</button>
            </div>
        </div>

        <div class="card-body">
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Filter --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_id" name="kategori_id">
                                <option value="">- Semua -</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Kategori --}}
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Modal --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var dataKategori;
    $(document).ready(function () {
        dataKategori = $('#table_kategori').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('kategori/list') }}",
                type: "POST",
                dataType: "json",
                data: function (d) {
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "kategori_kode",
                    className: "text-left"
                },
                {
                    data: "kategori_nama",
                    className: "text-left"
                },
                {
                    data: "aksi",
                    className: "text-left",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Filter saat dropdown berubah
        $('#kategori_id').on('change', function () {
            dataKategori.draw();
        });

        // Pencarian tekan enter
        $('#table_kategori_filter input').unbind().bind().on('keyup', function (e) {
            if (e.keyCode == 13) {
                dataKategori.search(this.value).draw();
            }
        });
    });
</script>
@endpush
