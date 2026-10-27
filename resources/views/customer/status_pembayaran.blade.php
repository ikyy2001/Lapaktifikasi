@extends('layout')

@section('title', 'Status Pembayaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/customer-status.css') }}">
@endpush

@section('content')
@php
    $orderId = $orderId ?? ($order->order_id ?? '');
    $statusText = '';
    $statusClass = '';
    $iconClass = '';
    $colorClass = '';
    $descText = '';

    if ($status === 'success') {
        $statusText = 'Pembayaran Berhasil';
        $statusClass = 'border-success';
        $iconClass = 'bi-check-circle-fill text-success';
        $colorClass = 'text-success';
        $descText = $type === 'premium'
            ? 'Terima kasih! Pembayaran Anda telah kami terima dan kredensial akun premium Anda telah siap. Anda akan dialihkan ke riwayat akun...'
            : 'Terima kasih! Pembayaran Anda telah kami terima dan produk Anda siap diunduh.';
    } elseif ($status === 'pending') {
        $statusText = 'Pembayaran Menunggu';
        $statusClass = 'border-warning';
        $iconClass = 'bi-clock-fill text-warning';
        $colorClass = 'text-warning';
        $descText = 'Pembayaran Anda sedang ditangguhkan atau menunggu penyelesaian. Silakan selesaikan pembayaran Anda di aplikasi e-wallet / bank.';
    } elseif ($status === 'expired' || $status === 'cancelled') {
        $statusText = 'Transaksi Dibatalkan';
        $statusClass = 'border-secondary';
        $iconClass = 'bi-exclamation-triangle-fill text-secondary';
        $colorClass = 'text-secondary';
        $descText = 'Batas waktu pembayaran untuk transaksi ini telah habis atau dibatalkan. Silakan buat pesanan baru.';
    } else {
        $statusText = 'Pembayaran Gagal';
        $statusClass = 'border-danger';
        $iconClass = 'bi-x-circle-fill text-danger';
        $colorClass = 'text-danger';
        $descText = 'Pembayaran Anda gagal diproses atau dibatalkan. Silakan coba kembali.';
    }
@endphp

<div class="container status-container my-4 text-center" id="status-card-container" data-order-id="{{ $orderId }}" data-status="{{ $status }}">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="status-card">
                
                <div class="status-icon-wrapper">
                    <i class="bi {{ $iconClass }}"></i>
                </div>
                
                <h2 class="status-title">{{ $statusText }}</h2>
                <p class="status-desc">{{ $descText }}</p>
                
                <div class="order-details-box">
                    <h5 class="order-details-title">Rincian Transaksi</h5>
                    
                    <div class="detail-row">
                        <span class="detail-label">Order ID</span>
                        <span class="detail-value text-monospace">{{ $orderId }}</span>
                    </div>
                    
                    @if($type === 'premium')
                        @php
                            $varian = $order->varianLayanan;
                            $tipe = $varian?->tipeLayanan;
                            $produk = $tipe?->produk;
                        @endphp
                        <div class="detail-row">
                            <span class="detail-label">Produk</span>
                            <span class="detail-value">{{ $produk?->nama_produk ?? 'Akun Premium' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Varian</span>
                            <span class="detail-value">{{ $tipe?->nama_tipe ?? '' }} ({{ $varian?->nama_varian ?? '' }})</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total Pembayaran</span>
                            <span class="detail-value text-monospace">Rp {{ number_format($order->harga_saat_beli, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="detail-row">
                            <span class="detail-label">Produk</span>
                            <span class="detail-value">{{ $order->produk->nama_produk }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total Pembayaran</span>
                            <span class="detail-value text-monospace">Rp {{ number_format($order->harga_beli, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value {{ $colorClass }}">
                            @if(in_array($status, ['expired', 'cancelled']))
                                TRANSAKSI DIBATALKAN
                            @else
                                {{ strtoupper($status) }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="btn-action-group">
                    <button onclick="window.location.reload();" class="btn-mono btn-mono-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Perbarui Status
                    </button>
                    
                    @if($status === 'success')
                        @if($type === 'premium')
                            <a href="{{ route('premium.riwayat') }}" class="btn-mono btn-mono-primary">
                                <i class="bi bi-key-fill"></i> Lihat Detail Akun
                            </a>
                        @else
                            <a href="{{ route('premium.riwayat') }}" class="btn-mono btn-mono-primary">
                                <i class="bi bi-cloud-arrow-down-fill"></i> Unduh Produk
                            </a>
                        @endif
                    @else
                        @if($type === 'premium')
                            <a href="{{ route('premium.katalog') }}" class="btn-mono btn-mono-primary">
                                <i class="bi bi-cart3"></i> Kembali Belanja
                            </a>
                            <a href="{{ route('premium.riwayat') }}" class="btn-mono btn-mono-secondary">
                                <i class="bi bi-clock-history"></i> Riwayat Pembelian
                            </a>
                        @else
                            <a href="{{ url('menu_produk') }}" class="btn-mono btn-mono-primary">
                                <i class="bi bi-cart3"></i> Kembali Belanja
                            </a>
                        @endif
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('status-card-container');
    if (!container) return;

    const orderId = container.getAttribute('data-order-id');
    let currentStatus = container.getAttribute('data-status');

    @if($status === 'success' && $type === 'premium')
    // Otomatis arahkan ke riwayat premium setelah pembayaran berhasil
    setTimeout(function() {
        window.location.href = "{{ route('premium.riwayat') }}";
    }, 2500);
    @endif

    // Lakukan polling hanya jika status saat ini masih pending
    if (currentStatus === 'pending') {
        let pollingInterval = setInterval(checkStatus, 6000); // Polling tiap 6 detik (sesuai best practice)

        function checkStatus() {
            @if(isset($is_db_only) && $is_db_only)
                const fetchUrl = `/api/checkout/${orderId}/status`;
            @else
                const fetchUrl = `/api/status/${orderId}`;
            @endif

            fetch(fetchUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status && data.status !== currentStatus) {
                        currentStatus = data.status;
                        // Hentikan interval polling dan segarkan halaman untuk merender status baru
                        clearInterval(pollingInterval);
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error fetching order status:', error));
        }

        // Panggil checkStatus secara instan saat tab kembali aktif
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                checkStatus();
            }
        });

        // Panggil checkStatus secara instan saat halaman dimuat dari cache (back-forward)
        window.addEventListener('pageshow', function (event) {
            checkStatus();
        });
    }
});
</script>
@endsection
