<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Kamar & WBP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendors/css/bsicon.min.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /*=========================================================
                            FLOATING DOCK
        =========================================================*/

        #quickDock {
            position: fixed;
            left: 50%;
            bottom: 20px;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
            padding: 14px 18px;
            border-radius: 28px;
            background: rgba(255, 255, 255, .90);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .35);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .18);
            z-index: 99999;

            overflow: visible;

            scrollbar-width: none;
        }

        #quickDock::-webkit-scrollbar {
            display: none;
        }

        /*=========================================================
                            SECTION FLOATING DOCK
        =========================================================*/
        .dock-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dock-title {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            white-space: nowrap;
            letter-spacing: .4px;
            margin-right: 6px;
            text-transform: uppercase;
        }


        /*=========================================================
                            ITEM FLOATING DOCK
        =========================================================*/
        .dock-item {
            position: relative;
            min-width: 52px;
            height: 52px;
            padding: 0 18px;
            border-radius: 999px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            user-select: none;
            font-weight: 700;
            transition:
                transform .25s,
                background .25s,
                color .25s,
                box-shadow .25s;
            box-shadow:
                0 2px 8px rgba(0, 0, 0, .08);
        }

        .dock-item span {
            font-size: 13px;
        }

        .dock-item:hover {
            transform:
                translateY(-3px) scale(1.03);
            background: #0d3b66;
            color: #fff;
            box-shadow:
                0 12px 25px rgba(13, 59, 102, .30);
        }

        /*=========================================================
                            ACTIVE FLOATING DOCK
        =========================================================*/

        .dock-item.active {
            background: #0d3b66;
            color: #fff;
            transform: translateY(-4px);
            box-shadow:
                0 10px 22px rgba(13, 59, 102, .35);
        }

        /*=========================================================
                            POPUP FLOATING DOCK
        =========================================================*/

        .dock-popup {
            position: absolute;
            bottom: 52px;
            left: 50%;

            width: 220px;
            padding: 12px;

            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;

            border-radius: 16px;

            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, .45);

            box-shadow: 0 15px 35px rgba(0, 0, 0, .18);

            z-index: 100000;

            opacity: 0;
            visibility: hidden;
            pointer-events: none;

            transform: translate(-50%, 10px);

            transition:
                opacity .18s ease,
                transform .18s ease,
                visibility 0s linear .18s;
        }

        .dock-item:is(:hover, :focus-within) .dock-popup {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translate(-50%, 0);
        }

        .dock-popup::before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -14px;
            height: 14px;
        }

        .dock-popup:hover {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /*=========================================================
                        ROOM BUTTON FLOATING DOCK
        =========================================================*/

        .popup-room {
            width: 42px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;

            background: #f3f6fa;
            color: #212529;

            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .popup-room:hover {
            background: #0d3b66;
            color: #fff;
        }

        /*=========================================================
                        CARD HIGHLIGHT FLOATING DOCK
        =========================================================*/

        .kamar-card {
            scroll-margin-top: 20px;
            transition:
                box-shadow .35s,
                border-color .35s,
                transform .35s;
        }

        .room-highlight {
            border: 3px solid #0d3b66 !important;
            box-shadow:
                0 0 0 4px rgba(13, 59, 102, .15),
                0 15px 35px rgba(13, 59, 102, .35);
            animation: roomPulse .9s ease;
        }

        .section-highlight {
            border: 3px solid #0d3b66 !important;
            box-shadow:
                0 0 0 4px rgba(13, 59, 102, .15),
                0 15px 35px rgba(13, 59, 102, .30);

            animation: roomPulse .9s ease;
        }

        /*=========================================================
                        ANIMATION FLOATING DOCK
        =========================================================*/

        @keyframes roomPulse {
            0% {
                transform: scale(.96);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }

        }

        /*=========================================================
                        RESPONSIVE FLOATING DOCK
        =========================================================*/
        @media (max-width:768px) {
            #quickDock {
                width: calc(100% - 20px);
                left: 10px;
                right: 10px;
                transform: none;
                bottom: 10px;
                padding: 12px;
                overflow-x: auto;
            }

            .dock-section {
                flex-wrap: wrap;
                justify-content: center;
            }

            .dock-title {
                width: 100%;
                text-align: center;
                margin-bottom: 4px;
            }

        }

        /*=========================================================
                        BREADCRUMB HEADER
        =========================================================*/
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }

        .breadcrumb-item {
            font-weight: 500;
        }

        .breadcrumb-item a {
            color: #0d3b66;
            text-decoration: none;
            transition: color .2s;
        }

        .breadcrumb-item a:hover {
            color: #0a2d4f;
            text-decoration: underline;
        }

        .breadcrumb-item.active {
            color: #6c757d;
            font-weight: 600;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: #adb5bd;
        }

        .foto-wbp {
            width: 55px;
            height: 70px;
            object-fit: cover;
            border-radius: 6px;
            background: #dee2e6;
            border: 1px solid #dee2e6;
        }

        .wbp-item {
            cursor: pointer;
            transition: .2s;
        }

        .wbp-item:hover {
            border-color: #0d6efd;
            background: #f8fbff;
        }

        .wbp-item.selected {

            background: #d1e7dd;
            border: 2px solid #198754;
            box-shadow: 0 0 0 .2rem rgba(25, 135, 84, .15);

        }

        .page-hunian {
            padding-bottom: 140px;
        }

        @media print {

            @page {
                size: 297mm 210mm;
                margin: 12mm;
            }

            body {
                background: #fff !important;
            }

            /* =========================
            PRINT GRID
            ========================= */

            .container-fluid.py-3 {
                display: flex !important;
                flex-wrap: wrap !important;

                width: 264mm !important;
                max-width: 264mm !important;

                column-gap: 8mm !important;
                row-gap: 8mm !important;

                margin: 0 !important;
                padding: 0 !important;

                align-items: flex-start !important;
                align-content: flex-start !important;
            }

            /* =========================
            SEMBUNYIKAN UI
            ========================= */

            .no-print,
            .btn-all,
            .btn-batal,
            .btn-pindah,
            .wbp-check,
            .toolbar-select,
            .quick-dock,
            #quickDock,
            .screen-only {
                display: none !important;
            }

            /* =========================
            SEMBUNYIKAN SEMUA HEADER CARD
            ========================= */

            .card-header {
                display: none !important;
            }

            /* =========================
            SEMBUNYIKAN PESAN KAMAR KOSONG
            ========================= */

            .kamar-card .alert {
                display: none !important;
            }


            /* =========================
            CONTAINER BLOK
            ========================= */

            .blok-card {
                display: contents !important;
            }

            .blok-card>.card-body {
                padding: 0 !important;
                margin: 0 !important;
            }


            /* =========================
            CONTAINER KAMAR
            ========================= */

            .kamar-card {
                display: contents !important;
            }

            .kamar-card>.card-body {
                padding: 0 !important;
                margin: 0 !important;
            }

            /* =========================
            HEADER KAMAR TERPILIH
            ========================= */

            .kamar-card:has(.print-card:not([style*="display: none"]))>.card-header {
                display: block !important;

                width: 264mm !important;
                max-width: 264mm !important;

                flex: 0 0 264mm !important;

                margin: 0 0 4mm 0 !important;
                padding: 0 0 3mm 0 !important;

                border: none !important;
                border-bottom: 1px solid #000 !important;

                background: #fff !important;
                color: #000 !important;
            }


            /* =========================
            JUDUL KAMAR
            ========================= */

            .kamar-card:has(.print-card:not([style*="display: none"]))>.card-header h5 {
                font-size: 11pt !important;
                margin: 0 !important;
                color: #000 !important;
            }


            /* =========================
            SEMBUNYIKAN KAMAR + KAPASITAS
            ========================= */

            .kamar-card:has(.print-card:not([style*="display: none"]))>.card-header .text-muted {
                display: none !important;
            }


            /* =========================
            SEMBUNYIKAN BAGIAN KANAN
            Dipilih + jumlah WBP + tombol
            ========================= */

            .kamar-card:has(.print-card:not([style*="display: none"]))>.card-header .d-flex.flex-column.align-items-end {
                display: none !important;
            }

            /* =========================
            RUMAH SAKIT & BON
            DEFAULT HILANG
            ========================= */

            #section-rs,
            #section-bon {
                display: none !important;
            }


            /* =========================
                TAMPILKAN HANYA JIKA
                ADA WBP YANG DIPILIH
                ========================= */

            #section-rs:has(.print-card:not([style*="display: none"])),
            #section-bon:has(.print-card:not([style*="display: none"])) {
                display: contents !important;
            }


            /* =========================
            BODY SECTION JADI TRANSPARAN
            ========================= */

            #section-rs>.card-body,
            #section-bon>.card-body {
                display: contents !important;
            }


            /* =========================
            ROW JADI TRANSPARAN
            ========================= */

            #section-rs .row.g-3,
            #section-bon .row.g-3 {
                display: contents !important;
            }


            /* =========================
            ROW
            ========================= */

            .kamar-card .row.g-3 {
                margin: 0 !important;
            }

            /* =========================
            SETIAP WBP
            ========================= */

            .print-card {
                width: 128mm !important;
                max-width: 128mm !important;

                flex: 0 0 128mm !important;

                padding: 0 !important;
                margin: 0 !important;

                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            /* =========================
            STEREK
            ========================= */

            .wbp-item {
                width: 128mm !important;
                height: 34mm !important;

                box-sizing: border-box !important;

                border: 1px solid #000 !important;
                border-radius: 0 !important;
                box-shadow: none !important;

                background: #fff !important;

                overflow: hidden !important;

                margin: 0 !important;
            }


            .wbp-item.selected {
                background: #fff !important;
                border: 1px solid #000 !important;
            }

            /* =========================
            ISI STEREK
            ========================= */

            .wbp-item .card-body {
                padding: 5px 6px !important;
            }

            /* =========================
            FOTO
            ========================= */

            .foto-wbp {
                width: 22mm !important;
                height: 28mm !important;

                object-fit: cover !important;
            }

            /* =========================
            NAMA
            ========================= */

            .nama-wbp {
                font-size: 10pt !important;
                font-weight: 700 !important;
                line-height: 1.15 !important;

                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            /* =========================
            DATA WBP
            ========================= */

            .print-only {
                display: block !important;

                margin-top: 3px !important;

                font-size: 8pt !important;
                line-height: 1.2 !important;
            }

            .print-only div {
                margin-bottom: 1px !important;

                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }


            .print-only strong {
                display: inline-block !important;
                width: 65px !important;
            }
        }

        .screen-only {
            display: block;
        }

        .print-only {
            display: none;
        }

        .select-box {
            width: 22px;
            height: 22px;
            cursor: pointer;
        }

        .toolbar-select {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #fff;
            border: 1px solid #dee2e6;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }
    </style>
</head>

<body>

    <div class="container-fluid py-3 page-hunian">

        <div class="card shadow-sm mb-4 no-print">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start mb-3">

                    <div>

                        <h4 class="fw-bold mb-1">
                            DATA HUNIAN KAMAR
                        </h4>

                        <nav aria-label="breadcrumb">

                            <ol class="breadcrumb mb-0">

                                <li class="breadcrumb-item">
                                    <a href="/admin-banceuy">
                                        Dashboard
                                    </a>
                                </li>

                                <li class="breadcrumb-item active">
                                    Data Hunian Kamar
                                </li>

                            </ol>

                        </nav>

                    </div>

                    <div class="d-flex align-items-center gap-2">

                        <div class="btn-group" role="group" aria-label="View Mode">
                            <button type="button" id="btnCardView" class="btn btn-outline-secondary active"
                                title="Card View">

                                <i class="bi bi-grid-3x3-gap-fill"></i>

                            </button>

                            <button type="button" id="btnDetailView" class="btn btn-outline-secondary"
                                title="Detail View">

                                <i class="bi bi-list-ul"></i>

                            </button>

                        </div>

                        <button type="button" class="btn btn-primary" onclick="printSelected()">

                            <i class="bi bi-printer me-1"></i>
                            Print Sterek

                        </button>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-4 col-md-6">

                        <input type="text" id="searchKamar" class="form-control"
                            placeholder="Cari Blok / No. Kamar...">

                    </div>

                </div>

            </div>

        </div>

        {{-- =======================
         CARD VIEW
        ======================= --}}
        <div id="cardView">
            @foreach ($grouped as $blok => $kamars)
                <div id="blok-{{ $blok }}" class="card shadow-sm mb-4 blok-card"
                    data-block="{{ $blok }}">

                    <div class="card-header bg-dark text-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h4 class="mb-0">
                                BLOK {{ $blok }}
                            </h4>

                        </div>

                    </div>

                    <div class="card-body">

                        @foreach ($kamars as $kamar)
                            <div id="room-card-{{ $kamar->kode_blok }}-{{ $kamar->no_kamar }}"
                                class="card border-primary mb-4 kamar-card" data-kamar="{{ $kamar->kamar_id }}"
                                data-search="{{ strtoupper(
                                    'BLOK ' .
                                        $kamar->kode_blok .
                                        ' ' .
                                        $kamar->lokasi_sel .
                                        ' ' .
                                        $kamar->kode_blok .
                                        ' ' .
                                        str_replace('KAMAR ', '', strtoupper($kamar->lokasi_sel)) .
                                        ' ' .
                                        $kamar->kode_blok .
                                        str_replace('KAMAR ', '', strtoupper($kamar->lokasi_sel)),
                                ) }}">

                                <div class="card-header bg-light">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>

                                            <h5 class="mb-1 fw-bold text-primary">
                                                BLOK {{ $kamar->kode_blok }} - {{ $kamar->lokasi_sel }}
                                            </h5>


                                            <div class="text-muted small">

                                                {{ $kamar->lokasi_sel }}
                                                &nbsp;•&nbsp;
                                                Kapasitas {{ $kamar->kapasitas }} Orang

                                            </div>

                                        </div>

                                        <div class="d-flex flex-column align-items-end">

                                            <span class="fw-bold mb-2">
                                                Dipilih :
                                                <span class="selectedCount">0</span>
                                                Orang
                                            </span>

                                            <div class="d-flex align-items-center gap-2">

                                                <span class="badge {{ $kamar->badge_kapasitas }} fs-6 px-3 py-2">
                                                    {{ $kamar->jumlah_wbp }}/{{ $kamar->kapasitas }} WBP
                                                </span>

                                                <button type="button" class="btn btn-success btn-sm btn-all"
                                                    onclick="selectKamar(this)">
                                                    Pilih Semua
                                                </button>

                                                <button type="button" class="btn btn-secondary btn-sm btn-batal"
                                                    onclick="unselectKamar(this)">
                                                    Batal
                                                </button>

                                                <button type="button"
                                                    class="btn btn-warning btn-sm fw-semibold btn-pindah"
                                                    onclick="bukaModalPindah(
                                                        this,
                                                        '{{ $kamar->kamar_id }}',
                                                        '{{ ucfirst(strtolower(explode('-', $kamar->kode_blok)[0])) }}',
                                                        '{{ $kamar->lokasi_sel }}'
                                                    )">

                                                    <i class="bi bi-arrow-left-right me-1"></i>
                                                    Pindahkan

                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="card-body">

                                    @if (count($kamar->wbps))
                                        <div class="row g-3">

                                            @foreach ($kamar->wbps as $wbp)
                                                <div class="col-lg-6 print-card">

                                                    <div class="card h-100 position-relative wbp-item">

                                                        <div class="position-absolute top-0 end-0 m-2">

                                                            <input type="checkbox"
                                                                class="form-check-input select-box wbp-check d-none"
                                                                value="{{ $wbp['id'] ?? '' }}">

                                                        </div>

                                                        <div class="card-body py-2">

                                                            <div class="d-flex align-items-center">

                                                                <div class="me-3 flex-shrink-0">

                                                                    @if (!empty($wbp['foto_wbp']))
                                                                        <img src="{{ asset($wbp['foto_wbp']) }}"
                                                                            class="foto-wbp" alt="{{ $wbp['nama'] }}">
                                                                    @else
                                                                        <div class="foto-wbp"></div>
                                                                    @endif

                                                                </div>

                                                                <div class="flex-grow-1 overflow-hidden">

                                                                    <div class="fw-bold nama-wbp text-uppercase">
                                                                        {{ $wbp['nama'] }}
                                                                    </div>

                                                                    {{-- Tampil di layar --}}
                                                                    <div
                                                                        class="screen-only text-muted small text-truncate">
                                                                        {{ $wbp['jenis_kejahatan'] }}
                                                                    </div>

                                                                    {{-- Tampil saat print --}}
                                                                    <div class="print-only">

                                                                        <div>
                                                                            <strong>No. Reg :</strong>
                                                                            {{ $wbp['no_reg_instansi'] ?? '-' }}
                                                                        </div>

                                                                        <div>
                                                                            <strong>Putusan :</strong>

                                                                            @php
                                                                                $tahun = (int) ($wbp['putusan'] ?? 0);
                                                                                $bulan =
                                                                                    (int) ($wbp['putusan_bulan'] ?? 0);
                                                                            @endphp

                                                                            @if ($tahun > 0)
                                                                                {{ $tahun }} Tahun
                                                                            @endif

                                                                            @if ($bulan > 0)
                                                                                {{ $bulan }} Bulan
                                                                            @endif

                                                                            @if ($tahun === 0 && $bulan === 0)
                                                                                -
                                                                            @endif
                                                                        </div>

                                                                        <div>
                                                                            <strong>Perkara :</strong>
                                                                            {{ $wbp['jenis_kejahatan'] ?? '-' }}
                                                                        </div>

                                                                        <div>
                                                                            <strong>Ekspirasi :</strong>
                                                                            {{ !empty($wbp['ekspirasi']) ? \Carbon\Carbon::parse($wbp['ekspirasi'])->format('d-m-Y') : '-' }}
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            @endforeach

                                        </div>
                                    @else
                                        <div class="alert alert-secondary mb-0">
                                            Tidak ada WBP pada kamar ini
                                        </div>
                                    @endif

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>
            @endforeach

            {{-- ==========================================================
                                LUAR TEMBOK
            ========================================================== --}}

            <section id="section-rs" class="card shadow-sm mb-4">

                <div class="card-header bg-danger text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            RUMAH SAKIT
                        </h4>

                        <span class="badge bg-light text-danger">
                            {{ $wbpRs->count() }} WBP
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    @if ($wbpRs->count())

                        <div class="row g-3">

                            @foreach ($wbpRs as $wbp)
                                <div class="col-lg-6 print-card">

                                    <div class="card h-100 position-relative wbp-item">

                                        <div class="position-absolute top-0 end-0 m-2">

                                            <input type="checkbox"
                                                class="form-check-input select-box wbp-check d-none"
                                                value="{{ $wbp['id'] ?? '' }}">

                                        </div>

                                        <div class="card-body py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 flex-shrink-0">

                                                    @if (!empty($wbp['foto_wbp']))
                                                        <img src="{{ asset($wbp['foto_wbp']) }}" class="foto-wbp"
                                                            alt="{{ $wbp['nama'] }}">
                                                    @else
                                                        <div class="foto-wbp"></div>
                                                    @endif

                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="fw-bold nama-wbp text-uppercase">
                                                        {{ $wbp['nama'] }}
                                                    </div>

                                                    <div class="mb-1">
                                                        <span class="badge bg-primary">
                                                            Status : {{ $wbp['status_wbp'] }}
                                                        </span>
                                                    </div>

                                                    {{-- Tampil di layar --}}
                                                    <div class="screen-only text-muted small text-truncate">
                                                        {{ $wbp['jenis_kejahatan'] }}
                                                    </div>

                                                    {{-- Tampil saat print --}}
                                                    <div class="print-only">

                                                        <div>
                                                            <strong>No. Reg :</strong>
                                                            {{ $wbp['no_reg_instansi'] ?? '-' }}
                                                        </div>

                                                        <div>
                                                            <strong>Putusan :</strong>

                                                            @php
                                                                $tahun = (int) ($wbp['putusan'] ?? 0);
                                                                $bulan = (int) ($wbp['putusan_bulan'] ?? 0);
                                                            @endphp

                                                            @if ($tahun > 0)
                                                                {{ $tahun }} Tahun
                                                            @endif

                                                            @if ($bulan > 0)
                                                                {{ $bulan }} Bulan
                                                            @endif

                                                            @if ($tahun === 0 && $bulan === 0)
                                                                -
                                                            @endif
                                                        </div>

                                                        <div>
                                                            <strong>Perkara :</strong>
                                                            {{ $wbp['jenis_kejahatan'] ?? '-' }}
                                                        </div>

                                                        <div>
                                                            <strong>Ekspirasi :</strong>
                                                            {{ !empty($wbp['ekspirasi']) ? \Carbon\Carbon::parse($wbp['ekspirasi'])->format('d-m-Y') : '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            Tidak ada WBP yang sedang dirawat di Rumah Sakit.
                        </div>

                    @endif

                </div>

            </section>

            <section id="section-bon" class="card shadow-sm mb-4">

                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            BON
                        </h4>

                        <span class="badge bg-light text-primary">
                            {{ $wbpBon->count() }} WBP
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    @if ($wbpBon->count())

                        <div class="row g-3">

                            @foreach ($wbpBon as $wbp)
                                <div class="col-lg-6 print-card">

                                    <div class="card h-100 position-relative wbp-item">

                                        <div class="position-absolute top-0 end-0 m-2">

                                            <input type="checkbox"
                                                class="form-check-input select-box wbp-check d-none"
                                                value="{{ $wbp['id'] ?? '' }}">

                                        </div>

                                        <div class="card-body py-2">

                                            <div class="d-flex align-items-center">

                                                <div class="me-3 flex-shrink-0">

                                                    @if (!empty($wbp['foto_wbp']))
                                                        <img src="{{ asset($wbp['foto_wbp']) }}" class="foto-wbp"
                                                            alt="{{ $wbp['nama'] }}">
                                                    @else
                                                        <div class="foto-wbp"></div>
                                                    @endif

                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">

                                                    <div class="fw-bold nama-wbp text-uppercase">
                                                        {{ $wbp['nama'] }}
                                                    </div>

                                                    <div class="mb-1">
                                                        <span class="badge bg-primary">
                                                            Status : {{ $wbp['status_wbp'] }}
                                                        </span>
                                                    </div>

                                                    {{-- Tampil di layar --}}
                                                    <div class="screen-only text-muted small text-truncate">
                                                        {{ $wbp['jenis_kejahatan'] }}
                                                    </div>

                                                    {{-- Tampil saat print --}}
                                                    <div class="print-only">

                                                        <div>
                                                            <strong>No. Reg :</strong>
                                                            {{ $wbp['no_reg_instansi'] ?? '-' }}
                                                        </div>

                                                        <div>
                                                            <strong>Putusan :</strong>

                                                            @php
                                                                $tahun = (int) ($wbp['putusan'] ?? 0);
                                                                $bulan = (int) ($wbp['putusan_bulan'] ?? 0);
                                                            @endphp

                                                            @if ($tahun > 0)
                                                                {{ $tahun }} Tahun
                                                            @endif

                                                            @if ($bulan > 0)
                                                                {{ $bulan }} Bulan
                                                            @endif

                                                            @if ($tahun === 0 && $bulan === 0)
                                                                -
                                                            @endif
                                                        </div>

                                                        <div>
                                                            <strong>Perkara :</strong>
                                                            {{ $wbp['jenis_kejahatan'] ?? '-' }}
                                                        </div>

                                                        <div>
                                                            <strong>Ekspirasi :</strong>
                                                            {{ !empty($wbp['ekspirasi']) ? \Carbon\Carbon::parse($wbp['ekspirasi'])->format('d-m-Y') : '-' }}
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            Tidak ada WBP BON.
                        </div>

                    @endif

                </div>

            </section>
        </div>

        {{-- =======================
        DETAIL VIEW
        ======================= --}}
        <div id="detailView" style="display:none;">

            {{-- ================= DALAM TEMBOK ================= --}}
            @foreach ($grouped as $blok => $kamars)
                @foreach ($kamars as $kamar)
                    <div id="room-detail-{{ $kamar->kode_blok }}-{{ $kamar->no_kamar }}"
                        class="card shadow-sm mb-3 kamar-card" data-kamar="{{ $kamar->kamar_id }}">

                        <div class="card-header bg-light">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>

                                    <h6 class="fw-bold text-primary mb-2">
                                        BLOK {{ $kamar->kode_blok }} - {{ $kamar->lokasi_sel }}
                                    </h6>

                                    <div class="d-flex align-items-center gap-2">

                                        <span class="fw-semibold">
                                            Dipilih :
                                            <span class="selectedCount">0</span>
                                            Orang
                                        </span>

                                        <button type="button" class="btn btn-success btn-sm btn-all"
                                            onclick="selectKamar(this)">

                                            Pilih Semua

                                        </button>

                                        <button type="button" class="btn btn-secondary btn-sm btn-batal"
                                            onclick="unselectKamar(this)">

                                            Batal

                                        </button>

                                        <button type="button" class="btn btn-warning btn-sm fw-semibold btn-pindah"
                                            onclick="bukaModalPindah(
                                            this,
                                            '{{ $kamar->kamar_id }}',
                                            '{{ ucfirst(strtolower(explode('-', $kamar->kode_blok)[0])) }}',
                                            '{{ $kamar->lokasi_sel }}'
                                        )">

                                            <i class="bi bi-arrow-left-right me-1"></i>
                                            Pindahkan

                                        </button>

                                    </div>

                                </div>

                                <span class="badge {{ $kamar->badge_kapasitas }} fs-6 px-3 py-2">

                                    {{ $kamar->jumlah_wbp }}/{{ $kamar->kapasitas }} WBP

                                </span>

                            </div>

                        </div>

                        <div class="card-body p-0">

                            @if (count($kamar->wbps))
                                <div class="table-responsive">

                                    <table class="table table-sm mb-0 align-middle">

                                        <thead class="table-light">

                                            <tr>

                                                <th style="width:50px;"></th>

                                                <th style="width:65%;">
                                                    Nama WBP
                                                </th>

                                                <th>
                                                    Perkara
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @foreach ($kamar->wbps as $wbp)
                                                <tr class="wbp-item">

                                                    <td class="text-center">

                                                        <input type="checkbox"
                                                            class="form-check-input select-box wbp-check"
                                                            value="{{ $wbp['id'] ?? '' }}">

                                                    </td>

                                                    <td class="fw-semibold">
                                                        {{ $wbp['nama'] }}
                                                    </td>

                                                    <td>
                                                        {{ $wbp['jenis_kejahatan'] }}
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>
                            @else
                                <div class="text-center text-muted py-3">

                                    Tidak ada WBP.

                                </div>
                            @endif

                        </div>

                    </div>
                @endforeach
            @endforeach

            {{-- ================= LUAR TEMBOK ================= --}}

            <div id="section-detail-rs">

                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-danger text-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h6 class="fw-bold mb-0">
                                RUMAH SAKIT
                            </h6>

                            <span class="badge bg-light text-danger">
                                {{ $wbpRs->count() }} WBP
                            </span>

                        </div>

                    </div>

                    <div class="card-body p-0">

                        @if ($wbpRs->count())

                            <div class="table-responsive">

                                <table class="table table-sm mb-0 align-middle">

                                    <thead class="table-light">

                                        <tr>

                                            <th style="width:45%;">
                                                Nama WBP
                                            </th>

                                            <th>
                                                Perkara
                                            </th>

                                            <th class="text-center" style="width:160px;">
                                                Aksi
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($wbpRs as $wbp)
                                            <tr>

                                                <td class="fw-semibold">
                                                    {{ $wbp['nama'] }}
                                                </td>

                                                <td>
                                                    {{ $wbp['jenis_kejahatan'] }}
                                                </td>

                                                <td class="text-center">

                                                    <button type="button" class="btn btn-warning btn-sm fw-semibold"
                                                        onclick="bukaModalPindah(
                                                        this,
                                                        '{{ $wbp['kamar_id'] }}',
                                                        '{{ $wbp['lokasi_blok'] }}',
                                                        '{{ $wbp['lokasi_sel'] }}'
                                                    )">

                                                        <i class="bi bi-arrow-left-right me-1"></i>
                                                        Pindahkan

                                                    </button>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                Tidak ada WBP di Rumah Sakit.
                            </div>

                        @endif

                    </div>

                </div>

            </div>

            <div id="section-detail-bon">

                <div class="card shadow-sm mb-3">

                    <div class="card-header bg-primary text-white">

                        <div class="d-flex justify-content-between align-items-center">

                            <h6 class="fw-bold mb-0">
                                BON
                            </h6>

                            <span class="badge bg-light text-primary">
                                {{ $wbpBon->count() }} WBP
                            </span>

                        </div>

                    </div>

                    <div class="card-body p-0">

                        @if ($wbpBon->count())

                            <div class="table-responsive">

                                <table class="table table-sm mb-0 align-middle">

                                    <thead class="table-light">

                                        <tr>

                                            <th style="width:45%;">
                                                Nama WBP
                                            </th>

                                            <th>
                                                Perkara
                                            </th>

                                            <th class="text-center" style="width:160px;">
                                                Aksi
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($wbpBon as $wbp)
                                            <tr>

                                                <td class="fw-semibold">
                                                    {{ $wbp['nama'] }}
                                                </td>

                                                <td>
                                                    {{ $wbp['jenis_kejahatan'] }}
                                                </td>

                                                <td class="text-center">

                                                    <button type="button" class="btn btn-warning btn-sm fw-semibold"
                                                        onclick="bukaModalPindah(
                                                this,
                                                '{{ $wbp['kamar_id'] }}',
                                                '{{ $wbp['lokasi_blok'] }}',
                                                '{{ $wbp['lokasi_sel'] }}'
                                            )">

                                                        <i class="bi bi-arrow-left-right me-1"></i>
                                                        Pindahkan

                                                    </button>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>
                        @else
                            <div class="text-center text-muted py-3">
                                Tidak ada WBP BON.
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ================= MODAL PINDAH ================= -->
    <div class="modal fade" id="modalPindah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">Pindahkan WBP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="kamarAsalId">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kamar Asal</label>
                        <input type="text" id="kamarAsalNama" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah WBP</label>
                        <input type="text" id="jumlahWbp" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kamar Tujuan</label>

                        <select id="kamarTujuan" class="form-select">
                            <option value="">-- Pilih Kamar Tujuan --</option>

                            @foreach ($grouped as $blok => $kamars)
                                <optgroup label="📍 BLOK {{ strtoupper(explode('-', $blok)[0]) }}">
                                    @foreach ($kamars as $k)
                                        <option value="{{ $k->kamar_id }}">
                                            BLOK {{ $k->kode_blok }} - {{ $k->lokasi_sel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach

                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                    <button type="button" class="btn btn-success" onclick="pindahkanData()">
                        Pindahkan Sekarang
                    </button>
                </div>

            </div>

        </div>
    </div>

    <div id="quickDock" class="no-print">

        <div class="dock-section">

            <div class="dock-title">
                🏢 Dalam Tembok
            </div>

            @php
                $inside = [
                    'A' => 12,
                    'B' => 12,
                    'C' => 12,
                    'D' => 12,
                    'E' => 9,
                    'F' => 9,
                    'G' => 10,
                    'H' => 12,
                    'DAPUR' => 1,
                    'ISOLASI' => 10,
                ];
            @endphp

            @foreach ($inside as $blok => $jumlah)
                <div class="dock-item" data-block="{{ $blok }}" data-room-count="{{ $jumlah }}">

                    @switch($blok)
                        @case('DAPUR')
                            <span>Dapur</span>
                        @break

                        @case('ISOLASI')
                            🔒 <span>Isolasi</span>
                        @break

                        @default
                            <span>{{ $blok }}</span>
                    @endswitch

                    <div class="dock-popup"></div>

                </div>
            @endforeach

        </div>

        {{-- ================= LUAR TEMBOK ================= --}}
        <div class="dock-section ms-4">

            <div class="dock-title">
                🌐 Luar Tembok
            </div>

            <div class="dock-item dock-link" data-target="section-rs">
                🏥 <span>RS</span>
            </div>

            <div class="dock-item dock-link" data-target="section-bon">
                📄 <span>BON</span>
            </div>

        </div>

    </div>

    <script>
        // =======================
        // TOGGLE VIEW CARD/DETAILS
        // =======================

        const btnCardView = document.getElementById("btnCardView");
        const btnDetailView = document.getElementById("btnDetailView");

        const cardView = document.getElementById("cardView");
        const detailView = document.getElementById("detailView");

        if (btnCardView && btnDetailView && cardView && detailView) {

            function setView(mode) {

                if (mode === "detail") {

                    cardView.style.display = "none";
                    detailView.style.display = "";

                    btnDetailView.classList.add("active");
                    btnCardView.classList.remove("active");

                } else {

                    cardView.style.display = "";
                    detailView.style.display = "none";

                    btnCardView.classList.add("active");
                    btnDetailView.classList.remove("active");

                }
            }

            btnCardView.addEventListener("click", function() {
                setView("card");
            });

            btnDetailView.addEventListener("click", function() {
                setView("detail");
            });

            // =========================
            // RESTORE SETELAH RELOAD
            // =========================

            const savedView = sessionStorage.getItem("mutasiViewMode");

            if (savedView) {
                setView(savedView);
                sessionStorage.removeItem("mutasiViewMode");
            }

        }

        // =========================
        // RESTORE POSISI SCROLL
        // =========================

        document.addEventListener("DOMContentLoaded", function() {

            const savedScroll = sessionStorage.getItem("mutasiScrollY");

            if (savedScroll !== null) {

                sessionStorage.removeItem("mutasiScrollY");

                requestAnimationFrame(function() {

                    window.scrollTo({
                        top: parseInt(savedScroll, 10),
                        left: 0,
                        behavior: "instant"
                    });

                });

            }

        });

        let currentCard = null;

        // =====================
        // UPDATE JUMLAH TERPILIH PER KAMAR
        // =====================
        function updateSelected(card) {
            if (!card) return;
            const total = card.querySelectorAll(".wbp-check:checked").length;
            const counter = card.querySelector(".selectedCount");
            if (counter) {
                counter.textContent = total;
            }
        }

        // =====================
        // SELECT ALL
        // =====================
        function selectKamar(button) {
            const card = button.closest(".kamar-card");
            card.querySelectorAll(".wbp-item").forEach(function(item) {
                item.classList.add("selected");
                item.querySelector(".wbp-check").checked = true;
            });

            updateSelected(card);

        }

        // =====================
        // UNSELECT ALL
        // =====================
        function unselectKamar(button) {
            const card = button.closest(".kamar-card");
            card.querySelectorAll(".wbp-item").forEach(function(item) {
                item.classList.remove("selected");
                item.querySelector(".wbp-check").checked = false;

            });

            updateSelected(card);

        }

        /* =====================
        PRINT WBP TERPILIH
        ===================== */
        function printSelected() {

            const checked = document.querySelectorAll(".wbp-check:checked");

            if (checked.length === 0) {
                alert("Pilih minimal satu WBP yang akan dicetak.");
                return;
            }

            // Simpan elemen yang disembunyikan
            const hiddenElements = [];

            // 1. Sembunyikan seluruh kartu WBP yang tidak dipilih
            document.querySelectorAll(".print-card").forEach(function(card) {

                const checkbox = card.querySelector(".wbp-check");

                if (!checkbox.checked) {

                    card.style.display = "none";
                    hiddenElements.push(card);

                }

            });

            // 2. Sembunyikan kamar yang tidak memiliki WBP terpilih
            document.querySelectorAll(".kamar-card").forEach(function(kamar) {

                const selected = kamar.querySelectorAll(".wbp-check:checked").length;

                if (selected === 0) {

                    kamar.style.display = "none";
                    hiddenElements.push(kamar);

                }

            });

            // 3. Sembunyikan blok yang tidak memiliki kamar tampil
            document.querySelectorAll(".blok-card").forEach(function(blok) {

                const kamarVisible = [...blok.querySelectorAll(".kamar-card")]
                    .some(k => k.style.display !== "none");

                if (!kamarVisible) {

                    blok.style.display = "none";
                    hiddenElements.push(blok);

                }

            });

            // Cetak
            window.print();

            // Kembalikan tampilan semula
            hiddenElements.forEach(function(el) {
                el.style.display = "";
            });

        }

        // =====================
        // OPEN MODAL
        // =====================
        function bukaModalPindah(button, idKamar, blok, lokasiSel) {
            currentCard = button.closest(".kamar-card");
            const checked = currentCard.querySelectorAll(".wbp-check:checked");
            if (checked.length === 0) {
                alert("Pilih minimal satu WBP");
                return;
            }
            document.getElementById("kamarAsalId").value = idKamar;
            document.getElementById("kamarAsalNama").value = "BLOK " + blok + " - " + lokasiSel;
            document.getElementById("jumlahWbp").value = checked.length + " Orang";
            document.getElementById("kamarTujuan").value = "";

            new bootstrap.Modal(
                document.getElementById("modalPindah")
            ).show();
        }

        // =====================
        // PINDAHKAN DATA
        // =====================
        async function pindahkanData() {

            const kamarAsal = document.getElementById("kamarAsalId").value;
            const kamarTujuan = document.getElementById("kamarTujuan").value;

            if (!kamarTujuan) {
                alert("Pilih kamar tujuan");
                return;
            }

            if (kamarAsal === kamarTujuan) {
                alert("Kamar tujuan tidak boleh sama");
                return;
            }

            const ids = [];

            currentCard.querySelectorAll(".wbp-check:checked")
                .forEach(i => ids.push(i.value));

            if (ids.length === 0) {
                alert("Pilih minimal satu WBP");
                return;
            }

            const csrf = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            try {

                const res = await fetch("/admin-banceuy/wbp-kamar-update", {

                    method: "POST",

                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrf,
                        "X-Requested-With": "XMLHttpRequest"
                    },

                    body: JSON.stringify({
                        kamar_id: kamarTujuan,
                        wbp_ids: ids
                    })

                });

                if (!res.ok) {

                    const txt = await res.text();
                    console.error(txt);
                    alert("HTTP Error " + res.status);
                    return;

                }

                const data = await res.json();

                if (data.success) {
                    alert(data.message);

                    bootstrap.Modal
                        .getInstance(document.getElementById("modalPindah"))
                        .hide();

                    // =========================
                    // SIMPAN STATE SEBELUM RELOAD
                    // =========================

                    // Simpan view yang sedang aktif
                    const currentView = detailView.style.display !== "none" ?
                        "detail" :
                        "card";

                    sessionStorage.setItem(
                        "mutasiViewMode",
                        currentView
                    );

                    // Simpan posisi scroll
                    sessionStorage.setItem(
                        "mutasiScrollY",
                        window.scrollY
                    );

                    location.reload();

                } else {
                    alert(data.message || "Gagal pindah");
                }

            } catch (err) {
                console.error(err);
                alert("Server error");
            }

        }

        // =====================
        // SEARCH BLOK / KAMAR
        // =====================
        document.getElementById("searchKamar").addEventListener("input", function() {

            const keyword = this.value
                .toUpperCase()
                .replace(/[^A-Z0-9]/g, "");

            // Filter setiap kamar
            document.querySelectorAll(".kamar-card").forEach(function(card) {

                const target = card.dataset.search
                    .toUpperCase()
                    .replace(/[^A-Z0-9]/g, "");

                if (keyword === "" || target.includes(keyword)) {

                    card.style.display = "";

                } else {

                    card.style.display = "none";

                }

            });

            // Tampilkan / sembunyikan blok
            document.querySelectorAll(".blok-card").forEach(function(blok) {

                const visibleRooms = blok.querySelectorAll(".kamar-card");

                let adaYangTampil = false;

                visibleRooms.forEach(function(room) {

                    if (room.style.display !== "none") {
                        adaYangTampil = true;
                    }

                });

                blok.style.display = adaYangTampil ? "" : "none";

            });

        });

        // =====================
        // CLICK CARD WBP
        // =====================
        document.querySelectorAll(".wbp-item").forEach(function(card) {

            card.addEventListener("click", function(e) {

                // Abaikan klik pada checkbox (kalau masih ada)
                if (e.target.classList.contains("wbp-check")) return;

                const checkbox = this.querySelector(".wbp-check");

                checkbox.checked = !checkbox.checked;

                this.classList.toggle("selected", checkbox.checked);

                updateSelected(this.closest(".kamar-card"));

            });

        });

        // =====================
        // EVENT CHECKBOX
        // =====================
        document.addEventListener("change", function(e) {
            if (e.target.classList.contains("wbp-check")) {
                const item = e.target.closest(".wbp-item");
                item.classList.toggle("selected", e.target.checked);
                updateSelected(item.closest(".kamar-card"));
            }

        });

        /* =====================================
                    FLOATING DOCK JS
        =====================================*/

        let activeHighlight = null;
        let highlightTimer = null;

        document.querySelectorAll(".dock-item[data-block]").forEach(function(item) {

            const popup = item.querySelector(".dock-popup");

            if (!popup) return;

            const block = item.dataset.block;
            const total = parseInt(item.dataset.roomCount);
            console.log({
                block,
                total,
                popup
            });

            popup.innerHTML = "";

            for (let i = 1; i <= total; i++) {

                const btn = document.createElement("div");

                btn.className = "popup-room";
                btn.textContent = i;

                btn.onclick = function(e) {
                    e.stopPropagation();
                    // Tentukan view yang sedang aktif
                    const prefix = (detailView.style.display !== "none") ?
                        "room-detail-" :
                        "room-card-";

                    let target;

                    if (block === "DAPUR") {

                        target = prefix + "DAPUR-1";

                    } else if (block === "ISOLASI") {

                        target = prefix + "ISOLASI-" + i;

                    } else {

                        target = prefix + block + "-" + i;

                    }

                    const card = document.getElementById(target);

                    if (!card) return;

                    // Active dock
                    document.querySelectorAll(".dock-item")
                        .forEach(d => d.classList.remove("active"));

                    item.classList.add("active");

                    // Hapus highlight sebelumnya
                    if (activeHighlight) {
                        activeHighlight.classList.remove("room-highlight");
                    }

                    activeHighlight = card;

                    card.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });

                    card.classList.add("room-highlight");

                    clearTimeout(highlightTimer);

                    highlightTimer = setTimeout(function() {
                        card.classList.remove("room-highlight");
                        item.classList.remove("active");
                        activeHighlight = null;
                    }, 1000);
                };
                popup.appendChild(btn);
            }

        });

        /* =====================================
                FLOATING DOCK RS / BON
        =====================================*/

        let activeSection = null;
        let sectionTimer = null;

        document.querySelectorAll(".dock-link").forEach(function(item) {

            item.addEventListener("click", function() {

                const target = document.getElementById(
                    item.dataset.target
                );

                if (!target) return;

                // Active dock
                document.querySelectorAll(".dock-item")
                    .forEach(i => i.classList.remove("active"));

                item.classList.add("active");

                // Hapus highlight sebelumnya
                if (activeSection) {
                    activeSection.classList.remove("section-highlight");
                }

                activeSection = target;

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

                target.classList.add("section-highlight");

                clearTimeout(sectionTimer);

                sectionTimer = setTimeout(function() {

                    target.classList.remove("section-highlight");

                    item.classList.remove("active");

                    activeSection = null;

                }, 1000);

            });

        });
    </script>

</body>

</html>
