<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    use HasFactory;

    protected $table = 'push_subscriptions';

    protected $fillable = [
        'user_id',
        'endpoint',
        'endpoint_hash',
        'public_key',
        'auth_token',
        'content_encoding',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Nama perangkat ramah pengguna berdasarkan user-agent.
     */
    public function getDeviceNameAttribute(): string
    {
        $ua = $this->user_agent ?? '';
        if (stripos($ua, 'iPhone') !== false) {
            return 'Apple iPhone (iOS)';
        }
        if (stripos($ua, 'iPad') !== false) {
            return 'Apple iPad (iPadOS)';
        }
        if (stripos($ua, 'Android') !== false) {
            return 'Android Mobile';
        }
        if (stripos($ua, 'Windows') !== false) {
            return 'Windows PC';
        }
        if (stripos($ua, 'Macintosh') !== false || stripos($ua, 'Mac OS X') !== false) {
            return 'Mac Desktop';
        }
        if (stripos($ua, 'Linux') !== false) {
            return 'Linux PC';
        }
        return 'Web Browser (' . substr($ua, 0, 30) . ')';
    }

    /**
     * Penyedia push service berdasarkan domain endpoint.
     */
    public function getPushProviderAttribute(): string
    {
        $ep = $this->endpoint ?? '';
        if (stripos($ep, 'fcm.googleapis.com') !== false) {
            return 'Google FCM';
        }
        if (stripos($ep, 'web.push.apple.com') !== false || stripos($ep, 'push.apple.com') !== false) {
            return 'Apple Web Push (APNs)';
        }
        if (stripos($ep, 'mozilla.com') !== false) {
            return 'Mozilla Push';
        }
        if (stripos($ep, 'windows.com') !== false) {
            return 'Microsoft WNS';
        }
        return 'WebPush Gateway';
    }
}
