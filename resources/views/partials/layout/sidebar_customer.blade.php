<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand">
            <a href="{{ route('premium.katalog') }}">
                @if(isset($websiteSettings) && $websiteSettings->logo_path)
                    <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name }}" style="max-height: 55px; width: auto; object-fit: contain;">
                @else
                    {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}
                @endif
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('premium.katalog') }}">
                @if(isset($websiteSettings) && $websiteSettings->favicon_path)
                    <img src="{{ asset($websiteSettings->favicon_path) }}" alt="Logo" style="max-height: 30px;">
                @else
                    {{ isset($websiteSettings) ? substr($websiteSettings->site_name, 0, 2) : 'LA' }}
                @endif
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Profile</li>

            <li class="nav-item {{ Request::path() == 'profile_customer/' . Session::get('id') ? 'active' : '' }}">
                <a href="{{ url('profile_customer/' . Session::get('id')) }}" class="nav-link">
                    <i class="bi bi-person-fill pl-3"></i><span>Profile</span>
                </a>
            </li>

            <li class="menu-header">Produk</li>
            <li class="nav-item @if(Request::path() == 'daftar_toko' || Request::segment(1) == 'toko' || Request::segment(1) == 'beli' || Request::segment(1) == 'metode_pembayaran') active @endif">
                <a href="{{ url('/daftar_toko') }}" class="nav-link"><i class="bi bi-shop pl-3"></i> <span>Daftar Toko</span></a>
            </li>

            <li class="menu-header">Akun Premium</li>
            <li class="nav-item @if(Request::path() == 'premium/katalog') active @endif">
                <a href="{{ route('premium.katalog') }}" class="nav-link"><i class="bi bi-shop pl-3"></i> <span>Katalog Premium</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'premium/member') active @endif">
                <a href="{{ route('premium.member') }}" class="nav-link"><i class="bi bi-award-fill pl-3"></i> <span>Level Saya</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'premium/referral') active @endif">
                <a href="{{ route('premium.referral') }}" class="nav-link"><i class="bi bi-people-fill pl-3"></i> <span>Ajak Teman</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'premium/riwayat') active @endif">
                <a href="{{ route('premium.riwayat') }}" class="nav-link"><i class="bi bi-receipt pl-3"></i> <span>Riwayat Premium</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'premium/laporan') active @endif">
                <a href="{{ route('customer.laporan') }}" class="nav-link"><i class="bi bi-chat-left-text-fill pl-3"></i> <span>Laporan Masalah</span></a>
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
