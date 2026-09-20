<!-- Scoped Vanilla CSS for PWA (Immune to Tailwind/Bootstrap differences) -->
<style>
    /* Default hidden states with !important to prevent any framework leaks */
    #lp-pwa-install-banner,
    #lp-pwa-ios-modal,
    #lp-pwa-welcome-modal,
    #lp-pwa-toast {
        display: none !important;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    #lp-pwa-install-banner * ,
    #lp-pwa-ios-modal * ,
    #lp-pwa-welcome-modal * {
        box-sizing: border-box;
    }

    /* Install Banner Floating Bottom (Only visible when class 'lp-show' is present) */
    #lp-pwa-install-banner.lp-show {
        display: flex !important;
        position: fixed;
        bottom: 20px;
        left: 20px;
        right: 20px;
        max-width: 420px;
        margin: 0 auto;
        background: #0f172a;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 14px 16px;
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.6), 0 0 15px rgba(79, 70, 229, 0.3);
        z-index: 99999;
        align-items: center;
        gap: 12px;
        animation: lpSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes lpSlideUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .lp-banner-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        flex-shrink: 0;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }

    .lp-banner-content {
        flex: 1;
        min-width: 0;
    }

    .lp-banner-title {
        font-size: 13px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        line-height: 1.2;
    }

    .lp-banner-desc {
        font-size: 11px;
        color: #94a3b8;
        margin: 2px 0 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .lp-banner-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .lp-btn-install {
        background: #4f46e5;
        color: #ffffff !important;
        border: none;
        border-radius: 10px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .lp-btn-install:hover {
        background: #4338ca;
    }

    .lp-btn-close {
        background: transparent;
        color: #94a3b8;
        border: none;
        border-radius: 8px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: color 0.2s, background 0.2s;
    }

    .lp-btn-close:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    /* iOS Instructions Modal */
    #lp-pwa-ios-modal.lp-show {
        display: flex !important;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 100000;
        align-items: flex-end;
        justify-content: center;
        padding: 16px;
    }

    @media (min-width: 640px) {
        #lp-pwa-ios-modal.lp-show {
            align-items: center;
        }
    }

    .lp-ios-card {
        background: #0f172a;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 24px;
        width: 100%;
        max-width: 360px;
        padding: 24px 20px;
        text-align: center;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
    }

    .lp-ios-card h3 {
        font-size: 17px;
        font-weight: 700;
        margin: 12px 0 6px 0;
        color: #ffffff;
    }

    .lp-ios-card p {
        font-size: 12px;
        color: #94a3b8;
        line-height: 1.4;
        margin: 0 0 16px 0;
    }

    .lp-ios-steps {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 14px;
        text-align: left;
        margin-bottom: 16px;
    }

    .lp-ios-step {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: #cbd5e1;
        margin-bottom: 10px;
    }

    .lp-ios-step:last-child {
        margin-bottom: 0;
    }

    .lp-ios-badge {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(79, 70, 229, 0.3);
        color: #818cf8;
        font-weight: 700;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .lp-ios-close-x {
        position: absolute;
        top: 14px;
        right: 14px;
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 16px;
        cursor: pointer;
        padding: 4px;
    }

    .lp-ios-btn-done {
        width: 100%;
        background: #4f46e5;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    /* Welcome Modal on First PWA Launch */
    #lp-pwa-welcome-modal.lp-show {
        display: flex !important;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 100002;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .lp-welcome-card {
        background: #0f172a;
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        width: 100%;
        max-width: 360px;
        padding: 28px 22px;
        text-align: center;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9), 0 0 25px rgba(79, 70, 229, 0.3);
    }

    /* Minimal Toast */
    #lp-pwa-toast.lp-show {
        display: flex !important;
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #0f172a;
        color: #ffffff;
        border: 1px solid #334155;
        border-radius: 9999px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 500;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        z-index: 100001;
        align-items: center;
        gap: 8px;
    }
</style>

<!-- Install Banner (Only pops up when installable and NOT dismissed) -->
<div id="lp-pwa-install-banner">
    <img src="{{ asset('assets/img/pwa/icon-96x96.png') }}" class="lp-banner-icon" alt="Lapaktifikasi">
    <div class="lp-banner-content">
        <div class="lp-banner-title">Pasang Lapaktifikasi</div>
        <div class="lp-banner-desc">Akses cepat & hemat kuota di beranda HP</div>
    </div>
    <div class="lp-banner-actions">
        <button id="lp-install-btn" class="lp-btn-install">Instal</button>
        <button id="lp-close-btn" class="lp-btn-close" aria-label="Tutup">&times;</button>
    </div>
</div>

