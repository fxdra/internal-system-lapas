<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Support\Facades\Storage;

class InformasiLayananController extends Controller
{
  public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $data = Informasi::query()
            ->when($q, function ($query) use ($q) {
                $query->where('judul', 'like', "%{$q}%")
                      ->orWhere('isi', 'like', "%{$q}%")
                      ->orWhere('instagram', 'like', "%{$q}%")
                      ->orWhere('facebook', 'like', "%{$q}%")
                      ->orWhere('twitter', 'like', "%{$q}%")
                      ->orWhere('contact_support', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin-banceuy.informasi_layanan', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'isi' => 'required|string',

            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'contact_support' => 'nullable|string|max:255',

            'is_active' => 'required|boolean',
        ]);

        $thumbPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbPath = $request->file('thumbnail')->store('informasi-layanan', 'public');
        }

        Informasi::create([
            'judul' => $request->judul,
            'thumbnail' => $thumbPath,
            'isi' => $request->isi,

            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'contact_support' => $request->contact_support,

            'is_active' => (int) $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Informasi layanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = Informasi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'isi' => 'required|string',

            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'contact_support' => 'nullable|string|max:255',

            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
                Storage::disk('public')->delete($item->thumbnail);
            }
            $item->thumbnail = $request->file('thumbnail')->store('informasi-layanan', 'public');
        }

        $item->judul = $request->judul;
        $item->isi = $request->isi;

        $item->instagram = $request->instagram;
        $item->facebook = $request->facebook;
        $item->twitter = $request->twitter;
        $item->contact_support = $request->contact_support;

        $item->is_active = (int) $request->is_active;
        $item->save();

        return redirect()->back()->with('success', 'Informasi layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Informasi::findOrFail($id);

        if ($item->thumbnail && Storage::disk('public')->exists($item->thumbnail)) {
            Storage::disk('public')->delete($item->thumbnail);
        }

        $item->delete();

        return redirect()->back()->with('success', 'Informasi layanan berhasil dihapus.');
    }
}
