@extends('layout')

@section('title', 'Kelola Stok Akun Premium')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Daftar Kredensial Stok Akun</h4>
                <div>
                    <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#bulkStokModal">
                        <i class="fas fa-cubes mr-2"></i>Bulk Add Stok
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStokModal">
                        <i class="fas fa-plus mr-2"></i>Tambah Stok Akun
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Paket Layanan</th>
                                <th>Username / Email</th>
                                <th>Password (Encrypted)</th>
                                <th>Catatan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stok as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->varianLayanan->tipeLayanan->produk->nama_produk }} - {{ $item->varianLayanan->tipeLayanan->nama_tipe }} ({{ $item->varianLayanan->nama_varian }})</td>
                                <td><code>{{ $item->email_username }}</code></td>
                                <td>
                                    <span class="badge badge-light">••••••••</span>
                                </td>
                                <td>{{ $item->catatan ?? '-' }}</td>
                                <td>
                                    @if($item->status->value == 'tersedia')
                                    <span class="badge badge-success">Tersedia</span>
                                    @elseif($item->status->value == 'reserved')
                                    <span class="badge badge-warning">Reserved</span>
                                    @else
                                    <span class="badge badge-secondary">Terjual</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewCredentials({{ $item->id_stok }})">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data stok akun premium.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Decrypt -->
<div class="modal fade" id="detailStokModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kredensial Akun Premium</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Username / Email</label>
                    <input type="text" class="form-control" id="detail-email" readonly>
                </div>
                <div class="form-group">
                    <label>Password (Decrypted On-Demand)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="detail-password" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('detail-password')">Salin</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea class="form-control" id="detail-catatan" rows="3" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addStokModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.stok.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stok Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Varian Paket</label>
                        <select class="form-control" name="id_varian" required>
                            <option value="">-- Pilih Paket Varian --</option>
                            @foreach($varian as $v)
                            <option value="{{ $v->id_varian }}">{{ $v->tipeLayanan->produk->nama_produk }} - {{ $v->tipeLayanan->nama_tipe }} ({{ $v->nama_varian }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Username / Email</label>
                        <input type="text" class="form-control" name="email_username" placeholder="Masukkan username/email akun" required>
                    </div>
                    <div class="form-group">
                        <label>Password Akun</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan password akun" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Informasi tambahan untuk pembeli..."></textarea>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Add Modal -->
<div class="modal fade" id="bulkStokModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.stok.bulk_store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Stok Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Varian Paket</label>
                        <select class="form-control" name="id_varian" required>
                            <option value="">-- Pilih Paket Varian --</option>
                            @foreach($varian as $v)
                            <option value="{{ $v->id_varian }}">{{ $v->tipeLayanan->produk->nama_produk }} - {{ $v->tipeLayanan->nama_tipe }} ({{ $v->nama_varian }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data Akun (Pemisah vertikal <code>|</code>)</label>
                        <textarea class="form-control" name="bulk_data" rows="8" placeholder="Format: email|password|catatan (Opsional)&#10;Contoh:&#10;user1@mail.com|pass123|Catatan slot 1&#10;user2@mail.com|pass456|Catatan slot 2" required></textarea>
                        <small class="form-text text-muted">Gunakan satu baris untuk setiap akun premium.</small>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Bulk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function viewCredentials(id) {
        // Fetch credentials decrypted on-demand from dedicated endpoint
        fetch("{{ url('premium/stok/detail') }}/" + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detail-email').value = data.email_username;
                document.getElementById('detail-password').value = data.password;
                document.getElementById('detail-catatan').value = data.catatan ? data.catatan : '-';
                $('#detailStokModal').modal('show');
            })
            .catch(error => {
                Swal.fire({ title: "Error", text: "Gagal memproses detail kredensial.", icon: "error" });
            });
    }

    function copyToClipboard(id) {
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
        Toast.fire({ icon: "success", title: "Berhasil menyalin password!" });
    }
</script>

@endsection
