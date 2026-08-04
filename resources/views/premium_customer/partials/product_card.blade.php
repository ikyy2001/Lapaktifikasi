@php
    $storeSlug = \Illuminate\Support\Str::slug($item->toko->nama_toko ?? 'toko') . '-' . ($item->id_toko ?? 1);
    $productSlug = \Illuminate\Support\Str::slug($item->nama_produk) . '-' . $item->id_produk;
@endphp
<div class="col-6 col-md-4 col-lg-3 d-flex align-items-stretch mb-4">
    <a href="{{ route('toko.produk.detail', ['store_slug' => $storeSlug, 'product_slug' => $productSlug]) }}" class="mono-product-card-link w-100 text-decoration-none">
        <div class="mono-product-card">
            <!-- 1. Thumbnail Image (Aspect Ratio 4:3) -->
            <div class="product-thumbnail-container">
                @if($item->gambar)
                    <img src="{{ asset('assets/img/produk_premium/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="product-thumbnail-img">
                @else
                    <div class="product-thumbnail-placeholder">
                        <i class="bi bi-box-seam"></i>
                    </div>
                @endif
            </div>

            <div class="product-card-content">
                <!-- 2. Judul Produk (Max 2 Baris) -->
                <h5 class="product-card-title" title="{{ $item->nama_produk }}">
                    {{ $item->nama_produk }}
                </h5>

                <!-- 3. Deskripsi Singkat (Max 2 Baris) -->
                <p class="product-card-desc">
                    {{ $item->deskripsi ?? 'Layanan terpercaya.' }}
                </p>
            </div>
        </div>
    </a>
</div>
