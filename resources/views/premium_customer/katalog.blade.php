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
        <h4 class="shop-header-title">Katalog Akun Premium</h4>

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
    </div>

@endsection
