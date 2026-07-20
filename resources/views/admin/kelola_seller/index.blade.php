@extends('layout')

@section('title', 'Kelola Seller')

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
                <h4>Daftar Toko Seller</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSellerModal">
                    <i class="fas fa-plus mr-2"></i>Tambah Seller
                </button>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            <strong>Gagal menyimpan data:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Toko</th>
                                <th>Username / Email</th>
                                <th>No Telp</th>
                                <th>Telegram</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sellers as $index => $seller)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $seller->nama_toko }}</strong>
                                    @if($seller->informasi_toko)
                                        <br><small class="text-muted">{{ Str::limit($seller->informasi_toko, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $seller->user->name ?? '-' }}
                                    <br><small class="text-muted">{{ $seller->user->email ?? '-' }}</small>
                                </td>
                                <td>{{ $seller->no_telp }}</td>
                                <td>
                                    <a href="https://t.me/{{ $seller->akun_telegram }}" target="_blank">
                                        {{ $seller->akun_telegram }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $seller->status == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($seller->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center" style="gap: 5px;">
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSellerModal{{ $seller->id_toko }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        
                                        <form action="{{ url('kelola_seller/toggle_status/' . $seller->id_toko) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $seller->status == 'aktif' ? 'danger' : 'success' }} btn-sm">
                                                <i class="fas fa-{{ $seller->status == 'aktif' ? 'ban' : 'check' }}"></i>
                                                {{ $seller->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Seller Modal -->
                            <div class="modal fade" id="editSellerModal{{ $seller->id_toko }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ url('kelola_seller/update/' . $seller->id_toko) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Seller: {{ $seller->nama_toko }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Toko</label>
                                                    <input type="text" class="form-control" name="nama_toko" value="{{ $seller->nama_toko }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>No. Telepon</label>
                                                    <input type="text" class="form-control" name="no_telp" value="{{ $seller->no_telp }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Akun Telegram (Username)</label>
                                                    <input type="text" class="form-control" name="akun_telegram" value="{{ $seller->akun_telegram }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Informasi Toko</label>
                                                    <textarea class="form-control" name="informasi_toko" rows="3">{{ $seller->informasi_toko }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Komisi Override Toko (%) - Kosongkan jika ingin pakai default platform</label>
                                                    <input type="number" step="0.01" min="0" max="100" class="form-control" name="komisi_override" value="{{ $seller->komisi_override }}" placeholder="Contoh: 8.50">
                                                </div>
                                            </div>
                                            <div class="modal-footer text-right">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data seller marketplace.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Seller Modal -->
<div class="modal fade" id="addSellerModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('kelola_seller/store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Akun Seller & Toko</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Toko</label>
                        <input type="text" class="form-control" name="nama_toko" placeholder="Contoh: Toko Keren Digital" value="{{ old('nama_toko') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Username (Akun Login)</label>
                        <input type="text" class="form-control" name="username" placeholder="Contoh: tokokeren" value="{{ old('username') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Contoh: seller@mail.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password (Min. 10 Karakter)</label>
                        <input type="password" class="form-control" name="password" placeholder="Masukkan password awal..." required>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" class="form-control" name="no_telp" placeholder="Contoh: 08123456789" value="{{ old('no_telp') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Akun Telegram (Username)</label>
                        <input type="text" class="form-control" name="akun_telegram" placeholder="Contoh: seller_tg" value="{{ old('akun_telegram') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Informasi Toko (Opsional)</label>
                        <textarea class="form-control" name="informasi_toko" rows="3" placeholder="Deskripsi atau catatan tentang toko...">{{ old('informasi_toko') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Komisi Override Toko (%) - Kosongkan jika ingin pakai default platform</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" name="komisi_override" placeholder="Contoh: 8.50" value="{{ old('komisi_override') }}">
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

@endsection
