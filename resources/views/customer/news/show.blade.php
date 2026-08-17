@extends('layout')

@section('title', $news->judul)

@section('content')
<style>
    .news-detail-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 18px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden !important;
    }

    .news-detail-cover {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        border-radius: 14px;
        margin-bottom: 2rem;
    }

    .news-detail-title {
        font-size: 1.85rem !important;
        font-weight: 800 !important;
        line-height: 1.35 !important;
        color: #0f172a !important;
        margin-bottom: 0.75rem !important;
    }

    .news-detail-subtitle {
        font-size: 1.1rem !important;
        color: #64748b !important;
        line-height: 1.6 !important;
        margin-bottom: 1.5rem !important;
    }

    .news-meta-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.25rem;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.9rem;
        color: #64748b;
    }

    .news-content-body {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #334155;
    }

    .news-content-body p {
        margin-bottom: 1.25rem;
    }

    .news-content-body img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 1rem 0;
    }
</style>

<div class="container-fluid py-2">
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left mr-1"></i> Kembali ke Semua Berita
        </a>
    </div>

    <!-- Artikel Card -->
    <div class="news-detail-card p-4 p-md-5 mb-5 mx-auto" style="max-width: 960px;">
        <!-- 1. Gambar Besar di Atas -->
        @if($news->gambar)
            <img src="{{ $news->gambar_url }}" alt="{{ $news->judul }}" class="news-detail-cover shadow-sm" loading="lazy">
        @endif

        <!-- 2. Judul Berita -->
        <h1 class="news-detail-title">{{ $news->judul }}</h1>

        <!-- Subjudul jika ada -->
        @if($news->subjudul)
            <p class="news-detail-subtitle">{{ $news->subjudul }}</p>
        @endif

        <!-- 3. Info Penulis & Tanggal Publikasi -->
        <div class="news-meta-bar">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-circle mr-2 text-primary font-weight-bold" style="font-size: 1.1rem;"></i>
                <span>Ditulis oleh <strong>{{ $news->admin?->name ?? 'Admin Lapaktifikasi' }}</strong></span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar3 mr-2 text-muted"></i>
                <span>{{ $news->published_at ? $news->published_at->translatedFormat('d F Y - H:i') . ' WIB' : 'Draft' }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-clock mr-2 text-muted"></i>
                <span>{{ $news->published_at ? $news->published_at->diffForHumans() : '-' }}</span>
            </div>
        </div>

        <!-- 4. Konten Lengkap -->
        {{--
            SECURITY REMINDER:
            Jika input konten dari admin menggunakan Rich Text Editor (HTML), konten ditampilkan menggunakan {!! !!}.
            Pastikan input telah melewati sanitasi (misal: HTMLPurifier / strip_tags pada tag berbahaya)
            sebelum disimpan ke database untuk mencegah serangan XSS.
            Di bawah ini diformat agar mendukung paragraf teks maupun elemen HTML yang valid.
        --}}
        <div class="news-content-body">
            @if(strip_tags($news->konten) === $news->konten)
                {!! nl2br(e($news->konten)) !!}
            @else
                {!! $news->konten !!}
            @endif
        </div>
    </div>
</div>
@endsection
