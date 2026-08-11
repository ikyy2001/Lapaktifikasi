@extends('layout')

@section('title', 'Kelola Mitra Industri')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-dark"><i class="bi bi-buildings-fill text-primary mr-2"></i> Kelola Mitra Industri</h4>
                <button type="button" class="btn btn-primary btn-sm px-4 shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalTambah">
                    <i class="bi bi-plus-circle mr-1"></i> Tambah Mitra
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
                                <th width="20%">Logo</th>
                                <th width="35%">Nama Mitra</th>
                                <th width="15%" class="text-center">Status</th>
                                <th width="25%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mitras as $index => $mitra)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">
                                    <div style="width: 120px; height: 50px; display: flex; align-items: center; background: #f8f9fa; border-radius: 8px; padding: 5px; border: 1px solid #eee;">
                                        <img src="{{ asset($mitra->image_path) }}" alt="{{ $mitra->name }}" style="max-height: 40px; max-width: 100%; margin: 0 auto; object-fit: contain;">
                                    </div>
                                </td>
                                <td class="align-middle font-weight-bold text-dark">{{ $mitra->name }}</td>
                                <td class="text-center align-middle">
                                    @if($mitra->is_active)
                                        <span class="badge badge-success px-3 py-2"><i class="bi bi-check-circle mr-1"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2"><i class="bi bi-x-circle mr-1"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('admin.mitra_industri.toggle', $mitra->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $mitra->is_active ? 'btn-warning' : 'btn-success' }} mb-1" data-toggle="tooltip" title="{{ $mitra->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $mitra->is_active ? 'bi-eye-slash-fill' : 'bi-eye-fill' }}"></i>
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-info mb-1" data-toggle="modal" data-target="#modalEdit{{ $mitra->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form action="{{ route('admin.mitra_industri.destroy', $mitra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mitra ini?');">
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
                <h5 class="modal-title font-weight-bold text-dark">Tambah Mitra Industri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.mitra_industri.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Mitra <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: PT. Jaringan Lintas Media" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Logo (Image) <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted mt-2 d-block">Format: JPG, PNG, WEBP, SVG. Maks 2MB.</small>
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
@foreach($mitras as $mitra)
<div class="modal fade" id="modalEdit{{ $mitra->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header bg-light border-bottom-0 pb-3">
                <h5 class="modal-title font-weight-bold text-dark">Edit Mitra Industri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.mitra_industri.update', $mitra->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama Mitra <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $mitra->name }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Logo Saat Ini</label>
                        <div class="mb-3">
                            <div style="width: 150px; height: 60px; display: flex; align-items: center; background: #f8f9fa; border-radius: 8px; padding: 5px; border: 1px solid #eee;">
                                <img src="{{ asset($mitra->image_path) }}" alt="{{ $mitra->name }}" style="max-height: 50px; max-width: 100%; margin: 0 auto; object-fit: contain;">
                            </div>
                        </div>
                        <label class="font-weight-bold">Ganti Logo Baru (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted mt-2 d-block">Biarkan kosong jika tidak ingin mengubah logo.</small>
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
