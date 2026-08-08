<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KomandanJaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KomjaController extends Controller
{
    
// ================= FILTER =================
public function filter(Request $request)
{
    $komandanJaga = KomandanJaga::query()

        ->when($request->status, function ($q) use ($request) {

            $q->where('status', $request->status);

        }, function ($q) {

            $q->where('status', 'Aktif');

        })

        ->when($request->search, function ($q) use ($request) {

            $q->where(function ($qq) use ($request) {

                $qq->where('nama_petugas', 'like', '%' . $request->search . '%')
                   ->orWhere('nip', 'like', '%' . $request->search . '%');

            });

        })

        ->orderBy('nama_petugas')
        ->paginate(10);

    return response()->json([

        'success' => true,

        'data' => $komandanJaga->items(),

        'total' => $komandanJaga->total(),

        'current_page' => $komandanJaga->currentPage(),

        'last_page' => $komandanJaga->lastPage(),

        'next_page_url' => $komandanJaga->nextPageUrl(),

        'prev_page_url' => $komandanJaga->previousPageUrl(),

    ]);
}
    
// ================= SEARCH AJAX =================
public function search(Request $request)
{
    $query = $request->get('query');

    $results = KomandanJaga::where('nama_petugas', 'like', "%{$query}%")
        ->orWhere('nip', 'like', "%{$query}%")
        ->limit(10)
        ->get(['nama_petugas', 'nip']);

    return response()->json($results);
}


// ================= INDEX =================
public function index(Request $request)
{
    // ================= MAIN QUERY =================
    $komandanJaga = KomandanJaga::query()
        ->when($request->status, function ($q) use ($request) {
            $q->where('status', $request->status);
        }, function ($q) {
            $q->where('status', 'Aktif');
        })
        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($qq) use ($request) {
                $qq->where('nama_petugas', 'like', "%{$request->search}%")
                   ->orWhere('nip', 'like', "%{$request->search}%");
            });
        })
        ->orderBy('nama_petugas')
        ->paginate(10)
        ->withQueryString();


    // ================= STATUS LIST (SOURCE OF TRUTH) =================
    $statuses = [
        'Aktif',
        'Cuti',
        'Dinas Luar',
        'Sakit',
        'Mutasi',
        'Nonaktif'
    ];


    // ================= AUTO COUNT (NO DUPLICATE QUERY) =================
    $counts = KomandanJaga::selectRaw('status, COUNT(*) as total')
        ->whereIn('status', $statuses)
        ->groupBy('status')
        ->pluck('total', 'status');


    // ================= NORMALIZE OUTPUT =================
    $countAktif      = $counts['Aktif'] ?? 0;
    $countCuti       = $counts['Cuti'] ?? 0;
    $countDinasLuar  = $counts['Dinas Luar'] ?? 0;
    $countSakit      = $counts['Sakit'] ?? 0;
    $countMutasi     = $counts['Mutasi'] ?? 0;
    $countNonaktif   = $counts['Nonaktif'] ?? 0;


    return view('admin-banceuy.komja', compact(
        'komandanJaga',
        'countAktif',
        'countCuti',
        'countDinasLuar',
        'countSakit',
        'countMutasi',
        'countNonaktif'
    ));
}



    
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
    
            'nama_petugas' => [
                'required',
                'string',
                'min:3',
                'max:100'
            ],
    
            'nip' => [
                'required',
                'digits:18',
                'unique:komandan_jagas,nip'
            ],
    
            'pangkat' => [
                'nullable',
                'string',
                'max:50'
            ],
    
            'golongan' => [
                'nullable',
                'string',
                'max:30'
            ],
    
            'jabatan' => [
                'nullable',
                'string',
                'max:100'
            ],
    
            'regu_jaga' => [
                'nullable',
                'in:Regu A,Regu B,Regu C,Regu D'
            ],
    
            'nomor_hp' => [
                'nullable',
                'digits_between:10,15'
            ],
    
            'email' => [
                'nullable',
                'email',
                'max:100',
                'unique:komandan_jagas,email'
            ],
    
            'status' => [
                'required',
                'in:Aktif,Nonaktif'
            ],
    
            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
    
            'keterangan' => [
                'nullable',
                'string',
                'max:500'
            ],
    
        ],[
    
            'nama_petugas.required' => 'Nama petugas wajib diisi.',
            'nama_petugas.min'      => 'Nama minimal 3 karakter.',
            'nama_petugas.max'      => 'Nama maksimal 100 karakter.',
    
            'nip.required'          => 'NIP wajib diisi.',
            'nip.digits'            => 'NIP harus terdiri dari 18 digit.',
            'nip.unique'            => 'NIP sudah terdaftar.',
    
            'nomor_hp.digits_between' => 'Nomor HP harus 10-15 digit.',
    
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah digunakan.',
    
            'status.required'       => 'Status wajib dipilih.',
    
            'foto.image'            => 'File harus berupa gambar.',
            'foto.mimes'            => 'Foto harus JPG, JPEG atau PNG.',
            'foto.max'              => 'Ukuran foto maksimal 2 MB.',
    
            'keterangan.max'        => 'Keterangan maksimal 500 karakter.'
    
        ]);
    
        if ($validator->fails()) {
    
            return back()
                ->withErrors($validator)
                ->withInput();
        }
    
        DB::beginTransaction();
    
        try {
    
            $foto = null;
    
            if ($request->hasFile('foto')) {
    
                $file = $request->file('foto');
    
                $namaFile = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
    
                $file->move(public_path('uploads/komandan-jaga'), $namaFile);
    
                $foto = 'uploads/komandan-jaga/'.$namaFile;
            }
    
            KomandanJaga::create([
    
                'nama_petugas' => strtoupper($request->nama_petugas),
                'nip'          => strtoupper($request->nip),
                'pangkat'      => strtoupper($request->pangkat),
                'golongan'     => strtoupper($request->golongan),
                'jabatan'      => strtoupper($request->jabatan),
                'regu_jaga'    => strtoupper($request->regu_jaga),
                'nomor_hp'     => strtoupper($request->nomor_hp),
                'email'        => strtolower($request->email),
                'foto'         => $foto,
                'status'       => $request->status,
                'keterangan'   => strtoupper($request->keterangan),
    
            ]);
    
            DB::commit();
    
            return redirect()
                ->back()
                ->with('success','Petugas berhasil ditambahkan.');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            if (!empty($foto) && File::exists(public_path($foto))) {
    
                File::delete(public_path($foto));
            }
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error',$e->getMessage());
        }
    }

}
