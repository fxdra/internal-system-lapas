<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Setting, Antrian, Pengunjung};
use App\Http\Controllers\Controller;

class AntrianController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal dari query string
        $tanggal = $request->query('tanggal');

        // Query Antrian sesuai tanggal yang dipilih
        $query = Antrian::query();

        if ($tanggal) {
            $query->whereDate('tanggal', $tanggal);
        }

        // Urutkan berdasarkan tanggal descending
        $kuota = $query->orderBy('tanggal', 'DESC')->get();
        
        
        $setting = Setting::first();

        return view('tatap-muka', compact('kuota','setting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'     => 'required|date',
            'kuota'       => 'required|integer|min:1',
            'keterangan'  => 'nullable|string'
        ]);

        Antrian::create($data);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $kuota = Antrian::findOrFail($id);

        $data = $request->validate([
            'tanggal'     => 'required|date',
            'kuota'       => 'required|integer|min:1',
            'keterangan'  => 'nullable|string'
        ]);

        $kuota->update($data);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        return Antrian::findOrFail($id);
    }

    public function destroy($id)
    {
        $kuota = Antrian::findOrFail($id);
        $kuota->delete();

        return response()->json([
            'message' => 'Kuota berhasil dihapus'
        ]);
    }
}
