<?php

namespace App\Http\Controllers;

use App\Models\Kritik;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class DataKritikController extends Controller
{
    public function index(Request $request)
    {
        $kritik = Kritik::when($request->search, function ($q) use ($request) {
            $q->where('nama_lengkap', 'like', "%{$request->search}%")
                ->orWhere('nik', 'like', "%{$request->search}%")
                ->orWhere('jenis', 'like', "%{$request->search}%");
        })->latest()->paginate(10);

        return view('admin-banceuy.kritik', compact('kritik'));
    }

    public function detail($id)
    {
        $k = Kritik::find($id);

        if (!$k) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $k->id,
                'nama_lengkap' => $k->nama_lengkap,
                'jenis'       => $k->jenis,
                'nik'         => $k->nik,
                'pesan'       => $k->pesan,
                'foto_ktp_url' => $k->foto_ktp ? asset('storage/' . $k->foto_ktp) : null,
            ]
        ]);
    }

    public function destroy($id)
    {
        $k = Kritik::find($id);

        if (!$k) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        }

        $k->delete();

        return response()->json(['success' => true, 'message' => 'Data kritik berhasil dihapus']);
    }

    public function exportPdf()
    {
        $kritik = Kritik::latest()->get();

        $pdf = Pdf::loadView('admin-banceuy.kritik', compact('kritik'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('data-kritik-saran.pdf');
    }
}
