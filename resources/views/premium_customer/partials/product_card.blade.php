@php
    $storeSlug = \Illuminate\Support\Str::slug($item->toko->nama_toko ?? 'toko') . '-' . ($item->id_toko ?? 1);
    $productSlug = \Illuminate\Support\Str::slug($item->nama_produk) . '-' . ($item->id_produk ?? $item->id);

    // Calculate minimum price from active variants
    $activeVariants = $item->tipeLayanan->flatMap->varianLayanan->where('status', 'aktif');
    if ($activeVariants->isEmpty()) {
        $activeVariants = $item->tipeLayanan->flatMap->varianLayanan;
    }
    $minPrice = $activeVariants->min('harga');
    if ($minPrice === null || $minPrice <= 0) {
        $minPrice = $item->harga ?? 0;
    }

    // Calculate shortest duration
    $minDuration = $activeVariants->where('durasi_hari', '>', 0)->min('durasi_hari');

    // Seller rating (temporary as requested)
    $ratingToko = (float) ($item->toko->rating_rata_rata ?? 0);
    if ($ratingToko <= 0) {
        $ratingToko = 5.0;
    }
    $ratingDisplay = number_format($ratingToko, 1);
@endphp

<div class="col-12 col-sm-6 col-md-4 col-lg-4 d-flex align-items-stretch mb-4">
    <a href="{{ route('toko.produk.detail', ['store_slug' => $storeSlug, 'product_slug' => $productSlug]) }}" class="katalog-card-link w-100 text-decoration-none">
        <div class="katalog-card">
            <!-- Thumbnail Image Container with Badges -->
            <div class="katalog-thumbnail-wrapper">
                @if($item->gambar)
                    <img src="{{ asset('assets/img/produk_premium/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="katalog-thumbnail-img" loading="lazy">
                @else
                    <!-- Clean Vector Placeholder -->
                    <div class="katalog-thumbnail-placeholder">
                        <div class="placeholder-graphic">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 16L8.586 11.414C9.367 10.633 10.633 10.633 11.414 11.414L16 16M14 14L15.586 12.414C16.367 11.633 17.633 11.633 18.414 12.414L20 14M14 8H14.01M6 20H18C19.1046 20 20 19.1046 20 18V6C20 4.89543 19.1046 4 18 4H6C4.89543 4 4 4.89543 4 6V18C4 19.1046 4.89543 20 6 20Z" stroke="#60a5fa" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span class="placeholder-label">PLACEHOLDER IMAGE</span>
                    </div>
                @endif

                <!-- Top Left Badge: AKUN or FILE ZIP -->
                <div class="katalog-badge-top-left">
                    @if($item->tipe_produk === 'digital')
                        <span class="badge-type-pill">FILE ZIP</span>
                    @else
                        <span class="badge-type-pill">AKUN</span>
                    @endif
                </div>

                <!-- Bottom Right Badge: Duration (if applicable) -->
                @if($minDuration)
                    <div class="katalog-badge-bottom-right">
                        <span class="badge-duration-pill">
                            <i class="bi bi-shield-check"></i> {{ $minDuration }} Hari
                        </span>
                    </div>
                @endif
            </div>

            <!-- Card Body Content -->
            <div class="katalog-card-body">
                <!-- 1. Nama Toko -->
                <div class="katalog-store-name" title="{{ $item->toko->nama_toko ?? 'Official Store' }}">
                    {{ $item->toko->nama_toko ?? 'Official Store' }}
                </div>

                <!-- 2. Judul Produk -->
                <h5 class="katalog-product-title" title="{{ $item->nama_produk }}">
                    {{ $item->nama_produk }}
                </h5>

                <!-- 3. Footer: Mulai dari Price & Rating -->
                <div class="katalog-card-footer">
                    <div class="katalog-price-block">
                        <span class="katalog-price-label">Mulai</span>
                        <div class="katalog-price-value">
                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="katalog-rating-pill">
                        <i class="bi bi-star-fill"></i>
                        <span>{{ $ratingDisplay }}</span>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
