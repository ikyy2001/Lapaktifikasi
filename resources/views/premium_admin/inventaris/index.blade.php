@extends('layout')

@section('title', 'Kelola Inventaris & Aset')

@section('content')

@if($success = Session::get('success'))
<script>
    Swal.fire({ title: "Berhasil", text: "{{ $success }}", icon: "success" });
</script>
@endif

@php
    $activeTab = request()->query('tab', 'tipe');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kelola Inventaris & Aset Produk</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="inventarisTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'tipe' ? 'active' : '' }}" id="tipe-tab" data-toggle="tab" href="#tipe" role="tab" aria-controls="tipe" aria-selected="{{ $activeTab == 'tipe' ? 'true' : 'false' }}">
                            <i class="fas fa-tags mr-1"></i> Kategori / Tipe Layanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'varian' ? 'active' : '' }}" id="varian-tab" data-toggle="tab" href="#varian" role="tab" aria-controls="varian" aria-selected="{{ $activeTab == 'varian' ? 'true' : 'false' }}">
                            <i class="fas fa-layer-group mr-1"></i> Varian & Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'stok' ? 'active' : '' }}" id="stok-tab" data-toggle="tab" href="#stok" role="tab" aria-controls="stok" aria-selected="{{ $activeTab == 'stok' ? 'true' : 'false' }}">
                            <i class="fas fa-box-open mr-1"></i> Stok & File Aset
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="inventarisTabContent">
                    
                    <!-- ============================================== -->
                    <!-- TAB 1: TIPE LAYANAN                            -->
                    <!-- ============================================== -->
                    <div class="tab-pane fade {{ $activeTab == 'tipe' ? 'show active' : '' }}" id="tipe" role="tabpanel" aria-labelledby="tipe-tab">
                        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                            <h5 class="mb-0">Daftar Tipe Layanan</h5>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTipeModal">
                                <i class="fas fa-plus mr-2"></i>Tambah Tipe Layanan
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-tipe">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Nama Tipe Layanan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tipeSemua as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->produk->nama_produk }}</td>
                                            <td><strong>{{ $item->nama_tipe }}</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $item->status == 'aktif' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editTipeModal{{ $item->id_tipe }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal Tipe -->
                                        <div class="modal fade" id="editTipeModal{{ $item->id_tipe }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('premium.tipe.update', $item->id_tipe) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Tipe Layanan</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Pilih Produk</label>
                                                                <select class="form-control" name="id_produk" required>
                                                                    @foreach($produk as $p)
                                                                        <option value="{{ $p->id_produk }}" {{ $p->id_produk == $item->id_produk ? 'selected' : '' }}>
                                                                            {{ $p->nama_produk }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Nama Tipe Layanan</label>
                                                                <input type="text" class="form-control" name="nama_tipe" value="{{ $item->nama_tipe }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                                    <option value="nonaktif" {{ $item->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                                </select>
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
                                            <td colspan="5" class="text-center text-muted">Belum ada data tipe layanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 2: VARIAN LAYANAN                          -->
                    <!-- ============================================== -->
                    <div class="tab-pane fade {{ $activeTab == 'varian' ? 'show active' : '' }}" id="varian" role="tabpanel" aria-labelledby="varian-tab">
                        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                            <h5 class="mb-0">Daftar Varian / Paket</h5>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addVarianModal">
                                <i class="fas fa-plus mr-2"></i>Tambah Varian
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-varian">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Layanan / Tipe</th>
                                        <th>Nama Varian</th>
                                        <th>Durasi (Hari)</th>
                                        <th>Harga</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($varianSemua as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->tipeLayanan->produk->nama_produk }} - {{ $item->tipeLayanan->nama_tipe }}</td>
                                        <td><strong>{{ $item->nama_varian }}</strong></td>
                                        <td>{{ $item->durasi_hari }} Hari</td>
                                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->deskripsi ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->status == 'aktif' ? 'success' : 'danger' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editVarianModal{{ $item->id_varian }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal Varian -->
                                    <div class="modal fade" id="editVarianModal{{ $item->id_varian }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('premium.varian.update', $item->id_varian) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Varian Layanan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Pilih Tipe Layanan</label>
                                                            <select class="form-control" name="id_tipe" required>
                                                                @foreach($tipeAktif as $t)
                                                                <option value="{{ $t->id_tipe }}" {{ $t->id_tipe == $item->id_tipe ? 'selected' : '' }}>{{ $t->produk->nama_produk }} - {{ $t->nama_tipe }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Nama Varian</label>
                                                            <input type="text" class="form-control" name="nama_varian" value="{{ $item->nama_varian }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Durasi (Hari)</label>
                                                            <input type="number" class="form-control" name="durasi_hari" value="{{ $item->durasi_hari }}" min="1" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Harga (IDR)</label>
                                                            <input type="number" class="form-control" name="harga" value="{{ $item->harga }}" min="0" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Deskripsi (Opsional)</label>
                                                            <textarea class="form-control" name="deskripsi" rows="3">{{ $item->deskripsi }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <select class="form-control" name="status">
                                                                <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                                <option value="nonaktif" {{ $item->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                            </select>
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
                                        <td colspan="8" class="text-center text-muted">Belum ada data varian layanan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 3: STOK AKUN / ASET                        -->
                    <!-- ============================================== -->
                    <div class="tab-pane fade {{ $activeTab == 'stok' ? 'show active' : '' }}" id="stok" role="tabpanel" aria-labelledby="stok-tab">
                        <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
                            <h5 class="mb-0">Daftar Stok Aset</h5>
                            <div>
                                <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#bulkStokModal">
                                    <i class="fas fa-cubes mr-2"></i>Bulk Add Stok
                                </button>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStokModal">
                                    <i class="fas fa-plus mr-2"></i>Tambah Stok Aset
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-stok">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Paket Layanan</th>
                                        <th>Identitas / Kredensial Utama</th>
                                        <th>Kredensial Sekunder (Encrypted)</th>
                                        <th>Catatan Tambahan</th>
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
                                            <form action="{{ route('premium.stok.destroy', $item->id_stok) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada data stok aset premium.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================= -->
<!-- BAGIAN MODAL GLOBAL (TAMBAH DATA & DETAIL DATA)         -->
<!-- ======================================================= -->

<!-- Modal Tambah Tipe -->
<div class="modal fade" id="addTipeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.tipe.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori / Tipe Layanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Produk Induk</label>
                        <select class="form-control" name="id_produk" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Tipe / Kategori</label>
                        <input type="text" class="form-control" name="nama_tipe" placeholder="Contoh: Private, Sharing, ZIP Download" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
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

<!-- Modal Tambah Varian -->
<div class="modal fade" id="addVarianModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.varian.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Varian / Paket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Tipe Layanan</label>
                        <select class="form-control" name="id_tipe" required>
                            <option value="">-- Pilih Tipe Layanan --</option>
                            @foreach($tipeAktif as $t)
                            <option value="{{ $t->id_tipe }}">{{ $t->produk->nama_produk }} - {{ $t->nama_tipe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Varian (Paket)</label>
                        <input type="text" class="form-control" name="nama_varian" placeholder="Contoh: 1 Bulan, Lifetime, Edisi Deluxe" required>
                    </div>
                    <div class="form-group">
                        <label>Durasi Aktif (Hari)</label>
                        <input type="number" class="form-control" name="durasi_hari" placeholder="Contoh: 30, 9999 (untuk lifetime)" min="1" required>
                        <small class="form-text text-muted">Untuk produk non-berlangganan seperti ZIP, bisa isi dengan angka besar seperti 9999 atau 1.</small>
                    </div>
                    <div class="form-group">
                        <label>Harga (IDR)</label>
                        <input type="number" class="form-control" name="harga" placeholder="Contoh: 35000" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Tambahan (Opsional)</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Informasi singkat paket ini..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
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

<!-- Modal Tambah Stok -->
<div class="modal fade" id="addStokModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.stok.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stok Aset / Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Varian Paket</label>
                        <select class="form-control" name="id_varian" required>
                            <option value="">-- Pilih Paket Varian --</option>
                            @foreach($varianAktif as $v)
                            <option value="{{ $v->id_varian }}">{{ $v->tipeLayanan->produk->nama_produk }} - {{ $v->tipeLayanan->nama_tipe }} ({{ $v->nama_varian }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Identitas Utama (Email / Username / Link ZIP)</label>
                        <input type="text" class="form-control" name="email_username" placeholder="Masukkan username, email, atau link file" required>
                    </div>
                    <div class="form-group">
                        <label>Kredensial Sekunder (Password / PIN / Password ZIP)</label>
                        <input type="text" class="form-control" name="password" placeholder="Masukkan password (atau strip '-' jika tidak ada)" required>
                    </div>
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="3" placeholder="Instruksi tambahan untuk pembeli..."></textarea>
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

<!-- Bulk Add Modal Stok -->
<div class="modal fade" id="bulkStokModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.stok.bulk_store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Upload Stok Aset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Varian Paket</label>
                        <select class="form-control" name="id_varian" required>
                            <option value="">-- Pilih Paket Varian --</option>
                            @foreach($varianAktif as $v)
                            <option value="{{ $v->id_varian }}">{{ $v->tipeLayanan->produk->nama_produk }} - {{ $v->tipeLayanan->nama_tipe }} ({{ $v->nama_varian }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data Kredensial (Pemisah vertikal <code>|</code>)</label>
                        <textarea class="form-control" name="bulk_data" rows="8" placeholder="Format: identitas1|kredensial2|catatan (Opsional)&#10;Contoh:&#10;user1@mail.com|pass123|Catatan slot 1&#10;user2@mail.com|pass456|Catatan slot 2" required></textarea>
                        <small class="form-text text-muted">Gunakan satu baris untuk setiap aset/stok.</small>
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

<!-- Modal Detail Decrypt Stok -->
<div class="modal fade" id="detailStokModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kredensial Stok Aset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Identitas Utama</label>
                    <input type="text" class="form-control" id="detail-email" readonly>
                </div>
                <div class="form-group">
                    <label>Kredensial Sekunder (Decrypted On-Demand)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="detail-password" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('detail-password')">Salin</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Catatan Tambahan</label>
                    <textarea class="form-control" id="detail-catatan" rows="3" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
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
    
    // Initialize DataTables if they exist
    $(document).ready(function() {
        if($.fn.DataTable) {
            $('#table-tipe').DataTable();
            $('#table-varian').DataTable();
            $('#table-stok').DataTable();
        }
    });
</script>

@endsection
