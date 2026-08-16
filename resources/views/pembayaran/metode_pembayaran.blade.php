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
                            window.addEventListener("pageshow", function(event) {
                                if (event.persisted) {
                                    window.location.reload();
                                }
                            });

                            setInterval(function() {
                                fetch("{{ route('bukti_pembayaran.status_api', $pembelian->order_id) }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            window.location.href = "{{ route('premium.riwayat') }}";
                                        }
                                    })
                                    .catch(err => console.error('Polling error:', err));
                            }, 4000);
                        </script>

                    @elseif(isset($hasActiveTransaction) && $hasActiveTransaction && $pembelian->payment_gateway === 'tripay')
                        @php
                            $rawTripay = $tripayActiveDetail['raw_response'] ?? [];
                            $payCode = $rawTripay['pay_code'] ?? null;
                            $qrUrl = $rawTripay['qr_url'] ?? null;
                            $qrString = $rawTripay['qr_string'] ?? null;
                            $checkoutUrl = $rawTripay['checkout_url'] ?? null;
                            $paymentName = $rawTripay['payment_name'] ?? ($tripayActiveDetail['payment_type'] ?? 'TriPay');
                            $instructions = $rawTripay['instructions'] ?? [];
                        @endphp

                        <div class="card border-primary p-3 my-2">
                            <div class="text-center">
                                <span class="badge badge-warning px-3 py-2 text-uppercase mb-2"><i class="bi bi-clock-history mr-1"></i> Menunggu Pembayaran (TriPay)</span>
                                <h5 class="mt-2 text-dark">{{ $paymentName }}</h5>
                                <h4 class="font-weight-bold text-primary mb-3">Rp {{ number_format($pembelian->harga_saat_beli, 0, ',', '.') }}</h4>

                                @if($qrUrl)
                                    <div class="my-3">
                                        <p class="mb-2 text-muted">Scan kode QRIS di bawah ini dengan aplikasi E-Wallet atau Mobile Banking Anda:</p>
                                        <img src="{{ $qrUrl }}" alt="QRIS TriPay" class="img-fluid rounded border shadow-sm p-2 bg-white" style="max-width: 250px;">
                                    </div>
                                @elseif($qrString)
                                    <div class="my-3">
                                        <p class="mb-2 text-muted">Scan kode QRIS di bawah ini:</p>
                                        <div class="d-inline-block p-2 bg-white rounded border shadow-sm">
                                            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(220)->generate($qrString) !!}
                                        </div>
                                    </div>
                                @endif

                                @if($payCode)
                                    <div class="card bg-light border p-3 my-3 mx-auto text-center" style="max-width: 400px;">
                                        <small class="text-muted text-uppercase font-weight-bold">Nomor Virtual Account / Kode Bayar</small>
                                        <h3 class="font-weight-bold text-dark my-2 text-monospace" id="tripay-pay-code">{{ $payCode }}</h3>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyTripayCode()">
                                                <i class="bi bi-clipboard mr-1"></i> Salin Kode Bayar
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @if($checkoutUrl)
                                    <div class="my-3">
                                        <a href="{{ $checkoutUrl }}" target="_blank" class="btn btn-success px-4 py-2">
                                            <i class="bi bi-box-arrow-up-right mr-1"></i> Buka Halaman Pembayaran TriPay
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if(!empty($instructions))
                                <div class="mt-4">
                                    <h6 class="font-weight-bold mb-2 text-dark"><i class="bi bi-card-checklist mr-1"></i> Panduan Cara Pembayaran:</h6>
                                    <div class="accordion" id="tripayInstructionsAccordion">
                                        @foreach($instructions as $idx => $instr)
                                            <div class="card mb-2 border shadow-none">
                                                <div class="card-header p-2 bg-light" id="heading-{{ $idx }}">
                                                    <h6 class="mb-0">
                                                        <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none" type="button" data-toggle="collapse" data-target="#collapse-{{ $idx }}" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
                                                            <i class="bi bi-chevron-down mr-2 text-primary"></i> {{ $instr['title'] ?? ('Instruksi ' . ($idx + 1)) }}
                                                        </button>
                                                    </h6>
                                                </div>
                                                <div id="collapse-{{ $idx }}" class="collapse {{ $idx === 0 ? 'show' : '' }}" data-parent="#tripayInstructionsAccordion">
                                                    <div class="card-body py-2 px-3">
                                                        <ol class="pl-3 mb-0 text-muted">
                                                            @foreach($instr['steps'] ?? [] as $step)
                                                                <li class="mb-1">{!! $step !!}</li>
                                                            @endforeach
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="text-center mt-3">
                                <button onclick="window.location.reload();" class="btn btn-outline-primary d-block mx-auto mb-2" style="max-width: 250px;">
                                    <i class="bi bi-arrow-clockwise mr-1"></i> Cek Status Manual
                                </button>
                                <a href="{{ url('/menu_produk') }}" class="btn btn-danger mt-1">Kembali</a>
                            </div>
                        </div>

                        <script>
                            function copyTripayCode() {
                                const codeText = document.getElementById('tripay-pay-code').innerText.trim();
                                navigator.clipboard.writeText(codeText).then(() => {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Kode berhasil disalin ke clipboard!',
                                        showConfirmButton: false,
                                        timer: 2500
                                    });
                                });
                            }

                            window.addEventListener("pageshow", function(event) {
                                if (event.persisted) {
                                    window.location.reload();
                                }
                            });

                            setInterval(function() {
                                fetch("{{ route('bukti_pembayaran.status_api', $pembelian->order_id) }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            window.location.href = "{{ route('premium.riwayat') }}";
                                        }
                                    })
                                    .catch(err => console.error('Polling error:', err));
                            }, 4000);
                        </script>

                    @else
                        @php
                            $activeGateways = \App\Models\SettingWebsite::getActiveGateways();
                        @endphp

                        @if(empty($activeGateways))
                            <div class="alert alert-danger text-center my-3">
                                <i class="bi bi-exclamation-triangle-fill mr-1"></i> Saat ini tidak ada metode gateway pembayaran yang aktif. Silakan hubungi admin.
                            </div>
                        @else
                            <div class="form-group">
                                <label for="metode_pembayaran" class="font-weight-bold">Pilih Gateway Pembayaran</label>

                                <select class="form-control mb-3" id="metode_pembayaran" name="gateway">
                                    @if(in_array('midtrans', $activeGateways))
                                    <option value="midtrans">Midtrans (Pop-up Snap: QRIS, VA, E-Wallet, Kartu)</option>
                                    @endif
                                    @if(isset($pembelian))
                                        @if(in_array('tripay', $activeGateways))
                                        <option value="tripay">TriPay (Pilih QRIS, Virtual Account, Minimarket, E-Wallet)</option>
                                        @endif
                                        @if(in_array('pakasir', $activeGateways))
                                        <option value="pakasir">Pakasir (QRIS Direct)</option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                        @endif

                        @if(isset($pembelian))
                        <div id="tripay-channel-container" class="form-group d-none">
                            <label for="tripay_channel" class="font-weight-bold">Pilih Channel Pembayaran TriPay</label>
                            
                            @php
                                $groupedChannels = [];
                                if (!empty($tripayChannels)) {
                                    foreach ($tripayChannels as $ch) {
                                        if (!empty($ch['active'])) {
                                            $group = $ch['group'] ?? 'Lainnya';
                                            $groupedChannels[$group][] = $ch;
                                        }
                                    }
                                }
                            @endphp

                            <select class="form-control mb-2" id="tripay_channel" name="channel">
                                @if(!empty($groupedChannels))
                                    @foreach($groupedChannels as $grpName => $channels)
                                        <optgroup label="{{ $grpName }}">
                                            @foreach($channels as $channel)
                                                <option value="{{ $channel['code'] }}">{{ $channel['name'] }} ({{ $channel['code'] }})</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @else
                                    <optgroup label="QRIS & E-Wallet">
                                        <option value="QRIS">QRIS (Semua E-Wallet / Mobile Banking)</option>
                                        <option value="OVO">OVO</option>
                                        <option value="DANA">DANA</option>
                                        <option value="SHOPEEPAY">ShopeePay</option>
                                    </optgroup>
                                    <optgroup label="Virtual Account">
                                        <option value="BRIVA">BRI Virtual Account</option>
                                        <option value="BCAVA">BCA Virtual Account</option>
                                        <option value="BNIVA">BNI Virtual Account</option>
                                        <option value="MANDIRIVA">Mandiri Virtual Account</option>
                                        <option value="PERMATAVA">Permata Virtual Account</option>
                                        <option value="CIMBVA">CIMB Niaga Virtual Account</option>
                                        <option value="BSIVA">BSI Virtual Account</option>
                                    </optgroup>
                                    <optgroup label="Convenience Store">
                                        <option value="ALFAMART">Alfamart</option>
                                        <option value="INDOMARET">Indomaret</option>
                                    </optgroup>
                                @endif
                            </select>
                            <small class="text-muted"><i class="bi bi-info-circle mr-1"></i>Pilih salah satu metode pembayaran yang Anda inginkan melalui TriPay.</small>
                        </div>
                        @endif

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
                if (document.getElementById("pay-button")) {
                    document.getElementById("pay-button").disabled = true;
                }
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
    const gatewaySelect = document.getElementById("metode_pembayaran");
    const tripayContainer = document.getElementById("tripay-channel-container");

    function updateTripayVisibility() {
        if (!gatewaySelect || !tripayContainer) return;
        if (gatewaySelect.value === "tripay") {
            tripayContainer.classList.remove("d-none");
        } else {
            tripayContainer.classList.add("d-none");
        }
    }

    if (gatewaySelect && tripayContainer) {
        gatewaySelect.addEventListener("change", updateTripayVisibility);
        updateTripayVisibility();
    }

    document.getElementById("pay-button").onclick = function () {
        if (!gatewaySelect) {
            Swal.fire("Error", "Metode pembayaran tidak tersedia.", "error");
            return;
        }
        const gateway = gatewaySelect.value;
        const channel = document.getElementById("tripay_channel") ? document.getElementById("tripay_channel").value : 'QRIS';
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
            body: JSON.stringify({ 
                gateway: gateway,
                channel: channel
            })
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
                        setTimeout(() => { window.location.href = "{{ route('premium.riwayat') }}"; }, 1000);
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
            } else if (gateway === 'tripay' && data.success) {
                if (data.data && data.data.checkout_url && !data.data.pay_code && !data.data.qr_url) {
                    window.location.href = data.data.checkout_url;
                } else {
                    window.location.reload();
                }
            }
        })
        .catch(err => {
            btn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            Swal.fire("Error", "Pembayaran gagal dibuat, silakan coba lagi.", "error");
        });
    };
</script>
@elseif(isset($snapToken) && !isset($pembelian))
<script>
    document.getElementById("pay-button").onclick = function () {
        snap.pay("{{ $snapToken }}", {
            onSuccess: function (result) {
                Swal.fire("Sukses", "Pembayaran midtrans berhasil.", "success");
                setTimeout(() => { window.location.href = "{{ route('premium.riwayat') }}"; }, 1000);
            },
            onPending: function (result) { Swal.fire("Pending", "Pembayaran Anda pending.", "warning"); },
            onError: function (result) { Swal.fire("Gagal", "Pembayaran Anda gagal.", "error"); }
        });
    };
</script>
@endif

@endsection