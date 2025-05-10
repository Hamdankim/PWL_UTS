@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/alat/import') }}')" class="btn btn-info">Import Alat</button>
<a href="{{ url('/alat/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Alat</a>
            <a href="{{ url('/alat/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Alat</a>
            <button onclick="modalAction('{{ url('/alat/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter -->
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="kategori_id" class="col-md-1 col-form-label">Filter</label>
                        <div class="col-md-3">
                            <select name="kategori_id" id="kategori_id" class="form-control form-control-sm">
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
        </div>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabel Alat -->
        <table class="table table-bordered table-sm table-striped table-hover" id="table_alat">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Alat</th>
                    <th>Kategori Alat</th>
                    <th>Nama Alat</th>
                    <th>Harga Sewa/Hari</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var tableAlat;
    $(document).ready(function () {
        tableAlat = $('#table_alat').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('alat/list') }}",
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
                    data: "alat_kode",
                    className: "text-left"
                },
                {
                    data: "kategori.kategori_nama",
                    className: "text-left"
                },
                {
                    data: "alat_nama",
                    className: "text-left"
                },
                {
                    data: "harga_sewa",
                    className: "text-left",
                    render: function (data) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(data);
                    }
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#table_alat_filter input').unbind().bind().on('keyup', function (e) {
            if (e.keyCode == 13) {
                tableAlat.search(this.value).draw();
            }
        });

        $('#kategori_id').change(function () {
            tableAlat.draw();
        });
    });
</script>
@endpush
