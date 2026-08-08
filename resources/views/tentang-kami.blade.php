@extends('startup_view.main')

@section('content')
    {{-- Load Feather Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <style>
        /* card glass */
        .profile-wrap {
            background: rgb(105, 119, 126);
            backdrop-filter: blur(12px);
        }

        /* tab pill */
        #profileTab .nav-link {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .75);
            transition: all .2s ease;
        }

        #profileTab .nav-link:hover {
            background: rgba(255, 255, 255, .10);
            color: #fff;
            transform: translateY(-1px);
        }

        #profileTab .nav-link.active {
            background: rgba(13, 110, 253, .20);
            border: 1px solid rgba(13, 110, 253, .35);
            color: #fff;
            box-shadow: 0 10px 25px rgba(13, 110, 253, .12);
        }

        /* konten box lebih lega + justify */
        .profile-content-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 18px;
            padding: 16px;
            color: rgba(255, 255, 255, .88);
            line-height: 1.9;
            text-align: justify;
            text-justify: inter-word;
        }

        #profileTab .nav-link i,
        .social-btn i {
            color: #fff;
        }

        .social-btn i {
            color: #fff;
            /* ikon selalu putih */
        }

        /* biar ga terlalu mepet di HP */
        @media (max-width: 576px) {
            .profile-content-box {
                padding: 12px;
            }
        }

        /* rapihin hasil summernote html */
        .profile-content-box h1,
        .profile-content-box h2,
        .profile-content-box h3,
        .profile-content-box h4,
        .profile-content-box h5 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .profile-content-box p {
            margin-bottom: 12px;
        }

        .profile-content-box ul,
        .profile-content-box ol {
            padding-left: 22px;
            margin-bottom: 12px;
        }

        .profile-content-box a {
            color: #8ab4ff;
            text-decoration: none;
        }

        .profile-content-box a:hover {
            text-decoration: underline;
        }

        /* Biar isi tab bener-bener center */
        #profileTab .nav-link {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px;
            /* jarak icon dan text */
            text-align: center;
            line-height: 1;
        }

        /* icon jangan ngubah posisi */
        #profileTab .nav-link svg {
            flex-shrink: 0;
            margin: 0 !important;
            display: block;
        }

        /* responsive tab pill */
        @media (max-width: 576px) {
            .profile-tab-mobile {
                flex-wrap: nowrap !important;
                gap: 6px !important;
            }

            .profile-tab-mobile .nav-item {
                flex: 0 0 25%;
                max-width: 25%;
            }

            .profile-tab-mobile .nav-link {
                width: 100%;
                padding: 8px 6px !important;
                font-size: 11px;
                text-align: center;
                justify-content: center;
                display: flex;
                align-items: center;
                white-space: nowrap;
            }

            .profile-tab-mobile .nav-link svg {
                width: 14px;
                height: 14px;
                margin-right: 4px !important;
            }
        }

        /* sosial media buttons */
        .social-btn {
            display: inline-block;
            padding: 12px 16px;
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            width: 100%;
            transition: transform .2s, box-shadow .2s;
        }

        .social-btn i {
            display: block;
            margin-bottom: 4px;
        }

        .social-btn:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
        }

        /* ===============================
                                       SOCIAL MEDIA – ELEGANT UNIFORM
                                       =============================== */

        /* ===============================
                                       SOCIAL MEDIA – ELEGANT GRADIENT
                                       =============================== */

        .facebook,
        .instagram,
        .twitter,
        .youtube,
        .tiktok,
        .whatsapp {
            background: linear-gradient(135deg,
                    rgba(178, 193, 206, 0.5),
                    rgba(40, 130, 220, 0.45));
            border: 1px solid rgba(160, 220, 255, 0.55);
            backdrop-filter: blur(10px);
            box-shadow:
                inset 0 1px 1px rgba(255, 255, 255, .35),
                0 8px 18px rgba(0, 0, 0, .25);
        }

        .facebook:hover,
        .instagram:hover,
        .twitter:hover,
        .youtube:hover,
        .tiktok:hover,
        .whatsapp:hover {
            background: linear-gradient(135deg,
                    rgba(120, 200, 255, 0.55),
                    rgba(60, 150, 255, 0.65));
        }

        @media (max-width: 576px) {

            /* UL bisa geser & napas */
            .profile-tab-mobile {
                flex-wrap: nowrap !important;
                overflow-x: auto;
                justify-content: flex-start !important;
                gap: 8px !important;
                padding-bottom: 8px;
                -webkit-overflow-scrolling: touch;
            }

            .profile-tab-mobile::-webkit-scrollbar {
                display: none;
            }


            .profile-tab-mobile .nav-item {
                margin-right: 16px;
                /* ⬅️ jarak antar pill */
                flex: 0 0 auto;
            }

            .profile-tab-mobile .nav-item:last-child {
                margin-right: 0;
            }

            /* INI KUNCI UTAMANYA */
            .profile-tab-mobile .nav-link {
                min-width: max-content;
                /* ⬅️ biar ikut panjang teks */
                padding: 10px 14px !important;
                font-size: 12.5px;
                line-height: 1.2;
                white-space: nowrap;

                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;

                border-radius: 999px;
            }

            /* ICON proporsional */
            .profile-tab-mobile .nav-link svg {
                width: 15px;
                height: 15px;
                flex-shrink: 0;
            }
        }

        @media (max-width: 360px) {

            .profile-tab-mobile {
                padding-left: 0px;
                padding-right: -40px;
                /* ⬅️ RUANG AMAN KANAN */
                overflow-x: auto;
                gap: 0 !important;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>

    <div class="container my-4">

        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1 text-dark">TENTANG LEMBAGA PEMASYARAKATAN KELAS IIA BANCEUY BANDUNG</h4>
            <small class="text-dark">Informasi ditampilkan dalam tab</small>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden profile-wrap">

            <div class="card-body p-4 p-lg-5">

                {{-- ================= TAB MENU ================= --}}
                <ul class="nav nav-pills gap-2 justify-content-center mb-4 profile-tab-mobile" id="profileTab" role="tablist">

                    <!--<li class="nav-item" role="presentation">-->
                    <!--    <button class="nav-link  rounded-pill px-4 py-2 fw-semibold"-->
                    <!--        id="tab-sejarah" data-bs-toggle="tab" data-bs-target="#sejarah"-->
                    <!--        type="button" role="tab">-->
                    <!--        <i data-feather="book-open" class="me-2"></i>Sejarah-->
                    <!--    </button>-->
                    <!--</li>-->

                    <!--<li class="nav-item" role="presentation">-->
                    <!--    <button class="nav-link rounded-pill px-4 py-2 fw-semibold"-->
                    <!--        id="tab-struktur" data-bs-toggle="tab" data-bs-target="#struktur"-->
                    <!--        type="button" role="tab">-->
                    <!--        <i data-feather="users" class="me-2"></i>Struktur-->
                    <!--    </button>-->
                    <!--</li>-->

                    <!--<li class="nav-item" role="presentation">-->
                    <!--    <button class="nav-link rounded-pill px-4 py-2 fw-semibold"-->
                    <!--        id="tab-visi" data-bs-toggle="tab" data-bs-target="#visi"-->
                    <!--        type="button" role="tab">-->
                    <!--        <i data-feather="target" class="me-2"></i>Visi-->
                    <!--    </button>-->
                    <!--</li>-->

                    <!--<li class="nav-item" role="presentation">-->
                    <!--    <button class="nav-link rounded-pill px-4 py-2 fw-semibold"-->
                    <!--        id="tab-tugas" data-bs-toggle="tab" data-bs-target="#tugas"-->
                    <!--        type="button" role="tab">-->
                    <!--        <i data-feather="briefcase" class="me-2"></i>Tugas-->
                    <!--    </button>-->
                    <!--</li>-->

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 py-2 fw-semibold" id="tab-sosmed"
                            data-bs-toggle="tab" data-bs-target="#sosmed" type="button" role="tab">
                            <i data-feather="share-2" class="me-2" style="color:white;"></i>Sosial Media
                        </button>
                    </li>

                </ul>

                {{-- ================= TAB CONTENT ================= --}}
                <div class="tab-content" id="profileTabContent">

                    {{-- Sejarah --}}
                    <div class="tab-pane fade  profile-content-box" id="sejarah" role="tabpanel">
                        {!! $profile->sejarah_singkat ?? '<span class="text-white-50">Belum ada data.</span>' !!}
                    </div>

                    {{-- Struktur --}}
                    <div class="tab-pane fade profile-content-box" id="struktur" role="tabpanel">
                        {!! $profile->struktur_organisasi ?? '<span class="text-white-50">Belum ada data.</span>' !!}
                    </div>

                    {{-- Visi --}}
                    <div class="tab-pane fade profile-content-box" id="visi" role="tabpanel">
                        {!! $profile->visi_misi ?? '<span class="text-white-50">Belum ada data.</span>' !!}
                    </div>

                    {{-- Tugas --}}
                    <div class="tab-pane fade profile-content-box" id="tugas" role="tabpanel">
                        {!! $profile->tugas_fungsi ?? '<span class="text-white-50">Belum ada data.</span>' !!}
                    </div>

                    {{-- Sosial Media --}}
                    <div class="tab-pane fade show active profile-content-box" id="sosmed" role="tabpanel">
                        <div class="row g-3 justify-content-center mt-3">

                            @if ($setting->facebook)
                                <div class="col-6 col-sm-4 col-md-4 text-center">
                                    <a href="{{ $setting->facebook }}" target="_blank" class="social-btn facebook">
                                        <i data-feather="facebook" class="mb-1" width="28" height="28"></i>
                                        <div>Facebook</div>
                                    </a>
                                </div>
                            @endif

                            @if ($setting->instagram)
                                <div class="col-6 col-sm-4 col-md-4 text-center">
                                    <a href="{{ $setting->instagram }}" target="_blank" class="social-btn instagram">
                                        <i data-feather="instagram" class="mb-1" width="28" height="28"></i>
                                        <div>Instagram</div>
                                    </a>
                                </div>
                            @endif

                            @if ($setting->twitter)
                                <div class="col-6 col-sm-4 col-md-4 text-center">
                                    <a href="{{ $setting->twitter }}" target="_blank" class="social-btn twitter">
                                        <i data-feather="twitter" class="mb-1" width="28" height="28"></i>
                                        <div>Twitter</div>
                                    </a>
                                </div>
                            @endif

                            @if ($setting->youtube)
                                <div class="col-6 col-sm-4 col-md-4 text-center">
                                    <a href="{{ $setting->youtube }}" target="_blank" class="social-btn youtube">
                                        <i data-feather="youtube" class="mb-1" width="28" height="28"></i>
                                        <div>YouTube</div>
                                    </a>
                                </div>
                            @endif

                            @if ($setting->tiktok)
                                <div class="col-6 col-sm-4 col-md-4 text-center">
                                    <a href="{{ $setting->tiktok }}" target="_blank" class="social-btn tiktok">
                                        <i data-feather="smartphone" class="mb-1" width="28" height="28"></i>
                                        <div>TikTok</div>
                                    </a>
                                </div>
                            @endif

                            @if ($setting->whatsapp)
                                <div class="col-6 col-sm-4 col-md-4 text-center">
                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $setting->whatsapp) }}"
                                        target="_blank" class="social-btn whatsapp">
                                        <i data-feather="message-circle" class="mb-1" width="28"
                                            height="28"></i>
                                        <div>WhatsApp</div>
                                    </a>
                                </div>
                            @endif

                            @if (
                                !$setting->facebook &&
                                    !$setting->instagram &&
                                    !$setting->twitter &&
                                    !$setting->youtube &&
                                    !$setting->tiktok &&
                                    !$setting->whatsapp)
                                <div class="col-12 text-center text-white-50">
                                    Belum ada data sosial media.
                                </div>
                            @endif

                            <div class="text-center mt-4 text-white-50 small">
                                Jangan lupa follow sosial media kami ya, supaya nggak ketinggalan info terbaru ✨
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <script>
        feather.replace(); // Render Feather icons
    </script>
@endsection
