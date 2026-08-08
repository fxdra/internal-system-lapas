<style>
    /* Quick Menu Links - default (desktop) */
    .d-grid .hover-link {
        display: flex;
        /* icon & teks sejajar */
        align-items: center;
        /* vertical center */
        gap: 0.5rem;
        /* jarak icon & teks */
        padding: 8px 12px;
        /* klik/tap area nyaman */
        color: #f8f9fa;
        /* teks default */
        transition: color 0.2s;
        text-decoration: none;
    }

    /* Hover effect */
    .d-grid .hover-link:hover {
        color: #ffc107;
        /* highlight saat hover */
        text-decoration: none;
    }

    /* Mobile: icon + teks sejajar & center, tapi icon tetap aligned */
    @media (max-width: 991.98px) {
        .d-grid .hover-link {
            justify-content: center;
            /* center horizontal */
            text-align: center;
            /* teks center */
        }

        /* Beri lebar tetap pada icon agar semua icon vertikal sejajar */
        .d-grid .hover-link i {
            flex: 0 0 24px;
            /* lebar tetap untuk icon */
            display: inline-flex;
            justify-content: center;
            /* icon di tengah slot */
        }
    }

    /* Optional: responsive spacing & alignment */
    @media (max-width: 576px) {
        .d-grid .hover-link {
            padding: 10px 8px;
            /* lebih nyaman di layar kecil */
            font-size: 0.9rem;
            /* sedikit mengecil */
        }
    }
</style>
<footer id="kontak" class="footer-glass pt-4 pb-3 mt-5">
    <div class="container">
        <div class="row g-4 align-items-start">

            {{-- LEFT : BRAND --}}
            <div class="col-12 col-lg-5">
                <div class="d-flex align-items-center gap-3 justify-content-center justify-content-lg-start">
                    <img src="{{ asset($setting->logo_utama ?? ($setting->logo_utama ?? 'image/logo.png')) }}"
                        alt="Logo Footer" style="height:80px; width:80px; object-fit:contain;" id="image-footer-skuy"
                        class="d-none d-lg-block">
                    <div class="text-center text-lg-start">
                        <div class="fw-bold fs-5">
                            {{ $setting->judul_header_1 . ' ' . $setting->judul_header_2 ?? 'BANCEUY BANDUNG' }}
                        </div>
                        <div class="text-muted small">
                            {{ $setting->footer_tagline ?? 'Website resmi pelayanan publik dan kunjungan' }}
                        </div>
                    </div>
                </div>

                {{-- Alamat / Deskripsi --}}
                <div class="mt-3 text-center text-lg-start">
                    <div class="text-muted small lh-lg">
                        {{ $setting->alamat ?? 'Jl. Soekarno Hatta No.187A, Bandung, Jawa Barat' }}
                    </div>
                </div>
            </div>

            {{-- MID : QUICK MENU --}}
            <div class="col-12 col-lg-3">
                <div class="text-center text-lg-start">
                    <div class="fw-bold mb-2">Menu Cepat</div>

                    <div class="d-grid gap-2">
                        <a href="/tatap-muka" class="text-decoration-none text-white-50 hover-link">
                            <i data-feather="users" class="me-2"></i> Pendaftaran Tatap Muka
                        </a>
                        <a href="/cek-antrian" class="text-decoration-none text-white-50 hover-link">
                            <i data-feather="hash" class="me-2"></i> Cek Nomor Antrian
                        </a>
                        <a href="/titip-barang" class="text-decoration-none text-white-50 hover-link">
                            <i data-feather="package" class="me-2"></i> Titip Barang
                        </a>
                        <a href="/kritik-saran" class="text-decoration-none text-white-50 hover-link">
                            <i data-feather="message-square" class="me-2"></i> Kritik & Saran
                        </a>
                    </div>
                </div>
            </div>

            {{-- RIGHT : SOSMED --}}
            <div class="col-12 col-lg-4">
                <div class="text-center text-lg-start">
                    <div class="fw-bold mb-2">Sosial Media</div>

                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">

                        {{-- WhatsApp --}}
                        @if (!empty($setting->wa))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3"
                                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting->wa) }}" target="_blank">
                                <i data-feather="phone" class="me-1"></i> WhatsApp
                            </a>
                        @endif

                        {{-- Instagram --}}
                        @if (!empty($setting->instagram))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3" href="{{ $setting->instagram }}"
                                target="_blank">
                                <i data-feather="instagram" class="me-1"></i> Instagram
                            </a>
                        @endif

                        {{-- Facebook --}}
                        @if (!empty($setting->facebook))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3" href="{{ $setting->facebook }}"
                                target="_blank">
                                <i data-feather="facebook" class="me-1"></i> Facebook
                            </a>
                        @endif

                        {{-- YouTube --}}
                        @if (!empty($setting->youtube))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3" href="{{ $setting->youtube }}"
                                target="_blank">
                                <i data-feather="youtube" class="me-1"></i> YouTube
                            </a>
                        @endif

                        {{-- TikTok (Feather ga ada icon tiktok, pakai icon "video") --}}
                        @if (!empty($setting->tiktok))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3" href="{{ $setting->tiktok }}"
                                target="_blank">
                                <i data-feather="video" class="me-1"></i> TikTok
                            </a>
                        @endif

                        {{-- Email --}}
                        @if (!empty($setting->email))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3"
                                href="mailto:{{ $setting->email }}">
                                <i data-feather="mail" class="me-1"></i> Email
                            </a>
                        @endif

                        {{-- Website --}}
                        @if (!empty($setting->website))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3" href="{{ $setting->website }}"
                                target="_blank">
                                <i data-feather="globe" class="me-1"></i> Website
                            </a>
                        @endif

                        {{-- Google Maps --}}
                        @if (!empty($setting->maps))
                            <a class="btn btn-outline-light btn-sm rounded-pill px-3" href="{{ $setting->maps }}"
                                target="_blank">
                                <i data-feather="map-pin" class="me-1"></i> Lokasi
                            </a>
                        @endif

                    </div>

                    {{-- Note kecil --}}
                    <div class="text-muted small mt-3">
                        {{ $setting->footer_note ?? 'Ikuti sosial media kami untuk informasi terbaru layanan kunjungan.' }}
                    </div>
                </div>
            </div>

        </div>

        {{-- GARIS --}}
        <hr class="border-secondary my-4">

        {{-- BOTTOM --}}
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-6 text-center text-md-start">
                <div class="text-muted small">
                    {{ $setting->footer_copyright ?? '© ' . date('Y') . ' Lapas Kelas IIA Banceuy Bandung' }}
                </div>
            </div>

            <div class="col-12 col-md-6 text-center text-md-end">
                <div class="text-muted small">
                    {{ $setting->footer_powered ?? 'Powered by ' . $setting->meta_author }}
                </div>
            </div>
        </div>

    </div>
</footer>
