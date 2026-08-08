<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceLog;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function gps(Request $r)
    {
        $device = Device::firstOrCreate(
            ['device_id' => $r->device_id],
            ['android_id' => $r->android_id]
        );

        DeviceLog::create([
            'device_id' => $device->id,
            'lat' => $r->lat,
            'lon' => $r->lon
        ]);

        return response()->json(['ok' => true]);
    }
}
