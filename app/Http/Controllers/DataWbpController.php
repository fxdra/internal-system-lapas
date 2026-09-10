<?php

namespace App\Http\Controllers;

use App\Models\Wbp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Kamar;
use Carbon\Carbon;
use App\Models\Mutasi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\WbpKlinik;

class DataWbpController extends Controller
{

    // ================= SEARCH AJAX =================
    public function search(Request $request)
    {
        $query = $request->get('query');

        $results = Wbp::where('nama', 'like', "%{$query}%")
            ->orWhere('no_reg_instansi', 'like', "%{$query}%")
            ->limit(10)
            ->get(['nama', 'no_reg_instansi']);

        return response()->json($results);
    }

    /** Menandai data WBP yang memiliki No. Reg atau Nama duplikat.*/
    private function markDuplicateData($wbps): void
    {
        // ================= DUPLIKAT NO REG =================
        $duplicateNoReg = array_flip(
            Wbp::select('no_reg_instansi')
                ->whereNotNull('no_reg_instansi')
                ->where('no_reg_instansi', '!=', '')
                ->groupBy('no_reg_instansi')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('no_reg_instansi')
                ->all()
        );

        // ================= DUPLIKAT NAMA =================
        $duplicateNama = array_flip(
            Wbp::select('nama')
                ->whereNotNull('nama')
                ->where('nama', '!=', '')
                ->groupBy('nama')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('nama')
                ->all()
        );

        // ================= MARK DUPLIKAT =================
        foreach ($wbps as $item) {

            $item->duplicate_no_reg =
                !empty($item->no_reg_instansi)
                && isset($duplicateNoReg[$item->no_reg_instansi]);

            $item->duplicate_nama =
                !empty($item->nama)
                && isset($duplicateNama[$item->nama]);
        }
    }

    private function applyAuditFilter($collection, ?string $audit)
    {
        switch ($audit) {

            case 'belum_ada_foto':
                return $collection->filter(
                    fn($w) => !$w->has_foto
                );

            case 'data_lengkap':
                return $collection->filter(
                    fn($w) => $w->is_data_complete
                );

            case 'belum_lengkap':
                return $collection->filter(
                    fn($w) => !$w->is_data_complete
                );

            default:
                return $collection;
        }
    }

    /** Mengambil statistik audit kelengkapan data WBP.*/
    private function getAuditStatistics(): array
    {
        // ================= TOTAL WBP =================
        $total = Wbp::count();

        // ================= DUPLIKAT NO REG =================
        $duplicateNoReg = Wbp::select('no_reg_instansi')
            ->whereNotNull('no_reg_instansi')
            ->where('no_reg_instansi', '!=', '')
            ->groupBy('no_reg_instansi')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('no_reg_instansi');

        $totalDuplicateNoReg = $duplicateNoReg->isNotEmpty()
            ? Wbp::whereIn('no_reg_instansi', $duplicateNoReg)->count()
            : 0;

        // ================= DUPLIKAT NAMA =================
        $duplicateNama = Wbp::select('nama')
            ->whereNotNull('nama')
            ->where('nama', '!=', '')
            ->groupBy('nama')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('nama');

        $totalDuplicateNama = $duplicateNama->isNotEmpty()
            ? Wbp::whereIn('nama', $duplicateNama)->count()
            : 0;

        // ================= EKSPIRASI KOSONG =================
        $totalEkspirasiKosong = Wbp::where(function ($q) {
            $q->whereNull('ekspirasi')
                ->orWhere('ekspirasi', '');
        })->count();

        // ================= AUDIT BERBASIS ACCESSOR =================
        $collection = Wbp::all();

        $this->markDuplicateData($collection);

        $totalBelumAdaFoto = $this->applyAuditFilter(
            $collection,
            'belum_ada_foto'
        )->count();

        $totalDataLengkap = $this->applyAuditFilter(
            $collection,
            'data_lengkap'
        )->count();

        $totalBelumLengkap = $this->applyAuditFilter(
            $collection,
            'belum_lengkap'
        )->count();

        // ================= RETURN =================
        return [
            'total'                 => $total,
            'duplicate_no_reg'      => $totalDuplicateNoReg,
            'duplicate_nama'        => $totalDuplicateNama,
            'ekspirasi_kosong'      => $totalEkspirasiKosong,

            // Audit berbasis accessor
            'belum_ada_foto'        => $totalBelumAdaFoto,
            'data_lengkap'          => $totalDataLengkap,
            'belum_lengkap'         => $totalBelumLengkap,
        ];
    }

