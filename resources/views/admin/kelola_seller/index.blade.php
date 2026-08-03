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
                                <th>Badge Toko</th>
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
                                    @if($seller->badges && $seller->badges->isNotEmpty())
                                        @foreach($seller->badges as $b)
                                            <span class="badge badge-dark mb-1" style="background:#0f172a; color:#a78bfa; border:1px solid #8b5cf6;" title="{{ $b->deskripsi }}">
                                                <i class="fas fa-certificate text-warning mr-1"></i> {{ $b->nama_badge }}
                                            </span><br>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $seller->status == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($seller->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center flex-wrap" style="gap: 5px;">
                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#badgeModal{{ $seller->id_toko }}" title="Kelola Badge">
                                            <i class="fas fa-award"></i> Badge
                                        </button>

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

                            <!-- Kelola Badge Modal -->
                            <div class="modal fade" id="badgeModal{{ $seller->id_toko }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title"><i class="fas fa-award text-warning mr-2"></i> Kelola Badge Toko: {{ $seller->nama_toko }}</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-4">
                                                <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-check-circle text-success mr-1"></i> Badge Dimiliki Toko Saat Ini:</h6>
                                                @if($seller->badges && $seller->badges->isNotEmpty())
                                                    <div class="d-flex flex-wrap" style="gap: 8px;">
                                                        @foreach($seller->badges as $b)
                                                            <div class="badge badge-dark p-2 d-inline-flex align-items-center" style="background:#0f172a; border:1px solid #8b5cf6; color:#a78bfa; font-size:0.85rem;">
                                                                <i class="fas fa-certificate text-warning mr-2"></i>
                                                                <span class="mr-2"><strong>{{ $b->nama_badge }}</strong></span>
                                                                <form action="{{ route('admin.kelola_seller.badge.detach', ['id_toko' => $seller->id_toko, 'id_badge' => $b->id_badge]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mencabut badge ini dari toko?')">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger btn-sm p-0 px-1 border-0" style="font-size:0.75rem; line-height:1.2;" title="Hapus Badge Ini">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-muted small mb-0">Toko ini belum memiliki badge apapun.</p>
                                                @endif
                                            </div>

                                            <hr>

                                            <div class="row">
                                                <!-- Form Tambah Badge Master -->
                                                <div class="col-md-6 border-right">
                                                    <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-plus-circle mr-1"></i> Tambah Badge Master (Yang Sudah Ada)</h6>
                                                    <form action="{{ route('admin.kelola_seller.badge.attach', $seller->id_toko) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group mb-3">
                                                            <label class="font-weight-bold small text-muted">Pilih Badge Master</label>
                                                            <select name="id_badge" class="form-control" required>
                                                                <option value="">-- Pilih Badge --</option>
                                                                @foreach($allBadges as $mb)
                                                                    <option value="{{ $mb->id_badge }}">{{ $mb->nama_badge }} @if($mb->deskripsi) - {{ $mb->deskripsi }} @endif</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-success btn-block">
                                                            <i class="fas fa-plus mr-1"></i> Berikan Badge Terpilih
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Form Buat Badge Custom -->
                                                <div class="col-md-6">
                                                    <h6 class="font-weight-bold text-info mb-3"><i class="fas fa-magic mr-1"></i> Buat Badge Custom Baru</h6>
                                                    <form action="{{ route('admin.kelola_seller.badge.custom', $seller->id_toko) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group mb-2">
                                                            <label class="font-weight-bold small text-muted">Nama Badge Custom</label>
                                                            <input type="text" name="nama_badge" class="form-control" placeholder="Contoh: Official Store / Top Recommended" required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="font-weight-bold small text-muted">Deskripsi (Opsional)</label>
                                                            <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi atau alasan pemberian badge">
                                                        </div>
                                                        <button type="submit" class="btn btn-info btn-block">
                                                            <i class="fas fa-save mr-1"></i> Buat & Berikan Badge Custom
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Selesai</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
