<!-- PWA Floating Quick Access Widget (Install & Push Notification) -->
<div id="pwa-widget-container" class="fixed bottom-5 right-5 z-40 flex flex-col items-end gap-2.5">
    <!-- Quick Action Popup Sheet -->
    <div id="pwa-quick-sheet" class="hidden flex-col bg-slate-900/95 text-white backdrop-blur-xl border border-slate-700/80 rounded-2xl p-4 shadow-2xl shadow-indigo-950/70 w-80 mb-1 transform transition-all duration-300 origin-bottom-right">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/img/pwa/icon-72x72.png') }}" class="w-6 h-6 rounded" alt="Logo">
                <span class="text-xs font-bold tracking-tight text-white">Fitur Pintar Lapaktifikasi</span>
            </div>
            <button id="pwa-sheet-close" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 text-xs">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="space-y-3 py-3 text-xs">
            <!-- Push Notification Section -->
            <div class="p-2.5 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                    <div id="pwa-push-status-icon" class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold text-slate-200">Push Notifikasi</div>
                        <div id="pwa-push-status-text" class="text-[10px] text-slate-400 truncate">Hemat kuota & bebas biaya WA</div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <button id="pwa-push-action-btn" onclick="togglePushSubscription()" class="px-2.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-[11px] shadow transition-all">
                        Aktifkan
                    </button>
                </div>
            </div>

            <!-- Test Push Notification Button (appears when subscribed) -->
            <div id="pwa-push-test-box" class="hidden">
                <button onclick="testPushNotification()" id="pwa-test-push-btn" class="w-full py-2 px-3 rounded-xl bg-slate-800 hover:bg-slate-700 border border-indigo-500/40 text-indigo-300 hover:text-white text-xs font-semibold flex items-center justify-center gap-2 transition-all">
                    <i class="bi bi-send-check"></i>
                    <span>Kirim Notifikasi Percobaan</span>
                </button>
            </div>

            <!-- App Install Section -->
            <div id="pwa-sheet-install-box" class="p-2.5 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5 min-w-0 pr-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold text-slate-200">Pasang di Beranda</div>
                        <div class="text-[10px] text-slate-400 truncate">Akses 1-klik fullscreen</div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <button onclick="window.installLapaktifikasiPWA()" class="px-2.5 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-semibold text-[11px] shadow transition-all">
                        Pasang
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-2 border-t border-slate-800/80 text-[10px] text-slate-500 text-center flex items-center justify-center gap-1">
            <i class="bi bi-shield-lock text-indigo-400"></i>
            <span>Enkripsi VAPID & PWA Standalone Resmi</span>
        </div>
    </div>

    <!-- Floating Trigger Icon Button -->
    <button id="pwa-quick-trigger" class="w-12 h-12 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white shadow-xl shadow-indigo-900/60 flex items-center justify-center text-lg active:scale-95 transition-all duration-200 relative group border border-indigo-400/30" title="Menu Cepat PWA & Notifikasi" aria-label="Menu Cepat PWA">
        <i class="bi bi-bell-fill group-hover:scale-110 transition-transform"></i>
        <span id="pwa-badge-dot" class="absolute top-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
    </button>
</div>

<!-- Bottom Banner for Auto Install Prompt (Mobile) -->
<div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-20 sm:max-w-md z-40 transform translate-y-36 opacity-0 pointer-events-none transition-all duration-300 ease-out">
    <div class="bg-slate-900/95 text-white backdrop-blur-md border border-slate-700/80 rounded-2xl p-4 shadow-2xl shadow-indigo-950/60 flex items-center gap-3.5">
        <img src="{{ asset('assets/img/pwa/icon-96x96.png') }}" class="w-12 h-12 rounded-xl shadow-md flex-shrink-0 border border-slate-700" alt="Lapaktifikasi">
        
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-white tracking-tight leading-tight">Instal Lapaktifikasi</h4>
            <p class="text-xs text-slate-400 truncate mt-0.5">Akses cepat & hemat kuota di layar utama HP</p>
        </div>

        <div class="flex items-center gap-1.5 flex-shrink-0">
            <button id="pwa-install-btn" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white text-xs font-semibold shadow-md shadow-indigo-600/30 transition-all">
                Instal
            </button>
            <button id="pwa-close-btn" class="p-2 text-slate-400 hover:text-slate-200 active:scale-90 rounded-xl hover:bg-slate-800 transition-colors" aria-label="Tutup">
                <i class="bi bi-x-lg text-xs"></i>
            </button>
        </div>
    </div>
