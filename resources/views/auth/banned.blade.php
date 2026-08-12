@extends('auth.layout')

@section('title', 'Akun Dibanned - Lapaktifikasi')

@section('content')
<h2 class="auth-form-title text-danger"><i class="bi bi-slash-circle mr-1"></i> Akun Dibanned</h2>
<p class="auth-form-subtitle">Maaf, akun Anda tidak dapat mengakses layanan kami saat ini.</p>

<div class="alert alert-danger p-3 mb-4 rounded-lg" role="alert" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
    <h6 class="font-weight-bold mb-1"><i class="bi bi-exclamation-octagon-fill mr-1"></i> Status Penangguhan:</h6>
    @if(session('banned_reason'))
        <p class="mb-0 small"><strong>Alasan:</strong> {{ session('banned_reason') }}</p>
    @else
        <p class="mb-0 small">Akun Anda telah dinonaktifkan oleh administrator karena pelanggaran ketentuan layanan.</p>
    @endif
</div>

<div class="mt-4">
    <a href="{{ url('/') }}" class="btn btn-auth-primary">
        <i class="bi bi-house-door-fill mr-1"></i>
        <span>Kembali ke Halaman Utama</span>
    </a>
</div>
@endsection
