<div class="modal-header">
    <h5 class="modal-title" id="modalLabel">Tambah Data Transaksi</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form action="{{ route('transaksi.store_ajax') }}" method="POST" id="form-tambah-transaksi">
        @csrf
        <div class="form-group">
            <label>Nama Penyewa</label>
            <input type="text" name="nama_penyewa" id="nama_penyewa" class="form-control" required>
            <small id="error-nama_penyewa" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Jenis Identitas</label>
            <select name="jenis_identitas" id="jenis_identitas" class="form-control" required>
                <option value="">- Pilih Jenis Identitas -</option>
                <option value="KTP">KTP</option>
                <option value="SIM">SIM</option>
                <option value="Paspor">Paspor</option>
            </select>
            <small id="error-jenis_identitas" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Nomor Identitas</label>
            <input type="text" name="nomor_identitas" id="nomor_identitas" class="form-control" required>
            <small id="error-nomor_identitas" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
            <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
        </div>
        <div class="form-group">
            <label>Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
            <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
        </div>

        <!-- Item Selection Section -->
        <div class="form-group">
            <label>Pilih Alat yang Disewa</label>
            <div id="item-selection">
                <div class="row mb-2">
                    <div class="col-md-5">
                        <select class="form-control alat-select" name="alat_id[]" required>
                            <option value="">- Pilih Alat -</option>
                            @foreach ($alat as $item)
                                <option value="{{ $item->alat_id }}" data-harga="{{ $item->harga_sewa }}">
                                    {{ $item->alat_nama }} (Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}/hari)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control jumlah-input" name="jumlah[]" min="1"
                            placeholder="Jumlah" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control subtotal-input" readonly placeholder="Subtotal">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-item">×</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-success mt-2" id="add-item">+ Tambah Alat</button>
        </div>

        <div class="form-group">
            <label>Total Harga</label>
            <input type="number" step="0.01" name="total_harga" id="total_harga" class="form-control" readonly
                required>
            <small id="error-total_harga" class="error-text form-text text-danger"></small>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
    <button type="submit" form="form-tambah-transaksi" class="btn btn-primary">Simpan</button>
</div>

<script>
    $(document).ready(function() {
        // Add new item row
        $('#add-item').click(function() {
            var newRow = `
                <div class="row mb-2">
                    <div class="col-md-5">
                        <select class="form-control alat-select" name="alat_id[]" required>
                            <option value="">- Pilih Alat -</option>
                            @foreach ($alat as $item)
                                <option value="{{ $item->alat_id }}" data-harga="{{ $item->harga_sewa }}">{{ $item->alat_nama }} (Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}/hari)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control jumlah-input" name="jumlah[]" min="1" placeholder="Jumlah" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control subtotal-input" readonly placeholder="Subtotal">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-item">×</button>
                    </div>
                </div>
            `;
            $('#item-selection').append(newRow);
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.row').remove();
            calculateTotal();
        });

        // Calculate subtotal and total when inputs change
        $(document).on('change', '.alat-select, .jumlah-input, #tanggal_mulai, #tanggal_selesai', function() {
            calculateSubtotal($(this).closest('.row'));
            calculateTotal();
        });

        function calculateSubtotal(row) {
            var alatSelect = row.find('.alat-select');
            var jumlahInput = row.find('.jumlah-input');
            var subtotalInput = row.find('.subtotal-input');

            var harga = parseFloat(alatSelect.find('option:selected').data('harga')) || 0;
            var jumlah = parseFloat(jumlahInput.val()) || 0;
            var durasi = calculateDuration();

            var subtotal = harga * jumlah * durasi;
            subtotalInput.val(formatRupiah(subtotal));
        }

        function calculateDuration() {
            var startDate = new Date($('#tanggal_mulai').val());
            var endDate = new Date($('#tanggal_selesai').val());
            if (startDate && endDate) {
                var diffTime = Math.abs(endDate - startDate);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                return diffDays;
            }
            return 0;
        }

        function calculateTotal() {
            var total = 0;
            $('.subtotal-input').each(function() {
                var subtotal = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
                total += subtotal;
            });
            $('#total_harga').val(total);
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Form submission
        $("#form-tambah-transaksi").on('submit', function(e) {
            e.preventDefault();

            // Validate form
            if (!this.checkValidity()) {
                $(this).addClass('was-validated');
                return;
            }

            var formData = new FormData(this);
            var detailTransaksi = [];

            // Collect detail transaksi data
            $('.row.mb-2').each(function() {
                var alatSelect = $(this).find('.alat-select');
                var jumlahInput = $(this).find('.jumlah-input');

                if (alatSelect.val() && jumlahInput.val()) {
                    detailTransaksi.push({
                        alat_id: alatSelect.val(),
                        jumlah: parseInt(jumlahInput.val()),
                        harga_sewa_saat_ini: parseFloat(alatSelect.find(
                            'option:selected').data('harga')),
                        subtotal: parseFloat($(this).find('.subtotal-input').val()
                            .replace(/[^0-9]/g, ''))
                    });
                }
            });

            if (detailTransaksi.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Pilih minimal satu alat yang akan disewa'
                });
                return;
            }

            formData.append('detail_transaksi', JSON.stringify(detailTransaksi));

            // Get CSRF token from meta tag
            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                beforeSend: function() {
                    // Show loading state
                    $('button[type="submit"]').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                },
                success: function(response) {
                    console.log('Success response:', response);
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Transaksi berhasil disimpan'
                    });
                    $('#table_transaksi').DataTable().ajax.reload(null, false);
                },
                error: function(xhr, status, error) {
                    console.error('Error response:', xhr.responseText);
                    console.error('Status:', status);
                    console.error('Error:', error);

                    var res = xhr.responseJSON;
                    if (res && res.errors) {
                        $('.error-text').text(''); // Reset error text
                        $.each(res.errors, function(key, value) {
                            $('#error-' + key).text(value[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res?.message ||
                                'Terjadi kesalahan saat menyimpan transaksi'
                        });
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    $('button[type="submit"]').prop('disabled', false).html('Simpan');
                }
            });
        });

        // Add event listeners for date changes
        $('#tanggal_mulai, #tanggal_selesai').on('change', function() {
            var startDate = new Date($('#tanggal_mulai').val());
            var endDate = new Date($('#tanggal_selesai').val());

            if (startDate && endDate) {
                if (startDate > endDate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai'
                    });
                    $(this).val('');
                    return;
                }

                // Recalculate all subtotals
                $('.row.mb-2').each(function() {
                    calculateSubtotal($(this));
                });
                calculateTotal();
            }
        });
    });
</script>
