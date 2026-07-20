<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>

    <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    @if(Request::path() == '/' && Auth::user()->role_id == 1 || Request::path() == 'menu_produk' || Request::path() ==
    'produk_terjual' || Request::path() == 'bukti_pembayaran' || Request::path() == 'extract_screenshots' || Request::path() == 'kelola_seller' || Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko' || Request::path() == 'setting_komisi')
    <link rel="stylesheet" href="{{asset('assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
    @endif

    <!-- Sweetalert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{config('midtrans.client_key')}}"></script>
    <style>
        /* Light Monochrome Layout Overrides */
        body {
            background-color: #fafafa !important;
            color: #000000 !important;
        }

        /* Sidebar Styling */
        .main-sidebar {
            background-color: #ffffff !important;
            border-right: 1px solid #e5e5e5 !important;
            box-shadow: none !important;
        }

        .main-sidebar .sidebar-brand {
            border-bottom: 1px solid #e5e5e5 !important;
            background-color: #ffffff !important;
        }

        .main-sidebar .sidebar-brand a {
            color: #000000 !important;
            font-weight: 800 !important;
            letter-spacing: -0.5px !important;
            text-transform: uppercase !important;
        }

        .main-sidebar .sidebar-menu li.menu-header {
            color: #a0a0a0 !important;
            font-weight: 700 !important;
            letter-spacing: 0.8px !important;
            font-size: 10px !important;
            text-transform: uppercase !important;
            padding: 3px 20px !important;
            margin-top: 15px !important;
        }

        .main-sidebar .sidebar-menu li a {
            color: #444444 !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            margin: 4px 12px !important;
            padding: 0 15px !important;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
            font-size: 13px !important;
        }

        .main-sidebar .sidebar-menu li a i {
            color: #444444 !important;
            transition: color 0.2s ease !important;
        }

        .main-sidebar .sidebar-menu li.active a {
            background-color: #000000 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
        }

        .main-sidebar .sidebar-menu li.active a i {
            color: #ffffff !important;
        }

        .main-sidebar .sidebar-menu li a:hover {
            background-color: #f2f2f2 !important;
            color: #000000 !important;
        }

        .main-sidebar .sidebar-menu li a:hover i {
            color: #000000 !important;
        }

        /* Navbar & Header Overrides */
        .navbar-bg {
            background: #000000 !important;
            height: 70px !important;
        }

        .main-navbar {
            background-color: #000000 !important;
        }

        .main-navbar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.2s ease !important;
        }

        .main-navbar .nav-link:hover {
            color: #ffffff !important;
        }

        .main-navbar .nav-link-user {
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        .main-navbar .nav-link-user img {
            border: 2px solid #ffffff !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2) !important;
        }

        /* Dropdown menu overrides */
        .dropdown-menu {
            border: 1px solid #e5e5e5 !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08) !important;
            border-radius: 12px !important;
            padding: 10px !important;
        }

        .dropdown-item {
            color: #333333 !important;
            font-weight: 600 !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
            font-size: 13px !important;
        }

        .dropdown-item i {
            font-size: 14px !important;
            margin-right: 10px !important;
        }

        .dropdown-item:hover {
            background-color: #f2f2f2 !important;
            color: #000000 !important;
        }

        .dropdown-item.text-danger {
            color: #fc544b !important;
        }
        
        .dropdown-item.text-danger:hover {
            background-color: #fff5f5 !important;
            color: #fc544b !important;
        }
        
        /* Modals overrides */
        .modal-content {
            border: 1px solid #000000 !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }
        
        .modal-header {
            border-bottom: 1px solid #e5e5e5 !important;
        }
        
        .modal-footer {
            border-top: 1px solid #e5e5e5 !important;
        }
        
        .modal-title {
            font-weight: 700 !important;
            color: #000000 !important;
            text-transform: uppercase !important;
        }

        /* Central mobile responsiveness overrides */
        @media (max-width: 1024px) {
            body.sidebar-show::before {
                content: "";
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(4px);
                z-index: 889;
                animation: fadeInOverlay 0.3s ease forwards;
            }
            @keyframes fadeInOverlay {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        }

        @media (max-width: 768px) {
            .profile-header-title,
            .pembayaran-header-title,
            .shop-header-title,
            .riwayat-header-title {
                font-size: 1.4rem !important;
                margin-bottom: 18px !important;
            }
        }

        @media (max-width: 576px) {
            .mono-card {
                padding: 20px 15px !important;
            }
            
            /* Responsive card headers with buttons/actions */
            .card-header.d-flex.justify-content-between {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 12px !important;
                height: auto !important;
                padding-top: 15px !important;
                padding-bottom: 15px !important;
            }
            .card-header.d-flex.justify-content-between > div {
                width: 100% !important;
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 8px !important;
                justify-content: flex-start !important;
            }
            .card-header.d-flex.justify-content-between > div > .btn {
                flex: 1 !important;
                margin: 0 !important;
                white-space: nowrap !important;
                font-size: 0.8rem !important;
                padding: 8px 12px !important;
            }

            /* Responsive tables padding */
            .table:not(.table-sm) th, 
            .table:not(.table-sm) td {
                padding: 10px 12px !important;
                font-size: 0.8rem !important;
            }
            
            .profile-grid {
                gap: 20px !important;
            }
            
            .float-right {
                float: none !important;
            }
        }
    </style>
</head>

<body>

    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                    </ul>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                            <img alt="image" src="{{ asset('assets/img/avatar/' . session('profile_picture'))}}"
                                class="rounded-circle mr-1">
                            <div class="d-none d-lg-inline-block">Hi, {{ (Auth::user()->name == "" ?
                                Auth::user()->email : Auth::user()->name ) }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" data-toggle="modal" data-target="#exampleModal"
                                class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">

                    <div class="sidebar-brand">
                        <a href="{{ Auth::user()->role_id == 1 ? url('/dashboard') : (Auth::user()->role_id == 3 ? url('/seller/dashboard') : route('premium.katalog')) }}">Lapaktifikasi</a>
                    </div>

                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{ Auth::user()->role_id == 1 ? url('/dashboard') : (Auth::user()->role_id == 3 ? url('/seller/dashboard') : route('premium.katalog')) }}">LF</a>
                    </div>

                    <ul class="sidebar-menu">
                        <li class="menu-header">{{ (Auth::user()->role_id == 1 || Auth::user()->role_id == 3 ? 'Dashboard' : 'Profile') }}</li>

                        <li class="nav-item {{Request::path() == 'dashboard' || Request::path() == 'seller/dashboard' || Request::path() == 'profile_customer/' . Session::get('id')
                         ? 'active' : ''}}"><a
                                href="{{ Auth::user()->role_id == 1 ? url('/dashboard') : (Auth::user()->role_id == 3 ? url('/seller/dashboard') : url('profile_customer/' . Session::get('id'))) }}"
                                class="nav-link">
                                @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                                <i class="bi bi-speedometer pl-3"></i><span>Dashboard</span></a>
                            @else
                            <i class="bi bi-person-fill pl-3"></i><span>Profile</span></a>
                            @endif

                        </li>

                        <li class="menu-header">Produk</li>

                        @if(Auth::user()->role_id == 2)
                        <li class="nav-item @if(Request::path() == 'daftar_toko' 
                            || Request::segment(1) == 'toko'
                            || Request::segment(1) == 'beli' 
                            || Request::segment(1) == 'metode_pembayaran') active @endif">
                            <a href="{{ url('/daftar_toko') }}" class="nav-link"><i class="bi bi-shop pl-3"></i>
                                <span>Daftar Toko</span></a>
                        </li>
                        @else
                        <li class="nav-item @if(Request::path() == 'menu_produk' 
                            || Request::path() == 'menu_produk/create'
                            || Request::segment(1) == 'menu_produk' ) active @endif">
                            <a href="{{url('/menu_produk')}}" class="nav-link"><i class="bi bi-cart-fill pl-3"></i>
                                <span>Menu Produk</span></a>
                        </li>
                        @endif

                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                        <li class="nav-item @if(Request::path() == 'produk_terjual') active @endif"> <a
                                href="{{url('produk_terjual')}}" class="nav-link"><i
                                    class="bi bi-cart-dash-fill pl-3"></i>
                                <span>Produk Terjual</span></a></li>
                        @endif

                        @if(Auth::user()->role_id == 3)
                        <li class="menu-header">Seller Menu</li>
                        <li class="nav-item @if(Request::path() == 'seller/profil') active @endif">
                            <a href="{{ url('seller/profil') }}" class="nav-link"><i class="bi bi-shop pl-3"></i> <span>Profil Toko</span></a>
                        </li>
                        @endif

                        @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                        <li class="menu-header">Premium Layanan</li>
                        <li class="nav-item @if(Request::segment(2) == 'tipe') active @endif">
                            <a href="{{ route('premium.tipe.index') }}" class="nav-link"><i class="bi bi-tags pl-3"></i> <span>Tipe Layanan</span></a>
                        </li>
                        <li class="nav-item @if(Request::segment(2) == 'varian') active @endif">
                            <a href="{{ route('premium.varian.index') }}" class="nav-link"><i class="bi bi-layers pl-3"></i> <span>Varian Layanan</span></a>
                        </li>
                        <li class="nav-item @if(Request::segment(2) == 'stok') active @endif">
                            <a href="{{ route('premium.stok.index') }}" class="nav-link"><i class="bi bi-key pl-3"></i> <span>Stok Akun</span></a>
                        </li>
                        <li class="nav-item @if(Request::segment(2) == 'histori') active @endif">
                            <a href="{{ route('premium.histori.index') }}" class="nav-link"><i class="bi bi-journal-text pl-3"></i> <span>Histori Penjualan</span></a>
                        </li>
                        @endif

                        @if(Auth::user()->role_id == 1)
                        <li class="menu-header">Admin Menu</li>
                        <li class="nav-item @if(Request::segment(2) == 'laporan-admin') active @endif">
                            <a href="{{ route('admin.laporan') }}" class="nav-link"><i class="bi bi-exclamation-triangle-fill pl-3"></i> <span>Laporan Customer</span></a>
                        </li>
                        <li class="nav-item @if(Request::path() == 'kelola_seller') active @endif">
                            <a href="{{ url('kelola_seller') }}" class="nav-link"><i class="bi bi-shop pl-3"></i> <span>Kelola Seller</span></a>
                        </li>
                        <li class="nav-item @if(Request::path() == 'setting_komisi') active @endif">
                            <a href="{{ url('setting_komisi') }}" class="nav-link"><i class="bi bi-percent pl-3"></i> <span>Setting Komisi</span></a>
                        </li>
                        <li class="nav-item @if(Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko') active @endif">
                            <a href="{{ url('saldo_toko') }}" class="nav-link"><i class="bi bi-wallet2 pl-3"></i> <span>Kelola Saldo Toko</span></a>
                        </li>
                        @endif

                        @if(Auth::user()->role_id == 2)
                        <li class="menu-header">Pembayaran</li>

                        <li class="nav-item @if(Request::path() == 'bukti_pembayaran') active @endif">
                            <a href="{{url('bukti_pembayaran')}}" class="nav-link"><i
                                    class="bi bi-credit-card-fill pl-3"></i>
                                <span>Bukti Pembayaran</span></a>
                        </li>

                        <li class="menu-header">Akun Premium</li>
                        <li class="nav-item @if(Request::path() == 'premium/katalog') active @endif">
                            <a href="{{ route('premium.katalog') }}" class="nav-link"><i class="bi bi-shop pl-3"></i> <span>Katalog Premium</span></a>
                        </li>
                        <li class="nav-item @if(Request::path() == 'premium/riwayat') active @endif">
                            <a href="{{ route('premium.riwayat') }}" class="nav-link"><i class="bi bi-receipt pl-3"></i> <span>Riwayat Premium</span></a>
                        </li>
                        <li class="nav-item @if(Request::path() == 'premium/laporan') active @endif">
                            <a href="{{ route('customer.laporan') }}" class="nav-link"><i class="bi bi-chat-left-text-fill pl-3"></i> <span>Laporan Masalah</span></a>
                        </li>
                        @endif

                        <li class="menu-header">Pengaturan</li>
                        <li class="nav-item @if(Request::path() == 'ganti_password') active @endif">
                            <a href="{{url('ganti_password')}}" class="nav-link"><i
                                    class="bi bi-shield-lock-fill pl-3"></i>
                                <span>Ganti Password</span></a>
                        </li>

                        @if(Auth::user()->role_id == 3)
                        <li class="nav-item @if(Request::path() == 'extract_screenshots') active @endif">
                            <a href="{{url('extract_screenshots')}}" class="nav-link"><i
                                    class="bi bi-file-zip-fill pl-3"></i>
                                <span>Extract Screenshots</span></a>
                        </li>
                        @endif
                    </ul>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section mt-4">
                    <div class="section-body">
                        @auth
                            @if(Auth::user()->role_id == 2)
                                @php
                                    $customer = \App\Models\CustomerModel::where('user_id', Auth::id())->first();
                                    $pendingPembelian = null;
                                    if ($customer) {
                                        $pendingPembelian = \Illuminate\Support\Facades\Cache::remember('pending_pembelian_' . $customer->id, 5, function() use ($customer) {
                                            return \App\Models\Pembelian::where('id_customer', $customer->id)
                                                ->where('status', \App\Enums\PembelianStatus::PENDING)
                                                ->where('reserved_until', '>', now())
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        });
                                    }
                                @endphp
                                @if($pendingPembelian)
                                    <div class="alert alert-warning alert-dismissible fade show mb-4 d-flex align-items-center justify-content-between" role="alert" style="border: 1px solid #eed27a; background-color: #fffdf5; border-radius: 12px; padding: 16px 20px;">
                                        <div style="color: #7d6318; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.15rem;"></i>
                                            <span>Anda memiliki transaksi pembayaran yang belum diselesaikan (Order ID: {{ $pendingPembelian->order_id }}).</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('bukti_pembayaran.status', ['order_id' => $pendingPembelian->order_id]) }}" class="btn btn-sm btn-warning font-weight-bold" style="color: #7d6318; border-radius: 6px; padding: 6px 14px;">
                                                Selesaikan Pembayaran
                                            </a>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: static; padding: 0 0 0 15px; color: #7d6318; opacity: 0.7;">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endauth

                        @yield('content')
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ url('logout') }}" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="{{asset('assets/js/stisla.js')}}"></script>

    @if(Request::path() == '/' && Auth::user()->role_id == 1 || Request::path() == 'menu_produk' || Request::path() ==
    'produk_terjual' || Request::path() == 'bukti_pembayaran' || Request::path() == 'extract_screenshots' || Request::path() == 'kelola_seller' || Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko' || Request::path() == 'setting_komisi')
    <!-- JS Libraies -->
    <script src="{{asset('assets/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/datatables.net-select-bs4/js/select.bootstrap4.min.js')}}"></script>
    @endif

    <!-- Template JS File -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>

    @if(Request::path() == '/' && Auth::user()->role_id == 1 || Request::path() == 'menu_produk' || Request::path() ==
    'produk_terjual' || Request::path() == 'bukti_pembayaran' || Request::path() == 'extract_screenshots' || Request::path() == 'kelola_seller' || Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko' || Request::path() == 'setting_komisi')
    <!-- Page Specific JS File -->
    <script src="{{asset('assets/js/page/modules-datatables.js')}}"></script>
    @endif

    @if(Request::path() == 'ganti_password')
    <script>
        let password_baru = document.querySelector('#password_baru');
        let konfirmasi_password_baru = document.querySelector('#konfirmasi_password_baru');
        let lihat_password = document.querySelector('#lihat_password');

        lihat_password.addEventListener('click', function(){
            if(password_baru.type == 'password' && konfirmasi_password_baru.type == 'password'){
                    password_baru.type = 'text';
                    konfirmasi_password_baru.type = 'text';
            }else{
                password_baru.type = 'password';
                konfirmasi_password_baru.type = 'password';
            }
        })

    </script>

    @elseif(Request::path() == 'extract_screenshots')
    <script>
        let produk_id = document.getElementById('produk_id');
        let nama_produk = document.getElementById('nama_produk');

        function btnPilih(namaProduk, idProduk){
            nama_produk.value = namaProduk;
            produk_id.value = idProduk;
        }
    </script>
    @endif

    @if (isset($snapToken) && isset($pathId) && Request::path() ==
    "metode_pembayaran/{$pathId}")
    <script>
        document.getElementById("pay-button").onclick = function () {
            // SnapToken acquired from previous step
            snap.pay("{{ $snapToken }}", {
                // Optional
                onSuccess: function (result) {
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
                            title: "Pembayaran midtrans telah berhasil pada order id {{ $orderIdProduk }}."
                            });

                    setTimeout(() => {
                        window.location.href = "{{ url('bukti_pembayaran') }}";
                    }, 3000);
                },
                // Optional
                onPending: function (result) {
                    Swal.fire({
                        title: "Pending",
                        text: "Pembayaran Anda pending.",
                        icon: "warning"
                    });
                },
                // Optional
                onError: function (result) {
                    Swal.fire({
                        title: "Gagal",
                        text: "Pembayaran Anda gagal.",
                        icon: "error"
                    });
                },
            });
        };
    </script>
    @endif
    <script>
        $(document).ready(function() {
            $('.modal').appendTo('body');
        });
    </script>
</body>

</html>