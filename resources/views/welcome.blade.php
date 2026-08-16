<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($websiteSettings) ? $websiteSettings->site_name : 'Lapaktifikasi' }} - {{ isset($websiteSettings) ? $websiteSettings->site_description : 'Solusi Terbaik' }}</title>
    <meta name="description" content="{{ isset($websiteSettings) ? $websiteSettings->site_description : 'Lapaktifikasi - Platform Jasa Digital' }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ isset($websiteSettings) && $websiteSettings->favicon_path ? asset($websiteSettings->favicon_path) : asset('assets/img/favicon.png') }}">
    <meta name="keywords" content="akun premium, smk plus pelita nusantara, lapaktifikasi, beli akun premium">
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
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -10;
            background-color: #f4f6f9;
            background-image: 
                radial-gradient(at 40% 20%, hsla(220,100%,80%,0.2) 0px, transparent 50%),
                radial-gradient(at 80% 0%, hsla(280,100%,80%,0.2) 0px, transparent 50%),
                radial-gradient(at 0% 50%, hsla(180,100%,80%,0.2) 0px, transparent 50%),
                radial-gradient(at 80% 50%, hsla(320,100%,80%,0.2) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(40,100%,80%,0.2) 0px, transparent 50%),
                radial-gradient(at 80% 100%, hsla(120,100%,80%,0.2) 0px, transparent 50%);
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
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

        /* BG BLOBS */
        .bg-blobs { display: none; }
        .bg-grid { position: fixed; inset: 0; z-index: 0; pointer-events: none; background-image: linear-gradient(rgba(0,0,0,0.015) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.015) 1px, transparent 1px); background-size: 60px 60px; }

        /* UTILITY */
        .container-custom { max-width: 1200px; margin: 0 auto; padding: 0 5%; }
        .section-tag { display: inline-flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.12); color: var(--text-main); font-size: 0.8rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; padding: 7px 16px; border-radius: 50px; margin-bottom: 20px; }
        .section-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 700; line-height: 1.2; color: var(--text-main); margin-bottom: 16px; }
        .section-subtitle { font-size: 1.05rem; color: var(--text-muted); max-width: 560px; line-height: 1.7; }
        .highlight { color: #000000; font-weight: 800; }
        .highlight-cyan { color: #000000; font-weight: 800; }
        .grad-text { background: none; -webkit-background-clip: unset; -webkit-text-fill-color: currentColor; background-clip: unset; color: #000000; font-weight: 800; }

        /* GLASS CARD */
        .glass-card { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(20px) saturate(180%); -webkit-backdrop-filter: blur(20px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.5); border-radius: var(--radius-lg); transition: all 0.35s ease; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.03); transform: translateZ(0); will-change: transform; }
        .glass-card:hover { transform: translateY(-6px) translateZ(0); border-color: rgba(255, 255, 255, 0.8); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

        /* BUTTONS */
        .btn-primary { display: inline-flex; align-items: center; gap: 10px; background: #000000; color: #ffffff !important; font-weight: 700; font-size: 1rem; padding: 15px 32px; border-radius: 14px; text-decoration: none !important; border: 1px solid #000000; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-primary:hover { background: transparent; color: #000000 !important; transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-secondary { display: inline-flex; align-items: center; gap: 10px; background: transparent; border: 1px solid #000000; color: #000000 !important; font-weight: 600; font-size: 1rem; padding: 14px 30px; border-radius: 14px; text-decoration: none !important; cursor: pointer; transition: all 0.3s ease; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-secondary:hover { background: #000000; color: #ffffff !important; transform: translateY(-2px); }

        /* NAVBAR */
        #navbar { position: fixed; top: 0; left: 0; right: 0; margin: 0 auto; width: 100%; max-width: 100%; z-index: 1000; padding: 20px 5%; display: flex; align-items: center; justify-content: space-between; transition: all 0.4s ease; transform: translateZ(0); will-change: transform, width, top; }
        #navbar.scrolled { top: 20px; width: 92%; max-width: 1100px; background: rgba(255, 255, 255, 0.45); backdrop-filter: blur(12px) saturate(180%); -webkit-backdrop-filter: blur(12px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.6); border-radius: 50px; padding: 12px 30px; box-shadow: 0 8px 32px rgba(0,0,0,0.06); }
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

        /* HERO */
        #hero { min-height: 100vh; display: flex; align-items: center; padding: 160px 5% 80px; position: relative; z-index: 1; }
        .hero-inner { display: flex; align-items: center; gap: 60px; max-width: 1200px; margin: 0 auto; width: 100%; }
        .hero-content { flex: 1; }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.12); color: #000000; font-size: 0.82rem; font-weight: 700; letter-spacing: 1px; padding: 8px 18px; border-radius: 50px; margin-bottom: 28px; animation: fadeInDown 0.7s ease both; }
        .hero-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.7rem, 3.2vw, 2.4rem); font-weight: 700; line-height: 1.2; margin-bottom: 24px; animation: fadeInUp 0.7s ease 0.1s both; }
        .hero-desc { font-size: 1.1rem; color: var(--text-muted); line-height: 1.75; margin-bottom: 42px; max-width: 500px; animation: fadeInUp 0.7s ease 0.2s both; }
        .hero-cta { display: flex; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 48px; animation: fadeInUp 0.7s ease 0.3s both; }
        .hero-trust { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; animation: fadeInUp 0.7s ease 0.4s both; }
        .trust-item { display: flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 0.85rem; font-weight: 500; }
        .trust-item i { color: #000000; }
        /* HERO 3-CARD LAYOUT */
        .hero-3card-container { display: flex; align-items: center; justify-content: center; gap: 10px; position: relative; padding: 10px 0; width: 100%; max-width: 100%; }
        .hero-3card-container::before { content: ''; position: absolute; width: 260px; height: 260px; background: rgba(0,0,0,0.025); border: 1px solid rgba(0,0,0,0.04); border-radius: 50%; z-index: 0; pointer-events: none; }
        .hero-3card { flex: 1; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.9); border-radius: 18px; padding: 14px 8px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between; position: relative; z-index: 1; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.04); min-height: 220px; }
        .hero-3card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .hero-3card-featured { transform: scale(1.03) translateY(-4px); z-index: 2; border: 1.5px solid #000000; background: #ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.1); min-height: 235px; }
        .hero-3card-featured:hover { transform: scale(1.05) translateY(-5px); }
        .hero-3card-img { width: 54px; height: 54px; border-radius: 14px; background: #f8fafc; border: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; padding: 8px; flex-shrink: 0; }
        .hero-3card-img img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .hero-3card h5 { font-family: 'Space Grotesk', sans-serif; font-size: 0.78rem; font-weight: 700; color: #000000; margin-bottom: 6px; line-height: 1.25; text-align: center; word-break: break-word; }
        .hero-3card-lines { width: 75%; display: flex; flex-direction: column; gap: 4px; margin-bottom: 10px; align-items: center; }
        .hero-3card-lines span { height: 3px; border-radius: 4px; background: #e2e8f0; display: block; }
        .hero-3card-lines span:nth-child(1) { width: 100%; }
        .hero-3card-lines span:nth-child(2) { width: 60%; }
        .btn-3card { width: 100%; padding: 6px 8px; background: #000000; color: #ffffff !important; font-size: 0.75rem; font-weight: 700; border-radius: 8px; text-decoration: none !important; transition: all 0.2s ease; border: 1px solid #000000; display: inline-block; }
        .btn-3card:hover { background: transparent; color: #000000 !important; }
        .hero-cards-mini { display: flex; flex-direction: row; gap: 8px; width: 100%; margin-top: 10px; }
        .mini-card { flex: 1; padding: 12px 6px; border-radius: 16px; text-align: center; background: rgba(255, 255, 255, 0.75); border: 1px solid rgba(255, 255, 255, 0.9); }
        .mini-card .num { font-family: 'Space Grotesk', sans-serif; font-size: 1.2rem; font-weight: 700; color: #000000; line-height: 1.1; }
        .mini-card .lbl { font-size: 0.7rem; color: var(--text-muted); margin-top: 3px; line-height: 1.2; }

        /* STATS */
        #stats { position: relative; z-index: 1; padding: 0 5% 80px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 20px; max-width: 1200px; margin: 0 auto; }
        .stat-card { padding: 32px 28px; text-align: center; }
        .stat-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin: 0 auto 16px; background: #fafafa; border: 1px solid #e5e5e5; }
        .stat-num { font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem; font-weight: 700; color: #000000; line-height: 1; margin-bottom: 6px; }
        .stat-label { font-size: 0.85rem; color: var(--text-muted); }

        /* SECTION WRAPPERS */
        .section-wrap { position: relative; z-index: 1; padding: 100px 5%; }
        .section-wrap.alt { background: rgba(255, 255, 255, 0.3); border-top: 1px solid rgba(255, 255, 255, 0.4); border-bottom: 1px solid rgba(255, 255, 255, 0.4); backdrop-filter: blur(10px); transform: translateZ(0); will-change: transform; }
        .section-header { margin-bottom: 64px; }
        .section-header.centered { text-align: center; }
        .section-header.centered .section-subtitle { margin: 0 auto; }

        /* FEATURES */
        .features-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; max-width: 1200px; margin: 0 auto; }
        .feature-card { padding: 36px 30px; }
        .feature-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 24px; background: #fafafa; border: 1px solid #000000; color: #000000; }
        .feature-card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.15rem; font-weight: 700; margin-bottom: 12px; }
        .feature-card p { color: var(--text-muted); font-size: 0.92rem; line-height: 1.7; }
        .fi-1, .fi-2, .fi-3, .fi-4, .fi-5, .fi-6 { background: #fafafa; border: 1px solid #000000; color: #000000; }

        /* VISI MISI */
        .vm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; max-width: 1200px; margin: 0 auto; }
        .vm-card { padding: 44px 40px; }
        .vm-icon { font-size: 2.4rem; margin-bottom: 20px; }
        .vm-card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 16px; color: #000000; }
        .vm-card p { color: var(--text-muted); line-height: 1.8; font-size: 0.97rem; }
        .vm-card ul { list-style: none; margin-top: 16px; }
        .vm-card ul li { display: flex; align-items: flex-start; gap: 12px; color: var(--text-muted); font-size: 0.95rem; margin-bottom: 12px; }
        .vm-card ul li i { color: #000000; margin-top: 3px; flex-shrink: 0; }
        .vm-visi { border-top: 3px solid #000000; }
        .vm-misi { border-top: 3px dashed #000000; }

        /* CARA KERJA */
        .steps-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 24px; max-width: 1200px; margin: 0 auto; position: relative; }
        .steps-grid::before { content: ''; position: absolute; top: 36px; left: 12.5%; right: 12.5%; height: 2px; background: #e5e5e5; z-index: 0; }
        .step-card { padding: 36px 24px; text-align: center; position: relative; z-index: 1; }
        .step-num { width: 56px; height: 56px; border-radius: 50%; background: #000000; color: #ffffff; font-family: 'Space Grotesk', sans-serif; font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .step-card h4 { font-family: 'Space Grotesk', sans-serif; font-size: 1rem; font-weight: 700; margin-bottom: 10px; }
        .step-card p { color: var(--text-muted); font-size: 0.88rem; line-height: 1.65; }

        /* PRODUCTS */
        .products-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; max-width: 1200px; margin: 0 auto; }
        .product-card { padding: 32px 28px; }
        .product-card-icon { width: 56px; height: 56px; border-radius: 16px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; padding: 10px; }
        .product-card-icon img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .product-card h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.15rem; font-weight: 700; margin-bottom: 8px; }
        .product-card p { color: var(--text-muted); font-size: 0.88rem; line-height: 1.6; margin-bottom: 20px; }
        .product-card-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid var(--glass-border); }
        .product-card-price small { color: var(--text-muted); font-size: 0.75rem; display: block; }
        .product-card-price strong { color: #000000; font-size: 1.1rem; font-weight: 700; }
        .btn-see { font-size: 0.82rem; font-weight: 600; color: #000000; text-decoration: none !important; border: 1px solid #000000; padding: 7px 16px; border-radius: 10px; transition: all 0.3s; }
        .btn-see:hover { background: #000000; color: #ffffff !important; }

        /* TESTIMONI MARQUEE */
        .testi-marquee-wrap { overflow: hidden; position: relative; width: 100%; padding: 10px 0; }
        .testi-marquee-wrap::before, .testi-marquee-wrap::after { content: ''; position: absolute; top: 0; bottom: 0; width: 80px; z-index: 2; pointer-events: none; }
        .testi-marquee-wrap::before { left: 0; background: linear-gradient(to right, rgba(255,255,255,0.9), transparent); }
        .testi-marquee-wrap::after { right: 0; background: linear-gradient(to left, rgba(255,255,255,0.9), transparent); }
        .testi-marquee { display: flex; width: max-content; animation: testimoniScroll 35s linear infinite; }
        .testi-marquee:hover { animation-play-state: paused; }
        .testi-marquee-group { display: flex; gap: 24px; padding-right: 24px; }
        .testi-card { width: 360px; min-width: 360px; padding: 32px 28px; border-radius: var(--radius-lg); flex-shrink: 0; }
        .testi-stars { color: #000000; font-size: 0.9rem; margin-bottom: 16px; letter-spacing: 3px; }
        .testi-text { color: var(--text-muted); font-size: 0.93rem; line-height: 1.75; font-style: italic; margin-bottom: 24px; }
        .testi-author { display: flex; align-items: center; gap: 14px; }
        .testi-avatar { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; flex-shrink: 0; background: #fafafa; border: 1px solid #e5e5e5; }
        .testi-name { font-weight: 700; font-size: 0.9rem; }
        .testi-role { color: var(--text-dim); font-size: 0.8rem; margin-top: 2px; }

        @keyframes testimoniScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* FAQ */
        .faq-wrap { max-width: 800px; margin: 0 auto; }
        .faq-item { margin-bottom: 14px; border-radius: 16px; overflow: hidden; }
        .faq-question { width: 100%; background: #ffffff; border: 1px solid #000000; color: var(--text-main); font-size: 0.98rem; font-weight: 600; text-align: left; padding: 20px 24px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 16px; transition: all 0.3s; border-radius: 16px; font-family: 'Plus Jakarta Sans', sans-serif; }
        .faq-question:hover, .faq-question.open { background: #fafafa; border-color: #000000; }
        .faq-question.open { border-bottom-left-radius: 0; border-bottom-right-radius: 0; }
        .faq-icon { transition: transform 0.3s; flex-shrink: 0; color: #000000; }
        .faq-question.open .faq-icon { transform: rotate(45deg); }
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.3s ease; background: #fafafa; border: 1px solid #000000; border-top: none; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; }
        .faq-answer.open { max-height: 300px; padding: 20px 24px; }
        .faq-answer p { color: var(--text-muted); font-size: 0.93rem; line-height: 1.7; }

        /* FAQ AND CTA */
        .cta-box { max-width: 1200px; margin: 0 auto; padding: 80px 60px; border-radius: 40px; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(25px) saturate(180%); -webkit-backdrop-filter: blur(25px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 10px 40px rgba(0,0,0,0.05); text-align: center; position: relative; overflow: hidden; transform: translateZ(0); will-change: transform; }
        .cta-box::before { display: none; }
        .cta-box h2 { font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 700; margin-bottom: 16px; position: relative; color: #000000; }
        .cta-box p { color: var(--text-muted); font-size: 1.05rem; margin-bottom: 36px; max-width: 500px; margin-left: auto; margin-right: auto; position: relative; }
        .cta-actions { display: flex; justify-content: center; align-items: center; gap: 16px; flex-wrap: wrap; position: relative; }

        /* FOOTER */
        footer { position: relative; z-index: 1; background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(20px); border-top: 1px solid rgba(255, 255, 255, 0.4); padding: 72px 5% 36px; color: #000000; transform: translateZ(0); will-change: transform; }
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
        .modal-box { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(25px) saturate(200%); -webkit-backdrop-filter: blur(25px) saturate(200%); border: 1px solid rgba(255, 255, 255, 0.5); border-radius: var(--radius-xl); padding: 44px; width: 100%; max-width: 460px; position: relative; box-shadow: 0 20px 50px rgba(0,0,0,0.1); transform: scale(0.92) translateY(20px) translateZ(0); transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1); color: #000000; will-change: transform; }
        .modal-overlay.open .modal-box { transform: scale(1) translateY(0) translateZ(0); }
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
        @keyframes fadeInRight { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
        .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* MITRA INDUSTRI MARQUEE */
        .mitra-wrap { overflow: hidden; position: relative; width: 100%; padding: 40px 0; background: transparent; }
        .mitra-wrap::before, .mitra-wrap::after { content: ''; position: absolute; top: 0; width: 150px; height: 100%; z-index: 2; pointer-events: none; }
        .mitra-wrap::before { left: 0; background: linear-gradient(to right, #f4f6f9, transparent); }
        .mitra-wrap::after { right: 0; background: linear-gradient(to left, #f4f6f9, transparent); }
        .marquee { display: flex; width: max-content; animation: scroll-left 30s linear infinite; }
        .marquee:hover { animation-play-state: paused; }
        .marquee-content { display: flex; gap: 30px; padding: 0 15px; }
        .mitra-card { 
            background: #ffffff; 
            border: 1px solid rgba(0,0,0,0.08); 
            border-radius: 16px; 
            padding: 20px 30px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100px;
            min-width: 200px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .mitra-card img {
            max-height: 50px;
            max-width: 140px;
            object-fit: contain;
            filter: grayscale(100%) opacity(70%);
            transition: all 0.4s ease;
        }
        .mitra-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: rgba(0,0,0,0.15);
        }
        .mitra-card:hover img {
            filter: grayscale(0%) opacity(100%);
            transform: scale(1.05);
        }
        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }


        /* RESPONSIVE */
        /* NAV TOGGLE BUTTON (Hidden on Desktop) */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: #000000;
            cursor: pointer;
            z-index: 1001;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            transition: background 0.3s;
        }
        .nav-toggle:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        /* MOBILE MENU DRAWER */
        .mobile-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            z-index: 9998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }
        .mobile-menu-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 85%;
            max-width: 340px;
            height: 100vh;
            height: 100dvh;
            background: #ffffff;
            z-index: 9999;
            padding: 80px 30px 40px;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.08);
            border-left: 1px solid #e5e5e5;
            transition: right 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            gap: 32px;
            overflow-y: auto;
        }
        .mobile-menu.open {
            right: 0;
        }
        
        .mobile-menu-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .mobile-menu-close:hover {
            background: #000000;
            color: #ffffff !important;
        }

        .mobile-nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .mobile-nav-links a {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 1.05rem;
            text-decoration: none !important;
            transition: color 0.3s;
            display: block;
        }
        .mobile-nav-links a:hover {
            color: #000000;
        }

        .mobile-nav-actions {
            display: flex;
            flex-direction: column;
            gap: 14px;
            border-top: 1px solid #e5e5e5;
            padding-top: 20px;
        }
        .mobile-nav-actions .btn-nav-signup {
            text-align: center;
            justify-content: center;
            display: flex;
            padding: 12px;
        }
        .mobile-nav-actions .btn-nav-login {
            text-align: center;
            font-size: 1rem;
            padding: 8px;
        }

        /* CUSTOM HELPER CLASSES */
        .jasa-cta-box {
            background: rgba(255, 255, 255, 0.75);
            border: 2px solid #000000;
            text-align: left;
            padding: 60px 50px;
            border-radius: 40px;
        }
        .partner-promo-card {
            padding: 32px;
            border-radius: 28px;
        }
        .card-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }
        .card-badge-live {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            border: 1px solid #000000;
        }
        .product-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 12px;
            background: #ffffff;
            border: 1px solid #e5e5e5;
            transition: transform 0.2s ease;
        }
        .product-row:hover {
            transform: translateX(4px);
        }
        .product-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(0,0,0,0.05);
            border: 1px solid #000000;
            color: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .product-info h6 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 0.95rem;
        }
        .product-info small {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* ==========================================
           RESPONSIVE BREAKPOINTS (320px - 2560px+)
           ========================================== */

        /* ULTRA WIDE SCREENS (> 1440px) */
        @media (min-width: 1441px) {
            .container-custom {
                max-width: 1360px;
            }
            .hero-title {
                font-size: 2.8rem;
            }
            .section-title {
                font-size: 2.8rem;
            }
        }

        /* SMALL DESKTOP / LAPTOP (992px - 1199px) */
        @media (max-width: 1199px) {
            #navbar.scrolled {
                width: 94%;
                padding: 10px 24px;
            }
            .hero-inner {
                gap: 40px;
            }
            .hero-3card-container {
                gap: 8px;
            }
            .hero-3card {
                padding: 12px 6px;
                min-height: 210px;
            }
            .hero-3card-featured {
                min-height: 225px;
            }
            .features-grid, .products-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 18px;
            }
            .footer-top-row {
                grid-template-columns: 1.4fr 1fr 1fr 1fr;
                gap: 32px;
            }
        }

        /* TABLET LANDSCAPE & SMALL LAPTOP (769px - 991px) */
        @media (max-width: 991px) {
            .nav-links { display: none; }
            .nav-actions { display: none; }
            .nav-toggle { display: flex; }
            
            #hero {
                padding: 140px 5% 60px;
            }
            .hero-inner {
                flex-direction: column;
                gap: 40px;
                text-align: center;
            }
            .hero-badge { margin: 0 auto 20px; }
            .hero-desc { margin-left: auto; margin-right: auto; }
            .hero-cta { justify-content: center; }
            .hero-trust { justify-content: center; text-align: left; }
            .hero-visual { width: 100%; max-width: 600px; margin: 0 auto; }

            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-grid::before { display: none; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
            .vm-grid { grid-template-columns: 1fr; gap: 20px; }
            .footer-top-row { grid-template-columns: repeat(2, 1fr); gap: 36px; }
        }

        /* TABLET PORTRAIT & MOBILE LARGE (576px - 768px) */
        @media (max-width: 768px) {
            #navbar {
                padding: 14px 4%;
            }
            #navbar.scrolled {
                top: 10px;
                width: 94%;
                padding: 10px 20px;
            }
            .nav-logo-icon {
                width: 36px;
                height: 36px;
                font-size: 1.1rem;
            }
            .nav-logo-text {
                font-size: 1.25rem;
            }
            
            #hero {
                padding: 120px 4% 50px;
            }
            .section-wrap {
                padding: 60px 4%;
            }
            .section-header {
                margin-bottom: 40px;
            }
            .section-title {
                font-size: 1.8rem;
            }
            .section-subtitle {
                font-size: 0.95rem;
            }

            .hero-title {
                font-size: 1.8rem;
                line-height: 1.25;
            }
            .hero-desc {
                font-size: 0.98rem;
                margin-bottom: 28px;
            }
            
            .cta-box, .jasa-cta-box {
                padding: 36px 20px !important;
                text-align: center !important;
                border-radius: 24px !important;
            }
            .jasa-btn-group {
                justify-content: center !important;
            }
            .jasa-btn-group .btn-primary, .jasa-btn-group .btn-secondary {
                width: 100%;
                justify-content: center;
            }
            .cta-box h2 {
                font-size: 1.8rem;
            }
            .cta-box p {
                font-size: 0.95rem;
            }
            .cta-actions {
                justify-content: center;
            }

            .mitra-wrap::before, .mitra-wrap::after {
                width: 50px;
            }
            .mitra-card {
                min-width: 160px;
                height: 85px;
                padding: 14px 20px;
            }
            .mitra-card img {
                max-height: 40px;
                max-width: 110px;
            }
        }

        /* MOBILE STANDARD (481px - 575px) */
        @media (max-width: 575px) {
            .hero-badge {
                font-size: 0.75rem;
                padding: 6px 14px;
                max-width: 100%;
                word-break: break-word;
            }
            .hero-title {
                font-size: 1.6rem !important;
            }
            .hero-cta {
                flex-direction: column;
                width: 100%;
            }
            .btn-primary, .btn-secondary {
                width: 100%;
                justify-content: center;
                padding: 13px 20px;
                font-size: 0.95rem;
            }
            .hero-trust {
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }

            .hero-3card-container {
                gap: 6px;
            }
            .hero-3card {
                padding: 10px 4px;
                min-height: 195px;
            }
            .hero-3card-featured {
                min-height: 205px;
            }
            .hero-3card-img {
                width: 44px;
                height: 44px;
                margin-bottom: 6px;
                padding: 6px;
            }
            .hero-3card h5 {
                font-size: 0.72rem;
            }
            .btn-3card {
                font-size: 0.7rem;
                padding: 5px 4px;
            }

            .features-grid, .products-grid, .steps-grid {
                grid-template-columns: 1fr;
            }

            .testi-marquee-wrap::before, .testi-marquee-wrap::after {
                width: 30px;
            }
            .testi-card {
                width: 300px;
                min-width: 300px;
                padding: 24px 20px;
            }

            .partner-promo-card {
                padding: 20px 16px !important;
                border-radius: 20px !important;
            }

            .footer-top-row {
                grid-template-columns: 1fr;
                gap: 28px;
            }
            .footer-bottom-row {
                flex-direction: column;
                text-align: center;
                justify-content: center;
                gap: 14px;
            }
            .footer-bottom-badges {
                justify-content: center;
            }

            .modal-box {
                padding: 30px 20px !important;
                width: 92% !important;
                max-height: 90vh;
                max-height: 90dvh;
                overflow-y: auto;
            }
        }

        /* MOBILE SMALL (320px - 480px) */
        @media (max-width: 480px) {
            .container-custom {
                padding: 0 4%;
            }
            #hero {
                padding: 105px 4% 40px;
            }
            .hero-title {
                font-size: 1.45rem !important;
                line-height: 1.25 !important;
            }
            .section-title {
                font-size: 1.55rem !important;
            }
            .section-subtitle {
                font-size: 0.88rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .stat-card {
                padding: 20px 16px;
            }
            .stat-num {
                font-size: 1.8rem;
            }

            .hero-cards-mini {
                flex-direction: row;
                gap: 6px;
            }
            .mini-card {
                padding: 10px 4px;
            }
            .mini-card .num {
                font-size: 1.05rem;
            }
            .mini-card .lbl {
                font-size: 0.65rem;
            }

            .product-card {
                padding: 24px 20px;
            }
            .product-card-footer {
                flex-direction: column;
                gap: 12px;
                align-items: center;
                text-align: center;
            }
            .btn-see {
                width: 100%;
                text-align: center;
            }

            .testi-card {
                width: 270px;
                min-width: 270px;
                padding: 20px 16px;
            }
            .testi-text {
                font-size: 0.85rem;
                margin-bottom: 16px;
            }

            .product-row {
                padding: 10px 12px;
                gap: 10px;
            }
            .product-icon {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }
            .product-info h6 {
                font-size: 0.88rem;
            }
            .product-info small {
                font-size: 0.75rem;
            }

            .faq-question {
                padding: 14px 16px;
                font-size: 0.9rem;
            }
            .faq-answer.open {
                padding: 14px 16px;
            }

            .cta-box {
                padding: 28px 16px !important;
            }
            .cta-actions {
                flex-direction: column;
                width: 100%;
            }
            .cta-actions .btn-primary, .cta-actions .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .vm-card {
                padding: 28px 20px;
            }
            .feature-card {
                padding: 24px 20px;
            }
            .step-card {
                padding: 24px 16px;
            }

            .mobile-menu {
                width: 88%;
                max-width: 320px;
                padding: 70px 24px 30px;
            }
        }

        /* EXTREMELY SMALL MOBILE (< 360px) */
        @media (max-width: 359px) {
            .hero-3card-container {
                flex-direction: column;
                gap: 10px;
            }
            .hero-3card {
                width: 100%;
                min-height: auto;
                padding: 14px 12px;
            }
            .hero-3card-featured {
                transform: none;
                min-height: auto;
            }
            .hero-3card-featured:hover {
                transform: none;
            }
            .btn-primary, .btn-secondary {
                padding: 12px 14px;
                font-size: 0.88rem;
            }
            .nav-logo-text {
                font-size: 1.1rem;
            }
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
            <img src="{{ asset($websiteSettings->logo_path) }}" alt="{{ $websiteSettings->site_name }}" style="max-height: 55px; margin-right: 10px; width: auto; object-fit: contain;">
        @else
            <div class="nav-logo-icon">L</div>
            <span class="nav-logo-text">{{ isset($websiteSettings) ? $websiteSettings->site_name : 'LAPAKTIFIKASI' }}</span>
        @endif
    </a>
    <ul class="nav-links">
        <li><a href="#hero">Beranda</a></li>
        <li><a href="#fitur">Kelebihan</a></li>
        <li><a href="#visimisi">Visi &amp; Misi</a></li>
        <li><a href="#produk">Produk</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li><a href="{{ route('daftar.seller') }}">Jadi Seller</a></li>
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
        <li><a href="#hero" onclick="closeMobileMenu()">Beranda</a></li>
        <li><a href="#fitur" onclick="closeMobileMenu()">Kelebihan</a></li>
        <li><a href="#visimisi" onclick="closeMobileMenu()">Visi &amp; Misi</a></li>
        <li><a href="#produk" onclick="closeMobileMenu()">Produk</a></li>
        <li><a href="#faq" onclick="closeMobileMenu()">FAQ</a></li>
        <li><a href="{{ route('daftar.seller') }}" onclick="closeMobileMenu()">Jadi Seller</a></li>
        @auth
            @if(Auth::user()->role_id == 2)
                <li><a href="{{ route('premium.katalog') }}" onclick="closeMobileMenu()">Belanja</a></li>
            @else
                <li><a href="{{ url('/dashboard') }}" onclick="closeMobileMenu()">Dashboard</a></li>
            @endif
        @endauth
    </ul>
    <div class="mobile-nav-actions">
        @auth
            @if(Auth::user()->role_id == 1)
                <a href="{{ url('/dashboard') }}" class="btn-nav-signup" onclick="closeMobileMenu()"><i class="bi bi-speedometer2"></i> Dashboard</a>
            @else
                <a href="{{ route('premium.katalog') }}" class="btn-nav-signup" onclick="closeMobileMenu()"><i class="bi bi-cart3"></i> Belanja</a>
            @endif
        @else
            <a href="{{ url('/login') }}" class="btn-nav-login" onclick="closeMobileMenu()">Masuk</a>
            <a href="{{ url('/pendaftaran') }}" class="btn-nav-signup" onclick="closeMobileMenu()">Daftar Gratis</a>
        @endauth
    </div>
</div>

<!-- HERO -->
<header id="hero">
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge"><i class="bi bi-mortarboard-fill"></i> Sinergi Digital SMK Plus Pelita Nusantara Bogor</div>
            <h1 class="hero-title">Lapaktifikasi Bersama<br><span class="grad-text">SMK PLUS PELITA NUSANTARA BOGOR</span></h1>
            <p class="hero-desc">Pusat Marketplace Digital &amp; Akun Premium Terpercaya. Hasil kolaborasi strategis dalam membangun ekosistem digital mandiri, mengasah jiwa kewirausahaan siswa 5 Jurusan &amp; Staff, serta mendorong pertumbuhan ekonomi kreatif digital di SMK Plus Pelita Nusantara Bogor.</p>
            <div class="hero-cta">
                @auth
                    @if(Auth::user()->role_id == 2)
                        <a href="{{ route('premium.katalog') }}" class="btn-primary"><i class="bi bi-bag-fill"></i> Mulai Jelajah Katalog</a>
                        <a href="{{ route('premium.riwayat') }}" class="btn-secondary"><i class="bi bi-clock-history"></i> Riwayat Pembelian</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="btn-primary"><i class="bi bi-speedometer2"></i> Masuk Dashboard</a>
                    @endif
                @else
                    <a href="#" class="btn-primary" onclick="openModal(); return false;"><i class="bi bi-bag-fill"></i> Belanja Produk Digital</a>
                    <a href="{{ url('/pendaftaran') }}" class="btn-secondary"><i class="bi bi-person-plus"></i> Daftar Akun Gratis</a>
                @endauth
            </div>
            <div class="hero-trust">
                <div class="trust-item"><i class="bi bi-check-circle-fill"></i> Akun Premium &amp; File Digital</div>
                <div class="trust-item"><i class="bi bi-check-circle-fill"></i> Download ZIP / File Instan</div>
                <div class="trust-item"><i class="bi bi-check-circle-fill"></i> Pembayaran QRIS &amp; Bank</div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-3card-container">
                <!-- Card 1: Left -->
                <div class="glass-card hero-3card">
                    <div class="hero-3card-img">
                        <img src="https://cdn-icons-png.flaticon.com/512/1005/1005141.png" alt="Source Code">
                    </div>
                    <h5>Source Code</h5>
                    <div class="hero-3card-lines">
                        <span></span>
                        <span></span>
                    </div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}" class="btn-3card">Beli</a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-3card">Beli</a>
                    @endauth
                </div>

                <!-- Card 2: Middle (Featured) -->
                <div class="glass-card hero-3card hero-3card-featured">
                    <div class="hero-3card-img">
                        <img src="https://cdn-icons-png.flaticon.com/512/3670/3670157.png" alt="Spotify & Netflix">
                    </div>
                    <h5>Spotify &amp; Netflix</h5>
                    <div class="hero-3card-lines">
                        <span></span>
                        <span></span>
                    </div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'premium']) }}" class="btn-3card">Beli</a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-3card">Beli</a>
                    @endauth
                </div>

                <!-- Card 3: Right -->
                <div class="glass-card hero-3card">
                    <div class="hero-3card-img">
                        <img src="https://cdn-icons-png.flaticon.com/512/2970/2970785.png" alt="Creative Assets">
                    </div>
                    <h5>Creative Assets</h5>
                    <div class="hero-3card-lines">
                        <span></span>
                        <span></span>
                    </div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}" class="btn-3card">Beli</a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-3card">Beli</a>
                    @endauth
                </div>
            </div>

            <div class="hero-cards-mini">
                <div class="glass-card mini-card"><div class="num">5</div><div class="lbl">Jurusan SMK Pelita</div></div>
                <div class="glass-card mini-card"><div class="num">2.5K+</div><div class="lbl">Pengguna Aktif</div></div>
                <div class="glass-card mini-card"><div class="num">100%</div><div class="lbl">Legal &amp; Aman</div></div>
            </div>
        </div>
    </div>
</header>

<!-- STATS -->
<section id="stats">
    <div class="stats-grid">
        <div class="glass-card stat-card reveal">
            <div class="stat-icon fi-1"><i class="bi bi-people-fill"></i></div>
            <div class="stat-num">2.500+</div>
            <div class="stat-label">Pelanggan Terdaftar</div>
        </div>
        <div class="glass-card stat-card reveal" style="transition-delay:.1s">
            <div class="stat-icon fi-2"><i class="bi bi-bag-check-fill"></i></div>
            <div class="stat-num">15.000+</div>
            <div class="stat-label">Transaksi Berhasil</div>
        </div>
        <div class="glass-card stat-card reveal" style="transition-delay:.2s">
            <div class="stat-icon fi-3"><i class="bi bi-star-fill"></i></div>
            <div class="stat-num">4.9/5</div>
            <div class="stat-label">Rating Kepuasan</div>
        </div>
        <div class="glass-card stat-card reveal" style="transition-delay:.3s">
            <div class="stat-icon fi-4"><i class="bi bi-clock-fill"></i></div>
            <div class="stat-num">99.8%</div>
            <div class="stat-label">Uptime Sistem</div>
        </div>
    </div>
</section>

<!-- MITRA INDUSTRI -->
<section id="mitra-industri" style="position: relative; z-index: 1; padding: 0 0 60px;">
    <div class="container-custom">
        <div class="section-header centered" style="margin-bottom: 40px;">
            <h2 class="section-title" style="font-size: 1.8rem; color: #1f2937;">Mitra Kami Di Lapaktifikasi</h2>
        </div>
    </div>
    <div class="mitra-wrap">
        <div class="marquee">
            <!-- Group 1 -->
            <div class="marquee-content">
                @foreach($mitras as $mitra)
                    <div class="mitra-card">
                        <img src="{{ asset($mitra->image_path) }}" alt="{{ $mitra->name }}">
                    </div>
                @endforeach
            </div>
            <!-- Group 2 (Duplicate for infinite scroll) -->
            <div class="marquee-content">
                @foreach($mitras as $mitra)
                    <div class="mitra-card">
                        <img src="{{ asset($mitra->image_path) }}" alt="{{ $mitra->name }}">
                    </div>  
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- FITUR -->
<section id="fitur" class="section-wrap">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-stars"></i> Kelebihan Kami</div>
            <h2 class="section-title">Mengapa <span class="highlight">Lapaktifikasi</span>?</h2>
            <p class="section-subtitle">Platform kami dirancang dengan teknologi terkini untuk memastikan pengalaman belanja yang cepat, aman, dan memuaskan.</p>
        </div>
        <div class="features-grid">
            <div class="glass-card feature-card reveal">
                <div class="feature-icon fi-1"><i class="bi bi-lightning-charge-fill"></i></div>
                <h3>Pengiriman Instan</h3>
                <p>Kredensial akun premium dikirim otomatis dalam hitungan detik setelah pembayaran berhasil dikonfirmasi oleh sistem.</p>
            </div>
            <div class="glass-card feature-card reveal" style="transition-delay:.1s">
                <div class="feature-icon fi-2"><i class="bi bi-shield-lock-fill"></i></div>
                <h3>3 Payment Gateway Terpercaya</h3>
                <p>Bekerja sama dengan Midtrans, Duitku, dan Pakasir. Transaksi menggunakan Midtrans tersertifikasi oleh Bank Indonesia, Kominfo, AES, dan PCI DSS serta memiliki standar internasional ISO/IEC 27001.</p>
            </div>
            <div class="glass-card feature-card reveal" style="transition-delay:.2s">
                <div class="feature-icon fi-3"><i class="bi bi-clock-history"></i></div>
                <h3>Reservasi Stok 15 Menit</h3>
                <p>Sistem kami mengamankan stok akun pilihan Anda selama 15 menit saat checkout agar tidak diambil pembeli lain.</p>
            </div>
            <div class="glass-card feature-card reveal" style="transition-delay:.3s">
                <div class="feature-icon fi-4"><i class="bi bi-headset"></i></div>
                <h3>Dukungan Responsif</h3>
                <p>Tim kami siap membantu Anda kapan saja melalui berbagai kanal yang tersedia untuk penyelesaian masalah.</p>
            </div>
            <div class="glass-card feature-card reveal" style="transition-delay:.4s">
                <div class="feature-icon fi-5"><i class="bi bi-collection-fill"></i></div>
                <h3>Pilihan Produk Lengkap</h3>
                <p>Temukan berbagai layanan digital premium dalam satu platform — hiburan, produktivitas, hingga pendidikan digital.</p>
            </div>
            <div class="glass-card feature-card reveal" style="transition-delay:.5s">
                <div class="feature-icon fi-6"><i class="bi bi-arrow-repeat"></i></div>
                <h3>Riwayat Jelas &amp; Transparan</h3>
                <p>Semua transaksi tersimpan rapi. Akses kembali kredensial Anda kapan saja melalui halaman riwayat belanja.</p>
            </div>
        </div>
    </div>
</section>

<!-- VISI MISI -->
<section id="visimisi" class="section-wrap alt">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-eye-fill"></i> Sinergi &amp; Komitmen</div>
            <h2 class="section-title">Visi &amp; Misi <span class="highlight-cyan">Sinergi Digital</span></h2>
            <p class="section-subtitle">Lapaktifikasi bersama SMK Plus Pelita Nusantara Bogor berkomitmen membangun ekosistem digital mandiri yang memberdayakan karya siswa dan menggerakkan potensi ekonomi sekolah.</p>
        </div>
        <div class="vm-grid">
            <div class="glass-card vm-card vm-visi reveal">
                <div class="vm-icon">&#127919;</div>
                <h3>Visi Kami</h3>
                <p>Menjadi platform marketplace produk digital &amp; akun premium terdepan berbasis kolaborasi pendidikan yang menginspirasi kemandirian ekonomi digital, inovasi karya siswa 5 Jurusan &amp; Staff, serta mempermudah akses produk kreatif di seluruh Indonesia.</p>
            </div>
            <div class="glass-card vm-card vm-misi reveal" style="transition-delay:.15s">
                <div class="vm-icon">&#128640;</div>
                <h3>Misi Utama</h3>
                <ul>
                    <li><i class="bi bi-chevron-right"></i> Membuka wadah distribusi karya digital siswa (Source Code, ZIP, E-Book, Desain) &amp; Akun Premium</li>
                    <li><i class="bi bi-chevron-right"></i> Mengembangkan jiwa kewirausahaan digital siswa 5 Jurusan &amp; Staff SMK Plus Pelita Nusantara Bogor</li>
                    <li><i class="bi bi-chevron-right"></i> Menyediakan sistem pengiriman otomatis &amp; unduh file digital instan yang aman dan terpercaya</li>
                    <li><i class="bi bi-chevron-right"></i> Mendorong pertumbuhan ekonomi kreatif sekolah melalui skema komisi &amp; bagi hasil transparan</li>
                    <li><i class="bi bi-chevron-right"></i> Menyediakan layanan pembuatan website &amp; portal digital custom untuk komunitas &amp; institusi</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CARA KERJA -->
<section id="cara-kerja" class="section-wrap">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-diagram-3-fill"></i> Cara Kerja</div>
            <h2 class="section-title">Transaksi Semudah <span class="highlight">4 Langkah</span></h2>
            <p class="section-subtitle">Proses belanja cepat dan sederhana — dari memilih file digital / akun hingga mengunduh langsung di akun Anda.</p>
        </div>
        <div class="steps-grid">
            <div class="glass-card step-card reveal">
                <div class="step-num">1</div>
                <h4>Daftar Akun</h4>
                <p>Buat akun Lapaktifikasi gratis hanya dengan email dan kata sandi. Proses verifikasi instan tanpa ribet.</p>
            </div>
            <div class="glass-card step-card reveal" style="transition-delay:.12s">
                <div class="step-num">2</div>
                <h4>Pilih Produk</h4>
                <p>Jelajahi berbagai pilihan akun premium, source code (ZIP), e-book, hingga file digital bermanfaat lainnya.</p>
            </div>
            <div class="glass-card step-card reveal" style="transition-delay:.24s">
                <div class="step-num">3</div>
                <h4>Bayar Otomatis</h4>
                <p>Lakukan pembayaran aman via QRIS, e-Wallet, atau Transfer Bank otomatis yang diproses dalam hitungan detik.</p>
            </div>
            <div class="glass-card step-card reveal" style="transition-delay:.36s">
                <div class="step-num">4</div>
                <h4>Unduh / Akses Instan</h4>
                <p>Unduh file ZIP/dokumen secara langsung atau lihat kredensial akun di halaman riwayat belanja Anda seketika!</p>
            </div>
        </div>
    </div>
</section>

<!-- PRODUK -->
<section id="produk" class="section-wrap alt">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-grid-fill"></i> Produk &amp; Layanan</div>
            <h2 class="section-title">Kategori Produk <span class="highlight">Digital Kami</span></h2>
            <p class="section-subtitle">Temukan berbagai ragam produk digital terlengkap — dari aplikasi premium hingga karya file digital buatan creator &amp; siswa.</p>
        </div>
        <div class="products-grid">
            <div class="glass-card product-card reveal">
                <div class="product-card-icon"><img src="https://cdn-icons-png.flaticon.com/512/1005/1005141.png" alt="Source Code"></div>
                <h3>Source Code &amp; ZIP File</h3>
                <p>Script web, project laravel/php, source code absensi, template, &amp; modul siap pakai dalam format ZIP/RAR.</p>
                <div class="product-card-footer">
                    <div class="product-card-price"><small>File Digital</small><strong>Unduh Instan</strong></div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}" class="btn-see">Lihat <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-see">Cek File <i class="bi bi-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
            <div class="glass-card product-card reveal" style="transition-delay:.1s">
                <div class="product-card-icon"><img src="https://cdn-icons-png.flaticon.com/512/3670/3670157.png" alt="Spotify & Netflix"></div>
                <h3>Spotify &amp; Netflix Premium</h3>
                <p>Nikmati akses streaming musik tanpa iklan dan film 4K Ultra HD dengan harga ramah kantong.</p>
                <div class="product-card-footer">
                    <div class="product-card-price"><small>Akun Premium</small><strong>Mulai Rp 15.000</strong></div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'premium']) }}" class="btn-see">Lihat <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-see">Beli <i class="bi bi-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
            <div class="glass-card product-card reveal" style="transition-delay:.2s">
                <div class="product-card-icon"><img src="https://cdn-icons-png.flaticon.com/512/2970/2970785.png" alt="Gambar & Desain Grafis"></div>
                <h3>Gambar &amp; Desain Grafis</h3>
                <p>Elemen desain, template Canva/Photoshop, ilustrasi, foto, dan aset grafis berkualitas tinggi untuk kebutuhan konten.</p>
                <div class="product-card-footer">
                    <div class="product-card-price"><small>Aset Kreatif</small><strong>Download Direct</strong></div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}" class="btn-see">Lihat <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-see">Beli <i class="bi bi-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
            <div class="glass-card product-card reveal" style="transition-delay:.3s">
                <div class="product-card-icon"><img src="https://cdn-icons-png.flaticon.com/512/2991/2991106.png" alt="Dokumen & Notepad"></div>
                <h3>Dokumen &amp; Notepad (TXT)</h3>
                <p>Modul pembelajaran, catatan penting, daftar prompt AI, &amp; script text yang dapat diakses langsung setelah order.</p>
                <div class="product-card-footer">
                    <div class="product-card-price"><small>File Teks / TXT</small><strong>Akses Langsung</strong></div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}" class="btn-see">Lihat <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-see">Beli <i class="bi bi-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
            <div class="glass-card product-card reveal" style="transition-delay:.4s">
                <div class="product-card-icon"><img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" alt="E-Book & Modul Edukasi"></div>
                <h3>E-Book &amp; Modul Edukasi</h3>
                <p>Panduan praktis, e-book pembelajaran 5 Jurusan SMK, hingga materi pengembangan karir &amp; teknologi.</p>
                <div class="product-card-footer">
                    <div class="product-card-price"><small>Format PDF</small><strong>Unduh Seketika</strong></div>
                    @auth
                        <a href="{{ route('premium.katalog', ['kategori' => 'digital']) }}" class="btn-see">Lihat <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="#" onclick="openModal(); return false;" class="btn-see">Beli <i class="bi bi-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
            <div class="glass-card product-card reveal" style="transition-delay:.5s">
                <div class="product-card-icon"><img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Karya Siswa SMK Pelita"></div>
                <h3>Karya Siswa SMK Pelita</h3>
                <p>Dukung kreativitas dan jiwa wirausaha para siswa SMK Plus Pelita Nusantara Bogor dengan membeli karya terbaik mereka.</p>
                <div class="product-card-footer">
                    <div class="product-card-price"><small>Karya Siswa</small><strong>Dukungan Ekonomi</strong></div>
                    @auth
                        <a href="{{ route('premium.katalog') }}" class="btn-see">Jelajahi <i class="bi bi-arrow-right"></i></a>
                    @else
                        <a href="{{ url('/pendaftaran') }}" class="btn-see">Daftar <i class="bi bi-arrow-right"></i></a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JASA PEMBUATAN WEBSITE & KEMITRAAN BANNER -->
