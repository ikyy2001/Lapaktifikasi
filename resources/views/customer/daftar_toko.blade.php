@extends('layout')

@section('title', 'Daftar Toko Marketplace')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

<div class="row mb-4">
    <div class="col-12">
        <div class="hero bg-primary text-white p-4 rounded shadow-sm">
            <div class="hero-inner">
                <h2>Selamat Datang di Marketplace Kami!</h2>
                <p class="lead">Temukan dan jelajahi berbagai toko seller terpercaya kami untuk mengunduh source code dan produk digital berkualitas tinggi.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @forelse($shops as $shop)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-primary card-hover shadow-sm" style="transition: transform 0.2s; border-radius: 8px; overflow: hidden;">
            <!-- Store Logo Header -->
            <div class="d-flex align-items-center justify-content-center bg-light p-4" style="height: 150px; border-bottom: 1px solid #eaeaea;">
                @if($shop->logo_toko)
                    <img src="{{ asset('assets/img/logo_toko/' . $shop->logo_toko) }}" alt="{{ $shop->nama_toko }}" style="max-height: 110px; max-width: 100%; object-fit: contain; border-radius: 4px;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="fas fa-store"></i>
                    </div>
                @endif
            </div>

            <!-- Card Body -->
            <div class="card-body d-flex flex-column" style="min-height: 200px;">
                <h5 class="card-title text-dark mb-1"><strong>{{ $shop->nama_toko }}</strong></h5>
                
                <div class="mb-2 d-flex align-items-center" style="gap: 5px;">
                    @php
                        $rating = (float) $shop->rating_rata_rata;
                        $jumlahReview = (int) $shop->jumlah_review;
                        $fullStars = floor($rating);
                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                        $emptyStars = 5 - $fullStars - $halfStar;
                    @endphp
                    <span class="text-warning" style="font-size: 0.9rem;">
                        {!! str_repeat('<i class="bi bi-star-fill"></i>', $fullStars) !!}
                        {!! $halfStar ? '<i class="bi bi-star-half"></i>' : '' !!}
                        {!! str_repeat('<i class="bi bi-star"></i>', $emptyStars) !!}
                    </span>
                    <small class="text-muted" style="font-size: 0.8rem;">({{ number_format($rating, 1) }} / {{ $jumlahReview }} ulasan)</small>
                </div>
                
                <p class="text-muted flex-grow-1" style="font-size: 0.9rem;">
                    {{ $shop->informasi_toko ? Str::limit($shop->informasi_toko, 120) : 'Belum ada deskripsi profil untuk toko ini.' }}
                </p>

                <div class="my-2" style="font-size: 0.85rem;">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-phone text-muted mr-2" style="width: 16px;"></i>
                        <span>{{ $shop->no_telp }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fab fa-telegram text-info mr-2" style="width: 16px;"></i>
                        <span class="text-info">@&nbsp;{{ $shop->akun_telegram }}</span>
                    </div>
                </div>

                <a href="{{ url('toko/' . $shop->slug . '/produk') }}" class="btn btn-primary btn-block mt-3">
                    <i class="fas fa-shopping-bag mr-1"></i> Kunjungi Toko
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="empty-state" data-height="300">
            <div class="empty-state-icon bg-secondary text-white">
                <i class="fas fa-store-slash"></i>
            </div>
            <h2>Belum ada toko aktif</h2>
            <p class="lead">Saat ini belum ada toko seller aktif yang dapat dikunjungi. Silakan periksa kembali nanti.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination Links -->
<div class="row">
    <div class="col-12 d-flex justify-content-center mt-3">
        {{ $shops->links() }}
    </div>
</div>

@endsection