    // ================= INDEX =================
    public function index(Request $request)
    {
        $wbp = Wbp::with('kamar')
            ->when($request->status, function ($q) use ($request) {
                $q->where('status_wbp', $request->status);
            }, function ($q) {
                $q->where('status_wbp', 'AKTIF');
            })
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('nama', 'like', "%{$request->search}%")
                        ->orWhere('no_reg_instansi', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();
        $this->markDuplicateData($wbp);

        $kamars = Kamar::orderBy('kode_blok')
            ->orderByRaw("
            CAST(
                REPLACE(lokasi_sel, 'KAMAR ', '')
                AS UNSIGNED
            )
        ")
            ->get();

        // ================= COUNT STATUS =================
        $audit = $this->getAuditStatistics();
        $countAktif = Wbp::where('status_wbp', 'AKTIF')->count();
        $countBon = Wbp::where('status_wbp', 'BON')->count();
        $countSakit = Wbp::where('status_wbp', 'SAKIT')->count();
        $countIsolasi = Wbp::where('status_wbp', 'AKTIF')->where('status_kamar', 'TERTUTUP')->count();
        $countPindah = Wbp::where('status_wbp', 'PINDAH UPT')->count();
        $countPulang = Wbp::where('status_wbp', 'PULANG')->count();
        $countMeninggal = Wbp::where('status_wbp', 'MENINGGAL')->count();

        return view(
            'admin-banceuy.wbp',
            compact(
                'wbp',
                'countAktif',
                'countBon',
                'countSakit',
                'countIsolasi',
                'countPindah',
                'countPulang',
                'countMeninggal',
                'kamars',
                'audit',
            )
        );
    }

    public function filter(Request $request)
    {
        // ================= BASE QUERY =================
        $query = Wbp::with('kamar')

            ->when($request->status, function ($q) use ($request) {

                if ($request->status === 'ISOLASI') {

                    return $q->where('status_wbp', 'AKTIF')
                        ->where('status_kamar', 'Tertutup');
                }

                return $q->where('status_wbp', $request->status);
            }, function ($q) {

                return $q->where('status_wbp', 'AKTIF');
            })

            ->when($request->search, function ($q) use ($request) {

                $q->where(function ($qq) use ($request) {

                    $qq->where('nama', 'like', "%{$request->search}%")
                        ->orWhere('no_reg_instansi', 'like', "%{$request->search}%");
                });
            })

            ->orderBy('nama');

        // ================= AMBIL COLLECTION =================
        $wbps = $query->get();

        // ================= MARK DUPLIKAT =================
        $this->markDuplicateData($wbps);

        // ================= FILTER AUDIT =================
        switch ($request->audit) {

            case 'duplicate_no_reg':

                $wbps = $wbps->where('duplicate_no_reg', true);

                break;

            case 'duplicate_nama':

                $wbps = $wbps->where('duplicate_nama', true);

                break;

            case 'ekspirasi_kosong':

                $wbps = $wbps->filter(function ($w) {

                    return blank($w->ekspirasi);
                });

                break;

            case 'belum_ada_foto':

            case 'data_lengkap':

            case 'belum_lengkap':

                $wbps = $this->applyAuditFilter(
                    $wbps,
                    $request->audit
                );

                break;
        }

        // ================= PAGINATION =================
        $perPage = 10;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $items = $wbps
            ->values()
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(

            $items,

            $wbps->count(),

            $perPage,

            $currentPage,

            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return response()->json([

            'success' => true,

            'data' => $paginator->items(),

            'total' => $paginator->total(),

            'current_page' => $paginator->currentPage(),

            'last_page' => $paginator->lastPage(),

            'next_page_url' => $paginator->nextPageUrl(),

            'prev_page_url' => $paginator->previousPageUrl(),
        ]);
    }

    public function detail($id)
    {
        $data = Wbp::with('kamar')->findOrFail($id);

        return response()->json([
            'id' => $data->id,
            'nama' => $data->nama,
            'no_reg_instansi' => $data->no_reg_instansi,

            'blok' => $data->kamar->kode_blok ?? '-',
            'sel' => $data->kamar->lokasi_sel ?? '-',

            'total_bulan_remisi' => $data->total_bulan_remisi,
            'total_hari_remisi' => $data->total_hari_remisi,

            // perkara
            'pasal' => $data->pasal,
            'putusan' => $data->putusan,
            'putusan_bulan' => $data->putusan_bulan,

            // pidana subsider
            'subsider_tahun' => $data->subsider_tahun,
            'subsider_bulan' => $data->subsider_bulan,
            'subsider_hari' => $data->subsider_hari,

            // denda subsider
            'denda_subsider' => $data->denda_subsider,

            // ekspirasi
            'ekspirasi' => $data->ekspirasi,

            // status WBP
            'status_wbp' => $data->status_wbp,

            // status kamar
            'status_kamar' => $data->status_kamar,

            'tanggal' => $data->tanggal,
            'keterangan' => $data->keterangan,
            'jenis_kejahatan' => $data->jenis_kejahatan,
        ]);
    }

    public function getBlok()
    {
        $blok = \App\Models\Wbp::select('lokasi_blok')
            ->distinct()
            ->orderBy('lokasi_blok')
            ->pluck('lokasi_blok');

        return response()->json([
            'success' => true,
            'data' => $blok
        ]);
    }

    public function getSel(Request $request)
    {
        $request->validate([
            'blok' => 'required'
        ]);

        $sel = \App\Models\Wbp::where('lokasi_blok', $request->blok)
            ->select('lokasi_sel')
            ->distinct()
            ->orderBy('lokasi_sel')
            ->pluck('lokasi_sel');

        return response()->json([
            'success' => true,
            'data' => $sel
        ]);
    }

    public function updateStatusKamar(Request $request)
    {
        $request->validate([
            'blok' => 'required',
            'sel' => 'nullable',
            'status_kamar' => 'required|in:Free,Scatch,Pengasingan'
        ]);

        $query = \App\Models\Wbp::where('lokasi_blok', $request->blok);

        // kalau sel dipilih -> update sel itu saja
        if (!empty($request->sel)) {
            $query->where('lokasi_sel', $request->sel);
        }

        $updated = $query->update([
            'status_kamar' => $request->status_kamar
        ]);

        return response()->json([
            'success' => true,
            'message' => "Berhasil update status kamar!",
            'updated_rows' => $updated
        ]);
    }

    public function store(Request $request)
    {

        $numericFields = [
            'putusan',
            'putusan_bulan',
            'subsider_tahun',
            'subsider_bulan',
            'subsider_hari',
            'denda_subsider',
            'total_bulan_remisi',
            'total_hari_remisi',
        ];

        foreach ($numericFields as $field) {
            if ($request->input($field) === '') {
                $request->merge([
                    $field => null,
                ]);
            }
        }

        // Denda bisa diinput dengan format 1.500.000
        if ($request->filled('denda_subsider')) {
            $request->merge([
                'denda_subsider' => str_replace(
                    '.',
                    '',
                    $request->denda_subsider
                ),
            ]);
        }

        $validated = $request->validate([

            // ================= IDENTITAS =================
            'no_reg_instansi' => [
                'required',
                'string',
                'max:50',
                Rule::unique('wbps')->where(
                    fn($q) => $q->where('nama', $request->nama)
                ),
            ],

            'nama' => [
                'required',
                'string',
                'max:100',
            ],

            'negara' => [
                'nullable',
                'string',
                'max:50',
            ],

            'agama' => [
                'nullable',
                'string',
                'max:30',
            ],

            'klasifikasi_wbp' => [
                'required',
                'string',
                'max:100',
            ],

            // ================= PERKARA =================
            'jenis_kejahatan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pasal' => [
                'nullable',
                'string',
                'max:255',
            ],

            'putusan' => [
                'nullable',
                'integer',
                'min:0',
                'max:255',
            ],

            'putusan_bulan' => [
                'nullable',
                'integer',
                'min:0',
                'max:11',
            ],

            // ================= SUBSIDER =================
            'subsider_tahun' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'subsider_bulan' => [
                'nullable',
                'integer',
                'min:0',
                'max:11',
            ],

            'subsider_hari' => [
                'nullable',
                'integer',
                'min:0',
                'max:30',
            ],

            'denda_subsider' => [
                'nullable',
                'integer',
                'min:0',
            ],

            // ================= MASA =================
            'ekspirasi' => [
                'nullable',
                'date',
            ],

            'masa_1_3' => [
                'nullable',
                'date',
            ],

            'masa_1_2' => [
                'nullable',
                'date',
            ],

            'masa_2_3' => [
                'nullable',
                'date',
            ],

            // ================= REMISI =================
            'total_bulan_remisi' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'total_hari_remisi' => [
                'nullable',
                'integer',
                'min:0',
            ],

            // ================= LOKASI =================
            'kamar_id' => [
                'nullable',
                'exists:kamars,id',
            ],

            // ================= STATUS =================
            'status_wbp' => [
                'required',
                'in:AKTIF,PINDAH UPT,BON,SAKIT,PULANG,MENINGGAL',
            ],

            // ================= FOTO =================
            'foto_wbp' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [

            // ================= IDENTITAS =================
            'no_reg_instansi.required' =>
            'Nomor Registrasi Instansi wajib diisi.',

            'no_reg_instansi.unique' =>
            'Nomor Registrasi Instansi tersebut sudah terdaftar untuk nama WBP ini.',

            'nama.required' =>
            'Nama WBP wajib diisi.',

            'klasifikasi_wbp.required' =>
            'Klasifikasi WBP wajib dipilih.',

            // ================= PERKARA =================
            'putusan.integer' =>
            'Putusan harus berupa angka.',

            'putusan_bulan.integer' =>
            'Putusan bulan harus berupa angka.',

            'putusan_bulan.max' =>
            'Putusan bulan maksimal 11.',

            // ================= SUBSIDER =================
            'subsider_tahun.integer' =>
            'Subsider tahun harus berupa angka.',

            'subsider_bulan.integer' =>
            'Subsider bulan harus berupa angka.',

            'subsider_bulan.max' =>
            'Subsider bulan maksimal 11.',

            'subsider_hari.integer' =>
            'Subsider hari harus berupa angka.',

            'subsider_hari.max' =>
            'Subsider hari maksimal 30.',

            'denda_subsider.integer' =>
            'Denda subsider harus berupa angka.',

            'denda_subsider.min' =>
            'Denda subsider tidak boleh bernilai negatif.',

            // ================= MASA =================
            'ekspirasi.date' =>
            'Format tanggal ekspirasi tidak valid.',

            'masa_1_3.date' =>
            'Format tanggal masa 1/3 tidak valid.',

            'masa_1_2.date' =>
            'Format tanggal masa 1/2 tidak valid.',

            'masa_2_3.date' =>
            'Format tanggal masa 2/3 tidak valid.',

            // ================= REMISI =================
            'total_bulan_remisi.integer' =>
            'Total bulan remisi harus berupa angka.',

            'total_hari_remisi.integer' =>
            'Total hari remisi harus berupa angka.',

            // ================= LOKASI =================
            'kamar_id.exists' =>
            'Kamar yang dipilih tidak ditemukan.',

            // ================= STATUS =================
            'status_wbp.required' =>
            'Status WBP wajib dipilih.',

            'status_wbp.in' =>
            'Status WBP yang dipilih tidak valid.',

            // ================= FOTO =================
            'foto_wbp.required' =>
            'Foto WBP wajib diunggah.',

            'foto_wbp.image' =>
            'File foto WBP harus berupa gambar.',

            'foto_wbp.mimes' =>
            'Format foto harus JPG, JPEG, PNG, atau WEBP.',

            'foto_wbp.max' =>
            'Ukuran foto maksimal 2 MB.',
        ]);

        DB::beginTransaction();

        $fotoPath = null;

        try {

            $kamar = $request->filled('kamar_id')
                ? Kamar::findOrFail($request->kamar_id)
                : null;

            $foto = null;

            if ($request->hasFile('foto_wbp')) {

                $file = $request->file('foto_wbp');

                $namaFile = $file->getClientOriginalName();

                $fotoPath = 'foto_wbp/' . $namaFile;

                $file->storeAs(
                    'foto_wbp',
                    $namaFile,
                    'public'
                );

                $foto = 'storage/' . $fotoPath;
            }

            $wbp = Wbp::create([

                // ================= IDENTITAS =================
                'no_reg_instansi' => $validated['no_reg_instansi'],
                'nama'            => $validated['nama'],
                'negara'          => $validated['negara'] ?? null,
                'agama'           => $validated['agama'] ?? null,
                'klasifikasi_wbp' => $validated['klasifikasi_wbp'],

                // ================= PERKARA =================
                'jenis_kejahatan' => $validated['jenis_kejahatan'] ?? null,
                'pasal'           => $validated['pasal'] ?? null,
                'putusan'         => $validated['putusan'] ?? null,
                'putusan_bulan'   => $validated['putusan_bulan'] ?? null,

                // ================= SUBSIDER =================
                'subsider_tahun'  => $validated['subsider_tahun'] ?? null,
                'subsider_bulan'  => $validated['subsider_bulan'] ?? null,
                'subsider_hari'   => $validated['subsider_hari'] ?? null,
                'denda_subsider'  => $validated['denda_subsider'] ?? null,

                // ================= MASA =================
                'ekspirasi' => $validated['ekspirasi'] ?? null,
                'masa_1_3'  => $validated['masa_1_3'] ?? null,
                'masa_1_2'  => $validated['masa_1_2'] ?? null,
                'masa_2_3'  => $validated['masa_2_3'] ?? null,

                // ================= REMISI =================
                'total_bulan_remisi' =>
                $validated['total_bulan_remisi'] ?? null,

                'total_hari_remisi' =>
                $validated['total_hari_remisi'] ?? null,

                // ================= LOKASI =================
                'kamar_id' =>
                $kamar?->id,

                'lokasi_blok' =>
                $kamar?->lokasi_blok,

                'lokasi_sel' =>
                $kamar?->lokasi_sel,

                // ================= STATUS =================
                'status_kamar' =>
                $kamar?->status_kamar ?? 'Terbuka',

                'status_wbp' =>
                $validated['status_wbp'],

                // ================= FOTO =================
                'foto_wbp' =>
                $foto,
            ]);

            WbpKlinik::create([
                'wbp_id'          => $wbp->id,
                'no_rekam_medis'  => null,
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Data WBP berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {

            DB::rollBack();

            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            Log::error('Tambah WBP gagal', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Data WBP gagal disimpan. Silakan periksa kembali data yang diinput.'
                );
        }
    }

    public function edit($id)
    {
        $wbp = Wbp::findOrFail($id);

        return response()->json($wbp);
    }

    public function update(Request $request, $id)
    {
        $wbp = Wbp::findOrFail($id);

        $request->validate([
            'no_reg_instansi' => [
                'required',
                'string',
                'max:50',
                Rule::unique('wbps')
                    ->where(fn($q) => $q->where('nama', $request->nama))
                    ->ignore($wbp->id),
            ],


            'nama' => 'required|string|max:100',
            'negara' => 'nullable|string|max:50',
            'agama' => 'nullable|string|max:30',
            'klasifikasi_wbp' => 'nullable|string|max:100',

            'jenis_kejahatan' => 'nullable|string|max:100',
            'pasal' => 'nullable|string|max:255',
            'putusan' => 'nullable|string|max:255',
            'putusan_bulan' => 'nullable|integer|min:0',

            'subsider_tahun' => 'nullable|integer|min:0',
            'subsider_bulan' => 'nullable|integer|min:0',
            'subsider_hari' => 'nullable|integer|min:0',
            'denda_subsider' => 'nullable|numeric|min:0',

            'ekspirasi' => 'nullable|date',
            'masa_1_3' => 'nullable|date',
            'masa_1_2' => 'nullable|date',
            'masa_2_3' => 'nullable|date',

            'total_bulan_remisi' => 'nullable|integer|min:0',
            'total_hari_remisi' => 'nullable|integer|min:0',

            'keperluan' => 'nullable|string|max:255',
            'tanggal_bon' => 'nullable|date',
            'tanggal' => 'nullable|date',
            'foto_wbp' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:5000',
        ]);

        DB::beginTransaction();

        try {

            $wbp->update([
                'no_reg_instansi' => $request->no_reg_instansi,
                'nama' => $request->nama,
                'negara' => $request->negara,
                'agama' => $request->agama,
                'klasifikasi_wbp' => $request->klasifikasi_wbp,

                'jenis_kejahatan' => $request->jenis_kejahatan,
                'pasal' => $request->pasal,
                'putusan' => $request->putusan,
                'putusan_bulan' => $request->putusan_bulan,

                'subsider_tahun' => $request->subsider_tahun,
                'subsider_bulan' => $request->subsider_bulan,
                'subsider_hari' => $request->subsider_hari,
                'denda_subsider' => $request->denda_subsider,

                'ekspirasi' => $request->ekspirasi,
                'masa_1_3' => $request->masa_1_3,
                'masa_1_2' => $request->masa_1_2,
                'masa_2_3' => $request->masa_2_3,

                'total_bulan_remisi' => $request->total_bulan_remisi,
                'total_hari_remisi' => $request->total_hari_remisi,

                'keperluan' => $request->keperluan,
                'tanggal_bon' => $request->tanggal_bon,
                'tanggal' => $request->tanggal,
                'foto_wbp' => $request->foto_wbp,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data WBP berhasil diperbarui.',
                'data' => $wbp->fresh()
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Update Data WBP gagal', [
                'wbp_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui Data WBP.'
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::guard('admin')->user();

        // ================= VALIDASI AUTH =================
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized user.'
            ], 401);
        }

        // ================= VALIDASI PASSWORD =================
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password yang Anda masukkan salah.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            $wbp = Wbp::findOrFail($id);

            $namaWbp = $wbp->nama ?? '-';

            $wbp->delete();

            Log::info('Menghapus Data WBP', [
                'user_id'   => $user->id,
                'username'  => $user->nama,
                'wbp_id'    => $id,
                'keterangan' => 'Menghapus WBP: ' . $namaWbp,
                'ip_address' => $request->ip(),
                'tanggal'   => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Delete WBP gagal', [
                'error' => $e->getMessage(),
                'wbp_id' => $id,
                'user_id' => $user->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.'
            ], 500);
        }
    }

    public function bonWbp(Request $request)
    {
        try {

            // ================= VALIDASI =================
            $request->validate([
                'items' => 'required|array',
                'items.*.wbp_id' => 'required|integer',
                'items.*.kamar_asal_id' => 'required|integer',
                'items.*.tanggal' => 'required|date',
                'items.*.keperluan' => 'required|string',
                'items.*.status_wbp' => 'required|string',
            ]);

            DB::beginTransaction();

            $updated = [];

            foreach ($request->items as $item) {

                // ================= UPDATE WBPS =================
                $wbp = Wbp::findOrFail($request->wbp_id);

                $wbp->update([
                    'kamar_id' => $request->kamar_asal_id,
                    'keperluan' => $request->keperluan,
                    'status_wbp' => 'BON',
                ]);

                $updated[] = $item['wbp_id'];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'BON WBP berhasil diproses',
                'updated_ids' => $updated
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal proses BON WBP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function kamarAll()
    {
        $kamar = Kamar::select(
            'id',
            'kode_blok',
            'lokasi_sel'
        )
            ->get();

        $kamar = $kamar->sort(function ($a, $b) {

            // Urutkan berdasarkan kode blok
            if (($a->kode_blok ?? '') !== ($b->kode_blok ?? '')) {
                return strcmp(
                    $a->kode_blok ?? '',
                    $b->kode_blok ?? ''
                );
            }

            // Ambil nomor dari "KAMAR 1", "KAMAR 2", dst.
            preg_match('/(\d+)/', $a->lokasi_sel ?? '', $ma);
            preg_match('/(\d+)/', $b->lokasi_sel ?? '', $mb);

            $aNo = (int)($ma[1] ?? 0);
            $bNo = (int)($mb[1] ?? 0);

            return $aNo <=> $bNo;
        })->values();

        return response()->json($kamar);
    }

    public function wbpKamarUpdate(Request $request)
    {
        $request->validate([
            'wbp_id'   => 'required|exists:wbps,id',
            'kamar_id' => 'required|exists:kamars,id'
        ]);

        // ambil data WBP
        $wbp = Wbp::findOrFail($request->wbp_id);

        // ambil data kamar tujuan
        $kamar = Kamar::findOrFail($request->kamar_id);

        // tambah create mutasi
        Mutasi::create([
            'wbp_id' => $wbp->id,
            'nama' => $wbp->nama,
            'negara' => $wbp->negara,
            'agama' => $wbp->agama,
            'putusan' => $wbp->putusan,
            'ekspirasi' => $wbp->ekspirasi,
            'jenis_kejahatan' => $wbp->jenis_kejahatan,
            'foto_wbp' => $wbp->foto_wbp,

            // kamar sebelum pindah
            'kamar_asal_id' => $wbp->kamar_id,
            'kamar_asal_nama' => $wbp->lokasi_blok . ' - ' . $wbp->lokasi_sel,

            // kamar tujuan
            'kamar_tujuan_id' => $kamar->id,
            'kamar_tujuan_nama' => $kamar->lokasi_blok . ' - ' . $kamar->lokasi_sel,

            'alasan' => 'Mutasi kamar'
        ]);

        // update semua field terkait
        $wbp->kamar_id     = $kamar->id;
        $wbp->lokasi_blok  = $kamar->lokasi_blok;
        $wbp->lokasi_sel   = $kamar->lokasi_sel;
        $wbp->status_kamar = $kamar->status_kamar;

        $wbp->save();

        return response()->json([
            'success' => true,
            'message' => 'Kamar & lokasi WBP berhasil diperbarui'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:wbps,id',
            'status_wbp' => 'required|in:AKTIF,BON,SAKIT,ISOLASI,PINDAH UPT,PULANG,MENINGGAL',
            'status_kamar' => 'required|in:Terbuka,Tertutup'
        ]);

        $wbp = Wbp::findOrFail($request->id);

        $wbp->status_wbp = $request->status_wbp;
        $wbp->status_kamar = $request->status_kamar;
        $wbp->save();

        $countIsolasi = Wbp::where('status_wbp', 'AKTIF')
            ->where('status_kamar', 'Tertutup')
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Status WBP berhasil diperbarui'
        ]);
    }

    public function updateKeterangan(Request $request)
    {
        $request->validate([

            'id' => [
                'required',
                'integer',
                'exists:wbps,id'
            ],

            'tanggal' => [
                'nullable',
                'date'
            ],

            'keterangan' => [
                'nullable',
                'string',
                'min:5',
                'max:5000',
                'not_regex:/<script\b[^>]*>(.*?)<\/script>/is'
            ]

        ]);

        $tanggal = null;

        if ($request->filled('tanggal')) {

            $tanggal = Carbon::parse(
                $request->tanggal
            )->format('Y-m-d H:i:s');
        }

        $wbp = Wbp::updateOrCreate(

            [
                'id' => $request->id
            ],

            [

                'tanggal' => $tanggal,

                'keterangan' => htmlspecialchars(
                    trim($request->keterangan ?? ''),
                    ENT_QUOTES,
                    'UTF-8'
                )

            ]

        );

        return response()->json([

            'success' => true,

            'message' => 'Keterangan berhasil diperbarui',

            'data' => [

                'id' => $wbp->id,

                'tanggal' => $wbp->tanggal,

                'keterangan' => $wbp->keterangan

            ]

        ]);
    }
}
