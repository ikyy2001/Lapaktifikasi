@extends('layout')

@section('title', 'Kelola Produk Premium')

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
                <h4>Katalog Produk Premium</h4>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProdukModal">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produk as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($item->gambar)
                                    <img src="{{ asset('assets/img/produk_premium/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" width="50" class="img-thumbnail">
                                    @else
                                    <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td><strong>{{ $item->nama_produk }}</strong></td>
                                <td>{{ $item->deskripsi ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->status == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editProdukModal{{ $item->id_produk }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editProdukModal{{ $item->id_produk }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('premium.produk.update', $item->id_produk) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Produk: {{ $item->nama_produk }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nama Produk</label>
                                                    <input type="text" class="form-control" name="nama_produk" value="{{ $item->nama_produk }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deskripsi</label>
                                                    <textarea class="form-control" name="deskripsi" rows="3">{{ $item->deskripsi }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Gambar Produk (Opsional)</label>
                                                    <input type="file" class="form-control-file" name="gambar">
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
                                <td colspan="6" class="text-center text-muted">Belum ada data produk premium.</td>
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
<div class="modal fade" id="addProdukModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('premium.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Produk Premium</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" placeholder="Contoh: Spotify, Netflix" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi produk..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Gambar Produk</label>
                        <input type="file" class="form-control-file" name="gambar">
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
