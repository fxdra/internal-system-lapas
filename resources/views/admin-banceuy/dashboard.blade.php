@extends('admin-banceuy.partisi.main')

@section('content')
    {{-- JQuery UI Datepicker --}}
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <style>
        .card-dashboard {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
            overflow: hidden;
            background: #fff;
        }

        .card-dashboard .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            font-weight: 700;
            padding: 16px 20px;
        }

        .stat-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
            background: #fff;
            transition: .2s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #6c757d;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .stat-value {
            font-size: 34px;
            font-weight: 800;
            line-height: 1;
        }

        .mini-muted {
            font-size: 12px;
            color: #6c757d;
        }

        .badge-status-open {
            background: rgba(25, 135, 84, .12);
            color: #198754;
            font-weight: 700;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
        }

        .badge-status-close {
            background: rgba(220, 53, 69, .12);
            color: #dc3545;
            font-weight: 700;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
        }

        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            background: #f8f9fa;
            color: #6c757d;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive table {
            min-width: 700px;
            white-space: nowrap;
        }

        /* ===================== RINGKASAN HUNIAN ===================== */

        .occupancy-section {
            margin-bottom: 24px;
        }

        .occupancy-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 14px;
        }

        .occupancy-title {
            font-size: 15px;
            font-weight: 800;
            color: #212529;
            margin-bottom: 2px;
        }

        .occupancy-subtitle {
            font-size: 12px;
            color: #6c757d;
        }

        .occupancy-card {
            border: 0;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
            padding: 16px;
            height: 100%;
            transition: .2s ease;
        }

        .occupancy-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
        }

        .occupancy-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .occupancy-block-name {
            font-size: 13px;
            font-weight: 800;
            color: #212529;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .occupancy-block-info {
            display: flex;
            align-items: center;
            gap: 7px;
            min-width: 0;
        }

        .occupancy-room-count {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 10px;
            font-weight: 600;
            color: #6c757d;
            white-space: nowrap;
        }

        .occupancy-room-count::before {
            content: "";
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #adb5bd;
            flex-shrink: 0;
        }

        .occupancy-status {
            font-size: 10px;
            font-weight: 800;
            padding: 5px 8px;
            border-radius: 999px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .occupancy-status-success {
            color: #198754;
            background: rgba(25, 135, 84, .12);
        }

        .occupancy-status-warning {
            color: #997404;
            background: rgba(255, 193, 7, .18);
        }

        .occupancy-status-danger {
            color: #dc3545;
            background: rgba(220, 53, 69, .12);
        }

        .occupancy-value {
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
            color: #212529;
        }

        .occupancy-value span {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
        }

        .occupancy-progress {
            height: 7px;
            background: #e9ecef;
            border-radius: 999px;
            overflow: hidden;
            margin: 14px 0 10px;
        }

        .occupancy-progress-bar {
            height: 100%;
            border-radius: 999px;
        }

        .occupancy-progress-success {
            background: #198754;
        }

        .occupancy-progress-warning {
            background: #ffc107;
        }

        .occupancy-progress-danger {
            background: #dc3545;
        }

        .occupancy-status-orange {
            color: #b54708;
            background: rgba(253, 126, 20, .15);
        }

        .occupancy-progress-orange {
            background: #fd7e14;
        }

        .occupancy-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            color: #6c757d;
        }

        .occupancy-meta strong {
            color: #212529;
        }

        .special-unit-card {
            border-left: 4px solid #212529;
        }

        .stat-breakdown {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px 12px;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid rgba(0, 0, 0, .06);
            font-size: 11px;
            color: #6c757d;
        }

        .stat-breakdown-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: #6c757d;
            white-space: nowrap;
        }

        .stat-breakdown-item strong {
            color: #212529;
            font-weight: 800;
        }

        .stat-breakdown-divider {
            width: 1px;
            height: 14px;
            background: rgba(0, 0, 0, .10);
        }

        /* ======= BREAKDOWN KAMAR TERTUTUP ======= */

        .stat-breakdown-closed {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 7px;
        }

        .stat-breakdown-closed .stat-breakdown-item {
            display: grid;
            grid-template-columns: 90px 10px minmax(0, 1fr);
            align-items: center;
            gap: 4px;
            width: 100%;
            min-width: 0;
        }

        .stat-breakdown-closed .stat-breakdown-item span {
            min-width: 0;
            white-space: nowrap;
        }

        .stat-breakdown-closed .stat-breakdown-dot {
            width: 10px;
            text-align: center;
            font-style: normal;
            color: #adb5bd;
            line-height: 1;
            transform: translateY(-2px);
        }

        .stat-breakdown-closed .stat-breakdown-item strong {
            min-width: 0;
            text-align: left;
            white-space: normal;
            overflow: visible;
            text-overflow: unset;
            line-height: 1.4;
        }

        /* ===================== PERHATIAN OPERASIONAL ===================== */

        .operational-panel {
            overflow: hidden;
        }

        .operational-toggle {
            width: 100%;
            border: 0;
            background: #fff;
            padding: 16px 20px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            text-align: left;
            cursor: pointer;
        }

        .operational-toggle:hover {
            background: #fafafa;
        }

        .operational-heading {
            display: flex;
            align-items: center;
            gap: 10px;

            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #212529;
        }

        .operational-chevron {
            width: 22px;
            height: 22px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #6c757d;
            transition: transform .2s ease;
        }

        .operational-toggle[aria-expanded="false"] .operational-chevron {
            transform: rotate(-90deg);
        }

        .operational-count {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;

            padding: 5px 9px;
            border-radius: 999px;

            background: #f1f3f5;
            color: #6c757d;

            white-space: nowrap;
        }

        .operational-list {
            border-top: 1px solid rgba(0, 0, 0, .06);
        }

        .operational-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;

            padding: 14px 20px;

            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .operational-item:last-child {
            border-bottom: 0;
        }

        .operational-indicator {
            width: 8px;
            height: 8px;

            margin-top: 5px;

            border-radius: 50%;
            flex-shrink: 0;
        }

        .operational-indicator-danger {
            background: #dc3545;
        }

        .operational-indicator-orange {
            background: #fd7e14;
        }

        .operational-indicator-warning {
            background: #ffc107;
        }

        .operational-indicator-info {
            background: #0d6efd;
        }

        .operational-content {
            min-width: 0;
        }

        .operational-title {
            font-size: 13px;
            font-weight: 800;
            color: #212529;

            text-transform: uppercase;
            letter-spacing: .2px;
        }

        .operational-message {
            margin-top: 3px;

            font-size: 12px;
            color: #6c757d;
        }

        .operational-empty {
            padding: 20px;

            font-size: 13px;
            color: #6c757d;

            text-align: center;
        }

        @media (max-width: 576px) {

            .operational-toggle {
                padding: 14px 16px;
            }

            .operational-item {
                padding: 13px 16px;
            }

            .operational-heading {
                font-size: 12px;
            }

            .operational-count {
                font-size: 9px;
            }
        }

        @media (max-width: 575.98px) {
            .occupancy-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .occupancy-value {
                font-size: 24px;
            }
        }

        /* ===================== SUMMARY KAMAR TERTUTUP ===================== */

        /* Summary running text hanya untuk mobile */
        .stat-closed-summary {
            display: none;
        }

        /* MOBILE DASHBOARD */
        @media (max-width: 575.98px) {

            /* ================= HEADER ================= */
            .container-fluid.py-3>.d-flex.justify-content-between.align-items-center {
                align-items: flex-start !important;
                gap: 10px;

                margin-bottom: 18px !important;
            }

            .container-fluid.py-3>.d-flex.justify-content-between.align-items-center>div:first-child {
                min-width: 0;
                flex: 1 1 auto;
            }

            .container-fluid.py-3 h4 {
                font-size: 18px;
                line-height: 1.25;
                margin-bottom: 4px !important;
            }

            .container-fluid.py-3 .mini-muted {
                font-size: 11px;
            }

            /* ================= BUTTON HEADER ================= */
            .container-fluid.py-3>.d-flex.justify-content-between.align-items-center>.d-flex.gap-2 {
                flex: 0 0 auto;

                gap: 5px !important;
            }

            .container-fluid.py-3>.d-flex.justify-content-between.align-items-center>.d-flex.gap-2 .btn {
                width: 62px;
                min-width: 62px;
                height: 34px;

                padding: 5px 6px;

                font-size: 9px;
                line-height: 1;

                border-radius: 5px;
            }

            /* ================= STAT GRID ================= */
            .container-fluid.py-3 .row.row-cols-2 {
                --bs-gutter-x: 10px;
                --bs-gutter-y: 10px;

                margin-left: -5px;
                margin-right: -5px;

                align-items: flex-start;
            }

            .container-fluid.py-3 .row.row-cols-2>.col {
                padding-left: 5px;
                padding-right: 5px;

                align-self: flex-start;

                height: auto;
            }

            /* ================= STAT CARD ================= */
            .container-fluid.py-3 .stat-card {
                height: auto;
                min-height: 0;

                padding: 12px !important;

                border-radius: 14px;
            }

            .container-fluid.py-3 .stat-card:hover {
                transform: none;
            }

            /* ================= SAMAKAN TINGGI KAMAR ================= */

            .container-fluid.py-3 .row.row-cols-2>.col:nth-child(3),
            .container-fluid.py-3 .row.row-cols-2>.col:nth-child(4) {
                align-self: stretch;
                display: flex;
            }

            .container-fluid.py-3 .row.row-cols-2>.col:nth-child(3)>.stat-card,
            .container-fluid.py-3 .row.row-cols-2>.col:nth-child(4)>.stat-card {
                width: 100%;
                height: 100%;
            }

            /* ================= TITLE ================= */
            .container-fluid.py-3 .stat-title {
                font-size: 10px;

                letter-spacing: .5px;

                margin-bottom: 6px;
            }

            /* ================= VALUE ================= */
            .container-fluid.py-3 .stat-value {
                font-size: 27px;

                line-height: 1;
            }

            /* ================= BREAKDOWN UMUM ================= */
            .container-fluid.py-3 .stat-breakdown {
                gap: 5px 8px;

                margin-top: 10px;
                padding-top: 8px;

                font-size: 10px;
            }

            .container-fluid.py-3 .stat-breakdown-item {
                gap: 4px;

                font-size: 10px;
            }

            .container-fluid.py-3 .stat-breakdown-item strong {
                font-size: 10px;
            }

            .container-fluid.py-3 .stat-breakdown-divider {
                height: 12px;
            }

            /* ================= KAMAR TERTUTUP ================= */

            .container-fluid.py-3 .stat-card-closed {
                position: relative;
                cursor: pointer;
            }

            /* ================= SUMMARY RUNNING TEXT ================= */

            .container-fluid.py-3 .stat-closed-summary {
                display: block;
                width: 100%;

                margin-top: 9px;
                padding-top: 8px;

                border-top: 1px solid rgba(0, 0, 0, .06);

                overflow: hidden;
            }

            .container-fluid.py-3 .stat-closed-marquee {
                width: 100%;
                overflow: hidden;
                white-space: nowrap;
            }

            .container-fluid.py-3 .stat-closed-marquee-track {
                display: inline-flex;
                align-items: center;

                width: max-content;

                gap: 5px;

                animation: statClosedMarquee 15s linear infinite;
            }

            .container-fluid.py-3 .stat-closed-marquee-track span {
                font-size: 8px;
                line-height: 1.2;

                color: #6c757d;

                white-space: nowrap;
            }

            .container-fluid.py-3 .stat-closed-marquee-track i {
                font-size: 8px;
                font-style: normal;

                color: #adb5bd;
            }

            /* ANIMASI RUNNING TEXT */

            @keyframes statClosedMarquee {
                from {
                    transform: translateX(0);
                }

                to {
                    transform: translateX(-50%);
                }
            }


            /* ================= DETAIL ================= */

            /* Detail disembunyikan */
            .container-fluid.py-3 .stat-card-closed .stat-breakdown-closed {
                display: none;
            }

            /* Detail saat card dibuka */
            .container-fluid.py-3 .stat-card-closed.is-open {
                min-height: 0;
            }

            .container-fluid.py-3 .stat-card-closed.is-open .stat-breakdown-closed {
                display: flex;

                flex-direction: column;

                align-items: stretch;

                gap: 5px;

                margin-top: 9px;
            }


            /* ================= ITEM DETAIL ================= */

            .container-fluid.py-3 .stat-card-closed.is-open .stat-breakdown-item {
                display: grid;

                grid-template-columns: 62px 6px minmax(0, 1fr);

                align-items: start;

                gap: 2px;

                width: 100%;
                min-width: 0;
            }

            .container-fluid.py-3 .stat-card-closed.is-open .stat-breakdown-item span {
                min-width: 0;

                font-size: 7.5px;
                line-height: 1.2;

                white-space: normal;
                overflow-wrap: anywhere;
            }

            .container-fluid.py-3 .stat-card-closed.is-open .stat-breakdown-dot {
                width: auto;

                font-size: 7px;
                line-height: 1.2;

                text-align: center;

                transform: none;
            }

            .container-fluid.py-3 .stat-card-closed.is-open .stat-breakdown-item strong {
                min-width: 0;

                font-size: 8.5px;
                line-height: 1.25;

                text-align: left;

                white-space: normal;

                overflow-wrap: anywhere;
                word-break: normal;
            }

            /* ================= OCCUPANCY ================= */

            .container-fluid.py-3 .occupancy-card {
                padding: 14px;
                border-radius: 14px;
                min-width: 0;
            }

            .container-fluid.py-3 .occupancy-card-header {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                align-items: start;
                gap: 8px;

                margin-bottom: 12px;
            }

            /* ================= NAMA BLOK + JUMLAH KAMAR ================= */

            .container-fluid.py-3 .occupancy-block-info {
                min-width: 0;

                display: flex;
                flex-direction: column;
                align-items: flex-start;

                gap: 4px;
            }

            .container-fluid.py-3 .occupancy-block-name {
                font-size: 12px;
                line-height: 1.2;

                letter-spacing: .3px;

                white-space: nowrap;
            }

            .container-fluid.py-3 .occupancy-room-count {
                font-size: 9px;
                line-height: 1.2;

                gap: 5px;

                white-space: nowrap;
            }

            .container-fluid.py-3 .occupancy-room-count::before {
                width: 3px;
                height: 3px;
            }

            /* ================= STATUS ================= */

            .container-fluid.py-3 .occupancy-status {
                max-width: 72px;

                padding: 5px 7px;

                font-size: 8px;
                line-height: 1.15;

                text-align: center;

                white-space: normal;
                overflow-wrap: anywhere;

                border-radius: 999px;
            }

            /* ================= VALUE ================= */

            .container-fluid.py-3 .occupancy-value {
                font-size: 23px;
                line-height: 1;
            }

            /* angka kapasitas */
            .container-fluid.py-3 .occupancy-value span {
                font-size: 11px;
            }

            /* ================= PROGRESS ================= */

            .container-fluid.py-3 .occupancy-progress {
                height: 6px;

                margin: 12px 0 9px;
            }

            /* ================= META ================= */

            .container-fluid.py-3 .occupancy-meta {
                gap: 5px;

                font-size: 9px;

                white-space: nowrap;
            }

            .container-fluid.py-3 .occupancy-meta strong {
                font-size: 9px;
            }


            /* ================= UNIT KHUSUS ================= */
            .container-fluid.py-3 .special-unit-card {
                width: 100%;
                min-width: 0;

                padding: 14px !important;

                border-radius: 14px;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-card-header {
                display: grid;

                grid-template-columns: minmax(0, 1fr) auto;

                align-items: start;

                gap: 6px;

                margin-bottom: 12px;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-block-info {
                min-width: 0;

                display: flex;
                flex-direction: column;
                align-items: flex-start;

                gap: 4px;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-block-name {
                font-size: 11px;
                line-height: 1.2;

                white-space: nowrap;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-room-count {
                font-size: 8px;
                line-height: 1.2;

                white-space: nowrap;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-status {
                max-width: 65px;

                padding: 5px 6px;

                font-size: 7px;
                line-height: 1.15;

                text-align: center;

                white-space: normal;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-value {
                font-size: 22px;
                line-height: 1;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-value span {
                font-size: 10px;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-progress {
                height: 6px;

                margin: 11px 0 8px;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-meta {
                width: 100%;

                gap: 4px;

                font-size: 8px;

                white-space: nowrap;
            }

            .container-fluid.py-3 .special-unit-card .occupancy-meta strong {
                font-size: 8px;

                white-space: nowrap;
            }

        }
    </style>

    <div class="container-fluid py-3">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Dashboard Lapas Kelas IIA Banceuy</h4>
                <div class="mini-muted">Monitoring WBP & Kamar</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ url('/admin-banceuy/laporan-data-wbp') }}" class="btn btn-outline-dark btn-sm">👤 WBP</a>
                <a href="{{ url('/admin-banceuy/laporan-data-kamar') }}" class="btn btn-outline-primary btn-sm">🏠 Kamar</a>
            </div>
        </div>

        {{-- ===================== STAT WBP + KAMAR ===================== --}}
        <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">

            <div class="col">
                <div class="card stat-card p-3">
                    <div class="stat-title">WBP Aktif</div>

                    <div class="stat-value">
                        {{ $totalWbpAktif }}
                    </div>

                    <div class="stat-breakdown">

                        <div class="stat-breakdown-item">
                            <span>Narapidana</span>
                            <strong>{{ $totalNarapidanaAktif }}</strong>
                        </div>

                        <div class="stat-breakdown-divider"></div>

                        <div class="stat-breakdown-item">
                            <span>Tahanan</span>
                            <strong>{{ $totalTahananAktif }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card stat-card p-3">
                    <div class="stat-title">Luar Tembok</div>

                    <div class="stat-value text-warning">
                        {{ $totalWbpLuarTembok }}
                    </div>

                    <div class="stat-breakdown">

                        <div class="stat-breakdown-item">
                            <span>BON</span>
                            <strong>{{ $totalWbpBon }}</strong>
                        </div>

                        <div class="stat-breakdown-divider"></div>

                        <div class="stat-breakdown-item">
                            <span>Sakit</span>
                            <strong>{{ $totalWbpSakit }}</strong>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card stat-card p-3">
                    <div class="stat-title">Kamar Terbuka</div>
                    <div class="stat-value text-success">
                        {{ $totalKamarTerbuka }}
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card stat-card stat-card-closed p-3">

                    <div class="stat-title">
                        Kamar Tertutup
                    </div>

                    <div class="stat-value text-danger">
                        {{ $totalKamarTertutup }}
                    </div>

                    {{-- SUMMARY KHUSUS MOBILE --}}
                    <div class="stat-closed-summary">

                        <div class="stat-closed-marquee">

                            <div class="stat-closed-marquee-track">

                                {{-- LOOP PERTAMA --}}
                                @forelse ($kamarTertutupBreakdown as $group)
                                    <span>
                                        {{ $group['nama'] }} {{ $group['kamar']->count() }}
                                    </span>

                                    <i>•</i>

                                @empty

                                    <span>Tidak ada kamar tertutup</span>
                                @endforelse


                                {{-- DUPLIKASI UNTUK RUNNING TEXT --}}
                                @foreach ($kamarTertutupBreakdown as $group)
                                    <span>
                                        {{ $group['nama'] }} {{ $group['kamar']->count() }}
                                    </span>

                                    <i>•</i>
                                @endforeach

                            </div>

                        </div>

                    </div>


                    {{-- DETAIL BREAKDOWN --}}
                    <div class="stat-breakdown stat-breakdown-closed">

                        @forelse ($kamarTertutupBreakdown as $group)
                            <div class="stat-breakdown-item">

                                <span>
                                    {{ $group['nama'] }}
                                </span>

                                <i class="stat-breakdown-dot">•</i>

                                <strong>

                                    @if (in_array($group['nama'], ['MAXIMUM', 'SEL ISOLASI']))
                                        {{ $group['kamar']->map(function ($kamar) {
                                                preg_match('/(\d+)/', $kamar->lokasi_sel ?? '', $match);
                                                return $match[1] ?? '-';
                                            })->implode(', ') }}
                                    @else
                                        {{ $group['kamar']->map(function ($kamar) {
                                                preg_match('/(\d+)/', $kamar->lokasi_sel ?? '', $match);
                                                return ($kamar->kode_blok ?? '-') . '-' . ($match[1] ?? '-');
                                            })->implode(', ') }}
                                    @endif

                                </strong>

                            </div>

                        @empty

                            <div class="stat-breakdown-item">
                                <span>Tidak ada kamar tertutup</span>
                            </div>
                        @endforelse

                    </div>

                </div>
            </div>

            <div class="col">
                <div class="card stat-card p-3">
                    <div class="stat-title">Total Kamar</div>
                    <div class="stat-value">
                        {{ $totalKamar }}
                    </div>
                </div>
            </div>

        </div>

        {{-- ===================== PERHATIAN OPERASIONAL ===================== --}}
        <div class="row mb-4">
            <div class="col-12">

                <div class="card card-dashboard operational-panel">

                    <button class="operational-toggle" type="button" data-bs-toggle="collapse"
                        data-bs-target="#operationalAlerts" aria-expanded="true" aria-controls="operationalAlerts">
                        <div class="operational-heading">

                            <span class="operational-chevron">
                                ▼
                            </span>

                            <span>Perhatian Operasional</span>

                        </div>

                        <span class="operational-count">
                            {{ $operationalAlerts->count() }} Perhatian
                        </span>

                    </button>

                    <div id="operationalAlerts" class="collapse show">
                        <div class="operational-list">

                            @forelse($operationalAlerts as $alert)
                                <div class="operational-item">

                                    <span
                                        class="
                                        operational-indicator
                                        operational-indicator-{{ $alert['level'] }}
                                    "></span>

                                    <div class="operational-content">

                                        <div class="operational-title">
                                            {{ $alert['title'] }}
                                        </div>

                                        <div class="operational-message">
                                            {{ $alert['message'] }}
                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="operational-empty">
                                    Tidak ada perhatian operasional saat ini.
                                </div>
                            @endforelse

                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- ===================== RINGKASAN HUNIAN ===================== --}}
        <div class="occupancy-section">

            <div class="occupancy-header">
                <div>
                    <div class="occupancy-title">Ringkasan Hunian</div>
                    <div class="occupancy-subtitle">
                        Monitoring kapasitas blok hunian dan unit khusus
                    </div>
                </div>
            </div>

            {{-- BLOK HUNIAN --}}
            <div class="row g-3 mb-3">

                @foreach ($summaryBlok as $item)
                    <div class="col-6 col-md-3">

                        <div class="occupancy-card">

                            <div class="occupancy-card-header">

                                <div class="occupancy-block-info">
                                    <div class="occupancy-block-name">
                                        {{ $item['blok'] }}
                                    </div>

                                    <div class="occupancy-room-count">
                                        {{ $item['jumlah_kamar'] }} Kamar
                                    </div>
                                </div>

                                <span
                                    class="
                                occupancy-status
                                occupancy-status-{{ $item['warna'] }}">
                                    {{ $item['status'] }}
                                </span>

                            </div>

                            <div class="occupancy-value">
                                {{ $item['jumlah_wbp'] }}
                                <span>/ {{ $item['kapasitas'] }} WBP</span>
                            </div>

                            <div class="occupancy-progress">

                                <div class="
                                    occupancy-progress-bar
                                    occupancy-progress-{{ $item['warna'] }}
                                "
                                    style="width: {{ min($item['persen'], 100) }}%">
                                </div>

                            </div>

                            <div class="occupancy-meta">

                                <span>
                                    {{ $item['persen'] }}% terisi
                                </span>

                                <strong>
                                    @if ($item['over'] > 0)
                                        Over {{ $item['over'] }}
                                    @elseif($item['sisa'] === 0)
                                        Penuh
                                    @else
                                        Sisa {{ $item['sisa'] }}
                                    @endif
                                </strong>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>


            {{-- UNIT KHUSUS --}}
            <div class="occupancy-subtitle fw-bold text-uppercase mb-2">
                Unit Khusus
            </div>

            <div class="row g-3">

                @foreach ($summaryUnitKhusus as $item)
                    <div class="col-6 col-md-6">

                        <div class="occupancy-card special-unit-card">

                            <div class="occupancy-card-header">

                                <div class="occupancy-block-info">

                                    <div class="occupancy-block-name">
                                        {{ $item['blok'] }}
                                    </div>

                                    <div class="occupancy-room-count">
                                        {{ $item['jumlah_kamar'] }} Kamar
                                    </div>

                                </div>

                                <span
                                    class="
                        occupancy-status
                        occupancy-status-{{ $item['warna'] }}
                    ">
                                    {{ $item['status'] }}
                                </span>

                            </div>

                            <div class="occupancy-value">
                                {{ $item['jumlah_wbp'] }}
                                <span>/ {{ $item['kapasitas'] }} WBP</span>
                            </div>

                            <div class="occupancy-progress">

                                <div class="
                        occupancy-progress-bar
                        occupancy-progress-{{ $item['warna'] }}
                    "
                                    style="width: {{ min($item['persen'], 100) }}%">
                                </div>

                            </div>

                            <div class="occupancy-meta">

                                <span>
                                    {{ $item['persen'] }}% terisi
                                </span>

                                <strong>
                                    @if ($item['over'] > 0)
                                        Over {{ $item['over'] }}
                                    @elseif($item['sisa'] === 0)
                                        Penuh
                                    @else
                                        Sisa {{ $item['sisa'] }}
                                    @endif
                                </strong>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        {{-- ===================== GRAFIK KEJAHATAN ===================== --}}
        <div class="row g-3 mb-4">

            <div class="col-md-12">
                <div class="card card-dashboard">
                    <div class="card-header">⚖️ Jenis Kejahatan</div>
                    <div class="card-body">
                        <canvas id="chartKejahatan"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===================== KAMAR PER BLOK + STATUS ===================== --}}
        <div class="row g-3 mb-4">

            <!-- CARD KEDUA -->
            <div class="col-md-12">
                <div class="card card-dashboard h-100">

                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

                        <span>🔄 Perpindahan WBP per Blok</span>

                        <div class="d-flex align-items-center gap-2">

                            <select id="filterMutasi" class="form-select form-select-sm" style="width:160px">
                                <option value="" {{ empty($filter) ? 'selected' : '' }}>
                                    Semua
                                </option>

                                <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>
                                    Hari Ini
                                </option>

                                <option value="yesterday" {{ $filter == 'yesterday' ? 'selected' : '' }}>
                                    Kemarin
                                </option>

                                <option value="date" {{ $filter == 'date' ? 'selected' : '' }}>
                                    Pilih Tanggal
                                </option>
                            </select>

                            <input type="text" id="tanggalMutasi" class="form-control form-control-sm"
                                style="width:140px; {{ $filter == 'date' ? '' : 'display:none;' }}"
                                placeholder="yyyy-mm-dd" value="{{ $tanggal }}">

                        </div>

                    </div>

                    <div class="card-body p-0">

                        <table class="table table-sm table-hover mb-0 w-100">

                            <tbody>
                                @php
                                    $totalMutasi = 0;
                                @endphp

                                @foreach ($countMutasiPerBlok as $m)
                                    @php
                                        $totalMutasi += $m->total_mutasi;
                                    @endphp

                                    <tr>
                                        <td class="ps-3">
                                            {{ $m->lokasi_blok }}
                                        </td>

                                        <td class="text-end fw-bold pe-3" style="width:90px;">
                                            {{ $m->total_mutasi }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr class="table-light border-top">
                                    <th class="ps-3">
                                        Total
                                    </th>

                                    <th class="text-end pe-3">
                                        {{ $countMutasiPerBlok->sum('total_mutasi') }}
                                    </th>
                                </tr>
                            </tfoot>

                        </table>

                    </div>

                </div>
            </div>


        </div>

        {{-- CHART --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // ================= JENIS KEJAHATAN =================
            new Chart(document.getElementById('chartKejahatan'), {
                type: 'bar',
                data: {
                    labels: @json($byJenisKejahatan->pluck('jenis_kejahatan')),
                    datasets: [{
                        data: @json($byJenisKejahatan->pluck('total')),
                        backgroundColor: '#f6c23e'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: '#e9ecef'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>

        <script>
            $(function() {

                $("#tanggalMutasi").datepicker({
                    dateFormat: "yy-mm-dd",
                    changeMonth: true,
                    changeYear: true
                });

                if ($("#filterMutasi").val() === "date") {
                    $("#tanggalMutasi").show();
                }

                $("#filterMutasi").change(function() {

                    let filter = $(this).val();

                    if (filter === "") {
                        window.location.href = "{{ route('dashboard') }}";
                    }

                    if (filter === "today") {
                        window.location.href = "{{ route('dashboard') }}?filter=today";
                    }

                    if (filter === "yesterday") {
                        window.location.href = "{{ route('dashboard') }}?filter=yesterday";
                    }

                    if (filter === "date") {
                        $("#tanggalMutasi").show().focus();
                    }

                });

                $("#tanggalMutasi").on("change", function() {

                    let tanggal = $(this).val();

                    if (tanggal !== "") {
                        window.location.href =
                            "{{ route('dashboard') }}?filter=date&tanggal=" + tanggal;
                    }

                });

            });
        </script>

        {{-- MOBILE KAMAR TERTUTUP --}}
        <script>
            document.addEventListener('click', function(e) {

                const card = e.target.closest('.stat-card-closed');

                if (!card) return;

                // Hanya aktif di mobile
                if (window.innerWidth > 575.98) return;

                card.classList.toggle('is-open');

            });
        </script>

        <script>
            $(function() {

                $("#tanggalMutasi").datepicker({
                    dateFormat: "yy-mm-dd",
                    changeMonth: true,
                    changeYear: true
                });

                // Jika sebelumnya memilih tanggal
                if ($("#filterMutasi").val() === "date") {
                    $("#tanggalMutasi").show();
                }

                $("#filterMutasi").change(function() {

                    let filter = $(this).val();

                    if (filter === "") {
                        window.location.href = "{{ route('dashboard') }}";
                    }

                    if (filter === "today") {
                        window.location.href = "{{ route('dashboard') }}?filter=today";
                    }

                    if (filter === "yesterday") {
                        window.location.href = "{{ route('dashboard') }}?filter=yesterday";
                    }

                    if (filter === "date") {
                        $("#tanggalMutasi").show().focus();
                    }

                });

                $("#tanggalMutasi").on("change", function() {

                    let tanggal = $(this).val();

                    if (tanggal !== "") {
                        window.location.href = "{{ route('dashboard') }}?filter=date&tanggal=" + tanggal;
                    }

                });

            });
        </script>
    @endsection
