<div>
    <!-- 1. Price Display Box (Livewire Reactive) -->
    <div class="shopee-price-box" wire:loading.class="opacity-75" style="transition: opacity 0.2s ease;">
        <span class="shopee-price-label">Harga:</span>
        <span class="shopee-price-val" id="livewire-price-val">
            @if($selectedVariantId && $formattedPrice)
                {{ $formattedPrice }}
            @else
                {{ $priceRange['formatted'] ?? 'Rp 0' }}
            @endif
        </span>
    </div>

    <!-- 2. Form Checkout & Variant Selector -->
    <form action="{{ url('/proses_checkout_premium') }}" method="POST" id="livewire-checkout-form">
        @csrf
        <!-- Pastikan input hidden selalu sinkron dengan state aktif Livewire -->
        <input type="hidden" name="id_varian" id="livewire_input_id_varian" value="{{ $selectedVariantId }}">
        <input type="hidden" name="qty" value="{{ $qty }}">

        <!-- Pilihan Varian Berdasarkan Tipe Layanan -->
        @forelse($product->tipeLayanan as $tipe)
            <div class="shopee-variant-group">
                <span class="shopee-variant-label">
                    <i class="bi bi-layers-fill mr-1"></i> {{ $tipe->nama_tipe }}
                </span>
                <div class="shopee-pills-wrap">
                    @foreach($tipe->varianLayanan as $varian)
                        @php
                            $stok = $stockMap[$varian->id_varian] ?? 0;
                            $isSelected = ($selectedVariantId === $varian->id_varian);
                            $isDisabled = ($stok <= 0);
                        @endphp
                        <button type="button"
                                wire:click="selectVariant({{ $varian->id_varian }})"
                                wire:key="variant-btn-{{ $varian->id_varian }}"
                                wire:loading.attr="disabled"
                                class="shopee-pill-btn {{ $isSelected ? 'shopee-pill-active' : '' }} {{ $isDisabled ? 'shopee-pill-disabled' : '' }}"
                                {{ $isDisabled ? 'disabled' : '' }}>
                            <i class="bi {{ $isSelected ? 'bi-check-circle-fill' : 'bi-check2-circle' }}"></i>
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

        @if($product->tipe_produk === 'digital')
            <!-- Informasi Tambahan Produk Digital -->
            <div class="alert alert-info py-2 font-weight-bold mb-3" style="font-size:0.85rem; border-radius:8px;">
                <i class="bi bi-info-circle-fill mr-1"></i> Produk ini dikirim dalam format akses digital langsung setelah pembayaran.
            </div>
        @endif

        <!-- 3. Status Stok Real-time (UX Indication Only) -->
        <div class="d-flex align-items-center mb-4" style="gap:12px;">
            <span class="shopee-variant-label mb-0">Status Stok:</span>
            @if(!$selectedVariantId)
                <span class="shopee-stock-badge bg-light text-muted border">
                    Silakan pilih varian layanan di atas
                </span>
            @elseif($isAvailable)
                <span class="shopee-stock-badge bg-success text-white">
                    <i class="bi bi-check-circle-fill"></i> Stok Tersedia (Stok: {{ $selectedStock }})
                </span>
            @else
                <span class="shopee-stock-badge bg-danger text-white">
                    <i class="bi bi-x-circle-fill"></i> Stok Habis
                </span>
            @endif
        </div>

        <!-- 4. Stepper Kuantitas -->
        <div class="shopee-qty-wrap">
            <span class="shopee-variant-label mb-0 mr-2">Jumlah:</span>
            <button type="button" 
                    class="shopee-qty-btn" 
                    wire:click="decrementQty"
                    wire:loading.attr="disabled"
                    wire:target="incrementQty, decrementQty, selectVariant, selectTipe"
                    {{ ($qty <= 1 || !$isAvailable) ? 'disabled' : '' }}>
                -
            </button>
            <input type="number" 
                   class="shopee-qty-input" 
                   value="{{ $qty }}" 
                   readonly>
            <button type="button" 
                    class="shopee-qty-btn" 
                    wire:click="incrementQty"
                    wire:loading.attr="disabled"
                    wire:target="incrementQty, decrementQty, selectVariant, selectTipe"
                    {{ ($qty >= $selectedStock || !$isAvailable) ? 'disabled' : '' }}>
                +
            </button>
        </div>

        <!-- 5. Tombol Aksi (Desktop Layout) -->
        <!-- Mencegah Race Condition dengan wire:loading.attr="disabled" -->
        <div class="shopee-action-row">
            <button type="button" 
                    class="shopee-btn-cart" 
                    onclick="addToCartMock()"
                    wire:loading.attr="disabled"
                    wire:target="selectVariant, selectTipe, incrementQty, decrementQty"
                    {{ (!$selectedVariantId || !$isAvailable) ? 'disabled' : '' }}>
                <i class="bi bi-cart-plus-fill" style="font-size: 1.15rem;"></i> Masukkan Keranjang
            </button>

            <button type="submit" 
                    class="shopee-btn-buy" 
                    id="livewire-btn-buy"
                    wire:loading.attr="disabled"
                    wire:target="selectVariant, selectTipe, incrementQty, decrementQty"
                    {{ (!$selectedVariantId || !$isAvailable) ? 'disabled' : '' }}>
                <span wire:loading wire:target="selectVariant, selectTipe, incrementQty, decrementQty" class="spinner-border spinner-border-sm mr-1" role="status"></span>
                <i wire:loading.remove wire:target="selectVariant, selectTipe, incrementQty, decrementQty" class="bi bi-bag-check-fill mr-1" style="font-size: 1.15rem;"></i> 
                Beli Sekarang
            </button>
        </div>
    </form>
</div>
