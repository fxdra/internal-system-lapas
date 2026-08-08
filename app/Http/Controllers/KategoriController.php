<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;
use Illuminate\Support\Str;

class KategoriController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        $kategoris = KategoriBuku::when($search, function ($query) use ($search) {

            $query->where('nama', 'like', "%{$search}%");

        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('admin-banceuy.kategori', compact('kategoris','search'));
    }



    private function generateSlug($nama)
    {
        $slug = Str::slug($nama);
        $original = $slug;
        $count = 1;

        while (KategoriBuku::where('slug', $slug)->exists()) {

            $slug = $original . '-' . $count++;

        }

        return $slug;
    }



    public function store(Request $request)
    {

        $data = $request->validate([

            'nama' => 'required|string|min:3|max:100'

        ]);



        /* sanitasi */
        $data['nama'] = strtoupper(strip_tags(trim($data['nama'])));



        /* slug unik */
        $data['slug'] = $this->generateSlug($data['nama']);



        KategoriBuku::create($data);



        return back()->with('success', 'Kategori berhasil ditambahkan');

    }



    public function update(Request $request, $id)
    {

        $kategori = KategoriBuku::findOrFail($id);



        $data = $request->validate([

            'nama' => 'required|string|min:3|max:100'

        ]);



        $data['nama'] = strtoupper(strip_tags(trim($data['nama'])));



        /* jika nama berubah update slug */
        if ($data['nama'] !== $kategori->nama) {

            $data['slug'] = $this->generateSlug($data['nama']);

        }



        $kategori->update($data);



        return back()->with('success', 'Kategori berhasil diperbarui');

    }



    public function destroy($id)
    {

        $kategori = KategoriBuku::findOrFail($id);



        $kategori->delete();



        return back()->with('success', 'Kategori berhasil dihapus');

    }

}