<!-- iOS Safari Manual Modal (Only opens if user clicks an install button) -->
<div id="lp-pwa-ios-modal">
    <div class="lp-ios-card">
        <button id="lp-ios-close" class="lp-ios-close-x">&times;</button>
        <img src="{{ asset('assets/img/pwa/icon-96x96.png') }}" style="width: 56px; height: 56px; border-radius: 14px; margin: 0 auto;" alt="Logo">
        <h3>Pasang di iPhone / iPad</h3>
        <p>Akses Lapaktifikasi langsung dari layar beranda Apple Anda.</p>
        <div class="lp-ios-steps">
            <div class="lp-ios-step">
                <span class="lp-ios-badge">1</span>
                <span>Ketuk tombol <strong>Bagikan (Share)</strong> di bilah bawah Safari.</span>
            </div>
            <div class="lp-ios-step">
                <span class="lp-ios-badge">2</span>
                <span>Pilih <strong>Tambahkan ke Layar Utama</strong> (Add to Home Screen).</span>
            </div>
            <div class="lp-ios-step">
                <span class="lp-ios-badge">3</span>
                <span>Ketuk <strong>Tambah</strong> di sudut kanan atas.</span>
            </div>
        </div>
        <button id="lp-ios-done" class="lp-ios-btn-done">Mengerti</button>
    </div>
</div>

<!-- Welcome Modal on First PWA Launch -->
<div id="lp-pwa-welcome-modal">
    <div class="lp-welcome-card">
        <button id="lp-welcome-close" class="lp-ios-close-x">&times;</button>
        <div style="font-size: 40px; margin-bottom: 8px;">🎉</div>
        <h3 style="font-size: 18px; font-weight: 700; color: #ffffff; margin: 0 0 8px 0;">Terima Kasih!</h3>
        <p style="font-size: 13px; color: #cbd5e1; line-height: 1.5; margin: 0 0 20px 0;">
            Terima kasih telah menginstall aplikasi <strong>Lapaktifikasi</strong>. Nikmati kemudahan akses akun premium & berkas digital langsung dari layar beranda Anda.
        </p>
        <button id="lp-welcome-btn" class="lp-ios-btn-done" style="background: #4f46e5; padding: 12px;">Mulai Belanja</button>
    </div>
</div>

<!-- Simple Toast -->
<div id="lp-pwa-toast">
    <span id="lp-toast-msg"></span>
</div>

