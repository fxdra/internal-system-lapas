<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    

public function index()
{
    return view('x');
}


private function getIpLocation(string $ip): ?array
{
    try {

        $response = Http::timeout(10)
            ->acceptJson()
            ->get("http://ip-api.com/json/{$ip}");

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (($data['status'] ?? null) !== 'success') {
            return null;
        }

        return [
            'latitude' => $data['lat'],
            'longitude' => $data['lon'],
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
        ];

    } catch (\Throwable $e) {

        return null;

    }
}

private function isVpnOrProxy(string $ip): bool
{
    try {

        $response = Http::timeout(10)
            ->acceptJson()
            ->get(
                "https://proxycheck.io/v2/{$ip}",
                [
                    'vpn' => 1,
                    'asn' => 1,
                    'risk' => 1,
                ]
            );

        if (!$response->successful()) {
            return false;
        }

        $data = $response->json();

        return (
            isset($data[$ip]['proxy']) &&
            $data[$ip]['proxy'] === 'yes'
        );

    } catch (\Throwable $e) {

        return false;

    }
}

private function calculateDistance(
    float $lat1,
    float $lon1,
    float $lat2,
    float $lon2
): float {

    $earthRadius = 6371;

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a =
        sin($dLat / 2) *
        sin($dLat / 2) +

        cos(deg2rad($lat1)) *
        cos(deg2rad($lat2)) *

        sin($dLon / 2) *
        sin($dLon / 2);

    $c = 2 * atan2(
        sqrt($a),
        sqrt(1 - $a)
    );

    return $earthRadius * $c;
}
    
    public function register(Request $request)
{
    $validated = $request->validate([

        'username' => [
            'required',
            'string',
            'min:5',
            'max:30',
            'regex:/^[A-Za-z0-9_]+$/',
            'unique:agents,username',
        ],

        'email' => [
            'nullable',
            'email',
            'max:255',
            'unique:agents,email',
        ],

        'phone' => [
            'nullable',
            'regex:/^[0-9]{8,15}$/',
        ],

        'password' => [
            'required',
            Password::min(8)
                ->mixedCase()
                ->numbers(),
        ],

        'latitude' => [
            'required',
            'numeric',
            'between:-90,90',
        ],

        'longitude' => [
            'required',
            'numeric',
            'between:-180,180',
        ],

        'accuracy' => [
            'required',
            'numeric',
            'min:0',
        ],

        'device_fingerprint' => [
            'required',
            'string',
            'size:64',
        ],

        'role' => [
            'nullable',
            'in:SUPERADMIN,ADMIN',
        ],

    ], [

        'username.regex' =>
            'Username hanya boleh huruf, angka dan underscore.',

        'phone.regex' =>
            'Nomor telepon tidak valid.',

        'role.in' =>
            'Role tidak valid.',

    ]);

    /*
    |--------------------------------------------------------------------------
    | GPS VALIDATION
    |--------------------------------------------------------------------------
    */

    if ($validated['accuracy'] > 50) {

        return response()->json([
            'success' => false,
            'message' => 'GPS Accuracy harus maksimal 50 meter'
        ], 422);

    }

    /*
    |--------------------------------------------------------------------------
    | VPN / PROXY VALIDATION
    |--------------------------------------------------------------------------
    */

    if ($this->isVpnOrProxy($request->ip())) {

        return response()->json([
            'success' => false,
            'message' => 'VPN atau Proxy terdeteksi'
        ], 403);

    }

    /*
    |--------------------------------------------------------------------------
    | IP vs GPS VALIDATION
    |--------------------------------------------------------------------------
    */

    $ipLocation = $this->getIpLocation(
        $request->ip()
    );

    if ($ipLocation) {

        $distance = $this->calculateDistance(

            (float) $validated['latitude'],
            (float) $validated['longitude'],

            (float) $ipLocation['latitude'],
            (float) $ipLocation['longitude']

        );

        if ($distance > 100) {

            return response()->json([
                'success' => false,
                'message' => 'Lokasi GPS tidak sesuai dengan lokasi jaringan'
            ], 403);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE AGENT CODE
    |--------------------------------------------------------------------------
    */

    do {

        $agentCode =
            'AG' .
            strtoupper(
                Str::random(8)
            );

    } while (
        Agent::where(
            'agent_code',
            $agentCode
        )->exists()
    );

    /*
    |--------------------------------------------------------------------------
    | GENERATE SIGNATURE CODE
    |--------------------------------------------------------------------------
    */

    do {

        $signatureCode =
            strtoupper(
                Str::random(10)
            );

    } while (
        Agent::where(
            'signature_code',
            $signatureCode
        )->exists()
    );

    /*
    |--------------------------------------------------------------------------
    | ROLE SECURITY
    |--------------------------------------------------------------------------
    |
    | Public API tidak boleh membuat SUPERADMIN
    |
    */

    $role =
        $validated['role']
        ?? 'ADMIN';

    if ($role === 'SUPERADMIN') {

        $role = 'ADMIN';

    }

    /*
    |--------------------------------------------------------------------------
    | CREATE AGENT
    |--------------------------------------------------------------------------
    */

    $agent = Agent::create([

        'agent_code' =>
            $agentCode,

        'signature_code' =>
            $signatureCode,

        'username' =>
            trim(
                $validated['username']
            ),

        'otp' => null,

        'email' =>
            $validated['email']
            ?? null,

        'phone' =>
            $validated['phone']
            ?? null,

        'password' =>
            Hash::make(
                $validated['password']
            ),

        'agent_balance' => 0.00,

        'role' =>
            $role,

        'status' =>
            'active',

        /*
        |--------------------------------------------------------------------------
        | DEVICE DATA
        |--------------------------------------------------------------------------
        */

        'ip' =>
            $request->ip(),

        'last_ip' =>
            $request->ip(),

        'user_agent' =>
            $request->userAgent(),

        'last_user_agent' =>
            $request->userAgent(),

        'device_fingerprint' =>
            $validated['device_fingerprint'],

        'last_device_fingerprint' =>
            $validated['device_fingerprint'],

        'device_verified_at' =>
            now(),

        /*
        |--------------------------------------------------------------------------
        | GPS DATA
        |--------------------------------------------------------------------------
        */

        'latitude' =>
            $validated['latitude'],

        'longitude' =>
            $validated['longitude'],

        'accuracy' =>
            $validated['accuracy'],

        /*
        |--------------------------------------------------------------------------
        | LOGIN DATA
        |--------------------------------------------------------------------------
        */

        'last_login_at' =>
            now(),

    ]);

    /*
    |--------------------------------------------------------------------------
    | CREATE SANCTUM TOKEN
    |--------------------------------------------------------------------------
    */

    $token = $agent
        ->createToken(
            'agent-token'
        )
        ->plainTextToken;

    /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

    return response()->json([

        'success' => true,

        'message' =>
            'Register berhasil',

        'token' =>
            $token,

        'agent' => [

            'id' =>
                $agent->id,

            'agent_code' =>
                $agent->agent_code,

            'signature_code' =>
                $agent->signature_code,

            'username' =>
                $agent->username,

            'email' =>
                $agent->email,

            'phone' =>
                $agent->phone,

            'role' =>
                $agent->role,

            'status' =>
                $agent->status,

            'agent_balance' =>
                $agent->agent_balance,

            'latitude' =>
                $agent->latitude,

            'longitude' =>
                $agent->longitude,

            'accuracy' =>
                $agent->accuracy,

            'device_verified_at' =>
                $agent->device_verified_at,

            'last_login_at' =>
                $agent->last_login_at,

            'created_at' =>
                $agent->created_at,

        ],

    ], 201);
}
    
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:5',
                'max:30',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],
        ]);

        $agent = Agent::where(
            'username',
            trim($validated['username'])
        )->first();

        if (
            !$agent ||
            !Hash::check(
                $validated['password'],
                $agent->password
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        if (
            isset($agent->status) &&
            $agent->status !== 'active'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Akun dinonaktifkan'
            ], 403);
        }

        // hapus semua token lama
        $agent->tokens()->delete();

        // buat token baru
        $token = $agent->createToken(
            'agent-token'
        )->plainTextToken;

        $agent->update([
            'last_login_at' => now(),
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'agent' => $agent,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'agent' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
    
public function getList()
{
    $operatorCode = env('OPERATOR_CODE');
    $providerCode = 'PG';
    $apiSecretKey = env('API_SECRET_KEY');
    $apiUrl = env('API_URL');

    $signature = strtoupper(
        md5($operatorCode . $providerCode . $apiSecretKey)
    );

    $response = Http::get($apiUrl . '/getGameList.ashx', [
        'operatorcode' => $operatorCode,
        'providercode' => $providerCode,
        'lang' => 'en',
        'html' => 1,
        'reformatJson' => 'no',
        'signature' => $signature,
    ]);

    if (!$response->ok()) {
        return response()->json([
            'error' => 'Failed to connect to Game List API.'
        ], 500);
    }

    $decodedResponse = json_decode($response->body(), true);

    if (!$decodedResponse) {
        return response()->json([
            'error' => 'Invalid response from API.'
        ], 500);
    }

    if (
        isset($decodedResponse['errCode']) &&
        $decodedResponse['errCode'] !== '0'
    ) {
        return response()->json([
            'error' => $decodedResponse['errMsg'] ?? 'Unknown error'
        ], 500);
    }

    $gameList = [];

    if (!empty($decodedResponse['gamelist'])) {
        $gameList = json_decode($decodedResponse['gamelist'], true);

        // Jika ternyata sudah array, tidak perlu decode lagi
        if (!$gameList && is_array($decodedResponse['gamelist'])) {
            $gameList = $decodedResponse['gamelist'];
        }
    }

    return response()->json([
        'gamelist' => $gameList
    ]);
}

    
}