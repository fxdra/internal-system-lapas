@extends('startup_view.main')
@section('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <!-- Slick JS -->
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


    <style>
        /* ================= MENU ANIMATION (SLOW & SMOOTH) ================= */

        .menu-card {
            opacity: 0;
            transform: translateY(60px);
            transition: transform .6s ease, box-shadow .6s ease;
        }

        /* Saat muncul (lebih lambat & elegan) */
        .menu-card.show {
            opacity: 1;
            transform: translateY(0);
            transition:
                opacity 1.2s ease,
                transform 1.2s cubic-bezier(.22, 1, .36, 1);
        }

        /* Hover animation lebih hidup */
        .menu-card:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.25);
        }

        /* Tambahan animasi icon saat hover */
        .menu-card:hover .menu-icon {
            transform: scale(1.12) translateY(-3px);
        }

        .menu-icon {
            transition: transform .4s cubic-bezier(.22, 1, .36, 1);
        }


        /* ================= INFORMASI ANIMATION FIX ================= */
        /* ================= TITLE GOLD HOVER ================= */

        .info-card h4 {
            transition: all .4s ease;
        }

        /* Saat card di-hover → title jadi emas */
        .info-card:hover h4 {
            background: linear-gradient(90deg, #d4af37, #ffd700, #f5d76e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .info-card {
            opacity: 0;
            transform: translateY(60px);
            transition: all 1s cubic-bezier(.22, 1, .36, 1);
            color: rgba(12, 12, 12, 0.06);
        }

        /* Muncul */
        .info-card.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Hover tetap smooth */
        .info-card:hover {
            transform: translateY(-8px);
            transition: transform .4s ease, box-shadow .4s ease;
        }


        /* ================= BERITA ANIMATION FIX ================= */

        .berita-card {
            opacity: 0;
            transform: translateY(80px);
            transition: opacity 1.2s ease, transform 1.2s cubic-bezier(.22, 1, .36, 1);
            will-change: transform, opacity;
        }

        .berita-card.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Hover */
        .berita-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.35);
        }

        /* Zoom gambar */
        .berita-card img {
            transition: transform .8s ease;
        }

        .berita-card:hover img {
            transform: scale(1.08);
        }


        /* ================== BERITA CARD HOVER ================== */

        .berita-card {
            transition: transform .25s ease, box-shadow .25s ease, border .25s ease;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }



        /* pastikan image tidak keluar card */
        .berita-card {
            overflow: hidden;
        }

        .berita-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .45);
            border-color: rgba(218, 165, 32, .45);
            /* gold border */
        }



        /* warna default */
        .berita-card .berita-date,
        .berita-card .berita-excerpt {
            transition: color .25s ease;
        }

        .berita-card .berita-title {
            transition: color .25s ease;
        }

        /* hover → gold */
        .berita-card:hover .berita-title,
        .berita-card:hover .berita-excerpt,
        .berita-card:hover .berita-date {
            color: #d4af37 !important;
            /* gold */
        }

        /* Slick dots rapih */
        .berita-slick .slick-dots {
            bottom: -35px;
        }

        .berita-slick .slick-dots li button:before {
            font-size: 10px;
            opacity: .5;
            color: dark;
        }

        .berita-slick .slick-dots li.slick-active button:before {
            opacity: 1;
            color: dark;
        }

        @media (max-width: 1397px) {
            .vavbar .collapse ul li a {
                font-size: 0.5em;
            }
        }

        @media (max-width: 579px) {
            .mobile-card {
                padding-inline: 40px;
                border-radius: 16px;
            }
        }
    </style>

    {{-- ================== HERO + SLIDER ================== --}}
    <header class="container py-3 py-lg-5">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true">
            <div class="carousel-inner">

                {{-- Slide 1 --}}
                <div class="carousel-item active">
                    <div class="hero-slide shadow-lg">
                        <div class="hero-bg"
                            style="background-image:url('{{ asset($setting->hero_image ?? 'image/lapas-banceuy-1.jpg') }}');">
                        </div>
                        <div class="hero-overlay"></div>

                        <div class="position-relative w-100">
                            <div class="p-3 p-md-4 p-lg-5">
                                <div class="row align-items-center g-4">

                                    <div class="col-12 col-lg-7 text-center text-lg-start">
                                        <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">
                                            Website Resmi Layanan Kunjungan
                                        </span>

                                        <h1 class="hero-title fw-bold mb-3">
                                            {{ $setting->judul_header_1 ?? 'LEMBAGA PEMASYARAKATAN KELAS IIA' }}
                                            <span class="text-warning">
                                                {{ $setting->judul_header_2 ?? 'BANCEUY BANDUNG' }}
                                            </span>
                                        </h1>

                                        <p class="hero-desc mb-4">
                                            Sistem layanan kunjungan, cek antrian, titip barang, serta kritik & saran
                                            untuk pelayanan yang lebih cepat, tertib, dan transparan.
                                        </p>

                                        <div
                                            class="hero-btn-wrap d-grid d-sm-flex gap-2 justify-content-center justify-content-lg-start">
                                            <a href="#menu" class="btn btn-primary rounded-pill px-4 py-2">
                                                <i class="bi bi-grid-3x3-gap me-1"></i> Lihat Menu
                                            </a>
                                            <a href="/informasi-layanan"
                                                class="btn btn-outline-light rounded-pill px-4 py-2">
                                                <i class="bi bi-info-circle me-1"></i> Informasi Layanan
                                            </a>
                                        </div>
                                    </div>

                                    {{-- kanan hanya desktop --}}
                                    <div class="col-lg-5 hero-status-col d-none d-lg-block">
                                        <div class="glass-card p-3 p-md-4">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div>
                                                    <div class="fw-bold">Status Layanan</div>
                                                    <small class="text-light">Update hari ini</small>
                                                </div>
                                                <span class="badge bg-success rounded-pill px-3 py-2">Aktif</span>
                                            </div>

                                            <div class="row g-2 g-md-3">
                                                <div class="col-6">
                                                    <div class="mini-box p-3">
                                                        <div class="fw-bold">Kunjungan</div>
                                                        <small class="text-light">Pendaftaran Online</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mini-box p-3">
                                                        <div class="fw-bold">Antrian</div>
                                                        <small class="text-light">Cek Barcode</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mini-box p-3">
                                                        <div class="fw-bold">Titip Barang</div>
                                                        <small class="text-light">Lebih Tertib</small>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mini-box p-3">
                                                        <div class="fw-bold">Kritik & Saran</div>
                                                        <small class="text-light">Respon Cepat</small>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slide 2 --}}
                <div class="carousel-item">
                    <div class="hero-slide shadow-lg">
                        <div class="hero-bg"
                            style="background-image:url('{{ asset($setting->hero_image_2 ?? 'image/penjara.jpg') }}');">
                        </div>
                        <div class="hero-overlay"></div>

                        <div class="position-relative w-100">
                            <div class="p-3 p-md-4 p-lg-5">
                                <div class="row align-items-center g-4">
                                    <div class="col-12 col-lg-8 text-center text-lg-start">
                                        <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">
                                            Transparan • Cepat • Tertib
                                        </span>

                                        <h2 class="hero-title fw-bold mb-3">
                                            Pelayanan Kunjungan Lebih Modern & Terintegrasi
                                        </h2>

                                        <p class="hero-desc mb-4">
                                            Pastikan data kunjungan lengkap, datang sesuai jadwal, dan cek antrian lebih
                                            mudah
                                            dengan sistem barcode.
                                        </p>

                                        <div class="hero-btn-wrap d-grid d-sm-inline-block">
                                            <a href="/tatap-muka" class="btn btn-primary rounded-pill px-4 py-2">
                                                <i class="bi bi-arrow-right-circle me-1"></i> Mulai Sekarang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <div class="marquee-wrap mt-3 mt-lg-4 p-3">
            <div class="marquee fw-semibold text-white">
                {{ $setting->teks_marquee ?? 'Selamat datang di Website Kunjungan Lapas — Mohon isi data dengan benar dan datang sesuai jadwal kunjungan.' }}
            </div>
        </div>
    </header>

    {{-- ================== MENU SECTION ================== --}}
    <section class="container pb-5 " id="menu">

        <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Menu Layanan</h3>
                <div class="fw-semibold mb-1 text-dark">Pilih layanan yang tersedia untuk pengunjung.</div>
            </div>
            <div class="dark small fw-semibold mb-1 text-dark">
                <i class="bi bi-lightning-charge"></i> Cepat • Aman • Transparan
            </div>
        </div>

        <div class="row g-3 row-menu-item">

            {{-- ================= MENU ITEM ================= --}}
            <div class="col-6 col-sm-4 col-md-6 col-lg-3">
                <a href="https://lapasbanceuy.web.id/" class="text-decoration-none text-white">

                    {{-- MOBILE VERSION --}}
                    <div class="d-lg-none text-center px-3">
                        <div class="menu-card mobile-card p-3">
                            <i class="bi bi-person-check fs-1"></i>
                        </div>
                        <div class="mt-2 small fw-semibold text-dark">
                            Kunjungan
                        </div>
                    </div>

                    {{-- DESKTOP VERSION --}}
                    <div class="menu-card p-4 d-none d-lg-block">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="menu-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white">Kunjungan</div>
                                <small class="text-light">Daftar Online</small>
                            </div>
                        </div>
                        <div class="dark small">
                            Daftarkan kunjungan lebih cepat dan tertib.
                        </div>
                    </div>

                </a>
            </div>

            {{-- ================= MENU 2 ================= --}}
            <div class="col-6 col-sm-4 col-md-6 col-lg-3">
                <a href="https://lapasbanceuy.web.id/" class="text-decoration-none text-white">

                    <div class="d-lg-none text-center px-3">
                        <div class="menu-card mobile-card p-3">
                            <i class="bi bi-upc-scan fs-1"></i>
                        </div>
                        <div class="mt-2 small fw-semibold text-dark">
                            Antrian
                        </div>
                    </div>

                    <div class="menu-card p-4 d-none d-lg-block">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="menu-icon">
                                <i class="bi bi-upc-scan"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Cek Antrian</div>
                                <small class="dark">Barcode</small>
                            </div>
                        </div>
                        <div class="dark small">
                            Scan barcode untuk cek status & nomor antrian.
                        </div>
                    </div>

                </a>
            </div>

            {{-- ================= MENU 3 ================= --}}
            <div class="col-6 col-sm-4 col-md-6 col-lg-3">
                <a href="https://lapasbanceuy.web.id/" class="text-decoration-none text-white">

                    <div class="d-lg-none text-center px-3">
                        <div class="menu-card mobile-card p-3">
                            <i class="bi bi-box-seam fs-1"></i>
                        </div>
                        <div class="mt-2 small fw-semibold text-dark">
                            Titip Barang
                        </div>
                    </div>

                    <div class="menu-card p-4 d-none d-lg-block">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="menu-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Titip Barang</div>
                                <small class="dark">Lebih Tertib</small>
                            </div>
                        </div>
                        <div class="dark small">
                            Catat barang titipan agar aman dan rapi.
                        </div>
                    </div>

                </a>
            </div>

            {{-- ================= MENU 4 ================= --}}
            <div class="col-6 col-sm-4 col-md-6 col-lg-3">
                <a href="https://lapasbanceuy.web.id/" class="text-decoration-none text-white">

                    <div class="d-lg-none text-center px-3">
                        <div class="menu-card mobile-card p-3">
                            <i class="bi bi-chat-dots fs-1"></i>
                        </div>
                        <div class="mt-2 small fw-semibold text-dark">
                            Laporan
                        </div>
                    </div>

                    <div class="menu-card p-4 d-none d-lg-block">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="menu-icon">
                                <i class="bi bi-chat-dots"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Kritik & Saran</div>
                                <small class="dark">Respon Cepat</small>
                            </div>
                        </div>
                        <div class="dark small">
                            Sampaikan masukan untuk meningkatkan pelayanan.
                        </div>
                    </div>

                </a>
            </div>

        </div>
    </section>

    {{-- ================== INFORMASI ================== --}}
    <section class="container pb-5" id="informasi">
        <div class="row g-3 g-lg-4 align-items-stretch">

            <div class="col-lg-6">
                <div class="info-card p-4 h-100">
                    <h4 class="fw-bold mb-2 text-white">Informasi Layanan</h4>
                    <p class="mb-4 text-white">
                        Pastikan Anda mengikuti aturan dan jadwal layanan agar proses kunjungan berjalan lancar.
                    </p>

                    <div class="d-flex gap-3 mb-3">
                        <div class="menu-icon">
                            <i class="bi bi-clock-history text-white"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-white">Jadwal Kunjungan</div>
                            <div class="small text-white">
                                {{ $setting->jadwal_kunjungan ?? 'Senin - Kamis (08:00 - 15:00 WIB)' }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-3">
                        <div class="menu-icon">
                            <i class="bi bi-shield-check text-white"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-white">Ketertiban & Keamanan</div>
                            <div class="small text-white">
                                {{ $setting->aturan_singkat ?? 'Bawa identitas, ikuti pemeriksaan, dan patuhi aturan lapas.' }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="menu-icon">
                            <i class="bi bi-telephone text-white"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-white">Kontak Layanan</div>
                            <div class="small text-white">
                                {{ $setting->kontak ?? 'Hubungi petugas jika ada kendala.' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-6">
                <div class="info-card p-4 h-100">
                    <h4 class="fw-bold mb-2 text-white">Tips Cepat</h4>
                    <p class="mb-4 text-white">
                        Biar gak bolak-balik, ikuti panduan ini ya.
                    </p>

                    <ul class="mb-0 text-white">
                        <li class="mb-2">Isi data sesuai identitas (KTP/SIM).</li>
                        <li class="mb-2">Datang sesuai jadwal kunjungan yang dipilih.</li>
                        <li class="mb-2">Siapkan barcode untuk pengecekan antrian.</li>
                        <li class="mb-0">Patuhi aturan barang yang diperbolehkan.</li>
                    </ul>

                    <div class="mt-4 d-grid d-sm-flex gap-2">
                        <a href="mailto:humas.lapasbanceuy@gmail.com" target="_blank"
                            class="btn btn-primary rounded-pill px-4 fw-semibold text-white">
                            <i class="bi bi-envelope me-1 fw-semibold text-white"></i> Kontak Mail
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ================== BERITA ================== --}}
    <section class="container my-5" id="berita">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0">Berita Terbaru</h4>
                <small class="fw-semibold text-dark">Update kegiatan & informasi terbaru</small>
            </div>
            <a href="/berita" class="btn btn-sm btn-dark rounded-pill px-3">
                Lihat Semua
            </a>
        </div>

        {{-- ================== GRID (PC) ================== --}}
        <div class="row g-3 d-none d-lg-flex">
            @forelse($berita as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card berita-card border-0 rounded-4 shadow-lg h-100 overflow-hidden"
                        style="background: rgba(255,255,255,.06); backdrop-filter: blur(12px);">

                        {{-- Thumbnail --}}
                        @if ($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" class="w-100"
                                style="height: 280px; object-fit: cover;" alt="{{ $item->judul }}">
                        @else
                            <div class="w-100 d-flex align-items-center justify-content-center"
                                style="height: 280px; background: rgba(255,255,255,.08);">
                                <span class="text-dark-50">Tidak ada thumbnail</span>
                            </div>
                        @endif

                        <div class="card-body p-3">
                            {{-- Tanggal --}}
                            <div class="text-dark-50 small mb-2 berita-date">
                                <i class="feather-calendar me-1"></i>
                                {{ $item->published_at ? $item->published_at->format('d M Y H:i') : $item->created_at->format('d M Y H:i') }}
                            </div>

                            {{-- Judul --}}
                            <h6 class="fw-bold text-dark mb-2 berita-title" style="min-height: 44px;">
                                {{ $item->judul }}
                            </h6>

                            {{-- Cuplikan isi --}}
                            <p class="text-dark-50 mb-3 " style="min-height: 60px;">
                                {!! \Illuminate\Support\Str::limit(strip_tags($item->isi), 110) !!}
                            </p>

                            {{-- Button --}}
                            <a href="{{ url('/berita/' . $item->slug) }}"
                                class="btn btn-sm button-custom rounded-pill px-3">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-dark rounded-4 shadow-sm">
                        Belum ada berita.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ================== SLIDER (MOBILE/TABLET) ================== --}}
        <div class="d-block d-lg-none">
            <div class="berita-slick">
                @forelse($berita as $item)
                    <div class="px-2">
                        <div class="card border-0 rounded-4 shadow-lg h-100 overflow-hidden"
                            style="   background: rgb(105, 119, 126); backdrop-filter: blur(12px);">

                            <div class="card-body p-3">
                                {{-- Tanggal --}}
                                <div class="text-dark-50 small mb-2">
                                    <i class="feather-calendar me-1"></i>
                                    {{ $item->published_at ? $item->published_at->format('d M Y H:i') : $item->created_at->format('d M Y H:i') }}
                                </div>

                                {{-- Judul --}}
                                <h6 class="fw-bold text-dark mb-2">
                                    {{ $item->judul }}
                                </h6>

                                {{-- Cuplikan isi --}}
                                <p class="text-dark-50 mb-3">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($item->isi), 110) !!}
                                </p>

                                {{-- Button --}}
                                <a href="{{ url('/berita/' . $item->slug) }}"
                                    class="btn btn-sm button-custom rounded-pill px-3">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-dark rounded-4 shadow-sm">
                        Belum ada berita.
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!--Section map lokasi-->
    {{-- MAPS EMBED --}}
    <section class="container mt-4">
        <div class="row g-3 align-items-stretch">

            <div class="col-12">
                <div class="p-3 rounded-4 shadow-sm"
                    style="   background: rgb(105, 119, 126); border: 1px solid rgba(12, 12, 12, 0.06);">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div>
                            <div class="fw-bold fs-5 text-white">
                                Lokasi Kami
                            </div>
                            <div class="fw-semibold text-white">
                                Jalan Soekarno Hatta No. 187A, Bandung, Jawa Barat
                            </div>
                        </div>

                        <a href="https://maps.google.com/?q=-6.9496233,107.600965" target="_blank"
                            class="btn btn-outline-white btn-sm rounded-pill px-3">
                            <i data-feather="map-pin" class="me-1"></i> Buka di Google Maps
                        </a>
                    </div>

                    <div class="ratio ratio-21x9 rounded-4 overflow-hidden">
                        <iframe src="https://maps.google.com/?q=-6.9496233,107.600965&output=embed" style="border:0;"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script>
        $(document).ready(function() {
            if ($('.berita-slick').length) {
                $('.berita-slick').slick({
                    dots: true,
                    arrows: false,
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 3500,
                    speed: 500,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerMode: true,
                    centerPadding: '30px',
                    responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                centerPadding: '20px'
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1,
                                centerPadding: '12px'
                            }
                        }
                    ]
                });
            }
        });
    </script>

    {{-- Animasi Card --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const selectors = [".menu-card", ".info-card", ".berita-card"];

            // Gabungkan semua card jadi satu array
            const cards = document.querySelectorAll(selectors.join(","));

            // Fallback jika browser tidak support IntersectionObserver
            if (!("IntersectionObserver" in window)) {
                cards.forEach(card => card.classList.add("show"));
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add("show");
                        }, index * 250);

                        observer.unobserve(entry.target); // biar tidak trigger ulang
                    }
                });
            }, {
                threshold: 0.2
            });

            cards.forEach(card => observer.observe(card));

        });
    </script>
@endsection
