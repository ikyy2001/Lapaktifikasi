@extends('layout')

@section('title', 'Histori Mutasi Saldo: ' . $toko->nama_toko)

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Semua Riwayat Mutasi Saldo</h4>
                <a href="{{ url('seller/dashboard') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
            </div>
            <div class="card-body">
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Nominal</th>
                                <th>Saldo Akhir</th>
                                <th>Keterangan</th>
                                <th>Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mutasi as $log)
                            <tr>
                                <td>{{ $log->created_at ? $log->created_at->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    @if($log->tipe == 'kredit_penjualan')
                                        <span class="badge badge-success">Kredit Penjualan</span>
                                    @elseif($log->tipe == 'potong_withdraw')
                                        <span class="badge badge-danger">Potong Withdraw</span>
                                    @elseif($log->tipe == 'penyesuaian_admin')
                                        <span class="badge badge-warning">Penyesuaian Admin</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $log->tipe }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-{{ $log->nominal > 0 ? 'success' : 'danger' }} font-weight-bold">
                                        {{ $log->nominal > 0 ? '+' : '' }}Rp {{ number_format($log->nominal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($log->saldo_akhir, 0, ',', '.') }}</td>
                                <td>{{ $log->keterangan ?? '-' }}</td>
                                <td>
                                    @if($log->tipe == 'kredit_penjualan')
                                        <small class="text-muted">Sistem</small>
                                    @else
                                        <small class="text-muted">{{ $log->dibuatOleh->name ?? 'Admin' }}</small>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada riwayat mutasi saldo.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card List -->
                <div class="d-md-none">
                    @forelse($mutasi as $log)
                    <div class="card mb-3 border shadow-sm rounded-lg" style="border-radius: 10px;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">{{ $log->created_at ? $log->created_at->format('d M Y H:i') : '-' }}</small>
                                @if($log->tipe == 'kredit_penjualan')
                                    <span class="badge badge-success">Kredit Penjualan</span>
                                @elseif($log->tipe == 'potong_withdraw')
                                    <span class="badge badge-danger">Potong Withdraw</span>
                                @elseif($log->tipe == 'penyesuaian_admin')
                                    <span class="badge badge-warning">Penyesuaian Admin</span>
                                @else
                                    <span class="badge badge-secondary">{{ $log->tipe }}</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small">Nominal:</span>
                                <span class="text-{{ $log->nominal > 0 ? 'success' : 'danger' }} font-weight-bold" style="font-size: 0.95rem;">
                                    {{ $log->nominal > 0 ? '+' : '' }}Rp {{ number_format($log->nominal, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Saldo Akhir:</span>
                                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">Rp {{ number_format($log->saldo_akhir, 0, ',', '.') }}</span>
                            </div>
                            <div class="pt-2 border-top text-muted small">
                                <div><i class="bi bi-info-circle mr-1"></i> {{ $log->keterangan ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">Belum ada riwayat mutasi saldo.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
