<style>
/* Navbar Mobile Glass */
.navbar-mobile-glass {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1040;

    background: transparent;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);

    border-bottom: 1px solid rgba(255, 255, 255, .08);
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

/* Teks & ikon sebelum scroll */
.navbar-mobile-glass,
.navbar-mobile-glass .header-text-2,
.navbar-mobile-glass  {
    color: #384959 !important; /* gelap */
    fill: #384959; /* ikon SVG */
    transition: color 0.3s, fill 0.3s;
}



.navbar-mobile-glass .header-text-1{
    color: gold;
}

/* Navbar mobile saat scroll */
.navbar-mobile-glass.scrolled {
    background: #384959;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.45);
}

/* Teks & ikon saat scroll */
.navbar-mobile-glass.scrolled,
.navbar-mobile-glass.scrolled .header-text-2 {
    color: #fff !important;  /* putih saat scroll */
    fill: #fff;
}

.navbar-mobile-glass.scrolled .header-text-1{
    color: gold;
}
</style>

{{-- ================== HEADER MOBILE (HP) ================== --}}
<header data-bs-theme="dark" class="d-lg-none">
    <nav class="navbar navbar-dark navbar-mobile-glass shadow-sm">
        <div class="container">
            <div class="row align-items-center w-100">
                <div class="col-10 d-flex align-items-center">
                    <img src="{{ asset($setting->logo_utama ?? 'image/logo.png') }}" alt="Logo" height="50"
                        width="50" style="object-fit:contain;" class="me-2">
                    <div class="text-white">
                        <div class="fw-bold header-text-1 text-warning" style="line-height: 1.25; font-size: 0.9rem;">
                            LEMBAGA PEMASYARAKATAN
                        </div>
                        <div class="fw-bold header-text-2 " style="line-height: 1.25; font-size: 0.7rem;">
                            KELAS IIA BANCEUY BANDUNG
                        </div>
                    </div>
                </div>

                <div class="col-2 text-end">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="offcanvas offcanvas-end offcanvas-glass text-white" tabindex="-1" id="mobileMenu"
    aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="mobileMenuLabel">
            <i data-feather="menu"></i> MENU
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <ul class="nav flex-column gap-2 fs-5">

            {{-- BERANDA --}}
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center gap-2" href="/">
                    <i data-feather="home"></i> BERANDA
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center gap-2" href="/banceuy-profile">
                    <i data-feather="layers"></i> PROFILE
                </a>
            </li>

            {{-- LAYANAN --}}
            <li class="nav-item">
                <button class="nav-link text-white w-100 text-start d-flex justify-content-between align-items-center"
                    type="button" data-bs-toggle="collapse" data-bs-target="#mobileLayanan" aria-expanded="false"
                    aria-controls="mobileLayanan">

                    <span class="d-flex align-items-center gap-2">
                        <i data-feather="grid"></i> LAYANAN
                    </span>

                    <i data-feather="chevron-down"></i>
                </button>

                <div class="collapse ps-2 mt-1" id="mobileLayanan">
                    <ul class="nav flex-column gap-1">

                        {{-- KUNJUNGAN (child lagi) --}}
                        <li class="nav-item">
                            <button
                                class="nav-link text-white-50 w-100 text-start d-flex justify-content-between align-items-center"
                                type="button" data-bs-toggle="collapse" data-bs-target="#mobileKunjungan"
                                aria-expanded="false" aria-controls="mobileKunjungan">

                                <span class="d-flex align-items-center gap-2">
                                    <i data-feather="users"></i> KUNJUNGAN
                                </span>

                                <i data-feather="chevron-down"></i>
                            </button>

                            <div class="collapse ps-3 mt-1" id="mobileKunjungan">
                                <ul class="nav flex-column gap-1 fs-6">

                                    <li class="nav-item">
                                        <a class="nav-link text-white d-flex align-items-center gap-2"
                                            href="/tatap-muka">
                                            <i data-feather="user-plus"></i> Pendaftaran Tatap Muka
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link text-white d-flex align-items-center gap-2"
                                            href="/cek-antrian">
                                            <i data-feather="hash"></i> Cek Nomor Antrian
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link text-white d-flex align-items-center gap-2"
                                            href="/titip-barang">
                                            <i data-feather="package"></i> Titip Barang
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link text-white d-flex align-items-center gap-2"
                                            href="/kritik-saran">
                                            <i data-feather="edit-3"></i> Kritik & Saran
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>


                        {{-- PERPUSTAKAAN ONLINE --}}
                        <li class="nav-item">
                            <a class="nav-link text-white-50 d-flex align-items-center gap-2"
                                href="/perpustakaan-banceuy">
                                <i data-feather="book-open"></i> PERPUSTAKAAN
                            </a>
                        </li>

                        {{-- PENDAFTARAN PROGRAM --}}
                        <li class="nav-item">
                            <a class="nav-link text-white-50 d-flex align-items-center gap-2" href="#">
                                <i data-feather="clipboard"></i> PENDAFTARAN PROGRAM
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            {{-- KEGIATAN --}}
            <li class="nav-item">
                <button class="nav-link text-white w-100 text-start d-flex justify-content-between align-items-center"
                    type="button" data-bs-toggle="collapse" data-bs-target="#mobileKegiatan" aria-expanded="false"
                    aria-controls="mobileKegiatan">

                    <span class="d-flex align-items-center gap-2">
                        <i data-feather="activity"></i> KEGIATAN
                    </span>

                    <i data-feather="chevron-down"></i>
                </button>

                <div class="collapse ps-2 mt-1" id="mobileKegiatan">
                    <ul class="nav flex-column gap-1">

                        <li class="nav-item">
                            <a class="nav-link text-white-50 d-flex align-items-center gap-2"
                                href="/banceuy-kegiatan">
                                <i data-feather="calendar"></i> Kegiatan
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white-50 d-flex align-items-center gap-2" href="/bulettin">
                                <i data-feather="book-open"></i> Bulettin
                            </a>
                        </li>

                    </ul>
                </div>
            </li>


            {{-- REFORMASI BIROKRASI --}}
            <!--<li class="nav-item">-->
            <!--    <button-->
            <!--        class="nav-link text-white w-100 text-start d-flex justify-content-between align-items-center"-->
            <!--        type="button" data-bs-toggle="collapse" data-bs-target="#mobileReformasi"-->
            <!--        aria-expanded="false" aria-controls="mobileReformasi">-->

            <!--        <span class="d-flex align-items-center gap-2">-->
            <!--            <i data-feather="shield"></i> REFORMASI BIROKRASI-->
            <!--        </span>-->

            <!--        <i data-feather="chevron-down"></i>-->
            <!--    </button>-->

            <!--    <div class="collapse ps-2 mt-1" id="mobileReformasi">-->
            <!--        <ul class="nav flex-column gap-1 fs-6">-->
            <!--            <li class="nav-item">-->
            <!--                <a class="nav-link text-white-50 d-flex align-items-center gap-2"-->
            <!--                    href="/reformasi-birokrasi/latar-belakang-zi">-->
            <!--                    <i data-feather="book"></i> Latar Belakang ZI-->
            <!--                </a>-->
            <!--            </li>-->
            <!--            <li class="nav-item">-->
            <!--                <a class="nav-link text-white-50 d-flex align-items-center gap-2"-->
            <!--                    href="/reformasi-birokrasi/area-perubahan-zi">-->
            <!--                    <i data-feather="layers"></i> Area Perubahan ZI-->
            <!--                </a>-->
            <!--            </li>-->
            <!--            <li class="nav-item">-->
            <!--                <a class="nav-link text-white-50 d-flex align-items-center gap-2"-->
            <!--                    href="/reformasi-birokrasi/inovasi">-->
            <!--                    <i data-feather="zap"></i> Inovasi-->
            <!--                </a>-->
            <!--            </li>-->
            <!--            <li class="nav-item">-->
            <!--                <a class="nav-link text-white-50 d-flex align-items-center gap-2"-->
            <!--                    href="/reformasi-birokrasi/data-dukung">-->
            <!--                    <i data-feather="database"></i> Data Dukung-->
            <!--                </a>-->
            <!--            </li>-->
            <!--            <li class="nav-item">-->
            <!--                <a class="nav-link text-white-50 d-flex align-items-center gap-2"-->
            <!--                    href="/reformasi-birokrasi/manfaat-pembangunan-zi">-->
            <!--                    <i data-feather="check-circle"></i> Manfaat Pembangunan ZI-->
            <!--                </a>-->
            <!--            </li>-->
            <!--        </ul>-->
            <!--    </div>-->
            <!--</li>-->

            {{-- BERITA --}}
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center gap-2" href="/berita">
                    <i data-feather="file-text"></i> BERITA
                </a>
            </li>
            
            {{-- TOKO / SHOP --}}
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center gap-2" href="/banceuy-shop">
                    <i data-feather="shopping-bag"></i> TOKO
                </a>
            </li>

            {{-- INFORMASI --}}
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center gap-2" href="/informasi-layanan">
                    <i data-feather="info"></i> INFORMASI
                </a>
            </li>

            {{-- TENTANG KAMI --}}
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center gap-2" href="/banceuy-tentang-kami">
                    <i data-feather="help-circle"></i> TENTANG KAMI
                </a>
            </li>

            {{-- DOWNLOAD APLIKASI --}}
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center gap-2" href="/download-aplikasi">
                    <i data-feather="download"></i> DOWNLOAD APLIKASI
                </a>
            </li>

            {{-- BAHASA --}}
            <li class="nav-item">
                <button class="nav-link text-white w-100 text-start d-flex justify-content-between align-items-center"
                    type="button" data-bs-toggle="collapse" data-bs-target="#mobileBahasa" aria-expanded="false"
                    aria-controls="mobileBahasa">

                    <span class="d-flex align-items-center gap-2">
                        <i data-feather="globe"></i> BAHASA
                    </span>

                    <i data-feather="chevron-down"></i>
                </button>

                <div class="collapse ps-2 mt-1" id="mobileBahasa">
                    <ul class="nav flex-column gap-1">

                        <li class="nav-item">
                            <a class="nav-link text-white-50 d-flex align-items-center gap-2" href="#"
                                onclick="setLanguage('id')">
                                🇮🇩 Indonesia
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white-50 d-flex align-items-center gap-2" href="#"
                                onclick="setLanguage('en')">
                                🇬🇧 English
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

        </ul>

    </div>
</div>
