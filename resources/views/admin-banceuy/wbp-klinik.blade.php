@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        /* =========================================================
                                                                                                DATA WBP KLINIK
                                                                                                ========================================================= */

        /* HEADER */
        .klinik-header {
            padding-bottom: 4px;
        }

        .klinik-header h4 {
            color: #283144;
            font-size: 20px;
            letter-spacing: -0.2px;
        }

        .klinik-header small {
            font-size: 13px;
        }

        .klinik-total-badge {
            display: inline-flex;
            align-items: center;

            padding: 5px 10px;

            border-radius: 8px;

            background: rgba(52, 84, 209, 0.10);
            color: #3454d1;

            font-size: 12px;
            font-weight: 600;
        }

        /* =========================================================
                                                                                                STAT CARD
                                                                                                ========================================================= */

        .klinik-stat-card {
            height: 100%;

            padding: 20px;

            background: #ffffff;

            border: 1px solid #e9ecef;
            border-radius: 14px;

            transition: all .2s ease;
        }

        .klinik-stat-card:hover {
            transform: translateY(-2px);

            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        .klinik-stat-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .klinik-stat-title {
            color: #6c757d;

            font-size: 13px;
            font-weight: 500;

            margin-bottom: 8px;
        }

        .klinik-stat-value {
            color: #283144;

            font-size: 28px;
            font-weight: 700;

            line-height: 1.2;
        }

        .klinik-stat-description {
            color: #8a94a6;

            font-size: 12px;

            margin-top: 8px;
        }

        .klinik-stat-primary .klinik-stat-value {
            color: #3454d1;
        }

        .klinik-stat-success .klinik-stat-value {
            color: #198754;
        }

        .klinik-stat-warning .klinik-stat-value {
            color: #d99a00;
        }

        /* =========================================================
                                            SEARCH
                                            ========================================================= */

        .klinik-search {
            margin-bottom: 18px;
        }

        .klinik-search .form-control {
            min-height: 44px;

            border: 1px solid #dee2e6;
            border-radius: 10px 0 0 10px;

            font-size: 13px;

            box-shadow: none;

            transition: all .2s ease;
        }

        .klinik-search .form-control:focus {
            border-color: #3454d1;

            box-shadow: 0 0 0 3px rgba(52, 84, 209, .10);
        }

        .klinik-search .btn-primary {
            min-width: 80px;

            border-radius: 0 10px 10px 0;

            font-size: 13px;
            font-weight: 600;
        }

        .klinik-search .btn-outline-secondary {
            margin-left: 6px;

            border-radius: 10px;
        }

        /* =========================================================
                                            TABLE
                                            ========================================================= */

        .klinik-table-card {
            background: #ffffff;

            border: 1px solid #e9ecef;
            border-radius: 14px;

            overflow: hidden;

            box-shadow: 0 4px 18px rgba(0, 0, 0, .035);
        }

        .klinik-table-card .card-body {
            padding: 0;
        }

        .klinik-table {
            margin-bottom: 0;
            width: 100%;
            table-layout: fixed;
        }

        /* =========================================================
                                            COLUMN WIDTH
                                            ===================================================== */

        .klinik-table .col-no {
            width: 4%;
        }

        .klinik-table .col-rekam-medis {
            width: 12%;
        }

        .klinik-table .col-nama {
            width: 52%;
        }

        .klinik-table .col-tanggal-masuk {
            width: 24%;
        }

        .klinik-table .col-aksi {
            width: 8%;
        }

        .klinik-table thead th {
            background: #f8f9fb;

            color: #6c757d;

            border-bottom: 1px solid #e9ecef;

            padding: 14px 16px;

            font-size: 11px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .4px;

            white-space: nowrap;
        }

        .klinik-table tbody td {
            padding: 15px 16px;

            color: #283144;

            border-bottom: 1px solid #f0f1f3;

            font-size: 13px;

            vertical-align: middle;
        }

        .klinik-table tbody tr {
            transition: background-color .15s ease;
        }

        .klinik-table tbody tr:hover {
            background: #fafbff;
        }

        .klinik-table tbody tr:last-child td {
            border-bottom: 0;
        }

        /* =========================================================
                                                        NAMA WBP
                                                        ========================================================= */

        .klinik-wbp-name {
            color: #283144;
            font-size: 13px;
            font-weight: 650;
            line-height: 1.4;

            white-space: normal;
            word-break: break-word;
        }

        .klinik-wbp-reg {
            display: block;

            color: #8a94a6;

            font-size: 11px;

            margin-top: 3px;
        }

        /* =========================================================
                                                    NO REKAM MEDIS
                                                    ========================================================= */

        .klinik-rekam-medis {
            display: inline-flex;
            align-items: center;

            padding: 6px 9px;

            background: #f5f7fa;

            border: 1px solid #e8ebef;

            border-radius: 7px;

            color: #3f4857;

            font-family: monospace;

            font-size: 12px;
            font-weight: 600;

            letter-spacing: .2px;
        }

        .klinik-rekam-kosong {
            color: #a0a7b1;

            font-size: 12px;
        }


        /* =========================================================
                                                STATUS
                                                ========================================================= */

        .klinik-status {
            display: inline-flex;
            align-items: center;

            padding: 6px 10px;

            border-radius: 7px;

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;
        }

        .klinik-status-lengkap {
            background: rgba(25, 135, 84, .10);

            color: #198754;
        }

        .klinik-status-belum {
            background: rgba(240, 173, 0, .12);

            color: #a66f00;
        }


        /* =========================================================
                                                ACTION
                                                ========================================================= */

        .klinik-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 34px;
            height: 34px;

            border-radius: 8px;

            font-size: 13px;

            transition: all .15s ease;
        }

        .klinik-action:hover {
            transform: translateY(-1px);
        }

        .klinik-action-detail {
            color: #3454d1;

            background: rgba(52, 84, 209, .10);

            border: 1px solid rgba(52, 84, 209, .12);
        }

        .klinik-action-detail:hover {
            color: #3454d1;

            background: rgba(52, 84, 209, .16);
        }

        .klinik-action-edit {
            color: #6c757d;

            background: #f5f6f8;

            border: 1px solid #e7e9ec;
        }

        .klinik-action-edit:hover {
            color: #495057;

            background: #eceef1;
        }


        /* =========================================================
                                                EMPTY STATE
                                                ========================================================= */

        .klinik-empty {
            padding: 60px 20px !important;

            text-align: center;

            color: #6c757d;
        }


        /* =========================================================
                                                PAGINATION
                                                ========================================================= */

        .klinik-pagination {
            padding: 16px 20px;

            border-top: 1px solid #f0f1f3;
        }

        .klinik-pagination-info {
            color: #8a94a6;

            font-size: 12px;
        }

        .klinik-pagination-info strong {
            color: #596273;
        }


        /* =========================================================
                                                RESPONSIVE
                                                ========================================================= */

        @media (max-width: 767.98px) {

            .klinik-stat-value {
                font-size: 24px;
            }

            .klinik-table thead th,
            .klinik-table tbody td {
                padding: 12px;
            }

            .klinik-wbp-name {
                min-width: 190px;
            }

            .klinik-table-card {
                border-radius: 10px;
            }

            .klinik-pagination {
                flex-direction: column;

                align-items: flex-start !important;
            }
        }

        /* =========================================================
                                                MODAL EDIT WBP KLINIK
                                                ========================================================= */

        .klinik-modal-overlay {
            position: fixed;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(0, 0, 0, .45);

            opacity: 0;
            visibility: hidden;

            transition: .2s ease;

            z-index: 99999;
        }

        .klinik-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .klinik-modal {
            width: 100%;
            max-width: 650px;

            background: #fff;

            border-radius: 16px;

            overflow: hidden;

            box-shadow: 0 15px 40px rgba(0, 0, 0, .2);

            transform: translateY(-15px);
            transition: .2s ease;
        }

        .klinik-modal-overlay.show .klinik-modal {
            transform: translateY(0);
        }


        /* HEADER */

        .klinik-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 18px 22px;

            background: #f8f9fa;

            border-bottom: 1px solid #e9ecef;
        }

        .klinik-modal-header h5 {
            margin: 0;

            font-size: 18px;
            font-weight: 700;

            color: #283144;
        }

        .klinik-modal-header small {
            color: #8a94a6;
        }


        /* CLOSE */

        .klinik-modal-close {
            width: 36px;
            height: 36px;

            border: none;
            border-radius: 8px;

            background: transparent;

            font-size: 28px;
            line-height: 1;

            color: #6c757d;

            cursor: pointer;

            transition: .2s;
        }

        .klinik-modal-close:hover {
            background: #e9ecef;
            color: #dc3545;
        }


        /* BODY */

        .klinik-modal-body {
            padding: 24px;
        }


        /* IDENTITY */

        .klinik-modal-identity {
            display: grid;
            grid-template-columns: 1fr 1fr;

            gap: 12px;

            margin-bottom: 24px;
        }

        .klinik-modal-info {
            padding: 14px;

            background: #f8f9fa;

            border: 1px solid #e9ecef;

            border-radius: 10px;
        }

        .klinik-modal-info span {
            display: block;

            margin-bottom: 5px;

            font-size: 11px;

            color: #8a94a6;
        }

        .klinik-modal-info strong {
            display: block;

            font-size: 13px;

            color: #283144;
        }


        /* FORM */

        .klinik-form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 13px;
            font-weight: 600;

            color: #343a40;
        }

        .klinik-form-group input {
            height: 45px;

            border-radius: 9px;
        }

        .klinik-form-group small {
            display: block;

            margin-top: 6px;

            font-size: 12px;

            color: #8a94a6;
        }

        .klinik-form-error {
            margin-top: 7px;

            font-size: 12px;

            color: #dc3545;
        }


        /* FOOTER */

        .klinik-modal-footer {
            display: flex;
            justify-content: flex-end;

            gap: 10px;

            padding: 16px 22px;

            border-top: 1px solid #e9ecef;
        }

        .klinik-modal-footer .btn {
            min-width: 100px;

            height: 40px;

            border-radius: 8px;

            font-size: 13px;
            font-weight: 600;
        }


        /* MOBILE */

        @media (max-width: 600px) {

            .klinik-modal-overlay {
                padding: 12px;
            }

            .klinik-modal-identity {
                grid-template-columns: 1fr;
            }

            .klinik-modal-body {
                padding: 18px;
            }

            .klinik-modal-footer {
                padding: 14px 18px;
            }

        }

        /* =========================================================
                                                                                                   MODAL KONFIRMASI KLINIK
                                                                                                ========================================================= */

        .klinik-confirm-overlay {
            position: fixed;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(2px);

            opacity: 0;
            visibility: hidden;

            transition: .2s ease;

            z-index: 100000;
        }

        .klinik-confirm-overlay.show {
            opacity: 1;
            visibility: visible;
        }


        .klinik-confirm-modal {
            width: 100%;
            max-width: 480px;

            background: #ffffff;

            border-radius: 16px;
            overflow: hidden;

            box-shadow: 0 20px 50px rgba(0, 0, 0, .18);

            transform: translateY(-15px) scale(.98);

            transition: .2s ease;
        }

        .klinik-confirm-overlay.show .klinik-confirm-modal {
            transform: translateY(0) scale(1);
        }


        /* HEADER */

        .klinik-confirm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 18px 22px;

            border-bottom: 1px solid #e9ecef;

            background: #f8f9fa;
        }

        .klinik-confirm-header h5 {
            margin: 0 0 3px;

            font-size: 17px;
            font-weight: 700;

            color: #283144;
        }

        .klinik-confirm-header small {
            font-size: 12px;

            color: #8a94a6;
        }


        .klinik-confirm-close {
            width: 36px;
            height: 36px;

            border: none;
            border-radius: 8px;

            background: transparent;

            color: #6c757d;

            font-size: 27px;
            line-height: 1;

            cursor: pointer;
        }

        .klinik-confirm-close:hover {
            background: #e9ecef;
            color: #dc3545;
        }


        /* BODY */

        .klinik-confirm-body {
            padding: 24px;
            text-align: center;
        }

        .klinik-confirm-icon {
            width: 56px;
            height: 56px;

            margin: 0 auto 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #fff3cd;

            border-radius: 50%;

            font-size: 27px;
        }

        .klinik-confirm-body h6 {
            margin-bottom: 7px;

            font-size: 16px;
            font-weight: 700;

            color: #283144;
        }

        .klinik-confirm-body>p {
            margin-bottom: 18px;

            font-size: 13px;
        }


        /* WBP */

        .klinik-confirm-wbp {
            padding: 12px 15px;

            background: #f8f9fa;

            border: 1px solid #e9ecef;

            border-radius: 10px;

            text-align: left;

            margin-bottom: 12px;
        }

        .klinik-confirm-wbp span {
            display: block;

            margin-bottom: 4px;

            font-size: 11px;

            color: #8a94a6;
        }

        .klinik-confirm-wbp strong {
            display: block;

            font-size: 14px;

            color: #283144;
        }


        /* CHANGE */

        .klinik-change-box {
            display: flex;
            align-items: center;

            gap: 10px;

            padding: 14px;

            background: #f8f9fa;

            border: 1px solid #e9ecef;

            border-radius: 10px;
        }

        .klinik-change-item {
            flex: 1;

            text-align: left;
        }

        .klinik-change-item span {
            display: block;

            margin-bottom: 5px;

            font-size: 11px;

            color: #8a94a6;
        }

        .klinik-change-item strong {
            display: block;

            font-size: 13px;

            word-break: break-word;
        }

        .klinik-change-arrow {
            font-size: 20px;

            color: #8a94a6;
        }


        /* WARNING */

        .klinik-confirm-warning {
            display: flex;
            align-items: flex-start;

            gap: 8px;

            margin-top: 15px;

            padding: 11px 13px;

            background: #fff8e1;

            border: 1px solid #ffe69c;

            border-radius: 9px;

            text-align: left;

            font-size: 12px;

            color: #856404;
        }


        /* FOOTER */

        .klinik-confirm-footer {
            display: flex;
            justify-content: flex-end;

            gap: 10px;

            padding: 16px 22px;

            border-top: 1px solid #e9ecef;
        }

        .klinik-confirm-footer .btn {
            min-width: 120px;

            height: 40px;

            border-radius: 8px;

            font-size: 13px;
            font-weight: 600;
        }


        /* MOBILE */

        @media (max-width: 600px) {

            .klinik-confirm-overlay {
                padding: 12px;
            }

            .klinik-confirm-body {
                padding: 20px;
            }

            .klinik-change-box {
                flex-direction: column;
                align-items: stretch;
            }

            .klinik-change-arrow {
                text-align: center;
                transform: rotate(90deg);
            }

            .klinik-confirm-footer {
                padding: 14px 18px;
            }

            .klinik-confirm-footer .btn {
                flex: 1;
                min-width: 0;
            }

        }
    </style>

    <div class="container-fluid my-3">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

            <div>
                <h4 class="fw-bold mb-1">
                    Data WBP Klinik

                    <span class="badge bg-primary ms-2">
                        {{ number_format($totalWbpKlinik) }} Orang
                    </span>
                </h4>

                <small class="text-secondary">
                    Data WBP yang terdaftar dalam sistem klinik
                </small>
            </div>

        </div>


        {{-- ================= STATISTIK ================= --}}
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">

            <div class="col">
                <div class="klinik-stat-card klinik-stat-primary">

                    <div class="klinik-stat-title">
                        Total WBP Klinik
                    </div>

                    <div class="klinik-stat-value">
                        {{ number_format($totalWbpKlinik) }}
                    </div>

                    <div class="klinik-stat-description">
                        Terdaftar pada Sistem Klinik Lapas Banceuy
                    </div>

                </div>
            </div>

            <div class="col">
                <div class="klinik-stat-card klinik-stat-success">

                    <div class="klinik-stat-title">
                        Memiliki Rekam Medis
                    </div>

                    <div class="klinik-stat-value">
                        {{ number_format($totalDenganRekamMedis) }}
                    </div>

                    <div class="klinik-stat-description">
                        Nomor rekam medis tersedia
                    </div>

                </div>
            </div>

            <div class="col">
                <a href="{{ route('klinik.wbp.index', ['status' => 'tanpa_rekam_medis']) }}" class="klinik-stat-card-link">

                    <div class="klinik-stat-card klinik-stat-warning">

                        <div class="klinik-stat-title">
                            Belum Ada Rekam Medis
                        </div>

                        <div class="klinik-stat-value">
                            {{ number_format($totalTanpaRekamMedis) }}
                        </div>

                        <div class="klinik-stat-description">
                            Klik untuk melihat data
                        </div>

                    </div>

                </a>
            </div>

        </div>


        {{-- ================= SEARCH ================= --}}
        <div class="klinik-search">

            <form method="GET" action="{{ route('klinik.wbp.index') }}">

                <div class="input-group">

                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama WBP atau nomor rekam medis..." value="{{ request('search') }}">

                    <button type="submit" class="btn btn-primary">
                        🔍 Cari
                    </button>

                    @if (request('search'))
                        <a href="{{ route('klinik.wbp.index') }}" class="btn btn-outline-secondary">

                            Reset

                        </a>
                    @endif

                </div>

            </form>

        </div>

        @if (request('status') === 'tanpa_rekam_medis')
            <div class="d-flex align-items-center justify-content-between mb-3">

                <div>
                    <small class="text-secondary ms-2">
                        Menampilkan WBP yang belum memiliki nomor rekam medis
                    </small>
                </div>

                <a href="{{ route('klinik.wbp.index') }}" class="btn btn-sm btn-outline-secondary">
                    ← Tampilkan Semua
                </a>

            </div>
        @endif


        {{-- ================= TABLE ================= --}}
        <div class="klinik-table-card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table klinik-table align-middle">

                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th class="col-rekam-medis">No. Rekam Medis</th>
                                <th class="col-nama">Nama WBP</th>
                                <th class="col-tanggal-masuk">Tanggal Masuk Lapas</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($data as $item)
                                <tr>

                                    {{-- NO --}}
                                    <td class="klinik-row-number">
                                        {{ $data->firstItem() + $loop->index }}
                                    </td>


                                    {{-- NO REKAM MEDIS --}}
                                    <td>

                                        @if ($item->no_rekam_medis)
                                            <span class="klinik-rekam-medis">
                                                {{ $item->no_rekam_medis }}
                                            </span>
                                        @else
                                            <span class="klinik-rekam-kosong">
                                                Belum tersedia
                                            </span>
                                        @endif

                                    </td>

                                    {{-- NAMA WBP --}}
                                    <td>

                                        @if ($item->wbp)
                                            <div class="klinik-wbp-name">
                                                {{ $item->wbp->nama }}
                                            </div>

                                            <span class="klinik-wbp-reg">
                                                No. Reg:
                                                {{ $item->wbp->no_reg_instansi ?? '-' }}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                Data WBP tidak ditemukan
                                            </span>
                                        @endif

                                    </td>

                                    {{-- TANGGAL MASUK LAPAS --}}
                                    <td>

                                        @if ($item->wbp?->tgl_masuk_lapas)
                                            <span class="klinik-tanggal-masuk">
                                                {{ \Carbon\Carbon::parse($item->wbp->tgl_masuk_lapas)->locale('id')->translatedFormat('d F Y') }}
                                            </span>
                                        @else
                                            <span class="klinik-tanggal-kosong">
                                                Belum tersedia
                                            </span>
                                        @endif

                                    </td>

                                    {{-- AKSI --}}
                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- DETAIL --}}
                                            <a href="{{ route('klinik.wbp.show', $item->wbp_id) }}"
                                                class="klinik-action klinik-action-detail" title="Detail">

                                                👁

                                            </a>


                                            {{-- EDIT --}}
                                            <button type="button" class="klinik-action klinik-action-edit btn-edit-klinik"
                                                title="Edit" data-wbp-id="{{ $item->wbp_id }}"
                                                data-nama="{{ $item->wbp->nama ?? '-' }}"
                                                data-no-reg="{{ $item->wbp->no_reg_instansi ?? '-' }}"
                                                data-rekam-medis="{{ $item->no_rekam_medis ?? '' }}"
                                                data-update-url="{{ route('klinik.wbp.update', $item->wbp_id) }}">

                                                ✏️

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="klinik-empty">

                                        <div class="klinik-empty-title">
                                            Data WBP klinik tidak ditemukan
                                        </div>

                                        <div class="klinik-empty-description">
                                            Tidak ada data yang sesuai dengan pencarian.
                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- ================= PAGINATION ================= --}}
                @if ($data->hasPages())
                    <div class="klinik-pagination d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div class="klinik-pagination-info">

                            Menampilkan
                            <strong>{{ $data->firstItem() }}</strong>
                            -
                            <strong>{{ $data->lastItem() }}</strong>

                            dari

                            <strong>{{ $data->total() }}</strong>
                            data

                        </div>

                        <div>
                            {{ $data->onEachSide(1)->links() }}
                        </div>

                    </div>
                @endif

            </div>

        </div>

    </div>

    {{-- =========================================================
     MODAL EDIT WBP KLINIK
    ========================================================= --}}
    <div id="modalEditWbpKlinik" class="klinik-modal-overlay">

        <div class="klinik-modal">

            {{-- HEADER --}}
            <div class="klinik-modal-header">

                <div>
                    <h5>
                        Edit Data WBP Klinik
                    </h5>

                    <small>
                        Perbarui nomor rekam medis
                    </small>
                </div>

                <button type="button" class="klinik-modal-close" id="btnCloseEditKlinik">

                    ×

                </button>

            </div>


            {{-- BODY --}}
            <div class="klinik-modal-body">

                {{-- IDENTITAS --}}
                <div class="klinik-modal-identity">

                    <div class="klinik-modal-info">

                        <span>
                            WBP ID
                        </span>

                        <strong id="editKlinikWbpId">
                            -
                        </strong>

                    </div>


                    <div class="klinik-modal-info">

                        <span>
                            Nama WBP
                        </span>

                        <strong id="editKlinikNama">
                            -
                        </strong>

                    </div>


                    <div class="klinik-modal-info">

                        <span>
                            No. Registrasi
                        </span>

                        <strong id="editKlinikNoReg">
                            -
                        </strong>

                    </div>

                </div>


                {{-- FORM --}}
                <form id="formEditWbpKlinik" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="klinik-form-group">

                        <label for="editKlinikRekamMedis">

                            No. Rekam Medis

                        </label>

                        <input type="text" id="editKlinikRekamMedis" name="no_rekam_medis" class="form-control"
                            maxlength="50" placeholder="Contoh: 25/XI/650">

                        <small>
                            Masukkan nomor rekam medis sesuai kartu pasien.
                        </small>

                        <div id="editKlinikError" class="klinik-form-error">
                        </div>

                    </div>

                </form>

            </div>


            {{-- FOOTER --}}
            <div class="klinik-modal-footer">

                <button type="button" class="btn btn-primary" id="btnSubmitEditKlinik">

                    💾 Simpan Perubahan

                </button>

            </div>

        </div>

    </div>

    {{-- =========================================================
     MODAL KONFIRMASI EDIT WBP KLINIK
========================================================= --}}

    <div id="modalKonfirmasiKlinik" class="klinik-confirm-overlay">

        <div class="klinik-confirm-modal">

            {{-- HEADER --}}
            <div class="klinik-confirm-header">

                <div>
                    <h5>
                        Konfirmasi Perubahan
                    </h5>

                    <small>
                        Pastikan data yang dimasukkan sudah benar.
                    </small>
                </div>

                <button type="button" class="klinik-confirm-close" id="btnCloseKonfirmasiKlinik">

                    ×

                </button>

            </div>


            {{-- BODY --}}
            <div class="klinik-confirm-body">

                <div class="klinik-confirm-icon">
                    ⚠️
                </div>

                <h6>
                    Ubah Nomor Rekam Medis?
                </h6>

                <p class="text-secondary">
                    Anda akan mengubah nomor rekam medis WBP berikut:
                </p>


                {{-- NAMA --}}
                <div class="klinik-confirm-wbp">

                    <span>
                        WBP
                    </span>

                    <strong id="confirmKlinikNama">
                        -
                    </strong>

                </div>


                {{-- PERUBAHAN --}}
                <div class="klinik-change-box">

                    <div class="klinik-change-item">

                        <span>
                            Sebelumnya
                        </span>

                        <strong id="confirmKlinikLama" class="text-secondary">

                            -

                        </strong>

                    </div>


                    <div class="klinik-change-arrow">
                        →
                    </div>


                    <div class="klinik-change-item">

                        <span>
                            Menjadi
                        </span>

                        <strong id="confirmKlinikBaru" class="text-primary">

                            -

                        </strong>

                    </div>

                </div>


                <div class="klinik-confirm-warning">

                    <span>⚠️</span>

                    <span>
                        Pastikan nomor rekam medis sesuai dengan kartu pasien.
                    </span>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="klinik-confirm-footer">

                <button type="button" class="btn btn-outline-secondary" id="btnBatalKonfirmasiKlinik">

                    Batal

                </button>

                <button type="button" class="btn btn-primary" id="btnLanjutSimpanKlinik">

                    Ya, Simpan Perubahan

                </button>

            </div>

        </div>

    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('modalEditWbpKlinik');

        const form = document.getElementById('formEditWbpKlinik');

        const wbpId = document.getElementById('editKlinikWbpId');
        const nama = document.getElementById('editKlinikNama');
        const noReg = document.getElementById('editKlinikNoReg');
        const rekamMedis = document.getElementById('editKlinikRekamMedis');
        const errorBox = document.getElementById('editKlinikError');

        const closeButton = document.getElementById('btnCloseEditKlinik');
        const cancelButton = document.getElementById('btnCancelEditKlinik');


        /* =========================================================
           BUKA MODAL
        ========================================================= */

        document.querySelectorAll('.btn-edit-klinik').forEach(button => {

            button.addEventListener('click', function() {

                wbpId.textContent =
                    this.dataset.wbpId || '-';

                nama.textContent =
                    this.dataset.nama || '-';

                noReg.textContent =
                    this.dataset.noReg || '-';

                rekamMedis.value =
                    this.dataset.rekamMedis || '';

                form.action =
                    this.dataset.updateUrl;

                errorBox.textContent = '';

                modal.classList.add('show');

                setTimeout(() => {
                    rekamMedis.focus();
                }, 200);

            });

        });


        /* =========================================================
           TUTUP MODAL
        ========================================================= */

        function closeModal() {

            modal.classList.remove('show');

            errorBox.textContent = '';

        }


        closeButton.addEventListener('click', closeModal);

        cancelButton.addEventListener('click', closeModal);


        /* =========================================================
           KLIK OVERLAY
        ========================================================= */

        modal.addEventListener('click', function(event) {

            if (event.target === modal) {
                closeModal();
            }

        });


        /* =========================================================
           ESC
        ========================================================= */

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape' &&
                modal.classList.contains('show')) {

                closeModal();

            }

        });

    });
</script>
