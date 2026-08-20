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
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px 0 0 10px !important;
            color: #0f172a !important;
            font-size: 0.9rem !important;
            padding: 12px 18px !important;
            height: auto !important;
            transition: all 0.2s ease !important;
        }

        .mono-search-input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
            outline: none !important;
        }

        .mono-search-btn {
            background-color: #0f172a !important;
            color: #ffffff !important;
            border: 1px solid #0f172a !important;
            border-radius: 0 10px 10px 0 !important;
            padding: 0 24px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            font-size: 0.85rem !important;
            transition: all 0.2s ease !important;
        }

        .mono-search-btn:hover {
            background-color: #1e293b !important;
            border-color: #1e293b !important;
        }

        /* =========================================================
           Modern Clean Product Card (As per reference design)
           ========================================================= */
        .katalog-card-link {
            display: block;
            color: inherit;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .katalog-card-link:hover {
            text-decoration: none !important;
            color: inherit !important;
        }

        .katalog-card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03) !important;
            overflow: hidden !important;
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
            position: relative;
        }

        .katalog-card-link:hover .katalog-card {
            transform: translateY(-6px) !important;
            box-shadow: 0 16px 36px rgba(37, 99, 235, 0.08), 0 4px 12px rgba(0, 0, 0, 0.04) !important;
            border-color: #cbd5e1 !important;
        }

        /* Thumbnail Container (16:10 aspect ratio) */
        .katalog-thumbnail-wrapper {
            width: 100% !important;
            aspect-ratio: 16 / 10 !important;
            background-color: #f8fafc !important;
            position: relative !important;
            overflow: hidden !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .katalog-thumbnail-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            transition: transform 0.4s ease !important;
        }

        .katalog-card-link:hover .katalog-thumbnail-img {
            transform: scale(1.04) !important;
        }

        /* Vector Placeholder */
        .katalog-thumbnail-placeholder {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%) !important;
            gap: 6px !important;
        }

        .placeholder-label {
            font-size: 0.65rem !important;
            font-weight: 800 !important;
            letter-spacing: 1px !important;
            color: #64748b !important;
            text-transform: uppercase !important;
        }

        /* Overlaid Badges */
        .katalog-badge-top-left {
            position: absolute !important;
            top: 12px !important;
            left: 12px !important;
            z-index: 2 !important;
        }

        .badge-type-pill {
            background: #1e293b !important;
            color: #ffffff !important;
            font-size: 0.68rem !important;
            font-weight: 800 !important;
            padding: 3px 10px !important;
            border-radius: 999px !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2) !important;
            display: inline-block !important;
        }

        .katalog-badge-bottom-right {
            position: absolute !important;
            bottom: 10px !important;
            right: 10px !important;
            z-index: 2 !important;
        }

        .badge-duration-pill {
            background: #059669 !important;
            color: #ffffff !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            padding: 3px 8px !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
        }

        /* Card Body */
        .katalog-card-body {
            padding: 16px 18px 18px !important;
            display: flex !important;
            flex-direction: column !important;
            flex-grow: 1 !important;
        }

        .katalog-store-name {
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: #64748b !important;
            margin-bottom: 6px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            line-height: 1.2 !important;
        }

        .katalog-product-title {
            color: #0f172a !important;
            font-size: 0.98rem !important;
            font-weight: 800 !important;
            line-height: 1.38 !important;
            margin-bottom: 16px !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            min-height: 2.7em !important;
            transition: color 0.2s ease !important;
        }

        .katalog-card-link:hover .katalog-product-title {
            color: #2563eb !important;
        }

        /* Card Footer */
        .katalog-card-footer {
            margin-top: auto !important;
            display: flex !important;
            align-items: flex-end !important;
            justify-content: space-between !important;
            gap: 8px !important;
        }

        .katalog-price-block {
            display: flex !important;
            flex-direction: column !important;
        }

        .katalog-price-label {
            font-size: 0.72rem !important;
            font-weight: 500 !important;
            color: #94a3b8 !important;
            line-height: 1 !important;
            margin-bottom: 3px !important;
        }

        .katalog-price-value {
            font-size: 1.12rem !important;
            font-weight: 800 !important;
            color: #2563eb !important;
            line-height: 1 !important;
            letter-spacing: -0.3px !important;
        }

        .katalog-rating-pill {
            background: #fffdf0 !important;
            border: 1px solid #fef08a !important;
            color: #b45309 !important;
            padding: 4px 9px !important;
            border-radius: 8px !important;
            font-size: 0.8rem !important;
            font-weight: 800 !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            box-shadow: 0 1px 2px rgba(217, 119, 6, 0.04) !important;
            flex-shrink: 0 !important;
        }

        .katalog-rating-pill .bi-star-fill {
            color: #f59e0b !important;
            font-size: 0.76rem !important;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .shop-header-title { font-size: 1.4rem; padding-left: 10px; border-width: 3px; }
            .shop-header-title span { font-size: 1.1rem !important; }
            .mono-search-input { padding: 10px 14px !important; font-size: 0.85rem !important; }
            .mono-search-btn { padding: 0 16px !important; font-size: 0.8rem !important; }
            .katalog-card-body { padding: 12px 14px 14px !important; }
            .katalog-product-title { font-size: 0.9rem !important; margin-bottom: 12px !important; }
            .katalog-price-value { font-size: 1rem !important; }
        }
        @media (max-width: 480px) {
            .shop-header-title { font-size: 1.25rem; }
            .shop-header-title span { display: block; margin-top: 4px; }
            .mono-search-input { width: 100%; border-radius: 8px !important; border-bottom: none !important; }
            .mono-search-btn { width: 100%; border-radius: 8px !important; padding: 12px !important; }
            form .d-flex { flex-direction: column; }
        }
    </style>

    <div class="shop-container">
        <div class="d-flex align-items-center justify-content-between mb-4" style="flex-wrap:wrap; gap:12px;">
            <h4 class="shop-header-title mb-0">
                Katalog Produk & Layanan
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
                            <img src="{{ asset('assets/img/toko/' . $toko->logo_toko) }}" alt="{{ $toko->nama_toko }}" style="max-height: 100px; max-width: 100%; object-fit: contain; border-radius: 12px; border: 1px solid #e5e5e5; padding: 5px;">
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

        <!-- Search & Category Filter -->
        <div class="row mb-4 align-items-center">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <div class="btn-group shadow-sm" role="group">
                    <a href="{{ route('premium.katalog', array_merge(request()->except('kategori'), ['kategori' => 'all'])) }}" class="btn btn-sm {{ !request('kategori') || request('kategori') == 'all' ? 'btn-dark font-weight-bold' : 'btn-outline-dark' }}" style="border-radius: 8px 0 0 8px; padding: 7px 14px;">
                        Semua Produk
                    </a>
                    <a href="{{ route('premium.katalog', array_merge(request()->except('kategori'), ['kategori' => 'premium'])) }}" class="btn btn-sm {{ request('kategori') == 'premium' ? 'btn-dark font-weight-bold' : 'btn-outline-dark' }}" style="padding: 7px 14px;">
                        <i class="bi bi-shield-check mr-1"></i> Akun Premium
                    </a>
                    <a href="{{ route('premium.katalog', array_merge(request()->except('kategori'), ['kategori' => 'digital'])) }}" class="btn btn-sm {{ request('kategori') == 'digital' ? 'btn-dark font-weight-bold' : 'btn-outline-dark' }}" style="border-radius: 0 8px 8px 0; padding: 7px 14px;">
                        <i class="bi bi-file-earmark-code-fill mr-1"></i> Produk Digital
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <form action="{{ route('premium.katalog') }}" method="GET">
                    @if(request('id_toko'))
                        <input type="hidden" name="id_toko" value="{{ request('id_toko') }}">
                    @endif
                    @if(request('toko'))
                        <input type="hidden" name="toko" value="{{ request('toko') }}">
                    @endif
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <div class="input-group shadow-sm">
                        <input type="text" name="search" class="form-control mono-search-input" placeholder="Cari layanan premium / produk digital..." value="{{ request('search') }}">
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
                @include('premium_customer.partials.product_card', ['item' => $item])
            @empty
            <div class="col-12 text-center my-5">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">Katalog produk premium sedang kosong.</h5>
            </div>
            @endforelse
        </div>

        @if($toko && $reviews)
        <!-- Ulasan Pembeli Section -->
        <hr class="my-5" style="border-top: 2px solid #e2e8f0;">
        
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
