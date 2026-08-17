@extends('layout')

@section('title', 'Status Pembayaran')

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

<style>
    .status-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        margin-top: 10px;
        animation: statusFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .status-card {
        background: #ffffff !important;
        border: 1px solid #000000 !important;
        border-radius: 16px !important;
        padding: 40px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        position: relative !important;
        overflow: hidden !important;
    }
    
    .status-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: #000000;
    }

    .status-icon-wrapper {
        font-size: 3.5rem;
        margin-bottom: 20px;
        line-height: 1;
    }

    .status-title {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        text-transform: uppercase;
        color: #000000;
        margin-bottom: 10px;
    }

    .status-desc {
        font-size: 0.95rem;
        color: #555555;
        max-width: 480px;
        margin: 0 auto 30px auto;
        line-height: 1.6;
    }

    .order-details-box {
        background: #fafafa;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 30px;
        text-align: left;
    }

    .order-details-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #000000;
        margin-bottom: 16px;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-size: 0.9rem;
    }

    .detail-row:last-child {
        margin-bottom: 0;
        padding-top: 12px;
        border-top: 1px dashed #e5e5e5;
        font-weight: 700;
    }

    .detail-label {
        color: #666666;
    }

    .detail-value {
        color: #000000;
        font-weight: 600;
    }

    .btn-action-group {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-mono {
        font-family: inherit !important;
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 12px 24px !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    .btn-mono-primary {
        background: #000000 !important;
        color: #ffffff !important;
        border: 1px solid #000000 !important;
    }

    .btn-mono-primary:hover {
        background: transparent !important;
        color: #000000 !important;
        text-decoration: none !important;
    }

    .btn-mono-secondary {
        background: #ffffff !important;
        color: #000000 !important;
        border: 1px solid #000000 !important;
    }

    .btn-mono-secondary:hover {
        background: #fafafa !important;
        text-decoration: none !important;
    }

    @keyframes statusFadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

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
