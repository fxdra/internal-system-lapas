<?php

namespace App\Http\Controllers;

use App\Models\WbpKlinik;
use Illuminate\Http\Request;

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

    public function show(WbpKlinik $wbpKlinik)
    {
        $wbpKlinik->load('wbp');

        return view(
            'admin-banceuy.wbp-klinik-detail',
            compact('wbpKlinik')
        );
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
            'no_rekam_medis' => [
                'nullable',
                'string',
                'max:50',
                'unique:wbp_klinik,no_rekam_medis,'
                    . $wbpKlinik->wbp_id
                    . ',wbp_id',
            ],
        ]);

        $validated['no_rekam_medis'] =
            blank($validated['no_rekam_medis'] ?? null)
            ? null
            : trim($validated['no_rekam_medis']);

        $wbpKlinik->update($validated);

        return redirect()
            ->route('klinik.wbp.index')
            ->with('success', 'Nomor rekam medis berhasil diperbarui.');
    }
}
