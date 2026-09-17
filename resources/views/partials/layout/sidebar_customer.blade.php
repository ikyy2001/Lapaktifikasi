<div class="flex flex-col justify-between h-full">
    <div>
        <!-- Sidebar Brand Logo Header -->
        <div class="logo flex flex-row items-center justify-start gap-x-2 mb-8 px-2">
            <a href="{{ route('premium.katalog') }}" class="flex items-center gap-x-2">
                @if(isset($websiteSettings) && $websiteSettings->logo_path)
                    <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name }}" class="h-10 max-w-[200px] w-auto object-contain">
                @else
                    <svg id="logo-85" width="36" height="36" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="ccustom" fill-rule="evenodd" clip-rule="evenodd"
                            d="M10 0C15.5228 0 20 4.47715 20 10V0H30C35.5228 0 40 4.47715 40 10C40 15.5228 35.5228 20 30 20C35.5228 20 40 24.4772 40 30C40 32.7423 38.8961 35.2268 37.1085 37.0334L37.0711 37.0711L37.0379 37.1041C35.2309 38.8943 32.7446 40 30 40C27.2741 40 24.8029 38.9093 22.999 37.1405C22.9756 37.1175 22.9522 37.0943 22.9289 37.0711C22.907 37.0492 22.8852 37.0272 22.8635 37.0051C21.0924 35.2009 20 32.728 20 30C20 35.5228 15.5228 40 10 40C4.47715 40 0 35.5228 0 30V20H10C4.47715 20 0 15.5228 0 10C0 4.47715 4.47715 0 10 0ZM18 10C18 14.4183 14.4183 18 10 18V2C14.4183 2 18 5.58172 18 10ZM38 30C38 25.5817 34.4183 22 30 22C25.5817 22 22 25.5817 22 30H38ZM2 22V30C2 34.4183 5.58172 38 10 38C14.4183 38 18 34.4183 18 30V22H2ZM22 18V2L30 2C34.4183 2 38 5.58172 38 10C38 14.4183 34.4183 18 30 18H22Z"
                            fill="#5417D7"></path>
                    </svg>
                    <h2 class="font-bold text-xl text-indigo-950 mb-0 truncate">
                        {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}
                    </h2>
                @endif
            </a>
        </div>

        <div class="flex flex-col gap-y-7">
            <!-- GENERAL SECTION -->
            <div class="flex flex-col gap-y-3">
                <h6 class="sidebar-section-title">GENERAL</h6>
                <ul class="sidebar-menu-list">
                    <li class="sidebar-menu-item @if(Request::path() == 'premium/katalog' || Request::path() == '/') active @endif">
                        <a href="{{ route('premium.katalog') }}">
                            <i class="bi bi-grid text-lg"></i>
                            Katalog Produk
                        </a>
                    </li>
                    <li class="sidebar-menu-item @if(Request::path() == 'daftar_toko' || Request::segment(1) == 'toko') active @endif">
                        <a href="{{ url('/daftar_toko') }}">
                            <i class="bi bi-shop text-lg"></i>
                            Daftar Toko
                        </a>
                    </li>
                </ul>
            </div>

            <!-- AKUN & TRANSAKSI SECTION -->
            <div class="flex flex-col gap-y-3">
                <h6 class="sidebar-section-title">AKUN & TRANSAKSI</h6>
                <ul class="sidebar-menu-list">
                    <li class="sidebar-menu-item @if(Request::path() == 'premium/riwayat') active @endif">
                        <a href="{{ route('premium.riwayat') }}">
                            <i class="bi bi-receipt text-lg"></i>
                            Riwayat Pembelian
                        </a>
                    </li>
                    <li class="sidebar-menu-item @if(Request::path() == 'premium/member') active @endif">
                        <a href="{{ route('premium.member') }}">
                            <i class="bi bi-award text-lg"></i>
                            Level Saya
                        </a>
                    </li>
                    <li class="sidebar-menu-item @if(Request::path() == 'premium/referral') active @endif">
                        <a href="{{ route('premium.referral') }}">
                            <i class="bi bi-people text-lg"></i>
                            Ajak Teman
                        </a>
                    </li>
                    <li class="sidebar-menu-item @if(Request::path() == 'premium/laporan') active @endif">
                        <a href="{{ route('customer.laporan') }}">
                            <i class="bi bi-chat-left-text text-lg"></i>
                            Laporan Masalah
                        </a>
                    </li>
                    <li class="sidebar-menu-item @if(request()->routeIs('news.*')) active @endif">
                        <a href="{{ route('news.index') }}">
                            <i class="bi bi-newspaper text-lg"></i>
                            Berita & Info
                        </a>
                    </li>
                </ul>
            </div>

            <!-- PENGATURAN SECTION -->
            <div class="flex flex-col gap-y-3">
                <h6 class="sidebar-section-title">PENGATURAN</h6>
                <ul class="sidebar-menu-list">
                    <li class="sidebar-menu-item @if(Request::path() == 'ganti_password') active @endif">
                        <a href="{{ url('ganti_password') }}">
                            <i class="bi bi-key text-lg"></i>
                            Ganti Password
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
