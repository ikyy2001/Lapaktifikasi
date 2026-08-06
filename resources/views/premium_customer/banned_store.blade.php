@extends('layout')

@section('title', 'Toko Dibanned')

@section('content')
<div class="row">
    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2">
        <div class="card card-danger mt-5 shadow-sm">
            <div class="card-header text-center">
                <h4 class="text-danger m-0" style="font-size: 1.5rem;"><i class="fas fa-store-slash"></i> Toko Telah Dibanned</h4>
            </div>
            <div class="card-body text-center">
                <p class="text-muted" style="font-size: 1.1rem;">Maaf, Anda tidak dapat mengakses toko <strong>{{ $nama_toko }}</strong> karena toko tersebut telah dibanned oleh administrator.</p>
                @if(isset($banned_reason) && $banned_reason)
                    <div class="alert alert-danger text-left mt-3">
                        <strong>Alasan:</strong> {{ $banned_reason }}
                    </div>
                @endif
                <div class="mt-4">
                    <a href="{{ route('daftar_toko') }}" class="btn btn-primary btn-lg" tabindex="4">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Toko
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
