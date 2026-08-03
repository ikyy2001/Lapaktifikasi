@extends('auth.layout')

@section('title', 'Pendaftaran - Lapaktifikasi')

@section('content')

<div class="card card-primary">
    <div class="card-body">
        <h4 class="text-center text-primary font-weight-bold mb-3">Pendaftaran Akun</h4>
        
        @if(request('ref') || (isset($ref) && $ref))
        <div class="alert alert-info py-2 text-center mb-3" style="font-size: 0.85rem;">
            <i class="bi bi-gift-fill mr-1"></i> Anda mendaftar menggunakan Kode Referral: <strong class="font-monospace text-uppercase">{{ request('ref') ?? $ref }}</strong>
        </div>
        @endif

        <form method="POST" action="{{url('proses_pendaftaran')}}">
            @csrf

            <input type="hidden" name="ref" value="{{ old('ref', request('ref') ?? ($ref ?? '')) }}">

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    tabindex="1" autocomplete="off" value="{{old('email')}}" placeholder="nama@email.com" required>

                @if ($errors->has('email'))
                <p class="mt-2 text-danger small"><i class="bi bi-exclamation-octagon-fill"></i>
                    {{ucfirst($errors->first('email'))}}
                </p>
                @endif
            </div>

            <div class="form-group mb-3">
                <div class="d-block">
                    <label for="password" class="control-label">Password</label>
                </div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" tabindex="2" autocomplete="off" value="{{old('password')}}" placeholder="Minimal 10 karakter" required>

                @if ($errors->has('password'))
                <p class="mt-2 text-danger small"><i class="bi bi-exclamation-octagon-fill"></i>
                    {{ucfirst($errors->first('password'))}}
                </p>
                @endif
            </div>

            <div class="form-group mb-4">
                <label for="kode_referral_input">Kode Referral <small class="text-muted">(Opsional)</small></label>
                <input id="kode_referral_input" type="text" class="form-control text-uppercase font-monospace"
                    name="ref_display" tabindex="3" placeholder="Misal: REF-ABC123" 
                    value="{{ old('ref', request('ref') ?? ($ref ?? '')) }}"
                    oninput="document.querySelector('input[name=ref]').value = this.value.toUpperCase()">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Daftar Akun Baru
                </button>
            </div>

            <hr />

            <div class="text-center mb-3 text-muted small">Atau daftar instan dengan</div>

            <div class="form-group">
                <a href="{{url('redirect') . (request('ref') ? '?ref=' . request('ref') : '')}}" class="btn btn-light btn-lg btn-block border" tabindex="5">
                    <i class="bi bi-google text-danger mr-1"></i> Daftar dengan Google
                </a>
            </div>
        </form>
    </div>
</div>

<div class="text-muted text-center mb-3">
    Sudah punya akun? <a href="{{url('/login')}}">Login Di Sini</a>
</div>
@endsection