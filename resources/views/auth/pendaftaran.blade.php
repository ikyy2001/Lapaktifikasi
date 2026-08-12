@extends('auth.layout')

@section('title', 'Pendaftaran - Lapaktifikasi')

@section('content')

<h2 class="auth-form-title">Buat Akun Baru</h2>
<p class="auth-form-subtitle">Lengkapi formulir di bawah ini untuk mendaftar akun Lapaktifikasi.</p>

@if(request('ref') || (isset($ref) && $ref))
<div class="alert alert-info py-2 px-3 text-center mb-3 small rounded-lg" style="background-color: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af;">
    <i class="bi bi-gift-fill mr-1"></i> Kode Referral Aktif: <strong class="text-uppercase font-weight-bold">{{ request('ref') ?? $ref }}</strong>
</div>
@endif

<form method="POST" action="{{url('proses_pendaftaran')}}">
    @csrf

    <input type="hidden" name="ref" value="{{ old('ref', request('ref') ?? ($ref ?? '')) }}">

    <div class="form-group mb-3">
        <label class="auth-label" for="email">Email</label>
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

    <div class="form-group mb-3">
        <label class="auth-label" for="password">Password</label>
        <div class="auth-input-group">
            <i class="bi bi-lock auth-input-icon"></i>
            <input id="password" type="password" class="form-control auth-control has-toggle @error('password') is-invalid @enderror"
                name="password" tabindex="2" autocomplete="off" value="{{old('password')}}" placeholder="Minimal 10 karakter" required>
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
        <label class="auth-label" for="kode_referral_input">Kode Referral <small class="text-muted text-transform-none font-weight-normal">(Opsional)</small></label>
        <div class="auth-input-group">
            <i class="bi bi-ticket-perforated auth-input-icon"></i>
            <input id="kode_referral_input" type="text" class="form-control auth-control text-uppercase font-monospace"
                name="ref_display" tabindex="3" placeholder="Contoh: REF-ABC123" 
                value="{{ old('ref', request('ref') ?? ($ref ?? '')) }}"
                oninput="document.querySelector('input[name=ref]').value = this.value.toUpperCase()">
        </div>
    </div>

    <div class="form-group mb-3">
        <button type="submit" class="btn btn-auth-primary" tabindex="4">
            <span>Daftar Akun Baru</span>
            <i class="bi bi-arrow-right-short fs-5"></i>
        </button>
    </div>

    <div class="auth-divider">
        <span>atau daftar instan dengan</span>
    </div>

    <div class="form-group mb-0">
        <a href="{{url('redirect') . (request('ref') ? '?ref=' . request('ref') : '')}}" class="btn btn-auth-google" tabindex="5">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z" fill="#4285F4"/>
                <path d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.15C3.26 21.3 7.31 24 12 24z" fill="#34A853"/>
                <path d="M5.28 14.24A7.32 7.32 0 0 1 4.9 12c0-.83.14-1.63.38-2.24V6.61H1.29A11.97 11.97 0 0 0 0 12c0 1.92.46 3.74 1.29 5.39l3.99-3.15z" fill="#FBBC05"/>
                <path d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.61l3.99 3.15c.95-2.85 3.6-4.96 6.72-4.96z" fill="#EA4335"/>
            </svg>
            <span>Daftar dengan Google</span>
        </a>
    </div>
</form>

<div class="text-center mt-4 text-muted small">
    Sudah memiliki akun? <a href="{{url('/login')}}" class="auth-link">Login Di Sini</a>
</div>
@endsection