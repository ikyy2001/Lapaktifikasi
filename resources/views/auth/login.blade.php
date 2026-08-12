@extends('auth.layout')

@section('title', 'Login - Lapaktifikasi')

@section('content')

@if($success = Session::get('success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    Toast.fire({
        icon: "success",
        title: "{{ $success }}"
    });
</script>

@elseif($error = Session::get('error'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    Toast.fire({
        icon: "error",
        title: "{{ $error }}"
    });
</script>
@endif

<h2 class="auth-form-title">Selamat Datang</h2>
<p class="auth-form-subtitle">Silakan masukkan email dan password untuk masuk ke akun Anda.</p>

@if($logout = Session::get('logout'))
<div class="alert alert-success text-center mb-3 py-2 small rounded-lg" role="alert">
    <i class="bi bi-check-circle-fill mr-1"></i> {{ $logout }}
</div>

@elseif($status = Session::get('status'))
<div class="alert alert-success text-center mb-3 py-2 small rounded-lg" role="alert">
    <i class="bi bi-check-circle-fill mr-1"></i> Reset password berhasil. Silakan login terlebih dahulu.
</div>
@endif

<form method="POST" action="{{url('proses_login')}}">
    @csrf

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
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="auth-label mb-0" for="password">Password</label>
            <a href="{{url('lupa_password')}}" class="auth-link small">Lupa Password?</a>
        </div>
        <div class="auth-input-group">
            <i class="bi bi-lock auth-input-icon"></i>
            <input id="password" type="password" class="form-control auth-control has-toggle @error('password') is-invalid @enderror"
                name="password" tabindex="2" autocomplete="off" placeholder="••••••••" required>
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
        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me"
                @if(old('remember')) checked @endif>
            <label class="custom-control-label" for="remember-me">Ingat saya di perangkat ini</label>
        </div>
    </div>

    <div class="form-group mb-3">
        <button type="submit" class="btn btn-auth-primary" tabindex="4">
            <span>Masuk ke Akun</span>
            <i class="bi bi-arrow-right-short fs-5"></i>
        </button>
    </div>

    <div class="auth-divider">
        <span>atau masuk dengan</span>
    </div>

    <div class="form-group mb-0">
        <a href="{{url('redirect')}}" class="btn btn-auth-google" tabindex="5">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z" fill="#4285F4"/>
                <path d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.15C3.26 21.3 7.31 24 12 24z" fill="#34A853"/>
                <path d="M5.28 14.24A7.32 7.32 0 0 1 4.9 12c0-.83.14-1.63.38-2.24V6.61H1.29A11.97 11.97 0 0 0 0 12c0 1.92.46 3.74 1.29 5.39l3.99-3.15z" fill="#FBBC05"/>
                <path d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.61l3.99 3.15c.95-2.85 3.6-4.96 6.72-4.96z" fill="#EA4335"/>
            </svg>
            <span>Lanjutkan dengan Google</span>
        </a>
    </div>
</form>

<div class="text-center mt-4 text-muted small">
    <p class="mb-1">Belum memiliki akun? <a href="{{url('/pendaftaran')}}" class="auth-link">Daftar Sekarang</a></p>
    <p class="mb-0 text-muted" style="font-size: 0.78rem; opacity: 0.85;">Lapaktifikasi Part Of Cipta Cerita Bersama Group</p>
</div>

@endsection