</div>

<!-- iOS Install Instruction Modal (Safari) -->
<div id="pwa-ios-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden items-end sm:items-center justify-center p-4">
    <div class="bg-slate-900 text-white border border-slate-700/80 rounded-3xl p-6 max-w-sm w-full shadow-2xl relative animate-fade-in">
        <button id="pwa-ios-close" class="absolute top-4 right-4 text-slate-400 hover:text-white p-2">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="text-center">
            <img src="{{ asset('assets/img/pwa/icon-96x96.png') }}" class="w-16 h-16 rounded-2xl mx-auto mb-3 shadow-lg border border-slate-700" alt="Logo">
            <h3 class="text-lg font-bold">Pasang di iPhone / iPad</h3>
            <p class="text-xs text-slate-400 mt-1 mb-4 leading-relaxed">
                Nikmati kemudahan akses Lapaktifikasi langsung dari layar utama Apple Anda.
            </p>
            <div class="bg-slate-800/80 rounded-2xl p-4 text-left space-y-2.5 text-xs text-slate-300 border border-slate-700/50 mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-indigo-600/30 text-indigo-400 font-bold flex items-center justify-center text-xs">1</span>
                    <span>Ketuk tombol <strong>Bagikan</strong> (<i class="bi bi-box-arrow-up text-indigo-400 font-bold"></i>) di Safari bawah.</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-indigo-600/30 text-indigo-400 font-bold flex items-center justify-center text-xs">2</span>
                    <span>Gulir ke bawah dan pilih <strong>"Tambahkan ke Layar Utama"</strong>.</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-indigo-600/30 text-indigo-400 font-bold flex items-center justify-center text-xs">3</span>
                    <span>Ketuk <strong>Tambah</strong> di pojok kanan atas.</span>
                </div>
            </div>
            <button id="pwa-ios-done" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs">
                Mengerti
            </button>
        </div>
    </div>
</div>

<!-- Offline / Online Toast Notification -->
<div id="pwa-network-toast" class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 px-4 py-2 rounded-full text-xs font-semibold shadow-xl transition-all duration-300 -translate-y-20 opacity-0 pointer-events-none flex items-center gap-2">
    <span id="pwa-toast-dot" class="w-2 h-2 rounded-full"></span>
    <span id="pwa-toast-text"></span>
</div>

