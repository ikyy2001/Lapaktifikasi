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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Riwayat Pembelian Akun Premium Anda</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
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
                                    <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status->value == 'success')
                                    <span class="badge badge-success">Success</span>
                                    @elseif($item->status->value == 'expired')
                                    <span class="badge badge-light">Expired</span>
                                    @elseif($item->status->value == 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                    @else
                                    <span class="badge badge-danger">Failed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status->value == 'pending')
                                    <a href="{{ route('metode_pembayaran', $item->order_id) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-credit-card-fill mr-1"></i>Selesaikan Pembayaran
                                    </a>
                                    @elseif($item->status->value == 'success')
                                    <button class="btn btn-sm btn-success" onclick="viewCredentials({{ $item->id_pembelian }})">
                                        <i class="bi bi-key-fill mr-1"></i>Lihat Akun
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
                <h5 class="modal-title">Kredensial Akun Premium Anda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <p class="mb-0"><strong>Penting:</strong> Harap simpan informasi akun ini dengan aman. Jangan menyebarkan informasi akun kepada pihak lain.</p>
                </div>
                <div class="form-group">
                    <label>Username / Email</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-primary font-weight-bold" id="kred-email" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="copyText('kred-email')">Salin</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="text" class="form-control text-primary font-weight-bold" id="kred-password" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="copyText('kred-password')">Salin</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Catatan Akses Layanan</label>
                    <textarea class="form-control" id="kred-catatan" rows="3" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
