@extends('layout')

@section('title', 'Update Produk')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow p-3 mb-5 bg-white rounded mt-3">
                <div class="ml-4">
                    <div class="row mt-3 mb-3">
                        <div class="col">
                            <h5 class="text-primary">Update Produk</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <form method="post" action="{{ route('menu_produk.update', $data->id_produk) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Nama Produk --}}
                                <div class="form-group mr-3">
                                    <label for="nama_produk"><strong>Nama Produk</strong></label>
                                    <input type="text" class="form-control border border-primary @error('nama_produk') is-invalid @enderror"
                                        id="nama_produk" name="nama_produk" autocomplete="off"
                                        value="{{ old('nama_produk', $data->nama_produk) }}">
                                    @error('nama_produk')
                                    <div class="invalid-feedback">{{ ucfirst($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div class="form-group mr-3">
                                    <label for="deskripsi">Deskripsi <small class="text-muted">(opsional)</small></label>
                                    <input type="text" class="form-control border border-primary @error('deskripsi') is-invalid @enderror"
                                        id="deskripsi" name="deskripsi" autocomplete="off"
                                        value="{{ old('deskripsi', $data->deskripsi) }}">
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ ucfirst($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="form-group mr-3">
                                    <label for="status"><strong>Status</strong></label>
                                    <select class="form-control border border-primary" id="status" name="status">
                                        <option value="aktif" @if(old('status', $data->status) == 'aktif') selected @endif>Aktif</option>
                                        <option value="nonaktif" @if(old('status', $data->status) == 'nonaktif') selected @endif>Nonaktif</option>
                                    </select>
                                </div>

                                {{-- Cover Image --}}
                                <div class="form-group mr-3">
                                    <label for="gambar">Cover Image <small class="text-muted">(kosongkan jika tidak ganti)</small></label>
                                    @if($data->gambar)
                                    <div class="mb-2">
                                        <img src="{{ asset('assets/img/produk_premium/' . $data->gambar) }}"
                                            alt="Cover saat ini" style="max-height:100px;border-radius:6px;border:1px solid #ccc;">
                                        <small class="d-block text-muted mt-1">Cover saat ini</small>
                                    </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('gambar') is-invalid @enderror"
                                            name="gambar" id="gambar" accept="image/jpeg,image/png,image/jpg,image/webp">
                                        <label class="custom-file-label border border-primary" for="gambar">Pilih gambar baru...</label>
                                    </div>
                                    @error('gambar')
                                    <div class="text-danger mt-1" style="font-size:0.85rem;">{{ ucfirst($message) }}</div>
                                    @enderror
                                    <div id="gambar-preview" class="mt-2"></div>
                                </div>

                                <hr />

                                <div class="d-flex align-items-center" style="gap: 10px;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ url('menu_produk') }}" class="btn btn-secondary">Batal</a>
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
        const label = this.nextElementSibling;
        if (label) label.textContent = this.files[0]?.name || 'Pilih gambar baru...';
    });
</script>

@endsection