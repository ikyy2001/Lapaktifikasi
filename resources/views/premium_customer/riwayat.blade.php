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
            overflow: hidden;
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
        <h4 class="riwayat-header-title">Riwayat Premium</h4>
        
        <div class="row">
            <div class="col-12">
                <div class="mono-card">
                    <h4 class="form-title">Riwayat Pembelian Akun Premium Anda</h4>
                    
                    <div class="table-responsive">
                        <table class="table mono-table" id="table-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Produk / Paket</th>
                                    <th>Harga Beli</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelian as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><code>{{ $item->order_id }}</code></td>
                                    <td>
                                        {{ $item->varianLayanan->tipeLayanan->produk->nama_produk }} - 
                                        {{ $item->varianLayanan->tipeLayanan->nama_tipe }} 
                                        ({{ $item->varianLayanan->nama_varian }})
                                    </td>
                                    <td>Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->status->value == 'pending')
                                        <span class="mono-badge mono-badge-pending">Pending</span>
                                        @elseif($item->status->value == 'success')
                                        <span class="mono-badge mono-badge-success">Success</span>
                                        @elseif($item->status->value == 'expired')
                                        <span class="mono-badge mono-badge-expired">Expired</span>
                                        @elseif($item->status->value == 'cancelled')
                                        <span class="mono-badge mono-badge-failed">Cancelled</span>
                                        @else
                                        <span class="mono-badge mono-badge-failed">Failed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status->value == 'pending')
                                        <a href="{{ route('metode_pembayaran', $item->order_id) }}" class="mono-btn-primary">
                                            <i class="bi bi-credit-card-fill"></i> Selesaikan
                                        </a>
                                        @elseif($item->status->value == 'success')
                                        <button class="mono-btn-primary" onclick="viewCredentials({{ $item->id_pembelian }})">
                                            <i class="bi bi-key-fill"></i> Akun
                                        </button>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Anda belum memiliki transaksi pembelian akun premium.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                            <input type="text" class="form-control mono-input-val" id="kred-email" readonly>
                            <div class="input-group-append">
                                <button class="btn mono-btn-outline" type="button" onclick="copyText('kred-email')">Salin</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="mono-input-label">Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control mono-input-val" id="kred-password" readonly>
                            <div class="input-group-append">
                                <button class="btn mono-btn-outline" type="button" onclick="copyText('kred-password')">Salin</button>
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
        function viewCredentials(idPembelian) {
            fetch("{{ url('premium/kredensial') }}/" + idPembelian)
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
