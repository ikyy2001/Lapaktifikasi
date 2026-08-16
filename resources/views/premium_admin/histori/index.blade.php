@extends('layout')

@section('title', 'Histori Penjualan Akun Premium')

@section('content')

@if($error = Session::get('error'))
<script>
    Swal.fire({ title: "Gagal", text: "{{ $error }}", icon: "error" });
</script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap pb-0" style="gap: 15px;">
                <h4 class="mb-0">Histori Penjualan Akun Premium</h4>
                
                <!-- Filter Form -->
                <form action="{{ route('premium.histori.index') }}" method="GET" class="form-inline">
                    <div class="form-group mb-2 mr-2">
                        <label for="start_date" class="sr-only">Dari</label>
                        <input type="date" class="form-control form-control-sm border-dark text-dark" id="start_date" name="start_date" value="{{ request('start_date') }}" style="border-radius: 6px;">
                    </div>
                    <div class="form-group mb-2 mr-2">
                        <span class="text-muted px-1">s/d</span>
                    </div>
                    <div class="form-group mb-2 mr-2">
                        <label for="end_date" class="sr-only">Sampai</label>
                        <input type="date" class="form-control form-control-sm border-dark text-dark" id="end_date" name="end_date" value="{{ request('end_date') }}" style="border-radius: 6px;">
                    </div>
                    
                    @if($user->role_id == 1)
                    <div class="form-group mb-2 mr-2">
                        <select name="status" class="form-control form-control-sm border-dark text-dark" style="border-radius: 6px;">
                            <option value="">-- Semua Status --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-sm btn-dark mb-2 mr-1" style="border-radius: 6px; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    @if(request('start_date') || request('end_date') || request('status'))
                        <a href="{{ route('premium.histori.index') }}" class="btn btn-sm btn-outline-secondary mb-2" style="border-radius: 6px; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>{{ $user->role_id == 1 ? 'Tanggal Order' : 'Tanggal Terjual' }}</th>
                                <th>Order ID</th>
                                <th>Paket Layanan</th>
                                @if($user->role_id == 1)
                                <th>Gateway</th>
                                @endif
                                <th>Username / Email Akun</th>
                                <th>Pembeli</th>
                                <th>Nominal</th>
                                @if($user->role_id == 1)
                                <th>Status</th>
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($user->role_id == 1)
                                @forelse($orders as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="text-primary font-weight-bold" onclick="viewStatusLogs({{ json_encode($item) }})">
                                            <code>{{ $item->order_id }}</code>
                                        </a>
                                    </td>
                                    <td>{{ $item->varianLayanan->tipeLayanan->produk->nama_produk }} - {{ $item->varianLayanan->tipeLayanan->nama_tipe }} ({{ $item->varianLayanan->nama_varian }})</td>
                                    <td>
                                        @php
                                            $gw = strtolower($item->payment_gateway ?? 'midtrans');
                                            $pemb = $item->pembayaran->first();
                                            $methodName = $pemb->metode_pembayaran ?? ($item->gateway_reference ? 'TriPay' : '-');
                                        @endphp
                                        @if($gw === 'tripay')
                                            <span class="badge badge-dark">TriPay</span>
                                        @elseif($gw === 'pakasir')
                                            <span class="badge badge-primary">Pakasir</span>
                                        @else
                                            <span class="badge badge-info">Midtrans</span>
                                        @endif
                                        <br><small class="text-muted">{{ strtoupper($methodName) }}</small>
                                    </td>
                                    <td><code>{{ $item->stokAkun->email_username ?? '-' }}</code></td>
                                    <td>{{ $item->customer->user->name ?? '-' }} ({{ $item->customer->user->email ?? '-' }})</td>
                                    <td>Rp {{ number_format($item->harga_saat_beli ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->status->value == 'pending')
                                        <span class="badge badge-warning">PENDING</span>
                                        @elseif($item->status->value == 'success')
                                        <span class="badge badge-success">SUCCESS</span>
                                        @elseif($item->status->value == 'expired')
                                        <span class="badge badge-secondary">EXPIRED</span>
                                        @else
                                        <span class="badge badge-danger">{{ strtoupper($item->status->value) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-info" title="Detail & Riwayat" onclick="viewStatusLogs({{ json_encode($item) }})">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                        @if($item->status->value == 'pending')
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Cek Status Pembayaran" onclick="checkPaymentStatusManual('{{ $item->order_id }}')">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Belum ada order premium.</td>
                                </tr>
                                @endforelse
                            @else
                                @forelse($stokTerjual as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->tanggal_terjual ? $item->tanggal_terjual->format('d-m-Y H:i') : '-' }}</td>
                                    <td>
                                        @if(isset($item->pembelian))
                                        <a href="javascript:void(0)" class="text-primary font-weight-bold" onclick="viewStatusLogs({{ json_encode($item->pembelian) }})">
                                            <code>{{ $item->pembelian->order_id }}</code>
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>{{ $item->varianLayanan->tipeLayanan->produk->nama_produk }} - {{ $item->varianLayanan->tipeLayanan->nama_tipe }} ({{ $item->varianLayanan->nama_varian }})</td>
                                    <td><code>{{ $item->email_username }}</code></td>
                                    <td>{{ $item->pembelian->customer->user->name ?? '-' }} ({{ $item->pembelian->customer->user->email ?? '-' }})</td>
                                    <td>Rp {{ number_format($item->pembelian->harga_saat_beli ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada akun premium yang terjual.</td>
                                </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Riwayat Perubahan Status & Detail Pembayaran -->
<div class="modal fade" id="statusLogsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg text-dark" role="document">
        <div class="modal-content" style="border-radius: 12px; border: 1px solid #000;">
            <div class="modal-header" style="border-bottom: 2px solid #f0f0f0;">
                <h5 class="modal-title font-weight-bold">Detail Riwayat & Status Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold text-uppercase" style="letter-spacing: 0.5px; color: #555;">Informasi Transaksi</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="pl-0 font-weight-bold" style="width: 150px;">Order ID</td>
                                    <td>: <code id="modal-order-id"></code></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Payment Gateway</td>
                                    <td>: <span id="modal-gateway" class="badge"></span></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Gateway Ref</td>
                                    <td>: <code id="modal-gateway-ref" class="text-muted">-</code></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Metode Bayar</td>
                                    <td>: <span id="modal-method" class="text-muted">-</span></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Nominal Tagihan</td>
                                    <td>: <span id="modal-amount" class="font-weight-bold text-dark"></span></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Batas Waktu Bayar</td>
                                    <td>: <span id="modal-expired-at" class="text-muted">-</span></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Status Terkini</td>
                                    <td>: <span id="modal-status-terkini" class="badge"></span></td>
                                </tr>
                            </table>
                            @if($user->role_id == 1)
                            <div class="mt-3" id="modal-check-status-container"></div>
                            @endif
                        </div>
                        <div class="col-md-6 border-left" id="modal-wa-section">
                            <h6 class="font-weight-bold text-uppercase" style="letter-spacing: 0.5px; color: #555;">Status WhatsApp</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="pl-0 font-weight-bold" style="width: 150px;">Terkirim Pada</td>
                                    <td>: <span id="modal-wa-sent-at" class="text-muted">-</span></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Jumlah Retry</td>
                                    <td>: <span id="modal-wa-retry-count" class="font-weight-bold">0</span> kali</td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Retry Terakhir</td>
                                    <td>: <span id="modal-wa-last-retry-at" class="text-muted">-</span></td>
                                </tr>
                                <tr>
                                    <td class="pl-0 font-weight-bold">Response API</td>
                                    <td>: <small id="modal-wa-response" class="text-monospace text-muted" style="word-break: break-all;">-</small></td>
                                </tr>
                            </table>
                            <div class="mt-2" id="modal-retry-btn-container">
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div>
                    <h6 class="font-weight-bold text-uppercase mb-3" style="letter-spacing: 0.5px; color: #555;">Riwayat Perubahan Status</h6>
                    <div class="table-responsive" style="border-radius: 8px; border: 1px solid #e5e5e5;">
                        <table class="table table-striped table-sm mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="py-2 pl-3">Waktu</th>
                                    <th class="py-2">Status Lama</th>
                                    <th class="py-2">Status Baru</th>
                                    <th class="py-2">Sumber Perubahan</th>
                                    <th class="py-2 pr-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="modal-log-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #f0f0f0;">
                <button type="button" class="btn btn-secondary" style="border-radius: 6px;" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentModalOrderId = null;

    function viewStatusLogs(pembelian) {
        if (!pembelian) return;

        currentModalOrderId = pembelian.order_id;
        document.getElementById('modal-order-id').innerText = pembelian.order_id;
        
        // Render Gateway info
        const gateway = (pembelian.payment_gateway || 'midtrans').toLowerCase();
        const gatewayBadge = document.getElementById('modal-gateway');
        gatewayBadge.className = 'badge';
        if (gateway === 'tripay') {
            gatewayBadge.classList.add('badge-dark');
            gatewayBadge.innerText = 'TRIPAY';
        } else if (gateway === 'pakasir') {
            gatewayBadge.classList.add('badge-primary');
            gatewayBadge.innerText = 'PAKASIR';
        } else {
            gatewayBadge.classList.add('badge-info');
            gatewayBadge.innerText = 'MIDTRANS';
        }

        // Render Gateway Reference
        document.getElementById('modal-gateway-ref').innerText = pembelian.gateway_reference || '-';

        // Render Method & Amount
        const pembayaran = (pembelian.pembayaran || [])[0] || null;
        document.getElementById('modal-method').innerText = pembayaran && pembayaran.metode_pembayaran ? pembayaran.metode_pembayaran.toUpperCase() : (pembelian.gateway_reference ? 'TriPay' : '-');
        document.getElementById('modal-amount').innerText = 'Rp ' + Number(pembelian.harga_saat_beli || 0).toLocaleString('id-ID');

        // Render Expired At
        if (pembelian.reserved_until) {
            const expDate = new Date(pembelian.reserved_until);
            document.getElementById('modal-expired-at').innerText = expDate.toLocaleString('id-ID');
        } else {
            document.getElementById('modal-expired-at').innerText = '-';
        }

        // Render current status badge
        const statusBadge = document.getElementById('modal-status-terkini');
        statusBadge.className = 'badge';
        const currentStatus = (pembelian.status && (pembelian.status.value || pembelian.status) || '').toLowerCase();
        statusBadge.innerText = currentStatus.toUpperCase();
        
        if (currentStatus === 'success') {
            statusBadge.classList.add('badge-success');
        } else if (currentStatus === 'pending') {
            statusBadge.classList.add('badge-warning');
        } else if (currentStatus === 'expired') {
            statusBadge.classList.add('badge-secondary');
        } else {
            statusBadge.classList.add('badge-danger');
        }

        // Render Check Status Action Button for Admin
        const checkStatusContainer = document.getElementById('modal-check-status-container');
        if (checkStatusContainer) {
            checkStatusContainer.innerHTML = `
                <button class="btn btn-sm btn-outline-primary font-weight-bold btn-block" onclick="checkPaymentStatusManual('${pembelian.order_id}')">
                    <i class="bi bi-arrow-clockwise mr-1"></i> Cek Status Pembayaran (Sync ${gateway.toUpperCase()})
                </button>
            `;
        }

        // Render WA status details
        const waSection = document.getElementById('modal-wa-section');
        const retryBtnContainer = document.getElementById('modal-retry-btn-container');
        
        if (pembayaran) {
            waSection.style.display = 'block';
            
            if (pembayaran.wa_sent_at) {
                const sentDate = new Date(pembayaran.wa_sent_at);
                document.getElementById('modal-wa-sent-at').innerText = sentDate.toLocaleString('id-ID');
            } else {
                document.getElementById('modal-wa-sent-at').innerHTML = '<span class="badge badge-danger">Gagal / Belum Terkirim</span>';
            }
            
            document.getElementById('modal-wa-retry-count').innerText = pembayaran.wa_retry_count || 0;
            
            if (pembayaran.wa_last_retry_at) {
                const retryDate = new Date(pembayaran.wa_last_retry_at);
                document.getElementById('modal-wa-last-retry-at').innerText = retryDate.toLocaleString('id-ID');
            } else {
                document.getElementById('modal-wa-last-retry-at').innerText = '-';
            }
            
            document.getElementById('modal-wa-response').innerText = pembayaran.wa_response || '-';
            
            if (currentStatus === 'success') {
                retryBtnContainer.innerHTML = `
                    <button class="btn btn-sm btn-primary font-weight-bold btn-block" onclick="retryWhatsapp(${pembayaran.id_pembayaran})">
                        <i class="bi bi-whatsapp"></i> Kirim Ulang WA
                    </button>
                `;
            } else {
                retryBtnContainer.innerHTML = '<small class="text-muted">Retry WA hanya tersedia untuk transaksi sukses.</small>';
            }
        } else {
            waSection.style.display = 'none';
            retryBtnContainer.innerHTML = '';
        }

        const logs = pembelian.logs || [];
        const tbody = document.getElementById('modal-log-body');
        tbody.innerHTML = '';

        if (logs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat perubahan status untuk transaksi ini.</td></tr>';
        } else {
            logs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            logs.forEach(log => {
                const tr = document.createElement('tr');
                
                const date = new Date(log.created_at);
                const formattedDate = date.toLocaleString('id-ID', {
                    day: '2-digit', month: '2-digit', year: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                });

                const oldStatus = log.status_lama ? log.status_lama.toUpperCase() : 'NULL';
                const oldBadgeClass = log.status_lama === 'success' ? 'badge-success' : 
                                      (log.status_lama === 'pending' ? 'badge-warning' : 
                                      (log.status_lama === 'expired' ? 'badge-secondary' : 
                                      (log.status_lama ? 'badge-danger' : 'badge-light')));

                const newStatus = log.status_baru ? log.status_baru.toUpperCase() : 'NULL';
                const newBadgeClass = log.status_baru === 'success' ? 'badge-success' : 
                                      (log.status_baru === 'pending' ? 'badge-warning' : 
                                      (log.status_baru === 'expired' ? 'badge-secondary' : 'badge-danger'));

                tr.innerHTML = `
                    <td class="py-2 pl-3 align-middle">${formattedDate}</td>
                    <td class="py-2 align-middle"><span class="badge ${oldBadgeClass}">${oldStatus}</span></td>
                    <td class="py-2 align-middle"><span class="badge ${newBadgeClass}">${newStatus}</span></td>
                    <td class="py-2 align-middle"><span class="badge badge-info">${log.sumber_perubahan}</span></td>
                    <td class="py-2 pr-3 align-middle text-muted" style="font-size: 0.85rem;">${log.keterangan || '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        $('#statusLogsModal').modal('show');
    }

    function checkPaymentStatusManual(orderId) {
        Swal.fire({
            title: 'Cek Status Pembayaran?',
            text: `Sistem akan memverifikasi status order ${orderId} secara langsung ke gateway server.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Cek Sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memverifikasi...',
                    text: 'Menghubungi server payment gateway...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/premium/order/${orderId}/check-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(res => {
                    if (res.status === 200 && res.body.success) {
                        Swal.fire({
                            title: 'Status Disinkronkan!',
                            text: `${res.body.message} Status terkini: ${String(res.body.status).toUpperCase()}`,
                            icon: 'success',
                            confirmButtonColor: '#000'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal Verifikasi',
                            text: res.body.message || 'Terjadi kesalahan saat memverifikasi status.',
                            icon: 'error',
                            confirmButtonColor: '#000'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kegagalan jaringan saat memproses.',
                        icon: 'error',
                        confirmButtonColor: '#000'
                    });
                });
            }
        });
    }

    function retryWhatsapp(id_pembayaran) {
        Swal.fire({
            title: 'Kirim Ulang WhatsApp?',
            text: 'Apakah Anda yakin ingin memicu ulang pengiriman notifikasi WhatsApp invoice ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#000000',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang mengirim permintaan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/premium/pembayaran/${id_pembayaran}/retry-wa`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(res => {
                    if (res.status === 200 && res.body.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.body.message,
                            icon: 'success',
                            confirmButtonColor: '#000'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: res.body.message || 'Terjadi kesalahan saat memproses pengiriman ulang.',
                            icon: 'error',
                            confirmButtonColor: '#000'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kegagalan jaringan/koneksi saat memproses.',
                        icon: 'error',
                        confirmButtonColor: '#000'
                    });
                });
            }
        });
    }
</script>
@endsection
