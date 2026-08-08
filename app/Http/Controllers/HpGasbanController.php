<?php

namespace App\Http\Controllers;

use App\Models\HpGasban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class HpGasbanController extends Controller
{
    // tampil data
    public function index()
    {
        $data = HpGasban::latest()->get();

        return view('admin-banceuy.hp-petugas', compact('data'));
    }

    // store + update
public function store(Request $request)
{
    $validated = $request->validate([

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */
        'id' => [
            'nullable',
            'integer',
            'exists:hp_gasbans,id'
        ],

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */
        'logo' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048'
        ],

        'title' => [
            'required',
            'string',
            'min:3',
            'max:150'
        ],

        'subtitle' => [
            'nullable',
            'string',
            'max:255'
        ],

        /*
        |--------------------------------------------------------------------------
        | UPT
        |--------------------------------------------------------------------------
        */
        'nama_upt' => [
            'required',
            'string',
            'min:3',
            'max:150'
        ],

        /*
        |--------------------------------------------------------------------------
        | PETUGAS
        |--------------------------------------------------------------------------
        */
        'foto_petugas' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:4096'
        ],

        'nama' => [
            'required',
            'string',
            'min:3',
            'max:150'
        ],

        'nip' => [
            'required',
            'numeric',
            'digits_between:8,30'
        ],

        'jabatan' => [
            'nullable',
            'string',
            'max:100'
        ],

        /*
        |--------------------------------------------------------------------------
        | HANDPHONE MULTIPLE
        |--------------------------------------------------------------------------
        */
        'foto_handphone' => [
            'nullable',
            'array',
            'max:2'
        ],

        'foto_handphone.*' => [
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:4096'
        ],

        'jenis_hp' => [
            'nullable',
            'array',
            'max:2'
        ],

        'jenis_hp.*' => [
            'nullable',
            'string',
            'max:100'
        ],

        'warna_hp' => [
            'nullable',
            'array',
            'max:2'
        ],

        'warna_hp.*' => [
            'nullable',
            'string',
            'max:50'
        ],

    ], [

        'id.exists' => 'Data tidak ditemukan',

        'logo.image' => 'Logo wajib berupa gambar',
        'logo.mimes' => 'Logo harus jpg, jpeg, png, atau webp',
        'logo.max' => 'Ukuran logo maksimal 2MB',

        'title.required' => 'Title wajib diisi',
        'title.min' => 'Title minimal 3 karakter',
        'title.max' => 'Title maksimal 150 karakter',

        'nama_upt.required' => 'Nama UPT wajib diisi',

        'foto_petugas.image' => 'Foto petugas wajib berupa gambar',

        'nama.required' => 'Nama wajib diisi',

        'nip.required' => 'NIP wajib diisi',
        'nip.numeric' => 'NIP wajib angka',

        'foto_handphone.max' =>
            'Maksimal hanya 2 foto handphone',

    ]);

    // ================= CEK DATA =================
    $data = HpGasban::find($request->id);

    /*
    |--------------------------------------------------------------------------
    | MAX 2 HP PER PETUGAS
    |--------------------------------------------------------------------------
    */

    $jumlahHp = count($request->jenis_hp ?? []);

    if ($jumlahHp > 2) {

        return response()->json([

            'success' => false,

            'message' =>
                '1 petugas maksimal hanya memiliki 2 handphone'

        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE ONLY
    |--------------------------------------------------------------------------
    */

    if (!$data) {

        // ================= UUID =================
        $uuid = (string) Str::uuid();

        $validated['barcode_id'] = $uuid;

        // ================= URL BARCODE =================
        $validated['url_barcode'] =
            url('/hp-gasban/barcode/' . $uuid);

        // ================= QR =================
        $qrCode = new QrCode(

            $validated['url_barcode']

        );

        $writer = new PngWriter();

        $result = $writer->write($qrCode);

        // ================= PATH =================
        $qrPath =
            'hp-gasban/barcode/' . $uuid . '.png';

        Storage::disk('public')->put(
            $qrPath,
            $result->getString()
        );

        // ================= SAVE =================
        $validated['img_barcode'] = $qrPath;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGO
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('logo')) {

        // hapus lama
        if (
            $data &&
            $data->logo &&
            Storage::disk('public')->exists($data->logo)
        ) {

            Storage::disk('public')->delete(
                $data->logo
            );
        }

        $validated['logo'] =
            $request->file('logo')
            ->store('hp-gasban/logo', 'public');

    } elseif ($data) {

        $validated['logo'] = $data->logo;
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO PETUGAS
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('foto_petugas')) {

        // hapus lama
        if (
            $data &&
            $data->foto_petugas &&
            Storage::disk('public')->exists(
                $data->foto_petugas
            )
        ) {

            Storage::disk('public')->delete(
                $data->foto_petugas
            );
        }

        $validated['foto_petugas'] =
            $request->file('foto_petugas')
            ->store(
                'hp-gasban/petugas',
                'public'
            );

    } elseif ($data) {

        $validated['foto_petugas'] =
            $data->foto_petugas;
    }

    /*
    |--------------------------------------------------------------------------
    | FOTO HANDPHONE MULTIPLE
    |--------------------------------------------------------------------------
    */

    $fotoHandphone = [];

    if ($request->hasFile('foto_handphone')) {

        // hapus lama
        if (
            $data &&
            is_array($data->foto_handphone)
        ) {

            foreach (
                $data->foto_handphone as $oldFoto
            ) {

                if (
                    $oldFoto &&
                    Storage::disk('public')->exists($oldFoto)
                ) {

                    Storage::disk('public')->delete(
                        $oldFoto
                    );
                }
            }
        }

        foreach (
            $request->file('foto_handphone')
            as $file
        ) {

            $fotoHandphone[] = $file->store(

                'hp-gasban/handphone',

                'public'
            );
        }

        $validated['foto_handphone'] =
            $fotoHandphone;

    } elseif ($data) {

        $validated['foto_handphone'] =
            $data->foto_handphone;
    }

    /*
    |--------------------------------------------------------------------------
    | ARRAY JSON
    |--------------------------------------------------------------------------
    */

    $validated['jenis_hp'] =
        $request->jenis_hp;

    $validated['warna_hp'] =
        $request->warna_hp;

    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */

    HpGasban::updateOrCreate(

        [
            'id' => $request->id
        ],

        $validated
    );

    return response()->json([

        'success' => true,

        'message' => 'Data berhasil disimpan'

    ]);
}

    public function showBarcode($barcode_id)
    {
        $data = HpGasban::where(
            'barcode_id',
            $barcode_id
        )->firstOrFail();
    
        return view(
            'show-data',
            compact('data')
        );
    }

    // delete
    public function destroy($id)
    {
        $data = HpGasban::findOrFail($id);

        $data->delete();

        return response()->json([

            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

}