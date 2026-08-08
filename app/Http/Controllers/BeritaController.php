<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::orderByDesc('id')->paginate(10);
        return view('admin-banceuy.berita', compact('beritas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'isi'          => 'nullable|string',
            'is_active'    => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);
    
        // FIX: decode isi agar tidak tersimpan sebagai &lt;div&gt;
        $isiFix = isset($validated['isi'])
            ? html_entity_decode($validated['isi'], ENT_QUOTES | ENT_HTML5, 'UTF-8')
            : null;
    
        $slug = Str::slug($validated['judul']);
        $originalSlug = $slug;
        $i = 1;
        while (Berita::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }
    
        $pathThumb = null;
        if ($request->hasFile('thumbnail')) {
            $pathThumb = $request->file('thumbnail')->store('berita', 'public');
        }
    
        Berita::create([
            'judul'        => $validated['judul'],
            'slug'         => $slug,
            'thumbnail'    => $pathThumb,
            'isi'          => $isiFix,
            'is_active'    => $request->boolean('is_active'),
            'published_at' => $validated['published_at'] ?? null,
        ]);
    
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }
    
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
    
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'isi'          => 'nullable|string',
            'is_active'    => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);
    
        // FIX: decode isi agar tidak jadi &lt;div&gt;
        $isiFix = isset($validated['isi'])
            ? html_entity_decode($validated['isi'], ENT_QUOTES | ENT_HTML5, 'UTF-8')
            : null;
    
        // slug update kalau judul berubah
        if ($validated['judul'] !== $berita->judul) {
            $slug = Str::slug($validated['judul']);
            $originalSlug = $slug;
            $i = 1;
    
            while (Berita::where('slug', $slug)->where('id', '!=', $berita->id)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }
    
            $berita->slug = $slug;
        }
    
        // thumbnail update
        if ($request->hasFile('thumbnail')) {
            if ($berita->thumbnail && Storage::disk('public')->exists($berita->thumbnail)) {
                Storage::disk('public')->delete($berita->thumbnail);
            }
            $berita->thumbnail = $request->file('thumbnail')->store('berita', 'public');
        }
    
        $berita->judul        = $validated['judul'];
        $berita->isi          = $isiFix;
        $berita->is_active    = $request->boolean('is_active');
        $berita->published_at = $validated['published_at'] ?? null;
        $berita->save();
    
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diupdate!');
    }


    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->thumbnail && Storage::disk('public')->exists($berita->thumbnail)) {
            Storage::disk('public')->delete($berita->thumbnail);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}
