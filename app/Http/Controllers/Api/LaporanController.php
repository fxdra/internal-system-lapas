<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Buku;
use App\Models\Kamar;
use App\Models\KategoriBuku;
use App\Models\PengunjungPerpustakaan;
use App\Models\Wbp;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\FcmToken;

class LaporanController extends Controller
{
    public function index()
    {
        // ================= Statistik Ringkas =================
        $total_buku = Buku::count();
        $total_pengunjung = PengunjungPerpustakaan::count();
        $total_peminjaman = PengunjungPerpustakaan::where('status', 'PEMINJAMAN')->count();
        $total_pengembalian = PengunjungPerpustakaan::where('status', 'PENGEMBALIAN')->count();
        $total_barcode_valid = PengunjungPerpustakaan::where('status_barcode', 'VALID')->count();
        $total_barcode_expired = PengunjungPerpustakaan::where('status_barcode', 'EXPIRED')->count();
        $total_kategori = KategoriBuku::count();

        // ================= Grafik Peminjaman =================
        $grafik_harian = PengunjungPerpustakaan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('count(*) as total')
        )->groupBy('tanggal')->orderBy('tanggal')->get();

        $grafik_bulanan = PengunjungPerpustakaan::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
            DB::raw('count(*) as total')
        )->groupBy('bulan')->orderBy('bulan')->get();

        $grafik_tahunan = PengunjungPerpustakaan::select(
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('count(*) as total')
        )->groupBy('tahun')->orderBy('tahun')->get();

        // ================= Return JSON =================
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_buku' => $total_buku,
                'total_pengunjung' => $total_pengunjung,
                'total_peminjaman' => $total_peminjaman,
                'total_pengembalian' => $total_pengembalian,
                'total_barcode_valid' => $total_barcode_valid,
                'total_barcode_expired' => $total_barcode_expired,
                'total_kategori' => $total_kategori,
                'grafik_harian' => $grafik_harian,
                'grafik_bulanan' => $grafik_bulanan,
                'grafik_tahunan' => $grafik_tahunan,
            ]
        ]);
    }


    // ================= Top Pengunjung =================
    public function topPengunjung()
    {
        $topPengunjung = \App\Models\PengunjungPerpustakaan::select('nama_peminjam', 'kamar_sel')
            ->selectRaw('COUNT(*) as total_kunjungan')
            ->groupBy('nama_peminjam', 'kamar_sel')
            ->orderByDesc('total_kunjungan')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $topPengunjung
        ]);
    }

    // ================= Top Buku =================
    public function topBuku()
    {
        $topBuku = \App\Models\PengunjungPerpustakaan::with('buku:id,judul,foto') // eager load buku
            ->select('buku_id')
            ->selectRaw('COUNT(*) as total_dibaca')
            ->groupBy('buku_id')
            ->orderByDesc('total_dibaca')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'buku_id' => $item->buku_id,
                    'judul' => $item->buku->judul ?? '-',
                    'foto_buku' => $item->buku->foto ?? null,
                    'total_dibaca' => $item->total_dibaca
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $topBuku
        ]);
    }


    // ================= Data Buku =================
    public function dataBuku()
    {
        $dataBuku = Buku::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $dataBuku
        ]);
    }


    // ================= Data WBP =================

    public function dataWbp()
    {
        $wbps = Wbp::all(['id', 'nama', 'lokasi_blok', 'lokasi_sel']);
        return response()->json(['data' => $wbps]);
    }



    public function dataPengunjungPerpustakaan()
    {
        $dataBuku = PengunjungPerpustakaan::orderBy('created_at', 'desc')->with('buku')->get();

        return response()->json([
            'status' => 'success',
            'data' => $dataBuku
        ]);
    }


    public function updateStatus(Request $request)
    {
        // 1. Ambil Key dari .env (Securely)
        $clientKey = $request->header('X-API-KEY');
        $secretKey = env('SCANNER_API_KEY');

        // Cek apakah key ada dan cocok menggunakan hash_equals (Anti-hacking timing attack)
        if (!$clientKey || !hash_equals($secretKey, $clientKey)) {
            Log::warning("Akses Ilegal terdeteksi dari IP: " . $request->ip());
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ilegal: Aplikasi tidak terdaftar atau token salah.'
            ], 401);
        }

        // 2. Validasi Format Barcode
        $request->validate([
            'id_barcode' => 'required|string|max:50',
        ], [
            'id_barcode.required' => 'Gagal: ID Barcode tidak terdeteksi.',
            'id_barcode.alpha_num' => 'Gagal: Format Barcode mengandung karakter berbahaya.',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // Ambil data & kunci baris (Lock) agar tidak bisa di-scan 2 kali bersamaan
                $pengunjung = PengunjungPerpustakaan::where('id_barcode', $request->id_barcode)
                    ->lockForUpdate()
                    ->first();

                // 3. Cek Keberadaan Barcode
                if (!$pengunjung) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal: Data pengunjung tidak ditemukan.'
                    ], 404);
                }

                // 4. Cek Status (Cegah Duplicate Scan)
                if ($pengunjung->status === 'PENGEMBALIAN' || $pengunjung->status_barcode === 'EXPIRED') {
                    return response()->json([
                        'status' => 'duplicate',
                        'message' => 'Ditolak: Barcode sudah pernah diproses (EXPIRED).'
                    ], 422);
                }

                // 5. Eksekusi Update
                $pengunjung->status = 'PENGEMBALIAN';
                $pengunjung->status_barcode = 'EXPIRED';
                $pengunjung->updated_at = now();
                $pengunjung->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil: Status pengunjung berhasil diperbarui ke PENGEMBALIAN.'
                ]);
            });
        } catch (\Exception $e) {
            Log::error("Scanner System Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Masalah Jaringan: Terjadi gangguan pada koneksi database server.'
            ], 500);
        }
    }


    public function getPengembalian()
    {
        try {
            // Mengambil data yang statusnya PENGEMBALIAN ATAU status_barcode-nya EXPIRED
            $data = PengunjungPerpustakaan::with('buku') // Asumsi ada relasi ke tabel buku
                ->where('status', 'PENGEMBALIAN')
                ->orWhere('status_barcode', 'EXPIRED')
                ->orderBy('updated_at', 'desc') // Yang terbaru diperbarui ada di atas
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function getPeminjaman()
    {
        try {
            // Mengambil data yang statusnya PENGEMBALIAN ATAU status_barcode-nya EXPIRED
            $data = PengunjungPerpustakaan::with('buku') // Asumsi ada relasi ke tabel buku
                ->where('status', 'PEMINJAMAN')
                ->orWhere('status_barcode', 'VALID')
                ->orderBy('created_at', 'desc') // Yang terbaru diperbarui ada di atas
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function generateSlug($judul)
    {
        $slug = Str::slug($judul);
        $original = $slug;
        $count = 1;

        while (Buku::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function kategori()
    {
        return response()->json([
            'data' => KategoriBuku::orderBy('nama')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|min:3|max:255',
            'penulis' => 'nullable|string|max:150',
            'publisher' => 'nullable|string|max:150',
            'kategori_id' => 'required|exists:kategori_bukus,id',
            'tanggal_publish' => 'required|date|before_or_equal:today',
            'tahun' => 'nullable|digits:4',
            'sinopsis' => 'nullable|string|max:5000',
            'lokasi_rak' => 'required|string|max:100',
            'stock' => 'required|integer|min:0|max:10000',
            'status' => 'required|in:TERSEDIA,HABIS',
            'is_active' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4048',
        ]);

        // ================= AMBIL NAMA KATEGORI =================
        $kategori = KategoriBuku::find($validated['kategori_id']);

        if ($kategori) {
            $validated['kategori'] = $kategori->nama;
        } else {
            $validated['kategori'] = null;
        }

        // ================= SANITIZE =================
        $fieldsUpper = ['judul', 'penulis', 'publisher', 'lokasi_rak', 'kategori'];

        foreach ($fieldsUpper as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = strtoupper(trim(strip_tags($validated[$field])));
            }
        }

        // ================= TAMBAHAN =================
        $validated['slug'] = $this->generateSlug($validated['judul']);
        $validated['is_active'] = $request->has('is_active');

        // ================= FOTO =================
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('buku', 'public');
        }

        // ================= SIMPAN =================
        Buku::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Buku berhasil ditambahkan'
        ]);
    }


    // List semua pengunjung (dengan pagination)
    public function lihatData(Request $request)
    {
        $pengunjungs = PengunjungPerpustakaan::with('buku')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $pengunjungs
        ]);
    }

    // Simpan data pengunjung baru
    public function createPengunjung(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'buku_id' => 'required|exists:buku,id',
            'nama_peminjam' => 'required|string|max:255',
            'kamar_sel' => 'required|string|max:50',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:PEMINJAMAN,PENGEMBALIAN',
            'status_barcode' => 'required|in:VALID,EXPIRED',
            'foto_buku' => 'nullable|image|mimes:jpeg,png,jpg|max:4048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only([
            'buku_id',
            'nama_peminjam',
            'kamar_sel',
            'tanggal_pinjam',
            'tanggal_kembali',
            'status',
            'status_barcode'
        ]);

        // Auto-uppercase
        $data['nama_peminjam'] = strtoupper($data['nama_peminjam']);
        $data['kamar_sel'] = strtoupper($data['kamar_sel']);

        // Kurangi stock buku jika PEMINJAMAN
        $buku = Buku::findOrFail($data['buku_id']);
        if ($data['status'] === 'PEMINJAMAN') {
            if ($buku->stock <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stock buku tidak cukup'
                ], 400);
            }
            $buku->decrement('stock');
        }

        // Upload foto buku jika ada
        if ($request->hasFile('foto_buku')) {
            $data['foto_buku'] = $request->file('foto_buku')->store('pengunjung/foto', 'public');
        }

        // Generate unique id_barcode
        $uuid = Str::uuid()->toString();
        $data['id_barcode'] = $uuid;

        // Generate QR Code image
        $qrCode = new QrCode($uuid);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrPath = 'pengunjung/barcode/' . $uuid . '.png';
        Storage::disk('public')->put($qrPath, $result->getString());
        $data['img_barcode'] = $qrPath;

        $pengunjung = PengunjungPerpustakaan::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $pengunjung
        ]);
    }


    public function getNotifikasi()
    {
        // Ambil tanggal hari ini sesuai timezone Jakarta
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        // Ambil semua pengunjung yang tanggal_kembali sama dengan hari ini
        $pengunjungHariIni = PengunjungPerpustakaan::whereDate('tanggal_kembali', $today)->get();

        $notifikasiList = [];

        foreach ($pengunjungHariIni as $p) {
            $notifikasiList[] = [
                'nama_peminjam' => $p->nama_peminjam,
                'kamar' => $p->kamar_sel,
                'judul_buku' => $p->buku->judul ?? '-',
                'note' => 'Harap dikembalikan hari ini',
                'tanggal_pengembalian' => $p->tanggal_kembali
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $notifikasiList
        ]);
    }