<section id="jasa-website" class="section-wrap">
    <div class="container-custom">
        <div class="jasa-cta-box reveal">
            <div class="row align-items-center" style="display: flex; flex-wrap: wrap; gap: 30px;">
                <div style="flex: 1; min-width: 280px;">
                    <div class="section-tag" style="background: #000; color: #fff;"><i class="bi bi-code-slash"></i> Jasa Pembuatan Website &amp; Software</div>
                    <h2 style="font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.6rem, 3.5vw, 2.5rem); font-weight: 700; color: #000; margin-bottom: 16px;">
                        Mau Memiliki Website Marketplace / Portal Digital Seperti Ini?
                    </h2>
                    <p style="color: var(--text-muted); font-size: 1.02rem; line-height: 1.7; margin-bottom: 24px;">
                        Tim Lapaktifikasi bersama pengembang SMK Plus Pelita Nusantara Bogor siap membantu Anda membangun website custom, sistem e-commerce, portal sekolah/komunitas, hingga aplikasi bisnis terintegrasi sesuai kebutuhan Anda!
                    </p>
                    <div class="jasa-btn-group" style="display: flex; gap: 16px; flex-wrap: wrap;">
                        <a href="https://wa.me/6287897600086?text=Halo%20Tim%20Lapaktifikasi,%20saya%20tertarik%20untuk%20konsultasi%20jasa%20pembuatan%20website%20/%20platform%20digital." target="_blank" class="btn-primary">
                            <i class="bi bi-whatsapp"></i> Hubungi Tim Lapaktifikasi
                        </a>
                        <a href="{{ route('join.partner') }}" class="btn-secondary">
                            <i class="bi bi-people"></i> Program Kemitraan Gratis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONI -->
