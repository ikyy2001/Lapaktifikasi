@extends('layout')

@section('title', 'Halaman Tidak Ditemukan - 404')

@section('content')
<style>
    .error-container {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px 20px;
    }
    .error-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 48px 36px;
        max-width: 520px;
        width: 100%;
    }
    .error-code {
        font-size: 5.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -2px;
        margin-bottom: 16px;
    }
    .error-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: -0.3px;
    }
    .error-desc {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 28px;
    }
    .btn-back-home {
        background-color: #000000;
        color: #ffffff;
        border: 2px solid #000000;
        font-weight: 700;
        font-size: 0.88rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
    }
    .btn-back-home:hover {
        background-color: transparent;
        color: #000000;
        text-decoration: none;
    }
</style>

<div class="error-container">
    <div class="error-card">
        <div class="error-code">404</div>
        <h3 class="error-title">Produk Tidak Ditemukan</h3>
        <p class="error-desc">
            Maaf, produk yang Anda cari mungkin sudah tidak aktif, dihapus oleh seller, atau alamat URL yang Anda masukkan salah.
        </p>
        <div>
            <a href="{{ route('premium.katalog') }}" class="btn-back-home">
                <i class="bi bi-arrow-left"></i> Kembali ke Katalog Produk
            </a>
        </div>
    </div>
</div>
@endsection
