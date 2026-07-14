@extends('layout')

@section('title', 'Laporan Masalah')

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

<div class="row">
    <!-- Form Tambah Laporan -->
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4>Buat Laporan Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('customer.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @php
                        $userPhone = Auth::user()->customer->first()?->nomor_telepon;
                    @endphp
                    <div class="form-group">
                        <label for="nomor_telepon">Nomor WhatsApp Terdaftar</label>
                        <input type="text" class="form-control {{ empty($userPhone) ? 'is-invalid border-danger' : '' }}" id="nomor_telepon" value="{{ $userPhone ?? 'Belum diisi' }}" readonly disabled>
                        @if(empty($userPhone))
                            <div class="invalid-feedback d-block mt-1">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>Anda belum mengisi nomor telepon. Silakan <a href="{{ url('profile_customer/' . Auth::user()->id) }}" class="font-weight-bold text-underline">isi di profil Anda</a> terlebih dahulu agar admin bisa menghubungi Anda.
                            </div>
                        @else
                            <small class="form-text text-muted">Admin akan menghubungi Anda via WhatsApp ke nomor ini. Jika salah, silakan update di profil Anda.</small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul Laporan</label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Akun tidak bisa login" required>
                        @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Masalah</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan detail kendala Anda..." style="height: auto;" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="gambar">Upload Screenshot / Gambar (Opsional)</label>
                        <input type="file" class="form-control-file @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/*">
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                        @error('gambar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary float-right mt-2">Kirim Laporan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Laporan -->
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h4>Riwayat Laporan Masalah</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Gambar</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->judul }}</strong></td>
                                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($item->gambar)
                                    <a href="{{ asset('assets/img/laporan/' . $item->gambar) }}" target="_blank">
                                        <img src="{{ asset('assets/img/laporan/' . $item->gambar) }}" alt="lampiran" class="rounded" style="max-height: 40px; max-width: 40px; object-fit: cover;">
                                    </a>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                    @elseif($item->status == 'proses')
                                    <span class="badge badge-primary">Diproses</span>
                                    @else
                                    <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="viewDetailLaporan({{ json_encode($item) }})">Detail</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada riwayat laporan masalah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Laporan -->
<div class="modal fade" id="detailLaporanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-judul">Detail Laporan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold">Status Laporan</label>
                    <div id="modal-status-badge"></div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Judul</label>
                    <p id="modal-judul-text" class="text-primary"></p>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Deskripsi Masalah</label>
                    <p id="modal-deskripsi-text" class="border p-2 bg-light rounded text-dark" style="white-space: pre-wrap;"></p>
                </div>
                <div class="form-group" id="modal-gambar-group">
                    <label class="font-weight-bold">Lampiran Gambar</label>
                    <div>
                        <a id="modal-gambar-link" href="#" target="_blank">
                            <img id="modal-gambar-img" src="" alt="lampiran" class="img-fluid rounded border" style="max-height: 250px;">
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewDetailLaporan(laporan) {
        document.getElementById('modal-judul-text').innerText = laporan.judul;
        document.getElementById('modal-deskripsi-text').innerText = laporan.deskripsi;
        
        const badgeContainer = document.getElementById('modal-status-badge');
        badgeContainer.innerHTML = '';
        if (laporan.status === 'pending') {
            badgeContainer.innerHTML = '<span class="badge badge-warning">Pending</span>';
        } else if (laporan.status === 'proses') {
            badgeContainer.innerHTML = '<span class="badge badge-primary">Diproses</span>';
        } else {
            badgeContainer.innerHTML = '<span class="badge badge-success">Selesai</span>';
        }

        const gambarGroup = document.getElementById('modal-gambar-group');
        if (laporan.gambar) {
            gambarGroup.style.display = 'block';
            document.getElementById('modal-gambar-link').href = "{{ asset('assets/img/laporan') }}/" + laporan.gambar;
            document.getElementById('modal-gambar-img').src = "{{ asset('assets/img/laporan') }}/" + laporan.gambar;
        } else {
            gambarGroup.style.display = 'none';
        }

        $('#detailLaporanModal').modal('show');
    }
</script>

@endsection
