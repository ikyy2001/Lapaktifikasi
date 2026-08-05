<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Dalam Pemeliharaan - Under Maintenance</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            --card-bg: rgba(255, 255, 255, 0.96);
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --warning-bg: #fef3c7;
            --warning-text: #b45309;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #1e293b;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Background Spheres */
        .ambient-glow-1 {
            position: absolute;
            top: 15%;
            left: 20%;
            width: 320px;
            height: 320px;
            background: rgba(99, 102, 241, 0.25);
            filter: blur(100px);
            border-radius: 50%;
            pointer-events: none;
        }

        .ambient-glow-2 {
            position: absolute;
            bottom: 15%;
            right: 20%;
            width: 360px;
            height: 360px;
            background: rgba(236, 72, 153, 0.2);
            filter: blur(120px);
            border-radius: 50%;
            pointer-events: none;
        }

        .maintenance-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            padding: 52px 40px;
            max-width: 580px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 10;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            position: relative;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);
        }

        .icon-wrapper i {
            font-size: 3rem;
            color: var(--primary);
            animation: pulseGear 4s infinite linear;
        }

        @keyframes pulseGear {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .badge-maintenance {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: var(--warning-bg);
            color: var(--warning-text);
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 20px;
        }

        .badge-maintenance .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: var(--warning-text);
            border-radius: 50%;
            display: inline-block;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .maintenance-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 14px;
            line-height: 1.25;
        }

        .maintenance-desc {
            color: #475569;
            font-size: 0.98rem;
            line-height: 1.65;
            margin-bottom: 32px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        @media (min-width: 480px) {
            .action-buttons {
                flex-direction: row;
                justify-content: center;
            }
        }

        .btn-primary-custom {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 14px 28px;
            border-radius: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            text-decoration: none;
        }

        .btn-outline-custom {
            background-color: transparent;
            color: #334155;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 14px 24px;
            border-radius: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 2px solid #cbd5e1;
            transition: all 0.25s ease;
        }

        .btn-outline-custom:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
            text-decoration: none;
        }

        .footer-note {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.82rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="maintenance-card">
        <div class="icon-wrapper">
            <i class="bi bi-gear-wide-connected"></i>
        </div>

        <div>
            <span class="badge-maintenance">
                <span class="pulse-dot"></span> Mode Pemeliharaan Aktif
            </span>
        </div>

        <h1 class="maintenance-title">Dashboard Sedang Dalam Pemeliharaan</h1>

        <p class="maintenance-desc">
            Saat ini fitur dashboard dan area member sedang ditingkatkan untuk memberikan layanan yang lebih baik. Halaman landing page dan login tetap dapat diakses dengan normal. Silakan kembali beberapa saat lagi.
        </p>

        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn-primary-custom">
                <i class="bi bi-house-door-fill"></i> Halaman Utama
            </a>
            <a href="{{ url('/login') }}" class="btn-outline-custom">
                <i class="bi bi-box-arrow-in-right"></i> Login System
            </a>
        </div>

        <div class="footer-note">
            &copy; {{ date('Y') }} Platform Lapaktifikasi. Hak Cipta Dilindungi.
        </div>
    </div>
</body>
</html>
