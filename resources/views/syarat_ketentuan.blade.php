<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - Lapaktifikasi</title>
    <meta name="description" content="Syarat dan Ketentuan penggunaan platform Lapaktifikasi. Ketahui hak, kewajiban, dan ketentuan garansi layanan digital kami.">
    <meta name="keywords" content="syarat dan ketentuan, terms of service, ketentuan garansi, lapaktifikasi">
    <meta name="robots" content="index, follow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary:      #000000;
            --primary-2:    #1a1a1a;
            --dark-bg:      #ffffff;
            --dark-2:       #fafafa;
            --text-main:    #111111;
            --text-muted:   #555555;
            --text-dim:     #888888;
            --white:        #ffffff;
            --radius-lg:    24px;
            --radius-xl:    32px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark-bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.7;
        }
        .container-custom { max-width: 1000px; margin: 0 auto; padding: 0 5%; }

        /* NAVBAR */
        #navbar { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; padding: 18px 5%; display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,0.95); border-bottom: 1px solid #e5e5e5; backdrop-filter: blur(10px); }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none !important; }
        .nav-logo-icon { width: 40px; height: 40px; background: #000000; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; color: #ffffff; }
        .nav-logo-text { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.4rem; color: #000000; }
        .nav-logo-text span { color: #555555; font-weight: 400; }
        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .btn-nav-outline { border: 1px solid #000000; color: #000000; padding: 8px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s ease; }
        .btn-nav-outline:hover { background: #000000; color: #ffffff; }

        /* CONTENT */
        .page-header { padding: 140px 0 40px; text-align: center; background: #fafafa; border-bottom: 1px solid #eee; margin-bottom: 50px; }
        .page-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(0,0,0,0.05); color: #000; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 6px 16px; border-radius: 50px; margin-bottom: 16px; }
        .page-title { font-family: 'Space Grotesk', sans-serif; font-size: clamp(2.2rem, 4vw, 3rem); font-weight: 800; color: #000000; margin-bottom: 12px; }
        .page-subtitle { color: var(--text-muted); font-size: 1.05rem; }

        .legal-content { background: #ffffff; border: 1px solid #e5e5e5; border-radius: 20px; padding: 45px 50px; margin-bottom: 80px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .legal-section { margin-bottom: 36px; }
        .legal-section:last-child { margin-bottom: 0; }
        .legal-section h3 { font-family: 'Space Grotesk', sans-serif; font-size: 1.35rem; font-weight: 700; color: #000000; margin-bottom: 14px; display: flex; align-items: center; gap: 10px; }
        .legal-section p { color: var(--text-muted); margin-bottom: 12px; font-size: 1rem; }
        .legal-section ul { list-style: disc; margin-left: 24px; color: var(--text-muted); margin-bottom: 12px; }
        .legal-section li { margin-bottom: 8px; }

        /* FOOTER */
        footer { background: #fafafa; border-top: 1px solid #e5e5e5; padding: 60px 0 30px; font-size: 0.95rem; }
        .footer-inner { max-width: 1200px; margin: 0 auto; padding: 0 5%; }
        .footer-top-row { display: grid; grid-template-columns: 2fr 1fr 1fr 1.2fr; gap: 40px; margin-bottom: 40px; }
        .footer-brand p { color: var(--text-muted); font-size: 0.9rem; margin-top: 12px; max-width: 320px; }
        .footer-socials { display: flex; gap: 10px; margin-top: 16px; }
        .social-btn { width: 36px; height: 36px; border-radius: 10px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; color: #000; text-decoration: none; transition: 0.2s; }
        .social-btn:hover { background: #000; color: #fff; border-color: #000; }
        .footer-col h5 { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1rem; margin-bottom: 16px; color: #000; }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 10px; }
        .footer-col a { color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
        .footer-col a:hover { color: #000; }
        .footer-bottom-row { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 24px; font-size: 0.85rem; color: var(--text-dim); }
        .footer-bottom-badges { display: flex; gap: 16px; }
        .badge-secure { display: flex; align-items: center; gap: 6px; }

        @media (max-width: 900px) {
            .footer-top-row { grid-template-columns: 1fr 1fr; }
            .legal-content { padding: 30px 24px; }
        }
        @media (max-width: 600px) {
            .footer-top-row { grid-template-columns: 1fr; }
            .footer-bottom-row { flex-direction: column; gap: 12px; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav id="navbar">
        <a href="{{ url('/') }}" class="nav-logo">
            <div class="nav-logo-icon">L</div>
            <span class="nav-logo-text">LAPAK<span>TIFIKASI</span></span>
        </a>
        <div class="nav-actions">
            <a href="{{ url('/') }}" class="btn-nav-outline"><i class="bi bi-arrow-left"></i> Beranda</a>
            @auth
                <a href="{{ route('premium.riwayat') }}" class="btn-nav-outline"><i class="bi bi-speedometer2"></i> Dashboard</a>
            @else
                <a href="{{ url('/login') }}" class="btn-nav-outline"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
            @endauth
        </div>
    </nav>

    <!-- HEADER -->
    <header class="page-header">
        <div class="container-custom">
            <div class="page-badge"><i class="bi bi-file-earmark-text-fill"></i> Syarat & Ketentuan</div>
            <h1 class="page-title">Syarat & Ketentuan Layanan</h1>
            <p class="page-subtitle">Ketentuan penggunaan platform dan jaminan garansi di Lapaktifikasi</p>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="container-custom">
        <div class="legal-content">
            <div class="legal-section">
                <h3>1. Ketentuan Umum</h3>
                <p>Dengan mendaftar, mengakses, atau membeli layanan melalui platform <strong>Lapaktifikasi</strong>, Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui seluruh Syarat dan Ketentuan yang berlaku.</p>
            </div>

            <div class="legal-section">
                <h3>2. Akun Pengguna & Tanggung Jawab</h3>
                <ul>
                    <li>Pengguna bertanggung jawab penuh untuk menjaga kerahasiaan kata sandi dan informasi akun pribadinya.</li>
                    <li>Pengguna wajib memberikan informasi yang valid, termasuk nomor WhatsApp dan email aktif untuk keperluan pengiriman kredensial akun digital.</li>
                    <li>Dilarang keras menyalahgunakan akun digital yang telah dibeli untuk aktivitas ilegal, spamming, atau melanggar hak cipta penyedia layanan resmi.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h3>3. Pemesanan & Pembayaran</h3>
                <ul>
                    <li>Setiap pesanan yang dibuat memiliki batas waktu pembayaran (expiry time). Pesanan yang tidak dibayar dalam batas waktu tersebut akan dibatalkan secara otomatis.</li>
                    <li>Pembayaran dinyatakan sah setelah sistem menerima konfirmasi sukses dari Payment Gateway resmi.</li>
                    <li>Kredensial layanan akun digital atau akses unduhan produk digital akan diberikan seketika setelah pembayaran sukses terverifikasi.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h3>4. Kebijakan Garansi & Klaim Masalah</h3>
                <p>Lapaktifikasi berkomitmen memberikan garansi penuh sesuai dengan durasi masa aktif layanan yang tertera pada setiap varian produk:</p>
                <ul>
                    <li><strong>Masa Garansi:</strong> Garansi berlaku selama masa aktif layanan masih berjalan sejak tanggal transaksi berhasil.</li>
                    <li><strong>Klaim Garansi:</strong> Jika terjadi kendala pada akun (misal: password tidak sesuai / logout), pengguna dapat mengajukan laporan melalui menu <em>Laporan Masalah</em> di dashboard atau menghubungi WhatsApp Support.</li>
                    <li><strong>Solusi:</strong> Tim kami akan melakukan perbaikan atau penggantian kredensial akun baru maksimal 1x24 jam sejak laporan diverifikasi.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h3>5. Kebijakan Pengembalian Dana (Refund)</h3>
                <p>Pengembalian dana (refund) hanya dapat disetujui apabila produk yang dipesan kehabisan stok atau tim teknis kami tidak dapat memberikan penggantian akun yang berfungsi dalam kurun waktu 3x24 jam.</p>
            </div>

            <div class="legal-section">
                <h3>6. Perubahan Ketentuan</h3>
                <p>Lapaktifikasi berhak memperbarui Syarat dan Ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Perubahan akan berlaku segera setelah dipublikasikan pada halaman ini.</p>
            </div>
        </div>
    </main>

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
                        <li><a href="{{ url('/#faq') }}">Pusat Bantuan</a></li>
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

</body>
</html>
