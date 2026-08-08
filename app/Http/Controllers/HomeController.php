<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\{Berita, Bulettin, Informasi, Kegiatan, Profile, Setting};

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
        ]);
    
        $search = $request->input('search');
    
        $berita = Berita::query()
            ->where('is_active', 1)
    
            // search aman pakai Eloquent
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', '%' . $search . '%');
            })
    
            // sorting tanpa raw SQL
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
    
            ->limit(6)
            ->get();
    
        $setting = Setting::first();
    
        return view('welcome', compact('setting', 'berita'));
    }

    public function kunjungan()
    {
        return view('kunjungan');
    }

    public function profile()
    {
        $setting = Setting::first();
        $profile = Profile::first();
        return view('profile', compact('profile', 'setting'));
    }


    public function bulettinIndex()
    {
        $bulettin = Bulettin::query()
            ->where('is_active', 1)
            ->orderByRaw("COALESCE(published_at, created_at) DESC")
            ->limit(6)
            ->get();
        $setting = Setting::first(); // ambil setting (row pertama)
        return view('bulettin', compact('setting', 'bulettin'));
    }



    public function informasi()
    {
        // ambil semua informasi aktif, urut terbaru
        $informasis = Informasi::where('is_active', 1)
            ->orderBy('created_at', 'ASC')
            ->get();

        $setting = Setting::first(); // optional

        return view('informasi_layanan', compact('informasis', 'setting'));
    }


    public function about()
    {

        $setting = Setting::first(); // optional

        return view('tentang-kami', compact('setting'));
    }


    public function kegiatan()
{
    $kegiatans = Kegiatan::where('is_active', true)
    ->where(function ($q) {
        $q->whereNull('published_at')
          ->orWhere('published_at', '<=', now());
    })
    ->orderByDesc('published_at')
    ->orderByDesc('id')
    ->get();

    $grouped = $kegiatans
        ->groupBy(
            fn ($item)
                => strtoupper(
                    $item->platform
                    ?? 'UNKNOWN'
                )
        );

    $videos = [

        'YOUTUBE' =>
            $grouped->get(
                'YOUTUBE',
                collect()
            ),

        'INSTAGRAM' =>
            $grouped->get(
                'INSTAGRAM',
                collect()
            ),

        'TIKTOK' =>
            $grouped->get(
                'TIKTOK',
                collect()
            ),

    ];

    $setting =
        Setting::first();

    return view(
        'kegiatan',
        compact(
            'videos',
            'setting'
        )
    );
}



    public function berita(Request $request)
    {
        $q = $request->q;

        $berita = Berita::query()
            ->where('is_active', 1)
            ->when($q, function ($query) use ($q) {
                $query->where('judul', 'like', "%$q%")
                    ->orWhere('isi', 'like', "%$q%");
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $setting = Setting::first();

        return view('berita', compact('berita', 'setting'));
    }

    public function showBerita($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $setting = Setting::first();

        return view('detail_berita', compact('berita', 'setting'));
    }


    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        $query = Berita::query()
            ->where('is_active', 1);

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('isi', 'like', "%{$q}%");
            });
        }

        $data = $query->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'slug' => $item->slug,
                    'tanggal' => $item->published_at
                        ? $item->published_at->format('d M Y H:i')
                        : $item->created_at->format('d M Y H:i'),

                    // thumbnail untuk JS
                    'thumb' => $item->thumbnail
                        ? asset('storage/' . $item->thumbnail)
                        : null,

                    // cuplikan isi
                    'excerpt' => Str::limit(strip_tags($item->isi), 130),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'status'   => 'required|boolean',
            'location' => 'required|string',
            'lat'      => 'required|numeric',
            'lng'      => 'required|numeric',
        ]);

        $data = [
            "status"     => $request->status,
            "location"   => $request->location,
            "lat"        => $request->lat,
            "lng"        => $request->lng,
            "ip"         => $request->ip(),
            "created_at" => now()->toDateTimeString(),
        ];

        // pastikan foldernya ada
        Storage::disk('public')->makeDirectory('/');

        Storage::disk('public')->put('debug_location.json', json_encode($data, JSON_PRETTY_PRINT));

        return response()->json([
            "success" => true,
            "message" => "Lokasi berhasil disimpan",
            "url"     => asset('storage/debug_location.json'),
        ]);
    }
    
    public function getKuota(Request $request)
{
    $tanggal = $request->tanggal;

    $data = DB::table('antrians')
        ->where('tanggal', $tanggal)
        ->first();

    return response()->json([
        'tanggal' => $tanggal,
        'kuota' => $data->kuota ?? 100
    ]);
}
}
