<!-- PWA (Progressive Web App) Manifest & Meta Tags -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#4f46e5">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="Lapaktifikasi">

<!-- Apple iOS PWA Meta & Icons -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Lapaktifikasi">
<link rel="apple-touch-icon" href="{{ asset('assets/img/pwa/icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/img/pwa/icon-152x152.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/pwa/icon-192x192.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('assets/img/pwa/icon-192x192.png') }}">
