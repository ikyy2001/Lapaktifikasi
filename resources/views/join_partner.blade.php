<!DOCTYPE html>
<html lang="id">

<head>
    @php
        $siteName = isset($websiteSettings) && $websiteSettings->site_name ? $websiteSettings->site_name : 'Lapaktifikasi';
        $siteDesc = isset($websiteSettings) && $websiteSettings->site_description ? $websiteSettings->site_description : 'Marketplace Produk Digital, Source Code & Akun Premium Terpercaya';
        $phoneRaw = isset($websiteSettings) && $websiteSettings->contact_phone ? $websiteSettings->contact_phone : '081234567890';
        $phoneClean = preg_replace('/[^0-9]/', '', $phoneRaw);
        if (str_starts_with($phoneClean, '0')) {
            $phoneClean = '62' . substr($phoneClean, 1);
        }
        $contactEmail = isset($websiteSettings) && $websiteSettings->contact_email ? $websiteSettings->contact_email : 'partner@lapaktifikasi.com';
        $logoUrl = isset($websiteSettings) && $websiteSettings->logo_path ? asset($websiteSettings->logo_path) : asset('assets/img/smk_pelita_ambassadors.jpg');
        $faviconUrl = isset($websiteSettings) && $websiteSettings->favicon_path ? asset($websiteSettings->favicon_path) : asset('assets/img/favicon.png');
        $canonicalUrl = url('/join-partner');
        $currentHost = request()->getHost() ?: 'lapaktifikasi.com';
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Partner Komunitas - Buka Hub E-Commerce Digital Gratis | {{ $siteName }}</title>
    <meta name="description" content="Bergabunglah sebagai mitra partner resmi di {{ $siteName }}. Buka website toko digital khusus komunitas Anda secara gratis dengan custom subdomain, sistem serba otomatis, dan bagi hasil komisi transparan.">
    <meta name="keywords" content="join partner {{ strtolower($siteName) }}, kemitraan komunitas, community store, custom subdomain gratis, marketplace komunitas, bagi hasil komisi">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="Join Partner Komunitas | {{ $siteName }}">
    <meta property="og:description" content="Dapatkan website e-commerce subdomain gratis untuk komunitas Anda. Kelola seller lokal dan nikmati bagi hasil komisi transaksi otomatis.">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $logoUrl }}">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Join Partner Komunitas | {{ $siteName }}">
    <meta name="twitter:description" content="Dapatkan website e-commerce subdomain gratis untuk komunitas Anda. Kelola seller lokal dan nikmati bagi hasil komisi transaksi otomatis.">
    <meta name="twitter:image" content="{{ $logoUrl }}">

    <!-- Structured Data (Schema.org JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Join Partner Komunitas - {{ $siteName }}",
      "url": "{{ $canonicalUrl }}",
      "description": "Program kemitraan komunitas dan toko regional Lapaktifikasi dengan custom website subdomain gratis dan bagi hasil komisi transaksi.",
      "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "Beranda",
            "item": "{{ url('/') }}"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "Join Partner",
            "item": "{{ $canonicalUrl }}"
          }
        ]
      }
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "Apa itu program Join Partner Komunitas {{ $siteName }}?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Program kemitraan resmi yang memungkinkan komunitas (regional, gamer, hobi, kampus, atau sekolah) memiliki website e-commerce digital sendiri dengan subdomain khusus secara gratis tanpa biaya server."
          }
        },
        {
          "@type": "Question",
          "name": "Apakah ada biaya pembuatan website atau biaya bulanan?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Sama sekali tidak ada biaya setup, instalasi, maupun sewa bulanan (100% Gratis). Kemitraan berjalan dengan sistem pembagian hasil persentase komisi transaksi yang transparan."
          }
        },
        {
          "@type": "Question",
          "name": "Siapa yang mengelola server dan sistem pembayarannya?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Seluruh infrastruktur server, payment gateway (QRIS, Bank, e-Wallet), keamanan enkripsi, dan otomasi pengiriman produk ditangani penuh oleh tim {{ $siteName }}. Mitra cukup fokus merangkul seller dan mempromosikan toko komunitas."
          }
        },
        {
          "@type": "Question",
          "name": "Bagaimana cara mencairkan saldo bagi hasil komisi mitra?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Bagi hasil komisi dihitung secara real-time dari setiap transaksi sukses di website komunitas Anda dan dapat dicairkan (*withdraw*) langsung ke rekening bank mitra kapan saja."
          }
        }
      ]
    }
    </script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #000000;
            --primary-2: #1a1a1a;
            --accent: #5417D7;
            --accent-light: #7033ff;
            --dark-bg: #ffffff;
            --dark-2: #fafafa;
            --glass-bg: rgba(255, 255, 255, 0.82);
            --glass-border: rgba(0, 0, 0, 0.08);
            --text-main: #111111;
            --text-muted: #555555;
            --text-dim: #888888;
            --white: #ffffff;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --whatsapp-color: #25D366;
            --gmail-color: #EA4335;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html { scroll-behavior: smooth; }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -10;
            background-color: #f4f6f9;
            background-image:
                radial-gradient(at 40% 20%, hsla(220, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(280, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(180, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 50%, hsla(320, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(40, 100%, 80%, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 100%, hsla(120, 100%, 80%, 0.2) 0px, transparent 50%);
            transform: translateZ(0);
            will-change: transform;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: transparent;
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--dark-bg); }
        ::-webkit-scrollbar-thumb { background: #000; border-radius: 3px; }

        .bg-grid {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image: linear-gradient(rgba(0, 0, 0, 0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 0, 0, 0.015) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* UTILITY */
        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5%;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(84, 23, 215, 0.06);
            border: 1px solid rgba(84, 23, 215, 0.2);
            color: var(--accent);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 7px 18px;
            border-radius: 50px;
            margin-bottom: 20px;
        }

        .section-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-main);
            margin-bottom: 16px;
        }

        .section-subtitle {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 600px;
            line-height: 1.7;
        }

        .highlight { color: #000000; font-weight: 800; }
        .grad-text {
            background: linear-gradient(135deg, #000000 0%, #5417D7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        /* GLASS CARD */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-lg);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }
        .glass-card:hover {
            transform: translateY(-6px);
            border-color: rgba(84, 23, 215, 0.3);
            box-shadow: 0 20px 40px rgba(84, 23, 215, 0.08);
        }

        /* BUTTONS */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #000000;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1rem;
            padding: 14px 30px;
            border-radius: 14px;
            text-decoration: none !important;
            border: 1px solid #000000;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-primary:hover {
            background: #5417D7;
            border-color: #5417D7;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(84, 23, 215, 0.25);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #111111 !important;
            font-weight: 700;
            font-size: 1rem;
            padding: 14px 28px;
            border-radius: 14px;
            text-decoration: none !important;
            border: 1px solid rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            border-color: #000000;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .btn-whatsapp {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            background: var(--whatsapp-color);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 18px 32px;
            border-radius: 16px;
            text-decoration: none !important;
            border: 2px solid var(--whatsapp-color);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.28);
            text-align: center;
        }
        .btn-whatsapp span.small { font-size: 0.8rem; font-weight: 500; opacity: 0.95; }
        .btn-whatsapp:hover {
            background: #20ba59;
            border-color: #20ba59;
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(37, 211, 102, 0.35);
        }

        .btn-gmail {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            background: var(--gmail-color);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 18px 32px;
            border-radius: 16px;
            text-decoration: none !important;
            border: 2px solid var(--gmail-color);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(234, 67, 53, 0.28);
            text-align: center;
        }
        .btn-gmail span.small { font-size: 0.8rem; font-weight: 500; opacity: 0.95; }
        .btn-gmail:hover {
            background: #d3382b;
            border-color: #d3382b;
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(234, 67, 53, 0.35);
        }

        /* NAVBAR */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 18px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.4s ease;
        }
        #navbar.scrolled {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding: 12px 5%;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.04);
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none !important; }
        .nav-logo-icon {
            width: 40px;
            height: 40px;
            background: #000000;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.3rem;
            color: #ffffff;
        }
        .nav-logo-text { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.45rem; color: #000000; }
        .nav-logo-text span { color: #555555; }
        .nav-links { display: flex; list-style: none; gap: 26px; }
        .nav-links a {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none !important;
            transition: color 0.3s;
        }
        .nav-links a:hover, .nav-links a.active { color: #000000; }
        .nav-actions { display: flex; align-items: center; gap: 14px; }
        .btn-nav-login {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none !important;
            transition: color 0.3s;
        }
        .btn-nav-login:hover { color: #000000; }
        .btn-nav-signup {
            background: #000000;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 9px 20px;
            border-radius: 12px;
            text-decoration: none !important;
            border: 1px solid #000000;
            transition: all 0.3s ease;
        }
        .btn-nav-signup:hover { background: #5417D7; border-color: #5417D7; transform: translateY(-2px); }

        .nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.6rem;
            color: var(--text-main);
            cursor: pointer;
        }

        /* MOBILE MENU DRAWER */
        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .mobile-menu-overlay.open { opacity: 1; visibility: visible; }
        .mobile-menu {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: min(85vw, 360px);
            background: #ffffff;
            z-index: 1200;
            padding: 30px 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: -10px 0 40px rgba(0, 0, 0, 0.1);
        }
        .mobile-menu.open { transform: translateX(0); }
        .mobile-menu-close {
            align-self: flex-end;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
        }
        .mobile-nav-links { list-style: none; display: flex; flex-direction: column; gap: 18px; margin-top: 20px; }
        .mobile-nav-links a { color: var(--text-main); font-size: 1.05rem; font-weight: 600; text-decoration: none; }
        .mobile-nav-actions { display: flex; flex-direction: column; gap: 12px; margin-top: auto; padding-top: 20px; border-top: 1px solid #eaeaea; }

        /* HERO PARTNER */
        #hero-partner {
            min-height: 72vh;
            display: flex;
            align-items: center;
            padding: 150px 5% 70px;
            position: relative;
            z-index: 1;
            text-align: center;
        }
        .hero-partner-inner {
            max-width: 920px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(84, 23, 215, 0.08);
            border: 1px solid rgba(84, 23, 215, 0.2);
            color: var(--accent);
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 8px 20px;
            border-radius: 50px;
            margin-bottom: 24px;
        }
        .hero-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.2rem, 4.5vw, 3.4rem);
            font-weight: 800;
            line-height: 1.18;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }
        .hero-desc {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.75;
            margin-bottom: 34px;
            max-width: 720px;
        }
        .hero-cta-group {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* SECTION WRAPPERS */
        .section-wrap {
            position: relative;
            z-index: 1;
            padding: 85px 5%;
        }
        .section-wrap.alt {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .section-header { margin-bottom: 50px; }
        .section-header.centered { text-align: center; }
        .section-header.centered .section-subtitle { margin: 0 auto; }

        /* MITRA PARTNERS MARQUEE */
        .mitra-section {
            padding: 30px 5% 50px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .mitra-label {
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: var(--text-dim);
            text-transform: uppercase;
            margin-bottom: 22px;
        }
        .mitra-grid {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 28px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .mitra-item {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 16px;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }
        .mitra-item:hover {
            transform: translateY(-3px);
            border-color: rgba(84, 23, 215, 0.25);
            box-shadow: 0 8px 20px rgba(84, 23, 215, 0.06);
        }
        .mitra-item img {
            max-height: 38px;
            width: auto;
            object-fit: contain;
        }
        .mitra-item span {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-main);
        }

        /* BENEFITS GRID */
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
        }
        .benefit-card {
            padding: 36px 30px;
            display: flex;
            flex-direction: column;
            border-radius: var(--radius-lg);
        }
        .benefit-icon {
            width: 58px;
            height: 58px;
            background: rgba(84, 23, 215, 0.08);
            color: var(--accent);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 22px;
            transition: all 0.3s ease;
        }
        .benefit-card:hover .benefit-icon {
            background: #5417D7;
            color: #ffffff;
            transform: scale(1.08);
        }
        .benefit-card h3 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-main);
        }
        .benefit-card p {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.65;
        }

        /* SUBDOMAIN SHOWCASE GRID */
        .subdomain-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }
        .subdomain-card {
            padding: 32px 26px;
            display: flex;
            flex-direction: column;
            border-radius: var(--radius-lg);
            position: relative;
        }
        .subdomain-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--accent);
            background: rgba(84, 23, 215, 0.08);
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 18px;
            width: fit-content;
        }
        .subdomain-card h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.12rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 10px;
            word-break: break-all;
        }
        .subdomain-card p {
            color: var(--text-muted);
            font-size: 0.88rem;
            line-height: 1.6;
        }

        /* ROLES GRID */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 26px;
        }
        .role-card {
            padding: 36px 30px;
            border-radius: var(--radius-lg);
        }
        .role-number {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--accent);
            opacity: 0.9;
            line-height: 1;
            margin-bottom: 16px;
        }
        .role-card h4 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .role-card p {
            font-size: 0.92rem;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* REVENUE SHARE WRAP */
        .revenue-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
        }
        .revenue-steps {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .rev-step {
            display: flex;
            gap: 18px;
            align-items: flex-start;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            padding: 20px 24px;
            transition: all 0.3s ease;
        }
        .rev-step:hover {
            transform: translateX(6px);
            border-color: rgba(84, 23, 215, 0.3);
            box-shadow: 0 8px 24px rgba(84, 23, 215, 0.08);
        }
        .rev-step-num {
            width: 42px;
            height: 42px;
            background: rgba(84, 23, 215, 0.1);
            color: var(--accent);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .rev-step-text h5 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .rev-step-text p {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.55;
            margin-bottom: 0;
        }

        /* REGISTRATION BOX */
        .registration-box {
            padding: 56px 40px;
            text-align: center;
            max-width: 860px;
            margin: 0 auto;
            border: 1px solid rgba(84, 23, 215, 0.2);
            box-shadow: 0 20px 60px rgba(84, 23, 215, 0.08);
        }
        .registration-box h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.8rem, 3.2vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 14px;
        }
        .registration-box p {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 620px;
            margin: 0 auto 34px;
            line-height: 1.7;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        /* FAQ ACCORDION */
        .faq-accordion {
            max-width: 860px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .faq-item {
            border: 1px solid rgba(0, 0, 0, 0.07);
            border-radius: 18px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .faq-item:hover { border-color: rgba(84, 23, 215, 0.25); }
        .faq-question {
            width: 100%;
            padding: 22px 26px;
            background: none;
            border: none;
            text-align: left;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            gap: 16px;
        }
        .faq-question i {
            font-size: 1.1rem;
            color: var(--accent);
            transition: transform 0.3s ease;
        }
        .faq-question.open i { transform: rotate(180deg); }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, padding 0.35s ease;
            padding: 0 26px;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.7;
        }
        .faq-answer.open {
            max-height: 300px;
            padding-bottom: 22px;
        }

        /* FOOTER */
        footer {
            background: #ffffff;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            padding: 70px 5% 30px;
            position: relative;
            z-index: 1;
        }
        .footer-inner { max-width: 1200px; margin: 0 auto; }
        .footer-top-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 50px;
        }
        .footer-brand p {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.7;
            margin: 14px 0 20px;
            max-width: 320px;
        }
        .footer-socials { display: flex; gap: 10px; }
        .social-btn {
            width: 38px;
            height: 38px;
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .social-btn:hover {
            background: #000000;
            color: #ffffff;
            transform: translateY(-2px);
        }
        .footer-col h5 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 18px;
            color: var(--text-main);
        }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-col a {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-decoration: none;
            transition: color 0.25s;
        }
        .footer-col a:hover { color: var(--accent); }
        .footer-bottom-row {
            padding-top: 30px;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 0.85rem;
            color: var(--text-dim);
        }
        .footer-bottom-badges { display: flex; gap: 16px; flex-wrap: wrap; }
        .badge-secure {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #16a34a;
        }

        /* LOGIN MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .modal-overlay.open { opacity: 1; visibility: visible; }
        .modal-box {
            background: #ffffff;
            border-radius: var(--radius-lg);
            width: min(92vw, 440px);
            padding: 36px 32px;
            position: relative;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        .modal-overlay.open .modal-box { transform: translateY(0); }
        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-dim);
            cursor: pointer;
        }
        .modal-logo {
            width: 44px;
            height: 44px;
            background: #000;
            color: #fff;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 12px;
        }
        .modal-title { font-family: 'Space Grotesk', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 6px; }
        .modal-subtitle { font-size: 0.88rem; color: var(--text-muted); }
        .login-tabs { display: flex; background: rgba(0, 0, 0, 0.04); padding: 4px; border-radius: 12px; margin-bottom: 20px; }
        .login-tab {
            flex: 1;
            padding: 8px;
            border: none;
            background: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.25s;
        }
        .login-tab.active { background: #ffffff; color: var(--text-main); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
        .form-group { margin-bottom: 16px; text-align: left; }
        .form-label { display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px; }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            font-size: 0.92rem;
            outline: none;
            transition: border 0.25s;
        }
        .form-input:focus { border-color: var(--accent); }
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.25s;
            margin-top: 6px;
        }
        .btn-submit:hover { background: var(--accent); }

        /* REVEAL ANIMATION */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        @media (max-width: 992px) {
            .nav-links { display: none; }
            .nav-toggle { display: block; }
            .revenue-wrap { grid-template-columns: 1fr; gap: 36px; }
            .footer-top-row { grid-template-columns: 1fr 1fr; gap: 36px; }
        }
        @media (max-width: 768px) {
            #hero-partner { padding: 130px 5% 50px; }
            .hero-title { font-size: 2.1rem; }
            .section-wrap { padding: 65px 5%; }
            .footer-top-row { grid-template-columns: 1fr; gap: 32px; }
            .registration-box { padding: 40px 22px; }
            .btn-whatsapp, .btn-gmail { width: 100%; }
        }
    </style>
</head>

<body>
    <div class="bg-grid"></div>

    <!-- NAVBAR -->
    <nav id="navbar">
        <a href="{{ url('/') }}" class="nav-logo">
            @if(isset($websiteSettings) && $websiteSettings->logo_path)
                <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $siteName }}"
                    style="max-height: 52px; margin-right: 8px; width: auto; object-fit: contain;">
            @else
                <div class="nav-logo-icon">L</div>
                <span class="nav-logo-text">{{ $siteName }}</span>
            @endif
        </a>
        <ul class="nav-links">
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li><a href="{{ route('premium.katalog') }}">Katalog Produk</a></li>
            <li><a href="#kelebihan">Kelebihan</a></li>
            <li><a href="#showcase">Subdomain</a></li>
            <li><a href="#roles">Peran Mitra</a></li>
            <li><a href="#skema">Bagi Hasil</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="{{ route('daftar.seller') }}">Jadi Seller</a></li>
            <li><a href="{{ route('join.partner') }}" class="active">Join Partner</a></li>
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
                <a href="#" class="btn-nav-login" onclick="openModal(); return false;">Masuk</a>
                <a href="{{ url('/pendaftaran') }}" class="btn-nav-signup">Daftar Akun</a>
            @endauth
        </div>
        <button class="nav-toggle" onclick="toggleMobileMenu()" aria-label="Toggle Navigation">
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
            <li><a href="#kelebihan" onclick="closeMobileMenu()">Kelebihan Kemitraan</a></li>
            <li><a href="#showcase" onclick="closeMobileMenu()">Contoh Subdomain</a></li>
            <li><a href="#roles" onclick="closeMobileMenu()">Tugas &amp; Peran</a></li>
            <li><a href="#skema" onclick="closeMobileMenu()">Skema Bagi Hasil</a></li>
            <li><a href="#faq" onclick="closeMobileMenu()">FAQ Partner</a></li>
            <li><a href="{{ route('daftar.seller') }}" onclick="closeMobileMenu()">Jadi Seller</a></li>
            <li><a href="{{ route('join.partner') }}" onclick="closeMobileMenu()">Join Partner</a></li>
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
                <a href="#" class="btn-nav-login" onclick="openModal(); closeMobileMenu(); return false;">Masuk</a>
                <a href="{{ url('/pendaftaran') }}" class="btn-nav-signup" onclick="closeMobileMenu()">Daftar Akun</a>
            @endauth
        </div>
    </div>

    <!-- HERO SECTION -->
    <header id="hero-partner">
        <div class="hero-partner-inner">
            <div class="hero-badge reveal">
                <i class="bi bi-people-fill"></i> Kemitraan Komunitas &amp; Store Regional
            </div>
            <h1 class="hero-title reveal" style="transition-delay: .1s;">
                Buka Hub E-Commerce Komunitas Anda<br>
                <span class="grad-text">100% Gratis Tanpa Biaya Server</span>
            </h1>
            <p class="hero-desc reveal" style="transition-delay: .2s;">
                Miliki platform e-commerce produk digital, source code, dan voucher dengan brand komunitas Anda sendiri. Kami sediakan website subdomain khusus secara cuma-cuma, sistem backend otomatis, dan Anda menikmati <strong>bagi hasil komisi transaksi otomatis</strong>!
            </p>
            <div class="hero-cta-group reveal" style="transition-delay: .3s;">
                <a href="#register" class="btn-primary"><i class="bi bi-send-fill"></i> Ajukan Kemitraan Sekarang</a>
                <a href="#showcase" class="btn-secondary"><i class="bi bi-globe"></i> Contoh Subdomain</a>
            </div>
        </div>
    </header>

    <!-- DYNAMIC MITRA INDUSTRI SECTION -->
    @if(isset($mitras) && $mitras->count() > 0)
    <section class="mitra-section reveal">
        <div class="mitra-label">Didukung &amp; Berkolaborasi Bersama Ekosistem Pendidikan &amp; Industri</div>
        <div class="mitra-grid">
            @foreach($mitras as $mitra)
                <div class="mitra-item">
                    @if($mitra->image_path && file_exists(public_path($mitra->image_path)))
                        <img src="{{ asset($mitra->image_path) }}" alt="{{ $mitra->name }}">
                    @else
                        <i class="bi bi-building-check text-primary" style="font-size: 1.4rem;"></i>
                    @endif
                    <span>{{ $mitra->name }}</span>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- KELEBIHAN KEMITRAAN SECTION -->
    <section id="kelebihan" class="section-wrap alt">
        <div class="container-custom">
            <div class="section-header centered reveal">
                <div class="section-tag"><i class="bi bi-star-fill"></i> Mengapa Join Partner?</div>
                <h2 class="section-title">Solusi All-in-One untuk <span class="highlight">Komunitas Anda</span></h2>
                <p class="section-subtitle">Dukung penuh para kreator dan seller digital lokal di komunitas Anda dengan menyediakan marketplace resmi yang kredibel.</p>
            </div>
            <div class="benefits-grid">
                <div class="glass-card benefit-card reveal">
                    <div class="benefit-icon"><i class="bi bi-browser-chrome"></i></div>
                    <h3>Website Komunitas Instan</h3>
                    <p>Website khusus dengan branding komunitas Anda siap online secara instan tanpa perlu repot koding, setup domain, ataupun sewa server.</p>
                </div>
                <div class="glass-card benefit-card reveal" style="transition-delay: .1s;">
                    <div class="benefit-icon"><i class="bi bi-people-fill"></i></div>
                    <h3>Pemberdayaan Seller Komunitas</h3>
                    <p>Wadahi seller digital di dalam komunitas Anda (gaming, desain grafis, source code, jasa freelance) agar memiliki pasar resmi yang aman.</p>
                </div>
                <div class="glass-card benefit-card reveal" style="transition-delay: .2s;">
                    <div class="benefit-icon"><i class="bi bi-cash-coin"></i></div>
                    <h3>Bagi Hasil Transparan</h3>
                    <p>Tidak ada biaya bulanan. Kemitraan dibiayai murni dari persentase bagi hasil transaksi otomatis yang sangat adil dan dapat dicairkan kapan saja.</p>
                </div>
                <div class="glass-card benefit-card reveal" style="transition-delay: .3s;">
                    <div class="benefit-icon"><i class="bi bi-qr-code-scan"></i></div>
                    <h3>Multi Payment Gateway Otomatis</h3>
                    <p>Seluruh transaksi pembeli diverifikasi otomatis via QRIS instan, Transfer Bank, dan e-Wallet tanpa butuh konfirmasi struk manual.</p>
                </div>
                <div class="glass-card benefit-card reveal" style="transition-delay: .4s;">
                    <div class="benefit-icon"><i class="bi bi-shield-check"></i></div>
                    <h3>Pemeliharaan Server Berkala</h3>
                    <p>Tim IT {{ $siteName }} merawat performa server, keamanan data, dan audit sistem secara rutin demi stabilitas toko komunitas Anda.</p>
                </div>
                <div class="glass-card benefit-card reveal" style="transition-delay: .5s;">
                    <div class="benefit-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                    <h3>Laporan Real-time</h3>
                    <p>Pantau trafik kunjungan toko komunitas Anda, volume transaksi, dan akumulasi saldo bagi hasil komisi secara transparan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SUBDOMAIN SHOWCASE SECTION -->
    <section id="showcase" class="section-wrap">
        <div class="container-custom">
            <div class="section-header centered reveal">
                <div class="section-tag"><i class="bi bi-globe-americas"></i> Subdomain Khusus</div>
                <h2 class="section-title">Contoh Identitas Toko <span class="highlight">Komunitas Anda</span></h2>
                <p class="section-subtitle">Setiap partner komunitas akan mendapatkan alamat website khusus dengan subdomain yang disesuaikan secara cuma-cuma.</p>
            </div>
            <div class="subdomain-grid">
                <div class="glass-card subdomain-card reveal">
                    <span class="subdomain-badge"><i class="bi bi-geo-alt-fill"></i> Komunitas Regional</span>
                    <h4>nusabogor.{{ $currentHost }}</h4>
                    <p>Website marketplace yang dirancang khusus untuk menampung para seller dan kreator digital regional Bogor dan sekitarnya.</p>
                </div>
                <div class="glass-card subdomain-card reveal" style="transition-delay: .1s;">
                    <span class="subdomain-badge"><i class="bi bi-controller"></i> Komunitas Gamers</span>
                    <h4>gamerstore.{{ $currentHost }}</h4>
                    <p>Subdomain khusus yang dirancang untuk komunitas game, mewadahi seller akun, top-up, dan voucher game internal.</p>
                </div>
                <div class="glass-card subdomain-card reveal" style="transition-delay: .2s;">
                    <span class="subdomain-badge"><i class="bi bi-palette-fill"></i> Komunitas Kreatif</span>
                    <h4>kreatifhub.{{ $currentHost }}</h4>
                    <p>Dukungan platform untuk template desain, foto, ilustrasi, font, dan aset kreatif karya anggota komunitas.</p>
                </div>
                <div class="glass-card subdomain-card reveal" style="transition-delay: .3s;">
                    <span class="subdomain-badge"><i class="bi bi-mortarboard-fill"></i> Sekolah &amp; Kampus</span>
                    <h4>smkstore.{{ $currentHost }}</h4>
                    <p>Wadah teaching factory dan e-commerce karya siswa jurusan RPL, Multimedia, dan IT sekolah/kampus.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ROLES & RESPONSIBILITIES SECTION -->
    <section id="roles" class="section-wrap alt">
        <div class="container-custom">
            <div class="section-header centered reveal">
                <div class="section-tag"><i class="bi bi-briefcase-fill"></i> Tugas &amp; Peran</div>
                <h2 class="section-title">Bagaimana Peran <span class="highlight">Mitra Partner</span>?</h2>
                <p class="section-subtitle">Sebagai mitra, Anda bertindak sebagai penggerak utama ekosistem digital di komunitas Anda.</p>
            </div>
            <div class="roles-grid">
                <div class="glass-card role-card reveal">
                    <div class="role-number">01</div>
                    <h4>Rekrut &amp; Ajak Seller</h4>
                    <p>Tugas utama Anda adalah menemukan dan mengajak para seller serta kreator digital di komunitas Anda untuk memajang produk mereka di website toko komunitas.</p>
                </div>
                <div class="glass-card role-card reveal" style="transition-delay: .1s;">
                    <div class="role-number">02</div>
                    <h4>Kurasi &amp; Jaga Mutu</h4>
                    <p>Bertindak sebagai kurator untuk memastikan produk yang dijual anggota komunitas memiliki kualitas baik dan mematuhi ketentuan legalitas platform.</p>
                </div>
                <div class="glass-card role-card reveal" style="transition-delay: .2s;">
                    <div class="role-number">03</div>
                    <h4>Promosikan Website Toko</h4>
                    <p>Sebarluaskan alamat website subdomain komunitas Anda kepada seluruh anggota komunitas agar seluruh transaksi dilakukan secara aman dan resmi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- REVENUE SHARING SECTION -->
    <section id="skema" class="section-wrap">
        <div class="container-custom">
            <div class="revenue-wrap">
                <div class="reveal">
                    <div class="section-tag"><i class="bi bi-percent"></i> Skema Pembagian Hasil</div>
                    <h2 class="section-title" style="font-size: 2.2rem;">Sistem Bagi Hasil <span class="highlight">Komisi Otomatis</span></h2>
                    <p style="color:var(--text-muted); margin-bottom: 24px; line-height: 1.7;">
                        Kemitraan ini didesain 100% gratis tanpa biaya bulanan. Pendapatan Anda bersumber langsung dari persentase komisi transaksi yang dilakukan oleh seller di website komunitas Anda.
                    </p>
                    <div class="glass-card" style="padding: 24px;">
                        <div style="font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.1rem; margin-bottom:10px; color:#000;">
                            Alur Distribusi Keuntungan:
                        </div>
                        <ul style="list-style:none; padding:0; display:flex; flex-direction:column; gap:10px; font-size:0.92rem; color:var(--text-muted);">
                            <li><i class="bi bi-check-circle-fill text-success" style="margin-right:8px;"></i> Website store komunitas memproses transaksi seller.</li>
                            <li><i class="bi bi-check-circle-fill text-success" style="margin-right:8px;"></i> Pembayaran otomatis diverifikasi via gateway terpercaya.</li>
                            <li><i class="bi bi-check-circle-fill text-success" style="margin-right:8px;"></i> Persentase bagi hasil komisi dialokasikan langsung ke saldo mitra.</li>
                            <li><i class="bi bi-check-circle-fill text-success" style="margin-right:8px;"></i> Saldo komisi dapat ditarik (*withdraw*) berkala ke rekening bank.</li>
                        </ul>
                    </div>
                </div>
                <div class="revenue-steps reveal" style="transition-delay: .15s;">
                    <div class="rev-step">
                        <div class="rev-step-num">1</div>
                        <div class="rev-step-text">
                            <h5>Ajukan Kemitraan Komunitas</h5>
                            <p>Daftarkan komunitas Anda dan ajukan nama subdomain khusus yang Anda inginkan secara gratis.</p>
                        </div>
                    </div>
                    <div class="rev-step">
                        <div class="rev-step-num">2</div>
                        <div class="rev-step-text">
                            <h5>Aktivasi Website Toko</h5>
                            <p>Tim IT {{ $siteName }} menyiapkan subdomain komunitas Anda dalam waktu cepat.</p>
                        </div>
                    </div>
                    <div class="rev-step">
                        <div class="rev-step-num">3</div>
                        <div class="rev-step-text">
                            <h5>Ajak Seller Berjualan</h5>
                            <p>Pandu seller digital di komunitas Anda untuk mendaftar dan memajang produk mereka.</p>
                        </div>
                    </div>
                    <div class="rev-step">
                        <div class="rev-step-num">4</div>
                        <div class="rev-step-text">
                            <h5>Nikmati Bagi Hasil Komisi</h5>
                            <p>Setiap transaksi sukses di website Anda menghasilkan saldo bagi hasil komisi otomatis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="section-wrap alt">
        <div class="container-custom">
            <div class="section-header centered reveal">
                <div class="section-tag"><i class="bi bi-question-circle-fill"></i> Tanya Jawab</div>
                <h2 class="section-title">Pertanyaan Seputar <span class="highlight">Join Partner</span></h2>
                <p class="section-subtitle">Pelajari seluk-beluk program kemitraan komunitas store dan cara memulai kolaborasi bersama kami.</p>
            </div>
            <div class="faq-accordion reveal">
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span>Apa itu program Join Partner Komunitas {{ $siteName }}?</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        Program kemitraan resmi yang memungkinkan komunitas (regional, gamer, hobi, kampus, atau sekolah) memiliki website e-commerce digital sendiri dengan subdomain khusus secara gratis tanpa biaya server.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span>Apakah ada biaya pembuatan website atau biaya bulanan?</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        Sama sekali tidak ada biaya setup, instalasi, maupun sewa bulanan (100% Gratis). Kemitraan berjalan murni dengan sistem pembagian hasil komisi transaksi yang adil dan transparan.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span>Siapa yang mengelola server dan sistem pembayarannya?</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        Seluruh infrastruktur server, payment gateway (QRIS, Bank, e-Wallet), keamanan enkripsi, dan otomasi pengiriman produk ditangani penuh oleh tim {{ $siteName }}. Mitra cukup fokus merangkul seller dan mempromosikan toko komunitas.
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span>Bagaimana cara mencairkan saldo bagi hasil komisi mitra?</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        Bagi hasil komisi dihitung secara real-time dari setiap transaksi sukses di website komunitas Anda dan dapat dicairkan (*withdraw*) langsung ke rekening bank mitra kapan saja.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT REGISTRATION SECTION -->
    <section id="register" class="section-wrap">
        <div class="container-custom">
            <div class="glass-card registration-box reveal">
                <div class="section-tag" style="margin-bottom: 16px;"><i class="bi bi-send-fill"></i> Pendaftaran Kemitraan</div>
                <h2>Mulai Hub E-Commerce Komunitas Anda Sekarang</h2>
                <p>Diskusikan pendaftaran kemitraan komunitas store Anda bersama tim representatif dari {{ $siteName }}. Hubungi kami melalui salah satu kontak di bawah ini.</p>
                <div class="action-buttons">
                    <a href="https://wa.me/{{ $phoneClean }}?text=Halo%20Tim%20{{ urlencode($siteName) }}%2C%20saya%20tertarik%20untuk%20join%20partner%20community%20store%20tim%20atau%20komunitas%20saya.%20Mohon%20informasi%20langkah%20selanjutnya." target="_blank" class="btn-whatsapp">
                        <span><i class="bi bi-whatsapp" style="font-size: 1.4rem; margin-right: 6px;"></i> Hubungi via WhatsApp</span>
                        <span class="small">Respon Cepat (Jam Kerja 09:00 - 21:00 WIB)</span>
                    </a>
                    <a href="mailto:{{ $contactEmail }}?subject=Permohonan%20Kemitraan%20Join%20Partner%20{{ urlencode($siteName) }}&body=Halo%20Tim%20Kemitraan%20{{ urlencode($siteName) }}%2C%0A%0ASaya%20mewakili%20komunitas%20ingin%20mengajukan%20kemitraan%20Join%20Partner%20Community%20Store.%0A-%20Nama%20Komunitas%3A%20%0A-%20Usulan%20Nama%20Subdomain%3A%20%0A-%20Perkiraan%20Jumlah%20Seller%20Komunitas%3A%20%0A%0AMohon%20tindak%20lanjutnya.%20Terima%20kasih." class="btn-gmail">
                        <span><i class="bi bi-envelope-fill" style="font-size: 1.4rem; margin-right: 6px;"></i> Hubungi via Email</span>
                        <span class="small">Respon Resmi &amp; Penawaran Kerjasama</span>
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
                        @if(isset($websiteSettings) && $websiteSettings->logo_path)
                            <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $siteName }}"
                                style="max-height: 48px; margin-right: 8px; width: auto; object-fit: contain;">
                        @else
                            <div class="nav-logo-icon">L</div>
                            <span class="nav-logo-text">{{ $siteName }}</span>
                        @endif
                    </a>
                    <p>{{ $siteDesc }}</p>
                    <div class="footer-socials">
                        <a href="https://instagram.com/nusagarudastudio" target="_blank" class="social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://wa.me/{{ $phoneClean }}" target="_blank" class="social-btn" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://t.me/nusagarudastudio" target="_blank" class="social-btn" aria-label="Telegram"><i class="bi bi-telegram"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h5>Layanan</h5>
                    <ul>
                        <li><a href="{{ route('premium.katalog') }}">Semua Katalog Produk</a></li>
                        <li><a href="{{ route('daftar_toko') }}">Daftar Toko / Jurusan</a></li>
                        <li><a href="{{ route('premium.katalog', ['kategori' => 'premium']) }}">Spotify &amp; Netflix Premium</a></li>
                        <li><a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}">Source Code &amp; File Digital</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Perusahaan</h5>
                    <ul>
                        <li><a href="{{ url('/') }}#visimisi">Tentang {{ $siteName }}</a></li>
                        <li><a href="{{ url('/') }}#visimisi">Visi &amp; Misi</a></li>
                        <li><a href="{{ url('/') }}#cara-kerja">Cara Kerja</a></li>
                        <li><a href="{{ url('/') }}#testimoni">Testimoni</a></li>
                        <li><a href="{{ url('/') }}#faq">FAQ Umum</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Dukungan &amp; Kemitraan</h5>
                    <ul>
                        <li><a href="#faq">FAQ Partner</a></li>
                        <li><a href="{{ route('kebijakan.privasi') }}">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('syarat.ketentuan') }}">Syarat &amp; Ketentuan</a></li>
                        <li><a href="{{ route('daftar.seller') }}">Daftar Jadi Seller</a></li>
                        <li><a href="{{ route('join.partner') }}">Join Partner Komunitas</a></li>
                        <li><a href="https://wa.me/{{ $phoneClean }}?text=Halo%20Admin%20{{ urlencode($siteName) }},%20saya%20ingin%20bertanya%20seputar%20kemitraan%20partner." target="_blank">Hubungi Admin</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom-row">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="footer-bottom-badges">
                    <div class="badge-secure"><i class="bi bi-shield-check-fill"></i> Pembayaran Aman</div>
                    <div class="badge-secure"><i class="bi bi-lock-fill"></i> Data Terenkripsi</div>
                    <div class="badge-secure"><i class="bi bi-patch-check-fill"></i> Terverifikasi Resmi</div>
                </div>
            </div>
        </div>
    </footer>

    <!-- LOGIN MODAL -->
    <div class="modal-overlay" id="login-modal" onclick="closeModalOverlay(event)">
        <div class="modal-box">
            <button class="modal-close" onclick="closeModal()" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
            <div style="text-align:center;margin-bottom:20px;">
                <div class="modal-logo">L</div>
                <div class="modal-title">Masuk ke {{ $siteName }}</div>
                <div class="modal-subtitle">Akses akun pelanggan atau dashboard mitra Anda</div>
            </div>
            <div class="login-tabs">
                <button class="login-tab active" onclick="switchModalTab(this, 'customer')">&#128722; Pelanggan</button>
                <button class="login-tab" onclick="switchModalTab(this, 'admin')">&#128737; Admin / Seller</button>
            </div>
            <form action="{{ url('/proses_login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" id="modal-username-label">Email Pelanggan</label>
                    <input type="email" name="email" id="modal-email" class="form-input" placeholder="Masukkan email Anda" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div style="position:relative;">
                        <input type="password" name="password" id="modal-password" class="form-input" placeholder="Masukkan kata sandi" required>
                        <i class="bi bi-eye" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#888;" onclick="togglePassword()"></i>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Masuk Sekarang</button>
            </form>
            <div style="text-align:center;margin-top:16px;font-size:0.85rem;color:var(--text-muted);">
                Belum punya akun? <a href="{{ url('/pendaftaran') }}" style="color:var(--accent);font-weight:700;text-decoration:none;">Daftar Akun Gratis</a>
            </div>
        </div>
    </div>

    <script>
        // MOBILE MENU
        window.toggleMobileMenu = function () {
            document.getElementById('mobileMenu').classList.toggle('open');
            document.getElementById('mobileMenuOverlay').classList.toggle('open');
            document.body.style.overflow = document.getElementById('mobileMenu').classList.contains('open') ? 'hidden' : '';
        };
        window.closeMobileMenu = function () {
            document.getElementById('mobileMenu').classList.remove('open');
            document.getElementById('mobileMenuOverlay').classList.remove('open');
            document.body.style.overflow = '';
        };

        // NAVBAR SCROLL
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // MODAL
        const tabLabels = { customer: 'Email Pelanggan', admin: 'Email Admin / Seller' };
        window.switchModalTab = function (btn, role) {
            document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('modal-username-label').textContent = tabLabels[role];
            document.getElementById('modal-email').placeholder = 'Masukkan ' + tabLabels[role].toLowerCase();
        };
        window.openModal = function () {
            document.getElementById('login-modal').classList.add('open');
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('modal-email').focus(), 120);
        };
        window.closeModal = function () {
            document.getElementById('login-modal').classList.remove('open');
            document.body.style.overflow = '';
        };
        window.closeModalOverlay = function (e) {
            if (e.target.id === 'login-modal') closeModal();
        };
        window.togglePassword = function () {
            const f = document.getElementById('modal-password');
            f.type = f.type === 'password' ? 'text' : 'password';
        };

        // FAQ
        window.toggleFaq = function (btn) {
            const answer = btn.nextElementSibling;
            const isOpen = answer.classList.contains('open');
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
            document.querySelectorAll('.faq-question').forEach(q => q.classList.remove('open'));
            if (!isOpen) {
                answer.classList.add('open');
                btn.classList.add('open');
            }
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
        }, { threshold: 0.1 });
        reveals.forEach(r => observer.observe(r));

        // SMOOTH SCROLL
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '') return;
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>

</html>
