<?php

namespace App\Http\Controllers;

use App\Models\Bulettin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class BulettinController extends Controller
{
    public function index()
    {
        $bulettin = Bulettin::orderBy('created_at', 'desc')->paginate(10);
        return view('admin-banceuy.bulettin', compact('bulettin'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'upload_pdf'   => 'nullable|file|mimes:pdf|max:262144', // 256MB
            'edisi'        => 'nullable|string',
            'is_active'    => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);




        $slug = Str::slug($validated['judul']);
        $originalSlug = $slug;
        $i = 1;
        while (Bulettin::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        $pathPdf = null;
        if ($request->hasFile('upload_pdf')) {
            $pathPdf = $request->file('upload_pdf')->store('berita', 'public');
        }

        Bulettin::create([
            'judul'        => $validated['judul'],
            'slug'         => $slug,
            'upload_pdf'   => $pathPdf,
            'edisi'        => $validated['edisi'],
            'is_active'    => $request->boolean('is_active'),
            'published_at' => $validated['published_at'] ?? null,
        ]);

        return redirect()->route('admin.bulettin.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $bulettin = Bulettin::findOrFail($id);

        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'edisi'        => 'required|string|max:255',
            'upload_pdf'   => 'nullable|mimes:pdf|max:262144', // 256MB
            'is_active'    => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        // slug update kalau judul berubah
        if ($validated['judul'] !== $bulettin->judul) {
            $slug = Str::slug($validated['judul']);
            $originalSlug = $slug;
            $i = 1;

            while (Bulettin::where('slug', $slug)->where('id', '!=', $bulettin->id)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            $bulettin->slug = $slug;
        }

        // PDF update
        if ($request->hasFile('upload_pdf')) {
            if ($bulettin->upload_pdf && Storage::disk('public')->exists($bulettin->upload_pdf)) {
                Storage::disk('public')->delete($bulettin->upload_pdf);
            }

            $bulettin->upload_pdf = $request->file('upload_pdf')
                ->store('bulettin', 'public');
        }

        $bulettin->judul        = $validated['judul'];
        $bulettin->edisi        = $validated['edisi'];
        $bulettin->is_active    = $request->boolean('is_active');
        $bulettin->published_at = $validated['published_at'] ?? null;

        $bulettin->save();

        return redirect()
            ->route('admin.bulettin.index')
            ->with('success', 'Buletin berhasil diupdate!');
    }

    public function destroy($id)
    {
        $bulettin = Bulettin::findOrFail($id);

        if ($bulettin->upload_pdf && Storage::disk('public')->exists($bulettin->upload_pdf)) {
            Storage::disk('public')->delete($bulettin->upload_pdf);
        }

        $bulettin->delete();

        return redirect()
            ->route('admin.bulettin.index')
            ->with('success', 'Buletin berhasil dihapus!');
    }
}
