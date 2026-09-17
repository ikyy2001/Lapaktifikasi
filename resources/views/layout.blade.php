<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.layout.head')
</head>
<body class="font-['Poppins'] bg-[#f5f5f5] dashpro-body">

    <!-- Mobile Topbar & Drawer Menu -->
    @include('partials.layout.mobile_nav')

    <div class="flex flex-row justify-start min-h-screen">
        <!-- Desktop Left Sidebar -->
        @include('partials.layout.sidebar')

        <!-- Main Workspace Area (Offset on Desktop) -->
        <div class="flex-auto w-full lg:pl-[250px] flex flex-col justify-between">
            <div>
                <!-- Navbar Topbar -->
                @include('partials.layout.topbar')

                <!-- Main Content Body -->
                <main class="px-4 sm:px-7 pt-8 pb-12">
                    <!-- System Alerts -->
                    @include('partials.layout.alerts')

                    <!-- Dynamic Page View Content -->
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Modals & Core Scripts -->
    @include('partials.layout.footer')
</body>
</html>