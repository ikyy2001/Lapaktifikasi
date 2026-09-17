<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard') - {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}</title>

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ isset($websiteSettings) && $websiteSettings->favicon_path ? asset($websiteSettings->favicon_path) : asset('assets/img/favicon.png') }}">

<!-- Tailwind CSS via CDN (Matching index.html) -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Google Fonts: Poppins -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- General CSS & Icons -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<!-- DataTables CSS if needed -->
<link rel="stylesheet" href="{{ asset('assets/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Separate Modular DashPro CSS Files -->
<link rel="stylesheet" href="{{ asset('css/dashboard-base.css') }}">
<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
<link rel="stylesheet" href="{{ asset('css/pages/tables.css') }}">

@stack('styles')
