<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Agent;
use App\Models\Transaction;

class ApiController extends Controller
{
    private $apiUrl;
    private $operatorCode;
    private $apiSecretKey;

    public function __construct()
    {
        $this->apiUrl = env('API_URL');
        $this->operatorCode = env('OPERATOR_CODE');
        $this->apiSecretKey = env('API_SECRET_KEY');
    }
    
    private function getIPInfo($ip)
    {
        try {
            $response = Http::get("https://ipinfo.io/{$ip}/json");
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error('Error fetching IP info: ' . $e->getMessage());
            return [];
        }
    }
    
    private function getPublicIP()
    {
        try {
            $response = Http::get('https://api.ipify.org?format=json');
            $data = $response->json();
            return $data['ip'] ?? '0.0.0.0';
        } catch (\Exception $e) {
            Log::error('Error fetching public IP: ' . $e->getMessage());
            return '0.0.0.0';
        }
    }
    
    private function getClientIP(Request $request)
    {
        // Ambil dari header X-Forwarded-For jika ada
        $ip = $request->header('X-Forwarded-For') 
            ? explode(',', $request->header('X-Forwarded-For'))[0]
            : $request->ip();

        // Jika localhost, ambil IP publik
        if ($ip === '127.0.0.1') {
            $ip = $this->getPublicIP();
        }
        return $ip;
    }
    
    
    // Validasi Client
    
   private function validateClient(Request $request)
{
    $ip = $this->getClientIP($request); // Ambil IP valid (gunakan fungsi custom)
    $agentCode = $request->input('agent_code'); // Agent code
    $username = $request->input('username'); // Username
    $signature = $request->input('signature'); // Signature

    // Cari client berdasarkan username
    $client = User::where('username', $username)->first();

    if (!$client) {
        return response()->json([
            'status' => 'failed',
            'message' => 'User tidak ditemukan',
        ], 400);
    }

    // Larang penggunaan IP localhost
    if ($ip === '127.0.0.1') {
        return response()->json([
            'status' => 'failed',
            'message' => 'IP localhost tidak diperbolehkan',
        ], 400);
    }

    // Validasi IP
    if ($client->ip_address !== $ip) {
        return response()->json([
            'status' => 'failed',
            'message' => 'IP tidak valid',
            'client_ip' => $client->ip_address,
            'current_ip' => $ip,
        ], 400);
    }

    // Validasi agent code
    if ($client->agent_code !== $agentCode) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Agent code tidak valid',
        ], 400);
    }

    // Validasi signature
    $expectedSignature = strtoupper(md5($this->operatorCode . $username . $this->apiSecretKey));

    if ($signature !== $expectedSignature) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Signature tidak valid',
        ], 400);
    }

    return true;
}

    // Handle request
    
    public function handleRequest(Request $request)
    {
        // Validasi client terlebih dahulu
        $validationResponse = $this->validateClient($request);
        if ($validationResponse !== true) {
            return $validationResponse; // Kembalikan respon error jika validasi gagal
        }

        $action = $request->input('action');

        switch ($action) {
            case 'createPlayer':
                return $this->handleCreatePlayer($request);

            case 'getbalance':
                return $this->getBalance($request);

            case 'launchGame':
                return $this->handleLaunchGame($request);

            case 'deposit':
                return $this->handleDeposit();
                
             case 'withdraw':
                return $this->handleWithdraw();
                
             case 'getHistoryPlay':
                return $this->showBettingHistory();

            default:
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Aksi tidak valid',
                ], 400);
        }
    }
    
    //Create Player

    public function handleCreatePlayer(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:12|lowercase',
        ]);
        
        
        $agent = Agent::where('agent_code', $request->agent_code)->first();
        $existingUser = User::where('user_code', $request->username)->first();
        if ($existingUser) {
            if ($existingUser->status == 'blocked') {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User terblokir',
                ], 400);
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'Username sudah terdaftar',
            ], 400);
        }

        $thirdPartySignature = strtoupper(md5($this->operatorCode . $request->username . $this->apiSecretKey));
        $response = Http::get($this->apiUrl . '/createMember.aspx', [
            'operatorcode' => $this->operatorCode,
            'username' => $request->username,
            'signature' => $thirdPartySignature,
        ]);

        if ($response->ok()) {
            $responseBody = $response->json();
            if ($responseBody['errCode'] !== '0') {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Pihak ketiga gagal membuat pemain: ' . $responseBody['errMsg'],
                ], 400);
            }

            $newUser = new User();
            $newUser->user_code = $request->username;
            $newUser->status = 'aktif';
            $newUser->agent_id = $agent->id;
            $newUser->ip_address = $this->getPublicIP;
            $newUser->user_balance = 0;
            $newUser->created_at = now();
            $newUser->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Pemain berhasil dibuat',
                'data' => $responseBody,
            ], 200);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Tidak dapat terhubung ke API pihak ketiga',
        ], 500);
    }
    
    // Get Balance User
