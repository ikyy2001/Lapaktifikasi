@extends('auth.layout')

@section('title', 'Reset Password - Lapaktifikasi')

@section('content')

<h2 class="auth-form-title">Reset Password</h2>
<p class="auth-form-subtitle">Buat kata sandi baru yang aman untuk akun Anda.</p>

@if($errors->has('token') || $errors->has('email'))
<div class="alert alert-danger text-center mb-3 py-2 small rounded-lg" role="alert">
    <i class="bi bi-exclamation-triangle-fill mr-1"></i> Proses reset password gagal dilakukan atau token kedaluwarsa.
</div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ request()->token }}">
    <input type="hidden" name="email" value="{{ request()->email }}">

    <div class="form-group mb-3">
        <label class="auth-label" for="password">Password Baru</label>
        <div class="auth-input-group">
            <i class="bi bi-lock auth-input-icon"></i>
            <input id="password" type="password" class="form-control auth-control has-toggle @error('password') is-invalid @enderror"
                name="password" tabindex="1" autocomplete="off" placeholder="••••••••" required>
            <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password', this)" title="Tampilkan/Sembunyikan Password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @if ($errors->has('password'))
        <p class="text-danger small mt-1 mb-2"><i class="bi bi-exclamation-circle-fill mr-1"></i>
            {{ucfirst($errors->first('password'))}}
        </p>
        @endif
    </div>

    <div class="form-group mb-4">
        <label class="auth-label" for="password_confirmation">Konfirmasi Password Baru</label>
        <div class="auth-input-group">
            <i class="bi bi-lock-fill auth-input-icon"></i>
            <input id="password_confirmation" type="password"
                class="form-control auth-control has-toggle @error('password_confirmation') is-invalid @enderror"
                name="password_confirmation" tabindex="2" autocomplete="off" placeholder="••••••••" required>
            <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)" title="Tampilkan/Sembunyikan Password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        @if ($errors->has('password_confirmation'))
        <p class="text-danger small mt-1 mb-2"><i class="bi bi-exclamation-circle-fill mr-1"></i>
            {{ucfirst($errors->first('password_confirmation'))}}
        </p>
        @endif
    </div>

    <div class="form-group mb-0">
        <button type="submit" class="btn btn-auth-primary" tabindex="4">
            <span>Simpan Password Baru</span>
            <i class="bi bi-shield-lock-fill ml-1"></i>
        </button>
    </div>
</form>

<div class="text-center mt-4 text-muted small">
    Batal reset password? <a href="{{url('/login')}}" class="auth-link">Kembali Login</a>
</div>
@endsection