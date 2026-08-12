@extends('auth.layout')

@section('title', 'Lupa Password - Lapaktifikasi')

@section('content')

<h2 class="auth-form-title">Lupa Password</h2>
<p class="auth-form-subtitle">Masukkan alamat email Anda di bawah ini untuk menerima tautan pemulihan kata sandi.</p>

@if(session()->has('status'))
<div class="alert alert-success text-center mb-3 py-2 small rounded-lg" role="alert">
    <i class="bi bi-check-circle-fill mr-1"></i> {{session()->get('status')}}
</div>
@endif

<form method="POST" action="{{route('password.email')}}">
    @csrf

    <div class="form-group mb-4">
        <label class="auth-label" for="email">Email Terdaftar</label>
        <div class="auth-input-group">
            <i class="bi bi-envelope auth-input-icon"></i>
            <input id="email" type="email" class="form-control auth-control @error('email') is-invalid @enderror" name="email"
                tabindex="1" autocomplete="off" value="{{old('email')}}" placeholder="nama@email.com" required>
        </div>
        @if ($errors->has('email'))
        <p class="text-danger small mt-1 mb-2"><i class="bi bi-exclamation-circle-fill mr-1"></i>
            {{ucfirst($errors->first('email'))}}
        </p>
        @endif
    </div>

    <div class="form-group mb-0">
        <button type="submit" class="btn btn-auth-primary" tabindex="4">
            <span>Kirim Link Reset</span>
            <i class="bi bi-send-fill ml-1 small"></i>
        </button>
    </div>
</form>

<div class="text-center mt-4 text-muted small">
    Ingat password Anda? <a href="{{url('/login')}}" class="auth-link">Kembali Login</a>
</div>

@endsection