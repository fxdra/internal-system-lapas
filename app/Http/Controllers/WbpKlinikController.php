<?php

namespace App\Http\Controllers;

use App\Models\WbpKlinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WbpKlinikController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $status = $request->input('status');

        /*
    |--------------------------------------------------------------------------
    | BASE QUERY — HANYA WBP AKTIF
    |--------------------------------------------------------------------------
    */

        $baseQuery = WbpKlinik::whereHas('wbp', function ($query) {
            $query->where('status_wbp', 'AKTIF');
        });

        /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

        $query = (clone $baseQuery)->with('wbp');

        /*
    |--------------------------------------------------------------------------
    | FILTER REKAM MEDIS
    |--------------------------------------------------------------------------
    */

        if ($status === 'tanpa_rekam_medis') {
            $query->where(function ($q) {
                $q->whereNull('no_rekam_medis')
                    ->orWhere('no_rekam_medis', '');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($q) use ($search) {

                $q->where(
                    'no_rekam_medis',
                    'like',
                    "%{$search}%"
                )

                    ->orWhereHas('wbp', function ($wbpQuery) use ($search) {

                        $wbpQuery->where('nama', 'like', "%{$search}%")
                            ->orWhere('no_reg_instansi', 'like', "%{$search}%");
                    });
            });
        }

        /*
    |--------------------------------------------------------------------------
    | DATA PAGINATION
    |--------------------------------------------------------------------------
    */

        $data = $query
            ->orderBy('wbp_id')
            ->paginate(25)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | STATISTIK — HANYA WBP AKTIF
    |--------------------------------------------------------------------------
    */

        $totalWbpKlinik = (clone $baseQuery)->count();

        $totalDenganRekamMedis = (clone $baseQuery)
            ->whereNotNull('no_rekam_medis')
            ->where('no_rekam_medis', '!=', '')
            ->count();

        $totalTanpaRekamMedis = (clone $baseQuery)
            ->where(function ($query) {
                $query->whereNull('no_rekam_medis')
                    ->orWhere('no_rekam_medis', '');
            })
            ->count();

        /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

        return view('admin-banceuy.wbp-klinik', compact(
            'data',
            'totalWbpKlinik',
            'totalDenganRekamMedis',
            'totalTanpaRekamMedis'
        ));
    }

    // ================= SEARCH AJAX =================
    public function search(Request $request)
    {
        $query = trim($request->get('query', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $results = WbpKlinik::with('wbp')
            ->whereHas('wbp', function ($q) {
                $q->where('status_wbp', 'AKTIF');
            })
            ->where(function ($q) use ($query) {

                $q->where('no_rekam_medis', 'like', "%{$query}%")
                    ->orWhereHas('wbp', function ($wbpQuery) use ($query) {

                        $wbpQuery->where('nama', 'like', "%{$query}%")
                            ->orWhere(
                                'no_reg_instansi',
                                'like',
                                "%{$query}%"
                            );
                    });
            })
            ->limit(10)
            ->get();

        return response()->json($results->map(function ($item) {
            return [
                'wbp_id' => $item->wbp_id,
                'nama' => $item->wbp?->nama,
                'no_reg_instansi' => $item->wbp?->no_reg_instansi,
                'no_rekam_medis' => $item->no_rekam_medis,
            ];
        }));
    }

    public function filter(Request $request)
    {
        $search = trim($request->input('search', ''));
        $status = $request->input('status');

        $query = WbpKlinik::with('wbp')
            ->whereHas('wbp', function ($q) {
                $q->where('status_wbp', 'AKTIF');
            });

        // Filter tanpa rekam medis
        if ($status === 'tanpa_rekam_medis') {
            $query->where(function ($q) {
                $q->whereNull('no_rekam_medis')
                    ->orWhere('no_rekam_medis', '');
            });
        }

        // Search
        if ($search !== '') {
            $query->where(function ($q) use ($search) {

                // Cari berdasarkan No. Rekam Medis
                $q->where(
                    'no_rekam_medis',
                    'like',
                    "%{$search}%"
                );

                // Cari berdasarkan data WBP
                $q->orWhereHas('wbp', function ($wbpQuery) use ($search) {

                    $wbpQuery
                        ->where('nama', 'like', "%{$search}%")
                        ->orWhere(
                            'no_reg_instansi',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'nik',
                            'like',
                            "%{$search}%"
                        );
                });
            });
        }

        $data = $query
            ->orderBy('wbp_id')
            ->paginate(25);

        return response()->json($data);
    }

    public function show(WbpKlinik $wbpKlinik)
    {
        $wbpKlinik->load('wbp');

        return response()->json([
            'success' => true,
            'data' => [
                'wbp_id' => $wbpKlinik->wbp_id,
                'no_rekam_medis' => $wbpKlinik->no_rekam_medis,
                'nama' => $wbpKlinik->wbp?->nama,
                'nik' => $wbpKlinik->wbp?->nik,
                'no_reg_instansi' => $wbpKlinik->wbp?->no_reg_instansi,
                'tgl_masuk_lapas' => $wbpKlinik->wbp?->tgl_masuk_lapas,
            ],
        ]);
    }

    public function edit(WbpKlinik $wbpKlinik)
    {
        $wbpKlinik->load('wbp');

        return view(
            'admin-banceuy.wbp-klinik-edit',
            compact('wbpKlinik')
        );
    }

    public function update(Request $request, WbpKlinik $wbpKlinik)
    {
        $validated = $request->validate([
            'nik' => [
                'nullable',
                'string',
                'max:30',
            ],

            'tgl_masuk_lapas' => [
                'nullable',
                'date',
            ],

            'no_rekam_medis' => [
                'nullable',
                'string',
                'max:50',
                'unique:wbp_klinik,no_rekam_medis,'
                    . $wbpKlinik->wbp_id
                    . ',wbp_id',
            ],
        ]);

        $nik = blank($validated['nik'] ?? null)
            ? null
            : trim($validated['nik']);

        $noRekamMedis = blank($validated['no_rekam_medis'] ?? null)
            ? null
            : trim($validated['no_rekam_medis']);

        DB::transaction(function () use (
            $wbpKlinik,
            $nik,
            $validated,
            $noRekamMedis
        ) {
            $wbpKlinik->wbp->update([
                'nik' => $nik,
                'tgl_masuk_lapas' => $validated['tgl_masuk_lapas'] ?? null,
            ]);

            $wbpKlinik->update([
                'no_rekam_medis' => $noRekamMedis,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Data WBP berhasil diperbarui.',
        ]);
    }
}
