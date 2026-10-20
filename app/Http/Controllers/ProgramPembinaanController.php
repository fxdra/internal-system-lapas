<?php

namespace App\Http\Controllers;

use App\Models\ProgramPembinaan;
use App\Models\Wbp;
use App\Models\WbpPembinaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Kamar;

class ProgramPembinaanController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');
        $programId = $request->query('program_id');
        $search = trim((string) $request->query('search', ''));
        $blok = $request->query('blok');
        $kamarId = $request->query('kamar_id');
        $selectedIds = collect($request->query('selected_ids', []))
            ->filter(fn($id) => filter_var($id, FILTER_VALIDATE_INT) !== false && (int) $id > 0)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $selectedWbps = Wbp::query()
            ->whereIn('id', $selectedIds)
            ->get(['id', 'no_reg_instansi', 'nama']);

        // Daftar blok untuk filter.
        $blokList = Kamar::query()
            ->whereNotNull('kode_blok')
            ->where('kode_blok', '!=', '')
            ->distinct()
            ->orderBy('kode_blok')
            ->pluck('kode_blok');

        // Daftar kamar untuk filter.
        $kamarList = Kamar::query()
            ->select(['id', 'kode_blok', 'lokasi_sel'])
            ->whereNotNull('lokasi_sel')
            ->where('lokasi_sel', '!=', '')
            ->orderBy('kode_blok')
            ->orderByRaw('CAST(REPLACE(lokasi_sel,"KAMAR ", "") AS UNSIGNED)')
            ->get();

        $programs = ProgramPembinaan::query()
            ->where('is_active', true)
            ->orderBy('kategori')
            ->orderBy('nama_program')
            ->get();

        $selectedProgram = null;

        if ($programId) {
            $selectedProgram = $programs->firstWhere('id', (int) $programId);

            abort_unless($selectedProgram, 404);
        }

        $wbps = Wbp::query()
            ->where('status_wbp', 'Aktif')
            ->select([
                'id',
                'no_reg_instansi',
                'nama',
                'kamar_id',
            ])
            ->with([
                'kamar:id,kode_blok,kode_kamar,nama_kamar',
                'riwayatPembinaan.program:id,nama_program',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_reg_instansi', 'like', "%{$search}%");
                });
            })
            ->when($blok, function ($query) use ($blok) {
                $query->whereHas('kamar', function ($subQuery) use ($blok) {
                    $subQuery->where('kode_blok', $blok);
                });
            })
            ->when($kamarId, function ($query) use ($kamarId) {
                $query->where('kamar_id', $kamarId);
            })
            ->when($selectedProgram, function ($query) use ($selectedProgram) {
                $query->whereDoesntHave('riwayatPembinaan', function ($subQuery) use ($selectedProgram) {
                    $subQuery->where('program_pembinaan_id', $selectedProgram->id)
                        ->where('status_keikutsertaan', 'Aktif');
                });
            })
            ->orderBy('nama')
            ->paginate(25)
            ->withQueryString();

        $riwayat = WbpPembinaan::query()
            ->with([
                'wbp:id,no_reg_instansi,nama,kamar_id',
                'wbp.kamar:id,kode_blok,lokasi_sel',
                'program:id,kategori,nama_program',
            ])
            ->when($selectedProgram, function ($query) use ($selectedProgram) {
                $query->where('program_pembinaan_id', $selectedProgram->id);
            })
            ->when($kategori, function ($query) use ($kategori) {
                $query->whereHas('program', function ($subQuery) use ($kategori) {
                    $subQuery->where('kategori', $kategori);
                });
            })
            ->join('wbps', 'wbp_pembinaan.wbp_id', '=', 'wbps.id')
            ->orderBy('wbps.nama', 'asc')
            ->select('wbp_pembinaan.*')
            ->paginate(20, ['*'], 'riwayat_page')
            ->withQueryString();

        $statistik = [
            'total_program' => $programs->count(),
            'total_wbp_aktif' => WbpPembinaan::where(
                'status_keikutsertaan',
                'Aktif'
            )->count(),
            'total_selesai' => WbpPembinaan::where(
                'status_keikutsertaan',
                'Selesai'
            )->count(),
        ];

        return view('admin-banceuy.program-pembinaan', compact(
            'programs',
            'selectedProgram',
            'kategori',
            'search',
            'blok',
            'kamarId',
            'blokList',
            'kamarList',
            'wbps',
            'riwayat',
            'selectedIds',
            'selectedWbps',
            'statistik'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_pembinaan_id' => [
                'required',
                'integer',
                Rule::exists('program_pembinaan', 'id')->where('is_active', true),
            ],
            'wbp_ids' => ['required', 'array', 'min:1'],
            'wbp_ids.*' => ['required', 'integer', 'distinct', 'exists:wbps,id'],
            'tanggal_mulai' => ['required', 'date'],
            'indikator_keaktifan' => [
                'required',
                Rule::in(['Sangat Aktif', 'Aktif', 'Kurang Aktif']),
            ],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        $berhasil = 0;
        $dilewati = 0;

        DB::transaction(function () use ($validated, &$berhasil, &$dilewati) {
            $program = ProgramPembinaan::query()
                ->where('is_active', true)
                ->findOrFail($validated['program_pembinaan_id']);

            $wbpIds = collect($validated['wbp_ids'])
                ->unique()
                ->sort()
                ->values();

            foreach ($wbpIds as $wbpId) {
                $sudahAktif = WbpPembinaan::query()
                    ->where('wbp_id', $wbpId)
                    ->where('program_pembinaan_id', $program->id)
                    ->where('status_keikutsertaan', 'Aktif')
                    ->lockForUpdate()
                    ->exists();

                if ($sudahAktif) {
                    $dilewati++;
                    continue;
                }

                WbpPembinaan::create([
                    'wbp_id' => $wbpId,
                    'program_pembinaan_id' => $program->id,
                    'tanggal_mulai' => $validated['tanggal_mulai'],
                    'indikator_keaktifan' => $validated['indikator_keaktifan'],
                    'status_keikutsertaan' => 'Aktif',
                    'keterangan' => $validated['keterangan'] ?? null,
                ]);

                $berhasil++;
            }
        });

        $message = "{$berhasil} WBP berhasil ditambahkan ke program pembinaan.";

        if ($dilewati > 0) {
            $message .= " {$dilewati} WBP dilewati karena sudah mengikuti program tersebut.";
        }

        return redirect()
            ->route('program-pembinaan.index', [
                'program_id' => $validated['program_pembinaan_id'],
            ])
            ->with('success', $message);
    }


    public function updateKeikutsertaan(Request $request, WbpPembinaan $wbpPembinaan)
    {
        $validated = $request->validate([
            'tanggal_mulai' => [
                'required',
                'date',
            ],
            'indikator_keaktifan' => [
                'required',
                Rule::in(['Sangat Aktif', 'Aktif', 'Kurang Aktif']),
            ],
            'status_keikutsertaan' => [
                'required',
                Rule::in(['Aktif', 'Selesai']),
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        $status = $validated['status_keikutsertaan'];

        if ($status === 'Aktif') {
            $duplikat = WbpPembinaan::query()
                ->where('wbp_id', $wbpPembinaan->wbp_id)
                ->where('program_pembinaan_id', $wbpPembinaan->program_pembinaan_id)
                ->where('status_keikutsertaan', 'Aktif')
                ->where('id', '!=', $wbpPembinaan->id)
                ->exists();

            if ($duplikat) {
                return back()->withErrors([
                    'status_keikutsertaan' => 'WBP sudah memiliki keikutsertaan aktif pada program ini.',
                ]);
            }
        }

        $wbpPembinaan->update([
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'indikator_keaktifan' => $validated['indikator_keaktifan'],
            'status_keikutsertaan' => $status,
            'tanggal_selesai' => $status === 'Selesai'
                ? ($validated['tanggal_selesai'] ?? now()->toDateString())
                : null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return back()->with('success', 'Data keikutsertaan berhasil diperbarui.');
    }
}
