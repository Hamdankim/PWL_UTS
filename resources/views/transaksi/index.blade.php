@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('transaksi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">
                    <i class="fas fa-plus"></i> Tambah Transaksi
                </button>
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
                        <label class="col-1 control-label col-form-label">Filter Status:</label>
                        <div class="col-3">
                            <select class="form-control" id="status_filter" name="status_filter">
                                <option value="">- Semua Status -</option>
                                <option value="pending">Pending</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="dibatalkan">Dibatalkan</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_transaksi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penyewa</th>
                        <th>Durasi</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .badge {
            font-size: 0.9em;
        }
    </style>
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            console.log('Loading modal from:', url); // Debug log

            // Check if jQuery is loaded
            if (typeof jQuery == 'undefined') {
                console.error('jQuery is not loaded');
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'jQuery tidak terdeteksi'
                });
                return;
            }

            // Check if Bootstrap modal is available
            if (typeof $.fn.modal == 'undefined') {
                console.error('Bootstrap modal is not loaded');
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Bootstrap modal tidak terdeteksi'
                });
                return;
            }

            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    console.log('Modal content loaded:', data); // Debug log
                    $('#myModal .modal-content').html(data);
                    $('#myModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error loading modal:', error); // Debug log
                    console.error('XHR Status:', status);
                    console.error('XHR Response:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal memuat form transaksi: ' + error
                    });
                }
            });
        }

        var dataTransaksi;
        $(document).ready(function() {
            dataTransaksi = $('#table_transaksi').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('transaksi/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function(d) {
                        d.status = $('#status_filter').val();
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_penyewa",
                        className: "text-left",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "durasi_hari",
                        className: "text-left",
                        orderable: true,
                        searchable: false,
                        render: function(data) {
                            return data + ' hari';
                        }
                    },
                    {
                        data: "total_harga",
                        className: "text-left",
                        orderable: true,
                        searchable: false,
                        render: function(data) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 2
                            }).format(data);
                        }
                    },
                    {
                        data: "status",
                        className: "text-left",
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            let badge = '';
                            switch (data) {
                                case 'pending':
                                    badge = 'warning';
                                    break;
                                case 'disetujui':
                                    badge = 'success';
                                    break;
                                case 'dibatalkan':
                                    badge = 'danger';
                                    break;
                                case 'selesai':
                                    badge = 'info';
                                    break;
                                default:
                                    badge = 'secondary';
                            }
                            return `<span class="badge badge-${badge}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }
                    },
                    {
                        data: "aksi",
                        className: "text-left",
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'desc']
                ] // Sort by tanggal_mulai descending
            });

            // Handle status filter change
            $('#status_filter').on('change', function() {
                dataTransaksi.ajax.reload();
            });
        });
    </script>
@endpush
