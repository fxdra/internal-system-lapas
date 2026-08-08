<style>
    
    .navbar-glass .header-title{
            color: #384959;
            transition: color 0.3s ease;
        }
        
        .navbar-glass.scrolled .header-title{
            color: #fff !important;
        }
            
        @media (max-width: 1399px) {
    
        /* ===== LOGO ===== */
        .main-navbar .navbar-brand img {
            width: 45px;
            height: 45px;
        }
    
        .main-navbar .header-title {
            font-size: 14px;
        }
    
        .main-navbar .navbar-brand .text-warning {
            font-size: 12px;
        }
    
        /* ===== NAV LINK ===== */
        .main-navbar .nav-link {
            font-size: 13px;
            padding: 6px 8px;
        }
    
        /* icon sedikit diperkecil */
        .main-navbar .nav-link i,
        .main-navbar .dropdown-item i {
            font-size: 14px;
        }
    
        /* dropdown item */
        .main-navbar .dropdown-menu .dropdown-item {
            font-size: 13px;
            padding: 6px 12px;
        }
    
        /* jarak antar menu dikurangi */
        .main-navbar .navbar-nav {
            gap: 0.3rem !important;
        }
    }
    
    @media (max-width: 1199px) {

    /* ===== BRAND / HEADER ===== */
    .main-navbar .navbar-brand img {
        width: 32px;
        height: 32px;
    }

    .main-navbar .header-title {
        font-size: 11.5px;
        line-height: 1.1;
    }

    .main-navbar .navbar-brand .text-warning {
        font-size: 12px;
    }

    /* kalau mau lebih clean, boleh sembunyikan teks atas */
    /* .main-navbar .navbar-brand .text-warning {
        display: none;
    } */

    /* ===== NAV LINK ===== */
    .main-navbar .nav-link {
        font-size: 10px;
        padding: 4px 5px;
    }

    /* icon */
    .main-navbar .nav-link i,
    .main-navbar .dropdown-item i {
        font-size: 12px;
    }

    /* dropdown */
    .main-navbar .dropdown-menu .dropdown-item {
        font-size: 11.5px;
        padding: 4px 8px;
    }

    /* spacing lebih rapat */
    .main-navbar .navbar-nav {
        gap: 0.15rem !important;
    }

    /* container lebih tipis */
    .main-navbar .container {
        padding-top: 0.25rem !important;
        padding-bottom: 0.25rem !important;
    }
}
</style>

{{-- ================== TOP BAR ================== --}}
<div class="top-bar small fixed-top d-none d-md-block">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center p-2">

            {{-- KIRI : JAM & TANGGAL --}}
            <div class="jam notranslate" translate="no">
                <span id="currentDate"></span> |
                <span id="currentTime"></span>
            </div>

            <div class="d-flex align-items-center gap-3">

                {{-- ICON DOWNLOAD APP --}}
                <a href="#" class="text-warning fs-5 text-decoration-none" title="Download Aplikasi">
                    <i class="bi bi-download"></i>
                </a>

                {{-- DROPDOWN BAHASA --}}
                <div class="dropdown">

                    <a href="javascript:void(0)"
                        class="text-warning fs-5 text-decoration-none align-middle dropdown-toggle"
                        data-bs-toggle="dropdown" title="Ganti Bahasa">

                        <i class="bi bi-globe2"></i>

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <button class="dropdown-item" type="button" onclick="setLanguage('id')">
                                🇮🇩 Indonesia
                            </button>
                        </li>
                    
                        <li>
                            <button class="dropdown-item" type="button" onclick="setLanguage('en')">
                                🇬🇧 English
                            </button>
                        </li>
                    
                        <li>
                            <button class="dropdown-item" type="button" onclick="setLanguage('zh-CN')">
                                🇨🇳 中文
                            </button>
                        </li>
                    
                    </ul>

                </div>

            </div>
        </div>
    </div>
</div>


{{-- ================== NAVBAR ================== --}}
<nav class="navbar navbar-expand-lg navbar-dark navbar-glass fixed-top main-navbar">
    <div class="container py-2 d-flex align-items-center mt-5">

        {{-- BRAND --}}
        <a class="navbar-brand d-flex align-items-start gap-2 flex-wrap me-3" href="/">
            <img src="{{ asset($setting->logo_utama ?? 'image/logo.png') }}" alt="Logo" width="60"
                height="60" class="flex-shrink-0">

            <div class="d-none d-md-block lh-sm text-wrap">
                <div class="text-warning">
                    {{ $setting->judul_header_1 }}
                </div>
                <div class="fw-bold header-title">
                    {{ $setting->judul_header_2 ?? 'BANCEUY BANDUNG' }}
                </div>
            </div>
        </a>

        {{-- TOGGLER --}}
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse fw-bold" id="navMain">
    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

        <li class="nav-item">
            <a class="nav-link" href="/banceuy-profile">
                <i class="bi bi-person-circle me-1"></i> PROFILE
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-grid me-1"></i> LAYANAN
            </a>
            <ul class="dropdown-menu dropdown-menu-dark shadow">
                <li>
                    <a class="dropdown-item" href="/tatap-muka">
                        <i class="bi bi-calendar-check me-1"></i> KUNJUNGAN
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="/perpustakaan-banceuy">
                        <i class="bi bi-book me-1"></i> PERPUSTAKAAN
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-pencil-square me-1"></i> PENDAFTARAN PROGRAM
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/berita">
                <i class="bi bi-newspaper me-1"></i> BERITA
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-collection me-1"></i> PUBLIKASI
            </a>
            <ul class="dropdown-menu dropdown-menu-dark shadow">
                <li>
                    <a class="dropdown-item" href="/banceuy-kegiatan">
                        <i class="bi bi-calendar-event me-1"></i> KEGIATAN
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="/bulettin">
                        <i class="bi bi-journal-text me-1"></i> BULETTIN
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/banceuy-shop">
                <i class="bi bi-shop me-1"></i> TOKO
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/informasi-layanan">
                <i class="bi bi-info-circle me-1"></i> INFORMASI
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/banceuy-tentang-kami">
                <i class="bi bi-people me-1"></i> TENTANG KAMI
            </a>
        </li>

    </ul>
</div>
    </div>
</nav>


<script>
    function updateDateTime() {
        const now = new Date();

        const optionsDate = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        const date = now.toLocaleDateString('id-ID', optionsDate);
        const time = now.toLocaleTimeString('id-ID');

        document.getElementById('currentDate').innerHTML = date;
        document.getElementById('currentTime').innerHTML = time;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>
