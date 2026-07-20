@extends('layout')

@section('title', 'Kelola Tipe Layanan')

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
                    <h4>Daftar Tipe Layanan</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTipeModal">
                        <i class="fas fa-plus mr-2"></i>Tambah Tipe Layanan
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                @forelse($tipe as $index => $item)
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
                                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#editTipeModal{{ $item->id_tipe }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editTipeModal{{ $item->id_tipe }}" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('premium.tipe.update', $item->id_tipe) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Tipe Layanan</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
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
                                                            <input type="text" class="form-control" name="nama_tipe"
                                                                value="{{ $item->nama_tipe }}" required>
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
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Tutup</button>
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
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addTipeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('premium.tipe.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Tipe Layanan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Produk</label>
                            <select class="form-control" name="id_produk" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produk as $p)
                                    <option value="{{ $p->id_produk }}">{{ $p->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Tipe Layanan</label>
                            <input type="text" class="form-control" name="nama_tipe" placeholder="Contoh: Private, Sharing"
                                required>
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