<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\PengunjungPerpustakaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $bukus = Buku::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('penulis', 'like', "%$search%")
                    ->orWhere('kategori', 'like', "%$search%");
            });
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kategoris = KategoriBuku::orderBy('nama')->get();

        return view('admin-banceuy.buku', compact('bukus', 'search', 'kategoris'));
    }

    private function generateSlug($judul)
    {
        $slug = Str::slug($judul);
        $original = $slug;
        $count = 1;

        while (Buku::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|min:3|max:255',
            'penulis' => 'nullable|string|max:150',
            'publisher' => 'nullable|string|max:150',
            'kategori_id' => 'required|exists:kategori_bukus,id', // validasi kategori_id
            'tanggal_publish' => 'required|date|before_or_equal:today',
            'tahun' => 'nullable|digits:4',
            'sinopsis' => 'nullable|string|max:5000',
            'lokasi_rak' => 'required|string|max:100',
            'stock' => 'required|integer|min:0|max:10000',
            'status' => 'required|in:TERSEDIA,HABIS',
            'is_active' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /* sanitize (hanya string tertentu) */
        $fieldsUpper = [
            'judul',
            'penulis',
            'publisher',
            'lokasi_rak',
        ];

        foreach ($fieldsUpper as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = strtoupper(trim(strip_tags($validated[$field])));
            }
        }

        // generate slug dari judul
        $validated['slug'] = $this->generateSlug($validated['judul']);
        $validated['kategori'] = $request->$validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('buku', 'public');
        }

        // Simpan ke database
        Buku::create($validated);

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|min:3|max:255',
            'penulis' => 'nullable|string|max:150',
            'publisher' => 'nullable|string|max:150',
            'kategori' => 'required|string|max:100',
            'tanggal_publish' => 'required|date|before_or_equal:today',
            'tahun' => 'nullable|digits:4',
            'sinopsis' => 'nullable|string|max:5000',
            'lokasi_rak' => 'required|string|max:100',
            'stock' => 'required|integer|min:0|max:10000',
            'status' => 'required|in:TERSEDIA,DIPINJAM',
            'is_active' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /* sanitize */
        $fieldsUpper = [
            'judul',
            'penulis',
            'publisher',
            'kategori',
            'lokasi_rak',
        ];

        foreach ($fieldsUpper as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = strtoupper(trim(strip_tags($validated[$field])));
            }
        }

        /* update slug jika judul berubah */
        if ($validated['judul'] !== $buku->judul) {
            $validated['slug'] = $this->generateSlug($validated['judul']);
        }

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('foto')) {

            if ($buku->foto && Storage::disk('public')->exists($buku->foto)) {
                Storage::disk('public')->delete($buku->foto);
            }

            $validated['foto'] = $request->file('foto')->store('buku', 'public');
        }

        $buku->update($validated);

        return back()->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // Cek apakah buku sedang dipinjam
        $adaPeminjaman = PengunjungPerpustakaan::where('buku_id', $buku->id)
            ->where('status', 'PEMINJAMAN')
            ->exists();

        if ($adaPeminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak bisa dihapus karena sedang dipinjam'
            ], 400);
        }

        // Hapus foto jika ada
        if ($buku->foto && Storage::disk('public')->exists($buku->foto)) {
            Storage::disk('public')->delete($buku->foto);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ]);
    }
}
