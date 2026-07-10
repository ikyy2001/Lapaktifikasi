@extends('layout')

@section('title', 'Katalog Akun Premium')

@section('content')

@if($error = Session::get('error'))
<script>
    Swal.fire({ title: "Gagal", text: "{{ $error }}", icon: "error" });
</script>
@endif

<div class="row">
    @forelse($produk as $item)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-primary">
            <div class="card-header">
                <h4>{{ $item->nama_produk }}</h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @if($item->gambar)
                    <img src="{{ asset('assets/img/produk_premium/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="img-fluid rounded" style="max-height: 150px;">
                    @else
                    <div class="bg-light d-flex justify-content-center align-items-center rounded" style="height: 150px;">
                        <i class="bi bi-music-note-beamed text-muted" style="font-size: 3rem;"></i>
                    </div>
                    @endif
                </div>
                <p>{{ $item->deskripsi ?? 'Aplikasi premium resmi.' }}</p>
                <hr>
                
                @foreach($item->tipeLayanan as $tipe)
                <div class="mb-3">
                    <h6 class="text-primary"><i class="bi bi-tag-fill mr-2"></i>Tipe: {{ $tipe->nama_tipe }}</h6>
                    
                    @foreach($tipe->varianLayanan as $varian)
                    <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded mb-2 border">
                        <div>
                            <strong>{{ $varian->nama_varian }}</strong> <br>
                            <span class="text-success font-weight-bold">Rp {{ number_format($varian->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-right">
                            @if($varian->stok_tersedia > 0)
                            <span class="badge badge-success mb-1">Stok: {{ $varian->stok_tersedia }}</span>
                            <form action="{{ url('/proses_checkout_premium') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_varian" value="{{ $varian->id_varian }}">
                                <button type="submit" class="btn btn-sm btn-primary btn-block">Beli Sekarang</button>
                            </form>
                            @else
                            <span class="badge badge-danger mb-1 d-block text-center">Stok Habis</span>
                            <button class="btn btn-sm btn-secondary btn-block" disabled>Beli Sekarang</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center my-5">
        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
        <h5 class="text-muted mt-3">Katalog produk premium sedang kosong.</h5>
    </div>
    @endforelse
</div>

@endsection