public function getBalance(Request $request)
{
    // Validasi input
    $request->validate([
        'username' => 'required|string|min:3|max:12|lowercase',
    ]);

    // Ambil data agent dan user
    $agent = Agent::where('agent_code', $request->agent_code)->first();
    $existingUser = User::where('user_code', $request->username)->first();

    // Validasi user
    if (!$existingUser || $existingUser->status === 'blocked') {
        return response()->json([
            'status' => 'failed',
            'message' => 'User tidak ditemukan atau terblokir',
        ], 400);
    }

    // Ambil transaksi terakhir user
    $transaction = Transaction::where('user_id', $existingUser->id)
        ->orderBy('created_at', 'desc')
        ->first();

    // Jika belum ada transaksi, kembalikan saldo default dari user_balance
    if (!$transaction) {
        return response()->json([
            'status' => 'success',
            'username' => $existingUser->user_code,
            'balance' => $existingUser->user_balance,
            'message' => 'Saldo default user',
        ], 200);
    }

    // Data dasar untuk proses API
    $operatorCode = $this->operatorCode;
    $password = $existingUser->password;
    $refid = Str::random(20);
    $type = 1; // 1 = credit / tarik dari provider ke user

    // Buat signature untuk getBalance
    $signature = strtoupper(md5(
        $operatorCode .
        $password .
        $transaction->provider_code .
        $existingUser->user_code .
        $refid .
        $this->apiSecretKey
    ));

    // Request saldo dari provider
    $response = Http::get($this->apiUrl . '/getBalance.aspx', [
        'operatorcode' => $operatorCode,
        'providercode' => $transaction->provider_code,
        'username' => $existingUser->user_code,
        'password' => $password,
        'signature' => $signature,
    ]);

    // Cek koneksi API provider
    if (!$response->ok()) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Gagal terhubung ke API provider',
        ], 500);
    }

    $responseBody = $response->json();

    // Validasi struktur respon
    if (!isset($responseBody['errCode']) || $responseBody['errCode'] !== '0') {
        return response()->json([
            'status' => 'failed',
            'message' => 'Gagal mendapatkan saldo: ' . ($responseBody['errMsg'] ?? 'Kesalahan tidak diketahui'),
        ], 400);
    }

    // Ambil saldo dari provider
    $providerBalance = $responseBody['balance'] ?? 0;

    // Update saldo user di sistem lokal
    $existingUser->update([
        'user_after_balance' => $providerBalance,
        'user_balance' => $providerBalance,
        'user_before_balance' => 0,
    ]);

    // Ambil ulang user setelah update
    $existingUserAfter = User::find($existingUser->id);

    // Buat signature untuk proses penarikan saldo
    $signatureWithdraw = strtoupper(md5(
        $existingUserAfter->user_balance .
        $operatorCode .
        $password .
        $transaction->provider_code .
        $refid .
        $type .
        $existingUserAfter->user_code .
        $this->apiSecretKey
    ));

    // Request untuk tarik saldo (withdraw dari provider ke user)
    $withdrawResponse = Http::get($this->apiUrl . '/makeTransfer.aspx', [
        'operatorcode' => $operatorCode,
        'providercode' => $transaction->provider_code,
        'username' => $existingUserAfter->user_code,
        'password' => $existingUserAfter->password,
        'referenceid' => $refid,
        'type' => $type,
        'amount' => $existingUserAfter->user_balance,
        'signature' => $signatureWithdraw,
    ]);

    // Cek koneksi API withdraw
    if (!$withdrawResponse->ok()) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Gagal terhubung ke API pihak ketiga saat withdraw',
        ], 500);
    }

    // Simpan transaksi withdraw ke database
    $withdrawTransaction = new Transaction();
    $withdrawTransaction->reference_id = $refid;
    $withdrawTransaction->user_id = $existingUser->id;
    $withdrawTransaction->agent_code = $agent->agent_code ?? null;
    $withdrawTransaction->user_code = $existingUser->user_code;
    $withdrawTransaction->provider_code = $transaction->provider_code ?? null;
    $withdrawTransaction->game_code = $transaction->game_code ?? null;
    $withdrawTransaction->type = 'credit';
    $withdrawTransaction->amount = $existingUserAfter->user_balance;
    $withdrawTransaction->status = 'completed';
    $withdrawTransaction->created_at = now();
    $withdrawTransaction->updated_at = now();
    $withdrawTransaction->save();

    // Response sukses
    return response()->json([
        'status' => 'success',
        'username' => $existingUserAfter->user_code,
        'balance' => $existingUserAfter->user_balance,
        'message' => 'Saldo provider berhasil dipindahkan ke saldo User',
    ], 200);
}


