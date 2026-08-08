<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
{
    $categories = KategoriProduk::orderBy('name', 'asc')->get();

    $query = Produk::with('category:id,name');

    // SEARCH (SAFE)
    if ($request->filled('search')) {
        $search = trim(strip_tags($request->search));
        $query->where('name', 'like', "%{$search}%");
    }

    // FILTER CATEGORY
    if ($request->filled('category_id')) {
        $query->where('category_id', (int) $request->category_id);
    }

    // SORT
    $query->when($request->sort, function ($q, $sort) {
        return match ($sort) {
            'oldest' => $q->oldest(),
            'name'   => $q->orderBy('name', 'asc'),
            default  => $q->latest(),
        };
    }, function ($q) {
        return $q->latest();
    });

    // PAGINATION (BIAR RINGAN)
    $products = $query->paginate(12)->withQueryString();

    return view('admin-banceuy.produk', compact('products', 'categories'));
}

    /*
    |--------------------------------------------------------------------------
    | STORE CATEGORY
    |--------------------------------------------------------------------------
    */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\&]+$/'
            ],
            'parent_id' => 'nullable|exists:kategori_produks,id'
        ]);

        KategoriProduk::create([
            'name' => strtoupper(strip_tags($request->name)),
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'is_active' => true
        ]);

        return back()->with('success', 'Category berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE CATEGORY
    |--------------------------------------------------------------------------
    */
    public function deleteCategory($id)
    {
        KategoriProduk::findOrFail((int)$id)->delete();

        return back()->with('success', 'Category berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PRODUCT
    |--------------------------------------------------------------------------
    */
     public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:kategori_produks,id',

            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\&]+$/'
            ],

            'description' => 'nullable|string|max:3000',

            'price' => 'required|numeric|min:0|max:1000000000',
            'stock' => 'required|integer|min:0|max:1000000',
            'weight' => 'nullable|numeric|min:0|max:100000',

            'status' => 'required|in:draft,published,archived',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // =========================
        // SANITIZE NAME
        // =========================
        $name = strtoupper(strip_tags($request->name));

        // =========================
        // CLEAN HTML DESCRIPTION (SAFE MODE)
        // =========================
        $description = $request->description;

        // buang script tag
        $description = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $description);

        // buang event handler (onclick, onerror, dll)
        $description = preg_replace('/on\w+="[^"]*"/i', '', $description);
        $description = preg_replace("/on\w+='[^']*'/i", '', $description);

        // =========================
        // IMAGE
        // =========================
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('foto_produk', 'public');
        }

        // =========================
        // INSERT PRODUCT
        // =========================
        Produk::create([
            'category_id' => (int) $request->category_id,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $description, // HTML aman tetap masuk
            'image' => $imagePath,
            'price' => (float) $request->price,
            'stock' => (int) $request->stock,
            'weight' => $request->weight ?? null,
            'status' => $request->status,
            'is_active' => true
        ]);

        return back()->with('success', 'Product berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PRODUCT
    |--------------------------------------------------------------------------
    */
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:kategori_produks,id',

            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\&]+$/'
            ],

            'description' => 'nullable|string|max:3000',

            'price' => 'required|numeric|min:0|max:1000000000',

            'stock' => 'required|integer|min:0|max:1000000',

            'weight' => 'nullable|numeric|min:0|max:100000',

            'status' => 'required|in:draft,published,archived',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $product = Produk::findOrFail((int)$id);

        $imagePath = $product->image;

        // IMAGE UPDATE
        if ($request->hasFile('image')) {

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')->store('foto_produk', 'public');
        }

        $name = strtoupper(strip_tags($request->name));
        $description = strip_tags($request->description);

        $product->update([
            'category_id' => (int) $request->category_id,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $description,
            'image' => $imagePath,
            'price' => (float) $request->price,
            'stock' => (int) $request->stock,
            'weight' => $request->weight,
            'status' => $request->status
        ]);

        return back()->with('success', 'Product berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE PRODUCT
    |--------------------------------------------------------------------------
    */
    public function deleteProduct($id)
    {
        $product = Produk::findOrFail((int)$id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Product berhasil dihapus');
    }
}