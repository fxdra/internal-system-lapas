<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BonWbp;
use App\Models\BonWbpLog;
use App\Models\Wbp;

class KomandanJagaController extends Controller
{
    // ================= APPROVE BON =================
    public function approve($id)
    {
        $bon = BonWbp::findOrFail($id);

        $bon->update([
            'status' => 'aktif',
            'disetujui_oleh' => auth()->id(),
            'disetujui_at' => now(),
            'jam_keluar' => now()
        ]);

        BonWbpLog::create([
            'bon_wbp_id' => $bon->id,
            'admin_id' => auth()->id(),
            'aksi' => 'approve',
            'keterangan' => 'BON disetujui Komandan Jaga'
        ]);

        return response()->json(['message' => 'BON approved']);
    }

    // ================= SELESAI BON =================
    public function selesai($id, Kplp $kplp)
    {
        DB::beginTransaction();
    
        try {
    
            $bon = BonWbp::findOrFail($id);
    
            // ================= UPDATE BON =================
            $bon->update([
                'status'       => 'selesai',
                'jam_kembali'  => now(),
                'ditutup_oleh' => auth()->id(),
                'ditutup_at'   => now(),
            ]);
    
            // ================= UPDATE WBP =================
            Wbp::where('id', $bon->wbp_id)->update([
                'status_wbp' => 'AKTIF',
                'keperluan'  => null,
            ]);
    
            // ================= LOG =================
            BonWbpLog::create([
                'bon_wbp_id' => $bon->id,
                'admin_id'   => auth()->id(),
                'aksi'       => 'selesai',
                'keterangan' => 'BON ditutup'
            ]);
    
            DB::commit();
    
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
    
                Log::info('SELESAI TOKEN', [
                    'count' => count($tokens)
                ]);
    
                if (!empty($tokens)) {
    
                    $wbp = Wbp::find($bon->wbp_id);
    
                    $body = "BON {$wbp->nama} telah selesai dan kembali ke kamar.";
    
                    foreach ($tokens as $token) {
    
                        $kplp->send(
                            token: $token,
                            title: "BON WBP",
                            body: $body,
                            data: [
                                'event'  => 'bon.finished',
                                'bon_id' => $bon->id,
                                'status' => 'selesai'
                            ]
                        );
                    }
                }
    
            } catch (\Throwable $e) {
    
                Log::error('FCM SELESAI ERROR', [
                    'message' => $e->getMessage()
                ]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'BON selesai'
            ]);
    
        } catch (\Throwable $e) {
    
            DB::rollBack();
    
            Log::error('SELESAI ERROR', [
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


    // ================= BON AKTIF =================
    public function aktif()
    {
        return BonWbp::with(['wbp','trackings'])
            ->where('status','aktif')
            ->latest()
            ->get();
    }
}