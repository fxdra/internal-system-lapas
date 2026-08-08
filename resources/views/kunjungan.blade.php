<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Astro v5.13.2">
    <title>WEBSITE KUNJUNGAN LEMBAGA PEMASYARAKATAN KELAS II A BANCEUY</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
    <script src="/docs/5.3/assets/js/color-modes.js"></script>
    <link href="/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB">
    <link rel="apple-touch-icon" href="/docs/5.3/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/5.3/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/5.3/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
    <link rel="icon" href="/docs/5.3/assets/img/favicons/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <meta name="theme-color" content="#712cf9">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
                /* reset margin halaman */
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
                background: #fff;
            }

            /* Container print full-width dan mulai dari atas */
            .container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 5px;
                /* tipis agar tidak nempel tepi */
            }

            /* Invoice full-width, minimal padding untuk naik ke atas */
            .invoice-box {
                width: 100%;
                padding: 5px 5px;
                /* minimal padding agar posisi ke atas */
                font-size: 13px;
                line-height: 1.5;
                text-align: left;
                margin-top: 0;
                /* hapus margin */
            }

            h4 {
                font-size: 20px;
                margin: 2px 0 6px 0;
                /* lebih rapat ke atas */
            }

            h6 {
                font-size: 17px;
                margin: 1px 0 4px 0;
                /* lebih rapat */
            }

            p,
            td,
            th,
            small {
                font-size: 13px;
            }

            table {
                width: 100%;
                page-break-inside: avoid;
                border-collapse: collapse;
                table-layout: fixed;
                /* kolom proporsional */
            }

            td,
            th {
                padding: 3px 5px;
                /* minimal padding */
                vertical-align: top;
                word-wrap: break-word;
            }

            /* Label, tanda, isi proporsional */
            table.table-borderless td:first-child {
                width: 35%;
                font-weight: 600;
            }

            table.table-borderless td:nth-child(2) {
                width: 5%;
            }

            table.table-borderless td:last-child {
                width: 60%;
            }

            /* Barcode lebih besar */
            .barcode img {
                width: 220px;
                display: block;
                margin: 0 auto;
            }

            /* Sembunyikan elemen non-print */
            .no-print,
            .tombol,
            .input_nik_pengunjung,
            header,
            .depan {
                display: none !important;
            }

            * {
                box-shadow: none !important;
                background: transparent !important;
            }
        }

        .huruf-bawah.marquee {
            display: inline-block;
            white-space: nowrap;
            position: absolute;
            left: 0;
            width: 100%;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            animation: scroll-left 20s linear infinite;
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%) translateY(-50%);
            }

            100% {
                transform: translateX(-100%) translateY(-50%);
            }
        }

        /* ================== Responsive HP ================== */
        @media (max-width: 576px) {

            /* Sembunyikan logo */
            .logo-col {
                display: none !important;
            }

            /* Sembunyikan judul atas */
            .huruf-atas {
                display: none !important;
            }

            /* Marquee full-width HP */
            .huruf-bawah.marquee {
                position: relative;
                width: 100%;
                top: auto;
                transform: none;
                font-size: 16px;
                display: inline-block;
                animation: scroll-left-mobile 12s linear infinite;
            }

            /* Animasi marquee HP */
            @keyframes scroll-left-mobile {
                0% {
                    transform: translateX(100%);
                }

                100% {
                    transform: translateX(-100%);
                }
            }

            .titip-barang {
                background: #111010;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        }
        
        .menu-box{
          width: 280px;
          height: 280px;
          margin: 0 auto;
          border: 3px solid #1f59a5;
          border-radius: 22px;
          overflow: hidden;
          background: rgba(255,255,255,.03);
          display: flex;
          align-items: center;
          justify-content: center;
          transition: .25s ease;
        }
        
        .menu-img{
          width: 100%;
          height: 100%;
          object-fit: cover;
          display: block;
        }
        
        
         @media (max-width: 1300px){
          .menu-box{
            width: 250px;
            height: 250px;
            border-radius: 18px;
          }
        }
        
        /* Tablet */
        @media (max-width: 991.98px){
          .menu-box{
            width: 280px;
            height: 280px;
            border-radius: 18px;
          }
        }
        
        /* HP */
        @media (max-width: 576px){
          .menu-box{
            width: 250px;
            height: 250px;
            border-radius: 16px;
            border-width: 2px;
          }
        }
        
        @media (max-width: 480px){
          .menu-box{
            width: 150px;
            height: 150px;
            border-radius: 16px;
            border-width: 2px;
          }
        }
        
        
         .navbar-mobile-glass {
            background: rgba(0, 0, 0, .35);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .offcanvas-glass {
            background: radial-gradient(1200px 600px at 10% 10%, rgba(13, 110, 253, .18), transparent 55%),
                radial-gradient(900px 500px at 90% 20%, rgba(32, 201, 151, .12), transparent 60%),
                rgba(10, 14, 22, .92);
            backdrop-filter: blur(14px);
            border-left: 1px solid rgba(255, 255, 255, .10);
        }

        .offcanvas-glass .offcanvas-header {
            background: rgba(0, 0, 0, .25);
            backdrop-filter: blur(12px);
        }

        .offcanvas-glass .nav-link {
            border-radius: 14px;
            padding: 10px 12px;
            transition: .2s ease;
        }

        .offcanvas-glass .nav-link:hover {
            background: rgba(255, 255, 255, .08);
            transform: translateX(2px);
        }

    </style>
</head>

<body>

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path
                d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z">
            </path>
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path
                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z">
            </path>
            <path
                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z">
            </path>
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path
                d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z">
            </path>
        </symbol>
    </svg>
    
    <header data-bs-theme="dark" class="d-lg-none">
        <nav class="navbar navbar-dark navbar-mobile-glass shadow-sm">
            <div class="container">

                <div class="row align-items-center w-100">

                    <!-- LOGO (KIRI) -->
                    <div class="col-3 text-start">
                        <img src="../../image/logo.png" alt="Logo Lapas Kelas IIA Banceuy" height="70"
                            width="70">
                    </div>

                    <!-- TEXT (TENGAH) -->
                    <div class="col-7 text-center text-white">
                        <div class="fw-bold" style="font-size:14px; line-height:1;color: darkgoldenrod">
                            LEMBAGA PEMASYARAKATAN
                        </div>
                        <div class="fw-bold" style="font-size:14px; line-height:1.1;color: darkgoldenrod">
                            KELAS IIA BANCEUY
                        </div>
                    </div>

                    <!-- TOGGLE (KANAN) -->
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




    <!-- ================= POPUP MENU (OFFCANVAS) ================= -->
    <div class="offcanvas offcanvas-end offcanvas-glass text-white" tabindex="-1" id="mobileMenu"
        aria-labelledby="mobileMenuLabel">

        <!-- Header Popup -->
        <div class="offcanvas-header border-bottom border-dark">
            <h5 class="offcanvas-title fw-bold" id="mobileMenuLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>

        <!-- Body Popup -->
        <div class="offcanvas-body">
            <ul class="nav flex-column gap-3 fs-5">

                <li class="nav-item">
                    <a class="nav-link text-white" href="/">
                        🏠 Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="/tatap-muka">
                        👥 Pendaftaran Tatap Muka
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="/cek-antrian">
                        🔢 Cek Nomor Antrian
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="/titip-barang">
                        📦 Titip Barang
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="/kritik-saran">
                        📝 Kritik & Saran
                    </a>
                </li>

            </ul>
        </div>
    </div>
    
    
     <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-glass d-none d-lg-block">
        <div class="container py-2">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="/">
                <img src="{{ asset($setting->logo_utama ?? 'image/logo.png') }}" alt="Logo" style="height:42px;">
                <span class="d-none d-md-inline">{{ $setting->judul_header_2 ?? 'BANCEUY BANDUNG' }}</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="#menu">Home</a></li>
                   
                    <li class="nav-item ms-lg-2">
                        <a href="#menu" class="btn btn-primary rounded-pill px-4">
                            Mulai
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <main>
        {{-- logo banceuy --}}
        <section class="hero-image d-flex align-items-center justify-content-center py-3">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center">
                        <img src="../../image/logo.png" class="img-fluid" alt="Hero Image"
                            style="max-height:500px; object-fit:cover;" id="hero">
                    </div>
                </div>
            </div>
        </section>

        {{-- marque --}}
        <div class="container depan">
            <div class="row g-0">
                <!-- Logo, hidden di HP -->

                <!-- Judul & Marquee -->
                <div class="col-12 col-sm-12 p-2 position-relative overflow-hidden">
                    <!-- Judul atas (desktop only) -->
                    <!-- Marquee teks -->
                    <div class="huruf-bawah marquee">
                        SELAMAT DATANG DI WEBSITE LEMBAGA PEMASYARAKATAN KELAS
                        IIA BANCEUY
                    </div>
                </div>
            </div>
        </div>
       <div class="album py-5 bg-body-dark">
                <div class="container-lg">
                    <div class="row justify-content-center g-3 menu-row">
            
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="/tatap-muka" class="card-link">
                               <div class="menu-box shadow-sm">
                                    <img src="{{ asset('image/Pendaftaran1.png') }}" class="menu-img" alt="Pendaftaran">
                                </div>
                            </a>
                        </div>
            
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="/cek-antrian" class="card-link">
                                <div class="menu-box shadow-sm">
                                    <img src="{{ asset('image/cek-antrian1.png') }}" class="menu-img" alt="Cek Antrian">
                                </div>
                            </a>
                        </div>
            
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="/titip-barang" class="card-link">
                               <div class="menu-box shadow-sm">
                                    <img src="{{ asset('image/titip-barang1.png') }}" class="menu-img" alt="Titip Barang">
                                </div>
                            </a>
                        </div>
            
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="/kritik-saran" class="card-link">
                                <div class="menu-box shadow-sm">
                                    <img src="{{ asset('image/kritik2.png') }}" class="menu-img" alt="Kritik">
                                </div>
                            </a>
                        </div>
            
                    </div>
                </div>
            </div>

    </main>
    {{--
    <div class="garis"></div>

    <div class="container">
        <!-- konten -->
    </div> --}}

    <footer class="py-4 ">
        <div class="container">

            <!-- Bar atas -->
            <div class="row align-items-center text-center text-md-start mb-3">
                <div class="col-md-8 mb-3 mb-md-0">
                    <p class="mb-0 small fw-semibold">
                        ©COPYRIGHT LEMBAGA PEMASYARAKATAN KELAS IIA BANCEUY
                    </p>
                </div>

                <div class="col-md-4 text-center text-md-end">
                    <img src="../../image/menteri.png" alt="Logo Menteri" class="img-fluid footer-logo">
                </div>
            </div>

            <!-- Sosial Media -->
            <div class="row mb-5">
                <div class="col text-center">
                    <ul class="list-inline mb-0 social-icons">

                        <li class="list-inline-item">
                            <a href="#" class="social-circle"><i class="bi bi-facebook"></i></a>
                        </li>

                        <li class="list-inline-item">
                            <a href="#" class="social-circle"><i class="bi bi-instagram"></i></a>
                        </li>

                        <li class="list-inline-item">
                            <a href="#" class="social-circle"><i class="bi bi-twitter-x"></i></a>
                        </li>

                        <li class="list-inline-item">
                            <a href="#" class="social-circle"><i class="bi bi-youtube"></i></a>
                        </li>

                        <li class="list-inline-item">
                            <a href="#" class="social-circle"><i class="bi bi-tiktok"></i></a>
                        </li>

                        <li class="list-inline-item">
                            <a href="#" class="social-circle"><i class="bi bi-whatsapp"></i></a>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Modal Hari Libur -->
    <div class="modal fade" id="liburModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-warning text-dark border-0">
                        <h5 class="modal-title fw-bold">Kunjungan Libur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4 py-4 text-center">
                        <p class="mb-3" style="font-size: 16px; line-height: 1.5;">
                            Kunjungan pada <b>hari Sabtu & Minggu</b> libur.
                        </p>
                        <p class="mb-0" style="font-size: 16px; line-height: 1.5;">
                            Hanya bisa menggunakan fitur <b>Titip Barang</b>.  
                            Silahkan ke menu Titip Barang.
                        </p>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 py-2" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
     <script>
         $(document).ready(function() {
            $('.card-link[href="/tatap-muka"]').on('click', function(e) {
        
                 // Ambil waktu sekarang WIB
                 const nowUTC = new Date(); // waktu perangkat user (UTC+offset)
                 const utcOffset = nowUTC.getTimezoneOffset(); // selisih menit user dari UTC
                 const nowWIB = new Date(nowUTC.getTime() + (7 * 60 + utcOffset) * 60000);
        
              const today = nowWIB.getDay(); // 0 = Minggu, 6 = Sabtu
        
               if (today === 0 || today === 6) {
                   e.preventDefault();
                   const liburModal = new bootstrap.Modal(document.getElementById('liburModal'));
                    liburModal.show();
               }
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
