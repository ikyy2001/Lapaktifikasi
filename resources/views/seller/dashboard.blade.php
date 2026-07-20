@extends('layout')

@section('title', 'Dashboard Seller: ' . $toko->nama_toko)

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

<!-- Time Range Selector Form -->
<div class="row mb-4">
    <div class="col-12">
        <form action="{{ url('seller/dashboard') }}" method="GET" class="form-inline p-3 bg-white border border-dark rounded" style="border-radius: 12px; gap: 10px;">
            <div class="form-group mb-2 mb-sm-0">
                <label for="filter_range" class="font-weight-bold text-dark mr-2">Rentang Waktu:</label>
                <select name="filter_range" id="filter_range" class="form-control form-control-sm border-dark text-dark" onchange="toggleCustomDates(this.value)" style="border-radius: 6px;">
                    <option value="today" {{ request('filter_range') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="7_days" {{ request('filter_range', 'today') == '7_days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="this_month" {{ request('filter_range') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="custom" {{ request('filter_range') == 'custom' ? 'selected' : '' }}>Rentang Custom</option>
                </select>
            </div>
            
            <div id="custom-date-inputs" class="form-group mb-2 mb-sm-0" style="display: {{ request('filter_range') == 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 10px;">
                <input type="date" class="form-control form-control-sm border-dark text-dark" name="start_date" value="{{ request('start_date') }}" style="border-radius: 6px;">
                <span class="text-muted">s/d</span>
                <input type="date" class="form-control form-control-sm border-dark text-dark" name="end_date" value="{{ request('end_date') }}" style="border-radius: 6px;">
            </div>
            
            <button type="submit" class="btn btn-sm btn-dark mb-2 mb-sm-0" style="border-radius: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                Apply
            </button>
        </form>
    </div>
</div>

<script>
    function toggleCustomDates(val) {
        document.getElementById('custom-date-inputs').style.display = val === 'custom' ? 'flex' : 'none';
    }
</script>

<div class="row">
    <!-- Statistic 1: Order Sukses -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="bi bi-cart-check fa-2x text-white"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ $range === 'today' ? 'Order Sukses Hari Ini' : 'Order Sukses (' . $startDate->format('d/m') . '-' . $endDate->format('d/m') . ')' }}</h4>
                </div>
                <div class="card-body">
                    {{ $total_order }}
                </div>
            </div>
        </div>
    </div>
 
    <!-- Statistic 2: Omzet -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-dark">
                <i class="bi bi-cash fa-2x text-white"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>{{ $range === 'today' ? 'Omzet Hari Ini' : 'Omzet (' . $startDate->format('d/m') . '-' . $endDate->format('d/m') . ')' }}</h4>
                </div>
                <div class="card-body">
                    Rp {{ $total_penjualan }}
                </div>
            </div>
        </div>
    </div>

    <!-- Statistic 3: Saldo Toko -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="bi bi-wallet2 fa-2x text-white"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Saldo Saat Ini</h4>
                </div>
                <div class="card-body">
                    Rp {{ number_format($saldo_toko, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Statistic 4: Total Barang Terjual -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="bi bi-box-seam fa-2x text-white"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Produk Terjual</h4>
                </div>
                <div class="card-body">
                    {{ $total_produk_terjual }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Mutation Logs Section -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Riwayat Mutasi Saldo Terbaru (15 Terakhir)</h4>
                <a href="{{ url('seller/mutasi') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list mr-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-md mb-0">
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
                            @forelse($riwayat_mutasi as $log)
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
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat mutasi saldo.</td>
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
