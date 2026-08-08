<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Support\Str;
use App\Models\{Item, Pengunjung, Setting, Titipan, Wbp};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Endroid\QrCode\{ErrorCorrectionLevel, QrCode, RoundBlockSizeMode};
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class TitipanController extends Controller
{
    public function titip()
    {
        $setting = Setting::first();
        return view('titip-barang', compact('setting'));
    }

    public function titipBarang(Request $request)
    {
        $nik = $request->query('nik');

        if (!$nik) {
            return response()->json(['success' => false, 'message' => 'NIK kosong']);
        }

        $pengunjung = Pengunjung::where('nik_pengunjung', $nik)
            ->orderBy('tanggal_kunjungan', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if (!$pengunjung) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        return response()->json([
            'success' => true,
            'pengunjung' => $pengunjung
        ]);
    }

    public function store(Request $request)
    {
        // ================== VALIDASI ==================
        $request->validate([
            'sesi_kunjungan' => 'nullable|string',
            'titip_barang'   => 'required|in:ya,tidak',

            'jenis_identitas' => 'required|string',
            'nik_pengunjung'  => 'required|digits_between:8,16',
            'nama_pengunjung' => 'required|string|max:255',

            'nama_wbp'        => 'required|string|max:255',
            'no_reg_instansi' => 'required|exists:wbps,no_reg_instansi',

            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'alamat_pengunjung' => 'required|string',
            'no_wa'           => 'required|digits_between:11,15',
            'hubungan_pengunjung' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',

            'foto_ktp'    => 'required|image|max:2048',
            'foto_selfie' => 'required|image|max:2048',

            // ================== LIST BARANG (HANYA JIKA titip_barang = ya) ==================
            'barang' => 'required_if:titip_barang,ya|array|min:1',
            'barang.*.barang_dari'   => 'required_if:titip_barang,ya|in:keluarga,passmart',
            'barang.*.jenis_barang'  => 'required_if:titip_barang,ya|string|max:255',
            'barang.*.jumlah_barang' => 'required_if:titip_barang,ya|integer|min:1',
            'barang.*.foto_barang'   => 'required_if:titip_barang,ya|image|max:2048',
        ], [
            'barang.required_if' => 'Minimal harus menambahkan 1 barang titipan.',
            'barang.min'         => 'Minimal harus menambahkan 1 barang titipan.',
        ]);

        // ================== SIMPAN DALAM TRANSACTION ==================
        $titip = DB::transaction(function () use ($request) {

            // ================== Ambil WBP ==================
            $wbp = Wbp::where('no_reg_instansi', $request->no_reg_instansi)->first();
            $wbpId = $wbp ? $wbp->id : null;

            // ================== Upload file utama ==================
            $fotoKtp    = $request->file('foto_ktp')->store('ktp', 'public');
            $fotoSelfie = $request->file('foto_selfie')->store('selfie', 'public');

            // ================== Generate UUID + QR ==================
            $uuid   = Str::uuid()->toString();
            $brcode = $uuid;

            // QR Code
            $qrCode = new \Endroid\QrCode\QrCode($brcode);

            // if (method_exists($qrCode, 'setSize')) {
            //     $qrCode->setSize(250);
            // }
            // if (method_exists($qrCode, 'setMargin')) {
            //     $qrCode->setMargin(10);
            // }

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            $qrPath = 'titip_barang/barcode/' . $uuid . '.png';
            Storage::disk('public')->put($qrPath, $result->getString());

            // ================== Simpan MASTER titip_barang ==================
            $titip = Titipan::create([
                'sesi_kunjungan' => $request->sesi_kunjungan,

                'wbp_id' => $wbpId,
                'no_reg_instansi' => $request->no_reg_instansi,

                'jenis_identitas' => $request->jenis_identitas,
                'nik_pengunjung'  => $request->nik_pengunjung,
                'nama_pengunjung' => strtoupper($request->nama_pengunjung),

                'nama_wbp'        => strtoupper($request->nama_wbp),
                'jenis_kelamin'   => $request->jenis_kelamin,
                'alamat_pengunjung' => $request->alamat_pengunjung,
                'no_wa'           => $request->no_wa,
                'hubungan_pengunjung' => strtoupper($request->hubungan_pengunjung),


                'tanggal_kunjungan' => $request->tanggal_kunjungan,

                'foto_ktp'    => $fotoKtp,
                'foto_selfie' => $fotoSelfie,

                'titip_barang' => $request->titip_barang, // optional kalau kolom ada

                // barcode
                'id_barcode'     => $brcode,
                'img_barcode'    => $qrPath,
                'status_barcode' => 'pending',
            ]);

            // ================== Simpan ITEM barang (jika ya) ==================
            if ($request->titip_barang === 'ya') {

                foreach ($request->barang as $i => $item) {

                    $fotoBarangPath = null;

                    if ($request->hasFile("barang.$i.foto_barang")) {
                        $fotoBarangPath = $request->file("barang.$i.foto_barang")
                            ->store('titip_barang/barang', 'public');
                    }

                    Item::create([
                        'titip_barang_id' => $titip->id,
                        'barang_dari'     => $item['barang_dari'],
                        'jenis_barang'    => $item['jenis_barang'],
                        'jumlah_barang'   => $item['jumlah_barang'],
                        'foto_barang'     => $fotoBarangPath,
                    ]);
                }
            }

            return $titip;
        });

        return redirect()
            ->route('result.titip_barang', $titip->id)
            ->with('success', 'Titip barang berhasil disimpan & QR Code berhasil dibuat.');
    }


    public function result($id)
    {
        $setting = Setting::first();
        $titip = Titipan::with(['wbp', 'items'])->findOrFail($id);

        return view('result_titipan', compact('titip', 'setting'));
    }
}
