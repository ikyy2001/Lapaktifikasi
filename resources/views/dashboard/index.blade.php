@extends('layout')

@section('title', 'Admin Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-dashboard.css') }}">
@endpush

@section('content')

    @if($success = Session::get('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            Toast.fire({
                icon: "success",
                title: "{{ $success }}"
            });
        </script>
    @endif

    <x-page-header title="Admin Overview" subtitle="Ringkasan aktivitas penjualan dan transaksi sistem hari ini.">
        <x-slot:actions>
            <a href="{{ url('produk_terjual') }}" class="px-5 py-2.5 rounded-full text-xs font-semibold text-indigo-950 bg-white border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                Lihat Produk Terjual
            </a>
            <a href="{{ url('menu_produk') }}" class="px-5 py-2.5 rounded-full text-xs font-semibold text-white bg-violet-700 hover:bg-violet-800 transition-colors shadow-sm">
                Kelola Produk
            </a>
        </x-slot:actions>
    </x-page-header>

    @if(Auth::user()->role_id == 1)
        <!-- Stat Cards Grid (Matching index.html) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
            <x-stat-card 
                title="Total Order Hari Ini" 
                value="{{ $total_order_hari_ini }}" 
                icon="bi bi-person-fill"
                iconBg="bg-violet-700"
                badgeText="Real-time"
                badgeClass="text-violet-700 bg-violet-50"
            />

            <x-stat-card 
                title="Total Penjualan Hari Ini" 
                value="Rp {{ is_numeric($total_penjualan_hari_ini) ? number_format($total_penjualan_hari_ini, 0, ',', '.') : $total_penjualan_hari_ini }}" 
                icon="bi bi-cash-stack"
                iconBg="bg-emerald-600"
                badgeText="Hari Ini"
                badgeClass="text-emerald-700 bg-emerald-50"
            />

            <x-stat-card 
                title="Total Barang Terjual Hari Ini" 
                value="{{ $total_barang_terjual_hari_ini }}" 
                icon="bi bi-basket-fill"
                iconBg="bg-rose-600"
                badgeText="Unit"
                badgeClass="text-rose-700 bg-rose-50"
            />
        </div>

        <!-- Orders Table Card -->
        <x-card title="Daftar Nama Order Hari Ini" subtitle="Daftar transaksi customer yang masuk pada hari ini">
            <div class="dash-table-wrapper overflow-x-auto">
                <table class="table dash-table table-hover w-full" id="table-1">
                    <thead>
                        <tr>
                            <th class="text-center w-16">No</th>
                            <th>Nama Customer</th>
                            <th>Invoice Order ID</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($nama_order_hari_ini as $order)
                            <tr>
                                <td class="text-center font-medium">{{ $no++ }}</td>
                                <td class="font-semibold text-indigo-950">{{ $order['nama_customer'] }}</td>
                                <td>
                                    <code class="px-2.5 py-1 bg-gray-100 text-violet-700 font-mono text-xs rounded-md">
                                        {{ $order['order_id'] }}
                                    </code>
                                </td>
                                <td>
                                    @if(strtolower($order['status']) == 'success')
                                        <span class="dash-badge dash-badge-success">Success</span>
                                    @elseif(strtolower($order['status']) == 'pending')
                                        <span class="dash-badge dash-badge-warning">Pending</span>
                                    @elseif(strtolower($order['status']) == 'expired')
                                        <span class="dash-badge dash-badge-info">Expired</span>
                                    @elseif(strtolower($order['status']) == 'failed' || strtolower($order['status']) == 'cancelled' || strtolower($order['status']) == 'deny')
                                        <span class="dash-badge dash-badge-danger">Failed</span>
                                    @else
                                        <span class="dash-badge dash-badge-info">{{ $order['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif

@endsection