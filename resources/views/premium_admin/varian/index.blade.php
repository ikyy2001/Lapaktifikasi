@extends('layout')

@section('title', 'Kelola Varian Layanan')

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
                <h4>Daftar Varian Layanan</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addVarianModal">
                    <i class="fas fa-plus mr-2"></i>Tambah Varian Layanan
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
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
                            @forelse($varian as $index => $item)
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

                            <!-- Edit Modal -->
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
                                                        @foreach($tipe as $t)
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
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addVarianModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.varian.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Varian Layanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Tipe Layanan</label>
                        <select class="form-control" name="id_tipe" required>
                            <option value="">-- Pilih Tipe Layanan --</option>
                            @foreach($tipe as $t)
                            <option value="{{ $t->id_tipe }}">{{ $t->produk->nama_produk }} - {{ $t->nama_tipe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Varian</label>
                        <input type="text" class="form-control" name="nama_varian" placeholder="Contoh: 1 Bulan, 3 Bulan" required>
                    </div>
                    <div class="form-group">
                        <label>Durasi (Hari)</label>
                        <input type="number" class="form-control" name="durasi_hari" placeholder="Contoh: 30, 90" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Harga (IDR)</label>
                        <input type="number" class="form-control" name="harga" placeholder="Contoh: 35000" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi varian..."></textarea>
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

@endsection