//Launch Game
public function handleLaunchGame(Request $request)
{
    // Validasi input
    $request->validate([
        'username' => 'required|string',
        'providercode' => 'required|string',
        'type' => 'required|string',
        'gameid' => 'nullable|string',
    ]);

    // Ambil data user
    $existingUser = User::where('user_code', $request->username)->first();

    if (!$existingUser || $existingUser->status === 'blocked') {
        return response()->json([
            'status' => 'failed',
            'message' => 'User tidak ditemukan atau terblokir',
        ], 400);
    }

    // Ambil data game berdasarkan provider dan type
    $game = Game::where('provider_code', $request->providercode)
        ->when($request->gameid, fn($q) => $q->where('game_code', $request->gameid))
        ->first();

    if (!$game) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Game tidak ditemukan',
        ], 400);
    }
    
    if ($existingUser->user_balance < 0) {
    return response()->json([
        'status' => 'failed',
        'message' => 'Saldo user tidak valid',
    ], 400);
 }

    // Ambil data agent user
    $agent = Agent::find($existingUser->agent_id);
    if (!$agent) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Agent tidak ditemukan',
        ], 400);
    }
    
    
    
    // User baru dan saldo 0 tetap boleh launch game
if ($existingUser->user_balance == 0) {

    $signature = strtoupper(md5(
        $this->operatorCode .
        $existingUser->password .
        $game->provider_code .
        $game->game_type .
        $existingUser->user_code .
        $this->apiSecretKey
    ));

    $response = Http::get($this->apiUrl . '/launchGames.aspx', [
        'operatorcode' => $this->operatorCode,
        'providercode' => $game->provider_code,
        'username' => $existingUser->user_code,
        'password' => $existingUser->password,
        'type' => $game->game_type,
        'signature' => $signature,
    ]);

    if (!$response->ok()) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Tidak dapat terhubung ke API pihak ketiga',
        ], 500);
    }

    $responseBody = $response->json();

    if (!isset($responseBody['errCode']) || $responseBody['errCode'] !== '0') {
        return response()->json([
            'status' => 'failed',
            'message' => $responseBody['errMsg'] ?? 'Gagal launch game',
        ], 400);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Game berhasil diluncurkan',
        'data' => $responseBody,
    ]);
}
    
    

    // Cek transaksi terakhir user
    $latestTransaction = Transaction::where('user_id', $existingUser->id)
        ->orderBy('created_at', 'desc')
        ->first();
       

    // Jika belum ada transaksi, lanjut ke proses launch game
    if (!$latestTransaction) {

        // Buat signature untuk launch game
        $signature = strtoupper(md5(
            $this->operatorCode .
            $existingUser->password .
            $game->provider_code .
            $game->game_type .
            $existingUser->user_code .
            $this->apiSecretKey
        ));

        // Request ke API Launch Game
        $response = Http::get($this->apiUrl . '/launchGames.aspx', [
            'operatorcode' => $this->operatorCode,
            'providercode' => $game->provider_code,
            'username' => $existingUser->user_code,
            'password' => $existingUser->password,
            'type' => $game->game_type,
            'signature' => $signature,
        ]);

        if (!$response->ok()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Tidak dapat terhubung ke API pihak ketiga (launch game)',
            ], 500);
        }

        $responseBody = $response->json();

        if (!isset($responseBody['errCode']) || $responseBody['errCode'] !== '0') {
            return response()->json([
                'status' => 'failed',
                'message' => 'Gagal meluncurkan game: ' . ($responseBody['errMsg'] ?? 'Kesalahan tidak diketahui'),
            ], 400);
        }

        // Siapkan transfer saldo user
        $refid = Str::random(20);
        $type = 0; // 0 = debit / transfer ke game

        $signatureTransfer = strtoupper(md5(
            $existingUser->user_balance .
            $this->operatorCode .
            $existingUser->password .
            $game->provider_code .
            $refid .
            $type .
            $existingUser->user_code .
            $this->apiSecretKey
        ));

        // Request ke API transfer saldo
        $depositResponse = Http::get($this->apiUrl . '/makeTransfer.aspx', [
            'operatorcode' => $this->operatorCode,
            'providercode' => $game->provider_code,
            'username' => $existingUser->user_code,
            'password' => $existingUser->password,
            'referenceid' => $refid,
            'type' => $type,
            'amount' => $existingUser->user_balance,
            'signature' => $signatureTransfer,
        ]);

        if (!$depositResponse->ok()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Tidak dapat terhubung ke API pihak ketiga untuk deposit',
            ], 500);
        }

        $depositResponseBody = $depositResponse->json();

        if (!isset($depositResponseBody['errCode']) || $depositResponseBody['errCode'] !== '0') {
            return response()->json([
                'status' => 'failed',
                'message' => 'Deposit gagal: ' . ($depositResponseBody['errMsg'] ?? 'Kesalahan tidak diketahui'),
            ], 400);
        }

        // Simpan transaksi & update saldo user
        DB::transaction(function () use ($existingUser, $agent, $game, $refid) {
            Transaction::create([
                'reference_id' => $refid,
                'user_id' => $existingUser->id,
                'agent_code' => $agent->agent_code,
                'user_code' => $existingUser->user_code,
                'provider_code' => $game->provider_code,
                'game_code' => $game->game_code,
                'type' => 'debit',
                'amount' => $existingUser->user_balance,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $existingUser->update([
                'user_before_balance' => $existingUser->user_balance,
                'user_after_balance' => 0,
                'user_balance' => 0,
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Game berhasil diluncurkan dan transfer berhasil',
            'data' => $responseBody,
        ], 200);
    }

    // Jika user sudah punya transaksi sebelumnya
    return response()->json([
        'status' => 'info',
        'message' => 'User sudah memiliki transaksi sebelumnya',
    ], 200);
}

public function handleDeposit(Request $request)
    {
        $amount     = $request->input('amount');
        $username   = $request->input('username');
        $agentCode  = $request->input('agent_code');
        $signature  = $request->input('signature');
        $minDeposit = 10000;

        //Validasi nominal
        if (!is_numeric($amount) || $amount <= 0) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Nominal tidak valid',
                'code' => 400,
            ]);
        }

        //Validasi Agent
        $agent = Agent::where('agent_code', $agentCode)->first();
        if (!$agent) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Agent code tidak valid',
                'code' => 403,
            ]);
        }

        //Validasi Signature
        if ($signature != $agent->signature) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Signature tidak valid',
                'code' => 403,
            ]);
        }

        //Validasi User
        $user = User::where('user_code', $username)->first();
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User tidak ditemukan',
                'code' => 403,
            ]);
        }

        //Validasi User Terblokir
        if ($user->status === 'blocked') {
            return response()->json([
                'status' => 'failed',
                'message' => 'User terblokir',
                'code' => 403,
            ]);
        }

        //Validasi saldo agent
        if ($agent->agent_balance < $amount) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Saldo agent tidak cukup untuk melakukan deposit',
                'code' => 403,
            ]);
        }

        //Validasi minimal deposit
        if ($amount < $minDeposit) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Minimal Deposit IDR 10.000',
                'code' => 403,
            ]);
        }

        //Update saldo (Eloquent murni)
        $agent->decrement('agent_balance', $amount);
        $user->increment('user_balance', $amount);

        //Simpan transaksi
        Depo_Wd::create([
            'user_code'  => $user->user_code,
            'agent_code' => $agent->agent_code,
            'amount'     => $amount,
            'type'       => 'deposit',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //Response sukses
        return response()->json([
            'status'  => 'success',
            'message' => 'Deposit berhasil',
            'data' => [
                'agent_balance' => $agent->fresh()->agent_balance,
                'user_balance'  => $user->fresh()->user_balance,
            ],
        ]);
    }

    /**
     * Handle Withdraw Request
     */
    public function handleWithdraw(Request $request)
    {
        $amount      = $request->input('amount');
        $username    = $request->input('username');
        $agentCode   = $request->input('agent_code');
        $signature   = $request->input('signature');
        $minWithdraw = 10000;

        //Validasi nominal
        if (!is_numeric($amount) || $amount <= 0) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Nominal tidak valid',
                'code' => 400,
            ]);
        }

        //Validasi Agent
        $agent = Agent::where('agent_code', $agentCode)->first();
        if (!$agent) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Agent code tidak valid',
                'code' => 403,
            ]);
        }

        //Validasi Signature
        if ($signature != $agent->signature) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Signature tidak valid',
                'code' => 403,
            ]);
        }

        //Validasi User
        $user = User::where('user_code', $username)->first();
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User tidak ditemukan',
                'code' => 403,
            ]);
        }

        //Validasi User Terblokir
        if ($user->status === 'blocked') {
            return response()->json([
                'status' => 'failed',
                'message' => 'User terblokir',
                'code' => 403,
            ]);
        }

        //Validasi saldo user
        if ($user->user_balance < $amount) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Saldo user tidak cukup untuk withdraw',
                'code' => 403,
            ]);
        }

        //Validasi minimal withdraw
        if ($amount < $minWithdraw) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Minimal Withdraw IDR 10.000',
                'code' => 403,
            ]);
        }

        //Update saldo (Eloquent murni)
        $user->decrement('user_balance', $amount);
        $agent->increment('agent_balance', $amount);

        //Simpan transaksi
        Depo_Wd::create([
            'user_code'  => $user->user_code,
            'agent_code' => $agent->agent_code,
            'amount'     => $amount,
            'type'       => 'withdraw',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //Response sukses
        return response()->json([
            'status'  => 'success',
            'message' => 'Withdraw berhasil',
            'data' => [
                'agent_balance' => $agent->fresh()->agent_balance,
                'user_balance'  => $user->fresh()->user_balance,
            ],
        ]);
    }
    
public function fetchBettingHistory()
{
    $operatorCode = env('OPERATOR_CODE');
    $apiSecretKey = env('API_SECRET_KEY');
    $url = env('LOG_URL');

    $versionKey = 0;
    $signature = strtoupper(md5($operatorCode . $apiSecretKey));

    $response = Http::get($url . '/fetchbykey.aspx', [
        'operatorcode' => $operatorCode,
        'versionkey' => $versionKey,
        'signature' => $signature,
    ]);

    if (!$response->ok()) {
        return response()->json(['error' => 'Failed to connect to betting history API.'], 500);
    }

    $decodedResponse = json_decode($response->body(), true);

    if (!isset($decodedResponse['result'])) {
        return response()->json(['error' => 'Invalid response format from API.'], 500);
    }

    if (isset($decodedResponse['errCode']) && $decodedResponse['errCode'] !== '0') {
        return response()->json([
            'error' => 'Failed to fetch betting history: ' . ($decodedResponse['errMsg'] ?? 'Unknown error')
        ], 500);
    }

    $bettingHistory = json_decode($decodedResponse['result'], true);

    if (!$bettingHistory || !is_array($bettingHistory)) {
        return response()->json(['error' => 'No valid betting history data found.'], 404);
    }

    // Hanya mengembalikan data hasil
    return response()->json(['betting_history' => $bettingHistory]);
}


//Agent Balance
public function getKioskBalance()
{
    $apiUrl = env('API_URL');
    $operatorCode = env('OPERATOR_CODE');
    $secretKey = env('API_SECRET_KEY');

    // Generate signature MD5
    $signature = strtoupper(md5($operatorCode . $secretKey));

    // Bangun URL dengan query string
    $url = "{$apiUrl}/checkAgentCredit.aspx";
    $url .= "?operatorcode={$operatorCode}&signature={$signature}";

    // Panggil API eksternal
    $response = Http::get($url);

    // Default response jika terjadi error
    $errMsg = 'Unable to fetch data';

    // Jika respons berhasil, ambil data
    if ($response->successful()) {
        $responseData = $response->json();
        if (isset($responseData['errCode']) && $responseData['errCode'] === '0') { // Perbaikan di sini
            return response()->json([
                'errCode' => '0',
                'data' => $responseData['data'],
                'errMsg' => $responseData['errMsg'],
            ]);
        } else {
            $errMsg = $responseData['errMsg'] ?? 'Unexpected error';
        }
    }

    // Jika gagal
    return response()->json([
        'errCode' => '500',
        'data' => null,
        'errMsg' => $errMsg,
    ]);
}

public function showBettingHistory(Request $request)
{
    $agentCode = $request->input('agent_code');
    $signature = $request->input('signature');
    $username  = $request->input('username'); // pastikan ini dikirim di request

    // Validasi Agent
    $agent = Agent::where('agent_code', $agentCode)->first();
    if (!$agent) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Agent code tidak valid',
            'code' => 403,
        ]);
    }

    // Validasi Signature
    if ($signature != $agent->signature) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Signature tidak valid',
            'code' => 403,
        ]);
    }

    // Validasi User
    $user = User::where('user_code', $username)->first();
    if (!$user) {
        return response()->json([
            'status' => 'failed',
            'message' => 'User tidak ditemukan',
            'code' => 403,
        ]);
    }

    // Validasi User Terblokir
    if ($user->status === 'blocked') {
        return response()->json([
            'status' => 'failed',
            'message' => 'User terblokir',
            'code' => 403,
        ]);
    }

    // Ambil data history sesuai agent_code
    $histories = History::where('agent_code', $agentCode)
        ->orderBy('created_at', 'desc') // atau orderBy('id', 'desc')
        ->get();

    if ($histories->isEmpty()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Tidak ada history ditemukan',
            'data' => [],
            'code' => 200,
        ]);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'History ditemukan',
        'data' => $histories,
        'code' => 200,
    ]);
}



    private function generateSignatureTrx()
    {
        $operatorCode = $this->operatorCode;
        $username = 'anis';
        $secretKey = $this->apiSecretKey;
        $rawString = $operatorCode . $username . $secretKey;
        return strtoupper(md5($rawString));
    }
}
