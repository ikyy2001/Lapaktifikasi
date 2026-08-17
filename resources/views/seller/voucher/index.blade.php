@extends('layout')

@section('title', 'Voucher Toko Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 font-weight-bold mb-0 text-dark">Voucher Toko ({{ $toko->nama_toko }})</h1>
            <p class="text-muted small mb-0">Kelola kode diskon khusus untuk produk-produk di toko Anda.</p>
        </div>
        <a href="{{ route('seller.voucher.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg mr-1"></i> Buat Voucher Toko
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="GET" action="{{ route('seller.voucher.index') }}" class="mb-4">
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode voucher..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" aria-label="Cari Voucher"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </form>

            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Tipe & Nilai Diskon</th>
                            <th>Minimal Transaksi</th>
                            <th>Maksimal Potongan</th>
                            <th>Kuota Terpakai</th>
                            <th>Masa Berlaku</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $v)
                        <tr>
                            <td><strong class="font-monospace text-primary" style="font-size: 1.1rem;">{{ $v->kode }}</strong></td>
                            <td>
                                <span class="badge badge-info">{{ strtoupper($v->tipe_diskon) }}</span>
                                <span class="font-weight-bold ml-1">
                                    {{ $v->tipe_diskon === 'persen' ? (float)$v->nilai_diskon . '%' : 'Rp ' . number_format((float)$v->nilai_diskon, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>Rp {{ number_format((float)$v->minimal_transaksi, 0, ',', '.') }}</td>
                            <td>{{ $v->maksimal_potongan ? 'Rp ' . number_format((float)$v->maksimal_potongan, 0, ',', '.') : '-' }}</td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $v->kuota_terpakai }}</span> /
                                {{ $v->kuota_total !== null ? $v->kuota_total : '∞' }}
                            </td>
                            <td class="small">
                                {{ $v->berlaku_dari ? $v->berlaku_dari->format('d/m/Y') : 'Sekarang' }} -
                                {{ $v->berlaku_sampai ? $v->berlaku_sampai->format('d/m/Y') : 'Selamanya' }}
                            </td>
                            <td>
                                @if($v->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('seller.voucher.edit', $v->id_voucher) }}" class="btn btn-sm btn-outline-primary" title="Edit Voucher" aria-label="Edit Voucher {{ $v->kode }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('seller.voucher.toggle_status', $v->id_voucher) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $v->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}" aria-label="{{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Voucher {{ $v->kode }}">
                                            <i class="bi {{ $v->is_active ? 'bi-power' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada voucher toko yang dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div class="d-md-none">
                @forelse($vouchers as $v)
                <div class="card mb-3 border shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="font-monospace text-primary h5 mb-0">{{ $v->kode }}</strong>
                            @if($v->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </div>
                        
                        <div class="mb-2">
                            <span class="badge badge-info mr-1">{{ strtoupper($v->tipe_diskon) }}</span>
                            <span class="font-weight-bold text-dark">
                                Diskon {{ $v->tipe_diskon === 'persen' ? (float)$v->nilai_diskon . '%' : 'Rp ' . number_format((float)$v->nilai_diskon, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="row text-muted small mb-2">
                            <div class="col-6">
                                Min: <strong>Rp {{ number_format((float)$v->minimal_transaksi, 0, ',', '.') }}</strong>
                            </div>
                            <div class="col-6 text-right">
                                Quota: <strong>{{ $v->kuota_terpakai }}/{{ $v->kuota_total ?? '∞' }}</strong>
                            </div>
                        </div>

                        <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                            <span class="small text-muted">
                                {{ $v->berlaku_sampai ? $v->berlaku_sampai->format('d/m/Y') : 'Selamanya' }}
                            </span>
                            <div class="d-flex gap-2">
                                <a href="{{ route('seller.voucher.edit', $v->id_voucher) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit Voucher {{ $v->kode }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('seller.voucher.toggle_status', $v->id_voucher) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $v->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" aria-label="{{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Voucher {{ $v->kode }}">
                                        <i class="bi {{ $v->is_active ? 'bi-power' : 'bi-check-circle' }}"></i> {{ $v->is_active ? 'Matikan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">Belum ada voucher toko yang dibuat.</div>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button (FAB) Mobile -->
<a href="{{ route('seller.voucher.create') }}" class="btn btn-primary fab-mobile" aria-label="Buat Voucher Toko">
    <i class="bi bi-plus-lg"></i> Voucher Baru
</a>
@endsection
