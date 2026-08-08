<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\{Antrian, Pengikut, Pengunjung, Setting};


class PengunjungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::first();
        return view('daftar-kunjungan', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function result($id)
    {
        $setting = Setting::first();
        $pengunjung = Pengunjung::with('Pengikut')->findOrFail($id);

        return view('result_kunjungan', compact('pengunjung', 'setting'));
    }

    public function detail($id)
    {
        $pengunjung = Pengunjung::with(['pengikut', 'wbp'])
            ->findOrFail($id);

        return response()->json($pengunjung);
    }

    public function destroy($id)
    {
        $pengunjung = Pengunjung::with(['pengikut', 'wbp'])
            ->findOrFail($id);

        $pengunjung->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    // Daftar Pengunjung

    public function store(Request $request)
    {
        // Cek apakah data WBP sudah ada di tanggal kunjungan
        $exists = Pengunjung::where('no_reg_instansi', $request->no_reg_instansi)
            ->whereDate('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'no_reg_instansi' => 'Data WBP sudah tersedia pada tanggal kunjungan tersebut.'
                ]);
        }

        // Validasi form
        $request->validate([
            'sesi_kunjungan' => 'required|in:PAGI,SIANG',
            'jenis_identitas' => 'required|string',
            'nik_pengunjung'  => 'required|digits_between:8,16',
            'nama_wbp'        => 'required|string|max:255',
            'nama_pengunjung' => 'required|string|max:255',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'alamat_pengunjung' => 'required|string',
            'no_wa'           => 'required|digits_between:11,15',
            'hubungan_pengunjung' => 'required|string',
            'jumlah_anak_pengunjung' => 'required|integer|min:0',

            'titip_barang'    => 'required|in:ya,tidak',
            'no_reg_instansi' => 'required|exists:wbps,no_reg_instansi',

            'foto_ktp'        => 'required|image|max:2048',
            'foto_selfie'     => 'required|image|max:2048',
            'foto_barang'     => 'exclude_unless:titip_barang,ya|required|image|max:2048',
            'jenis_barang'    => 'exclude_unless:titip_barang,ya|required|string|max:255',
            'tanggal_kunjungan' => 'nullable|date',

            // Pengikut
            'tipe_identitas_pengikut.*' => 'nullable|string',
            'nik_pengikut.*'            => 'nullable|digits_between:8,16',
            'nama_pengikut.*'           => 'nullable|string|max:255',
            'jk_pengikut.*'             => 'nullable|string',
            'alamat_pengikut.*'         => 'nullable|string',
            'hubungan_pengikut.*'       => 'nullable|string|max:255',
            'jumlah_anak_pengikut.*'    => 'nullable|integer|min:0',
            'foto_ktp_pengikut.*'       => 'nullable|image|max:2048',
        ]);

        $pengunjung = DB::transaction(function () use ($request) {

            // Biar tidak undefined kalau titip_barang = tidak
            $fotoBarang  = null;
            $jenisBarang = null;

            // Upload file utama (masuk ke storage/app/public/...)
            $fotoKtp    = $request->file('foto_ktp')->store('ktp', 'public');
            $fotoSelfie = $request->file('foto_selfie')->store('selfie', 'public');

            if ($request->titip_barang === 'ya') {
                $fotoBarang  = $request->file('foto_barang')->store('barang', 'public');
                $jenisBarang = $request->jenis_barang;
            }

            // Generate UUID + QR
            $uuid   = Str::uuid()->toString();
            $brcode = $uuid;

            // ✅ FIX QR (tanpa named argument, compatible endroid lama)
            $qrCode = new \Endroid\QrCode\QrCode($brcode);

            // kalau versi kamu support setSize & setMargin, aktifkan ini:
            // if (method_exists($qrCode, 'setSize')) {
            //     $qrCode->setSize(50);
            // }
            // if (method_exists($qrCode, 'setMargin')) {
            //     $qrCode->setMargin(5);
            // }

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            $qrPath = 'barcode/' . $uuid . '.png';
            Storage::disk('public')->put($qrPath, $result->getString());

            // Simpan pengunjung
            $pengunjung = Pengunjung::create([
                'sesi_kunjungan' => $request->sesi_kunjungan,
                'no_reg_instansi' => $request->no_reg_instansi,
                'jenis_identitas' => $request->jenis_identitas,
                'nik_pengunjung'  => $request->nik_pengunjung,
                'nama_wbp'        => strtoupper($request->nama_wbp),
                'foto_ktp'        => $fotoKtp,
                'foto_selfie'     => $fotoSelfie,
                'nama_pengunjung' => strtoupper($request->nama_pengunjung),
                'jenis_kelamin'   => $request->jenis_kelamin,
                'alamat'          => $request->alamat_pengunjung,
                'no_wa'           => $request->no_wa,
                'hubungan'        => strtoupper($request->hubungan_pengunjung),
                'jumlah_anak'     => $request->jumlah_anak_pengunjung,

                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'titip_barang'    => $request->titip_barang === 'ya' ? 1 : 0,
                'foto_barang'     => $fotoBarang,
                'jenis_barang'    => $jenisBarang,
                'id_barcode'      => $brcode,
                'img_barcode'     => $qrPath,
                'status_barcode'  => 'pending',
            ]);

            // Simpan pengikut
            if ($request->nama_pengikut) {
                foreach ($request->nama_pengikut as $i => $nama) {
                    if (!$nama) continue;

                    $fotoKtpPengikut = null;

                    // lebih aman per index
                    if ($request->hasFile("foto_ktp_pengikut.$i")) {
                        $fotoKtpPengikut = $request->file("foto_ktp_pengikut.$i")
                            ->store('ktp_pengikut', 'public');
                    }

                    Pengikut::create([
                        'pengunjung_id'          => $pengunjung->id,
                        'tipe_identitas'         => $request->tipe_identitas_pengikut[$i] ?? null,
                        'nik_pengikut'           => $request->nik_pengikut[$i] ?? null,
                        'nama_pengikut'          => strtoupper($nama),
                        'jk_pengikut'            => $request->jk_pengikut[$i] ?? null,
                        'alamat_pengikut'        => $request->alamat_pengikut[$i] ?? null,
                        'hubungan_pengunjung'    => strtoupper($request->hubungan_pengikut[$i] ?? null),
                        'jumlah_anak_pengikut'   => $request->jumlah_anak_pengikut[$i] ?? 0,
                        'foto_ktp_pengikut'      => $fotoKtpPengikut,
                        'tanggal_kunjungan'      => $request->tanggal_kunjungan,
                    ]);
                }
            }

            return $pengunjung;
        });

        return redirect()
            ->route('result.kunjungan', $pengunjung->id)
            ->with('success', 'Data berhasil disimpan & QR Code berhasil dibuat');
    }


    // Daftar Penitipan Barang


     public function getKuota(Request $request)
{
    // ===== VALIDASI INPUT =====
    $request->validate([
        'tanggal' => 'required|date_format:Y-m-d'
    ]);

    $tanggal = $request->tanggal;

    // ===== AMBIL DATA =====
    $data = Antrian::whereDate('tanggal', $tanggal)->first();

    // ===== VALIDASI DATA KOSONG =====
    if (!$data) {
        return response()->json([
            'status' => 'empty',
            'tanggal' => $tanggal,
            'kuota' => 100,
            'message' => 'Data kuota belum diatur untuk tanggal ini'
        ]);
    }

    // ===== VALIDASI KUOTA INVALID =====
    if (!is_numeric($data->kuota) || $data->kuota < 0) {
        return response()->json([
            'status' => 'error',
            'tanggal' => $tanggal,
            'kuota' => 100,
            'message' => 'Data kuota tidak valid'
        ], 422);
    }

    // ===== RESPONSE NORMAL =====
    return response()->json([
        'status' => 'ok',
        'tanggal' => $tanggal,
        'kuota' => (int) $data->kuota
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function antrian()
    {
        $setting = Setting::first();
        return view('cek-antrian', compact('setting'));
    }


    // Cek Antrian
    public function cekAntrian(Request $request)
    {
        $nik = $request->query('nik');

        if (!$nik) {
            return response()->json(['success' => false, 'message' => 'NIK kosong']);
        }

        $pengunjung = Pengunjung::with(['wbp', 'pengikut'])
            ->where('nik_pengunjung', $nik)
            ->orderBy('tanggal_kunjungan', 'desc') // ambil yang terbaru
            ->orderBy('id', 'desc') // backup kalau tanggal sama
            ->first();

        if (!$pengunjung) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        return response()->json([
            'success' => true,
            'pengunjung' => $pengunjung
        ]);
    }
}
