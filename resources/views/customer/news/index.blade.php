@extends('layout')

@section('title', 'Berita & Informasi')

@section('content')
<style>
    /* Modern News Card Styling matching platform aesthetic */
    .news-card-link {
        display: block;
        color: inherit;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        height: 100%;
    }

    .news-card-link:hover {
        text-decoration: none !important;
        color: inherit !important;
    }

    .news-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03) !important;
        overflow: hidden !important;
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .news-card-link:hover .news-card {
        transform: translateY(-6px) scale(1.01) !important;
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.09) !important;
        border-color: #000000 !important;
    }

    .news-thumbnail-wrapper {
        width: 100% !important;
        aspect-ratio: 16 / 9 !important;
        background-color: #f1f5f9 !important;
        position: relative !important;
        overflow: hidden !important;
    }

    .news-thumbnail-img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        transition: transform 0.4s ease !important;
    }

    .news-card-link:hover .news-thumbnail-img {
        transform: scale(1.05);
    }

    .news-placeholder-img {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        color: #94a3b8;
        font-size: 2.2rem;
    }

    .news-card-body {
        padding: 1.25rem 1.4rem !important;
        display: flex !important;
        flex-direction: column !important;
        flex-grow: 1 !important;
    }

    .news-title {
        font-size: 1.05rem !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;
        color: #0f172a !important;
        margin-bottom: 0.5rem !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-subtitle {
        font-size: 0.875rem !important;
        color: #64748b !important;
        line-height: 1.5 !important;
        margin-bottom: 0 !important;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="container-fluid py-2">
    <!-- Header Banner -->
    <div class="mb-4">
        <h1 class="h3 font-weight-bold text-dark mb-1">Berita & Informasi Terbaru</h1>
        <p class="text-muted small mb-0">Temukan informasi update, promo spesial, dan berita seputar platform Lapaktifikasi.</p>
    </div>

    <!-- News Grid -->
    <div class="row">
        @forelse($newsList as $item)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <!-- Seluruh Card Clickable mengarah ke news.show -->
            <a href="{{ route('news.show', $item->slug) }}" class="news-card-link">
                <div class="news-card">
                    <!-- 1. Gambar -->
                    <div class="news-thumbnail-wrapper">
                        @if($item->gambar)
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="news-thumbnail-img" loading="lazy">
                        @else
                            <div class="news-placeholder-img">
                                <i class="bi bi-newspaper"></i>
                            </div>
                        @endif
                    </div>

                    <!-- 2. Konten Card: Judul & Subjudul -->
                    <div class="news-card-body">
                        <h2 class="news-title">{{ $item->judul }}</h2>
                        @if($item->subjudul)
                            <p class="news-subtitle">{{ $item->subjudul }}</p>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="py-5 bg-white rounded-lg shadow-sm border">
                <i class="bi bi-newspaper display-4 text-muted d-block mb-3" style="opacity: 0.35;"></i>
                <h5 class="font-weight-bold text-dark">Belum Ada Berita Terbaru</h5>
                <p class="text-muted small mb-0">Saat ini belum ada artikel atau pengumuman yang dipublikasikan. Silakan cek kembali nanti!</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($newsList->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $newsList->links() }}
    </div>
    @endif
</div>
@endsection
