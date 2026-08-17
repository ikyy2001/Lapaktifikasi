@extends('layout')

@section('title', $produk->nama_produk . ' - Detail Produk')

@section('content')

@if($error = Session::get('error'))
<script>
    Swal.fire({ title: "Gagal", text: "{{ $error }}", icon: "error" });
</script>
@endif

<style>
    .shopee-detail-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
        margin-top: 10px;
        padding-bottom: 80px; /* Space for mobile sticky action bar */
    }

    /* Breadcrumb */
    .shopee-breadcrumb {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 20px;
    }
    .shopee-breadcrumb a {
        color: #334155;
        text-decoration: none;
        font-weight: 600;
    }
    .shopee-breadcrumb a:hover {
        color: #000000;
        text-decoration: underline;
    }

    /* Card Box */
    .shopee-main-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        padding: 28px;
        margin-bottom: 30px;
    }

    /* GALERI GAMBAR (KOLOM KIRI) */
    .shopee-gallery-main {
        width: 100%;
        aspect-ratio: 4 / 3;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        position: relative;
    }
    .shopee-gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 1;
        transition: opacity 0.25s ease-in-out, transform 0.3s ease;
    }
    .shopee-gallery-thumbs {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 6px;
        scroll-behavior: smooth;
    }
    .shopee-thumb-btn {
        width: 72px;
        height: 54px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
        cursor: pointer;
        background: #f8fafc;
        flex-shrink: 0;
        transition: all 0.2s ease;
        padding: 0;
    }
    .shopee-thumb-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .shopee-thumb-btn.shopee-thumb-active {
        border-color: #000000 !important;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.2);
    }

    /* INFORMASI PRODUK (KOLOM KANAN) */
    .shopee-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
        line-height: 1.3;
        margin-bottom: 12px;
    }

    .shopee-store-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .shopee-store-badge {
        background: #0f172a;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    .shopee-store-badge:hover {
        color: #ffffff;
        opacity: 0.9;
        text-decoration: none;
    }

    .shopee-rating-summary {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 0.88rem;
        color: #475569;
        margin-bottom: 20px;
    }
    .shopee-rating-score {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.05rem;
    }

    /* Price Box */
    .shopee-price-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 22px;
        margin-bottom: 24px;
        display: flex;
        align-items: baseline;
        gap: 12px;
    }
    .shopee-price-val {
        font-size: 1.85rem;
        font-weight: 800;
        color: #000000;
        letter-spacing: -0.5px;
        transition: opacity 0.2s ease;
    }
    .shopee-price-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 700;
    }

    /* Varian Section */
    .shopee-variant-group {
        margin-bottom: 22px;
    }
    .shopee-variant-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        margin-bottom: 10px;
        display: block;
    }
    .shopee-pills-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .shopee-pill-btn {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        color: #1e293b;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .shopee-pill-btn:hover:not(.shopee-pill-disabled) {
        border-color: #000000;
        background: #f8fafc;
    }
    .shopee-pill-btn.shopee-pill-active {
        background: #000000 !important;
        color: #ffffff !important;
        border-color: #000000 !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    .shopee-pill-btn.shopee-pill-disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f1f5f9;
        border-color: #e2e8f0;
    }

    /* Stock Badge */
    .shopee-stock-badge {
        font-size: 0.82rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    /* Quantity Stepper */
    .shopee-qty-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 26px;
    }
    .shopee-qty-btn {
        width: 38px;
        height: 38px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
    .shopee-qty-btn:hover:not(:disabled) {
        background: #f1f5f9;
        border-color: #000000;
    }
    .shopee-qty-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    .shopee-qty-input {
        width: 56px;
        height: 38px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        text-align: center;
        font-weight: 700;
        font-size: 0.95rem;
    }

    /* Action Buttons */
    .shopee-action-row {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }
    .shopee-btn-cart {
        flex: 1;
        min-width: 180px;
        background: #ffffff;
        color: #000000;
        border: 2px solid #000000;
        font-weight: 800;
        font-size: 0.88rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-radius: 10px;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .shopee-btn-cart:hover:not(:disabled) {
        background: #f1f5f9;
        color: #000000;
    }
    .shopee-btn-buy {
        flex: 1.2;
        min-width: 200px;
        background: #000000;
        color: #ffffff;
        border: 2px solid #000000;
        font-weight: 800;
        font-size: 0.88rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-radius: 10px;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }
    .shopee-btn-buy:hover:not(:disabled) {
        background: #222222;
        border-color: #222222;
        color: #ffffff;
    }
    .shopee-btn-buy:disabled, .shopee-btn-cart:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* SECTION DESKRIPSI & REVIEWS */
    .shopee-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        padding: 28px;
        margin-bottom: 30px;
    }
    .shopee-section-header {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: -0.3px;
        margin-bottom: 20px;
        border-left: 4px solid #000000;
        padding-left: 14px;
    }

    /* Rating Breakdown */
    .shopee-rating-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 28px;
    }

    /* STICKY ACTION BAR UNTUK MOBILE & TABLET (< 992px) */
    .shopee-mobile-sticky-bar {
        display: none;
    }

    @media (max-width: 991.98px) {
        .shopee-detail-container {
            padding-bottom: 90px;
        }
        .shopee-main-card {
            padding: 20px;
        }
        .shopee-gallery-main {
            aspect-ratio: 1 / 1;
        }
        .shopee-title {
            font-size: 1.25rem;
        }
        .shopee-price-val {
            font-size: 1.5rem;
        }
        .shopee-action-row {
            display: none; /* Disembunyikan di inline form, pindah ke sticky bottom bar */
        }
        .shopee-mobile-sticky-bar {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.08);
            padding: 12px 16px;
            gap: 10px;
            align-items: center;
        }
        .shopee-mobile-sticky-bar .btn-mobile-cart {
            flex: 1;
            background: #ffffff;
            border: 2px solid #000000;
            color: #000000;
            font-weight: 800;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .shopee-mobile-sticky-bar .btn-mobile-buy {
            flex: 1.5;
            background: #000000;
            border: 2px solid #000000;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .shopee-mobile-sticky-bar button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
    }

    @media (max-width: 480px) {
        .shopee-main-card { padding: 16px; }
        .shopee-title { font-size: 1.15rem; line-height: 1.4; }
        .shopee-price-val { font-size: 1.3rem; }
        .shopee-section-card { padding: 16px; }
        .shopee-section-header { font-size: 1rem; }
        .shopee-rating-box h1 { font-size: 2.5rem !important; }
        .shopee-rating-box .text-warning { font-size: 0.95rem !important; }
        .shopee-mobile-sticky-bar .btn-mobile-cart, .shopee-mobile-sticky-bar .btn-mobile-buy {
            font-size: 0.75rem; padding: 10px;
        }
    }
</style>

<div class="shopee-detail-container">
    <!-- Breadcrumb Nav (Toko > Kategori > Nama Produk) -->
    <div class="shopee-breadcrumb">
        @if($toko)
            <a href="{{ route('premium.katalog', ['id_toko' => $toko->id_toko]) }}"><i class="bi bi-shop mr-1"></i> {{ $toko->nama_toko }}</a>
            <span class="mx-2">&gt;</span>
        @else
            <a href="{{ route('premium.katalog') }}"><i class="bi bi-shop mr-1"></i> Toko</a>
            <span class="mx-2">&gt;</span>
        @endif
        
        <a href="{{ route('premium.katalog', ['kategori' => $produk->kategori ?? $produk->tipe_produk]) }}">
            {{ $produk->kategori ?? ($produk->tipe_produk === 'digital' ? 'Produk Digital' : 'Akun Premium') }}
        </a>
        <span class="mx-2">&gt;</span>
        <span class="text-dark font-weight-bold">{{ $produk->nama_produk }}</span>
    </div>

    <!-- MAIN PRODUCT CARD (2 KOLOM DESKTOP, 1 KOLOM MOBILE) -->
    <div class="shopee-main-card">
        <div class="row">
            <!-- KOLOM KIRI: GALERI GAMBAR -->
            <div class="col-12 col-lg-5 mb-4 mb-lg-0">
                <div class="shopee-gallery-main">
                    @if(count($gallery) > 0)
                        <img id="mainProductImg" src="{{ $gallery[0] }}" alt="{{ $produk->nama_produk }}">
                    @elseif($produk->gambar)
                        <img id="mainProductImg" src="{{ asset('assets/img/produk_premium/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height:100%;">
                            <i class="bi bi-box-seam" style="font-size: 4rem;"></i>
                            <span class="mt-2" style="font-size: 0.85rem;">Gambar tidak tersedia</span>
                        </div>
                    @endif
                </div>

                @if(count($gallery) > 1)
                <!-- BARIS THUMBNAIL MULTI GAMBAR -->
                <div class="shopee-gallery-thumbs">
                    @foreach($gallery as $idx => $imgUrl)
                        <button type="button" class="shopee-thumb-btn {{ $idx === 0 ? 'shopee-thumb-active' : '' }}" onclick="changeMainImage('{{ $imgUrl }}', this)">
                            <img src="{{ $imgUrl }}" alt="Thumb {{ $idx + 1 }}">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- KOLOM KANAN: INFORMASI PRODUK & VARIAN -->
            <div class="col-12 col-lg-7">
                <!-- 1. Nama Produk -->
                <h1 class="shopee-title">{{ $produk->nama_produk }}</h1>

                <!-- 2. Toko & Badge Reputasi -->
                <div class="shopee-store-row">
                    @if($produk->tipe_produk === 'digital')
                        <span class="badge badge-info p-2" style="font-size:0.75rem; font-weight:700;"><i class="bi bi-file-earmark-code"></i> Produk Digital</span>
                    @else
                        <span class="badge badge-dark p-2" style="font-size:0.75rem; font-weight:700;"><i class="bi bi-shield-check"></i> Akun Premium</span>
                    @endif

                    @if($toko)
                        <a href="{{ route('premium.katalog', ['id_toko' => $toko->id_toko]) }}" class="shopee-store-badge">
                            <i class="bi bi-shop"></i> {{ $toko->nama_toko }}
                        </a>

                        @if($toko->badges && $toko->badges->isNotEmpty())
                            @foreach($toko->badges as $b)
                            <span class="badge p-2" data-toggle="tooltip" title="{{ $b->nama_badge }}: {{ $b->deskripsi }}" style="background: #0f172a; border: 1px solid #8b5cf6; color: #a78bfa; font-size: 0.75rem; border-radius: 6px; cursor: pointer;">
                                <i class="bi bi-patch-check-fill text-warning mr-1"></i> {{ $b->nama_badge }}
                            </span>
                            @endforeach
                        @endif
                    @endif
                </div>

                <!-- 3. Rating & Review Summary -->
                <div class="shopee-rating-summary">
                    @php
                        $avgRating = $toko ? (float) $toko->rating_rata_rata : 5.0;
                        $countReviews = $toko ? (int) $toko->jumlah_review : 0;
                        $fullStars = floor($avgRating);
                        $halfStar = ($avgRating - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                    @endphp
                    <span class="shopee-rating-score">{{ number_format($avgRating, 1) }}</span>
                    <div class="text-warning" style="font-size: 1.05rem;">
                        {!! str_repeat('<i class="bi bi-star-fill"></i>', $fullStars) !!}
                        {!! $halfStar ? '<i class="bi bi-star-half"></i>' : '' !!}
                        {!! str_repeat('<i class="bi bi-star"></i>', $emptyStars) !!}
                    </div>
                    <span class="text-muted">|</span>
                    <span><strong class="text-dark">{{ $countReviews }}</strong> Ulasan Pembeli</span>
                </div>

                <!-- 
                    ================================================================
                    LIVEWIRE MIGRATION SECTION (ProductVariantSelector)
                    Untuk mengaktifkan Livewire Component dan membandingkan hasilnya,
                    cukup un-comment baris di bawah dan comment-out blok Vanilla JS (section 4 s/d 8).
                    ================================================================
                    <livewire:product-variant-selector :product="$produk" />
                -->

                <!-- 4. Price Box (Range / Single Price) [LEGACY BLADE + VANILLA JS] -->
                <div class="shopee-price-box">
                    <span class="shopee-price-label">Harga:</span>
                    <span class="shopee-price-val" id="selected-price-display">
                        @if($minPrice == $maxPrice)
                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($minPrice, 0, ',', '.') }} - Rp {{ number_format($maxPrice, 0, ',', '.') }}
                        @endif
                    </span>
                </div>

                <!-- FORM CHECKOUT FORM [LEGACY BLADE + VANILLA JS] -->
                <form action="{{ url('/proses_checkout_premium') }}" method="POST" id="checkout-form">
                    @csrf
                    <input type="hidden" name="id_varian" id="input_id_varian" value="">

                    <!-- 5. Pilihan Varian (Interactive Pill Buttons) -->
                    @forelse($produk->tipeLayanan as $tipeIndex => $tipe)
                    <div class="shopee-variant-group">
                        <span class="shopee-variant-label">
                            <i class="bi bi-layers-fill mr-1"></i> {{ $tipe->nama_tipe }}
                        </span>
                        <div class="shopee-pills-wrap">
                            @foreach($tipe->varianLayanan as $vIndex => $varian)
                            <button type="button" 
                                    class="shopee-pill-btn {{ $varian->stok_tersedia <= 0 ? 'shopee-pill-disabled' : '' }}" 
                                    data-id="{{ $varian->id_varian }}"
                                    data-harga="{{ $varian->harga }}"
                                    data-harga-formatted="Rp {{ number_format($varian->harga, 0, ',', '.') }}"
                                    data-stok="{{ $varian->stok_tersedia }}"
                                    data-nama="{{ $varian->nama_varian }}"
                                    onclick="selectVarian(this)"
                                    {{ $varian->stok_tersedia <= 0 ? 'disabled' : '' }}>
                                <i class="bi bi-check2-circle"></i>
                                {{ $varian->nama_varian }}
                                @if($varian->durasi_hari)
                                    <small class="opacity-75">({{ $varian->durasi_hari }} Hari)</small>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-secondary py-2 font-weight-bold mb-3" style="font-size:0.85rem;">
                        Belum ada varian layanan yang dikonfigurasi untuk produk ini.
                    </div>
                    @endforelse

                    @if($produk->tipe_produk === 'digital')
                    <!-- Informasi Tambahan Produk Digital -->
                    <div class="alert alert-info py-2 font-weight-bold mb-3" style="font-size:0.85rem; border-radius:8px;">
                        <i class="bi bi-info-circle-fill mr-1"></i> Produk ini dikirim dalam format akses digital langsung setelah pembayaran.
                    </div>
                    @endif

                    <!-- 6. Status Stok Real-time -->
                    <div class="d-flex align-items-center mb-4" style="gap:12px;">
                        <span class="shopee-variant-label mb-0">Status Stok:</span>
                        <span id="stok-status-badge" class="shopee-stock-badge bg-light text-muted border">
                            Silakan pilih varian layanan di atas
                        </span>
                    </div>

                    <!-- 7. Input Kuantitas Stepper -->
                    <div class="shopee-qty-wrap">
                        <span class="shopee-variant-label mb-0 mr-2">Jumlah:</span>
                        <button type="button" class="shopee-qty-btn" id="btn-qty-minus" onclick="updateQty(-1)">-</button>
                        <input type="number" id="qty-input" name="qty" class="shopee-qty-input" value="1" min="1" readonly>
                        <button type="button" class="shopee-qty-btn" id="btn-qty-plus" onclick="updateQty(1)">+</button>
                    </div>

                    <!-- 8. Dua Tombol Aksi (Desktop Layout) -->
                    <div class="shopee-action-row">
                        <button type="button" class="shopee-btn-cart" id="btn-cart-desktop" onclick="addToCartMock()">
                            <i class="bi bi-cart-plus-fill" style="font-size: 1.15rem;"></i> Masukkan Keranjang
                        </button>

                        <button type="submit" class="shopee-btn-buy" id="btn-buy-desktop">
                            <i class="bi bi-bag-check-fill" style="font-size: 1.15rem;"></i> Beli Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SECTION BAWAH 1: DESKRIPSI LENGKAP PRODUK -->
    <div class="shopee-section-card">
        <h3 class="shopee-section-header">Deskripsi Lengkap Produk</h3>
        <div class="text-dark" style="line-height: 1.7; font-size: 0.95rem; white-space: pre-line;">
            {{ $produk->deskripsi ?? 'Tidak ada deskripsi tambahan untuk produk ini.' }}
        </div>
    </div>

    <!-- SECTION BAWAH 2: ULASAN PEMBELI -->
    @if($toko && $reviews)
    <div class="shopee-section-card">
        <h3 class="shopee-section-header">Penilaian & Ulasan Pembeli</h3>

        <!-- Rating Breakdown Summary -->
        <div class="shopee-rating-box">
            <div class="row align-items-center">
                <div class="col-lg-3 text-center mb-3 mb-lg-0 border-right">
                    <h1 class="font-weight-bold text-dark mb-0" style="font-size: 3rem;">{{ number_format($avgRating, 1) }}</h1>
                    <div class="text-warning my-1" style="font-size: 1.15rem;">
                        {!! str_repeat('<i class="bi bi-star-fill"></i>', $fullStars) !!}
                        {!! $halfStar ? '<i class="bi bi-star-half"></i>' : '' !!}
                        {!! str_repeat('<i class="bi bi-star"></i>', $emptyStars) !!}
                    </div>
                    <span class="text-muted font-weight-bold" style="font-size: 0.85rem;">{{ $countReviews }} total ulasan</span>
                </div>

                <div class="col-lg-9 pl-lg-4">
                    <div style="font-size: 0.82rem;">
                        @for($star = 5; $star >= 1; $star--)
                            @php
                                $count = $ratingDistribution[$star] ?? 0;
                                $percent = $countReviews > 0 ? ($count / $countReviews) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-1">
                                <span class="mr-2 font-weight-bold" style="width: 14px;">{{ $star }}</span>
                                <i class="bi bi-star-fill text-warning mr-2" style="font-size: 0.75rem;"></i>
                                <div class="progress flex-grow-1" style="height: 8px; border-radius: 4px; background-color: #e2e8f0;">
                                    <div class="progress-bar bg-dark" style="width: {{ $percent }}%; border-radius: 4px;"></div>
                                </div>
                                <span class="ml-3 text-muted font-weight-bold" style="width: 35px; text-align: right;">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Review List -->
        <div class="shopee-reviews-list">
            @forelse($reviews as $rev)
                <div class="card border mb-3" style="border-radius: 12px; background: #ffffff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2" style="gap: 8px;">
                            <div>
                                @php
                                    $name = $rev->customer->user->name ?? 'Pelanggan';
                                    $parts = explode(' ', trim($name));
                                    $maskedName = $parts[0];
                                    if (count($parts) > 1) {
                                        $maskedName .= ' ' . substr($parts[1], 0, 1) . '.';
                                    }
                                @endphp
                                <strong class="text-dark" style="font-size: 0.95rem;">{{ $maskedName }}</strong>
                                <div class="text-warning mt-1" style="font-size: 0.88rem;">
                                    {!! str_repeat('<i class="bi bi-star-fill"></i>', $rev->rating) !!}
                                    {!! str_repeat('<i class="bi bi-star"></i>', 5 - $rev->rating) !!}
                                </div>
                            </div>
                            <small class="text-muted">{{ $rev->created_at->format('d M Y H:i') }} WIB</small>
                        </div>
                        
                        @if($rev->komentar)
                            <p class="text-dark mb-0 mt-2" style="font-size: 0.92rem; line-height: 1.5;">
                                {!! nl2br(e($rev->komentar)) !!}
                            </p>
                        @else
                            <p class="text-muted mb-0 mt-2 font-italic" style="font-size: 0.88rem;">
                                Pembeli tidak menuliskan komentar ulasan.
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-4 border rounded bg-white" style="border-radius: 12px;">
                    <i class="bi bi-chat-left-dots text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Belum ada ulasan untuk toko ini.</h5>
                </div>
            @endforelse

            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- STICKY BOTTOM ACTION BAR UNTUK TABLET & MOBILE -->
