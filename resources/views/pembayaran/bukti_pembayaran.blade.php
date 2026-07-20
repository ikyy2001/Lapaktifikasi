@extends('layout')

@section('title', 'Bukti Pembayaran')

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

@elseif($error = Session::get('error'))
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
    icon: "error",
    title: "{{ $error }}"
    });
</script>
@endif

    <style>
        .pembayaran-container {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin-top: 10px;
        }

        .pembayaran-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1a1a1a;
            margin-bottom: 24px;
            text-transform: uppercase;
            border-left: 4px solid #000000;
            padding-left: 14px;
        }

        .mono-card {
            background: #ffffff !important;
            border: 1px solid #000000 !important;
            border-radius: 16px !important;
            padding: 35px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            position: relative !important;
            overflow: hidden !important;
            animation: profileFadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        .mono-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: #000000;
        }

        .form-title {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #000000;
            margin-bottom: 28px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 14px;
        }

        /* Table custom styling */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e5e5;
        }

        .mono-table {
            width: 100%;
            margin-bottom: 0 !important;
            background-color: #ffffff;
        }

        .mono-table th {
            background-color: #000000 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.8px !important;
            padding: 16px 20px !important;
            border: none !important;
        }

        .mono-table td {
            padding: 16px 20px !important;
            font-size: 0.88rem !important;
            color: #333333 !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f0f0f0 !important;
        }

        .mono-table tbody tr:nth-of-type(even) {
            background-color: #fafafa;
        }

        .mono-table tbody tr:hover {
            background-color: #f5f5f5;
        }

        /* Minimalist Badges */
        .mono-badge {
            font-size: 0.68rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            padding: 5px 10px !important;
            border-radius: 4px !important;
            display: inline-block !important;
        }

        .mono-badge-pending {
            background-color: #ffffff !important;
            color: #7d6318 !important;
            border: 1px solid #eed27a !important;
        }

        .mono-badge-success {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #000000 !important;
        }

        .mono-badge-failed {
            background-color: #ffffff !important;
            color: #ea5455 !important;
            border: 1px solid #fcd4d4 !important;
        }

        /* Buttons */
        .mono-btn-primary {
            background: #000000 !important;
            color: #ffffff !important;
            border: 1px solid #000000 !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
        }
        
        .mono-btn-primary:hover {
            background: transparent !important;
            color: #000000 !important;
        }

        .mono-btn-danger {
            background: #ffffff !important;
            color: #ea5455 !important;
            border: 1px solid #fcd4d4 !important;
            font-weight: 700 !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            padding: 8px 16px !important;
            border-radius: 6px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
        }

        .mono-btn-danger:hover {
            background: #fff5f5 !important;
        }

        @keyframes profileFadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="pembayaran-container">
        <h4 class="pembayaran-header-title">Bukti Pembayaran</h4>
        
        <div class="row">
            <div class="col-12">
                <div class="mono-card">
                    <h4 class="form-title">Bukti Pembayaran {{ Auth::user()->role_id == 2 ? "Anda" : "" }}</h4>
                    
                    <div class="table-responsive">
                        <table class="table mono-table" id="table-1">
                            <thead>
                                <tr>
                                    @if (Auth::user()->role_id == 2)
                                    <th class="text-center">No</th>
                                    <th class="text-center">Toko</th>
                                    <th class="text-center">Produk</th>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp

                                @if(Auth::user()->role_id == 2)
                                    @foreach($produk as $data)
                                        @foreach($data->produk_beli as $item)
                                        <tr class="text-center">
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                @if($item->toko)
                                                    <strong>{{ $item->toko->nama_toko }}</strong><br/>
                                                    <small class="text-muted"><i class="fas fa-phone mr-1"></i>{{ $item->toko->no_telp }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $data->nama_produk }}</td>
                                            <td><code>{{ $item->order_id }}</code></td>
                                            <td>
                                                @if($item->status == 'pending')
                                                <span class="mono-badge mono-badge-pending">Pending</span>
                                                @elseif($item->status == 'success')
                                                <span class="mono-badge mono-badge-success">Success</span>
                                                @else
                                                <span class="mono-badge mono-badge-failed">{{ ucfirst($item->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    @if($item->status == 'pending' || $item->status == 'deny')
                                                    <a href="{{ route('metode_pembayaran', $item->order_id) }}" class="mono-btn-danger">
                                                        <i class="bi bi-credit-card-fill"></i> Bayar
                                                    </a>
                                                    @elseif($item->status == 'success')
                                                    <a href="{{ route('download_bukti_pembayaran', $item->order_id) }}" class="mono-btn-primary">
                                                        <i class="bi bi-file-pdf-fill"></i> Unduh Bukti
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection