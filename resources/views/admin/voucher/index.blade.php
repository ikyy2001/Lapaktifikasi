@extends('layout')

@section('title', 'Kelola Voucher (Admin)')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 font-weight-bold mb-0 text-dark">Kelola Voucher Diskon</h1>
        <a href="{{ route('admin.voucher.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg mr-1"></i> Buat Voucher Baru
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
            <form method="GET" action="{{ route('admin.voucher.index') }}" class="mb-4">
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode voucher..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode</th>
                            <th>Tipe & Nilai</th>
                            <th>Minimal & Maksimal</th>
                            <th>Kuota</th>
                            <th>Masa Berlaku</th>
                            <th>Scope</th>
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
                                <div class="font-weight-bold mt-1">
                                    {{ $v->tipe_diskon === 'persen' ? (float)$v->nilai_diskon . '%' : 'Rp ' . number_format((float)$v->nilai_diskon, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="small">
                                Min: Rp {{ number_format((float)$v->minimal_transaksi, 0, ',', '.') }}
                                @if($v->maksimal_potongan)
                                <br>Max: Rp {{ number_format((float)$v->maksimal_potongan, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $v->kuota_terpakai }}</span> /
                                {{ $v->kuota_total !== null ? $v->kuota_total : '∞' }}
                            </td>
                            <td class="small">
                                {{ $v->berlaku_dari ? $v->berlaku_dari->format('d/m/Y') : 'Sekarang' }} -
                                {{ $v->berlaku_sampai ? $v->berlaku_sampai->format('d/m/Y') : 'Selamanya' }}
                            </td>
                            <td>
                                @if($v->scope === 'global')
                                    <span class="badge badge-success">Global (Semua Toko)</span>
                                @else
                                    <span class="badge badge-warning">Toko: {{ $v->toko?->nama_toko ?? 'Spesifik' }}</span>
                                @endif
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
                                    <a href="{{ route('admin.voucher.edit', $v->id_voucher) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.voucher.toggle_status', $v->id_voucher) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $v->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $v->is_active ? 'bi-power' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada voucher yang dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