<div class="shopee-mobile-sticky-bar">
    <button type="button" class="btn-mobile-cart" id="btn-cart-mobile" onclick="addToCartMock()">
        <i class="bi bi-cart-plus-fill"></i> Keranjang
    </button>
    <button type="button" class="btn-mobile-buy" id="btn-buy-mobile" onclick="triggerMobileBuy()">
        <i class="bi bi-bag-check-fill"></i> Beli Sekarang
    </button>
</div>

<script>
    // 1. Pergantian Gambar Utama Galeri (dengan Transisi Fade Halus)
    function changeMainImage(src, element) {
        const mainImg = document.getElementById('mainProductImg');
        if (!mainImg) return;

        mainImg.style.opacity = '0';

        setTimeout(function() {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 150);

        document.querySelectorAll('.shopee-thumb-btn').forEach(btn => {
            btn.classList.remove('shopee-thumb-active');
        });
        element.classList.add('shopee-thumb-active');
    }

    // 2. Pemilihan Varian (Interactive Pill Buttons)
    let selectedVarianId = null;
    let selectedVarianStok = 0;

    function selectVarian(button) {
        document.querySelectorAll('.shopee-pill-btn').forEach(btn => {
            btn.classList.remove('shopee-pill-active');
        });

        button.classList.add('shopee-pill-active');

        selectedVarianId = button.getAttribute('data-id');
        selectedVarianStok = parseInt(button.getAttribute('data-stok')) || 0;
        const hargaFormatted = button.getAttribute('data-harga-formatted');

        // Update Hidden Input Form
        document.getElementById('input_id_varian').value = selectedVarianId;

        // Update Dynamic Price Display
        const priceDisplay = document.getElementById('selected-price-display');
        if (priceDisplay) {
            priceDisplay.style.opacity = '0';
            setTimeout(function() {
                priceDisplay.innerText = hargaFormatted;
                priceDisplay.style.opacity = '1';
            }, 100);
        }

        // Update Stock Badge & Button States
        const stokBadge = document.getElementById('stok-status-badge');
        const buyDesktop = document.getElementById('btn-buy-desktop');
        const buyMobile = document.getElementById('btn-buy-mobile');
        const cartDesktop = document.getElementById('btn-cart-desktop');
        const cartMobile = document.getElementById('btn-cart-mobile');

        if (selectedVarianStok > 0) {
            if (stokBadge) {
                stokBadge.className = 'shopee-stock-badge bg-success text-white';
                stokBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Stok Tersedia (Stok: ' + selectedVarianStok + ')';
            }
            if (buyDesktop) buyDesktop.disabled = false;
            if (buyMobile) buyMobile.disabled = false;
            if (cartDesktop) cartDesktop.disabled = false;
            if (cartMobile) cartMobile.disabled = false;
        } else {
            if (stokBadge) {
                stokBadge.className = 'shopee-stock-badge bg-danger text-white';
                stokBadge.innerHTML = '<i class="bi bi-x-circle-fill"></i> Stok Habis';
            }
            if (buyDesktop) buyDesktop.disabled = true;
            if (buyMobile) buyMobile.disabled = true;
            if (cartDesktop) cartDesktop.disabled = true;
            if (cartMobile) cartMobile.disabled = true;
        }

        // Reset Qty input if larger than stock
        const qtyInput = document.getElementById('qty-input');
        if (qtyInput) {
            let currentQty = parseInt(qtyInput.value) || 1;
            if (currentQty > selectedVarianStok && selectedVarianStok > 0) {
                qtyInput.value = 1;
            }
        }
    }

    // Auto-select first available variant on load
    document.addEventListener('DOMContentLoaded', function() {
        const firstAvailablePill = document.querySelector('.shopee-pill-btn:not(.shopee-pill-disabled)');
        if (firstAvailablePill) {
            selectVarian(firstAvailablePill);
        }
    });

    // 3. Stepper Kuantitas (+/-)
    function updateQty(change) {
        const qtyInput = document.getElementById('qty-input');
        if (!qtyInput) return;

        let currentQty = parseInt(qtyInput.value) || 1;
        currentQty += change;

        if (currentQty < 1) {
            currentQty = 1;
        }

        if (selectedVarianStok > 0 && currentQty > selectedVarianStok) {
            currentQty = selectedVarianStok;
            Swal.fire({
                title: "Batas Stok",
                text: "Kuantitas tidak dapat melebihi stok yang tersedia (" + selectedVarianStok + ")",
                icon: "warning",
                timer: 2000,
                showConfirmButton: false
            });
        }

        qtyInput.value = currentQty;
    }

    // 4. Trigger Beli dari Sticky Bottom Bar di Mobile (Mendukung Legacy & Livewire)
    function triggerMobileBuy() {
        const livewireForm = document.getElementById('livewire-checkout-form');
        if (livewireForm) {
            const hiddenInput = document.getElementById('livewire_input_id_varian');
            if (!hiddenInput || !hiddenInput.value) {
                Swal.fire({
                    title: "Pilih Varian",
                    text: "Silakan pilih salah satu varian produk terlebih dahulu.",
                    icon: "warning"
                });
                return;
            }
            livewireForm.submit();
            return;
        }

        const legacyForm = document.getElementById('checkout-form');
        if (legacyForm) {
            if (!selectedVarianId) {
                Swal.fire({
                    title: "Pilih Varian",
                    text: "Silakan pilih salah satu varian produk terlebih dahulu.",
                    icon: "warning"
                });
                return;
            }
            if (selectedVarianStok <= 0) {
                Swal.fire({
                    title: "Stok Habis",
                    text: "Maaf, stok untuk varian ini sedang habis.",
                    icon: "error"
                });
                return;
            }
            legacyForm.submit();
        }
    }

    // Listener Event Livewire Variant Changed (Sinkronisasi Mobile Bar & State JS)
    window.addEventListener('variant-changed', function(event) {
        const data = event.detail && event.detail[0] ? event.detail[0] : (event.detail || {});
        selectedVarianId = data.id_varian;
        selectedVarianStok = data.stok || 0;

        const buyMobile = document.getElementById('btn-buy-mobile');
        const cartMobile = document.getElementById('btn-cart-mobile');
        if (buyMobile) buyMobile.disabled = !data.is_available;
        if (cartMobile) cartMobile.disabled = !data.is_available;
    });

    // 5. Mock Add To Cart Toast
    function addToCartMock() {
        if (!selectedVarianId) {
            Swal.fire({
                title: "Pilih Varian",
                text: "Silakan pilih varian layanan terlebih dahulu.",
                icon: "info"
            });
            return;
        }
        if (selectedVarianStok <= 0) {
            Swal.fire({
                title: "Stok Habis",
                text: "Maaf, stok untuk varian ini sedang habis.",
                icon: "error"
            });
            return;
        }

        Swal.fire({
            title: "Berhasil!",
            text: "Produk telah ditambahkan ke keranjang Anda.",
            icon: "success",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    }

    // 6. Submit Form Validation
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        if (!selectedVarianId) {
            e.preventDefault();
            Swal.fire({
                title: "Pilih Varian",
                text: "Silakan pilih salah satu varian produk sebelum melanjutkan pembelian.",
                icon: "warning"
            });
        } else if (selectedVarianStok <= 0) {
            e.preventDefault();
            Swal.fire({
                title: "Stok Habis",
                text: "Maaf, stok untuk varian ini sedang habis.",
                icon: "error"
            });
        }
    });
</script>

@endsection
