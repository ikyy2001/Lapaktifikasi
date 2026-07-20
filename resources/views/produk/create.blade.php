@extends('layout')

@section('title', 'Tambah Produk')

@section('content')

@if($success = Session::get('success'))
<script>
    const Toast = Swal.mixin({ toast: true, position: "top-end", showConfirmButton: false, timer: 3000, timerProgressBar: true });
    Toast.fire({ icon: "success", title: "{{ $success }}" });
</script>
@elseif($error = Session::get('error'))
<script>
    const Toast = Swal.mixin({ toast: true, position: "top-end", showConfirmButton: false, timer: 3000, timerProgressBar: true });
    Toast.fire({ icon: "error", title: "{{ $error }}" });
</script>
@endif

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow p-3 mb-5 bg-white rounded mt-3">
                <div class="ml-4">
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <h5 class="text-primary">Tambah Produk</h5>
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                Contoh: <em>Netflix</em>, <em>Spotify</em>, <em>Disney+</em> — setelah ini atur Tipe &amp; Varian Layanan-nya.
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <form method="post" action="{{ route('menu_produk.store') }}" enctype="multipart/form-data">
                                @csrf

                                {{-- Nama Produk --}}
                                <div class="form-group mr-3">
                                    <label for="nama_produk"><strong>Nama Produk</strong></label>
                                    <input type="text" class="form-control border border-primary @error('nama_produk') is-invalid @enderror"
                                        id="nama_produk" name="nama_produk" autocomplete="off"
                                        placeholder="Contoh: Netflix, Spotify, Disney+"
                                        value="{{ old('nama_produk') }}">
                                    @error('nama_produk')
                                    <div class="invalid-feedback">{{ ucfirst($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div class="form-group mr-3">
                                    <label for="deskripsi">Deskripsi <small class="text-muted">(opsional)</small></label>
                                    <input type="text" class="form-control border border-primary @error('deskripsi') is-invalid @enderror"
                                        id="deskripsi" name="deskripsi" autocomplete="off"
                                        placeholder="Contoh: Akun Netflix Premium Garansi Full Month"
                                        value="{{ old('deskripsi') }}">
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ ucfirst($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="form-group mr-3">
                                    <label for="status"><strong>Status</strong></label>
                                    <select class="form-control border border-primary @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="">- Pilih Status -</option>
                                        <option value="aktif" @if(old('status') == 'aktif') selected @endif>Aktif</option>
                                        <option value="nonaktif" @if(old('status') == 'nonaktif') selected @endif>Nonaktif</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ ucfirst($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Cover Image --}}
                                <div class="form-group mr-3">
                                    <label for="gambar">Cover Image <small class="text-muted">(opsional, max 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('gambar') is-invalid @enderror"
                                            name="gambar" id="gambar" accept="image/jpeg,image/png,image/jpg,image/webp">
                                        <label class="custom-file-label border border-primary" for="gambar">Pilih gambar...</label>
                                    </div>
                                    @error('gambar')
                                    <div class="text-danger mt-1" style="font-size:0.85rem;">{{ ucfirst($message) }}</div>
                                    @enderror
                                    <div id="gambar-preview" class="mt-2"></div>
                                </div>

                                <hr />

                                <div class="d-flex align-items-center" style="gap: 10px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Simpan Produk
                                    </button>
                                    <a href="{{ url('menu_produk') }}" class="btn btn-secondary">Batal</a>
                                </div>

                                <div class="mt-3 p-2 rounded" style="background:#f0f4ff; font-size:0.85rem;">
                                    <i class="fas fa-info-circle text-primary mr-1"></i>
                                    <strong>Langkah berikutnya:</strong> Setelah produk tersimpan, buka
                                    <a href="{{ route('premium.tipe.index') }}">Tipe Layanan</a>
                                    dan tambahkan tipe seperti <em>"Private"</em> atau <em>"Sharing"</em>.
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview gambar sebelum upload
    document.getElementById('gambar').addEventListener('change', function () {
        const preview = document.getElementById('gambar-preview');
        preview.innerHTML = '';
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}" style="max-height:120px;border-radius:6px;border:1px solid #ccc;" alt="preview">`;
            };
            reader.readAsDataURL(this.files[0]);
        }
        // Update label custom-file
        const label = this.nextElementSibling;
        if (label) label.textContent = this.files[0]?.name || 'Pilih gambar...';
    });
</script>

@endsection