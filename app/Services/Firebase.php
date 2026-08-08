<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\FcmToken;
use App\Models\Wbp;
use App\Models\Kamar;

class Firebase
{
    protected array $credentials;
    protected string $projectId;

    public function __construct()
    {
        $path = base_path(env('FIREBASE_CREDENTIALS'));

        if (!file_exists($path)) {
            throw new \Exception('Firebase credentials file not found.');
        }

        $this->credentials = json_decode(file_get_contents($path), true);
        $this->projectId = env('FIREBASE_PROJECT_ID');
    }

    /**
     * =========================
     * SEND FCM (FIXED)
     * =========================
     */
    public function send(
        string $token,
        string $title,
        string $body,
        array $data = []
    ) {
        $accessToken = $this->getAccessToken();

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $token,

                // 🔥 WAJIB biar notif muncul di background / killed state
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],

                // ⚙️ DATA untuk logic app
                'data' => $this->normalizeData(array_merge([
                    'title' => $title,
                    'body'  => $body,
                ], $data)),

                // 🔥 Android behavior fix
                'android' => [
                    'priority' => 'HIGH',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'bonwbp_channel_v7',
                    ],
                ],
            ]
        ];

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        if (!$response->successful()) {

            Log::error('FCM SEND FAILED', [
                'token' => $token,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            // 🧹 auto cleanup token rusak
            if (str_contains($response->body(), 'UNREGISTERED')) {
                FcmToken::where('token', $token)->delete();
            }
        }

        Log::info('FCM SENT', [
            'token' => $token,
            'status' => $response->status(),
        ]);

        return $response->json();
    }

    /**
     * =========================
     * APPROVE NOTIF
     * =========================
     */
    public function notifyApprove($bon, $admin)
    {
        $tokens = FcmToken::query()
            ->whereHas('admin', function ($q) {
                $q->where('role', 'petugas');
            })
            ->whereNotNull('token')
            ->pluck('token')
            ->unique()
            ->values()
            ->toArray();

        Log::info('KPLP TOKENS', [
            'count' => count($tokens)
        ]);

        if (empty($tokens)) {
            Log::warning('NO KPLP TOKENS FOUND');
            return;
        }

        $wbp = Wbp::find($bon->wbp_id);
        $kamar = Kamar::find($bon->kamar_asal_id);

        if (!$wbp || !$kamar) {
            Log::error('RELATION MISSING', [
                'wbp' => $bon->wbp_id,
                'kamar' => $bon->kamar_asal_id,
            ]);
            return;
        }

        $body = "BON {$admin->role} atas nama {$wbp->nama} dari kamar {$kamar->lokasi_blok} - {$kamar->lokasi_sel}";

        foreach ($tokens as $token) {
            try {
                $this->send(
                    token: $token,
                    title: "BON WBP DISETUJUI",
                    body: $body,
                    data: [
                        'event' => 'bon.approved',
                        'bon_id' => $bon->id,
                        'status' => 'disetujui'
                    ]
                );
            } catch (\Throwable $e) {
                Log::error('FCM LOOP ERROR', [
                    'token' => $token,
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * =========================
     * GOOGLE ACCESS TOKEN
     * =========================
     */
    protected function getAccessToken()
    {
        $jwt = $this->generateJwt();

        $response = Http::asForm()->post($this->credentials['token_uri'], [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Failed generate access token');
        }

        return $response->json()['access_token'];
    }

    /**
     * =========================
     * JWT GENERATOR
     * =========================
     */
    protected function generateJwt()
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];

        $now = time();

        $payload = [
            'iss'   => $this->credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => $this->credentials['token_uri'],
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];

        $headerEncoded  = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $signatureInput = $headerEncoded . '.' . $payloadEncoded;

        $privateKey = openssl_pkey_get_private($this->credentials['private_key']);

        openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return $signatureInput . '.' . $this->base64UrlEncode($signature);
    }

    /**
     * =========================
     * BASE64 URL SAFE
     * =========================
     */
    protected function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * =========================
     * NORMALIZE DATA
     * =========================
     */
    protected function normalizeData(array $data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = (string) $value;
        }

        return $result;
    }
}