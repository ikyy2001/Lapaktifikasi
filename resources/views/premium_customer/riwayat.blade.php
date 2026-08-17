@extends('layout')

@section('title', 'Riwayat Pembelian Premium')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

@if($error = Session::get('error'))
<script>
    Swal.fire({ title: "Gagal", text: "{{ $error }}", icon: "error" });
</script>
@endif

    <style>
        .riwayat-container {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin-top: 10px;
        }

        .riwayat-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1a1a1a;
            margin-bottom: 24px;
            text-transform: uppercase;
            border-left: 4px solid #000000;
            padding-left: 14px;
        }

        .mono-card {
            background: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 16px !important;
            padding: 35px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            position: relative !important;
            overflow: hidden !important;
            animation: profileFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .mono-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #000000;
        }

        .form-title {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #000000;
            margin-bottom: 28px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 14px;
        }

        /* Table custom styling */
        .table-responsive {
            border-radius: 12px;
            overflow-x: auto !important;
            border: 1px solid #e5e5e5;
        }

        .mono-table {
            width: 100%;
            margin-bottom: 0 !important;
            background-color: #ffffff;
        }

        .mono-table th {
            background-color: #000000 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.8px !important;
            padding: 16px 20px !important;
            border: none !important;
        }

        .mono-table td {
            padding: 16px 20px !important;
            font-size: 0.88rem !important;
            color: #333333 !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f0f0f0 !important;
        }

        .mono-table tbody tr:nth-of-type(even) {
            background-color: #fafafa;
        }

        .mono-table tbody tr:hover {
            background-color: #f5f5f5;
        }

        /* Minimalist Badges */
        .mono-badge {
            font-size: 0.68rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            padding: 5px 10px !important;
            border-radius: 4px !important;
            display: inline-block !important;
        }

        .mono-badge-pending {
            background-color: #ffffff !important;
            color: #7d6318 !important;
            border: 1px solid #eed27a !important;
        }

        .mono-badge-success {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
        }

        .mono-badge-expired {
            background-color: #ffffff !important;
            color: #888888 !important;
            border: 1px solid #e5e5e5 !important;
        }

        .mono-badge-failed {
            background-color: #ffffff !important;
            color: #ea5455 !important;
            border: 1px solid #fcd4d4 !important;
        }

        /* Buttons */
        .mono-btn-primary {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid #000000 !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
        }
        
        .mono-btn-primary:hover {
            background: transparent !important;
            color: #000000 !important;
        }

        /* Modal Styles override */
        .mono-modal-alert {
            background-color: #fffdf5 !important;
            border: 1px dashed #eed27a !important;
            color: #7d6318 !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
            font-size: 0.82rem !important;
            margin-bottom: 20px !important;
        }

        .mono-input-label {
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            color: #666666 !important;
            margin-bottom: 6px !important;
        }

        .mono-input-val {
            background-color: #ffffff !important;
            border: 1px solid #000000 !important;
            color: #000000 !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
        }

        .mono-btn-outline {
            border: 1px solid #000000 !important;
            background-color: transparent !important;
            color: #000000 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.78rem !important;
            padding: 10px 18px !important;
            border-radius: 0 8px 8px 0 !important;
            transition: all 0.2s ease !important;
        }

        .mono-btn-outline:hover {
            background-color: #000000 !important;
            color: #ffffff !important;
        }

        .mono-btn-secondary {
            border: 1px solid #cccccc !important;
            background-color: transparent !important;
            color: #666666 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.78rem !important;
            padding: 10px 24px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
        }

        .mono-btn-secondary:hover {
            background-color: #f2f2f2 !important;
            color: #333333 !important;
        }

        @keyframes profileFadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="riwayat-container">
        <h4 class="riwayat-header-title">Riwayat Pembelian</h4>
        
        <div class="tab-nav-container mb-4" style="display: flex; gap: 8px; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; padding-bottom: 8px;">
            <a href="{{ route('premium.riwayat') }}" class="tab-btn {{ !request('status') ? 'active' : '' }}" style="border-radius: 20px; padding: 8px 16px; background: {{ !request('status') ? '#000' : '#f8f9fa' }}; color: {{ !request('status') ? '#fff' : '#000' }}; border: 1px solid #000; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Semua</a>
            <a href="{{ route('premium.riwayat', ['status' => 'pending']) }}" class="tab-btn {{ request('status') == 'pending' ? 'active' : '' }}" style="border-radius: 20px; padding: 8px 16px; background: {{ request('status') == 'pending' ? '#000' : '#f8f9fa' }}; color: {{ request('status') == 'pending' ? '#fff' : '#000' }}; border: 1px solid #000; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Pending</a>
            <a href="{{ route('premium.riwayat', ['status' => 'success']) }}" class="tab-btn {{ request('status') == 'success' ? 'active' : '' }}" style="border-radius: 20px; padding: 8px 16px; background: {{ request('status') == 'success' ? '#000' : '#f8f9fa' }}; color: {{ request('status') == 'success' ? '#fff' : '#000' }}; border: 1px solid #000; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Sukses</a>
            <a href="{{ route('premium.riwayat', ['status' => 'failed']) }}" class="tab-btn {{ in_array(request('status'), ['failed', 'expired', 'cancelled']) ? 'active' : '' }}" style="border-radius: 20px; padding: 8px 16px; background: {{ in_array(request('status'), ['failed', 'expired', 'cancelled']) ? '#000' : '#f8f9fa' }}; color: {{ in_array(request('status'), ['failed', 'expired', 'cancelled']) ? '#fff' : '#000' }}; border: 1px solid #000; text-decoration: none; font-weight: bold; font-size: 0.85rem;">Gagal / Batal</a>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="mono-card">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 pb-2 border-bottom" style="gap: 15px;">
                        <h4 class="form-title mb-0" style="border-bottom: none; padding-bottom: 0;">Riwayat Pembelian Akun Premium Anda</h4>
                        
                        <!-- Date Filter Form -->
                        <form action="{{ route('premium.riwayat') }}" method="GET" class="form-inline w-100 w-sm-auto justify-content-start justify-content-sm-end" style="gap: 8px;">
                            <div class="form-group mb-2 mr-2">
                                <label for="start_date" class="sr-only">Dari</label>
                                <input type="date" class="form-control form-control-sm border-dark text-dark" id="start_date" name="start_date" value="{{ request('start_date') }}" style="border-radius: 6px; font-size: 0.85rem; height: 38px;">
                            </div>
                            <div class="form-group mb-2 mr-2">
                                <span class="text-muted px-1" style="font-size: 0.85rem;">s/d</span>
                            </div>
                            <div class="form-group mb-2 mr-2">
                                <label for="end_date" class="sr-only">Sampai</label>
                                <input type="date" class="form-control form-control-sm border-dark text-dark" id="end_date" name="end_date" value="{{ request('end_date') }}" style="border-radius: 6px; font-size: 0.85rem; height: 38px;">
                            </div>
                            <button type="submit" class="btn btn-sm btn-dark mb-2 mr-1" style="border-radius: 6px; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; height: 38px; padding: 0 16px;">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            @if(request('start_date') || request('end_date'))
                                <a href="{{ route('premium.riwayat') }}" class="btn btn-sm btn-outline-secondary mb-2" style="border-radius: 6px; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; height: 38px; display: inline-flex; align-items: center; justify-content: center; padding: 0 16px;">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>
                    
                    <!-- Desktop Table (Hidden on Mobile) -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table mono-table" id="table-1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Order ID</th>
                                        <th>Produk / Paket</th>
                                        <th>Harga Beli</th>
                                        <th>Status</th>
                                        <th style="min-width: 260px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembelian as $index => $item)
                                    @php
                                        $isPendingActive = ($item->status->value == 'pending') && (!$item->reserved_until || $item->reserved_until > now());
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td style="font-size: 0.82rem; white-space: nowrap;">{{ $item->created_at->translatedFormat('d F Y, H:i') }}</td>
                                        <td><code style="font-size: 0.75rem; white-space: nowrap;">{{ $item->order_id }}</code></td>
                                        <td style="min-width: 180px;">
                                            <strong class="d-block text-dark" style="font-size: 0.88rem;">{{ $item->varianLayanan?->tipeLayanan?->produk?->nama_produk }}</strong>
                                            <small class="text-muted">{{ $item->varianLayanan?->tipeLayanan?->nama_tipe }} ({{ $item->varianLayanan?->nama_varian }})</small>
                                        </td>
                                        <td style="white-space: nowrap; font-weight: 700;">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</td>
                                        <td>
                                            @if($isPendingActive)
                                            <span class="mono-badge mono-badge-pending">Pending</span>
                                            @elseif($item->status->value == 'success')
                                            <span class="mono-badge mono-badge-success">Success</span>
                                            @elseif($item->status->value == 'expired' || $item->status->value == 'cancelled' || ($item->status->value == 'pending' && $item->reserved_until && $item->reserved_until <= now()))
                                            <span class="mono-badge mono-badge-expired">Transaksi Dibatalkan</span>
                                            @else
                                            <span class="mono-badge mono-badge-failed">Failed</span>
                                            @endif
                                        </td>
                                        <td style="min-width: 260px;">
                                            @if($isPendingActive)
                                            <a href="{{ route('metode_pembayaran', $item->order_id) }}" class="btn btn-sm btn-dark font-weight-bold" style="border-radius: 6px; font-size: 0.75rem; padding: 6px 14px; white-space: nowrap;">
                                                <i class="bi bi-credit-card-fill mr-1"></i> Selesaikan
                                            </a>
                                            @elseif($item->status->value == 'success')
                                            <div class="d-flex align-items-center" style="gap: 6px; flex-wrap: nowrap;">
                                                @if($item->varianLayanan?->tipeLayanan?->produk?->tipe_produk == 'digital')
                                                <a href="{{ route('premium.digital.download', $item->order_id) }}" class="btn btn-sm btn-dark font-weight-bold" style="border-radius: 6px; font-size: 0.75rem; padding: 6px 12px; white-space: nowrap; display: inline-flex; align-items: center;">
                                                    <i class="bi bi-download mr-1"></i> Download
                                                </a>
                                                @else
                                                <button class="btn btn-sm btn-dark font-weight-bold" onclick="viewCredentials('{{ $item->order_id }}')" style="border-radius: 6px; font-size: 0.75rem; padding: 6px 12px; white-space: nowrap; display: inline-flex; align-items: center;">
                                                    <i class="bi bi-key-fill mr-1"></i> Akun
                                                </button>
                                                @endif
                                                <a href="{{ route('premium.invoice.download', $item->order_id) }}" class="btn btn-sm btn-outline-dark font-weight-bold" target="_blank" style="border-radius: 6px; font-size: 0.75rem; padding: 6px 12px; white-space: nowrap; display: inline-flex; align-items: center;">
                                                    <i class="bi bi-file-earmark-pdf-fill mr-1"></i> Invoice
                                                </a>
                                                @if(!$item->review)
                                                <a href="{{ route('premium.review.show', $item->order_id) }}" class="btn btn-sm btn-outline-dark font-weight-bold" style="border-radius: 6px; font-size: 0.75rem; padding: 6px 12px; white-space: nowrap; display: inline-flex; align-items: center;">
                                                    <i class="bi bi-star-fill text-warning mr-1"></i> Review
                                                </a>
                                                @endif
                                            </div>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Anda belum memiliki transaksi pembelian akun premium.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile List (Hidden on Desktop) -->
                    <div class="d-md-none">
                        @forelse($pembelian as $index => $item)
                        @php
                            $isPendingActiveMobile = ($item->status->value == 'pending') && (!$item->reserved_until || $item->reserved_until > now());
                        @endphp
                        <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px; background: #ffffff;">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2 px-3 border-bottom" style="border-radius: 12px 12px 0 0;">
                                <div class="d-flex align-items-center gap-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <i class="bi bi-shop text-muted"></i>
                                    <span class="font-weight-bold text-dark" style="font-size: clamp(0.75rem, 2vw, 0.85rem);">{{ $item->varianLayanan?->tipeLayanan?->produk?->toko?->nama_toko ?? 'Toko' }}</span>
                                </div>
                                <span class="text-right" style="flex-shrink: 0; padding-left: 10px;">
                                    @if($isPendingActiveMobile)
                                    <span class="mono-badge mono-badge-pending" style="font-size: 0.65rem !important;">Pending</span>
                                    @elseif($item->status->value == 'success')
                                    <span class="mono-badge mono-badge-success" style="font-size: 0.65rem !important;">Success</span>
                                    @elseif(in_array($item->status->value, ['expired', 'cancelled']) || ($item->status->value == 'pending' && $item->reserved_until && $item->reserved_until <= now()))
                                    <span class="mono-badge mono-badge-expired" style="font-size: 0.65rem !important;">Dibatalkan</span>
                                    @else
                                    <span class="mono-badge mono-badge-failed" style="font-size: 0.65rem !important;">Failed</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong class="text-dark d-block" style="font-size: clamp(0.9rem, 3vw, 1rem); line-height: 1.3;">
                                        {{ $item->varianLayanan?->tipeLayanan?->produk?->nama_produk }}
                                    </strong>
                                    <span class="font-weight-bold text-dark ml-2">x1</span>
                                </div>
                                <span class="text-muted d-block mb-2" style="font-size: clamp(0.75rem, 2.5vw, 0.85rem);">
                                    Varian: {{ $item->varianLayanan?->tipeLayanan?->nama_tipe }} ({{ $item->varianLayanan?->nama_varian }})
                                </span>
                                <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                    <span>{{ $item->created_at->translatedFormat('d F Y, H:i') }}</span>
                                    <code>{{ $item->order_id }}</code>
                                </div>
                            </div>

                            <!-- Mobile Action Buttons & Total -->
                            <div class="card-footer bg-white px-3 py-3 d-flex flex-column gap-2" style="border-radius: 0 0 12px 12px; border-top: 1px dashed #e5e5e5;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-dark font-weight-bold" style="font-size: 0.85rem;">Total Pesanan</span>
                                    <span class="font-weight-bold text-dark" style="font-size: clamp(1rem, 4vw, 1.15rem);">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    @if($isPendingActiveMobile)
                                    <a href="{{ route('metode_pembayaran', $item->order_id) }}" class="btn btn-dark font-weight-bold" style="border-radius: 6px; font-size: 0.75rem; text-transform: uppercase; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; flex: 1;">
                                        Selesaikan
                                    </a>
                                    @elseif($item->status->value == 'success')
                                        @if($item->varianLayanan?->tipeLayanan?->produk?->tipe_produk == 'digital')
                                        <a href="{{ route('premium.digital.download', $item->order_id) }}" class="btn btn-dark font-weight-bold" style="border-radius: 6px; font-size: 0.75rem; text-transform: uppercase; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; flex: 1;">
                                            Download
                                        </a>
                                        @else
                                        <button class="btn btn-dark font-weight-bold" onclick="viewCredentials('{{ $item->order_id }}')" style="border-radius: 6px; font-size: 0.75rem; text-transform: uppercase; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; flex: 1;">
                                            Akun
                                        </button>
                                        @endif
                                        <a href="{{ route('premium.invoice.download', $item->order_id) }}" class="btn btn-outline-dark font-weight-bold" target="_blank" style="border-radius: 6px; font-size: 0.75rem; text-transform: uppercase; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; padding: 0 12px;">
                                            Invoice
                                        </a>
                                        @if(!$item->review)
                                        <a href="{{ route('premium.review.show', $item->order_id) }}" class="btn btn-outline-dark font-weight-bold" style="border-radius: 6px; font-size: 0.75rem; text-transform: uppercase; min-height: 40px; display: inline-flex; align-items: center; justify-content: center; padding: 0 12px;">
                                            Ulas
                                        </a>
                                        @endif
                                    @else
                                    <div class="text-center text-muted" style="font-size: 0.85rem;">-</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted bg-light rounded" style="border: 1px dashed #ccc; border-radius: 8px;">
                            Anda belum memiliki transaksi pembelian akun premium.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tampilkan Kredensial -->
    <div class="modal fade" id="kredensialModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kredensial Akun Premium</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mono-modal-alert">
                        <p class="mb-0"><strong>Penting:</strong> Harap simpan informasi akun ini dengan aman. Jangan menyebarkan informasi akun kepada pihak lain.</p>
                    </div>
                    <div class="form-group">
                        <label class="mono-input-label">Username / Email</label>
                        <div class="input-group">
                            <input type="text" class="form-control mono-input-val" id="kred-email" readonly style="min-height: 44px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; font-size: clamp(0.85rem, 3vw, 1rem);">
                            <div class="input-group-append">
                                <button class="btn mono-btn-outline" type="button" onclick="copyText('kred-email')" style="min-height: 44px; display: inline-flex; align-items: center; justify-content: center; padding: 0 20px;">Salin</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="mono-input-label">Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control mono-input-val" id="kred-password" readonly style="min-height: 44px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; font-size: clamp(0.85rem, 3vw, 1rem);">
                            <div class="input-group-append">
                                <button class="btn mono-btn-outline" type="button" onclick="copyText('kred-password')" style="min-height: 44px; display: inline-flex; align-items: center; justify-content: center; padding: 0 20px;">Salin</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="mono-input-label">Catatan Akses Layanan</label>
                        <textarea class="form-control mono-input-val" style="height: auto;" id="kred-catatan" rows="3" readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn mono-btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewCredentials(orderId) {
            fetch("{{ url('premium/kredensial') }}/" + orderId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Akses kredensial gagal.");
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('kred-email').value = data.email_username;
                    document.getElementById('kred-password').value = data.password;
                    document.getElementById('kred-catatan').value = data.catatan ? data.catatan : '-';
                    $('#kredensialModal').modal('show');
                })
                .catch(error => {
                    Swal.fire({ title: "Akses Ditolak", text: "Gagal memuat kredensial akun.", icon: "error" });
                });
        }

        function copyText(id) {
            const copyText = document.getElementById(id);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500
            });
            Toast.fire({ icon: "success", title: "Berhasil disalin!" });
        }
    </script>

@endsection
