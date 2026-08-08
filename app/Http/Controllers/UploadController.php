<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $r)
    {
        $device = Device::firstOrCreate(
            ['device_id' => $r->device_id],
            ['android_id' => $r->android_id]
        );

        $file = $r->file('image');

        $name = time() . '_' . $file->getClientOriginalName();

        $path = $file->storeAs('uploads', $name, 'public');

        // ❗ anti duplicate
        if (Upload::where('file_path', $path)->exists()) {
            return ['skip' => true];
        }

        Upload::create([
            'device_id' => $device->id,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName()
        ]);

        return ['ok' => true];
    }
}
