@extends('layout')

@section('title', 'Histori Penjualan Akun Premium')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Histori Penjualan Akun Premium</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Terjual</th>
                                <th>Order ID</th>
                                <th>Paket Layanan</th>
                                <th>Username / Email Akun</th>
                                <th>Pembeli</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokTerjual as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->tanggal_terjual ? $item->tanggal_terjual->format('d-m-Y H:i') : '-' }}</td>
                                <td><code>{{ $item->pembelian->order_id ?? '-' }}</code></td>
                                <td>{{ $item->varianLayanan->tipeLayanan->produk->nama_produk }} - {{ $item->varianLayanan->tipeLayanan->nama_tipe }} ({{ $item->varianLayanan->nama_varian }})</td>
                                <td><code>{{ $item->email_username }}</code></td>
                                <td>{{ $item->pembelian->customer->user->name ?? '-' }} ({{ $item->pembelian->customer->user->email ?? '-' }})</td>
                                <td>Rp {{ number_format($item->pembelian->harga_saat_beli ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada akun premium yang terjual.</td>
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
