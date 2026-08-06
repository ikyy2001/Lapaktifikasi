@extends('auth.layout')

@section('title', 'Akun Dibanned')

@section('content')
<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
        <div class="card card-danger mt-5">
            <div class="card-header">
                <h4 class="text-danger"><i class="fas fa-ban"></i> Akun Anda Telah Dibanned</h4>
            </div>
            <div class="card-body">
                <p class="text-muted">Maaf, akun Anda tidak dapat mengakses layanan kami karena telah dibanned oleh administrator.</p>
                @if(session('banned_reason'))
                    <div class="alert alert-danger">
                        <strong>Alasan:</strong> {{ session('banned_reason') }}
                    </div>
                @endif
                <div class="mt-4 text-center">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
