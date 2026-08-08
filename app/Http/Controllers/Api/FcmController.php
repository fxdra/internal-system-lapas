<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FcmToken;
use Illuminate\Support\Facades\Log;

class FcmController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'role'  => 'required|string',
        ]);

        try {
            $token = $request->token;
            $role  = $request->role;

            // 🔥 cegah duplikat token
            FcmToken::updateOrCreate(
                ['token' => $token],
                [
                    'role' => $role,
                    'updated_at' => now()
                ]
            );

            Log::info('FCM TOKEN SAVED', [
                'token' => $token,
                'role' => $role
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Token saved'
            ]);

        } catch (\Exception $e) {

            Log::error('FCM TOKEN ERROR', [
                'msg' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to save token'
            ], 500);
        }
    }
}