public function generateKamarBulkSync()
{
    $unik = Wbp::select('lokasi_blok', 'lokasi_sel')
        ->distinct()
        ->get();

    foreach ($unik as $item) {

        $blok = $item->lokasi_blok;
        $sel  = $item->lokasi_sel;

        if (empty($blok) || empty($sel)) continue;

        $kode = $blok . '-' . $sel;

        // ================= CREATE / GET KAMAR =================
        $kamar = Kamar::firstOrCreate(
            ['kode_kamar' => $kode],
            [
                'lokasi_blok' => $blok,
                'lokasi_sel' => $sel,
                'nama_kamar' => 'Kamar ' . $kode,
            ]
        );

        // ================= UUID BARU =================
        $uuid = (string) Str::uuid();

        // ================= QR GENERATE =================
        $qrCode = new QrCode($uuid);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // 🔥 FIX: FILE NAME PAKAI UUID
        $qrPath = 'kamars/barcode/' . $uuid . '.png';

        // OPTIONAL: hapus lama (kalau ada)
        if ($kamar->img_barcode && Storage::disk('public')->exists($kamar->img_barcode)) {
            Storage::disk('public')->delete($kamar->img_barcode);
        }

        Storage::disk('public')->put($qrPath, $result->getString());

        // ================= UPDATE KAMAR =================
        $kamar->update([
            'barcode_id'  => $uuid,
            'img_barcode' => $qrPath,
        ]);

        // ================= LINK WBP =================
        Wbp::where('lokasi_blok', $blok)
            ->where('lokasi_sel', $sel)
            ->update([
                'kamar_id' => $kamar->id
            ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'QR berhasil di-generate dengan UUID sebagai identitas utama'
    ]);
}


public function resetUuidAndRegenerateQr()
{
    $kamars = Kamar::all();

    foreach ($kamars as $kamar) {

        // ================= UUID BARU =================
        $uuid = (string) Str::uuid();

        // ================= UPDATE BARCODE ID =================
        $kamar->barcode_id = $uuid;

        // ================= URL SCAN =================
        $url = url('/scan/' . $uuid);

        // ================= QR PAYLOAD =================
        $qrPayload = $url;

        // ================= GENERATE QR =================
        $qrCode = new QrCode($qrPayload);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // ================= FILE PATH (TETAP OVERWRITE PATTERN) =================
        $qrPath = 'kamars/barcode/' . $uuid . '.png';

        // ================= DELETE OLD FILE =================
        if ($kamar->img_barcode && Storage::disk('public')->exists($kamar->img_barcode)) {
            Storage::disk('public')->delete($kamar->img_barcode);
        }

        // ================= SAVE NEW FILE =================
        Storage::disk('public')->put($qrPath, $result->getString());

        // ================= UPDATE DB =================
        $kamar->img_barcode = $qrPath;
        $kamar->save();
    }

    return response()->json([
        'status' => true,
        'message' => 'UUID + QR berhasil di-reset dan di-sync (UUID + URL)'
    ]);
}



    // ==================== Kamar with WBP ====================
public function dataKamar()
{
    try {

        $kamars = Kamar::with('wbps')
            ->get()
            ->sort(function ($a, $b) {

                /*
                |--------------------------------------------------------------------------
                | SORT KODE BLOK
                |--------------------------------------------------------------------------
                */

                $kode = strcmp(
                    $a->kode_blok ?? '',
                    $b->kode_blok ?? ''
                );

                if ($kode !== 0) {
                    return $kode;
                }

                /*
                |--------------------------------------------------------------------------
                | SORT NOMOR KAMAR
                |--------------------------------------------------------------------------
                */

                preg_match('/(\d+)/', $a->lokasi_sel ?? '', $ma);
                preg_match('/(\d+)/', $b->lokasi_sel ?? '', $mb);

                $aNo = (int)($ma[1] ?? 0);
                $bNo = (int)($mb[1] ?? 0);

                return $aNo <=> $bNo;

            })
            ->values();

        return response()->json([
            'status' => true,
            'data'   => $kamars
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);

    }
}

 
public function loginApi(Request $request)
{
    $request->validate([
        'nip'       => 'required|string|max:255',
        'password'  => 'required|string|min:6|max:100',
        'fcm_token' => 'nullable|string'
    ], [
        'nip.required'      => 'NIP wajib diisi',
        'password.required' => 'Password wajib diisi',
    ]);

    // =========================
    // 1. CEK USER
    // =========================
    $admin = Admin::where('nip', trim($request->nip))->first();

    if (!$admin || !Hash::check($request->password, $admin->password)) {
        return response()->json([
            'status'  => false,
            'message' => 'NIP atau password salah'
        ], 401);
    }

    // =========================
    // 2. ROLE CHECK
    // =========================
    if ($admin->role !== 'kplp') {
        return response()->json([
            'status'  => false,
            'message' => 'Akses hanya untuk petugas KPLP'
        ], 403);
    }

    // =========================
    // 3. SINGLE SESSION SANCTUM
    // =========================
    $admin->tokens()->delete();

    $token = $admin->createToken('auth_token')->plainTextToken;

    // =========================
    // 4. SIMPAN FCM TOKEN
    // =========================
    $fcmToken = $request->input('fcm_token');

    if (!empty($fcmToken)) {

        $fcmToken = trim($fcmToken);

        try {
            FcmToken::updateOrCreate(
                ['admin_id' => $admin->id],
                ['token' => $fcmToken]
            );
        } catch (\Exception $e) {
            Log::error('FCM SAVE ERROR', [
                'admin_id' => $admin->id,
                'error'    => $e->getMessage(),
                'token'    => $fcmToken
            ]);
        }

    } else {

        Log::warning('FCM TOKEN KOSONG', [
            'admin_id' => $admin->id,
            'nip'      => $request->nip
        ]);
    }

    // =========================
    // 5. RESPONSE
    // =========================
    return response()->json([
        'status'  => true,
        'message' => 'Login berhasil',
        'data' => [
            'id'    => $admin->id,
            'nama'  => $admin->nama,
            'nip'   => $admin->nip,
            'role'  => $admin->role,
            'token' => $token
        ]
    ], 200);
}
    
    
public function loginPetugas(Request $request)
{
    $request->validate([
        'nip'       => 'required|string|max:255',
        'password'  => 'required|string|min:6|max:100',
        'fcm_token' => 'nullable|string'
    ], [
        'nip.required' => 'NIP wajib diisi',
        'password.required' => 'Password wajib diisi',
    ]);

    // =========================
    // 1. CEK USER
    // =========================
    $admin = Admin::where('nip', trim($request->nip))->first();

    if (!$admin || !Hash::check($request->password, $admin->password)) {
        return response()->json([
            'status'  => false,
            'message' => 'NIP atau password salah'
        ], 401);
    }

    // =========================
    // 2. ROLE CHECK
    // =========================
    if ($admin->role !== 'petugas') {
        return response()->json([
            'status'  => false,
            'message' => 'Akses hanya untuk petugas'
        ], 403);
    }

    // =========================
    // 3. SINGLE SESSION SANCTUM
    // =========================
    $admin->tokens()->delete();

    $token = $admin->createToken('auth_token')->plainTextToken;

    // =========================
    // 4. VALIDASI FCM TOKEN (IMPORTANT)
    // =========================
    $fcmToken = $request->input('fcm_token');

    if (!empty($fcmToken)) {

        // optional: buang spasi aneh / newline
        $fcmToken = trim($fcmToken);

        try {
            FcmToken::updateOrCreate(
                ['admin_id' => $admin->id],
                ['token' => $fcmToken]
            );
        } catch (\Exception $e) {
            Log::error('FCM SAVE ERROR', [
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
                'token' => $fcmToken
            ]);
        }
    } else {
        Log::warning('FCM TOKEN KOSONG', [
            'admin_id' => $admin->id,
            'nip' => $request->nip
        ]);
    }

    // =========================
    // 5. RESPONSE
    // =========================
    return response()->json([
        'status'  => true,
        'message' => 'Login berhasil',
        'data' => [
            'id'    => $admin->id,
            'nama'  => $admin->nama,
            'nip'   => $admin->nip,
            'role'  => $admin->role,
            'token' => $token
        ]
    ], 200);
}


    /**
     * REFRESH TOKEN
     */
    public function refreshToken(Request $request)
    {
        $admin = $request->user();

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // hapus semua token lama
        $admin->tokens()->delete();

        // buat token baru
        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Token berhasil di-refresh',
            'token' => $token
        ]);
    }

    /**
     * LOGOUT API
     */
    public function logoutApi(Request $request)
    {
        $admin = $request->user();

        if ($admin) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil'
        ]);
    }
    
    
    
    public function kamarGet()
    {
        try {

            $data = Kamar::select('id', 'kode_kamar')
                    ->whereNotNull('kode_kamar')
                    ->orderBy('kode_kamar', 'asc')
                    ->get();

            return response()->json([
                'status' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function dataStatistikWbp()
    {
        // 🔥 Base query (biar bisa reuse kalau nanti mau filter global)
        $baseQuery = Wbp::query();
    
        // ✅ TOTAL
        $total_wbp = (clone $baseQuery)->where('status_wbp' , 'AKTIF')->count();
    
        // 🔥 Helper untuk distinct count
        $distinctCount = function ($column) {
            return Wbp::whereNotNull($column)
                ->distinct()
                ->count($column);
        };
    
        // 🔥 Helper untuk group by
        $groupCount = function ($column) {
            return Wbp::select($column, DB::raw('COUNT(*) as total'))
                ->whereNotNull($column)
                ->groupBy($column)
                ->orderByDesc('total')
                ->get();
        };
    
        // ✅ DISTINCT TOTAL
        $total_negara = $distinctCount('negara');
        $total_agama = $distinctCount('agama');
        $total_jenis_kejahatan = $distinctCount('jenis_kejahatan');
        $total_blok = $distinctCount('lokasi_blok');
        $total_sel = $distinctCount('lokasi_sel');
    
        // ✅ GROUPING
        $by_negara = $groupCount('negara');
        $by_agama = $groupCount('agama');
        $by_jenis_kejahatan = $groupCount('jenis_kejahatan');
        $by_blok = $groupCount('lokasi_blok');
        $by_sel = $groupCount('lokasi_sel');
    
        // 🔥 GROUP BY KAMAR (blok + sel)
        $by_kamar = Wbp::select(
                'lokasi_blok',
                'lokasi_sel',
                DB::raw('COUNT(*) as total')
            )
            ->whereNotNull('lokasi_blok')
            ->whereNotNull('lokasi_sel')
            ->groupBy('lokasi_blok', 'lokasi_sel')
            ->orderBy('lokasi_blok')
            ->orderBy('lokasi_sel')
            ->get();
    
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_wbp' => $total_wbp,
                'total_negara' => $total_negara,
                'total_agama' => $total_agama,
                'total_jenis_kejahatan' => $total_jenis_kejahatan,
                'total_blok' => $total_blok,
                'total_sel' => $total_sel,
                'by_negara' => $by_negara,
                'by_agama' => $by_agama,
                'by_jenis_kejahatan' => $by_jenis_kejahatan,
                'by_blok' => $by_blok,
                'by_sel' => $by_sel,
                'by_kamar' => $by_kamar,
            ]
        ]);
    }

    public function scanKamar($barcode)
    {
        $barcode = trim(urldecode($barcode));
        
        $kamar = Kamar::with('wbps')
            ->where('barcode_id', $barcode)
            ->orWhere('barcode_id', $barcode . '.png')
            ->first();
    
        if (!$kamar) {
            return response()->json([
                'status' => false,
                'message' => 'Kamar tidak ditemukan',
                'debug' => $barcode
            ], 404);
        }
    
        return response()->json([
            'status' => true,
            'data' => $kamar
        ]);
    }


    public function updateStatusKamar(Request $request, $id)
    {
        // ✅ Validasi input
        $request->validate([
            'status_kamar' => 'required|in:Terbuka,Tertutup'
        ]);
    
        // 🔍 Ambil data kamar
        $kamar = Kamar::find($id);
    
        if (!$kamar) {
            return response()->json([
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }
    
        DB::beginTransaction();
    
        try {
            // ✅ Update status kamar (master)
            $kamar->status_kamar = $request->status_kamar;
            $kamar->save();
    
            // 🔥 Sinkron hanya WBP di kamar ini
            $affected = Wbp::where('kamar_id', $id)
                ->update([
                    'status_kamar' => $request->status_kamar
                ]);
    
            DB::commit();
    
            return response()->json([
                'message' => 'Berhasil update kamar & sinkron WBP',
                'data_kamar' => $kamar,
                'wbp_terupdate' => $affected // jumlah WBP yang kena update
            ]);
    
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return response()->json([
                'message' => 'Gagal update',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function uploadFotoWbp(Request $request)
    {
        try {
    
            // =========================
            // 1. AMBIL HEADER
            // =========================
            $wbpId = $request->header('X-WBP-ID');
    
            if (!$wbpId) {
                return response()->json([
                    'message' => 'wbp_id tidak ditemukan di header'
                ], 400);
            }
    
            // =========================
            // 2. VALIDASI DATA WBP
            // =========================
            $wbp = DB::table('wbps')->where('id', $wbpId)->first();
    
            if (!$wbp) {
                return response()->json([
                    'message' => 'WBP tidak ditemukan'
                ], 404);
            }
    
            // =========================
            // 3. AMBIL RAW IMAGE
            // =========================
            $imageData = file_get_contents("php://input");
    
            if (!$imageData || strlen($imageData) < 100) {
                return response()->json([
                    'message' => 'Data gambar kosong atau tidak valid'
                ], 400);
            }
    
            // =========================
            // 4. SIMPAN FILE (FIX STORAGE)
            // =========================
            $fileName = 'WBP_' . $wbpId . '_' . time() . '.jpg';
    
            $folder = storage_path('app/public/foto_wbp');
    
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }
    
            $filePath = $folder . '/' . $fileName;
    
            file_put_contents($filePath, $imageData);
    
            // =========================
            // 5. UPDATE DATABASE (FIX PATH STORAGE)
            // =========================
            DB::table('wbps')
                ->where('id', $wbpId)
                ->update([
                    'foto_wbp'   => 'storage/foto_wbp/' . $fileName,
                    'updated_at' => now(),
                ]);
    
            // =========================
            // 6. RESPONSE
            // =========================
            return response()->json([
                'message' => 'Upload berhasil',
                'wbp_id'  => $wbpId,
                'file'    => $fileName
            ], 200);
    
        } catch (\Exception $e) {
    
            return response()->json([
                'message' => 'Server error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function pending()
        {
            $data = Wbp::
                whereNotNull('foto_wbp')
                ->get();
    
            return response()->json($data);
        }
    
 
    
    
    public function generateMissingKamars()
{
    // 🔥 KAMAR YANG BELUM ADA
    $missingKamars = [
       
        9,
        10,
        // 16,
        // 17,
        // 18
    ];

    foreach ($missingKamars as $nomor) {

        // ================= FORMAT DATA =================
        $blok = 'SEL ISOLASI';

        $sel = 'SEL ISOLASI-TP Tutup Sunyi LT 1/' . $nomor;

        $kode = $blok . '-' . $sel;

        // ================= CREATE / GET KAMAR =================
        $kamar = Kamar::firstOrCreate(
            [
                'kode_kamar' => $kode
            ],
            [
                'lokasi_blok' => $blok,
                'lokasi_sel'  => $sel,
                'nama_kamar'  => 'Kamar ' . $kode,
                'status_kamar'=> 'Terbuka',
            ]
        );

        // ================= UUID =================
        $uuid = (string) Str::uuid();

        // ================= QR GENERATE =================
        $qrCode = new QrCode($uuid);

        $writer = new PngWriter();

        $result = $writer->write($qrCode);

        // ================= FILE PATH =================
        $qrPath = 'kamars/barcode/' . $uuid . '.png';

        // ================= DELETE OLD QR =================
        if (
            $kamar->img_barcode &&
            Storage::disk('public')->exists($kamar->img_barcode)
        ) {
            Storage::disk('public')->delete($kamar->img_barcode);
        }

        // ================= SAVE QR =================
        Storage::disk('public')->put(
            $qrPath,
            $result->getString()
        );

        // ================= UPDATE KAMAR =================
        $kamar->update([
            'barcode_id'  => $uuid,
            'img_barcode' => $qrPath,
        ]);
    }

    return response()->json([
        'status'  => true,
        'message' => 'Missing kamar berhasil dibuat',
    ]);
}
    
    
}


