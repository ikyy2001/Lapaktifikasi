@extends('layout')

@section('title', 'Beri Review Toko')

@section('content')

<style>
    .review-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        margin-top: 10px;
        animation: formFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .mono-card {
        background: #ffffff !important;
        border: 1px solid #000000 !important;
        border-radius: 16px !important;
        padding: 35px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        position: relative !important;
        overflow: hidden !important;
    }
    .mono-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: #000000;
    }
    .form-title {
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #000000;
        margin-bottom: 28px;
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 14px;
    }
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        font-size: 2.5rem;
        color: #e5e5e5;
        cursor: pointer;
        transition: color 0.15s ease-in-out;
        margin-right: 8px;
        margin-bottom: 0;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107;
    }
    .mono-btn-primary {
        background: #000000 !important;
        color: #ffffff !important;
        border: 1px solid #000000 !important;
        font-weight: 700 !important;
        font-size: 0.78rem !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        padding: 10px 20px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }
    .mono-btn-primary:hover {
        background: transparent !important;
        color: #000000 !important;
        text-decoration: none !important;
    }
    .mono-btn-secondary {
        background: #ffffff !important;
        color: #000000 !important;
        border: 1px solid #e5e5e5 !important;
        font-weight: 700 !important;
        font-size: 0.78rem !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        padding: 10px 20px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
    }
    .mono-btn-secondary:hover {
        background: #fafafa !important;
        text-decoration: none !important;
    }
    @keyframes formFadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container review-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mono-card">
                <h4 class="form-title">Tulis Review untuk Toko</h4>

                <!-- Info Produk & Toko -->
                <div class="mb-4 p-3 bg-light rounded border">
                    <div class="row">
                        <div class="col-sm-6">
                            <span class="text-muted d-block" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;">Toko</span>
                            <strong style="font-size: 1.05rem;">{{ $pembelian->varianLayanan->tipeLayanan->produk->toko->nama_toko }}</strong>
                        </div>
                        <div class="col-sm-6 mt-3 mt-sm-0">
                            <span class="text-muted d-block" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;">Produk / Paket</span>
                            <strong>{{ $pembelian->varianLayanan->tipeLayanan->produk->nama_produk }}</strong>
                            <span class="text-muted d-block" style="font-size: 0.85rem;">{{ $pembelian->varianLayanan->tipeLayanan->nama_tipe }} ({{ $pembelian->varianLayanan->nama_varian }})</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('premium.review.store', $pembelian->order_id) }}" method="POST">
                    @csrf

                    <!-- Rating Selector -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark mb-1">Berikan Rating</label>
                        <span class="text-danger">*</span>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" {{ old('rating') == 5 ? 'checked' : '' }} required />
                            <label for="star5" title="Sangat Baik"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == 4 ? 'checked' : '' }} />
                            <label for="star4" title="Baik"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == 3 ? 'checked' : '' }} />
                            <label for="star3" title="Cukup"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == 2 ? 'checked' : '' }} />
                            <label for="star2" title="Buruk"><i class="bi bi-star-fill"></i></label>
                            
                            <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == 1 ? 'checked' : '' }} />
                            <label for="star1" title="Sangat Buruk"><i class="bi bi-star-fill"></i></label>
                        </div>
                        @error('rating')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Komentar -->
                    <div class="form-group mb-4">
                        <label for="komentar" class="font-weight-bold text-dark mb-1">Komentar / Ulasan</label>
                        <span class="text-muted">(Opsional)</span>
                        <textarea class="form-control @error('komentar') is-invalid @enderror" id="komentar" name="komentar" rows="5" placeholder="Tuliskan ulasan Anda tentang kualitas produk dan layanan toko ini..." style="border-radius: 8px; border: 1px solid #ccc; font-size: 0.92rem; padding: 12px; resize: none;">{{ old('komentar') }}</textarea>
                        <small class="text-muted d-block mt-1">Maksimal 1000 karakter.</small>
                        @error('komentar')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('premium.riwayat') }}" class="mono-btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="mono-btn-primary">
                            <i class="bi bi-send-fill"></i> Kirim Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