<section id="testimoni" class="section-wrap alt" style="overflow: hidden;">
    <div class="container-custom" style="margin-bottom: 40px;">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-chat-quote-fill"></i> Testimoni</div>
            <h2 class="section-title">Kata Mereka yang <span class="highlight">Sudah Merasakan</span></h2>
            <p class="section-subtitle">Ribuan pelanggan &amp; seller telah merasakan kemudahan bertransaksi file digital dan akun premium di Lapaktifikasi.</p>
        </div>
    </div>
    
    <div class="testi-marquee-wrap">
        <div class="testi-marquee">
            <!-- Group 1 -->
            <div class="testi-marquee-group">
                @foreach($testimonis as $item)
                <div class="glass-card testi-card">
                    <div class="testi-stars">
                        @for($i=1; $i<=$item->rating; $i++) &#9733; @endfor
                    </div>
                    <p class="testi-text">"{{ $item->comment }}"</p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background: #fafafa; color: #000000;">
                            {{ strtoupper(substr($item->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="testi-name">{{ $item->name }}</div>
                            <div class="testi-role">{{ $item->role }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Group 2 (Duplicate for infinite marquee scroll) -->
            <div class="testi-marquee-group">
                @foreach($testimonis as $item)
                <div class="glass-card testi-card">
                    <div class="testi-stars">
                        @for($i=1; $i<=$item->rating; $i++) &#9733; @endfor
                    </div>
                    <p class="testi-text">"{{ $item->comment }}"</p>
                    <div class="testi-author">
                        <div class="testi-avatar" style="background: #fafafa; color: #000000;">
                            {{ strtoupper(substr($item->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="testi-name">{{ $item->name }}</div>
                            <div class="testi-role">{{ $item->role }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- JOIN PARTNER PROMO -->
<section id="join-partner-promo" class="section-wrap">
    <div class="container-custom">
        <div class="vm-grid">
            <div class="hero-content reveal">
                <div class="section-tag"><i class="bi bi-people-fill"></i> Program Kemitraan Komunitas &amp; Sekolah</div>
                <h2 class="section-title">Website Toko Komunitas <span class="highlight">100% Gratis</span></h2>
                <p class="hero-desc" style="margin-bottom:28px;">
                    Miliki platform e-commerce produk digital khusus untuk komunitas atau sekolah Anda secara gratis (contoh: <code>nusabogor.lapaktifikasi.my.id</code>). Ajak para seller digital di dalam komunitas Anda untuk bergabung berjualan, dan nikmati bagi hasil keuntungan dari komisi transaksi secara otomatis dan transparan.
                </p>
                <div class="hero-trust" style="margin-bottom:36px; display:flex; flex-direction:column; align-items:flex-start; gap:12px;">
                    <div class="trust-item"><i class="bi bi-patch-check-fill"></i> Subdomain Khusus Komunitas (e.g., <code>nusabogor.lapaktifikasi.my.id</code>)</div>
                    <div class="trust-item"><i class="bi bi-patch-check-fill"></i> Rekrut &amp; Hubungkan Seller Digital dari Komunitas Anda</div>
                    <div class="trust-item"><i class="bi bi-patch-check-fill"></i> Sistem Bagi Hasil Komisi Transparan dengan Tim Lapaktifikasi</div>
                </div>
                <a href="{{ route('join.partner') }}" class="btn-primary"><i class="bi bi-arrow-right-circle-fill"></i> Pelajari Selengkapnya</a>
            </div>
            <div class="hero-visual reveal" style="transition-delay:.15s">
                <div class="glass-card partner-promo-card">
                    <div class="card-header-row">
                        <span style="font-weight:700;font-size:1.1rem;font-family:'Space Grotesk',sans-serif;"><i class="bi bi-rocket-takeoff-fill"></i> Kemitraan Komunitas</span>
                        <span class="card-badge-live" style="background:rgba(0,0,0,0.05); color:#000000; border-color:#000000;">Gratis</span>
                    </div>
                    <div class="product-row">
                        <div class="product-icon"><i class="bi bi-globe"></i></div>
                        <div class="product-info">
                            <h6>Subdomain Sendiri</h6>
                            <small>nusabogor.lapaktifikasi.my.id</small>
                        </div>
                    </div>
                    <div class="product-row">
                        <div class="product-icon"><i class="bi bi-people"></i></div>
                        <div class="product-info">
                            <h6>Rekrut Seller Internal</h6>
                            <small>Kumpulkan seller dari komunitas Anda</small>
                        </div>
                    </div>
                    <div class="product-row">
                        <div class="product-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="product-info">
                            <h6>Bagi Hasil Otomatis</h6>
                            <small>Komisi transparan dari tiap transaksi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="section-wrap">
    <div class="container-custom">
        <div class="section-header centered">
            <div class="section-tag"><i class="bi bi-question-circle-fill"></i> FAQ</div>
            <h2 class="section-title">Pertanyaan yang <span class="highlight">Sering Ditanyakan</span></h2>
            <p class="section-subtitle">Temukan jawaban atas pertanyaan umum seputar layanan dan cara penggunaan Lapaktifikasi.</p>
        </div>
        <div class="faq-wrap">
            <div class="faq-item reveal">
                <button class="faq-question" onclick="toggleFaq(this)">Apakah akun premium yang dijual di Lapaktifikasi aman dan legal? <i class="bi bi-plus-lg faq-icon"></i></button>
                <div class="faq-answer"><p>Ya, seluruh produk yang kami jual merupakan akun premium yang aman untuk digunakan. Kami berkomitmen untuk menjaga keamanan dan kenyamanan setiap pelanggan dengan sistem yang terverifikasi.</p></div>
            </div>
            <div class="faq-item reveal" style="transition-delay:.08s">
                <button class="faq-question" onclick="toggleFaq(this)">Berapa lama waktu yang dibutuhkan untuk menerima akun setelah pembayaran? <i class="bi bi-plus-lg faq-icon"></i></button>
                <div class="faq-answer"><p>Sistem kami memproses pengiriman secara otomatis. Setelah pembayaran Anda dikonfirmasi oleh Midtrans, kredensial akun akan langsung tersedia di halaman riwayat belanja dalam hitungan detik — umumnya kurang dari 5 detik.</p></div>
            </div>
            <div class="faq-item reveal" style="transition-delay:.16s">
                <button class="faq-question" onclick="toggleFaq(this)">Metode pembayaran apa saja yang tersedia? <i class="bi bi-plus-lg faq-icon"></i></button>
                <div class="faq-answer"><p>Kami menyediakan berbagai metode pembayaran melalui Midtrans, termasuk transfer bank (BCA, BNI, BRI, Mandiri), e-wallet (GoPay, OVO, Dana, ShopeePay), QRIS, virtual account, dan Alfamart/Indomaret. Semua transaksi diproses secara aman dan otomatis.</p></div>
            </div>
            <div class="faq-item reveal" style="transition-delay:.24s">
                <button class="faq-question" onclick="toggleFaq(this)">Apa yang terjadi jika akun yang saya beli bermasalah? <i class="bi bi-plus-lg faq-icon"></i></button>
                <div class="faq-answer"><p>Kepuasan pelanggan adalah prioritas kami. Jika Anda mengalami masalah dengan akun yang dibeli, segera hubungi tim dukungan kami dan kami akan menindaklanjuti dengan cepat sesuai kebijakan garansi yang berlaku.</p></div>
            </div>
            <div class="faq-item reveal" style="transition-delay:.32s">
                <button class="faq-question" onclick="toggleFaq(this)">Apakah data pribadi dan pembayaran saya aman? <i class="bi bi-plus-lg faq-icon"></i></button>
                <div class="faq-answer"><p>Keamanan data Anda adalah hal utama bagi kami. Seluruh data pribadi dienkripsi dan dilindungi. Proses pembayaran dikelola sepenuhnya oleh Midtrans yang telah tersertifikasi PCI-DSS, sehingga informasi kartu atau rekening Anda tidak pernah kami simpan.</p></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA BOTTOM -->
<section id="cta" class="section-wrap">
    <div class="container-custom">
        <div class="cta-box reveal">
            <h2>Siap Menikmati <span class="grad-text">Premium Digital</span>?</h2>
            <p>Bergabunglah dengan ribuan pelanggan yang sudah mempercayai Lapaktifikasi. Daftar gratis sekarang dan dapatkan akun premium impian Anda.</p>
            <div class="cta-actions">
                @auth
                    @if(Auth::user()->role_id == 2)
                        <a href="{{ route('premium.katalog') }}" class="btn-primary"><i class="bi bi-bag-fill"></i> Mulai Belanja</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="btn-primary"><i class="bi bi-speedometer2"></i> Ke Dashboard</a>
                    @endif
                @else
                    <a href="{{ url('/pendaftaran') }}" class="btn-primary"><i class="bi bi-person-plus-fill"></i> Daftar Gratis Sekarang</a>
                    <a href="#" class="btn-secondary" onclick="openModal(); return false;"><i class="bi bi-box-arrow-in-right"></i> Sudah Punya Akun</a>
                @endauth
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
                    <li><a href="#produk">Spotify Premium</a></li>
                    <li><a href="#produk">Netflix Premium</a></li>
                    <li><a href="#produk">YouTube Premium</a></li>
                    <li><a href="#produk">Layanan Gaming</a></li>
                    <li><a href="#produk">Platform Edukasi</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Perusahaan</h5>
                <ul>
                    <li><a href="#visimisi">Tentang Lapaktifikasi</a></li>
                    <li><a href="#visimisi">Visi &amp; Misi</a></li>
                    <li><a href="#cara-kerja">Cara Kerja</a></li>
                    <li><a href="#testimoni">Testimoni</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Dukungan</h5>
                <ul>
                    <li><a href="#">Pusat Bantuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat &amp; Ketentuan</a></li>
                    <li><a href="{{ route('daftar.seller') }}">Daftar Jadi Seller</a></li>
                    <li><a href="{{ route('join.partner') }}">Join Partner</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
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
    // MOBILE NAV
    window.toggleMobileMenu = function() {
        const menu = document.getElementById('mobileMenu');
        const overlay = document.getElementById('mobileMenuOverlay');
        const isOpen = menu.classList.contains('open');
        if (isOpen) {
            closeMobileMenu();
        } else {
            menu.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    };
    window.closeMobileMenu = function() {
        document.getElementById('mobileMenu').classList.remove('open');
        document.getElementById('mobileMenuOverlay').classList.remove('open');
        document.body.style.overflow = '';
    };

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

    // FAQ
    window.toggleFaq = function(btn) {
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
    }, { threshold: 0.12 });
    reveals.forEach(r => observer.observe(r));

    // SMOOTH SCROLL
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
</script>
</body>
</html>
