<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(['id' => 1]);
        return view('admin-banceuy.setting', compact('setting'));
    }

    public function update(Request $request)
    {
        // Ambil atau buat record pertama
        $setting = Setting::firstOrCreate(['id' => 1]);

        // ================= VALIDASI =================
        $validated = $request->validate([
            // META
            'nama_website'       => 'required|string|max:120',
            'meta_description'   => 'nullable|string|max:160',
            'meta_author'        => 'nullable|string|max:80',
            'meta_generator'     => 'nullable|string|max:80',
            'meta_theme_color'   => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'meta_canonical'     => 'nullable|url|max:255',

            // HEADER
            'judul_header_1'     => 'nullable|string|max:120',
            'judul_header_2'     => 'nullable|string|max:120',
            'teks_marquee'       => 'nullable|string|max:255',

            // FOOTER
            'footer_copyright'   => 'nullable|string|max:120',

            // SOSIAL MEDIA
            'facebook'           => 'nullable|url|max:255',
            'instagram'          => 'nullable|url|max:255',
            'twitter'            => 'nullable|url|max:255',
            'youtube'            => 'nullable|url|max:255',
            'tiktok'             => 'nullable|url|max:255',
            'whatsapp'           => ['nullable', 'regex:/^\+?[0-9]{9,15}$/'],

            // OG
            'og_title'           => 'nullable|string|max:120',
            'og_description'     => 'nullable|string|max:160',
            'og_image'           => 'nullable|string|max:255',
            'og_type'            => 'nullable|string|max:50',
            'og_url'             => 'nullable|url|max:255',

            // TWITTER
            'twitter_card'       => 'nullable|string|max:50',
            'twitter_site'       => 'nullable|string|max:50',
            'twitter_creator'    => 'nullable|string|max:50',
            'twitter_title'      => 'nullable|string|max:120',
            'twitter_description' => 'nullable|string|max:160',
            'twitter_image'      => 'nullable|string|max:255',

            // UPLOAD GAMBAR
            'logo_utama'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'hero_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'logo_footer'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'menu_pendaftaran_img'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'menu_cek_antrian_img'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'menu_titip_barang_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'menu_kritik_saran_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            // ICON / FAVICON
            'apple_touch_icon'   => 'nullable|image|mimes:png|max:4096',
            'favicon_32'         => 'nullable|image|mimes:png|max:2048',
            'favicon_16'         => 'nullable|image|mimes:png|max:2048',
            'favicon_ico'        => 'nullable|mimes:ico|max:2048',
            'manifest_json'      => 'nullable|string|max:4096',
            'mask_icon'          => 'nullable|image|mimes:svg,png|max:4096',
            'mask_icon_color'    => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        // ================= UPDATE TEXT FIELD =================
        $setting->update([
            'nama_website'       => $request->nama_website,
            'meta_description'   => $request->meta_description,
            'meta_author'        => $request->meta_author,
            'meta_generator'     => $request->meta_generator,
            'meta_theme_color'   => $request->meta_theme_color,
            'meta_canonical'     => $request->meta_canonical,

            'judul_header_1'     => $request->judul_header_1,
            'judul_header_2'     => $request->judul_header_2,
            'teks_marquee'       => $request->teks_marquee,

            'footer_copyright'   => $request->footer_copyright,

            'facebook'           => $request->facebook,
            'instagram'          => $request->instagram,
            'twitter'            => $request->twitter,
            'youtube'            => $request->youtube,
            'tiktok'             => $request->tiktok,
            'whatsapp'           => preg_replace('/[^0-9+]/', '', $request->whatsapp),

            // ================= OG =================
            'og_title'           => $request->og_title,
            'og_description'     => $request->og_description,
            'og_image'           => $request->og_image,
            'og_type'            => $request->og_type ?? 'website',
            'og_url'             => $request->og_url,

            // ================= Twitter =================
            'twitter_card'       => $request->twitter_card ?? 'summary_large_image',
            'twitter_site'       => $request->twitter_site,
            'twitter_creator'    => $request->twitter_creator,
            'twitter_title'      => $request->twitter_title,
            'twitter_description' => $request->twitter_description,
            'twitter_image'      => $request->twitter_image,

            'manifest_json'      => $request->manifest_json,
            'mask_icon_color'    => $request->mask_icon_color,
        ]);

        // ================= UPLOAD FILE DENGAN DELETE OLD =================
        $uploadPath = public_path('uploads/setting');
        if (!file_exists($uploadPath)) mkdir($uploadPath, 0777, true);

        $upload = function ($field, $dbField) use ($request, $setting, $uploadPath) {
            if ($request->hasFile($field)) {
                // hapus file lama
                if ($setting->{$dbField} && file_exists(public_path($setting->{$dbField}))) {
                    @unlink(public_path($setting->{$dbField}));
                }
                $file = $request->file($field);
                $filename = $dbField . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $setting->{$dbField} = 'uploads/setting/' . $filename;
            }
        };

        $gambarFields = [
            'logo_utama',
            'hero_image',
            'logo_footer',
            'menu_pendaftaran_img',
            'menu_cek_antrian_img',
            'menu_titip_barang_img',
            'menu_kritik_saran_img',
            'apple_touch_icon',
            'favicon_32',
            'favicon_16',
            'favicon_ico',
            'mask_icon'
        ];

        foreach ($gambarFields as $field) $upload($field, $field);

        $setting->save();

        return redirect()->back()->with('success', 'Setting berhasil disimpan!');
    }
}
