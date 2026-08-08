<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Kegiatan;
use App\Helpers\VideoEmbedHelper;

class KegiatanController extends Controller
{
public function index()
{
    $kegiatans = Kegiatan::orderByDesc('published_at')->paginate(10);

    $grouped = $kegiatans->getCollection()->groupBy(function ($item) {
        return strtoupper(trim($item->platform ?? 'UNKNOWN'));
    });

    $videos = [
        'YOUTUBE' => $grouped->get('YOUTUBE', collect())->values(),
        'INSTAGRAM' => $grouped->get('INSTAGRAM', collect())->values(),
        'TIKTOK' => $grouped->get('TIKTOK', collect())->values(),
    ];

    $allEmpty = collect($videos)->flatten()->isEmpty();

    return view('admin-banceuy.kegiatan', compact('kegiatans', 'videos', 'allEmpty'));
}

    public function store(Request $request)
    {
    $validated = $request->validate([
        'judul' => 'required|string|max:255',
        'platform' => 'required|string',
        'deskripsi' => 'nullable|string',

        'video_url' => [
            'required',
            'url',
            function ($attr, $value, $fail) {

                $host = parse_url($value, PHP_URL_HOST);

                if (!$host) return $fail('URL tidak valid.');

                $host = strtolower($host);

                $allowed = [
                    'youtube.com','www.youtube.com','youtu.be',
                    'instagram.com','www.instagram.com',
                    'tiktok.com','www.tiktok.com',
                ];

                if (!in_array($host, $allowed)) {
                    return $fail('Hanya YouTube, Instagram, TikTok.');
                }
            }
        ],

        'published_at' => 'nullable|date',
        'is_active' => 'nullable|boolean',
    ]);

    // 🔥 FORCE UPPERCASE PLATFORM
    $platform = strtoupper($validated['platform']);

    // slug
    $slug = Str::slug($validated['judul']);
    $ori = $slug;
    $i = 1;

    while (Kegiatan::where('slug', $slug)->exists()) {
        $slug = $ori . '-' . $i++;
    }

    Kegiatan::create([
        'judul'        => $validated['judul'],
        'slug'         => $slug,
        'platform'     => $platform, // ✅ FIX INI
        'deskripsi'    => $validated['deskripsi'] ?? null, // ✅ FIX INI
        'video_url'    => $validated['video_url'],
        'video_embed'  => VideoEmbedHelper::make($validated['video_url']),
        'is_active'    => $request->boolean('is_active'),
        'published_at' => $validated['published_at'],
    ]);

    return redirect()->route('admin.kegiatan.index')
        ->with('success', 'Kegiatan berhasil ditambahkan!');
}

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
    
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'platform' => 'required|string',
            'deskripsi' => 'nullable|string',
            'video_url' => [
    'required',
    'url',
    function ($attr, $value, $fail) {

        $host = parse_url($value, PHP_URL_HOST);

        if (!$host) {
            return $fail('URL tidak valid.');
        }

        $host = strtolower($host);

        $allowed = [
            'youtube.com',
            'www.youtube.com',
            'youtu.be',

            'instagram.com',
            'www.instagram.com',

            'tiktok.com',
            'www.tiktok.com',
        ];

        if (!in_array($host, $allowed)) {
            return $fail(
                'Hanya YouTube, Instagram, TikTok.'
            );
        }
    }
],
            'published_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);
    
        $platform = strtoupper($validated['platform']);
    
        if ($validated['judul'] !== $kegiatan->judul) {
            $slug = Str::slug($validated['judul']);
            $ori = $slug;
            $i = 1;
    
            while (
                Kegiatan::where('slug', $slug)
                    ->where('id', '!=', $kegiatan->id)
                    ->exists()
            ) {
                $slug = $ori . '-' . $i++;
            }
    
            $kegiatan->slug = $slug;
        }
    
        $kegiatan->judul        = $validated['judul'];
        $kegiatan->platform     = $platform; // ✅ FIX INI
        $kegiatan->deskripsi    = $validated['deskripsi'] ?? null;
        $kegiatan->video_url    = $validated['video_url'];
        $kegiatan->video_embed  = VideoEmbedHelper::make($validated['video_url']);
        $kegiatan->is_active    = $request->boolean('is_active');
        $kegiatan->published_at = $validated['published_at'];
    
        $kegiatan->save();
    
        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diupdate!');
    }

    public function destroy($id)
    {
        Kegiatan::findOrFail($id)->delete();

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }
    
    
    
}
