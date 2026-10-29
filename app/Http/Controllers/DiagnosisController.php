<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $kategori = trim((string) $request->input('kategori', ''));

        $query = DB::table('diagnosis')
            ->select(['id', 'kode_sakit', 'uraian', 'kategori']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kode_sakit', 'like', "%{$search}%")
                    ->orWhere('uraian', 'like', "%{$search}%");
            });
        }

        if ($kategori !== '') {
            $query->where('kategori', $kategori);
        }

        $diagnoses = $query
            ->orderBy('kode_sakit')
            ->paginate(50)
            ->withQueryString();

        $totalDiagnosis = 71704;

        return view('admin-banceuy.diagnosis', compact(
            'diagnoses',
            'search',
            'kategori',
            'totalDiagnosis'
        ));
    }

    public function searchCategories(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        if (mb_strlen($search) < 2) {
            return response()->json([]);
        }

        $categories = DB::table('diagnosis')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->where('kategori', 'like', '%' . $search . '%')
            ->distinct()
            ->orderBy('kategori')
            ->limit(30)
            ->pluck('kategori');

        return response()->json($categories);
    }
}
