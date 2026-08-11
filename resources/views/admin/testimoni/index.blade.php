@extends('layout')

@section('title', 'Kelola Testimoni')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-dark"><i class="bi bi-chat-quote-fill text-primary mr-2"></i> Kelola Testimoni Website</h4>
                <button type="button" class="btn btn-primary btn-sm px-4 shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalTambah">
                    <i class="bi bi-plus-circle mr-1"></i> Tambah Testimoni
                </button>
            </div>
            
            <div class="card-body pt-0">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-left-success" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-left-danger" role="alert">
                    <strong>Gagal!</strong> Periksa kembali inputan Anda.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle" id="table-1">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="20%">Nama Pelanggan</th>
                                <th width="20%">Profesi / Detail</th>
                                <th width="10%" class="text-center">Rating</th>
                                <th width="30%">Komentar / Ulasan</th>
                                <th width="5%" class="text-center">Status</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($testimonis as $index => $item)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle font-weight-bold text-dark">{{ $item->name }}</td>
                                <td class="align-middle text-muted">{{ $item->role }}</td>
                                <td class="text-center align-middle text-warning font-weight-bold">
                                    @for($i=1; $i<=$item->rating; $i++) &#9733; @endfor
                                </td>
                                <td class="align-middle">"{{ $item->comment }}"</td>
                                <td class="text-center align-middle">
                                    @if($item->is_active)
                                        <span class="badge badge-success px-3 py-2"><i class="bi bi-check-circle mr-1"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2"><i class="bi bi-x-circle mr-1"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('admin.testimoni.toggle', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $item->is_active ? 'btn-warning' : 'btn-success' }} mb-1" data-toggle="tooltip" title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $item->is_active ? 'bi-eye-slash-fill' : 'bi-eye-fill' }}"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-info mb-1" data-toggle="modal" data-target="#modalEdit{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.testimoni.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mb-1" data-toggle="tooltip" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header bg-light border-bottom-0 pb-3">
                <h5 class="modal-title font-weight-bold text-dark">Tambah Testimoni</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.testimoni.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Andi Firmansyah" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Profesi / Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="role" class="form-control" placeholder="Contoh: Siswa · Bogor" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Rating Bintang <span class="text-danger">*</span></label>
                        <select name="rating" class="form-control" required>
                            <option value="5" selected>5 Bintang (Sangat Puas)</option>
                            <option value="4">4 Bintang (Puas)</option>
                            <option value="3">3 Bintang (Cukup)</option>
                            <option value="2">2 Bintang (Kurang)</option>
                            <option value="1">1 Bintang (Kecewa)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Isi Testimoni <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Tuliskan ulasan atau testimoni pelanggan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach($testimonis as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header bg-light border-bottom-0 pb-3">
                <h5 class="modal-title font-weight-bold text-dark">Edit Testimoni</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.testimoni.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Profesi / Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="role" class="form-control" value="{{ $item->role }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Rating Bintang <span class="text-danger">*</span></label>
                        <select name="rating" class="form-control" required>
                            @for($r=5; $r>=1; $r--)
                                <option value="{{ $r }}" {{ $item->rating == $r ? 'selected' : '' }}>{{ $r }} Bintang</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Isi Testimoni <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="4" required>{{ $item->comment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info font-weight-bold px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table-1').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection
