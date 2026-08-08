<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BonWbp;
use App\Models\BonWbpLog;
use App\Models\Wbp;
use App\Models\Kamar;
use App\Models\BonWbpTracking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Services\Firebase;
use App\Services\Kplp;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Auth;


class BonWbpController extends Controller
{
    // ================= STORE BON =================
   public function store(Request $request, Firebase $firebase)
    {
    try {

        $request->validate([
            'wbp_id' => 'required|integer|exists:wbps,id',
            'kamar_asal_id' => 'required|integer|exists:kamars,id',
            'keperluan' => 'required|string|min:5|max:255',
        ]);

        DB::beginTransaction();

        $admin = auth()->user();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin belum login'
            ], 401);
        }

        // ================= CEK BON AKTIF =================
        $existingBon = BonWbp::where('wbp_id', $request->wbp_id)
            ->where('status', '!=', 'selesai')
            ->first();

        if ($existingBon) {
            return response()->json([
                'success' => false,
                'message' => 'WBP masih dalam proses BON aktif'
            ], 422);
        }

        // ================= CREATE BON =================
        $bon = BonWbp::create([
            'nomor_bon' => 'BON-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
            'wbp_id' => $request->wbp_id,
            'kamar_asal_id' => $request->kamar_asal_id,
            'dibuat_oleh' => $admin->id,
            'unit_asal' => $admin->role,
            'keperluan' => $request->keperluan,
            'status' => 'menunggu'
        ]);

        // ================= UPDATE WBP =================
        Wbp::where('id', $request->wbp_id)->update([
            'kamar_id' => $request->kamar_asal_id,
            'keperluan' => $request->keperluan,
            'status_wbp' => 'BON',
        ]);

        // ================= LOG =================
        BonWbpLog::create([
            'bon_wbp_id' => $bon->id,
            'admin_id' => $admin->id,
            'aksi' => 'create',
            'keterangan' => 'BON dibuat + WBP diupdate'
        ]);

        DB::commit();

        // =====================================================
        // 🔥 FCM OPTIMIZED (1x request per token, filtered)
        // =====================================================
        try {

            $tokens = FcmToken::query()
                ->join('admins', 'admins.id', '=', 'fcm_tokens.admin_id')
                ->where('admins.role', 'petugas')
                ->pluck('fcm_tokens.token')
                ->unique()
                ->values()
                ->toArray();

            if (!empty($tokens)) {

                $wbp = Wbp::find($request->wbp_id);
                $kamar = Kamar::find($request->kamar_asal_id);
            
                $body = "BON {$admin->role} atas nama {$wbp->nama} dari BLOK {$kamar->kode_blok} - Kamar {$kamar->lokasi_sel}";
            
                foreach ($tokens as $token) {
            
                    $firebase->send(
                        token: $token,
                        title: "BON WBP BARU",
                        body: $body,
                        data: [
                            'event' => 'bon.created',
                            'bon_id' => $bon->id,
                            'status' => $bon->status
                        ]
                    );
                }
            }

        } catch (\Exception $e) {
            Log::error("FCM ERROR: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'BON berhasil dibuat',
            'data' => $bon
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        Log::error('BON STORE ERROR: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server'
        ], 500);
    }
}

    // ================= OPTIONAL: ALL DEVICE TOKENS =================
    private function getAllDeviceTokens()
    {
        // GANTI SESUAI TABLE KAMU
        // contoh: users punya fcm_token

        return \App\Models\User::whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();
    }

    // ================= INDEX =================
    public function index()
    {
        return BonWbp::with(['wbp','trackings','logs'])->latest()->get();
    }

    // ================= SHOW =================
    public function show($id)
    {
        return BonWbp::with(['wbp','trackings','logs'])->findOrFail($id);
    }

    // ================= TRACKING =================
    public function tracking(Request $request, $id)
    {
        $bon = BonWbp::findOrFail($id);

        $track = BonWbpTracking::create([
            'bon_wbp_id' => $bon->id,
            'lokasi' => $request->lokasi,
            'admin_id' => auth()->id(),
            'waktu' => now()
        ]);

        BonWbpLog::create([
            'bon_wbp_id' => $bon->id,
            'admin_id' => auth()->id(),
            'aksi' => 'tracking',
            'keterangan' => $request->lokasi
        ]);

        return response()->json($track);
    }

    // ================= LATEST =================
    public function latest()
    {
        $bon = BonWbp::orderByDesc('id')->first();

        if (!$bon) {
            return response()->json([
                'id' => 0,
                'title' => 'BON WBP',
                'message' => 'Tidak ada data',
                'timestamp' => now()->timestamp
            ]);
        }

        return response()->json([
            'id' => $bon->id,
            'title' => 'BON WBP #' . $bon->nomor_bon,
            'message' => 'Status: ' . $bon->status,
            'status' => $bon->status,
            'nomor_bon' => $bon->nomor_bon,
            'timestamp' => now()->timestamp
        ]);
    }
    
    
    public function getApproveBon()
    {
       $logs = BonWbp::with([
                    'wbp',
                    'kamarAsal'
                ])
                ->where('status', 'aktif')
                ->latest()
                ->limit(100)
                ->get();
    
        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
    
    
    public function getCancelBon()
    {
       $logs = BonWbp::with([
                    'wbp',
                    'kamarAsal'
                ])
                ->where('status', 'batal')
                ->latest()
                ->limit(100)
                ->get();
    
        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
    
    public function getLogBon()
    {
        $logs = BonWbpLog::with([
                'bonWbp.wbp',
                'bonWbp.kamarAsal',
                'admin'
            ])
            ->latest()
            ->limit(100)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    public function showDataLog($bonWbpId)
    {
        $logs = BonWbpLog::with([
                'bonWbp.wbp',
                'bonWbp.kamarAsal',
                'admin'
            ])
            ->where('bon_wbp_id', $bonWbpId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
    
    
    // ================= LIST APPROVAL =================
    public function approvalList()
    {
        $data = BonWbp::with(['wbp'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    
    // ================= CLOSE BON =================
    public function closeBon($id)
    {
        $bon = BonWbp::findOrFail($id);
    
        $bon->update([
            'status'        => 'selesai',
            'ditutup_at'    => now(),
            'ditutup_oleh'  => Auth::id(),
            'jam_kembali'   => now(),
            'keperluan'     => 'Bon ditutup oleh petugas' // tetap / bisa diubah kalau mau
        ]);
        
        BonWbpLog::create([
            'bon_wbp_id' => $bon->id,
            'admin_id' => Auth::id(),
            'aksi' => 'selesai',
            'keterangan' => 'BON ditolak oleh petugas'
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'BON berhasil ditutup'
        ]);
    }

   public function approve(Request $request, $id, Kplp $kplp)
    {
    try {

        Log::info('APPROVE START');

        $bon = BonWbp::findOrFail($id);
        $admin = Auth::user();

        Log::info('ADMIN', [
            'id' => $admin?->id,
            'role' => $admin?->role
        ]);

        if (!$admin || $admin->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak punya akses approve'
            ], 403);
        }
        DB::beginTransaction();

        $bon->update([
            'status' => 'aktif',
            'disetujui_at' => now(),
            'disetujui_oleh' => $admin->id,
        ]);

        DB::commit();

        Log::info('BON UPDATED');

        $tokens = FcmToken::query()
            ->join('admins', 'admins.id', '=', 'fcm_tokens.admin_id')
            ->where('admins.role', 'kplp')
            ->pluck('fcm_tokens.token')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        Log::info('TOKEN RESULT', [
            'count' => count($tokens),
            'tokens' => $tokens
        ]);

        if (count($tokens) === 0) {

            Log::warning('TOKEN KPLP KOSONG');

            return response()->json([
                'success' => true,
                'message' => 'BON disetujui, tetapi token KPLP tidak ditemukan'
            ]);
        }

        $wbp = Wbp::find($bon->wbp_id);

        $body = "BON disetujui untuk {$wbp->nama}";

        foreach ($tokens as $token) {

            Log::info('SEND TOKEN', [
                'token' => $token
            ]);

            $result = $kplp->send(
                token: $token,
                title: "BON WBP",
                body: $body,
                data: [
                    'event' => 'bon.approved',
                    'bon_id' => $bon->id,
                    'status' => $bon->status
                ]
            );

            Log::info('SEND RESULT', [
                'response' => $result
            ]);
        }

        Log::info('APPROVE FINISH');

        return response()->json([
            'success' => true,
            'message' => 'BON disetujui'
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error('APPROVE ERROR', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    
    // ================= REJECT =================
   public function reject(Request $request, $id, Kplp $kplp)
    {
    DB::beginTransaction();

    try {

        Log::info('REJECT START');

        $bon = BonWbp::findOrFail($id);
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'petugas') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak punya akses reject'
            ], 403);
        }

        // ================= UPDATE BON =================
        $bon->update([
            'status'       => 'batal',
            'ditolak_at'   => now(),
            'ditolak_oleh' => $admin->id,
        ]);

        // ================= UPDATE WBP =================
        Wbp::where('id', $bon->wbp_id)->update([
            'status_wbp' => 'AKTIF',
            'keperluan'  => null,
        ]);

        // ================= LOG =================
        BonWbpLog::create([
            'bon_wbp_id' => $bon->id,
            'admin_id'   => $admin->id,
            'aksi'       => 'batal',
            'keterangan' => 'BON ditolak oleh petugas'
        ]);

        DB::commit();

        // ================= FCM =================
        $tokens = FcmToken::query()
            ->join('admins', 'admins.id', '=', 'fcm_tokens.admin_id')
            ->where('admins.role', 'kplp')
            ->pluck('fcm_tokens.token')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        Log::info('TOKEN KPLP', [
            'count' => count($tokens)
        ]);

        if (!empty($tokens)) {

            $wbp = Wbp::find($bon->wbp_id);

            $body = "BON ditolak untuk {$wbp->nama}";

            foreach ($tokens as $token) {

                $kplp->send(
                    token: $token,
                    title: "BON WBP",
                    body: $body,
                    data: [
                        'event'  => 'bon.rejected',
                        'bon_id' => $bon->id,
                        'status' => $bon->status
                    ]
                );
            }
        }

        Log::info('REJECT FINISH');

        return response()->json([
            'success' => true,
            'message' => 'BON ditolak'
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        Log::error('REJECT ERROR', [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile(),
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    
    
    public function storeTracking(Request $request, Kplp $kplp)
    {
    try {

        $request->validate([
            'bon_wbp_id' => 'required|integer|exists:bon_wbps,id',
            'lokasi'     => 'required|string|max:255',
        ]);

        $bon = BonWbp::findOrFail($request->bon_wbp_id);

        $tracking = BonWbpTracking::updateOrCreate(
            [
                'bon_wbp_id' => $request->bon_wbp_id,
            ],
            [
                'lokasi'   => $request->lokasi,
                'admin_id' => Auth::id(),
                'waktu'    => now(),
            ]
        );

        // ================= FCM =================
        try {

            $tokens = FcmToken::query()
                ->join('admins', 'admins.id', '=', 'fcm_tokens.admin_id')
                ->where('admins.role', 'kplp')
                ->pluck('fcm_tokens.token')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            Log::info('TRACKING TOKEN', [
                'count' => count($tokens)
            ]);

            if (!empty($tokens)) {

                $wbp = Wbp::find($bon->wbp_id);

                $body = "{$wbp->nama} sekarang berada di {$tracking->lokasi}";

                foreach ($tokens as $token) {

                    $kplp->send(
                        token: $token,
                        title: "TRACKING WBP",
                        body: $body,
                        data: [
                            'event'       => 'bon.tracking',
                            'bon_id'      => $bon->id,
                            'tracking_id' => $tracking->id,
                            'lokasi'      => $tracking->lokasi,
                        ]
                    );
                }
            }

        } catch (\Throwable $e) {

            Log::error('TRACKING FCM ERROR', [
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lokasi WBP '.$wbp->nama.' berhasil diperbarui ke '.$tracking->lokasi,
            'data'    => $tracking,
        ], 200);

    } catch (\Throwable $e) {

        Log::error('TRACKING STORE ERROR', [
            'message' => $e->getMessage(),
            'line'    => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
    
    
}