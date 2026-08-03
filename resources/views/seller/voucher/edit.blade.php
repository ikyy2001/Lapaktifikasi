@extends('layout')

@section('title', 'Edit Voucher Toko')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 font-weight-bold text-dark">Edit Voucher {{ $voucher->kode }}</h1>
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
            <form action="{{ route('seller.voucher.update', $voucher->id_voucher) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label class="font-weight-bold">Kode Voucher Toko</label>
                    <input type="text" name="kode" class="form-control text-uppercase" value="{{ old('kode', $voucher->kode) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Tipe Diskon</label>
                        <select name="tipe_diskon" class="form-control" required>
                            <option value="persen" {{ old('tipe_diskon', $voucher->tipe_diskon) == 'persen' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="nominal" {{ old('tipe_diskon', $voucher->tipe_diskon) == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Nilai Diskon</label>
                        <input type="number" step="0.01" name="nilai_diskon" class="form-control" value="{{ old('nilai_diskon', (float)$voucher->nilai_diskon) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Maksimal Potongan (Rp) <small class="text-muted">(Khusus Tipe Persen)</small></label>
                        <input type="number" step="0.01" name="maksimal_potongan" class="form-control" value="{{ old('maksimal_potongan', (float)$voucher->maksimal_potongan) }}">
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label class="font-weight-bold">Minimal Transaksi (Rp)</label>
                        <input type="number" step="0.01" name="minimal_transaksi" class="form-control" value="{{ old('minimal_transaksi', (float)$voucher->minimal_transaksi) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-bold">Kuota Total</label>
                        <input type="number" name="kuota_total" class="form-control" value="{{ old('kuota_total', $voucher->kuota_total) }}">
                    </div>

                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-bold">Berlaku Dari</label>
                        <input type="date" name="berlaku_dari" class="form-control" value="{{ old('berlaku_dari', $voucher->berlaku_dari ? $voucher->berlaku_dari->format('Y-m-d') : '') }}">
                    </div>

                    <div class="col-md-4 form-group mb-3">
                        <label class="font-weight-bold">Berlaku Sampai</label>
                        <input type="date" name="berlaku_sampai" class="form-control" value="{{ old('berlaku_sampai', $voucher->berlaku_sampai ? $voucher->berlaku_sampai->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary px-4">Update Voucher Toko</button>
                    <a href="{{ route('seller.voucher.index') }}" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
