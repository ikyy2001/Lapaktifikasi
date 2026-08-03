@extends('layout')

@section('title', 'Buat Voucher Baru (Admin)')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 font-weight-bold text-dark">Buat Voucher Baru</h1>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm border-0" style="max-width: 800px;">
        <div class="card-body">
            <form action="{{ route('admin.voucher.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Kode Voucher</label>
                    <input type="text" name="kode" class="form-control text-uppercase" placeholder="Misal: HEMAT10" value="{{ old('kode') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Tipe Diskon</label>
                        <select name="tipe_diskon" class="form-control" required>
                            <option value="persen" {{ old('tipe_diskon') == 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="nominal" {{ old('tipe_diskon') == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Nilai Diskon</label>
                        <input type="number" step="0.01" name="nilai_diskon" class="form-control" placeholder="10 atau 10000" value="{{ old('nilai_diskon') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Maksimal Potongan (Rp) <small class="text-muted">(Khusus Tipe Persen, Optional)</small></label>
                        <input type="number" step="0.01" name="maksimal_potongan" class="form-control" placeholder="Contoh: 50000" value="{{ old('maksimal_potongan') }}">
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Minimal Transaksi (Rp)</label>
                        <input type="number" step="0.01" name="minimal_transaksi" class="form-control" placeholder="0" value="{{ old('minimal_transaksi', 0) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-bold">Kuota Total <small class="text-muted">(Kosongkan jika unlimited)</small></label>
                        <input type="number" name="kuota_total" class="form-control" placeholder="Unlimited" value="{{ old('kuota_total') }}">
                    </div>

                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-bold">Berlaku Dari</label>
                        <input type="date" name="berlaku_dari" class="form-control" value="{{ old('berlaku_dari') }}">
                    </div>

                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-bold">Berlaku Sampai</label>
                        <input type="date" name="berlaku_sampai" class="form-control" value="{{ old('berlaku_sampai') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Scope Voucher</label>
                        <select name="scope" id="scope_select" class="form-control" required onchange="toggleTokoSelect()">
                            <option value="global" {{ old('scope') == 'global' ? 'selected' : '' }}>Global (Semua Toko)</option>
                            <option value="toko_spesifik" {{ old('scope') == 'toko_spesifik' ? 'selected' : '' }}>Toko Spesifik</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group mb-3" id="toko_select_group" style="display: none;">
                        <label class="font-weight-bold">Pilih Toko</label>
                        <select name="id_toko" class="form-control">
                            <option value="">-- Pilih Toko --</option>
                            @foreach($tokos as $t)
                                <option value="{{ $t->id_toko }}" {{ old('id_toko') == $t->id_toko ? 'selected' : '' }}>{{ $t->nama_toko }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">Simpan Voucher</button>
                    <a href="{{ route('admin.voucher.index') }}" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleTokoSelect() {
        var scope = document.getElementById('scope_select').value;
        var group = document.getElementById('toko_select_group');
        group.style.display = scope === 'toko_spesifik' ? 'block' : 'none';
    }
    toggleTokoSelect();
</script>
@endsection
