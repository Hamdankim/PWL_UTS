@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('alat/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah
                    Alat</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_id" name="kategori_id" required>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_alat">
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
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataAlat;
        $(document).ready(function() {
            dataAlat = $('#table_alat').DataTable({
                serverSide: true, // Jika ingin menggunakan server-side processing
                ajax: {
                    url: "{{ url('alat/list') }}",
                    dataType: "json",
                    type: "POST",
                    "data": function(d) {
                        d.kategori_id = $('#kategori_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "alat_kode",
                        className: "text-left",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori.kategori_nama",
                        className: "text-left",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "alat_nama",
                        className: "text-left",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "harga_sewa",
                        className: "text-left",
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(data);
                        }
                    },
                    {
                        data: "aksi",
                        className: "text-left",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#kategori_id').on('change', function() {
                dataAlat.ajax.reload();
            });
        });
    </script>
@endpush
