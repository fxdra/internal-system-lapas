@extends('startup_view.main')

@section('content')
    <style>
        /* card glass */
        .profile-wrap {
            background: rgb(105, 119, 126);
            backdrop-filter: blur(12px);
        }

        /* tab pill */
        #profileTab .nav-link {
            background: rgb(117, 114, 114);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #fff;
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
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 18px;
            padding: 10px;
            color: rgba(255, 255, 255, .88);
            line-height: 1.9;

            /* ini yang bikin rapih */
            text-align: justify;
            text-justify: inter-word;
        }

        /* biar ga terlalu mepet di HP */
        @media (max-width: 576px) {
            .profile-content-box {
                padding: 16px;
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
    </style>

    <div class="container my-4">

        <div class="text-center mb-4">
            <h4 class="fw-bold mb-1 text-dark">PROFILE LEMBAGA PEMASYARAKATAN KELAS IIA BANCEUY BANDUNG</h4>
            <small class="fw-semibold text-dark">Informasi profil ditampilkan dalam tab</small>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden profile-wrap">

            <div class="card-body p-4 p-lg-5">

                {{-- ================= TAB MENU ================= --}}
                <ul class="nav nav-pills gap-2 justify-content-center justify-content-lg-start mb-4 profile-tab-mobile"
                    id="profileTab" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 py-2 fw-semibold" id="tab-sejarah"
                            data-bs-toggle="tab" data-bs-target="#sejarah" type="button" role="tab">
                            <i data-feather="book-open" class="me-2"></i>Sejarah
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 fw-semibold" id="tab-struktur" data-bs-toggle="tab"
                            data-bs-target="#struktur" type="button" role="tab">
                            <i data-feather="users" class="me-2"></i>Struktur
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 fw-semibold" id="tab-visi" data-bs-toggle="tab"
                            data-bs-target="#visi" type="button" role="tab">
                            <i data-feather="target" class="me-2"></i>Visi
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-2 fw-semibold" id="tab-tugas" data-bs-toggle="tab"
                            data-bs-target="#tugas" type="button" role="tab">
                            <i data-feather="briefcase" class="me-2"></i>Tugas
                        </button>
                    </li>

                </ul>

                {{-- ================= TAB CONTENT ================= --}}
                <div class="tab-content" id="profileTabContent">

                    {{-- ================= SEJARAH ================= --}}
                    <div class="tab-pane fade show active" id="sejarah" role="tabpanel"
                        style="color: #fff; text-align: justify;">

                        {!! $profile->sejarah_singkat ?? '<span class="text-white">Belum ada data.</span>' !!}

                    </div>

                    {{-- ================= STRUKTUR ================= --}}
                    <div class="tab-pane fade" id="struktur" role="tabpanel" style="color: #fff; text-align: justify;">

                        {!! $profile->struktur_organisasi ?? '<span class="text-white">Belum ada data.</span>' !!}

                    </div>

                    {{-- ================= VISI MISI ================= --}}
                    <div class="tab-pane fade" id="visi" role="tabpanel" style="color: #fff; text-align: justify;">

                        {!! $profile->visi_misi ?? '<span class="text-white">Belum ada data.</span>' !!}

                    </div>

                    {{-- ================= TUGAS FUNGSI ================= --}}
                    <div class="tab-pane fade" id="tugas" role="tabpanel" style="color: #fff; text-align: justify;">

                        {!! $profile->tugas_fungsi ?? '<span class="text-white">Belum ada data.</span>' !!}

                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
