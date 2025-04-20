@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center"
            style="background-color: #2E8B57; color: #fff;">
            <h3 class="card-title m-0">📋 Dashboard FomoId</h3>
            <div class="card-tools" id="current-datetime">
                {{-- Tanggal & Jam akan muncul di sini --}}
            </div>
        </div>
        <div class="card-body" style="font-size: 1rem;">
            <p>Ini adalah halaman utama untuk mengelola <strong>transaksi penyewaan alat outdoor</strong> melalui
                <strong>FomoId</strong>.</p>

            <ul class="mt-3">
                <li>📦 Kelola stok dan ketersediaan alat</li>
                <li>🔄 Cek dan proses transaksi baru</li>
            </ul>
        </div>
    </div>

    {{-- Script untuk menampilkan tanggal dan jam --}}
    @push('scripts')
        <script>
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                const formatted = now.toLocaleDateString('id-ID', options);
                document.getElementById('current-datetime').innerText = formatted;
            }

            setInterval(updateDateTime, 1000); // update setiap detik
            updateDateTime(); // panggil langsung saat load
        </script>
    @endpush
@endsection
