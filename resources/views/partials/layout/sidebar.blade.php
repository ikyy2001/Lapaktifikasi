<div class="h-screen fixed left-sidebar flex-none bg-white py-6 px-4 w-[250px] lg:block hidden z-30 border-r border-gray-100">
    @auth
        @if(Auth::user()->role_id == \App\Enums\Role::ADMIN->value)
            @include('partials.layout.sidebar_admin')
        @elseif(Auth::user()->role_id == \App\Enums\Role::SELLER->value)
            @include('partials.layout.sidebar_seller')
        @elseif(Auth::user()->role_id == \App\Enums\Role::CUSTOMER->value)
            @include('partials.layout.sidebar_customer')
        @endif
    @endauth
</div>