<script>
    (function () {
        // --- 1. Service Worker Silent Registration ---
        let swRegistration = null;

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js')
                    .then(function (reg) {
                        swRegistration = reg;
                    })
                    .catch(function (err) {
                        console.warn('[PWA] SW register info:', err);
                    });
            });
        }

        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function showLpToast(msg) {
            const toast = document.getElementById('lp-pwa-toast');
            const toastMsg = document.getElementById('lp-toast-msg');
            if (!toast || !toastMsg) return;
            toastMsg.textContent = msg;
            toast.classList.add('lp-show');
            setTimeout(function () {
                toast.classList.remove('lp-show');
            }, 3000);
        }

        // --- 2. Install Prompt (Clean, Non-Intrusive) ---
        let deferredPrompt = null;
        const banner = document.getElementById('lp-pwa-install-banner');
        const installBtn = document.getElementById('lp-install-btn');
        const closeBtn = document.getElementById('lp-close-btn');

        const DISMISS_KEY = 'lp_pwa_install_dismissed_v2';
        const isDismissed = function () {
            const t = localStorage.getItem(DISMISS_KEY);
            if (!t) return false;
            // Jangan ganggu selama 7 hari
            return (Date.now() - parseInt(t, 10)) < (7 * 24 * 60 * 60 * 1000);
        };

        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            deferredPrompt = e;

            // Hanya tampilkan jika belum pernah ditolak dalam 7 hari
            if (banner && !isDismissed()) {
                setTimeout(function () {
                    banner.classList.add('lp-show');
                }, 3000);
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async function () {
                if (banner) banner.classList.remove('lp-show');
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const choice = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                }
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                if (banner) banner.classList.remove('lp-show');
                localStorage.setItem(DISMISS_KEY, Date.now().toString());
            });
        }

        window.addEventListener('appinstalled', function () {
            if (banner) banner.classList.remove('lp-show');
            deferredPrompt = null;
            showLpToast('Lapaktifikasi berhasil dipasang!');
        });

        // --- First Time Opening PWA Welcome Greeting ---
        const welcomeModal = document.getElementById('lp-pwa-welcome-modal');
        const welcomeBtn = document.getElementById('lp-welcome-btn');
        const welcomeClose = document.getElementById('lp-welcome-close');

        function closeWelcomeModal() {
            if (welcomeModal) welcomeModal.classList.remove('lp-show');
        }
        if (welcomeBtn) welcomeBtn.addEventListener('click', closeWelcomeModal);
        if (welcomeClose) welcomeClose.addEventListener('click', closeWelcomeModal);

        function checkFirstTimePwaLaunch() {
            const isPwa = window.matchMedia('(display-mode: standalone)').matches ||
                          (window.navigator.standalone === true) ||
                          window.location.search.includes('source=pwa') ||
                          document.referrer.includes('android-app://');

            const WELCOME_KEY = 'lp_pwa_first_install_greeted';
            if (isPwa && !localStorage.getItem(WELCOME_KEY)) {
                localStorage.setItem(WELCOME_KEY, Date.now().toString());

                setTimeout(function () {
                    if (welcomeModal) {
                        welcomeModal.classList.add('lp-show');
                    }
                    if ('Notification' in window && Notification.permission === 'granted' && swRegistration) {
                        try {
                            swRegistration.showNotification('🎉 Selamat Datang di Lapaktifikasi!', {
                                body: 'Terima kasih telah menginstall aplikasi Lapaktifikasi. Nikmati kemudahan transaksi produk digital terpercaya.',
                                icon: '/assets/img/pwa/icon-192x192.png',
                                badge: '/assets/img/pwa/icon-96x96.png',
                                data: { url: '/premium/katalog' }
                            });
                        } catch (e) {}
                    }
                }, 800);
            }
        }

        checkFirstTimePwaLaunch();

        // --- 3. iOS Safari Modal (Hanya jika pengguna klik instalasi) ---
        const isIos = function () {
            const ua = window.navigator.userAgent.toLowerCase();
            return /iphone|ipad|ipod/.test(ua);
        };
        const isInStandalone = function () {
            return ('standalone' in window.navigator) && (window.navigator.standalone);
        };

        const iosModal = document.getElementById('lp-pwa-ios-modal');
        const iosClose = document.getElementById('lp-ios-close');
        const iosDone = document.getElementById('lp-ios-done');

        function openIosModal() {
            if (iosModal) iosModal.classList.add('lp-show');
        }
        function closeIosModal() {
            if (iosModal) iosModal.classList.remove('lp-show');
        }

        if (iosClose) iosClose.addEventListener('click', closeIosModal);
        if (iosDone) iosDone.addEventListener('click', closeIosModal);

        // --- 4. Global PWA Helper (Bisa dipanggil dari mana saja) ---
        window.LapaktifikasiPWA = {
            install: function () {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                } else if (isIos() && !isInStandalone()) {
                    openIosModal();
                } else {
                    showLpToast('Aplikasi sudah terpasang atau browser Anda sudah dalam mode PWA.');
                }
            },

            // Web Push Subscription Helper
            requestPush: async function () {
                if (!('Notification' in window) || !('PushManager' in window)) {
                    if (isIos()) {
                        alert('Pada iPhone/iPad (iOS), Apple mewajibkan aplikasi ditambahkan ke Layar Utama (Add to Home Screen) terlebih dahulu untuk mendukung fitur Push Notification (iOS 16.4+).');
                        openIosModal();
                    } else {
                        alert('Browser Anda belum mendukung Web Push Notifications.');
                    }
                    return false;
                }

                if (!swRegistration && 'serviceWorker' in navigator) {
                    swRegistration = await navigator.serviceWorker.ready;
                }

                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    alert('Izin notifikasi belum diizinkan. Silakan aktifkan melalui pengaturan browser Anda.');
                    return false;
                }

                try {
                    const keyRes = await fetch('/webpush/key');
                    const keyData = await keyRes.json();
                    if (!keyData.publicKey) throw new Error('Kunci VAPID tidak ditemukan');

                    // Convert base64 to Uint8Array
                    const padding = '='.repeat((4 - keyData.publicKey.length % 4) % 4);
                    const base64 = (keyData.publicKey + padding).replace(/\-/g, '+').replace(/_/g, '/');
                    const rawData = window.atob(base64);
                    const outputArray = new Uint8Array(rawData.length);
                    for (let i = 0; i < rawData.length; ++i) {
                        outputArray[i] = rawData.charCodeAt(i);
                    }

                    const subscription = await swRegistration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: outputArray
                    });

                    const subJson = subscription.toJSON();
                    const saveRes = await fetch('/webpush/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            endpoint: subJson.endpoint,
                            keys: subJson.keys,
                            contentEncoding: 'aes128gcm'
                        })
                    });

                    const saveResult = await saveRes.json();
                    if (saveResult.status === 'success') {
                        showLpToast('Notifikasi Web Push aktif!');
                        return true;
                    }
                    throw new Error(saveResult.message || 'Gagal menyimpan langganan');
                } catch (err) {
                    console.error('[WebPush Error]', err);
                    alert('Gagal mengaktifkan notifikasi: ' + err.message);
                    return false;
                }
            },

            testPush: async function () {
                if (!swRegistration && 'serviceWorker' in navigator) {
                    swRegistration = await navigator.serviceWorker.ready;
                }

                if (!swRegistration || !swRegistration.pushManager) {
                    await this.requestPush();
                    return;
                }

                const sub = await swRegistration.pushManager.getSubscription();
                if (!sub) {
                    const ok = await this.requestPush();
                    if (!ok) return;
                }

                const currentSub = await swRegistration.pushManager.getSubscription();
                if (!currentSub) return;

                const subJson = currentSub.toJSON();
                try {
                    const res = await fetch('/webpush/test', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            endpoint: subJson.endpoint,
                            keys: subJson.keys
                        })
                    });

                    const data = await res.json();
                    if (data.status === 'success') {
                        showLpToast('Push Notifikasi percobaan terkirim!');
                    } else {
                        alert(data.message || 'Gagal mengirim push notifikasi');
                    }
                } catch (err) {
                    alert('Terjadi kesalahan: ' + err.message);
                }
            }
        };

        // Backward compatibility
        window.installLapaktifikasiPWA = window.LapaktifikasiPWA.install;
    })();
</script>
