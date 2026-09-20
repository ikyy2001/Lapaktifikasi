<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebPushController extends Controller
{
    /**
     * Pastikan konfigurasi OpenSSL berjalan mulus di environment Windows/Laragon.
     */
    private function bootstrapOpenSSL()
    {
        $possibleCnf = [
            'C:/laragon/bin/php/php-8.3.23-Win32-vs16-x64/extras/ssl/openssl.cnf',
            'C:/laragon/etc/ssl/openssl.cnf',
            'C:/laragon/bin/apache/httpd-2.4.54-win64-VS16/conf/openssl.cnf',
        ];
        foreach ($possibleCnf as $cnf) {
            if (file_exists($cnf) && !getenv('OPENSSL_CONF')) {
                putenv("OPENSSL_CONF=$cnf");
                $_ENV['OPENSSL_CONF'] = $cnf;
                break;
            }
        }
    }

    /**
     * Dapatkan instance WebPush yang terautentikasi dengan kunci VAPID.
     */
    private function getWebPushInstance(): WebPush
    {
        $this->bootstrapOpenSSL();

        $auth = [
            'VAPID' => [
                'subject' => config('webpush.vapid.subject', 'mailto:support@lapaktifikasi.com'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];

        return new WebPush($auth);
    }

    /**
     * Endpoint untuk mengambil Public VAPID Key ke frontend.
     */
    public function getPublicKey()
    {
        return response()->json([
            'status' => 'success',
            'publicKey' => config('webpush.vapid.public_key'),
        ]);
    }

    /**
     * Daftarkan atau perbarui langganan Push perangkat browser.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
            'contentEncoding' => 'nullable|string',
        ]);

        $endpoint = $validated['endpoint'];
        $endpointHash = hash('sha256', $endpoint);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint_hash' => $endpointHash],
            [
                'user_id' => Auth::check() ? Auth::id() : null,
                'endpoint' => $endpoint,
                'public_key' => $validated['keys']['p256dh'],
                'auth_token' => $validated['keys']['auth'],
                'content_encoding' => $validated['contentEncoding'] ?? 'aes128gcm',
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendaftarkan langganan Push Notification.',
            'id' => $subscription->id,
        ]);
    }

    /**
     * Batalkan langganan notifikasi untuk endpoint tertentu.
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        $endpointHash = hash('sha256', $validated['endpoint']);
        PushSubscription::where('endpoint_hash', $endpointHash)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Langganan notifikasi berhasil dihapus.',
        ]);
    }

    /**
     * Kirim notifikasi uji coba langsung ke perangkat yang meminta.
     */
    public function sendTestNotification(Request $request)
    {
        $endpoint = $request->input('endpoint');
        $subscription = null;

        if ($endpoint) {
            $endpointHash = hash('sha256', $endpoint);
            $subscription = PushSubscription::where('endpoint_hash', $endpointHash)->first();
        }

        if (!$subscription && Auth::check()) {
            $subscription = PushSubscription::where('user_id', Auth::id())->latest()->first();
        }

        if (!$subscription) {
            // Jika belum tersimpan di DB, buat instant subscription dari payload request
            if ($request->has(['endpoint', 'keys.p256dh', 'keys.auth'])) {
                $subscription = new PushSubscription([
                    'endpoint' => $request->input('endpoint'),
                    'public_key' => $request->input('keys.p256dh'),
                    'auth_token' => $request->input('keys.auth'),
                    'content_encoding' => 'aes128gcm',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ditemukan perangkat aktif yang terdaftar untuk dikirimkan notifikasi.',
                ], 404);
            }
        }

        try {
            $webPush = $this->getWebPushInstance();

            $sub = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'publicKey' => $subscription->public_key,
                'authToken' => $subscription->auth_token,
                'contentEncoding' => $subscription->content_encoding ?? 'aes128gcm',
            ]);

            $payload = json_encode([
                'title' => '⚡ Notifikasi Lapaktifikasi Aktif!',
                'body' => 'Web Push berhasil terhubung. Anda akan menerima update pesanan & promo langsung di HP.',
                'icon' => asset('assets/img/pwa/icon-192x192.png'),
                'badge' => asset('assets/img/pwa/icon-96x96.png'),
                'url' => url('/premium/katalog'),
                'timestamp' => time(),
            ]);

            $report = $webPush->sendOneNotification($sub, $payload);

            if ($report->isSuccess()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Push notification berhasil dikirim ke perangkat Anda!',
                ]);
            } else {
                if ($report->isSubscriptionExpired() && $subscription->exists) {
                    $subscription->delete();
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal mengirim push: ' . $report->getReason(),
                ], 400);
            }
        } catch (\Throwable $e) {
            Log::error('WebPush Test Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Broadcast Push Notification dari Admin ke seluruh pengguna atau segmen tertentu.
     */
    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'target' => 'nullable|string|in:all,customer,seller',
        ]);

        $query = PushSubscription::query();
        $target = $validated['target'] ?? 'all';

        if ($target === 'customer') {
            $query->whereHas('user', function ($q) {
                $q->where('id_role', 2);
            });
        } elseif ($target === 'seller') {
            $query->whereHas('user', function ($q) {
                $q->where('id_role', 3);
            });
        }

        $subscriptions = $query->get();

        if ($subscriptions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada perangkat terdaftar yang sesuai dengan target.',
            ], 404);
        }

        $webPush = $this->getWebPushInstance();
        $payload = json_encode([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'icon' => asset('assets/img/pwa/icon-192x192.png'),
            'badge' => asset('assets/img/pwa/icon-96x96.png'),
            'url' => !empty($validated['url']) ? $validated['url'] : url('/premium/katalog'),
            'timestamp' => time(),
        ]);

        $successCount = 0;
        $failedCount = 0;

        foreach ($subscriptions as $subRecord) {
            try {
                $sub = Subscription::create([
                    'endpoint' => $subRecord->endpoint,
                    'publicKey' => $subRecord->public_key,
                    'authToken' => $subRecord->auth_token,
                    'contentEncoding' => $subRecord->content_encoding ?? 'aes128gcm',
                ]);
                $webPush->queueNotification($sub, $payload);
            } catch (\Throwable $e) {
                $failedCount++;
            }
        }

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $successCount++;
            } else {
                $failedCount++;
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint', $report->getRequest()->getUri()->__toString())->delete();
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Notifikasi berhasil disiarkan: {$successCount} sukses, {$failedCount} gagal.",
            'success_count' => $successCount,
            'failed_count' => $failedCount,
        ]);
    }
}
