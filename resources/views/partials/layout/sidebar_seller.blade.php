<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand">
            <a href="{{ url('/seller/dashboard') }}">
                @if(isset($websiteSettings) && $websiteSettings->logo_path)
                    <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name }}" style="max-height: 55px; width: auto; object-fit: contain;">
                @else
                    {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}
                @endif
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/seller/dashboard') }}">
                @if(isset($websiteSettings) && $websiteSettings->favicon_path)
                    <img src="{{ asset($websiteSettings->favicon_path) }}" alt="Logo" style="max-height: 30px;">
                @else
                    {{ isset($websiteSettings) ? substr($websiteSettings->site_name, 0, 2) : 'LA' }}
                @endif
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            <li class="nav-item {{ Request::path() == 'seller/dashboard' ? 'active' : '' }}">
                <a href="{{ url('/seller/dashboard') }}" class="nav-link">
                    <i class="bi bi-speedometer pl-3"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="menu-header">Produk</li>
            <li class="nav-item @if(Request::path() == 'menu_produk' || Request::path() == 'menu_produk/create' || Request::segment(1) == 'menu_produk') active @endif">
                <a href="{{ url('/menu_produk') }}" class="nav-link"><i class="bi bi-cart-fill pl-3"></i> <span>Menu Produk Premium</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'menu_produk_digital' || Request::path() == 'menu_produk_digital/create' || Request::segment(1) == 'menu_produk_digital') active @endif">
                <a href="{{ url('/menu_produk_digital') }}" class="nav-link"><i class="bi bi-file-earmark-code-fill pl-3"></i> <span>Menu Produk Digital</span></a>
            </li>

            <li class="nav-item @if(Request::path() == 'produk_terjual') active @endif">
                <a href="{{ url('produk_terjual') }}" class="nav-link"><i class="bi bi-cart-dash-fill pl-3"></i> <span>Produk Terjual</span></a>
            </li>

            <li class="menu-header">Seller Menu</li>
            <li class="nav-item @if(Request::path() == 'seller/profil') active @endif">
                <a href="{{ url('seller/profil') }}" class="nav-link"><i class="bi bi-shop pl-3"></i> <span>Profil Toko</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'seller/badges') active @endif">
                <a href="{{ route('seller.badges') }}" class="nav-link"><i class="bi bi-patch-check-fill pl-3"></i> <span>Badge Toko</span></a>
            </li>
            <li class="nav-item @if(Request::segment(2) == 'voucher') active @endif">
                <a href="{{ route('seller.voucher.index') }}" class="nav-link"><i class="bi bi-ticket-detailed-fill pl-3"></i> <span>Voucher Toko</span></a>
            </li>

            <li class="menu-header">Premium Layanan</li>
            <li class="nav-item @if(Request::segment(1) == 'premium' && Request::segment(2) == 'inventaris') active @endif">
                <a href="{{ route('premium.inventaris.index') }}" class="nav-link"><i class="bi bi-box-seam pl-3"></i> <span>Inventaris Premium</span></a>
            </li>
            <li class="nav-item @if(Request::segment(2) == 'histori') active @endif">
                <a href="{{ route('premium.histori.index') }}" class="nav-link"><i class="bi bi-journal-text pl-3"></i> <span>Histori Premium</span></a>
            </li>

            <li class="menu-header">Digital Layanan</li>
            <li class="nav-item @if(Request::segment(1) == 'digital' && Request::segment(2) == 'inventaris') active @endif">
                <a href="{{ route('digital.inventaris.index') }}" class="nav-link"><i class="bi bi-cloud-arrow-down-fill pl-3"></i> <span>Inventaris Digital</span></a>
            </li>

            <li class="menu-header">Pengaturan</li>
            <li class="nav-item @if(Request::path() == 'profile_customer' || Request::segment(1) == 'profile_customer') active @endif">
                <a href="{{ url('profile_customer/' . Auth::id()) }}" class="nav-link"><i class="bi bi-person-circle pl-3"></i> <span>Ganti Nama Profil</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'ganti_password') active @endif">
                <a href="{{ url('ganti_password') }}" class="nav-link"><i class="bi bi-key-fill pl-3"></i> <span>Ganti Password</span></a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-danger" data-toggle="modal" data-target="#exampleModal"><i class="bi bi-box-arrow-right pl-3"></i> <span class="text-danger">Keluar</span></a>
            </li>
        </ul>
    </aside>
</div>
