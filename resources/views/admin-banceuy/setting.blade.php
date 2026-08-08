@extends('admin-banceuy.partisi.main')

@section('content')

    <style>
        .nav-pills .nav-link.active {
            background: #0d6efd;
            color: #fff;
        }
    </style>

    <div class="container-fluid">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="feather-settings me-1"></i> Setting Website
                </h4>
                <small class="text-muted">Kelola meta, header, logo, menu, footer, sosial media, dan SEO preview.</small>
            </div>

            <a href="/" target="_blank" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="feather-external-link me-1"></i> Lihat Website
            </a>
        </div>

        {{-- ================= ALERT ================= --}}
        @if (session('success'))
            <div class="alert alert-success rounded-4 border-0 shadow-sm">
                <i class="feather-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 border-0 shadow-sm">
                <div class="fw-semibold mb-2">
                    <i class="feather-alert-triangle me-1"></i> Ada kesalahan input:
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <form action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    {{-- ================= TAB MENU ================= --}}
                    <ul class="nav nav-pills gap-2 mb-4" id="settingTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-3" id="tab-meta" data-bs-toggle="tab"
                                data-bs-target="#meta" type="button" role="tab">
                                <i class="feather-globe me-1"></i> Meta
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3" id="tab-header" data-bs-toggle="tab"
                                data-bs-target="#header" type="button" role="tab">
                                <i class="feather-layout me-1"></i> Header
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3" id="tab-gambar" data-bs-toggle="tab"
                                data-bs-target="#gambar" type="button" role="tab">
                                <i class="feather-image me-1"></i> Logo & Gambar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3" id="tab-menu" data-bs-toggle="tab"
                                data-bs-target="#menu" type="button" role="tab">
                                <i class="feather-grid me-1"></i> Menu
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3" id="tab-footer" data-bs-toggle="tab"
                                data-bs-target="#footer" type="button" role="tab">
                                <i class="feather-credit-card me-1"></i> Footer
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3" id="tab-sosmed" data-bs-toggle="tab"
                                data-bs-target="#sosmed" type="button" role="tab">
                                <i class="feather-share-2 me-1"></i> Sosmed
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3" id="tab-favicon" data-bs-toggle="tab"
                                data-bs-target="#favicon" type="button" role="tab">
                                <i class="feather-sliders me-1"></i> Favicon & Manifest
                            </button>
                        </li>
                    </ul>

                    {{-- ================= TAB CONTENT ================= --}}
                    <div class="tab-content" id="settingTabContent">

                        {{-- ================= META ================= --}}
                        <div class="tab-pane fade show active" id="meta" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="mb-1 fw-bold">Meta Website</div>
                                    <small class="text-muted">Title, meta description, OG & Twitter untuk SEO dan
                                        preview.</small>
                                </div>

                                {{-- Website basic meta --}}
                                <div class="col-lg-12">
                                    <label class="form-label fw-semibold">Nama Website (Title)</label>
                                    <input type="text" class="form-control" name="nama_website"
                                        value="{{ old('nama_website', $setting->nama_website) }}">
                                </div>

                                <div class="col-lg-12">
                                    <label class="form-label fw-semibold">Meta Description</label>
                                    <input type="text" class="form-control" name="meta_description"
                                        value="{{ old('meta_description', $setting->meta_description) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Meta Author</label>
                                    <input type="text" class="form-control" name="meta_author"
                                        value="{{ old('meta_author', $setting->meta_author) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Meta Generator</label>
                                    <input type="text" class="form-control" name="meta_generator"
                                        value="{{ old('meta_generator', $setting->meta_generator) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Theme Color</label>
                                    <input type="text" class="form-control" name="meta_theme_color"
                                        value="{{ old('meta_theme_color', $setting->meta_theme_color) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Canonical URL</label>
                                    <input type="text" class="form-control" name="meta_canonical"
                                        value="{{ old('meta_canonical', $setting->meta_canonical) }}">
                                </div>

                                {{-- Open Graph --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">OG Title</label>
                                    <input type="text" class="form-control" name="og_title"
                                        value="{{ old('og_title', $setting->og_title) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">OG Description</label>
                                    <input type="text" class="form-control" name="og_description"
                                        value="{{ old('og_description', $setting->og_description) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">OG Image URL</label>
                                    <input type="text" class="form-control" name="og_image"
                                        value="{{ old('og_image', $setting->og_image) }}">
                                </div>

                                {{-- Twitter --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Twitter Title</label>
                                    <input type="text" class="form-control" name="twitter_title"
                                        value="{{ old('twitter_title', $setting->twitter_title) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Twitter Description</label>
                                    <input type="text" class="form-control" name="twitter_description"
                                        value="{{ old('twitter_description', $setting->twitter_description) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Twitter Image URL</label>
                                    <input type="text" class="form-control" name="twitter_image"
                                        value="{{ old('twitter_image', $setting->twitter_image) }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= HEADER ================= --}}
                        <div class="tab-pane fade" id="header" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Judul Header 1</label>
                                    <input type="text" class="form-control" name="judul_header_1"
                                        value="{{ old('judul_header_1', $setting->judul_header_1) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Judul Header 2</label>
                                    <input type="text" class="form-control" name="judul_header_2"
                                        value="{{ old('judul_header_2', $setting->judul_header_2) }}">
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label fw-semibold">Teks Marquee</label>
                                    <input type="text" class="form-control" name="teks_marquee"
                                        value="{{ old('teks_marquee', $setting->teks_marquee) }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= LOGO & GAMBAR ================= --}}
                        <div class="tab-pane fade" id="gambar" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Logo Utama</label>
                                    <input type="file" class="form-control" name="logo_utama" accept="image/*">
                                    @if ($setting->logo_utama)
                                        <img src="{{ asset($setting->logo_utama) }}" style="max-height:120px;">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Hero Image</label>
                                    <input type="file" class="form-control" name="hero_image" accept="image/*">
                                    @if ($setting->hero_image)
                                        <img src="{{ asset($setting->hero_image) }}" style="max-height:120px;">
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Logo Footer</label>
                                    <input type="file" class="form-control" name="logo_footer" accept="image/*">
                                    @if ($setting->logo_footer)
                                        <img src="{{ asset($setting->logo_footer) }}" style="max-height:120px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ================= MENU ================= --}}
                        <div class="tab-pane fade" id="menu" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Menu Pendaftaran</label>
                                    <input type="file" class="form-control" name="menu_pendaftaran_img"
                                        accept="image/*">
                                    @if ($setting->menu_pendaftaran_img)
                                        <img src="{{ asset($setting->menu_pendaftaran_img) }}" style="max-height:120px;">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Menu Cek Antrian</label>
                                    <input type="file" class="form-control" name="menu_cek_antrian_img"
                                        accept="image/*">
                                    @if ($setting->menu_cek_antrian_img)
                                        <img src="{{ asset($setting->menu_cek_antrian_img) }}" style="max-height:120px;">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Menu Titip Barang</label>
                                    <input type="file" class="form-control" name="menu_titip_barang_img"
                                        accept="image/*">
                                    @if ($setting->menu_titip_barang_img)
                                        <img src="{{ asset($setting->menu_titip_barang_img) }}"
                                            style="max-height:120px;">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Menu Kritik & Saran</label>
                                    <input type="file" class="form-control" name="menu_kritik_saran_img"
                                        accept="image/*">
                                    @if ($setting->menu_kritik_saran_img)
                                        <img src="{{ asset($setting->menu_kritik_saran_img) }}"
                                            style="max-height:120px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ================= FOOTER ================= --}}
                        <div class="tab-pane fade" id="footer" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <label class="form-label fw-semibold">Copyright</label>
                                    <input type="text" class="form-control" name="footer_copyright"
                                        value="{{ old('footer_copyright', $setting->footer_copyright) }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= SOSMED ================= --}}
                        <div class="tab-pane fade" id="sosmed" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Facebook</label>
                                    <input type="text" class="form-control" name="facebook"
                                        value="{{ old('facebook', $setting->facebook) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Instagram</label>
                                    <input type="text" class="form-control" name="instagram"
                                        value="{{ old('instagram', $setting->instagram) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Twitter / X</label>
                                    <input type="text" class="form-control" name="twitter"
                                        value="{{ old('twitter', $setting->twitter) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Youtube</label>
                                    <input type="text" class="form-control" name="youtube"
                                        value="{{ old('youtube', $setting->youtube) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tiktok</label>
                                    <input type="text" class="form-control" name="tiktok"
                                        value="{{ old('tiktok', $setting->tiktok) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Whatsapp</label>
                                    <input type="text" class="form-control" name="whatsapp"
                                        value="{{ old('whatsapp', $setting->whatsapp) }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= FAVICON & MANIFEST ================= --}}
                        <div class="tab-pane fade" id="favicon" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Apple Touch Icon</label>
                                    <input type="file" class="form-control" name="apple_touch_icon" accept="image/*">
                                    @if ($setting->apple_touch_icon)
                                        <img src="{{ asset($setting->apple_touch_icon) }}" style="max-height:80px;">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Favicon 32x32</label>
                                    <input type="file" class="form-control" name="favicon_32" accept="image/*">
                                    @if ($setting->favicon_32)
                                        <img src="{{ asset($setting->favicon_32) }}" style="max-height:32px;">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Favicon 16x16</label>
                                    <input type="file" class="form-control" name="favicon_16" accept="image/*">
                                    @if ($setting->favicon_16)
                                        <img src="{{ asset($setting->favicon_16) }}" style="max-height:16px;">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Favicon ICO</label>
                                    <input type="file" class="form-control" name="favicon_ico" accept="image/*,.ico">
                                    @if ($setting->favicon_ico)
                                        <img src="{{ asset($setting->favicon_ico) }}" style="max-height:32px;">
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Manifest JSON</label>
                                    <input type="file" class="form-control" name="manifest_json" accept=".json">
                                    @if ($setting->manifest_json)
                                        <small class="text-muted d-block mt-1">{{ $setting->manifest_json }}</small>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Mask Icon</label>
                                    <input type="file" class="form-control" name="mask_icon" accept="image/*">
                                    @if ($setting->mask_icon)
                                        <img src="{{ asset($setting->mask_icon) }}" style="max-height:80px;">
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Mask Icon Color</label>
                                    <input type="text" class="form-control" name="mask_icon_color"
                                        value="{{ old('mask_icon_color', $setting->mask_icon_color) }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ================= FOOTER BUTTON ================= --}}
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light rounded-pill px-4">Reset</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="feather-save me-1"></i> Simpan Setting
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

@endsection
