@extends('layout')

@section('title', 'Katalog Akun Premium')

@section('content')

@if($error = Session::get('error'))
<script>
    Swal.fire({ title: "Gagal", text: "{{ $error }}", icon: "error" });
</script>
@endif

    <style>
        .shop-container {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin-top: 10px;
        }

        .shop-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1a1a1a;
            margin-bottom: 24px;
            text-transform: uppercase;
            border-left: 4px solid #000000;
            padding-left: 14px;
        }

        /* Profile Complete Warning Alert */
        .mono-alert-warning {
            background-color: #fffdf5 !important;
            border: 1px dashed #eed27a !important;
            color: #7d6318 !important;
            border-radius: 12px !important;
            padding: 16px 20px !important;
            font-size: 0.88rem !important;
            margin-bottom: 30px !important;
            line-height: 1.5 !important;
            text-align: left !important;
        }

        .mono-alert-warning a {
            color: #000000 !important;
            text-decoration: underline !important;
            font-weight: 700 !important;
        }

        /* Search Form Styling */
        .mono-search-input {
            background-color: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 8px 0 0 8px !important;
            color: #000000 !important;
            font-size: 0.9rem !important;
            padding: 12px 18px !important;
            height: auto !important;
            transition: all 0.2s ease !important;
        }

        .mono-search-input:focus {
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1) !important;
            outline: none !important;
        }

        .mono-search-btn {
            background-color: #000000 !important;
            color: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 0 8px 8px 0 !important;
            padding: 0 24px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            font-size: 0.85rem !important;
            transition: all 0.2s ease !important;
        }

        .mono-search-btn:hover {
            background-color: #222222 !important;
            border-color: #222222 !important;
        }

        /* Shop Card Styling */
        .mono-shop-card {
            background: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            position: relative !important;
            overflow: hidden !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            margin-bottom: 30px !important;
            display: flex;
            flex-direction: column;
            height: calc(100% - 30px);
        }

        .mono-shop-card:hover {
            transform: translateY(-6px) !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
        }

        .mono-shop-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #000000;
        }

        .mono-card-header {
            padding: 24px 28px 12px 28px !important;
            background: transparent !important;
            border: none !important;
        }

        .mono-card-header h4 {
            color: #000000 !important;
            font-weight: 800 !important;
            font-size: 1.25rem !important;
            letter-spacing: -0.3px !important;
            text-transform: uppercase !important;
            margin: 0 !important;
        }

        .mono-card-body {
            padding: 12px 28px 28px 28px !important;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .mono-card-body p {
            color: #555555 !important;
            font-size: 0.9rem !important;
            line-height: 1.5 !important;
            margin-bottom: 20px !important;
        }

        .product-image-wrapper {
            background-color: #fafafa !important;
            border: 1px solid #e5e5e5 !important;
            border-radius: 12px !important;
            padding: 12px !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            height: 160px !important;
            margin-bottom: 20px !important;
            overflow: hidden !important;
        }

        .product-image-img {
            max-height: 100% !important;
            max-width: 100% !important;
            object-fit: contain !important;
            border-radius: 8px !important;
        }

        .service-type-title {
            color: #000000 !important;
            font-weight: 700 !important;
            font-size: 0.95rem !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            margin-top: 15px !important;
            margin-bottom: 10px !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .service-type-title i {
            color: #000000 !important;
        }

        /* Varian List Styling */
        .mono-varian-item {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            background-color: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 10px !important;
            padding: 14px 16px !important;
            margin-bottom: 12px !important;
            transition: all 0.2s ease !important;
        }

        .mono-varian-item:hover {
            background-color: #fafafa !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
        }

        .mono-varian-name {
            color: #000000 !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            display: block !important;
        }

        .mono-varian-price {
            color: #000000 !important;
            font-weight: 800 !important;
            font-size: 0.92rem !important;
            margin-top: 2px !important;
            display: inline-block !important;
        }

        /* Stock Status & Buttons */
        .mono-badge-stok {
            background-color: #f2f2f2 !important;
            color: #333333 !important;
            border: 1px solid #dcdcdc !important;
            font-weight: 700 !important;
            font-size: 0.65rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 4px 10px !important;
            border-radius: 4px !important;
            display: inline-block !important;
            margin-bottom: 6px !important;
        }

        .mono-badge-habis {
            background-color: #fff5f5 !important;
            color: #ea5455 !important;
            border: 1px dashed #fcd4d4 !important;
            font-weight: 700 !important;
            font-size: 0.65rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 4px 10px !important;
            border-radius: 4px !important;
            display: inline-block !important;
            margin-bottom: 6px !important;
        }

        .mono-btn-buy {
            background-color: #000000 !important;
            color: #ffffff !important;
            border: 1px solid #000000 !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            width: 100% !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            cursor: pointer !important;
        }

        .mono-btn-buy:hover {
            background-color: transparent !important;
            color: #000000 !important;
        }

        .mono-btn-disabled {
            background-color: #f9f9f9 !important;
            color: #aaaaaa !important;
            border: 1px dashed #cccccc !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            width: 100% !important;
            cursor: not-allowed !important;
        }
    </style>

    <div class="shop-container">
        <div class="d-flex align-items-center justify-content-between mb-4" style="flex-wrap:wrap; gap:12px;">
            <h4 class="shop-header-title mb-0">
                Katalog Akun Premium
                @if($toko)
                    &nbsp;<span style="font-weight:600; color:#555; font-size:1.3rem;">— {{ $toko->nama_toko }}</span>
                @endif
            </h4>
            @if($toko)
            <a href="{{ url('daftar_toko') }}" class="btn" style="background:#000; color:#fff; font-size:0.82rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; padding:8px 18px; border-radius:8px; display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Toko
            </a>
            @endif
        </div>
        
        @if($toko)
        <!-- Toko Profile Details and Rating Summary -->
        <div class="card border border-dark mb-5" style="border-radius: 16px; overflow: hidden; background: #ffffff; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-2 text-center mb-3 mb-lg-0">
                        @if($toko->logo_toko)
                            <img src="{{ asset('assets/img/logo_toko/' . $toko->logo_toko) }}" alt="{{ $toko->nama_toko }}" style="max-height: 100px; max-width: 100%; object-fit: contain; border-radius: 12px; border: 1px solid #e5e5e5; padding: 5px;">
                        @else
                            <div class="rounded-circle bg-dark d-inline-flex align-items-center justify-content-center text-white" style="width: 90px; height: 90px; font-size: 2.5rem;">
                                <i class="bi bi-shop"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <h4 class="font-weight-bold text-dark mb-2">{{ $toko->nama_toko }}</h4>

                        @if($toko->badges && $toko->badges->isNotEmpty())
                        <div class="mb-2 d-flex flex-wrap align-items-center" style="gap: 6px;">
                            @foreach($toko->badges as $b)
                            <span class="badge badge-dark p-2" data-toggle="tooltip" data-placement="top" title="{{ $b->nama_badge }}: {{ $b->deskripsi }}" style="cursor: pointer; background: #0f172a; border: 1px solid #8b5cf6; color: #a78bfa; font-size: 0.78rem; border-radius: 6px;">
                                <i class="bi bi-patch-check-fill text-warning mr-1"></i> {{ $b->nama_badge }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <p class="text-muted mb-3" style="font-size: 0.9rem; line-height: 1.5;">
                            {{ $toko->informasi_toko ?? 'Belum ada deskripsi profil untuk toko ini.' }}
                        </p>
                        <div class="d-flex flex-wrap gap-3" style="gap: 15px; font-size: 0.85rem;">
                            <span class="text-dark"><i class="bi bi-telephone-fill mr-1"></i> {{ $toko->no_telp }}</span>
                            <span class="text-info"><i class="bi bi-telegram mr-1"></i> @&nbsp;{{ $toko->akun_telegram }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4 border-left">
                        <div class="pl-lg-3">
                            <h6 class="font-weight-bold text-uppercase mb-2 text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Reputasi Toko</h6>
                            <div class="d-flex align-items-center mb-2">
                                @php
                                    $rating = (float) $toko->rating_rata_rata;
                                    $jumlahReview = (int) $toko->jumlah_review;
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                    $emptyStars = 5 - $fullStars - $halfStar;
                                @endphp
                                <h2 class="font-weight-bold text-dark mb-0 mr-3">{{ number_format($rating, 1) }}</h2>
                                <div>
                                    <div class="text-warning" style="font-size: 1.15rem;">
                                        {!! str_repeat('<i class="bi bi-star-fill"></i>', $fullStars) !!}
                                        {!! $halfStar ? '<i class="bi bi-star-half"></i>' : '' !!}
                                        {!! str_repeat('<i class="bi bi-star"></i>', $emptyStars) !!}
                                    </div>
                                    <span class="text-muted" style="font-size: 0.82rem;">{{ $jumlahReview }} Ulasan Pembeli</span>
                                </div>
                            </div>
                            
                            <!-- Rating Distribution Bars -->
                            <div style="font-size: 0.8rem;">
                                @for($star = 5; $star >= 1; $star--)
                                    @php
                                        $count = $ratingDistribution[$star] ?? 0;
                                        $percent = $jumlahReview > 0 ? ($count / $jumlahReview) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="mr-2" style="width: 12px; font-weight: bold;">{{ $star }}</span>
                                        <i class="bi bi-star-fill text-warning mr-2" style="font-size: 0.7rem;"></i>
                                        <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px; background-color: #f0f0f0;">
                                            <div class="progress-bar bg-dark" style="width: {{ $percent }}%; border-radius: 3px;"></div>
                                        </div>
                                        <span class="ml-2 text-muted" style="width: 25px; text-align: right;">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(empty($customer->nomor_telepon) || empty(Auth::user()->name))
        <div class="mono-alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle-fill mr-2"></i><strong>Profil Belum Lengkap!</strong> Silakan <a href="{{ url('profile_customer/' . Auth::user()->id) }}">lengkapi nama dan nomor WhatsApp Anda di profil</a> terlebih dahulu agar invoice dan detail akun bisa dikirimkan ke WhatsApp Anda setelah pembayaran.
        </div>
        @endif

        <div class="row mb-5">
            <div class="col-12 col-md-6 offset-md-3">
                <form action="{{ route('premium.katalog') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control mono-search-input" placeholder="Cari layanan premium..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn mono-search-btn" type="submit">
                                <i class="bi bi-search mr-1"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @forelse($produk as $item)
            <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
                <div class="mono-shop-card">
                    <div class="mono-card-header">
                        <h4>{{ $item->nama_produk }}</h4>
                        @if(!$toko && $item->toko)
                        <div style="margin-top:6px;">
                            <a href="{{ url('premium/katalog?id_toko=' . $item->toko->id_toko) }}"
                               style="display:inline-flex; align-items:center; gap:5px; font-size:0.75rem; font-weight:700; color:#fff; background:#333; border-radius:20px; padding:3px 10px; text-decoration:none; letter-spacing:0.3px;">
                                <i class="bi bi-shop"></i> {{ $item->toko->nama_toko }}
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="mono-card-body">
                        <div class="product-image-wrapper">
                            @if($item->gambar)
                            <img src="{{ asset('assets/img/produk_premium/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="product-image-img">
                            @else
                            <i class="bi bi-music-note-beamed text-muted" style="font-size: 3rem;"></i>
                            @endif
                        </div>
                        <p>{{ $item->deskripsi ?? 'Aplikasi premium resmi terpercaya.' }}</p>
                        
                        @foreach($item->tipeLayanan as $tipe)
                        <div class="mb-4">
                            <h6 class="service-type-title"><i class="bi bi-tag-fill"></i>{{ $tipe->nama_tipe }}</h6>
                            
                            @foreach($tipe->varianLayanan as $varian)
                            <div class="mono-varian-item">
                                <div>
                                    <span class="mono-varian-name">{{ $varian->nama_varian }}</span>
                                    <span class="mono-varian-price">Rp {{ number_format($varian->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right" style="min-width: 110px;">
                                    @if($varian->stok_tersedia > 0)
                                    <span class="mono-badge-stok">Stok: {{ $varian->stok_tersedia }}</span>
                                    <form action="{{ url('/proses_checkout_premium') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_varian" value="{{ $varian->id_varian }}">
                                        <button type="submit" class="mono-btn-buy">Beli</button>
                                    </form>
                                    @else
                                    <span class="mono-badge-habis">Habis</span>
                                    <button class="mono-btn-disabled" disabled>Beli</button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center my-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">Katalog produk premium sedang kosong.</h5>
            </div>
            @endforelse
        </div>

        @if($toko && $reviews)
        <!-- Ulasan Pembeli Section -->
        <hr class="my-5" style="border-top: 2px solid #000000;">
        
        <div class="mb-5">
            <h4 class="font-weight-bold text-uppercase mb-4" style="letter-spacing: -0.5px; border-left: 4px solid #000000; padding-left: 14px;">Ulasan Pembeli</h4>
            
            @forelse($reviews as $rev)
                <div class="card border mb-3" style="border-radius: 12px; background: #ffffff; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);">
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
                                <strong class="text-dark">{{ $maskedName }}</strong>
                                <div class="text-warning mt-1" style="font-size: 0.9rem;">
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
                            <p class="text-muted mb-0 mt-2 font-italic" style="font-size: 0.92rem;">
                                Pembeli tidak menuliskan komentar ulasan.
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 border rounded bg-white" style="border-radius: 12px;">
                    <i class="bi bi-chat-left-dots text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Belum ada ulasan untuk toko ini.</h5>
                </div>
            @endforelse
            
            <!-- Reviews Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
        @endif
    </div>

@endsection
