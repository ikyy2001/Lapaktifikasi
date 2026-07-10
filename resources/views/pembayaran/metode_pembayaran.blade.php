@extends('layout')

@section('title', 'Metode Pembayaran')

@section('content')

<div class="container mb-3">

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
        icon: "info",
        title: "{{ $success }}"
        });
    </script>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Metode Pembayaran</h4>
                </div>

                <div class="card-body">
                    @if(isset($pembelian))
                    <div class="alert alert-info">
                        <h5>Detail Pesanan</h5>
                        <p class="mb-1"><strong>Varian Layanan:</strong> {{ $varian->tipeLayanan->nama_tipe }} - {{ $varian->nama_varian }}</p>
                        <p class="mb-0"><strong>Total Tagihan:</strong> Rp {{ number_format($pembelian->harga_saat_beli, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="alert alert-warning text-center my-3">
                        <h6 class="mb-1"><i class="bi bi-clock-history mx-1"></i>Sisa Waktu Pembayaran</h6>
                        <h3 id="countdown-timer" class="font-weight-bold mb-0">15:00</h3>
                        <small class="text-muted">Lakukan pembayaran sebelum batas waktu berakhir agar pesanan tidak otomatis dibatalkan.</small>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="metode_pembayaran">Pilih metode pembayaran</label>

                        <select class="form-control mb-3" id="metode_pembayaran">
                            <option value="midtrans">Midtrans</option>
                        </select>
                        <i>* Saat ini hanya tersedia metode pembayaran dengan Midtrans.</i>
                    </div>

                    <a href="{{url('/menu_produk')}}" class="btn btn-danger mt-3 me-3">Kembali</a>
                    <button type="button" id="pay-button" class="btn btn-success mt-3 float-right">Pilih
                        Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($reserved_expired_at))
<script>
    (function() {
        const expiredTime = new Date("{{ $reserved_expired_at->toIso8601String() }}").getTime();
        
        const timerInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = expiredTime - now;
            
            if (distance < 0) {
                clearInterval(timerInterval);
                document.getElementById("countdown-timer").innerHTML = "EXPIRED";
                document.getElementById("pay-button").disabled = true;
                Swal.fire({
                    title: "Waktu Habis",
                    text: "Batas waktu pembayaran telah habis. Silakan buat pesanan baru.",
                    icon: "error"
                }).then(() => {
                    window.location.href = "{{ url('menu_produk') }}";
                });
                return;
            }
            
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById("countdown-timer").innerHTML = 
                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                (seconds < 10 ? "0" + seconds : seconds);
        }, 1000);
    })();
</script>
@endif

@endsection