<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koneksi Terputus - Lapaktifikasi</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/pwa/icon-192x192.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 0.4; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }
        .pulse-ring {
            animation: pulse-ring 3s infinite ease-in-out;
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 selection:bg-indigo-500 selection:text-white relative overflow-hidden">
    <!-- Ambient Background Glows -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative w-full max-w-md bg-slate-800/80 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-8 text-center shadow-2xl shadow-indigo-950/50">
        <!-- Offline Icon Graphic -->
        <div class="relative mx-auto w-24 h-24 mb-6 flex items-center justify-center">
            <div class="absolute inset-0 rounded-full bg-red-500/10 pulse-ring"></div>
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-600/50 flex items-center justify-center shadow-inner">
                <i class="bi bi-wifi-off text-3xl text-red-400"></i>
            </div>
            <span class="absolute -bottom-1 -right-1 flex h-5 w-5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 border-2 border-slate-800 items-center justify-center">
                    <i class="bi bi-exclamation text-[10px] text-white font-bold"></i>
                </span>
            </span>
        </div>

        <!-- Text Description -->
        <h1 class="text-2xl font-bold text-white mb-2 tracking-tight">Koneksi Internet Terputus</h1>
        <p class="text-slate-400 text-sm leading-relaxed mb-8">
            Sepertinya perangkat Anda sedang offline atau sinyal tidak stabil. Periksa koneksi Wi-Fi atau data seluler Anda.
        </p>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <button onclick="tryReconnect()" id="btn-retry" class="w-full py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 transition-all duration-200 flex items-center justify-center gap-2 group">
                <i class="bi bi-arrow-clockwise text-base group-hover:rotate-180 transition-transform duration-500" id="retry-icon"></i>
                <span id="retry-text">Coba Hubungkan Kembali</span>
            </button>

            <button onclick="window.history.back()" class="w-full py-3 px-6 rounded-2xl bg-slate-700/50 hover:bg-slate-700 active:scale-[0.98] text-slate-300 hover:text-white text-sm font-medium border border-slate-600/50 transition-all duration-200 flex items-center justify-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Halaman Sebelumnya</span>
            </button>
        </div>

        <!-- Offline Info Cards -->
        <div class="mt-8 pt-6 border-t border-slate-700/60 grid grid-cols-2 gap-3 text-left">
            <div class="p-3 rounded-xl bg-slate-900/50 border border-slate-800/80">
                <div class="flex items-center gap-2 text-indigo-400 text-xs font-semibold mb-1">
                    <i class="bi bi-shield-check"></i>
                    <span>Data Tersimpan</span>
                </div>
                <p class="text-[11px] text-slate-400 leading-tight">
                    Halaman & riwayat akun yang pernah Anda buka tetap tersimpan di HP.
                </p>
            </div>
            <div class="p-3 rounded-xl bg-slate-900/50 border border-slate-800/80">
                <div class="flex items-center gap-2 text-emerald-400 text-xs font-semibold mb-1">
                    <i class="bi bi-lightning-charge"></i>
                    <span>Auto-Reload</span>
                </div>
                <p class="text-[11px] text-slate-400 leading-tight">
                    Halaman akan otomatis termuat kembali begitu sinyal pulih.
                </p>
            </div>
        </div>

        <!-- App Branding -->
        <div class="mt-6 flex items-center justify-center gap-2 text-slate-500 text-xs">
            <img src="{{ asset('assets/img/pwa/icon-72x72.png') }}" class="w-5 h-5 rounded" alt="Logo">
            <span>Lapaktifikasi PWA v1.0</span>
        </div>
    </div>

    <script>
        function tryReconnect() {
            const btn = document.getElementById('btn-retry');
            const icon = document.getElementById('retry-icon');
            const text = document.getElementById('retry-text');

            icon.classList.add('animate-spin');
            text.textContent = 'Memeriksa Jaringan...';
            btn.disabled = true;

            if (navigator.onLine) {
                // Sinyal pulih
                window.location.reload();
            } else {
                setTimeout(() => {
                    icon.classList.remove('animate-spin');
                    text.textContent = 'Belum Ada Internet, Coba Lagi';
                    btn.disabled = false;
                }, 1200);
            }
        }

        // Auto reload saat internet kembali menyala
        window.addEventListener('online', () => {
            document.getElementById('retry-text').textContent = 'Koneksi Pulih! Memuat...';
            setTimeout(() => {
                window.location.reload();
            }, 600);
        });
    </script>
</body>
</html>
