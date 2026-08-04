@extends('layout')

@section('title', 'Kelola Inventaris & Aset Digital')

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

@php
    $activeTab = request()->query('tab', 'tipe');
@endphp

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Kelola Inventaris & Aset Produk Digital</h4>
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
                            <i class="fas fa-layer-group mr-1"></i> Varian & File Digital
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
                                            <td>{{ $item->produk->nama_produk ?? '-' }}</td>
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
                            <h5 class="mb-0">Daftar Varian / Paket Digital</h5>
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
                                        <th>File Digital</th>
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
                                        <td>{{ $item->tipeLayanan?->produk?->nama_produk ?? '-' }} - {{ $item->tipeLayanan?->nama_tipe ?? '-' }}</td>
                                        <td><strong>{{ $item->nama_varian }}</strong></td>
                                        <td>
                                            @if($item->file_path)
                                                <span class="badge badge-info"><i class="fas fa-file"></i> {{ Str::limit($item->file_path, 20) }}</span>
                                            @else
                                                <span class="badge badge-warning">Belum ada file</span>
                                            @endif
                                        </td>
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
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada data varian layanan digital.</td>
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
<!-- BAGIAN MODAL GLOBAL (TAMBAH & EDIT DATA)                -->
<!-- ======================================================= -->

<!-- Edit Modal Tipe -->
@foreach($tipeSemua as $item)
<div class="modal fade" id="editTipeModal{{ $item->id_tipe }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('digital.tipe.update', $item->id_tipe) }}" method="POST">
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
                                    {{ $p->nama_produk }}
                                </option>
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
@endforeach

<!-- Edit Modal Varian -->
@foreach($varianSemua as $item)
<div class="modal fade" id="editVarianModal{{ $item->id_varian }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('digital.varian.update', $item->id_varian) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Varian Layanan Digital</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Tipe Layanan</label>
                        <select class="form-control" name="id_tipe" required>
                            @foreach($tipeAktif as $t)
                            <option value="{{ $t->id_tipe }}" {{ $t->id_tipe == $item->id_tipe ? 'selected' : '' }}>
                                {{ $t->produk->nama_produk ?? '-' }} - {{ $t->nama_tipe }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Varian</label>
                        <input type="text" class="form-control" name="nama_varian" value="{{ $item->nama_varian }}" required>
                    </div>
                    <input type="hidden" name="durasi_hari" value="0">
                    <div class="form-group">
                        <label>File Digital (Opsional)</label>
                        <input type="file" class="form-control" name="file_digital" accept=".zip,.rar,.txt,.pdf,.png,.jpg,.jpeg,.webp">
                        <small class="form-text text-muted">Upload file baru jika ingin mengganti file lama. Maksimal {{ $limit_mb ?? 250 }}MB.</small>
                        @if($item->file_path)
                            <small class="form-text text-info">File saat ini: {{ $item->file_path }}</small>
                        @endif
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
@endforeach

<!-- Modal Tambah Tipe -->
<div class="modal fade" id="addTipeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('digital.tipe.store') }}" method="POST">
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
                            <option value="">-- Pilih Produk Digital --</option>
                            @foreach($produk as $p)
                                <option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                        @if($produk->isEmpty())
                            <small class="form-text text-danger">Belum ada produk digital. Silakan buat produk digital terlebih dahulu di <strong>Menu Produk Digital</strong>.</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Nama Tipe / Kategori</label>
                        <input type="text" class="form-control" name="nama_tipe" placeholder="Contoh: ZIP Download, Full Source Code" required>
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
                    <button type="submit" class="btn btn-primary" {{ $produk->isEmpty() ? 'disabled' : '' }}>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Varian -->
<div class="modal fade" id="addVarianModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('digital.varian.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Varian / Paket Digital</h5>
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
                            <option value="{{ $t->id_tipe }}">{{ $t->produk->nama_produk ?? '-' }} - {{ $t->nama_tipe }}</option>
                            @endforeach
                        </select>
                        @if($tipeAktif->isEmpty())
                            <small class="form-text text-danger">Belum ada tipe layanan aktif. Silakan buat Tipe Layanan terlebih dahulu.</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Nama Varian (Paket)</label>
                        <input type="text" class="form-control" name="nama_varian" placeholder="Contoh: Versi 1.0, Source Code Full" required>
                    </div>
                    <input type="hidden" name="durasi_hari" value="0">
                    <div class="form-group">
                        <label>File Digital (ZIP, PDF, Image, Txt)</label>
                        <input type="file" class="form-control" name="file_digital" accept=".zip,.rar,.txt,.pdf,.png,.jpg,.jpeg,.webp" required>
                        <small class="form-text text-muted">File ini yang akan diunduh oleh pembeli. Maksimal {{ $limit_mb ?? 250 }}MB.</small>
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
                    <button type="submit" class="btn btn-primary" {{ $tipeAktif->isEmpty() ? 'disabled' : '' }}>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
