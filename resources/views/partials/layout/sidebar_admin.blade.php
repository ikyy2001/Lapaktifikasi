<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand">
            <a href="{{ url('/dashboard') }}">
                @if(isset($websiteSettings) && $websiteSettings->logo_path)
                    <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name }}" style="max-height: 55px; width: auto; object-fit: contain;">
                @else
                    {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}
                @endif
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/dashboard') }}">
                @if(isset($websiteSettings) && $websiteSettings->favicon_path)
                    <img src="{{ asset($websiteSettings->favicon_path) }}" alt="Logo" style="max-height: 30px;">
                @else
                    {{ isset($websiteSettings) ? substr($websiteSettings->site_name, 0, 2) : 'LA' }}
                @endif
            </a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            <li class="nav-item {{ Request::path() == 'dashboard' ? 'active' : '' }}">
                <a href="{{ url('/dashboard') }}" class="nav-link">
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

            <li class="menu-header">Admin Menu</li>
            <li class="nav-item @if(Request::segment(2) == 'laporan-admin') active @endif">
                <a href="{{ route('admin.laporan') }}" class="nav-link"><i class="bi bi-exclamation-triangle-fill pl-3"></i> <span>Laporan Customer</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'kelola_customer') active @endif">
                <a href="{{ url('kelola_customer') }}" class="nav-link"><i class="bi bi-people-fill pl-3"></i> <span>Kelola Customer</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'kelola_seller') active @endif">
                <a href="{{ url('kelola_seller') }}" class="nav-link"><i class="bi bi-shop pl-3"></i> <span>Kelola Seller</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'setting_komisi') active @endif">
                <a href="{{ url('setting_komisi') }}" class="nav-link d-flex align-items-center justify-content-between">
                    <div>
                        <i class="bi bi-tools pl-3 mr-1"></i> <span>Setting & Maintenance</span>
                    </div>
                    @php 
                        $isMaintSidebar = \Illuminate\Support\Facades\Cache::remember('is_maintenance_flag', 300, function() { 
                            return \App\Models\SettingKomisi::value('is_maintenance'); 
                        }); 
                    @endphp
                    @if($isMaintSidebar)
                        <span class="badge badge-warning font-weight-bold ml-1" style="font-size: 0.65rem; padding: 3px 6px;">MAINTENANCE</span>
                    @endif
                </a>
            </li>
            <li class="nav-item @if(Request::path() == 'saldo_toko' || Request::segment(1) == 'saldo_toko') active @endif">
                <a href="{{ url('saldo_toko') }}" class="nav-link"><i class="bi bi-wallet2 pl-3"></i> <span>Kelola Saldo Toko</span></a>
            </li>
            <li class="nav-item @if(Request::segment(2) == 'voucher') active @endif">
                <a href="{{ route('admin.voucher.index') }}" class="nav-link"><i class="bi bi-ticket-perforated-fill pl-3"></i> <span>Kelola Voucher</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'mitra_industri' || Request::segment(1) == 'mitra_industri') active @endif">
                <a href="{{ route('admin.mitra_industri') }}" class="nav-link"><i class="bi bi-buildings-fill pl-3"></i> <span>Mitra Industri</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'setting_website' || Request::segment(1) == 'setting_website') active @endif">
                <a href="{{ route('admin.setting_website') }}" class="nav-link"><i class="bi bi-gear-fill pl-3"></i> <span>Setting Website</span></a>
            </li>
            <li class="nav-item @if(Request::path() == 'testimoni' || Request::segment(1) == 'testimoni') active @endif">
                <a href="{{ route('admin.testimoni') }}" class="nav-link"><i class="bi bi-chat-quote-fill pl-3"></i> <span>Kelola Testimoni</span></a>
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