<script>
    (function () {
        // --- 1. Service Worker Registration ---
        let swRegistration = null;

        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        swRegistration = registration;
                        console.log('[PWA] Service Worker aktif.');
                        checkPushSubscriptionState();
                    })
                    .catch((err) => {
                        console.warn('[PWA] Service Worker gagal terdaftar:', err);
                    });
            });
        }

        // --- 2. Push Notification Subscription Handling ---
        const pushStatusText = document.getElementById('pwa-push-status-text');
        const pushActionBtn = document.getElementById('pwa-push-action-btn');
        const pushTestBox = document.getElementById('pwa-push-test-box');
        const pushStatusIcon = document.getElementById('pwa-push-status-icon');

        async function checkPushSubscriptionState() {
            if (!('Notification' in window) || !('PushManager' in window)) {
                if (pushStatusText) pushStatusText.textContent = 'Browser tidak mendukung push';
                if (pushActionBtn) pushActionBtn.style.display = 'none';
                return;
            }

            if (!swRegistration) {
                try {
                    swRegistration = await navigator.serviceWorker.ready;
                } catch (e) {
                    return;
                }
            }

            const subscription = await swRegistration.pushManager.getSubscription();

            if (subscription && Notification.permission === 'granted') {
                updatePushUiState(true);
            } else if (Notification.permission === 'denied') {
                updatePushUiState(false, 'Diblokir di pengaturan browser');
            } else {
                updatePushUiState(false, 'Belum diaktifkan');
            }
        }

        function updatePushUiState(isSubscribed, customMessage = null) {
            if (isSubscribed) {
                if (pushStatusText) pushStatusText.textContent = 'Notifikasi Aktif';
                if (pushActionBtn) {
                    pushActionBtn.textContent = 'Terhubung';
                    pushActionBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-500');
                    pushActionBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-500');
                }
                if (pushTestBox) pushTestBox.classList.remove('hidden');
                if (pushStatusIcon) {
                    pushStatusIcon.classList.remove('text-indigo-400', 'bg-indigo-500/20');
                    pushStatusIcon.classList.add('text-emerald-400', 'bg-emerald-500/20');
                }
            } else {
                if (pushStatusText) pushStatusText.textContent = customMessage || 'Hemat kuota & bebas biaya WA';
                if (pushActionBtn) {
                    pushActionBtn.textContent = 'Aktifkan';
                    pushActionBtn.classList.remove('bg-emerald-600', 'hover:bg-emerald-500');
                    pushActionBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-500');
                }
                if (pushTestBox) pushTestBox.classList.add('hidden');
                if (pushStatusIcon) {
                    pushStatusIcon.classList.remove('text-emerald-400', 'bg-emerald-500/20');
                    pushStatusIcon.classList.add('text-indigo-400', 'bg-indigo-500/20');
                }
            }
        }

        window.togglePushSubscription = async function () {
            if (!('Notification' in window) || !('PushManager' in window)) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Didukung',
                        text: 'Browser Anda belum mendukung Web Push Notifications.',
                    });
                }
                return;
            }

            if (!swRegistration) {
                swRegistration = await navigator.serviceWorker.ready;
            }

            // Minta izin ke pengguna
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                updatePushUiState(false, 'Izin notifikasi ditolak');
                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Izin Ditolak',
                        text: 'Anda menolak izin notifikasi. Silakan aktifkan melalui ikon gembok di sebelah URL browser Anda.',
                    });
                }
                return;
            }

            try {
                // Ambil Public VAPID Key dari backend
                const keyRes = await fetch('/webpush/key');
                const keyData = await keyRes.json();
                if (!keyData.publicKey) {
                    throw new Error('Gagal mengambil kunci VAPID publik');
                }

                const convertedVapidKey = urlBase64ToUint8Array(keyData.publicKey);

                // Subscribe pushManager
                const subscription = await swRegistration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: convertedVapidKey,
                });

                // Kirim subscription ke backend Laravel
                const subJson = subscription.toJSON();
                const saveRes = await fetch('/webpush/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        endpoint: subJson.endpoint,
                        keys: subJson.keys,
                        contentEncoding: (PushManager.supportedContentEncodings || ['aes128gcm'])[0],
                    })
                });

                const saveResult = await saveRes.json();
                if (saveResult.status === 'success') {
                    updatePushUiState(true);
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notifikasi Aktif!',
                            text: 'Perangkat Anda berhasil terhubung. Anda akan menerima update pesanan secara langsung.',
                            confirmButtonColor: '#4f46e5'
                        });
                    }
                } else {
                    throw new Error(saveResult.message || 'Gagal menyimpan langganan');
                }
            } catch (err) {
                console.error('[PWA Push Error]', err);
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengaktifkan',
                        text: err.message,
                    });
                }
            }
        };

        window.testPushNotification = async function () {
            if (!swRegistration) {
                swRegistration = await navigator.serviceWorker.ready;
            }

            const subscription = await swRegistration.pushManager.getSubscription();
            if (!subscription) {
                window.togglePushSubscription();
                return;
            }

            const testBtn = document.getElementById('pwa-test-push-btn');
            if (testBtn) {
                testBtn.disabled = true;
                testBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Mengirim...';
            }

            try {
                const subJson = subscription.toJSON();
                const res = await fetch('/webpush/test', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        endpoint: subJson.endpoint,
                        keys: subJson.keys
                    })
                });

                const data = await res.json();
                if (data.status === 'success') {
                    showNetworkToast('Push notifikasi percobaan terkirim!', true);
                } else {
                    throw new Error(data.message || 'Gagal mengirim notifikasi uji coba');
                }
            } catch (err) {
                console.error('[PWA Test Error]', err);
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Uji Coba',
                        text: err.message,
                    });
                }
            } finally {
                if (testBtn) {
                    testBtn.disabled = false;
                    testBtn.innerHTML = '<i class="bi bi-send-check"></i> <span>Kirim Notifikasi Percobaan</span>';
                }
            }
        };

        // --- 3. Quick Action Popup Trigger & Controls ---
        const quickTrigger = document.getElementById('pwa-quick-trigger');
        const quickSheet = document.getElementById('pwa-quick-sheet');
        const sheetClose = document.getElementById('pwa-sheet-close');

        if (quickTrigger && quickSheet) {
            quickTrigger.addEventListener('click', () => {
                quickSheet.classList.toggle('hidden');
                quickSheet.classList.toggle('flex');
            });
        }
        if (sheetClose && quickSheet) {
            sheetClose.addEventListener('click', () => {
                quickSheet.classList.add('hidden');
                quickSheet.classList.remove('flex');
            });
        }

        // --- 4. Install Prompt Handling (Android & Chromium) ---
        let deferredPrompt = null;
        const installBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const closeBtn = document.getElementById('pwa-close-btn');

        const DISMISS_KEY = 'lapaktifikasi_pwa_dismissed';
        const isRecentlyDismissed = () => {
            const dismissedTime = localStorage.getItem(DISMISS_KEY);
            if (!dismissedTime) return false;
            return (Date.now() - parseInt(dismissedTime, 10)) < (3 * 24 * 60 * 60 * 1000);
        };

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            if (installBanner && !isRecentlyDismissed()) {
                setTimeout(() => {
                    installBanner.classList.remove('translate-y-36', 'opacity-0', 'pointer-events-none');
                }, 2000);
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                installBanner.classList.add('translate-y-36', 'opacity-0', 'pointer-events-none');
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('[PWA] Pengguna memasang aplikasi.');
                }
                deferredPrompt = null;
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (installBanner) {
                    installBanner.classList.add('translate-y-36', 'opacity-0', 'pointer-events-none');
                }
                localStorage.setItem(DISMISS_KEY, Date.now().toString());
            });
        }

        window.addEventListener('appinstalled', () => {
            if (installBanner) {
                installBanner.classList.add('translate-y-36', 'opacity-0', 'pointer-events-none');
            }
            deferredPrompt = null;
            showNetworkToast('Aplikasi Lapaktifikasi berhasil dipasang!', true);
        });

        // --- 5. iOS Safari Guidance ---
        const isIos = () => /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
        const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

        window.showIosPwaPrompt = function () {
            const iosModal = document.getElementById('pwa-ios-modal');
            if (iosModal) {
                iosModal.classList.remove('hidden');
                iosModal.classList.add('flex');
            }
        };

        const iosModal = document.getElementById('pwa-ios-modal');
        const iosClose = document.getElementById('pwa-ios-close');
        const iosDone = document.getElementById('pwa-ios-done');
        const closeIosModal = () => {
            if (iosModal) {
                iosModal.classList.add('hidden');
                iosModal.classList.remove('flex');
            }
        };
        if (iosClose) iosClose.addEventListener('click', closeIosModal);
        if (iosDone) iosDone.addEventListener('click', closeIosModal);

        window.installLapaktifikasiPWA = function () {
            if (deferredPrompt) {
                deferredPrompt.prompt();
            } else if (isIos() && !isInStandaloneMode()) {
                window.showIosPwaPrompt();
            } else {
                showNetworkToast('Aplikasi sudah terpasang di perangkat Anda.', true);
            }
        };

        // --- 6. Online / Offline Notification Toast ---
        const toast = document.getElementById('pwa-network-toast');
        const toastDot = document.getElementById('pwa-toast-dot');
        const toastText = document.getElementById('pwa-toast-text');
        let toastTimeout = null;

        function showNetworkToast(message, isOnline) {
            if (!toast) return;
            clearTimeout(toastTimeout);

            toastText.textContent = message;
            if (isOnline) {
                toast.className = 'fixed top-5 left-1/2 transform -translate-x-1/2 z-50 px-4 py-2 rounded-full text-xs font-semibold shadow-xl transition-all duration-300 bg-emerald-600 text-white flex items-center gap-2';
                toastDot.className = 'w-2 h-2 rounded-full bg-white animate-pulse';
            } else {
                toast.className = 'fixed top-5 left-1/2 transform -translate-x-1/2 z-50 px-4 py-2 rounded-full text-xs font-semibold shadow-xl transition-all duration-300 bg-red-600 text-white flex items-center gap-2';
                toastDot.className = 'w-2 h-2 rounded-full bg-white animate-ping';
            }

            toast.classList.remove('-translate-y-20', 'opacity-0', 'pointer-events-none');

            toastTimeout = setTimeout(() => {
                toast.classList.add('-translate-y-20', 'opacity-0', 'pointer-events-none');
            }, 3500);
        }

        window.addEventListener('online', () => {
            showNetworkToast('Koneksi internet terhubung kembali.', true);
        });

        window.addEventListener('offline', () => {
            showNetworkToast('Koneksi internet terputus. Mode offline aktif.', false);
        });
    })();
</script>
