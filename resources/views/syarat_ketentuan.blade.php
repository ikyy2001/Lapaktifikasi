<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @section('title', 'Syarat & Ketentuan - ' . (isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi'))
    @section('meta_title', 'Syarat & Ketentuan Layanan & Garansi - ' . (isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi'))
    @section('meta_description', 'Syarat dan Ketentuan penggunaan platform ' . (isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi') . '. Pelajari hak, kewajiban, ketentuan garansi akun premium, dan sistem pengiriman file digital kami.')
    @section('meta_keywords', 'syarat dan ketentuan, terms of service, ketentuan garansi akun, lapaktifikasi, aturan transaksi digital')
    @include('partials.seo')
    
    <link rel="icon" type="image/png" href="{{ isset($websiteSettings) && $websiteSettings->favicon_path ? asset($websiteSettings->favicon_path) : asset('assets/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #000000;
            --primary-2: #1a1a1a;
            --accent: #000000;
            --accent-2: #333333;
            --dark-bg: #ffffff;
            --dark-2: #fafafa;
            --glass-bg: rgba(0, 0, 0, 0.02);
            --glass-border: rgba(0, 0, 0, 0.08);
            --glass-hover: rgba(0, 0, 0, 0.04);
            --text-main: #111111;
            --text-muted: #555555;
            --text-dim: #888888;
            --white: #ffffff;
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --shadow-subtle: 0 4px 20px rgba(0, 0, 0, 0.03);
            --shadow-float: 0 20px 40px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark-bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.7;
            position: relative;
        }

        /* Ambient Backgrounds */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(0, 0, 0, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        .bg-blobs {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            animation: float 20s infinite alternate;
        }

        .blob-1 { width: 500px; height: 500px; background: #000000; top: -100px; right: -100px; }
        .blob-2 { width: 400px; height: 400px; background: #333333; bottom: 20%; left: -100px; animation-duration: 25s; }
        .blob-3 { width: 350px; height: 350px; background: #555555; top: 40%; right: 20%; animation-duration: 18s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 40px) scale(1.08); }
        }

        .container-custom {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 5%;
            position: relative;
            z-index: 1;
        }

        /* NAVBAR */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 16px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.88);
            border-bottom: 1px solid rgba(0, 0, 0, 0.07);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            transition: var(--transition);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none !important;
            transition: var(--transition);
        }

        .nav-logo:hover {
            opacity: 0.9;
        }

        .nav-logo-icon {
            width: 42px;
            height: 42px;
            background: #000000;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.25rem;
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.45rem;
            color: #000000;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 26px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none !important;
            font-size: 0.92rem;
            font-weight: 600;
            transition: var(--transition);
            padding: 6px 12px;
            border-radius: 8px;
        }

        .nav-links a:hover, .nav-links a.active {
            color: #000000;
            background: rgba(0, 0, 0, 0.04);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-nav-login {
            color: #000000;
            padding: 8px 18px;
            border-radius: 10px;
            text-decoration: none !important;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.12);
        }

        .btn-nav-login:hover {
            background: rgba(0, 0, 0, 0.04);
            border-color: #000000;
            color: #000000;
        }

        .btn-nav-signup {
            background: #000000;
            color: #ffffff !important;
            padding: 9px 20px;
            border-radius: 10px;
            text-decoration: none !important;
            font-weight: 700;
            font-size: 0.9rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .btn-nav-signup:hover {
            background: #222222;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .nav-toggle {
            display: none;
            background: transparent;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 1.3rem;
            color: #000000;
            cursor: pointer;
        }

        /* Mobile Menu Drawer */
        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -320px;
            width: 300px;
            height: 100vh;
            background: #ffffff;
            z-index: 1050;
            padding: 30px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.15);
            transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow-y: auto;
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-menu-close {
            align-self: flex-end;
            background: transparent;
            border: none;
            font-size: 1.4rem;
            color: #555555;
            cursor: pointer;
            padding: 4px;
        }

        .mobile-nav-links {
            list-style: none;
            padding: 0;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .mobile-nav-links a {
            color: #111111;
            text-decoration: none !important;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 10px;
            display: block;
            transition: background 0.2s;
        }

        .mobile-nav-links a:hover {
            background: #f5f5f5;
        }

        .mobile-nav-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }

        /* PAGE HEADER */
        .page-header {
            padding: 150px 0 50px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .page-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 0, 0, 0.05);
            color: #000000;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 7px 18px;
            border-radius: 50px;
            margin-bottom: 16px;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .page-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.2rem, 4.5vw, 3.2rem);
            font-weight: 800;
            color: #000000;
            letter-spacing: -1px;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 650px;
            margin: 0 auto;
        }

        /* CONTENT */
        .legal-content {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.09);
            border-radius: var(--radius-lg);
            padding: 50px 60px;
            margin-bottom: 90px;
            box-shadow: var(--shadow-float);
            position: relative;
            z-index: 1;
        }

        .legal-section {
            margin-bottom: 38px;
        }

        .legal-section:last-child {
            margin-bottom: 0;
        }

        .legal-section h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #000000;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.3px;
        }

        .legal-section p {
            color: var(--text-muted);
            margin-bottom: 14px;
            font-size: 0.98rem;
            line-height: 1.75;
        }

        .legal-section ul {
            list-style: none;
            padding: 0;
            margin: 0 0 14px 0;
        }

        .legal-section li {
            position: relative;
            padding-left: 26px;
            margin-bottom: 10px;
            color: var(--text-muted);
            font-size: 0.96rem;
            line-height: 1.65;
        }

        .legal-section li::before {
            content: "\F287";
            font-family: "bootstrap-icons";
            position: absolute;
            left: 0;
            top: 2px;
            color: #000000;
            font-weight: 800;
            font-size: 0.85rem;
        }

        /* FOOTER */
        footer {
            background: #fafafa;
            border-top: 1px solid #e5e5e5;
            padding: 70px 0 35px;
            font-size: 0.95rem;
            position: relative;
            z-index: 1;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
        }

        .footer-top-row {
            display: grid;
            grid-template-columns: 1.4fr 0.9fr 0.9fr 1fr 1.3fr;
            gap: 36px;
            margin-bottom: 50px;
        }

        .footer-brand p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 14px;
            line-height: 1.6;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .social-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #dddddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000;
            text-decoration: none !important;
            transition: var(--transition);
            background: #ffffff;
        }

        .social-btn:hover {
            background: #000000;
            color: #ffffff;
            border-color: #000000;
            transform: translateY(-2px);
        }

        .footer-location-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-address {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: var(--text-muted);
            font-size: 0.84rem;
            line-height: 1.5;
            margin: 0;
        }

        .footer-address i {
            color: #000000;
            font-size: 1.05rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .footer-map-frame {
            width: 100%;
            height: 110px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e5e5;
            background: #f8fafc;
        }

        .footer-map-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #000000;
            text-decoration: none !important;
            transition: var(--transition);
        }

        .footer-map-action:hover {
            color: #555555;
        }

        .footer-col h5 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
            margin-bottom: 18px;
            color: #000000;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col li {
            margin-bottom: 11px;
        }

        .footer-col a {
            color: var(--text-muted);
            text-decoration: none !important;
            transition: color 0.2s;
            font-size: 0.9rem;
        }

        .footer-col a:hover {
            color: #000000;
        }

        .footer-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e5e5;
            padding-top: 28px;
            font-size: 0.86rem;
            color: var(--text-dim);
        }

        .footer-bottom-badges {
            display: flex;
            gap: 18px;
        }

        .badge-secure {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #333333;
            font-weight: 600;
        }

        .badge-secure i {
            color: #000000;
        }

        /* LOGIN MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-box {
            background: #ffffff;
            border-radius: 20px;
            max-width: 440px;
            width: 100%;
            padding: 36px 32px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.08);
            animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: transparent;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: #888888;
        }

        .modal-logo {
            width: 48px;
            height: 48px;
            background: #000000;
            color: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0 auto 12px;
        }

        .modal-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.45rem;
            color: #000000;
        }

        .modal-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .login-tabs {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
            margin: 20px 0;
            gap: 4px;
        }

        .login-tab {
            flex: 1;
            padding: 8px 12px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s;
        }

        .login-tab.active {
            background: #ffffff;
            color: #000000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #222222;
        }

        .form-input-wrap {
            position: relative;
        }

        .form-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888888;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.92rem;
            outline: none;
            transition: border 0.2s;
        }

        .form-input:focus {
            border-color: #000000;
        }

        .form-check-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 18px;
            cursor: pointer;
        }

        .btn-modal-submit {
            width: 100%;
            padding: 12px;
            background: #000000;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-modal-submit:hover {
            background: #222222;
        }

        @media (max-width: 992px) {
            .nav-links { display: none; }
            .nav-toggle { display: block; }
            .footer-top-row { grid-template-columns: 1fr 1fr; gap: 32px; }
            .footer-brand, .footer-location-col { grid-column: span 2; }
            .legal-content { padding: 36px 28px; }
        }

        @media (max-width: 600px) {
            .footer-top-row { grid-template-columns: 1fr; }
            .footer-brand, .footer-location-col { grid-column: span 1; }
            .footer-bottom-row { flex-direction: column; gap: 14px; text-align: center; }
            .legal-content { padding: 30px 20px; }
        }
    </style>
</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>
    <div class="bg-grid"></div>

    <!-- NAVBAR -->
    <nav id="navbar">
        <a href="{{ url('/') }}" class="nav-logo">
            @if(isset($websiteSettings) && $websiteSettings->logo_path)
                <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name ?? 'Lapaktifikasi' }}"
                    style="max-height: 52px; margin-right: 8px; width: auto; object-fit: contain;">
            @else
                <div class="nav-logo-icon">L</div>
                <span class="nav-logo-text">{{ isset($websiteSettings) ? $websiteSettings->site_name : 'LAPAKTIFIKASI' }}</span>
            @endif
        </a>
        <ul class="nav-links">
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li><a href="{{ route('premium.katalog') }}">Katalog Produk</a></li>
            <li><a href="{{ url('/') }}#fitur">Kelebihan</a></li>
            <li><a href="{{ url('/') }}#visimisi">Visi &amp; Misi</a></li>
            <li><a href="{{ url('/') }}#produk">Produk</a></li>
            <li><a href="{{ url('/') }}#faq">FAQ</a></li>
            <li><a href="{{ route('daftar.seller') }}">Jadi Seller</a></li>
            <li><a href="{{ route('join.partner') }}">Join Partner</a></li>
            @auth
                @if(Auth::user()->role_id == 2)
                    <li><a href="{{ route('premium.riwayat') }}">Riwayat</a></li>
                @else
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                @endif
            @endauth
        </ul>
        <div class="nav-actions">
            @auth
                @if(Auth::user()->role_id == 1)
                    <a href="{{ url('/dashboard') }}" class="btn-nav-signup"><i class="bi bi-speedometer2"></i> Dashboard</a>
                @elseif(Auth::user()->role_id == 3)
                    <a href="{{ url('/seller/dashboard') }}" class="btn-nav-signup"><i class="bi bi-shop"></i> Toko Saya</a>
                @else
                    <a href="{{ route('premium.katalog') }}" class="btn-nav-signup"><i class="bi bi-cart3"></i> Belanja</a>
                @endif
            @else
                <a href="{{ url('/login') }}" class="btn-nav-login" onclick="openModal(); return false;">Masuk</a>
                <a href="{{ url('/pendaftaran') }}" class="btn-nav-signup">Daftar Gratis</a>
            @endauth
        </div>
        <button class="nav-toggle" id="navToggle" onclick="toggleMobileMenu()" aria-label="Toggle Navigation">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <!-- MOBILE MENU DRAWER -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="closeMobileMenu()"></div>
    <div class="mobile-menu" id="mobileMenu">
        <button class="mobile-menu-close" onclick="closeMobileMenu()" aria-label="Close Navigation">
            <i class="bi bi-x-lg"></i>
        </button>
        <ul class="mobile-nav-links">
            <li><a href="{{ url('/') }}" onclick="closeMobileMenu()">Beranda</a></li>
            <li><a href="{{ route('premium.katalog') }}" onclick="closeMobileMenu()">Katalog Produk</a></li>
            <li><a href="{{ url('/') }}#fitur" onclick="closeMobileMenu()">Kelebihan</a></li>
            <li><a href="{{ url('/') }}#visimisi" onclick="closeMobileMenu()">Visi &amp; Misi</a></li>
            <li><a href="{{ url('/') }}#produk" onclick="closeMobileMenu()">Produk</a></li>
            <li><a href="{{ url('/') }}#faq" onclick="closeMobileMenu()">FAQ</a></li>
            <li><a href="{{ route('daftar.seller') }}" onclick="closeMobileMenu()">Jadi Seller</a></li>
            <li><a href="{{ route('join.partner') }}" onclick="closeMobileMenu()">Join Partner</a></li>
            @auth
                @if(Auth::user()->role_id == 2)
                    <li><a href="{{ route('premium.riwayat') }}" onclick="closeMobileMenu()">Riwayat</a></li>
                @else
                    <li><a href="{{ url('/dashboard') }}" onclick="closeMobileMenu()">Dashboard</a></li>
                @endif
            @endauth
        </ul>
        <div class="mobile-nav-actions">
            @auth
                @if(Auth::user()->role_id == 1)
                    <a href="{{ url('/dashboard') }}" class="btn-nav-signup" onclick="closeMobileMenu()"><i class="bi bi-speedometer2"></i> Dashboard</a>
                @elseif(Auth::user()->role_id == 3)
                    <a href="{{ url('/seller/dashboard') }}" class="btn-nav-signup" onclick="closeMobileMenu()"><i class="bi bi-shop"></i> Toko Saya</a>
                @else
                    <a href="{{ route('premium.katalog') }}" class="btn-nav-signup" onclick="closeMobileMenu()"><i class="bi bi-cart3"></i> Belanja</a>
                @endif
            @else
                <a href="{{ url('/login') }}" class="btn-nav-login" onclick="closeMobileMenu(); openModal(); return false;">Masuk</a>
                <a href="{{ url('/pendaftaran') }}" class="btn-nav-signup" onclick="closeMobileMenu()">Daftar Gratis</a>
            @endauth
        </div>
    </div>

    <!-- HEADER -->
    <header class="page-header">
        <div class="container-custom">
            <div class="page-badge"><i class="bi bi-file-earmark-text-fill"></i> Syarat & Ketentuan</div>
            <h1 class="page-title">Syarat &amp; Ketentuan Layanan</h1>
            <p class="page-subtitle">Ketentuan penggunaan platform dan jaminan garansi resmi di {{ isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi' }}</p>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="container-custom">
        <div class="legal-content">
            <div class="legal-section">
                <h3>1. Ketentuan Umum</h3>
                <p>Dengan mendaftar, mengakses, atau membeli layanan melalui platform <strong>{{ isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi' }}</strong>, Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui seluruh Syarat dan Ketentuan yang berlaku.</p>
            </div>

            <div class="legal-section">
                <h3>2. Akun Pengguna &amp; Tanggung Jawab</h3>
                <ul>
                    <li>Pengguna bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi dan informasi akun pribadinya.</li>
                    <li>Pengguna wajib memberikan informasi yang valid, termasuk nomor WhatsApp dan email aktif untuk keperluan pengiriman kredensial akun digital.</li>
                    <li>Dilarang keras menyalahgunakan akun digital yang telah dibeli untuk aktivitas ilegal, spamming, atau melanggar hak cipta penyedia layanan resmi.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h3>3. Pemesanan &amp; Pembayaran</h3>
                <ul>
                    <li>Setiap pesanan yang dibuat memiliki batas waktu pembayaran (expiry time). Pesanan yang tidak dibayar dalam batas waktu tersebut akan dibatalkan secara otomatis.</li>
                    <li>Pembayaran dinyatakan sah setelah sistem menerima konfirmasi sukses dari Payment Gateway resmi.</li>
                    <li>Kredensial layanan akun digital atau akses unduhan produk digital akan diberikan seketika setelah pembayaran sukses terverifikasi.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h3>4. Kebijakan Garansi &amp; Klaim Masalah</h3>
                <p>{{ isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi' }} berkomitmen memberikan garansi penuh sesuai dengan durasi masa aktif layanan yang tertera pada setiap varian produk:</p>
                <ul>
                    <li><strong>Masa Garansi:</strong> Garansi berlaku selama masa aktif layanan masih berjalan sejak tanggal transaksi berhasil.</li>
                    <li><strong>Klaim Garansi:</strong> Jika terjadi kendala pada akun (misal: password tidak sesuai / logout), pengguna dapat mengajukan laporan melalui menu <em>Laporan Masalah</em> di dashboard atau menghubungi WhatsApp Support resmi.</li>
                    <li><strong>Solusi:</strong> Tim kami akan melakukan perbaikan atau penggantian kredensial akun baru maksimal 1x24 jam sejak laporan diverifikasi.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h3>5. Kebijakan Pengembalian Dana (Refund)</h3>
                <p>Pengembalian dana (refund) hanya dapat disetujui apabila produk yang dipesan kehabisan stok atau tim teknis kami tidak dapat memberikan penggantian akun yang berfungsi dalam kurun waktu 3x24 jam.</p>
            </div>

            <div class="legal-section">
                <h3>6. Perubahan Ketentuan</h3>
                <p>{{ isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi' }} berhak memperbarui Syarat dan Ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Perubahan akan berlaku segera setelah dipublikasikan pada halaman ini.</p>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="footer-inner">
            <div class="footer-top-row">
                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="nav-logo" style="display:inline-flex;align-items:center;margin-bottom:8px;text-decoration:none;">
                        @if(isset($websiteSettings) && $websiteSettings->logo_path)
                            <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name ?? 'Lapaktifikasi' }}"
                                style="max-height: 48px; width: auto; object-fit: contain;">
                        @else
                            <div class="nav-logo-icon">L</div>
                            <span class="nav-logo-text">{{ isset($websiteSettings) ? $websiteSettings->site_name : 'LAPAKTIFIKASI' }}</span>
                        @endif
                    </a>
                    <p>{{ isset($websiteSettings) && $websiteSettings->site_description ? $websiteSettings->site_description : 'Platform marketplace produk digital, source code aplikasi, dan akun premium terpercaya di Indonesia.' }}</p>
                    <div class="footer-socials">
                        <a href="https://instagram.com/nusagarudastudio" target="_blank" class="social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://wa.me/{{ isset($websiteSettings) && $websiteSettings->contact_phone ? preg_replace('/[^0-9]/', '', $websiteSettings->contact_phone) : '6287897600086' }}" target="_blank" class="social-btn" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://t.me/nusagarudastudio" target="_blank" class="social-btn" aria-label="Telegram"><i class="bi bi-telegram"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h5>Navigasi</h5>
                    <ul>
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ route('premium.katalog') }}">Katalog Produk</a></li>
                        <li><a href="{{ url('/') }}#fitur">Kelebihan</a></li>
                        <li><a href="{{ url('/') }}#visimisi">Visi &amp; Misi</a></li>
                        <li><a href="{{ url('/') }}#faq">FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Layanan</h5>
                    <ul>
                        <li><a href="{{ route('premium.katalog', ['kategori' => 'premium']) }}">Akun Premium</a></li>
                        <li><a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}">Source Code</a></li>
                        <li><a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}">E-Book &amp; Modul</a></li>
                        <li><a href="{{ route('daftar.seller') }}">Program Seller</a></li>
                        <li><a href="{{ route('join.partner') }}">Kemitraan Komunitas</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Dukungan</h5>
                    <ul>
                        <li><a href="{{ url('/') }}#faq">Pusat Bantuan</a></li>
                        <li><a href="{{ route('kebijakan.privasi') }}">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('syarat.ketentuan') }}">Syarat &amp; Ketentuan</a></li>
                        <li><a href="{{ route('daftar.seller') }}">Daftar Jadi Seller</a></li>
                        <li><a href="{{ route('join.partner') }}">Join Partner</a></li>
                        <li><a href="https://wa.me/{{ isset($websiteSettings) && $websiteSettings->contact_phone ? preg_replace('/[^0-9]/', '', $websiteSettings->contact_phone) : '6287897600086' }}?text=Halo%20Admin%20Lapaktifikasi,%20saya%20ingin%20bertanya%20mengenai%20layanan%20Lapaktifikasi." target="_blank">Hubungi Kami</a></li>
                        @guest
                            <li><a href="{{ url('/pendaftaran') }}">Daftar Akun</a></li>
                        @endguest
                    </ul>
                </div>
                <div class="footer-col footer-location-col">
                    <h5>Lokasi Kami</h5>
                    <div class="footer-location-content">
                        <p class="footer-address">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>{{ isset($websiteSettings) && $websiteSettings->address ? $websiteSettings->address : 'Jl. Golf RT06/08, Ciriung, Kec. Cibinong, Kab. Bogor, Jawa Barat 16918' }}</span>
                        </p>
                        @php
                            $mapEmbedSrc = (isset($websiteSettings) && !empty($websiteSettings->maps_embed_url)) 
                                ? $websiteSettings->maps_embed_url 
                                : 'https://maps.google.com/maps?q=' . urlencode(isset($websiteSettings) && $websiteSettings->address ? $websiteSettings->address : 'Bogor, Jawa Barat, Indonesia') . '&t=&z=15&ie=UTF8&iwloc=&output=embed';
                            $mapsDirectUrl = 'https://maps.google.com/?q=' . urlencode(isset($websiteSettings) && $websiteSettings->address ? $websiteSettings->address : 'Bogor, Jawa Barat, Indonesia');
                        @endphp
                        <div class="footer-map-frame">
                            <iframe 
                                src="{{ $mapEmbedSrc }}" 
                                width="100%" 
                                height="110" 
                                style="border:0;display:block;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Peta Lokasi {{ $websiteSettings->site_name ?? 'Lapaktifikasi' }}">
                            </iframe>
                        </div>
                        <a href="{{ $mapsDirectUrl }}" target="_blank" class="footer-map-action" rel="noopener noreferrer">
                            <i class="bi bi-box-arrow-up-right"></i>
                            <span>Buka di Google Maps</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom-row">
                <p>&copy; {{ date('Y') }} {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="footer-bottom-badges">
                    <div class="badge-secure"><i class="bi bi-shield-check-fill"></i> Pembayaran Aman</div>
                    <div class="badge-secure"><i class="bi bi-lock-fill"></i> Data Terenkripsi</div>
                    <div class="badge-secure"><i class="bi bi-patch-check-fill"></i> Terverifikasi</div>
                </div>
            </div>
        </div>
    </footer>

    <!-- LOGIN MODAL -->
    <div class="modal-overlay" id="login-modal" onclick="closeModalOverlay(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
            <div style="text-align:center;margin-bottom:24px;">
                <div class="modal-logo">L</div>
                <div class="modal-title">Masuk ke {{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }}</div>
                <div class="modal-subtitle">Masuk untuk mulai menikmati aneka produk digital &amp; akun premium</div>
            </div>
            <div class="login-tabs">
                <button class="login-tab active" onclick="switchModalTab(this,'customer')">&#128722; Pelanggan</button>
                <button class="login-tab" onclick="switchModalTab(this,'admin')">&#128737; Admin</button>
            </div>
            <form action="{{ url('/proses_login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" id="modal-username-label">Email Pelanggan</label>
                    <div class="form-input-wrap">
                        <i class="bi bi-envelope-fill form-icon"></i>
                        <input class="form-input" type="email" id="modal-email" name="email"
                            placeholder="Masukkan email Anda" required autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="form-input-wrap">
                        <i class="bi bi-lock-fill form-icon"></i>
                        <input class="form-input" type="password" id="modal-password" name="password"
                            placeholder="Masukkan kata sandi" required autocomplete="off">
                    </div>
                </div>
                <label class="form-check-row">
                    <input type="checkbox" id="modal-show-password" onclick="togglePassword()"> Tampilkan kata sandi
                </label>
                <button type="submit" class="btn-modal-submit">Masuk Sekarang</button>
            </form>
            <div style="text-align:center;margin-top:16px;font-size:0.85rem;color:#666666;">
                Belum punya akun? <a href="{{ url('/pendaftaran') }}" style="color:#000000;font-weight:700;text-decoration:none;">Daftar Gratis</a>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');
            if (menu && overlay) {
                menu.classList.toggle('active');
                overlay.classList.toggle('active');
            }
        }

        function closeMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileMenuOverlay');
            if (menu && overlay) {
                menu.classList.remove('active');
                overlay.classList.remove('active');
            }
        }

        function openModal() {
            const modal = document.getElementById('login-modal');
            if (modal) modal.style.display = 'flex';
        }

        function closeModal() {
            const modal = document.getElementById('login-modal');
            if (modal) modal.style.display = 'none';
        }

        function closeModalOverlay(e) {
            if (e.target.id === 'login-modal') closeModal();
        }

        function switchModalTab(btn, type) {
            document.querySelectorAll('.login-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const label = document.getElementById('modal-username-label');
            const input = document.getElementById('modal-email');
            if (type === 'admin') {
                if (label) label.textContent = 'Username Admin';
                if (input) {
                    input.type = 'text';
                    input.name = 'username';
                    input.placeholder = 'Masukkan username admin';
                }
            } else {
                if (label) label.textContent = 'Email Pelanggan';
                if (input) {
                    input.type = 'email';
                    input.name = 'email';
                    input.placeholder = 'Masukkan email Anda';
                }
            }
        }

        function togglePassword() {
            const pwd = document.getElementById('modal-password');
            const chk = document.getElementById('modal-show-password');
            if (pwd && chk) {
                pwd.type = chk.checked ? 'text' : 'password';
            }
        }
    </script>
</body>
</html>
