<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Seller - Lapaktifikasi</title>
    <meta name="description" content="Bergabunglah sebagai seller di Lapaktifikasi dan mulai menjual produk digital premium Anda kepada ribuan pelanggan aktif. Proses cepat dan tanpa formulir rumit.">
    <meta name="keywords" content="daftar seller, jadi seller, jualan akun premium, lapaktifikasi, seller premium">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary:      #000000;
            --primary-2:    #1a1a1a;
            --accent:       #000000;
            --accent-2:     #333333;
            --dark-bg:      #ffffff;
            --dark-2:       #fafafa;
            --glass-bg:     rgba(0, 0, 0, 0.02);
            --glass-border: rgba(0, 0, 0, 0.08);
            --glass-hover:  rgba(0, 0, 0, 0.04);
            --text-main:    #111111;
            --text-muted:   #555555;
            --text-dim:     #888888;
            --white:        #ffffff;
            --radius-lg:    24px;
            --radius-xl:    32px;
            --whatsapp-color: #25D366;
            --gmail-color:    #EA4335;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark-bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--dark-bg); }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* BG BLOBS & GRID */
        .bg-blobs { display: none; }
        .bg-grid { position: fixed; inset: 0; z-index: 0; pointer-events: none; background-image: linear-gradient(rgba(0,0,0,0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.015) 1px, transparent 1px); background-size: 60px 60px; }

        /* UTILITY */
        .container-custom { max-width: 1200px; margin: 0 auto; padding: 0 5%; }
        .section-tag { display: inline-flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.12); color: var(--text-main); font-size: 0.8rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; padding: 7px 16px; border-radius: 50px; margin-bottom: 20px; }
        .section-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 700; line-height: 1.2; color: var(--text-main); margin-bottom: 16px; }
        .section-subtitle { font-size: 1.05rem; color: var(--text-muted); max-width: 560px; line-height: 1.7; }
        .highlight { color: #000000; font-weight: 800; }
        .grad-text { background: none; -webkit-background-clip: unset; -webkit-text-fill-color: currentColor; background-clip: unset; color: #000000; font-weight: 800; }

        /* GLASS CARD */
        .glass-card { background: #ffffff; border: 1px solid #000000; border-radius: var(--radius-lg); transition: all 0.35s ease; }
        .glass-card:hover { transform: translateY(-6px); border-color: #000000; box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

        /* BUTTONS */
        .btn-primary { display: inline-flex; align-items: center; gap: 10px; background: #000000; color: #ffffff !important; font-weight: 700; font-size: 1rem; padding: 15px 32px; border-radius: 14px; text-decoration: none !important; border: 1px solid #000000; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-primary:hover { background: transparent; color: #000000 !important; transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        
        .btn-whatsapp { display: inline-flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; background: var(--whatsapp-color); color: #ffffff !important; font-weight: 700; font-size: 1.1rem; padding: 20px 36px; border-radius: 18px; text-decoration: none !important; border: 2px solid var(--whatsapp-color); cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(37, 211, 102, 0.25); text-align: center; }
        .btn-whatsapp span.small { font-size: 0.8rem; font-weight: 500; opacity: 0.9; }
        .btn-whatsapp:hover { background: transparent; color: var(--whatsapp-color) !important; transform: translateY(-4px); box-shadow: 0 12px 28px rgba(37, 211, 102, 0.15); }

        .btn-gmail { display: inline-flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; background: var(--gmail-color); color: #ffffff !important; font-weight: 700; font-size: 1.1rem; padding: 20px 36px; border-radius: 18px; text-decoration: none !important; border: 2px solid var(--gmail-color); cursor: pointer; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(234, 67, 53, 0.25); text-align: center; }
        .btn-gmail span.small { font-size: 0.8rem; font-weight: 500; opacity: 0.9; }
        .btn-gmail:hover { background: transparent; color: var(--gmail-color) !important; transform: translateY(-4px); box-shadow: 0 12px 28px rgba(234, 67, 53, 0.15); }

        /* NAVBAR */
        #navbar { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; padding: 20px 5%; display: flex; align-items: center; justify-content: space-between; transition: all 0.4s ease; }
        #navbar.scrolled { background: rgba(255,255,255,0.95); border-bottom: 1px solid #e5e5e5; padding: 14px 5%; box-shadow: 0 4px 30px rgba(0,0,0,0.02); }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none !important; }
        .nav-logo-icon { width: 42px; height: 42px; background: #000000; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.3rem; color: #ffffff; }
        .nav-logo-text { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.5rem; color: #000000; }
        .nav-logo-text span { color: #555555; }
        .nav-links { display: flex; list-style: none; gap: 32px; }
        .nav-links a { color: var(--text-muted); font-weight: 600; font-size: 0.95rem; text-decoration: none !important; transition: color 0.3s; }
        .nav-links a:hover { color: #000000; }
        .nav-actions { display: flex; align-items: center; gap: 16px; }
        .btn-nav-login { color: var(--text-muted); font-weight: 600; font-size: 0.95rem; text-decoration: none !important; transition: color 0.3s; }
        .btn-nav-login:hover { color: #000000; }
        .btn-nav-signup { background: #000000; color: #ffffff !important; font-weight: 700; font-size: 0.9rem; padding: 10px 22px; border-radius: 12px; text-decoration: none !important; border: 1px solid #000000; transition: all 0.3s ease; }
        .btn-nav-signup:hover { background: transparent; color: #000000 !important; transform: translateY(-2px); }

        /* HERO SELLER */
        #hero-seller { min-height: 70vh; display: flex; align-items: center; padding: 160px 5% 80px; position: relative; z-index: 1; text-align: center; }
        .hero-seller-inner { max-width: 900px; margin: 0 auto; width: 100%; display: flex; flex-direction: column; align-items: center; }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.12); color: #000000; font-size: 0.82rem; font-weight: 700; letter-spacing: 1px; padding: 8px 18px; border-radius: 50px; margin-bottom: 28px; animation: fadeInDown 0.7s ease both; }
        .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.7rem, 3.2vw, 2.4rem); font-weight: 700; line-height: 1.2; margin-bottom: 24px; animation: fadeInUp 0.7s ease 0.1s both; }
        .hero-desc { font-size: 1.15rem; color: var(--text-muted); line-height: 1.75; margin-bottom: 32px; max-width: 700px; animation: fadeInUp 0.7s ease 0.2s both; }

        /* SECTION WRAPPERS */
        .section-wrap { position: relative; z-index: 1; padding: 80px 5%; }
        .section-wrap.alt { background: #fafafa; border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; }
        .section-header { margin-bottom: 56px; }
        .section-header.centered { text-align: center; }
        .section-header.centered .section-subtitle { margin: 0 auto; }

        /* BENEFITS */
        .benefits-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; max-width: 1200px; margin: 0 auto; }
        .benefit-card { padding: 36px 30px; text-align: center; }
        .benefit-icon { width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 24px; background: #fafafa; border: 1px solid #000000; color: #000000; transition: all 0.3s; }
        .benefit-card:hover .benefit-icon { background: #000000; color: #ffffff; }
        .benefit-card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 14px; }
        .benefit-card p { color: var(--text-muted); font-size: 0.92rem; line-height: 1.7; }

        /* REGISTRATION ACTIONS */
        .registration-box { max-width: 900px; margin: 0 auto; padding: 60px 40px; border-radius: var(--radius-xl); background: #ffffff; border: 1px solid #000000; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .registration-box h2 { font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem; font-weight: 700; margin-bottom: 14px; }
        .registration-box p { color: var(--text-muted); font-size: 1.05rem; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto; }
        .action-buttons { display: flex; justify-content: center; align-items: stretch; gap: 24px; flex-wrap: wrap; }
        .action-buttons a { flex: 1; min-width: 280px; max-width: 380px; }

        /* STEPS */
        .steps-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 24px; max-width: 1200px; margin: 0 auto; position: relative; }
        .steps-grid::before { content: ''; position: absolute; top: 36px; left: 12.5%; right: 12.5%; height: 2px; background: #e5e5e5; z-index: 0; }
        .step-card { padding: 36px 24px; text-align: center; position: relative; z-index: 1; }
        .step-num { width: 56px; height: 56px; border-radius: 50%; background: #000000; color: #ffffff; font-family: 'Space Grotesk', sans-serif; font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .step-card h4 { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; margin-bottom: 10px; }
        .step-card p { color: var(--text-muted); font-size: 0.88rem; line-height: 1.65; }

        /* FOOTER */
        footer { position: relative; z-index: 1; background: #ffffff; border-top: 1px solid #000000; padding: 72px 5% 36px; color: #000000; }
        .footer-inner { max-width: 1200px; margin: 0 auto; }
        .footer-top-row { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 60px; }
        .footer-brand p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.7; margin: 18px 0 24px; }
        .footer-socials { display: flex; gap: 12px; }
        .social-btn { width: 40px; height: 40px; border-radius: 10px; background: #ffffff; border: 1px solid #000000; display: flex; align-items: center; justify-content: center; color: #000000; font-size: 1.1rem; text-decoration: none !important; transition: all 0.3s; }
        .social-btn:hover { background: #000000; color: #ffffff !important; }
        .footer-col h5 { font-family: 'Space Grotesk', sans-serif; font-size: 0.95rem; font-weight: 700; color: #000000; margin-bottom: 20px; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 12px; }
        .footer-col ul a { color: var(--text-muted); text-decoration: none !important; font-size: 0.9rem; transition: color 0.3s; }
        .footer-col ul a:hover { color: #000000; }
        .footer-bottom-row { border-top: 1px solid #e5e5e5; padding-top: 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .footer-bottom-row p { color: var(--text-dim); font-size: 0.85rem; }
        .footer-bottom-badges { display: flex; gap: 12px; flex-wrap: wrap; }
        .badge-secure { display: flex; align-items: center; gap: 6px; background: #ffffff; border: 1px solid #e5e5e5; color: var(--text-muted); font-size: 0.8rem; padding: 6px 14px; border-radius: 8px; }
        .badge-secure i { color: #000000; }

        /* MODAL */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(8px); display: none; justify-content: center; align-items: center; z-index: 10000; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.open { display: flex; opacity: 1; }
        .modal-box { background: #ffffff; border: 1px solid #000000; border-radius: var(--radius-xl); padding: 44px; width: 100%; max-width: 460px; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.1); transform: scale(0.92) translateY(20px); transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1); color: #000000; }
        .modal-overlay.open .modal-box { transform: scale(1) translateY(0); }
        .modal-close { position: absolute; top: 20px; right: 20px; background: #ffffff; border: 1px solid #e5e5e5; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; cursor: pointer; color: var(--text-muted); transition: all 0.2s; }
        .modal-close:hover { background: #000000; color: #ffffff !important; }
        .modal-logo { width: 54px; height: 54px; background: #000000; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.6rem; color: #ffffff; margin: 0 auto 16px; }
        .modal-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 6px; color: #000000; }
        .modal-subtitle { color: var(--text-muted); text-align: center; font-size: 0.88rem; margin-bottom: 28px; }
        .login-tabs { display: flex; background: #fafafa; border: 1px solid #e5e5e5; padding: 5px; border-radius: 14px; margin-bottom: 24px; }
        .login-tab { flex: 1; border: none; background: transparent; padding: 10px; border-radius: 10px; font-weight: 600; font-size: 0.88rem; color: var(--text-muted); cursor: pointer; transition: all 0.3s; font-family: 'Plus Jakarta Sans', sans-serif; }
        .login-tab.active { background: #000000; color: #ffffff !important; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-weight: 600; font-size: 0.85rem; color: #333333; margin-bottom: 8px; }
        .form-input-wrap { position: relative; }
        .form-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #666666; }
        .form-input { width: 100%; padding: 14px 18px 14px 46px; background: #ffffff; border: 1px solid #cccccc; border-radius: 12px; color: #000000; font-size: 0.93rem; outline: none; transition: all 0.3s; font-family: 'Plus Jakarta Sans', sans-serif; }
        .form-input::placeholder { color: var(--text-dim); }
        .form-input:focus { border-color: #000000; box-shadow: 0 0 0 3px rgba(0,0,0,0.1); }
        .form-check-row { display: flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.84rem; margin-bottom: 22px; cursor: pointer; }
        .btn-modal-submit { width: 100%; padding: 15px; background: #000000; color: #ffffff; font-weight: 700; font-size: 1rem; border: 1px solid #000000; border-radius: 14px; cursor: pointer; transition: all 0.3s; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-modal-submit:hover { background: transparent; color: #000000 !important; }
        .modal-register-hint { text-align: center; margin-top: 18px; font-size: 0.85rem; color: var(--text-muted); }
        .modal-register-hint a { color: #000000; font-weight: 600; text-decoration: none !important; }

        /* ANIMATIONS */
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .benefits-grid { grid-template-columns: repeat(2,1fr); }
            .steps-grid { grid-template-columns: repeat(2,1fr); }
            .steps-grid::before { display: none; }
            .footer-top-row { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .hero-seller-inner { padding: 0 10px; }
            .nav-links { display: none; }
            .benefits-grid { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: 1fr; }
            .footer-top-row { grid-template-columns: 1fr; gap: 32px; }
            .registration-box { padding: 40px 20px; width: 100%; }
            .action-buttons { gap: 16px; flex-direction: column; }
            .action-buttons a { width: 100%; max-width: 100%; }
        }
        @media (max-width: 480px) {
            .hero-title { font-size: 2.2rem !important; line-height: 1.25 !important; }
            .section-title { font-size: 1.8rem !important; }
            .modal-box { padding: 28px 20px !important; width: 92% !important; }
            .btn-whatsapp, .btn-gmail { padding: 16px 20px; font-size: 1rem; }
        }
    </style>
</head>
<body>

<div class="bg-grid"></div>

<!-- NAVBAR -->
<nav id="navbar">
    <a href="{{ url('/') }}" class="nav-logo">
        <div class="nav-logo-icon">L</div>
        <span class="nav-logo-text">LAPAK<span>TIFIKASI</span></span>
    </a>
    <ul class="nav-links">
        <li><a href="{{ url('/') }}#hero">Beranda</a></li>
        <li><a href="{{ url('/') }}#fitur">Kelebihan</a></li>
        <li><a href="{{ url('/') }}#visimisi">Visi &amp; Misi</a></li>
        <li><a href="{{ url('/') }}#produk">Produk</a></li>
        <li><a href="{{ url('/') }}#faq">FAQ</a></li>
        <li><a href="{{ route('daftar.seller') }}" class="highlight">Jadi Seller</a></li>
        @auth
            @if(Auth::user()->role_id == 2)
                <li><a href="{{ route('premium.katalog') }}">Belanja</a></li>
            @else
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            @endif
        @endauth
    </ul>
    <div class="nav-actions">
        @auth
            @if(Auth::user()->role_id == 1)
                <a href="{{ url('/dashboard') }}" class="btn-nav-signup"><i class="bi bi-speedometer2"></i> Dashboard</a>
            @else
                <a href="{{ route('premium.katalog') }}" class="btn-nav-signup"><i class="bi bi-cart3"></i> Belanja</a>
            @endif
        @else
            <a href="{{ url('/login') }}" class="btn-nav-login">Masuk</a>
            <a href="{{ url('/pendaftaran') }}" class="btn-nav-signup">Daftar Gratis</a>
        @endauth
    </div>
</nav>

<!-- HERO SELLER -->
<header id="hero-seller">
    <div class="hero-seller-inner">
        <div class="hero-badge"><i class="bi bi-shop-window"></i> Kemitraan Seller Terbuka</div>
        <h1 class="hero-title">Bergabung Sebagai Seller<br><span class="grad-text">Lapak &amp; Produk Digital</span></h1>
        <p class="hero-desc">Mulai jualan berbagai produk digital premium Anda dengan mudah. Lapaktifikasi menyediakan platform modern bagi para penyedia layanan untuk menjangkau ribuan pembeli aktif dengan sistem pengelolaan stok otomatis.</p>
        <a href="#daftar" class="btn-primary"><i class="bi bi-arrow-down-circle-fill"></i> Mulai Pendaftaran</a>
    </div>
</header>

<!-- BENEFIT SELLER -->
<section class="section-wrap alt">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-gift-fill"></i> Benefit Kemitraan</div>
            <h2 class="section-title">Mengapa Menjadi <span class="highlight">Seller Kami</span>?</h2>
            <p class="section-subtitle">Dapatkan fasilitas lengkap dan infrastruktur e-commerce terbaik untuk mengembangkan bisnis penjualan akun digital Anda.</p>
        </div>
        <div class="benefits-grid">
            <div class="glass-card benefit-card reveal">
                <div class="benefit-icon"><i class="bi bi-people-fill"></i></div>
                <h3>Pasar Luas &amp; Aktif</h3>
                <p>Akses ke ribuan pembeli premium terdaftar di platform kami yang siap berbelanja setiap harinya secara berkelanjutan.</p>
            </div>
            <div class="glass-card benefit-card reveal" style="transition-delay:.1s">
                <div class="benefit-icon"><i class="bi bi-cpu-fill"></i></div>
                <h3>Sistem Manajemen Stok</h3>
                <p>Dashboard seller lengkap untuk menambahkan jenis produk, mengelola durasi varian, serta mengunggah stok kredensial secara instan.</p>
            </div>
            <div class="glass-card benefit-card reveal" style="transition-delay:.2s">
                <div class="benefit-icon"><i class="bi bi-wallet2"></i></div>
                <h3>Penarikan Saldo Cepat</h3>
                <p>Tarik hasil penjualan Anda kapan saja dengan mudah. Histori mutasi saldo tercatat lengkap secara transparan.</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW TO REGISTER (STEPS) -->
<section class="section-wrap">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-diagram-3-fill"></i> Cara Mendaftar</div>
            <h2 class="section-title">Proses Mudah <span class="highlight">4 Langkah</span></h2>
            <p class="section-subtitle">Sistem pendaftaran manual yang terverifikasi untuk menjaga keamanan ekosistem seller dan pembeli kami.</p>
        </div>
        <div class="steps-grid">
            <div class="glass-card step-card reveal">
                <div class="step-num">1</div>
                <h4>Hubungi Admin</h4>
                <p>Klik tombol WhatsApp atau Email di bawah untuk menghubungi tim pendaftaran admin Lapaktifikasi.</p>
            </div>
            <div class="glass-card step-card reveal" style="transition-delay:.1s">
                <div class="step-num">2</div>
                <h4>Kirimkan Profil Toko</h4>
                <p>Kirimkan informasi nama toko Anda serta daftar produk digital yang ingin Anda tawarkan di platform kami.</p>
            </div>
            <div class="glass-card step-card reveal" style="transition-delay:.2s">
                <div class="step-num">3</div>
                <h4>Proses Verifikasi</h4>
                <p>Tim kami akan memverifikasi penawaran Anda dan membuatkan akun seller khusus untuk masuk ke sistem.</p>
            </div>
            <div class="glass-card step-card reveal" style="transition-delay:.3s">
                <div class="step-num">4</div>
                <h4>Mulai Jualan</h4>
                <p>Setelah akun aktif, Anda bisa langsung menambahkan stok akun premium dan menerima hasil penjualan instan.</p>
            </div>
        </div>
    </div>
</section>

<!-- KETENTUAN & MAINTENANCE SECTION -->
<section class="section-wrap alt">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-info-circle-fill"></i> Kebijakan &amp; Ketentuan</div>
            <h2 class="section-title">Infrastruktur &amp; <span class="highlight">Skema Kerjasama</span></h2>
            <p class="section-subtitle">Informasi penting mengenai skema potongan komisi, integrasi gerbang pembayaran, dan jadwal pemeliharaan server berkala.</p>
        </div>
        <div class="benefits-grid">
            <div class="glass-card benefit-card reveal">
                <div class="benefit-icon"><i class="bi bi-shield-fill-check"></i></div>
                <h3>100% Gerbang Pembayaran Aman</h3>
                <p>Seluruh transaksi pelanggan diproses melalui payment gateway <strong>Midtrans</strong> bersertifikasi resmi dan aman. Biaya admin payment gateway dibebankan kepada customer per transaksi.</p>
            </div>
            <div class="glass-card benefit-card reveal" style="transition-delay:.1s">
                <div class="benefit-icon"><i class="bi bi-award-fill"></i></div>
                <h3>Skema Komisi Fleksibel</h3>
                <p>Nikmati <strong>gratis biaya potongan/komisi pada bulan pertama</strong> Anda bergabung. Potongan komisi baru akan diberlakukan pada bulan kedua dan dihitung dari setiap orderan yang masuk.</p>
            </div>
            <div class="glass-card benefit-card reveal" style="transition-delay:.2s">
                <div class="benefit-icon"><i class="bi bi-clock-history"></i></div>
                <h3>Jadwal Pemeliharaan Server</h3>
                <p>Pemeliharaan rutin dilakukan setiap <strong>hari Minggu pukul 24:00 s.d 01:00 WIB</strong> (1 jam), serta pemeliharaan bulanan terjadwal antara pukul <strong>22:00 s.d 03:00 WIB</strong>.</p>
            </div>
            <div class="glass-card benefit-card reveal" style="transition-delay:.3s">
                <div class="benefit-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></div>
                <h3>Laporan &amp; Changelog Maintenance</h3>
                <p>Kami menyajikan laporan berkala dan <strong>changelog transparan</strong> hasil pemeliharaan sistem agar Anda selalu mengetahui perkembangan platform.</p>
            </div>
            <div class="glass-card benefit-card reveal" style="transition-delay:.4s">
                <div class="benefit-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <h3>Pengecekan Sistem Menyeluruh</h3>
                <p>Setiap maintenance berfokus pada <strong>keamanan sistem (security)</strong>, optimasi performa database, audit keamanan, dan pembersihan bug secara komprehensif.</p>
            </div>
            <div class="glass-card benefit-card reveal" style="transition-delay:.5s">
                <div class="benefit-icon"><i class="bi bi-palette-fill"></i></div>
                <h3>Redesain Tampilan Berkala</h3>
                <p>Untuk menjaga antusiasme pembeli, platform kami melakukan <strong>redesain layout &amp; tampilan setiap 3 bulan</strong> agar selalu segar, modern, dan konversi penjualan tetap tinggi.</p>
            </div>
        </div>
    </div>
</section>

<!-- REGISTRATION CARD (CONTACT ACTION) -->
<section id="daftar" class="section-wrap" style="padding: 100px 5%;">
    <div class="container-custom">
        <div class="glass-card registration-box reveal">
            <div class="section-tag" style="margin-bottom: 16px;"><i class="bi bi-send-fill"></i> Kontak Pendaftaran</div>
            <h2>Mulai Langkah Sukses Anda</h2>
            <p>Kami tidak menggunakan formulir yang panjang dan membosankan. Silakan hubungi admin kami secara langsung melalui salah satu metode berikut untuk mendaftar.</p>
            
            <div class="action-buttons">
                <!-- WHATSAPP BUTTON -->
                <a href="https://wa.me/6287897600086?text=Halo%20Admin%20Lapaktifikasi%2C%20saya%20tertarik%20untuk%20mendaftar%20sebagai%20seller.%20Mohon%20informasi%20langkah%20selanjutnya." target="_blank" class="btn-whatsapp">
                    <i class="bi bi-whatsapp" style="font-size: 1.8rem; margin-bottom: 2px;"></i>
                    Hubungi via WhatsApp
                    <span class="small">Respons cepat &amp; diskusi langsung</span>
                </a>
                
                <!-- GMAIL BUTTON -->
                <a href="mailto:nusagarudastudio@gmail.com?subject=Pendaftaran%20Seller%20Lapaktifikasi&body=Halo%20Admin%20Lapaktifikasi%2C%250D%250A%250D%250Asaya%20tertarik%20untuk%20mendaftar%20sebagai%20seller%20di%20platform%20Anda.%20Berikut%20detail%20singkat%20saya%3A%250D%250A-%20Nama%20Toko%3A%20%5B%5D%250D%250A-%20Kategori%20Produk%20Digital%3A%20%5B%5D%250D%250A%250D%250AMohon%20panduannya.%20Terima%20kasih." class="btn-gmail">
                    <i class="bi bi-envelope-fill" style="font-size: 1.8rem; margin-bottom: 2px;"></i>
                    Hubungi via Gmail
                    <span class="small">Kirim penawaran &amp; proposal kerjasama</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-top-row">
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="nav-logo" style="display:inline-flex;margin-bottom:4px;">
                    <div class="nav-logo-icon">L</div>
                    <span class="nav-logo-text">LAPAK<span>TIFIKASI</span></span>
                </a>
                <p>Platform distribusi akun digital premium terpercaya di Indonesia. Cepat, aman, dan terjangkau untuk semua lapisan pengguna.</p>
                <div class="footer-socials">
                    <a href="https://instagram.com/nusagarudastudio" target="_blank" class="social-btn"><i class="bi bi-instagram"></i></a>
                    <a href="https://wa.me/6287897600086" target="_blank" class="social-btn"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://t.me/nusagarudastudio" target="_blank" class="social-btn"><i class="bi bi-telegram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h5>Layanan</h5>
                <ul>
                    <li><a href="{{ url('/') }}#produk">Spotify Premium</a></li>
                    <li><a href="{{ url('/') }}#produk">Netflix Premium</a></li>
                    <li><a href="{{ url('/') }}#produk">YouTube Premium</a></li>
                    <li><a href="{{ url('/') }}#produk">Layanan Gaming</a></li>
                    <li><a href="{{ url('/') }}#produk">Platform Edukasi</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Perusahaan</h5>
                <ul>
                    <li><a href="{{ url('/') }}#visimisi">Tentang Lapaktifikasi</a></li>
                    <li><a href="{{ url('/') }}#visimisi">Visi &amp; Misi</a></li>
                    <li><a href="{{ url('/') }}#cara-kerja">Cara Kerja</a></li>
                    <li><a href="{{ url('/') }}#testimoni">Testimoni</a></li>
                    <li><a href="{{ url('/') }}#faq">FAQ</a></li>
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
                    <li><a href="https://wa.me/6287897600086?text=Halo%20Admin%20Lapaktifikasi,%20saya%20ingin%20bertanya%20mengenai%20layanan%20Lapaktifikasi." target="_blank">Hubungi Kami</a></li>
                    @guest
                        <li><a href="{{ url('/pendaftaran') }}">Daftar Akun</a></li>
                    @endguest
                </ul>
            </div>
        </div>
        <div class="footer-bottom-row">
            <p>&copy; {{ date('Y') }} Lapaktifikasi. Hak Cipta Dilindungi Undang-Undang.</p>
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
            <div class="modal-title">Masuk ke Lapaktifikasi</div>
            <div class="modal-subtitle">Masuk untuk mulai menikmati akun premium</div>
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
                    <input class="form-input" type="email" id="modal-email" name="email" placeholder="Masukkan email Anda" required autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Kata Sandi</label>
                <div class="form-input-wrap">
                    <i class="bi bi-lock-fill form-icon"></i>
                    <input class="form-input" type="password" id="modal-password" name="password" placeholder="Masukkan kata sandi" required autocomplete="off">
                </div>
            </div>
            <label class="form-check-row">
                <input type="checkbox" id="modal-show-password" onclick="togglePassword()"> Tampilkan kata sandi
            </label>
            <button class="btn-modal-submit" type="submit"><i class="bi bi-box-arrow-in-right" style="margin-right:8px;"></i> Masuk Sekarang</button>
        </form>
        <div class="modal-register-hint">Belum punya akun? <a href="{{ url('/pendaftaran') }}">Daftar di sini &rarr;</a></div>
    </div>
</div>

<script>
    // NAVBAR SCROLL
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    });

    // MODAL
    const tabLabels = { customer: 'Email Pelanggan', admin: 'Email Admin' };
    window.switchModalTab = function(btn, role) {
        document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('modal-username-label').textContent = tabLabels[role];
        document.getElementById('modal-email').placeholder = 'Masukkan ' + tabLabels[role].toLowerCase();
    };
    window.openModal = function() {
        document.getElementById('login-modal').classList.add('open');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('modal-email').focus(), 120);
    };
    window.closeModal = function() {
        document.getElementById('login-modal').classList.remove('open');
        document.body.style.overflow = '';
    };
    window.closeModalOverlay = function(e) {
        if (e.target.id === 'login-modal') closeModal();
    };
    window.togglePassword = function() {
        const f = document.getElementById('modal-password');
        f.type = f.type === 'password' ? 'text' : 'password';
    };

    // SCROLL REVEAL
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });
    reveals.forEach(r => observer.observe(r));
</script>
</body>
</html>
