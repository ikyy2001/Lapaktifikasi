<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title') - {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png"
        href="{{ isset($websiteSettings) && $websiteSettings->favicon_path ? asset($websiteSettings->favicon_path) : asset('assets/img/favicon.png') }}">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    @if(
            Request::path() == '/' && Auth::user()->role_id == 1 || Request::path() == 'menu_produk' || Request::path() ==
            'produk_terjual' || Request::path() == 'extract_screenshots' || Request::path() == 'kelola_seller' || Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko' || Request::path() == 'setting_komisi'
        )
        <link rel="stylesheet" href="{{asset('assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}">
    @endif

    <!-- Sweetalert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{config('midtrans.client_key')}}"></script>
    @livewireStyles
    <style>
        /* Light Modern Background with subtle mesh gradient for glassmorphism pop */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -10;
            background-color: #f4f6f9 !important;
            background-image:
                radial-gradient(at 40% 20%, hsla(220, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(280, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(180, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 50%, hsla(320, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(40, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 100%, hsla(120, 100%, 80%, 0.2) 0px, transparent 50%);
            /* Hardware acceleration to prevent mobile lag */
            transform: translateZ(0);
        }

        body {
            background-color: transparent !important;
            color: #2d3748 !important;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sidebar Glassmorphism Styling */
        .main-sidebar {
            background: rgba(255, 255, 255, 0.45) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-right: 1px solid rgba(255, 255, 255, 0.4) !important;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.03) !important;
            z-index: 890;
        }

        .main-sidebar .sidebar-brand {
            border-bottom: 1px solid rgba(255, 255, 255, 0.3) !important;
            background: transparent !important;
        }

        .main-sidebar .sidebar-brand a {
            color: #1a1a1a !important;
            font-weight: 900 !important;
            letter-spacing: -0.3px !important;
            text-transform: uppercase !important;
            background: linear-gradient(135deg, #000000, #434343);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .main-sidebar .sidebar-menu li.menu-header {
            color: rgba(0, 0, 0, 0.45) !important;
            font-weight: 800 !important;
            letter-spacing: 1px !important;
            font-size: 10px !important;
            text-transform: uppercase !important;
            padding: 5px 20px !important;
            margin-top: 15px !important;
        }

        .main-sidebar .sidebar-menu li a {
            color: #4a5568 !important;
            font-weight: 600 !important;
            border-radius: 12px !important;
            margin: 4px 14px !important;
            padding: 0 15px !important;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            font-size: 13.5px !important;
            background: transparent !important;
            border: 1px solid transparent !important;
        }

        .main-sidebar .sidebar-menu li a i {
            color: rgba(0, 0, 0, 0.5) !important;
            transition: all 0.3s ease !important;
            font-size: 16px !important;
        }

        .main-sidebar .sidebar-menu li.active a {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.5)) !important;
            border: 1px solid rgba(255, 255, 255, 0.7) !important;
            color: #000000 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05), inset 0 0 0 1px rgba(255, 255, 255, 0.2) !important;
        }

        .main-sidebar .sidebar-menu li.active a i {
            color: #000000 !important;
        }

        .main-sidebar .sidebar-menu li a:hover {
            background: rgba(255, 255, 255, 0.5) !important;
            color: #000000 !important;
            transform: translateX(4px);
            border-color: rgba(255, 255, 255, 0.4) !important;
        }

        .main-sidebar .sidebar-menu li a:hover i {
            color: #000000 !important;
            transform: scale(1.1);
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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2) !important;
        }

        /* Dropdown menu overrides */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
            border-radius: 16px !important;
            padding: 10px !important;
        }

        .dropdown-item {
            color: #333333 !important;
            font-weight: 600 !important;
            padding: 10px 20px !important;
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
            font-size: 13.5px !important;
            background: transparent !important;
        }

        .dropdown-item i {
            font-size: 14px !important;
            margin-right: 10px !important;
        }

        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.04) !important;
            color: #000000 !important;
            transform: translateX(4px);
        }

        .dropdown-item.text-danger {
            color: #fc544b !important;
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(252, 84, 75, 0.1) !important;
            color: #fc544b !important;
        }

        /* Glassmorphism for Cards (Dashboard Content) */
        .card,
        .mono-card {
            background: rgba(255, 255, 255, 0.6) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 18px !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.03) !important;
            animation: cardEntrance 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
            transform: translateZ(0);
        }

        .card .card-header,
        .mono-card .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
            background-color: transparent !important;
            padding-top: 20px !important;
            padding-bottom: 20px !important;
        }

        .card .card-footer,
        .mono-card .card-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.04) !important;
            background-color: transparent !important;
        }

        /* Glassmorphism for Form Controls & Inputs */
        .form-control,
        .custom-select,
        .selectric {
            background: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            border-radius: 12px !important;
            color: #2d3748 !important;
            backdrop-filter: blur(5px);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        .form-control:focus,
        .custom-select:focus {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(103, 119, 239, 0.5) !important;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.15) !important;
        }

        /* Input Group Addons (Icons in forms) */
        .input-group-text {
            background: rgba(255, 255, 255, 0.4) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            border-right: none !important;
            border-radius: 12px 0 0 12px !important;
        }

        .input-group .form-control {
            border-left: none !important;
        }

        /* Buttons Enhancement */
        .btn {
            border-radius: 10px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            border: none !important;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
        }

        /* Tables Enhancements */
        .table {
            color: #2d3748 !important;
        }

        .table th,
        .table td {
            border-color: rgba(0, 0, 0, 0.04) !important;
        }

        .table thead th {
            background-color: rgba(0, 0, 0, 0.02) !important;
            color: #4a5568 !important;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05) !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.015) !important;
        }

        /* Modals overrides */
        .modal-content {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(25px) saturate(200%);
            -webkit-backdrop-filter: blur(25px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        .modal-title {
            font-weight: 800 !important;
            color: #1a1a1a !important;
            text-transform: uppercase !important;
        }

        /* Universal Mobile & Touch Screen Responsiveness */
        .table-responsive {
            width: 100% !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
        }

        .table-responsive>table {
            width: 100% !important;
            margin-bottom: 0;
        }

        .img-fluid,
        img {
            max-width: 100%;
            height: auto;
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
                pointer-events: none;
                /* Allow clicks to pass through to Stisla's backdrop */
            }

            @keyframes fadeInOverlay {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding-left: 15px !important;
                padding-right: 15px !important;
                padding-top: 80px !important;
            }

            .modal-dialog {
                max-width: 96% !important;
                margin: 0.5rem auto !important;
            }

            .card-header h4 {
                font-size: 1.1rem !important;
            }

            .profile-header-title,
            .pembayaran-header-title,
            .shop-header-title,
            .riwayat-header-title {
                font-size: 1.4rem !important;
                margin-bottom: 18px !important;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            .card-body {
                padding: 15px 10px !important;
            }

            .mono-card {
                padding: 20px 12px !important;
            }

            .nav-pills {
                flex-wrap: wrap !important;
                gap: 6px !important;
            }

            .nav-pills .nav-link {
                padding: 8px 12px !important;
                font-size: 0.8rem !important;
            }

            .row {
                margin-left: -5px !important;
                margin-right: -5px !important;
            }

            [class*="col-"] {
                padding-left: 5px !important;
                padding-right: 5px !important;
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

            .card-header.d-flex.justify-content-between>div {
                width: 100% !important;
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 8px !important;
                justify-content: flex-start !important;
            }

            .card-header.d-flex.justify-content-between>div>.btn {
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
                            <div class="d-none d-lg-inline-block">Hi, {{ Auth::check() ? (Auth::user()->name == "" ?
    Auth::user()->email : Auth::user()->name) : 'Guest' }}</div>
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

            @auth
                @if(Auth::user()->role_id == \App\Enums\Role::ADMIN->value)
                    @include('partials.layout.sidebar_admin')
                @elseif(Auth::user()->role_id == \App\Enums\Role::SELLER->value)
                    @include('partials.layout.sidebar_seller')
                @elseif(Auth::user()->role_id == \App\Enums\Role::CUSTOMER->value)
                    @include('partials.layout.sidebar_customer')
                @endif
            @endauth

            <!-- Main Content -->
            <div class="main-content">
                <section class="section mt-4">
                    <div class="section-body">
                        @auth
                            @if(Auth::user()->role_id == 1)
                                @php
                                    $isMaintActive = \Illuminate\Support\Facades\Cache::remember('is_maintenance_flag', 300, function () {
                                        return \App\Models\SettingKomisi::value('is_maintenance');
                                    });
                                @endphp
                                @if($isMaintActive)
                                    <div class="alert alert-warning border border-warning shadow-sm mb-4 d-flex align-items-center justify-content-between"
                                        role="alert" style="border-radius: 12px; background-color: #fff9e6;">
                                        <div class="d-flex align-items-center" style="color: #856404; font-weight: 600;">
                                            <i class="bi bi-tools mr-2" style="font-size: 1.2rem;"></i>
                                            <span><strong>MODE MAINTENANCE AKTIF:</strong> Seller dan Customer saat ini diblokir
                                                dari akses dashboard.</span>
                                        </div>
                                        <a href="{{ url('setting_komisi') }}" class="btn btn-sm btn-warning font-weight-bold ml-3"
                                            style="white-space: nowrap;">
                                            Kelola Status
                                        </a>
                                    </div>
                                @endif
                            @endif

                            @if(Auth::user()->role_id == 2)
                                @php
                                    $customer = \Illuminate\Support\Facades\Cache::remember('customer_user_' . Auth::id(), 300, function () {
                                        return \App\Models\CustomerModel::where('user_id', Auth::id())->first();
                                    });
                                    $pendingPembelian = null;
                                    if ($customer) {
                                        $pendingPembelian = \Illuminate\Support\Facades\Cache::remember('pending_pembelian_' . $customer->id, 5, function () use ($customer) {
                                            return \App\Models\Pembelian::where('id_customer', $customer->id)
                                                ->where('status', \App\Enums\PembelianStatus::PENDING)
                                                ->where('reserved_until', '>', now())
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        });
                                    }
                                @endphp
                                @if($pendingPembelian)
                                    <div class="alert alert-warning alert-dismissible fade show mb-4 d-flex align-items-center justify-content-between"
                                        role="alert"
                                        style="border: 1px solid #eed27a; background-color: #fffdf5; border-radius: 12px; padding: 16px 20px;">
                                        <div
                                            style="color: #7d6318; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                                            <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.15rem;"></i>
                                            <span>Anda memiliki transaksi pembayaran yang belum diselesaikan (Order ID:
                                                {{ $pendingPembelian->order_id }}).</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('bukti_pembayaran.status', ['order_id' => $pendingPembelian->order_id]) }}"
                                                class="btn btn-sm btn-warning font-weight-bold"
                                                style="color: #7d6318; border-radius: 6px; padding: 6px 14px;">
                                                Selesaikan Pembayaran
                                            </a>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                                                style="position: static; padding: 0 0 0 15px; color: #7d6318; opacity: 0.7;">
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

    @if(
            Request::path() == '/' && Auth::user()->role_id == 1 || Request::path() == 'menu_produk' || Request::path() ==
            'produk_terjual' || Request::path() == 'extract_screenshots' || Request::path() == 'kelola_seller' || Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko' || Request::path() == 'setting_komisi'
        )
        <!-- JS Libraies -->
        <script src="{{asset('assets/datatables/media/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/datatables.net-select-bs4/js/select.bootstrap4.min.js')}}"></script>
    @endif

    <!-- Template JS File -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>

    @if(
            Request::path() == '/' && Auth::user()->role_id == 1 || Request::path() == 'menu_produk' || Request::path() ==
            'produk_terjual' || Request::path() == 'extract_screenshots' || Request::path() == 'kelola_seller' || Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko' || Request::path() == 'setting_komisi'
        )
        <!-- Page Specific JS File -->
        <script src="{{asset('assets/js/page/modules-datatables.js')}}"></script>
    @endif

    @if(Request::path() == 'ganti_password')
        <script>
            let password_baru = document.querySelector('#password_baru');
            let konfirmasi_password_baru = document.querySelector('#konfirmasi_password_baru');
            let lihat_password = document.querySelector('#lihat_password');

            lihat_password.addEventListener('click', function () {
                if (password_baru.type == 'password' && konfirmasi_password_baru.type == 'password') {
                    password_baru.type = 'text';
                    konfirmasi_password_baru.type = 'text';
                } else {
                    password_baru.type = 'password';
                    konfirmasi_password_baru.type = 'password';
                }
            })

        </script>

    @elseif(Request::path() == 'extract_screenshots')
        <script>
            let produk_id = document.getElementById('produk_id');
            let nama_produk = document.getElementById('nama_produk');

            function btnPilih(namaProduk, idProduk) {
                nama_produk.value = namaProduk;
                produk_id.value = idProduk;
            }
        </script>
    @endif


    <script>
        $(document).ready(function () {
            $('.modal').appendTo('body');
        });
    </script>
    @livewireScripts
</body>

</html>