<!-- Mobile Top Header Bar -->
<div class="flex flex-row justify-between items-center lg:hidden bg-white px-4 py-3 border-b border-gray-100 sticky top-0 z-40 shadow-sm">
    <div class="logo flex flex-row items-center gap-x-2">
        <a href="{{ url('/') }}" class="flex items-center gap-x-2">
            @if(isset($websiteSettings) && $websiteSettings->logo_path)
                <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name }}" class="h-9 max-w-[170px] w-auto object-contain">
            @else
                <svg id="logo-85" width="32" height="32" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="ccustom" fill-rule="evenodd" clip-rule="evenodd"
                        d="M10 0C15.5228 0 20 4.47715 20 10V0H30C35.5228 0 40 4.47715 40 10C40 15.5228 35.5228 20 30 20C35.5228 20 40 24.4772 40 30C40 32.7423 38.8961 35.2268 37.1085 37.0334L37.0711 37.0711L37.0379 37.1041C35.2309 38.8943 32.7446 40 30 40C27.2741 40 24.8029 38.9093 22.999 37.1405C22.9756 37.1175 22.9522 37.0943 22.9289 37.0711C22.907 37.0492 22.8852 37.0272 22.8635 37.0051C21.0924 35.2009 20 32.728 20 30C20 35.5228 15.5228 40 10 40C4.47715 40 0 35.5228 0 30V20H10C4.47715 20 0 15.5228 0 10C0 4.47715 4.47715 0 10 0ZM18 10C18 14.4183 14.4183 18 10 18V2C14.4183 2 18 5.58172 18 10ZM38 30C38 25.5817 34.4183 22 30 22C25.5817 22 22 25.5817 22 30H38ZM2 22V30C2 34.4183 5.58172 38 10 38C14.4183 38 18 34.4183 18 30V22H2ZM22 18V2L30 2C34.4183 2 38 5.58172 38 10C38 14.4183 34.4183 18 30 18H22Z"
                        fill="#5417D7"></path>
                </svg>
                <h2 class="font-bold text-lg text-indigo-950 mb-0">
                    {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}
                </h2>
            @endif
        </a>
    </div>

    <!-- Modern Animated Hamburger Toggle Button -->
    <button type="button" id="mobile-menu-toggle" aria-label="Toggle Navigation Menu"
        class="flex items-center justify-center p-2 rounded-xl bg-gray-100 hover:bg-violet-50 text-indigo-950 hover:text-violet-700 transition-all border border-gray-200 focus:outline-none">
        <i class="bi bi-list text-2xl leading-none"></i>
    </button>
</div>

<!-- Backdrop Overlay for Mobile Drawer -->
<div id="mobile-drawer-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300 lg:hidden"></div>

<!-- Slide-in Mobile Drawer Navigation -->
<div id="mobile-drawer" class="fixed inset-y-0 left-0 w-[280px] bg-white z-50 transform -translate-x-full transition-transform duration-300 ease-in-out shadow-2xl flex flex-col justify-between p-5 overflow-y-auto lg:hidden">
    <div>
        <!-- Drawer Top Bar -->
        <div class="flex items-center justify-between pb-5 mb-5 border-b border-gray-100">
            <div class="logo flex items-center gap-x-2">
                @if(isset($websiteSettings) && $websiteSettings->logo_path)
                    <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name }}" class="h-8 max-w-[160px] w-auto object-contain">
                @else
                    <h2 class="font-bold text-lg text-indigo-950 mb-0">
                        {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}
                    </h2>
                @endif
            </div>
            <button type="button" id="mobile-drawer-close" class="p-1.5 rounded-full text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors focus:outline-none">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <!-- Role Specific Menu Links inside Mobile Drawer -->
        <div class="mobile-drawer-content">
            @auth
                @if(Auth::user()->role_id == \App\Enums\Role::ADMIN->value)
                    @include('partials.layout.sidebar_admin')
                @elseif(Auth::user()->role_id == \App\Enums\Role::SELLER->value)
                    @include('partials.layout.sidebar_seller')
                @elseif(Auth::user()->role_id == \App\Enums\Role::CUSTOMER->value)
                    @include('partials.layout.sidebar_customer')
                @endif
            @else
                @include('partials.layout.sidebar_customer')
            @endauth
        </div>
    </div>

    <!-- Mobile Drawer Footer Info -->
    <div class="pt-4 border-t border-gray-100 text-center">
        <p class="text-[11px] text-gray-400 mb-0">
            &copy; {{ date('Y') }} {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}. All rights reserved.
        </p>
    </div>
</div>
