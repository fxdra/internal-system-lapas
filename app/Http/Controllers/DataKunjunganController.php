<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Antrian, Pengunjung, Wbp};
use App\Http\Controllers\Controller;

class DataKunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // funtion search wbp


    public function index(Request $request)
    {

        $pengunjungs = Pengunjung::with(['pengikut', 'wbp'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where('nama_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nik_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nama_wbp', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin-banceuy.kunjungan', compact('pengunjungs'));
    }
    
    
     public function pending(Request $request)
    {

        $pengunjungs = Pengunjung::with(['pengikut', 'wbp'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where('nama_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nik_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nama_wbp', 'like', "%{$search}%");
            })
            ->where('status_barcode', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin-banceuy.status_pending', compact('pengunjungs'));
    }
    
    
   
    
     public function checkIn(Request $request)
    {
        
        $pengunjungs = Pengunjung::with(['pengikut', 'wbp'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where('nama_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nik_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nama_wbp', 'like', "%{$search}%");
            })
            ->where('status_barcode', 'checkin')
            ->latest()
            ->paginate(10);

        return view('admin-banceuy.status_checkin', compact('pengunjungs'));
    }
    
     public function checkOut(Request $request)
    {
       

        $pengunjungs = Pengunjung::with(['pengikut', 'wbp'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where('nama_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nik_pengunjung', 'like', "%{$search}%")
                    ->orWhere('nama_wbp', 'like', "%{$search}%");
            })
            ->where('status_barcode', 'checkout')
            ->latest()
            ->paginate(10);

        return view('admin-banceuy.status_checkout', compact('pengunjungs'));
    }
    
    
    public function kuotaKunjungan(Request $request){
        $search = trim($request->search);

        $bulanMap = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
        ];
    
        $searchLower = strtolower($search);
    
        $bulanAngka = $bulanMap[$searchLower] ?? null;
    
        $kuota = \App\Models\Antrian::query()
            ->when($search, function ($q) use ($search, $bulanAngka) {
    
                $q->where('keterangan', 'like', "%$search%")
                  ->orWhere('tanggal', 'like', "%$search%");
    
                // kalau user ketik nama bulan: "maret" -> cari "-03-"
                if ($bulanAngka) {
                    $q->orWhere('tanggal', 'like', "%-$bulanAngka-%");
                }
            })
            ->orderBy('tanggal', 'asc')
            ->paginate(5);
        return view('admin-banceuy.kuota-kunjungan', compact('kuota'));
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
    public function store(Request $request)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
