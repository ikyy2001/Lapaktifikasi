<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected string $token;

    public function __construct()
    {
        $this->token = (string) config('services.fonnte.token', '');
    }

    public function sendText(string $target, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            return [
                'status' => false,
                'reason' => 'exception: ' . $e->getMessage(),
            ];
        }
    }
}
