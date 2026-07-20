@extends('layout')

@section('title', 'Detail Saldo & Mutasi: ' . $toko->nama_toko)

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
    <!-- Shop Information & Withdraw Form -->
    <div class="col-md-4">
        <div class="card card-hero">
            <div class="card-header bg-dark text-white p-4">
                <div class="card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h5>Saldo Toko Saat Ini</h5>
                <h3>Rp {{ number_format($toko->saldo, 0, ',', '.') }}</h3>
                <div class="card-description">Toko: {{ $toko->nama_toko }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Withdraw Manual (Potong Saldo)</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <strong>Gagal proses:</strong>
                            <ul class="mb-0 mt-1 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ url('saldo_toko/withdraw/' . $toko->id_toko) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nominal">Nominal Penarikan (Rp)</label>
                        <input type="number" min="1" max="{{ $toko->saldo }}" class="form-control" id="nominal" name="nominal" placeholder="Contoh: 50000" value="{{ old('nominal') }}" required>
                        <small class="form-text text-muted">
                            Maksimal penarikan: Rp {{ number_format($toko->saldo, 0, ',', '.') }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan Penarikan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: Transfer Bank Mandiri Mandra" value="{{ old('keterangan') }}" required>
                    </div>

                    <button type="submit" class="btn btn-danger btn-block" {{ $toko->saldo <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-minus-circle mr-1"></i> Proses Potong Saldo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mutation Ledger History -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Riwayat Mutasi Saldo</h4>
                <a href="{{ url('saldo_toko') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                                <td>
                                    {{ $log->created_at ? $log->created_at->format('d M Y H:i') : '-' }}
                                </td>
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
                                <td>
                                    Rp {{ number_format($log->saldo_akhir, 0, ',', '.') }}
                                </td>
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
                                <td colspan="6" class="text-center text-muted">Belum ada riwayat mutasi saldo untuk toko ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
