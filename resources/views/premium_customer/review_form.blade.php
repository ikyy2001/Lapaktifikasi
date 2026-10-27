@extends('layout')

@section('title', 'Beri Review Toko')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/customer-review.css') }}">
@endpush

@section('content')

<div class="container review-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mono-card">
                <h4 class="form-title">Tulis Review untuk Toko</h4>

                <!-- Info Produk & Toko -->
                <div class="mb-4 p-3 bg-light rounded border">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3 mb-sm-0">
                            <span class="text-muted d-block" style="font-size: clamp(0.7rem, 2vw, 0.78rem); text-transform: uppercase; letter-spacing: 0.5px;">Toko</span>
                            <strong style="font-size: clamp(0.9rem, 3vw, 1.05rem); word-break: break-word;">{{ $pembelian->varianLayanan->tipeLayanan->produk->toko->nama_toko }}</strong>
                        </div>
                        <div class="col-12 col-sm-6">
                            <span class="text-muted d-block" style="font-size: clamp(0.7rem, 2vw, 0.78rem); text-transform: uppercase; letter-spacing: 0.5px;">Produk / Paket</span>
                            <strong style="font-size: clamp(0.85rem, 2.5vw, 1rem); word-break: break-word;">{{ $pembelian->varianLayanan->tipeLayanan->produk->nama_produk }}</strong>
                            <span class="text-muted d-block" style="font-size: clamp(0.75rem, 2.5vw, 0.85rem); word-break: break-word;">{{ $pembelian->varianLayanan->tipeLayanan->nama_tipe }} ({{ $pembelian->varianLayanan->nama_varian }})</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('premium.review.store', $pembelian->order_id) }}" method="POST" style="width: 100%;">
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
                    <div class="form-group mb-4" style="width: 100%;">
                        <label for="komentar" class="font-weight-bold text-dark mb-1" style="font-size: clamp(0.85rem, 2.5vw, 1rem);">Komentar / Ulasan</label>
                        <span class="text-muted" style="font-size: clamp(0.75rem, 2vw, 0.85rem);">(Opsional)</span>
                        <textarea class="form-control @error('komentar') is-invalid @enderror w-100" id="komentar" name="komentar" rows="5" placeholder="Tuliskan ulasan Anda..." style="border-radius: 8px; border: 1px solid #ccc; font-size: clamp(0.85rem, 2.5vw, 0.92rem); padding: 12px; resize: none; width: 100%; box-sizing: border-box;"></textarea>
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
