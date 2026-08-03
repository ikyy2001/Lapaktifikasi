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

                    @if(isset($hasActiveTransaction) && $hasActiveTransaction && $pembelian->payment_gateway === 'pakasir')
                        <div class="alert alert-primary text-center">
                            <h5>Status: Menunggu Pembayaran</h5>
                            <p>Anda sebelumnya telah memilih pembayaran via Pakasir. Sistem akan otomatis mengecek status pembayaran Anda.</p>
                            
                            @php
                                $slug = config('pakasir.project_slug');
                                $amount = (int) $pembelian->harga_saat_beli;
                                $redirectUrl = rtrim(config('pakasir.base_url', 'https://app.pakasir.com'), '/') . "/pay/{$slug}/{$amount}?order_id={$pembelian->order_id}";
                            @endphp
                            <a href="{{ $redirectUrl }}" class="btn btn-success mt-3 mb-2 d-block mx-auto" style="max-width: 250px;">Buka Halaman Pembayaran</a>
                            
                            <button onclick="window.location.reload();" class="btn btn-outline-primary d-block mx-auto mb-3" style="max-width: 250px;">Cek Status Manual</button>

                            <a href="{{url('/menu_produk')}}" class="btn btn-danger mt-1">Kembali</a>
                        </div>
                        
                        <script>
                            // Force reload if loaded from browser cache (back button)
                            window.addEventListener("pageshow", function(event) {
                                if (event.persisted) {
                                    window.location.reload();
                                }
                            });

                            // Auto poll status every 5 seconds
                            setInterval(function() {
                                fetch("{{ route('bukti_pembayaran.status_api', $pembelian->order_id) }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            window.location.href = "{{ url('bukti_pembayaran') }}";
                                        }
                                    })
                                    .catch(err => console.error('Polling error:', err));
                            }, 5000);
                        </script>
                    @else
                        <div class="form-group">
                            <label for="metode_pembayaran">Pilih metode pembayaran</label>

                            <select class="form-control mb-3" id="metode_pembayaran" name="gateway">
                                <option value="midtrans">Midtrans</option>
                                @if(isset($pembelian))
                                <option value="pakasir">Pakasir (QRIS)</option>
                                @endif
                            </select>
                        </div>

                        <a href="{{url('/menu_produk')}}" class="btn btn-danger mt-3 me-3">Kembali</a>
                        <button type="button" id="pay-button" class="btn btn-success mt-3 float-right">
                            <span id="pay-text">Pilih Pembayaran</span>
                            <span id="pay-loading" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    @endif
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

@if(isset($pembelian) && (!isset($hasActiveTransaction) || !$hasActiveTransaction))
<script>
    document.getElementById("pay-button").onclick = function () {
        const gateway = document.getElementById("metode_pembayaran").value;
        const btnText = document.getElementById("pay-text");
        const btnLoading = document.getElementById("pay-loading");
        const btn = document.getElementById("pay-button");
        
        btn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        
        fetch("{{ route('metode_pembayaran.generate', $pembelian->order_id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ gateway: gateway })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            
            if (data.error) {
                Swal.fire("Error", data.error, "error");
                return;
            }
            
            if (gateway === 'midtrans' && data.snapToken) {
                snap.pay(data.snapToken, {
                    onSuccess: function (result) {
                        Swal.fire("Sukses", "Pembayaran midtrans berhasil.", "success");
                        setTimeout(() => { window.location.href = "{{ url('bukti_pembayaran') }}"; }, 3000);
                    },
                    onPending: function (result) { Swal.fire("Pending", "Pembayaran Anda pending.", "warning"); },
                    onError: function (result) { Swal.fire("Gagal", "Pembayaran Anda gagal.", "error"); },
                    onClose: function () { Swal.fire("Tertutup", "Anda menutup popup tanpa menyelesaikan pembayaran.", "info"); }
                });
            } else if (gateway === 'pakasir' && data.success) {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.reload();
                }
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            Swal.fire("Error", "Terjadi kesalahan pada server.", "error");
        });
    };
</script>
@elseif(isset($snapToken) && !isset($pembelian))
<script>
    document.getElementById("pay-button").onclick = function () {
        snap.pay("{{ $snapToken }}", {
            onSuccess: function (result) {
                Swal.fire("Sukses", "Pembayaran midtrans berhasil.", "success");
                setTimeout(() => { window.location.href = "{{ url('bukti_pembayaran') }}"; }, 3000);
            },
            onPending: function (result) { Swal.fire("Pending", "Pembayaran Anda pending.", "warning"); },
            onError: function (result) { Swal.fire("Gagal", "Pembayaran Anda gagal.", "error"); }
        });
    };
</script>
@endif

@endsection