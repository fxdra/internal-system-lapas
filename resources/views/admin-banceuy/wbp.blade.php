@php
    $user = Auth::guard('admin')->user();
    $role = $user?->role;

    $permissions = [
        // Full Access
        'fullAccess' => in_array($role, ['superadmin', 'admin', 'kplp', 'ka_kplp']),

        // User Management
        'userManagement' => in_array($role, ['superadmin', 'admin']),

        // Bidang
        'registrasi' => $role === 'registrasi',
        'binadik' => $role === 'binadik',
        'giatja' => $role === 'giatja',
        'klinik' => $role === 'klinik',
        'kamtib' => $role === 'kamtib',
    ];
@endphp

@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        .custom-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);

            display: flex;
            justify-content: center;
            align-items: center;

            opacity: 0;
            visibility: hidden;

            transition: .25s;
            z-index: 99999;
        }

        .custom-modal-content form {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .modal-header-custom,
        .modal-footer-custom {
            flex-shrink: 0;
        }

        /* SHOW */
        .custom-modal.show {
            opacity: 1;
            visibility: visible;
        }

        /* BOX */
        .custom-modal-content {
            width: 1100px;
            max-width: 95%;
            height: 85vh;

            display: flex;
            flex-direction: column;

            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
            transform: translateY(-20px);
            transition: .25s ease;

        }

        .custom-modal-content {
            border-radius: 16px;
        }

        .modal-body-custom {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .custom-modal.show .custom-modal-content {
            transform: translateY(0);
        }

        /* HEADER */
        .modal-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 25px;
            border-bottom: 1px solid #ececec;
            background: #f8f9fa;
        }

        .modal-header-custom h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        /* CLOSE */
        .close-modal {
            border: none;
            background: none;
            font-size: 30px;
            cursor: pointer;
            color: #777;
            transition: .2s;

        }

        .close-modal:hover {
            color: red;
            transform: scale(1.1);
        }

        /* BODY */
        .modal-body-custom {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 24px;
        }

        .audit-toolbar {
            flex-shrink: 0;
        }

        .audit-toolbar .badge {
            font-size: .75rem;
            font-weight: 600;
        }

        .audit-filter.active {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, .18);
            outline: 2px solid rgba(255, 255, 255, .6);
            filter: brightness(1.08);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: .2s;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, .15);
        }

        /* FOOTER */
        .modal-footer-custom {
            padding: 18px 25px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid #eee;
            background: #fafafa;
        }

        /* RESPONSIVE */
        @media(max-width:576px) {
            .custom-modal-content {
                width: 95%;
            }

        }
    </style>

    <style>
        /* ================= EDIT MODAL ================= */
        #modalEditKamar {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 10000;
        }

        #modalEditStatus {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 10000;
        }


        #modalEditKeterangan {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 10000;
        }

        .edit-card {
            background: #fff;
            max-width: 500px;
            margin: 7% auto;
            padding: 22px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
        }

        .table,
        .table td,
        .table th,
        .table tr {
            border: none !important;
        }

        .text-muted {
            display: none;
        }

        /* ================= CARD ================= */
        .main-row {
            display: block;
            background: #f8f9fa;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: .2s;
            height: 100%;
            position: relative;
        }

        .main-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        .nama-col {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
        }

        .sub-info {
            font-size: 13px;
            color: #6c757d;
            margin-top: 4px;
        }

        /* ================= GRID ================= */
        .wbp-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        /* ================= SEARCH ================= */
        .search-box {
            border-radius: 12px;
            height: 48px;
            border: 1px solid #dee2e6;
            box-shadow: none !important;
        }

        /* ================= IMPORT BUTTON ================= */
        .btn-import {
            height: 45px;
            border-radius: 12px;
            font-weight: 600;
            padding: 0 20px;
        }

        /* ================= DETAIL MODAL ================= */
        #modalDetail {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 9999;
        }

        .detail-card {
            background: #fff;
            max-width: 430px;
            margin: 7% auto;
            padding: 22px;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* ================= IMPORT MODAL ================= */
        .jq-modal-overlay {
            position: fixed;
            inset: 0;
            padding: 20px;
            background: rgba(0, 0, 0, .6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            opacity: 0;
            visibility: hidden;
            transition: .25s ease;
        }

        .jq-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .jq-modal-card {
            width: 95%;
            max-width: 520px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            transform: translateY(20px) scale(.96);
            transition: .25s ease;
        }

        .jq-modal-overlay.active .jq-modal-card {
            transform: translateY(0) scale(1);
        }

        .jq-modal-header {
            background: linear-gradient(135deg, #198754, #157347);
            color: white;
            padding: 18px 22px;
        }

        .jq-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            padding: 0;
            font-size: 20px;
            line-height: 1;
        }

        .jq-modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }

        .upload-box {
            border: 2px dashed #ced4da;
            border-radius: 16px;
            padding: 35px 20px;
            text-align: center;
            background: #f8f9fa;
            transition: .2s;
        }

        .upload-box:hover {
            border-color: #198754;
            background: #f1fff7;
        }

        .upload-icon {
            font-size: 55px;
        }

        .import-btn {
            height: 50px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
        }


        .action-buttons {
            margin-top: 10px;
            display: flex;
            align-items: center;
        }

        /* base button */
        .action-btn {
            width: 82px;
            height: 38px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0;

            line-height: 1;

            border: none;
            border-radius: 8px;

            font-size: 12px;
            font-weight: 600;

            cursor: pointer;
            transition: .2s;
        }

        /* DETAIL */
        .btn-detail {
            background: #111827;
            color: #fff;
        }

        .btn-detail:hover {
            background: #1f2937;
            transform: translateY(-1px);
        }

        /* DELETE */
        .btn-delete {
            background: #ef4444;
            color: #fff;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }


        /* ================= STATUS TAB ================= */

        #statusTabs {
            gap: 10px;
            flex-wrap: wrap;
        }

        #statusTabs .nav-link {

            border: none;
            border-radius: 12px;

            padding: 10px 18px;

            font-size: 14px;
            font-weight: 600;

            color: #6b7280;
            background: #f3f4f6;

            transition: .25s ease;

            white-space: nowrap;
        }

        #statusTabs .nav-link:hover {

            background: #e5e7eb;
            color: #111827;

            transform: translateY(-1px);
        }

        /* ACTIVE */
        #statusTabs .nav-link.active {

            background: linear-gradient(135deg,
                    #198754,
                    #157347);

            color: #fff;

            box-shadow:
                0 4px 12px rgba(25, 135, 84, .25);

        }


        /* Mobile */
        @media (max-width:768px) {

            .action-btn {
                flex: 1;
                justify-content: center;
            }

            #statusTabs {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                overflow-y: hidden;
                padding-bottom: 6px;
                scrollbar-width: none;
            }

            #statusTabs::-webkit-scrollbar {
                display: none;
            }

            #statusTabs .nav-item {
                flex: 0 0 auto;
            }

            #statusTabs .nav-link {
                padding: 9px 16px;
                font-size: 13px;
            }

            /* Overlay */
            #modalDetail {
                padding: 12px;
                overflow-y: auto;
                align-items: flex-start;
            }

            /* Card */
            #modalDetail .detail-card {
                width: 100%;
                max-width: 100%;
                margin: 20px auto;
                padding: 20px;
                border-radius: 18px;

                height: auto;
                max-height: calc(100vh - 40px);

                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            /* Isi modal */
            #detailBody {
                flex: 1;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;

                max-height: none;
                min-height: 0;
            }
        }

        /* ================= MOBILE ================= */
        @media (max-width:768px) {

            .wbp-grid {
                grid-template-columns: 1fr;
            }

            .nama-col {
                font-size: 15px;
            }

            .detail-card {
                margin: 20% 14px;
            }

        }
    </style>

    <style>
        /* ================= DELETE MODAL ================= */

        .delete-modal-overlay {
            position: fixed;
            inset: 0;
            display: none;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(3px);
            z-index: 99999;
        }

        .delete-modal-card {
            width: 100%;
            max-width: 430px;
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0, 0, 0, .18);
            animation: deleteModalShow .22s ease;
        }

        /* HEADER */

        .delete-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px;
            border-bottom: 1px solid #ececec;
        }

        .delete-modal-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        /* CLOSE */

        .delete-modal-close {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            background: #f4f4f4;
            color: #555;
            font-size: 22px;
            cursor: pointer;
            transition: .2s;
        }

        .delete-modal-close:hover {
            background: #e9e9e9;
            color: #dc3545;
        }

        /* BODY */

        .delete-modal-body {
            padding: 24px;
        }

        .delete-modal-body p {
            font-size: 14px;
            line-height: 1.6;
        }

        .delete-modal-body .form-control {
            height: 48px;
            border-radius: 12px;
            font-size: 15px;
        }

        .delete-modal-body .btn {
            border-radius: 12px;
            height: 46px;
            font-weight: 600;
        }

        /* ANIMATION */

        @keyframes deleteModalShow {

            from {
                opacity: 0;
                transform: translateY(15px) scale(.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

        }

        /* ================= MOBILE ================= */

        @media (max-width:768px) {

            .delete-modal-overlay {
                padding: 16px;
                align-items: flex-end;
            }

            .delete-modal-card {
                max-width: 100%;
                width: 100%;
                border-radius: 20px 20px 0 0;
                animation: deleteModalMobile .25s ease;
            }

            .delete-modal-body {
                padding: 20px;
            }

        }

        @keyframes deleteModalMobile {

            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }

        }
    </style>

    <div class="container-fluid my-3">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

            <div>
                <h4 class="fw-bold mb-1">
                    Data WBP
                    <span class="badge bg-primary ms-2">
                        {{ number_format($countAktif) }} Orang
                    </span>
                </h4>

                <small class="text-secondary">
                    Manage data warga binaan
                </small>
            </div>

            <div class="d-flex gap-2">
                @if ($permissions['fullAccess'])
                    <button id="btnImportExcel" class="btn btn-success btn-import shadow-sm">
                        📥 Import Excel
                    </button>
                @endif

                @if ($permissions['fullAccess'])
                    <button id="btnTambahWbp" class="btn btn-primary btn-import shadow-sm">
                        ➕ Tambah WBP
                    </button>
                @endif
            </div>

        </div>

        {{-- ================= SEARCH ================= --}}
        <input type="text" id="search" class="form-control search-box mb-2" placeholder="Cari nama warga binaan...">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <ul class="nav nav-pills mb-0" id="statusTabs">

                <li class="nav-item">
                    <button class="nav-link active status-tab" data-status="AKTIF">
                        PENGHUNI -
                        <span class="count-badge" id="count-aktif">
                            {{ $countAktif ?? 0 }}
                        </span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link status-tab" data-status="BON">
                        BON -
                        <span class="count-badge" id="count-bon">
                            {{ $countBon ?? 0 }}
                        </span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link status-tab" data-status="SAKIT">
                        LUAR TEMBOK -
                        <span class="count-badge" id="count-sakit">
                            {{ $countSakit ?? 0 }}
                        </span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link status-tab" data-status="ISOLASI">
                        ISOLASI -
                        <span class="count-badge" id="count-isolasi">
                            {{ $countIsolasi ?? 0 }}
                        </span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link status-tab" data-status="PINDAH UPT">
                        PINDAH UPT -
                        <span class="count-badge" id="count-pindah">
                            {{ $countPindah ?? 0 }}
                        </span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link status-tab" data-status="PULANG">
                        PULANG -
                        <span class="count-badge" id="count-pulang">
                            {{ $countPulang ?? 0 }}
                        </span>
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link status-tab" data-status="MENINGGAL">
                        MENINGGAL -
                        <span class="count-badge" id="count-meninggal">
                            {{ $countMeninggal ?? 0 }}
                        </span>
                    </button>
                </li>

            </ul>

            {{-- Audit --}}
            <div class="d-flex gap-2 audit-toolbar">

                @if ($audit['duplicate_no_reg'] > 0)
                    <button class="badge bg-danger border-0 audit-filter" data-filter="duplicate_no_reg">
                        Duplikat No. Reg • {{ $audit['duplicate_no_reg'] }}
                    </button>
                @endif

                @if ($audit['duplicate_nama'] > 0)
                    <button class="badge bg-warning text-dark border-0 audit-filter" data-filter="duplicate_nama">
                        Duplikat Nama • {{ $audit['duplicate_nama'] }}
                    </button>
                @endif

                @if ($audit['belum_ada_foto'] > 0)
                    <button class="badge bg-secondary border-0 audit-filter" data-filter="belum_ada_foto">
                        Belum Ada Foto • {{ $audit['belum_ada_foto'] }}
                    </button>
                @endif

                @if ($audit['ekspirasi_kosong'] > 0)
                    <button class="badge bg-info text-dark border-0 audit-filter" data-filter="ekspirasi_kosong">
                        Exp • {{ $audit['ekspirasi_kosong'] }}
                    </button>
                @endif

                @if ($audit['belum_lengkap'] > 0)
                    <button class="badge bg-dark border-0 audit-filter" data-filter="belum_lengkap">
                        Belum Lengkap • {{ $audit['belum_lengkap'] }}
                    </button>
                @endif

            </div>

        </div>

        {{-- ================= TABLE WRAPPER ================= --}}
        <div id="tableWrapper">

            <div class="card shadow-md border-0">
                <div class="card-body">

                    <div class="wbp-grid ">

                        @forelse($wbp as $w)
                            <div class="main-row d-flex gap-3 align-items-start shadow-lg">

                                <div class="position-absolute top-0 end-0 m-2 d-flex flex-column align-items-end gap-1">

                                    @if ($w->duplicate_no_reg)
                                        <span class="badge bg-danger">
                                            No Reg Duplikat
                                        </span>
                                    @endif

                                    @if ($w->duplicate_nama)
                                        <span class="badge bg-warning text-dark">
                                            Nama Duplikat
                                        </span>
                                    @endif

                                    @if (!$w->has_foto)
                                        <span class="badge bg-secondary">
                                            Belum Ada Foto
                                        </span>
                                    @endif

                                    @if (blank($w->ekspirasi))
                                        <span class="badge bg-info text-dark">
                                            Ekspirasi Kosong
                                        </span>
                                    @endif

                                    @if ($w->is_data_complete)
                                        <span class="badge bg-success">
                                            Data Lengkap
                                        </span>
                                    @endif

                                </div>

                                {{-- FOTO --}}
                                <div style="flex:0 0 90px;">

                                    @if (!empty($w->foto_wbp))
                                        <img src="{{ asset($w->foto_wbp) }}"
                                            style="
                                            width:90px;
                                            height:90px;
                                            object-fit:cover;
                                            border-radius:12px;
                                        ">
                                    @else
                                        <div
                                            style="
                                        width:90px;
                                        height:90px;
                                        background:#e9ecef;
                                        border-radius:12px;
                                    ">
                                        </div>
                                    @endif

                                </div>

                                {{-- DATA --}}
                                <div style="flex:1;">

                                    <div class="nama-col">
                                        {{ $w->nama ?? '-' }}
                                    </div>

                                    <div class="sub-info fw-bold">
                                        BLOK {{ $w->kamar->kode_blok ?? '-' }} - <span
                                            class="">{{ $w->kamar->lokasi_sel ?? '-' }}</span>
                                    </div>

                                    {{-- STATUS --}}
                                    @php
                                        $status = $w->status_kamar ?? '-';
                                        $statusStyle = match ($status) {
                                            'Terbuka' => 'background:#28a745;color:#fff;',
                                            'Tertutup' => 'background:#dc3545;color:#fff;',
                                            default => 'background:#6c757d;color:#fff;',
                                        };
                                    @endphp

                                    <span
                                        style="
                                    display:inline-block;
                                    padding:5px 10px;
                                    border-radius:8px;
                                    font-size:12px;
                                    margin-top:8px;
                                    {{ $statusStyle }}
                                ">
                                        {{ $status }}
                                    </span>

                                    <div class="d-flex gap-2 action-buttons">

                                        @if ($permissions['fullAccess'])
                                            <button class="action-btn btn-detail" data-id="{{ $w->id }}">
                                                Detail
                                            </button>
                                        @endif

                                        @if ($permissions['fullAccess'])
                                            <button class="action-btn btn-delete" data-id="{{ $w->id }}">
                                                Hapus
                                            </button>
                                        @endif

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-5 text-secondary">
                                Data tidak ditemukan
                            </div>
                        @endforelse

                    </div>

                    {{-- PAGINATION --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $wbp->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>

        </div>

    </div>

    {{-- ================= MODAL DETAIL ================= --}}
    <div id="modalDetail">

        <div class="detail-card">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">
                    Detail WBP
                </h5>

                <button id="closeModal" class="btn btn-sm btn-danger">
                    ✕
                </button>

            </div>

            <div id="detailBody">
                Loading...
            </div>

        </div>

    </div>

    {{-- ================= MODAL EDIT STATUS ================= --}}

    <div id="modalEditStatus">

        <div class="edit-card">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">
                    Edit Status WBP
                </h5>

                <button id="closeStatusModal" class="btn btn-sm btn-danger">
                    ✕
                </button>

            </div>

            <div id="statusBody"></div>

        </div>

    </div>


    {{-- ================= MODAL EDIT KAMAR ================= --}}
    <div id="modalEditKamar">

        <div class="edit-card">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">
                    Edit Kamar
                </h5>

                <button id="closeEditModal" class="btn btn-sm btn-danger">
                    ✕
                </button>

            </div>

            <div id="editBody"></div>

        </div>

    </div>

    {{-- ================= MODAL EDIT KETERANGAN ================= --}}
    <div id="modalEditKeterangan">

        <div class="edit-card">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">
                    Edit Keterangan
                </h5>

                <button id="closeKeteranganModal" class="btn btn-sm btn-danger">
                    ✕
                </button>

            </div>

            <div id="keteranganBody"></div>

        </div>

    </div>

    {{-- ================= MODAL IMPORT ================= --}}
    <div class="jq-modal-overlay" id="importExcelModal">

        <div class="jq-modal-card shadow-lg">

            {{-- HEADER --}}
            <div class="jq-modal-header d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">
                    📥 Import Data WBP
                </h5>

                <button type="button" class="jq-close btn btn-light btn-sm">
                    ×
                </button>

            </div>

            {{-- BODY --}}
            <div class="jq-modal-body">

                <form id="importWbpForm" action="{{ route('wbp.import') }}" method="POST"
                    enctype="multipart/form-data" autocomplete="off">

                    @csrf

                    <div class="upload-box">

                        <div class="upload-icon">
                            📄
                        </div>

                        <h5 class="fw-bold mt-3">
                            Upload File Excel
                        </h5>

                        <div class="text-secondary mb-4">
                            Format .xls / .xlsx
                        </div>

                        {{-- MODE IMPORT --}}
                        <div class="mb-4 text-start">

                            <label class="form-label fw-bold">
                                Mode Import
                            </label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="import_mode" id="mode_append"
                                    value="append" checked>

                                <label class="form-check-label" for="mode_append">
                                    <strong>Tambah Data</strong>
                                    <br>
                                    <small class="text-muted">
                                        Menambahkan data baru.
                                    </small>
                                </label>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="import_mode" id="mode_update"
                                    value="update">

                                <label class="form-check-label" for="mode_update">
                                    <strong>Perbarui Data WBP</strong>
                                    <br>
                                    <small class="text-muted">
                                        Memperbarui data wbp berdasarkan No Registrasi Instansi.
                                        Jika belum ada, data WBP akan dibuat sebagai data baru.
                                    </small>
                                </label>
                            </div>

                        </div>

                        {{-- FILE --}}
                        <input id="fileExcel" type="file" name="file_excel" class="form-control" accept=".xls,.xlsx"
                            required>

                    </div>

                    {{-- HASIL PREVIEW --}}
                    <div id="previewResult" class="card border-primary mt-4 shadow-sm" style="display:none;">

                        <div class="card-header bg-primary text-white fw-bold">
                            👁 HASIL PREVIEW
                        </div>

                        <div class="card-body">

                            <table class="table table-borderless table-sm mb-0">

                                <tr>
                                    <th width="45%">Mode</th>
                                    <td id="previewMode">-</td>
                                </tr>

                                <tr>
                                    <th>Total Data Excel</th>
                                    <td id="previewTotal">0</td>
                                </tr>

                                <tr>
                                    <th>Data Baru (Insert)</th>
                                    <td class="text-success fw-bold" id="previewInsert">0</td>
                                </tr>

                                <tr>
                                    <th>Data Update</th>
                                    <td class="text-primary fw-bold" id="previewUpdate">0</td>
                                </tr>

                                <tr>
                                    <th>Data Tidak Valid</th>
                                    <td class="text-danger fw-bold" id="previewInvalid">0</td>
                                </tr>

                            </table>

                        </div>

                    </div>

            </div>

            <div class="d-grid gap-2 mt-4">

                <button type="button" id="btnPreviewImport" class="btn btn-primary">
                    👁 Preview Import
                </button>

                <button type="button" id="btnImportNow" class="btn btn-success" style="display:none;">
                    🚀 Import Sekarang
                </button>

            </div>

            </form>

        </div>

    </div>

    <!-- ================= MODAL TAMBAH WBP ================= -->
    <div class="custom-modal" id="modalTambahWbp">

        <div class="custom-modal-content">
            <div class="modal-header-custom">

                <h4>
                    ➕ Tambah WBP
                </h4>

                <button type="button" class="close-modal" id="closeTambahModal">
                    ×
                </button>

            </div>

            <form action="{{ route('wbp.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body-custom">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>No Register Instansi</label>
                            <input type="text" name="no_reg_instansi" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Nama WBP</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Negara</label>
                            <input type="text" name="negara" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Agama</label>
                            <select name="agama" class="form-select">
                                <option value="">Pilih Agama</option>
                                <option>Islam</option>
                                <option>Kristen</option>
                                <option>Katolik</option>
                                <option>Hindu</option>
                                <option>Buddha</option>
                                <option>Konghucu</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Jenis Kejahatan</label>
                            <input type="text" name="jenis_kejahatan" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Putusan (Tahun)</label>
                            <input type="number" name="putusan" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Putusan (Bulan)</label>
                            <input type="number" name="putusan_bulan" class="form-control" min="0"
                                max="11" value="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Ekspirasi</label>
                            <input type="date" name="ekspirasi" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Kamar</label>
                            <select name="kamar_id" class="form-control">
                                <option value="">Pilih Kamar</option>

                                @foreach ($kamars as $kamar)
                                    <option value="{{ $kamar->id }}"
                                        data-search="
                                                {{ $kamar->kode_blok }}{{ preg_replace('/\D/', '', $kamar->lokasi_sel) }}
                                                {{ $kamar->kode_blok }} {{ preg_replace('/\D/', '', $kamar->lokasi_sel) }}
                                                Blok {{ $kamar->kode_blok }}
                                                {{ formatNamaBlok($kamar->kode_blok, $kamar->lokasi_sel) }}
                                            ">
                                        {{ formatNamaBlok($kamar->kode_blok, $kamar->lokasi_sel) }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status WBP</label>
                            <select name="status_wbp" class="form-control">
                                <option value="AKTIF">AKTIF</option>
                                <option value="BON">BON</option>
                                <option value="SAKIT">SAKIT</option>
                                <option value="PINDAH UPT">PINDAH UPT</option>
                                <option value="PULANG">PULANG</option>
                                <option value="MENINGGAL">MENINGGAL</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Masa 1/3</label>
                            <input type="date" name="masa_1_3" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Masa 1/2</label>
                            <input type="date" name="masa_1_2" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Masa 2/3</label>
                            <input type="date" name="masa_2_3" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Total Bulan Remisi</label>
                            <input type="number" name="total_bulan_remisi" class="form-control" min="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Total Hari Remisi</label>
                            <input type="number" name="total_hari_remisi" class="form-control" min="0">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Foto WBP</label>
                            <input type="file" name="foto_wbp" class="form-control" accept="image/*">
                        </div>

                    </div>

                </div>

                <div class="modal-footer-custom">

                    <button type="button" class="btn btn-secondary" id="btnBatalTambah">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ================= DELETE MODAL ================= --}}
    <div class="delete-modal-overlay" id="deleteModal">

        <div class="delete-modal-card">

            <div class="delete-modal-header">

                <h5 class="mb-0">
                    🗑️ Konfirmasi Hapus
                </h5>

                <button type="button" id="closeDeleteModal" class="delete-modal-close">
                    ×
                </button>

            </div>

            <div class="delete-modal-body">

                <p class="mb-3 text-muted">
                    Untuk menghapus data ini, silakan masukkan password akun Anda.
                </p>

                {{-- Dummy field supaya browser tidak autofill --}}
                <input type="text" name="fake_username" autocomplete="username" tabindex="-1"
                    style="
                    position:absolute;
                    left:-9999px;
                    width:1px;
                    height:1px;
                    opacity:0;
                ">

                <form id="deleteForm" autocomplete="off" onsubmit="return false;">

                    <input type="password" id="deletePassword" name="delete_password_confirm" class="form-control"
                        placeholder="Masukkan password" autocomplete="current-password" spellcheck="false"
                        autocapitalize="off" autocorrect="off">

                </form>

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <button type="button" class="btn btn-light" id="cancelDelete">

                        Batal

                    </button>

                    <button type="button" class="btn btn-danger" id="confirmDelete">

                        🗑️ Hapus

                    </button>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= JQUERY ================= --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        const fullAccess = @json($permissions['fullAccess']);

        let currentStatus = 'AKTIF';
        let currentPage = 1;
        let currentAudit = '';

        // ================= LOAD DATA =================
        function loadStatus(status, page = 1) {
            $('#tableWrapper').html(`
        <div class="text-center p-4">
            Loading...
        </div>
    `);

            $.ajax({

                url: '/admin-banceuy/wbp/filter',

                type: 'GET',

                dataType: 'json',

                data: {
                    status: status,
                    page: page,
                    search: $('#search').val(),
                    audit: currentAudit
                },

                success: function(res) {

                    console.log('RES DATA:', res.data);

                    let html = '';

                    if (res.data.length === 0) {

                        html = `
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            Data tidak ditemukan
                        </div>
                    </div>
                `;

                    } else {

                        html += `
                    <div class="card shadow-sm border-0">
                        <div class="card-body">

                            <div class="wbp-grid">
                `;

                        $.each(res.data, function(i, w) {

                            let foto = '';

                            if (w.has_foto) {

                                foto = `
                                    <img
                                        src="/${w.foto_wbp}"
                                        style="
                                            width:90px;
                                            height:90px;
                                            object-fit:cover;
                                            border-radius:12px;
                                        ">
                                `;

                            } else {

                                foto = `
                                    <div style="
                                        width:90px;
                                        height:90px;
                                        background:#e9ecef;
                                        border-radius:12px;
                                    "></div>
                                `;

                            }

                            // ================= STATUS BADGE =================
                            let statusBadge = '';

                            if (w.status_kamar === 'Terbuka') {

                                statusBadge = `
                            <span style="
                                display:inline-block;
                                padding:5px 10px;
                                border-radius:8px;
                                font-size:12px;
                                margin-top:8px;
                                background:#28a745;
                                color:#fff;
                            ">
                                Terbuka
                            </span>
                        `;

                            } else if (w.status_kamar === 'Tertutup') {

                                statusBadge = `
                            <span style="
                                display:inline-block;
                                padding:5px 10px;
                                border-radius:8px;
                                font-size:12px;
                                margin-top:8px;
                                background:#dc3545;
                                color:#fff;
                            ">
                                Tertutup
                            </span>
                        `;

                            }

                            // ================= AUDIT BADGE =================
                            let auditBadge = '';

                            if (w.duplicate_no_reg) {
                                auditBadge += `<span class="badge bg-danger">No Reg Duplikat</span>`;
                            }

                            if (w.duplicate_nama) {
                                auditBadge +=
                                    `<span class="badge bg-warning text-dark">Nama Duplikat</span>`;
                            }

                            if (!w.has_foto) {
                                auditBadge += `<span class="badge bg-secondary">Belum Ada Foto</span>`;
                            }

                            if (!w.ekspirasi) {
                                auditBadge +=
                                    `<span class="badge bg-info text-dark">Ekspirasi Kosong</span>`;
                            }

                            if (w.is_data_complete) {
                                auditBadge += `<span class="badge bg-success">Data Lengkap</span>`;
                            }

                            html += `

                        <div class="main-row d-flex gap-3 align-items-start shadow-lg">

                            <div class="position-absolute top-0 end-0 m-2 d-flex flex-column align-items-end gap-1">
                                ${auditBadge}
                            </div>

                            <div style="flex:0 0 90px;">
                                ${foto}
                            </div>

                            <div style="flex:1;">

                                <div class="nama-col">
                                    ${w.nama ?? '-'}
                                </div>

                                <div class="sub-info fw-bold">
                                  BLOK ${w.kamar?.kode_blok ?? '-'} - ${
                                        w.kamar?.lokasi_sel
                                    }
                                </div>

                                ${statusBadge}

                                <div class="mt-2 d-flex gap-2">
                                    ${
                                        fullAccess
                                        ? `<button class="action-btn btn-detail"data-id="${w.id}"> Detail </button> <button class="action-btn btn-delete" data-id="${w.id}"> Hapus </button> ` : '' }
                                </div>

                            </div>

                        </div>

                    `;
                        });

                        html += `

                        </div>

                        <div class="d-flex justify-content-center mt-4">
                `;

                        if (res.current_page > 1) {

                            html += `
                        <button
                            class="btn btn-outline-primary me-2 page-btn"
                            data-page="${res.current_page - 1}">
                            Prev
                        </button>
                    `;
                        }

                        html += `
                    <span class="align-self-center">
                        Halaman ${res.current_page} / ${res.last_page}
                    </span>
                `;

                        if (res.current_page < res.last_page) {

                            html += `
                        <button
                            class="btn btn-outline-primary ms-2 page-btn"
                            data-page="${res.current_page + 1}">
                            Next
                        </button>
                    `;
                        }

                        html += `
                        </div>
                    </div>
                </div>
                `;
                    }

                    $('#tableWrapper').html(html);

                    $('#totalBadge').text(
                        res.total + ' Orang'
                    );

                },

                error: function(xhr) {

                    console.log(xhr.responseText);

                    $('#tableWrapper').html(`
                <div class="alert alert-danger">
                    Gagal memuat data
                </div>
            `);

                }

            });
        }

        // ================= TAB =================
        $(document).on('click', '.status-tab', function() {

            $('.status-tab').removeClass('active');

            $(this).addClass('active');

            currentStatus = $(this).data('status');

            currentPage = 1;

            loadStatus(currentStatus, currentPage);

        });

        // ================= AUDIT BADGE =================
        $(document).on('click', '.audit-filter', function() {

            $('.audit-filter').removeClass('active');

            if (currentAudit === $(this).data('filter')) {

                currentAudit = '';

            } else {

                $(this).addClass('active');

                currentAudit = $(this).data('filter');

            }

            currentPage = 1;

            loadStatus(currentStatus, currentPage);

        });

        // ================= SEARCH =================
        let typingTimer;

        $('#search').on('keyup', function() {

            clearTimeout(typingTimer);

            typingTimer = setTimeout(function() {

                currentPage = 1;

                loadStatus(
                    currentStatus,
                    currentPage
                );

            }, 300);

        });

        // ================= PAGINATION =================
        $(document).on('click', '.page-btn', function() {

            currentPage = $(this).data('page');

            loadStatus(
                currentStatus,
                currentPage
            );

        });

        // ================= DETAIL =================
        $(document).on('click', '.btn-detail', function() {

            let id = $(this).data('id');

            $('#modalDetail').fadeIn(200);

            $('#detailBody').html('Loading...');

            $.get('/admin-banceuy/wbp/' + id + '/detail', function(res) {

                let status = res.status_kamar ?? '-';
                let noReg = res.no_reg_instansi ?? '-';
                let blok = res.blok ?? '-';
                let sel = res.sel ?? '-';
                let nama = res.nama ?? '-';
                let tanggal = res.tanggal ?? '-';
                let keterangan = res.keterangan ?? '';
                let jenis_kejahatan = res.jenis_kejahatan ?? '';
                let bulanRemisi = res.total_bulan_remisi ?? '-';
                let hariRemisi = res.total_hari_remisi ?? '-';

                let statusBadge = '';

                if (status === 'Terbuka') {

                    statusBadge = `
                    <span style="
                        background:#28a745;
                        color:#fff;
                        padding:6px 12px;
                        border-radius:8px;
                        font-weight:600;
                    ">
                        Terbuka
                    </span>
                `;

                } else if (status === 'Tertutup') {

                    statusBadge = `
                    <span style="
                        background:#dc3545;
                        color:#fff;
                        padding:6px 12px;
                        border-radius:8px;
                        font-weight:600;
                    ">
                        Tertutup
                    </span>
                `;

                } else {

                    statusBadge = `
                    <span style="
                        background:#6c757d;
                        color:#fff;
                        padding:6px 12px;
                        border-radius:8px;
                        font-weight:600;
                    ">
                        -
                    </span>
                `;

                }

                $('#detailBody').html(`

                <h5 class="fw-bold mb-3">
                    ${nama}
                </h5>

                <p class="fw-bold">
                    BLOK ${blok} - ${sel}
                </p>

                <hr>

                <p>
                    <b>No Reg:</b><br>
                    ${noReg}
                </p>

                <p>
                    <b>Total Remisi:</b><br>
                    ${bulanRemisi} Bulan ${hariRemisi} Hari
                </p>

                <p>
                    <b>Jenis Kejahatan:</b><br>
                    ${jenis_kejahatan}
                </p>

                <p>
                    <b>Status:</b><br>
                    ${statusBadge}
                </p>


                <div class="mt-3">

                    <label
                        class="fw-bold mb-2 d-block">
                        Tanggal Keterangan
                    </label>

                    <div
                        class="border rounded p-2 bg-light">

                        ${tanggal}

                    </div>

                </div>

                <div class="mt-3">

                        <label class="fw-bold mb-2 d-block">
                            Keterangan
                        </label>

                        <div
                            style="
                                min-height:120px;
                                border:1px solid #dee2e6;
                                border-radius:12px;
                                padding:16px;
                                background:#f8f9fa;
                                white-space:pre-wrap;
                                word-break:break-word;
                                overflow-wrap:break-word;
                                font-size:15px;
                                line-height:1.7;
                                color:#495057;
                                text-align:left;
                                display:flex;
                                align-items:flex-start;
                                justify-content:flex-start;
                            ">

                            ${
                                keterangan
                                    ? `<div style="width:100%;">${keterangan}</div>`
                                    : `<span class="text-muted fst-italic">Belum ada keterangan</span>`
                            }

                        </div>

                    </div>

               <div class="mt-4 text-end d-flex gap-2 justify-content-end">

                    <button
                        class="btn btn-info btn-edit-status"
                        data-id="${res.id ?? ''}"
                        data-nama="${nama}"
                        data-status="${res.status_wbp ?? ''}">
                        Edit Status
                    </button>

                    <button
                        class="btn btn-warning btn-edit-kamar"
                        data-id="${res.id ?? ''}"
                        data-nama="${nama}"
                        data-kamar="${blok} - ${sel}">
                        Edit Kamar
                    </button>

                    <button
                        class="btn btn-secondary btn-edit-keterangan"
                        data-id="${res.id ?? ''}"
                        data-nama="${nama}"
                        data-tanggal="${res.tanggal ?? ''}"
                        data-keterangan="${res.keterangan ?? ''}">
                        Edit Keterangan
                    </button>

                </div>

            `);

            });

        });

        // ================= OPEN EDIT KAMAR =================
        $(document).on('click', '.btn-edit-kamar', function() {

            let wbpId = $(this).data('id');
            let nama = $(this).data('nama');
            let kamarSaatIni = $(this).data('kamar');


            // ================= SAFETY GUARD =================
            if (!wbpId) {
                alert('WBP ID tidak ditemukan di tombol');
                return;
            }

            $('#modalDetail').fadeOut(200);
            $('#modalEditKamar').fadeIn(200);

            $('#editBody').html(`
            <div class="text-center p-4">
                Loading...
            </div>
        `);

            $.get('/admin-banceuy/kamar/all', function(kamarList) {

                let option = `<option value="">Pilih Kamar Tujuan</option>`;

                let currentBlok = '';

                $.each(kamarList, function(i, kamar) {

                    if (currentBlok !== kamar.kode_blok) {

                        currentBlok = kamar.kode_blok;

                        option += `
                        <optgroup label="BLOK ${currentBlok}">
                    `;
                    }

                    option += `
                    <option value="${kamar.id}">
                        ${
                            kamar.lokasi_sel
                        }
                    </option>
                `;

                    if (
                        i === kamarList.length - 1 ||
                        kamarList[i + 1].kode_blok !== currentBlok
                    ) {
                        option += `</optgroup>`;
                    }

                });

                $('#kamar_id').html(option);

                $('#editBody').html(`

                <input type="hidden" id="edit_wbp_id" value="${wbpId}">

                <div class="mb-3">
                    <label>Nama WBP</label>
                    <input type="text" class="form-control" value="${nama ?? ''}" readonly>
                </div>

                <div class="mb-3">
                    <label>Kamar Saat Ini</label>
                    <input type="text" class="form-control" value="BLOK ${kamarSaatIni ?? ''}" readonly>
                </div>

                <div class="mb-3">
                    <label>Kamar Tujuan</label>
                    <select id="kamar_tujuan" class="form-control">
                        ${option}
                    </select>
                </div>

                <div class="d-flex justify-content-center gap-2 mt-3">

                    <button type="button" class="btn btn-secondary" id="closeEditModal">
                        Batal
                    </button>

                    <button type="button" class="btn btn-success" id="btnSimpanKamar">
                        Simpan
                    </button>

                </div>

            `);

            });

        });

        // ================= SAVE EDIT KAMAR =================
        $(document).on('click', '#btnSimpanKamar', function() {

            let wbpId = $('#edit_wbp_id').val();
            let kamarId = $('#kamar_tujuan').val();

            // ================= SAFETY GUARD =================
            if (!wbpId || wbpId === 'undefined') {
                alert('WBP ID tidak valid');
                return;
            }

            if (!kamarId) {
                alert('Pilih kamar tujuan');
                return;
            }

            $.post('/admin-banceuy/wbp/update-kamar', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                wbp_id: wbpId,
                kamar_id: kamarId

            }, function(res) {
                alert(res.message ?? 'Kamar berhasil diperbarui');
                $('#modalEditKamar').fadeOut(200);
                location.reload();

            }).fail(function(xhr) {
                alert(xhr.responseJSON?.message ?? 'Gagal memperbarui kamar');
            });

        });

        $(document).on('click', '.btn-edit-status', function() {

            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let status = $(this).data('status');
            let statusKamar = $(this).data('status-kamar');

            $('#modalDetail').fadeOut(200);
            $('#modalEditStatus').fadeIn(200);

            $('#statusBody').html(`

        <input
            type="hidden"
            id="status_wbp_id"
            value="${id}">

        <div class="mb-3">
            <label>Nama WBP</label>
            <input
                type="text"
                class="form-control"
                value="${nama}"
                readonly>
        </div>

        <div class="mb-3">
            <label>Status WBP</label>
            <select
                id="status_wbp"
                class="form-control">
                <option value="AKTIF">AKTIF</option>
                <option value="PINDAH UPT">PINDAH UPT</option>
                <option value="BON">BON</option>
                <option value="SAKIT">SAKIT</option>
                <option value="PULANG">PULANG</option>
                <option value="MENINGGAL">MENINGGAL</option>
            </select>

        </div>

        <div class="mb-3">
            <label>Status Kamar</label>
            <select
                id="status_kamar"
                class="form-control">
                <option value="Terbuka">Terbuka</option>
                <option value="Tertutup">Tertutup</option>
            </select>
        </div>
        <div class="text-end">
            <button
                class="btn btn-success"
                id="btnSimpanStatus">
                Simpan
            </button>
        </div>
    `);
            $('#status_wbp').val(status);
            $('#status_kamar').val(statusKamar);
        });


        $(document).on('click', '#btnSimpanStatus', function() {
            $.ajax({
                url: '/admin-banceuy/wbp/update-status',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: $('#status_wbp_id').val(),
                    status_wbp: $('#status_wbp').val(),
                    status_kamar: $('#status_kamar').val()
                },

                success: function(res) {
                    if (res.success) {
                        $('#modalEditStatus').fadeOut(200);
                        $('#count-isolasi').text(res.count_isolasi);
                        loadStatus(currentStatus, currentPage);
                    }
                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        // ================= Modal Keterangan =================
        $(document).on('click', '#closeStatusModal', function() {
            $('#modalEditStatus').fadeOut(200);
        });

        $(window).click(function(e) {
            if ($(e.target).is('#modalEditStatus')) {
                $('#modalEditStatus').fadeOut(200);
            }
        });


        $(document).on(
            'click',
            '.btn-edit-keterangan',
            function() {

                let id = $(this).data('id');
                let nama = $(this).data('nama');
                let tanggal = $(this).data('tanggal') || '';
                let keterangan = $(this).data('keterangan') || '';

                $('#keteranganBody').html(`

            <input
                type="hidden"
                id="wbp_id"
                value="${id}">

            <div class="mb-3">

                <label class="form-label">
                    Nama WBP
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="${nama}"
                    readonly>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Tanggal
                </label>

                <input
                type="datetime-local"
                id="tanggal"
                class="form-control">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Keterangan
                </label>

                <textarea
                    id="keterangan"
                    class="form-control"
                    rows="5">${keterangan}</textarea>

            </div>

            <button
                id="btnSimpanKeterangan"
                class="btn btn-primary w-100">

                Simpan

            </button>

        `);

                $('#modalDetail').fadeOut(200, function() {

                    $('#modalEditKeterangan').fadeIn(200);

                });

            }
        );

        $(document).on(
            'click',
            '#btnSimpanKeterangan',
            function() {
                let btn = $(this);
                btn.prop('disabled', true);
                $.ajax({
                    url: '/admin-banceuy/wbp/update-keterangan',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    data: {
                        id: $('#wbp_id').val(),
                        tanggal: $('#tanggal').val(),
                        keterangan: $('#keterangan').val()
                    },
                    success: function(res) {
                        if (res.success) {
                            $('#modalEditKeterangan')
                                .fadeOut(200);

                            // refresh detail WBP
                            loadDetailWbp(
                                $('#wbp_id').val()
                            );

                        } else {
                            alert(
                                res.message ??
                                'Gagal menyimpan'
                            );
                        }
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                        if (
                            xhr.responseJSON &&
                            xhr.responseJSON.errors
                        ) {

                            let errors = [];
                            $.each(
                                xhr.responseJSON.errors,
                                function(key, value) {
                                    errors.push(
                                        value.join('\n')
                                    );
                                }
                            );
                            alert(
                                errors.join('\n\n')
                            );
                        } else {
                            alert(
                                'Terjadi kesalahan server'
                            );
                        }
                    },

                    complete: function() {
                        btn.prop(
                            'disabled',
                            false
                        );
                    }
                });
            }
        );

        // ================= CLOSE KETERANGAN =================
        $('#closeKeteranganModal').click(function() {
            $('#modalEditKeterangan').fadeOut(200);
        });

        // ================= CLOSE DETAIL =================
        $('#closeModal').click(function() {
            $('#modalDetail').fadeOut(200);
        });
        // ================= CLOSE EDIT =================
        $(document).on('click', '#closeEditModal', function() {

            $('#modalEditKamar').fadeOut(200);
        });

        // ================= CLICK OUTSIDE =================
        $(window).click(function(e) {

            if ($(e.target).is('#modalDetail')) {
                $('#modalDetail').fadeOut(200);
            }

            if ($(e.target).is('#modalEditKamar')) {
                $('#modalEditKamar').fadeOut(200);
            }

        });
    </script>

    <script>
        $(function() {

            // ================= SEARCH =================
            let t;
            $('#search').on('input', function() {
                clearTimeout(t);
                t = setTimeout(() => {
                    $('#tableWrapper').html(`
                <div class="text-center p-4">
                    Loading...
                </div>
            `);

                    $.get(window.location.pathname, {
                        search: $('#search').val()
                    }, function(res) {

                        $('#tableWrapper').html(
                            $(res).find('#tableWrapper').html()
                        );
                    });
                }, 300);
            });

            // ================= MODAL OPEN IMPORT =================
            $('#btnImportExcel').on('click', function() {
                $('#importExcelModal').addClass('active');
            });

            // ================= CLOSE IMPORT =================
            $(document).on('click', '#importExcelModal .jq-close', function() {
                $('#importExcelModal').removeClass('active');
            });

            // ================= CLOSE IMPORT OVERLAY =================
            $(document).on('click', '#importExcelModal', function(e) {
                if (e.target.id === 'importExcelModal') {
                    $('#importExcelModal').removeClass('active');
                }
            });
        });
    </script>

    <!--Delete wbp script-->
    <script>
        $(function() {

            let deleteId = null;
            let deleteCard = null;
            let isProcessing = false;

            // ================= OPEN DELETE MODAL =================
            $(document).on('click', '.btn-delete', function() {

                deleteId = $(this).data('id');
                deleteCard = $(this).closest('.main-row');

                $('#deletePassword').val('');
                $('#deleteModal').css('display', 'flex');

                setTimeout(function() {
                    $('#deletePassword').trigger('focus');
                }, 100);

            });

            // ================= CONFIRM DELETE =================
            $('#confirmDelete').on('click', function() {

                if (isProcessing) return;

                let password = $('#deletePassword').val().trim();

                if (!password) {
                    alert('Password wajib diisi.');
                    $('#deletePassword').focus();
                    return;
                }

                isProcessing = true;

                $('#confirmDelete').prop('disabled', true).text('Menghapus...');

                $.ajax({

                    url: '/admin-banceuy/wbp/' + deleteId,
                    type: 'POST',

                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                        password: password,
                        search: $('#search').val() || ''
                    },

                    success: function(res) {

                        if (res && res.success) {

                            $('#deleteModal').hide();
                            $('#deletePassword').val('');

                            if (deleteCard && deleteCard.length) {
                                deleteCard.fadeOut(200, function() {
                                    $(this).remove();
                                });
                            }

                        } else {
                            alert(res.message || 'Gagal menghapus data.');
                        }

                    },

                    error: function(xhr) {

                        if (xhr.status === 422) {
                            alert(xhr.responseJSON?.message || 'Password salah.');
                        } else if (xhr.status === 500) {
                            alert('Terjadi kesalahan server.');
                        } else {
                            alert('Request gagal, coba lagi.');
                        }

                    },

                    complete: function() {
                        isProcessing = false;
                        $('#confirmDelete').prop('disabled', false).text('🗑️ Hapus');
                    }
                });
            });

            // ================= CLOSE MODAL DELETE =================
            function closeModal() {
                $('#deletePassword').val('');
                $('#deleteModal').hide();
            }

            $('#closeDeleteModal, #cancelDelete').on('click', closeModal);

            // ================= CLICK OUTSIDE MODAL =================
            $('#deleteModal').on('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // ================= ENTER DELETE KEY =================
            $('#deletePassword').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#confirmDelete').trigger('click');
                }
            });

        });
    </script>

    <script>
        $(function() {

            $('#btnTambahWbp').on('click', function() {

                $('#modalTambahWbp').addClass('show');
            });
            $('#closeTambahModal, #btnBatalTambah').on('click', function() {
                $('#modalTambahWbp').removeClass('show');
            });

            $('#modalTambahWbp').on('click', function(e) {
                if (e.target === this) {
                    $(this).removeClass('show');
                }
            });

        });
    </script>

    <script>
        const previewImportUrl = "{{ route('wbp.import.preview') }}";
        const importUrl = "{{ route('wbp.import') }}";
    </script>

    {{-- ================= JS IMPORT ================= --}}
    <script>
        $(function() {

            // ================= OPEN IMPORT MODAL =================
            $('#btnImportExcel').on('click', function() {
                resetImportModal();
                $('#importExcelModal').addClass('active');
            });

            // ================= MODAL CLOSE IMPORT =================
            $(document).on('click', '#importExcelModal .jq-close', function() {
                resetImportModal();
            });

            // ================= CLOSE IMPORT OVERLAY =================
            $(document).on('click', '#importExcelModal', function(e) {
                if (e.target.id === 'importExcelModal') {
                    resetImportModal();
                }
            });

            // ================= MODAL PREVIEW IMPORT =================
            $('#btnPreviewImport').on('click', function() {

                let btn = $(this);

                if ($('#fileExcel').val() === '') {
                    alert('Silakan pilih file Excel terlebih dahulu.');
                    return;
                }

                let formData = new FormData($('#importWbpForm')[0]);

                btn.prop('disabled', true).text('Memproses...');

                $.ajax({

                    url: previewImportUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(res) {

                        $('#previewMode').text(res.mode);
                        $('#previewTotal').text(res.total);
                        $('#previewInsert').text(res.insert);
                        $('#previewUpdate').text(res.update);
                        $('#previewInvalid').text(res.invalid);

                        $('#previewResult').fadeIn(200);
                        $('#btnImportNow').fadeIn(200);

                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert(xhr.responseJSON?.message ?? 'Preview gagal.');
                    },

                    complete: function() {
                        btn.prop('disabled', false).html('👁 Preview Import');
                    }

                });

            });

            // ================= IMPORT EXCEL =================
            $('#btnImportNow').on('click', function() {

                console.log('1. Tombol Import diklik');

                let btn = $(this);

                if (!confirm('Yakin ingin mengimpor data WBP ini?')) {
                    console.log('2. User membatalkan import');
                    return;
                }

                console.log('3. Konfirmasi OK');

                let form = $('#importWbpForm')[0];

                console.log('4. Form ditemukan:', form);

                let formData = new FormData(form);

                console.log('5. FormData berhasil dibuat');

                console.log('6. Import URL:', importUrl);

                btn.prop('disabled', true).html('⏳ Mengimpor...');

                $.ajax({

                    beforeSend: function() {
                        console.log('7. AJAX akan dikirim...');
                    },

                    url: importUrl,

                    type: 'POST',

                    data: formData,

                    processData: false,

                    contentType: false,

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(res) {

                        console.log('8. SUCCESS', res);

                        alert(
                            res.message +
                            '\n\n' +
                            'Data Baru : ' + res.inserted +
                            '\nUpdate : ' + res.updated +
                            '\nDilewati : ' + res.skipped
                        );

                        resetImportModal();

                        loadStatus(currentStatus, currentPage);

                    },

                    error: function(xhr) {

                        console.log('9. ERROR', xhr);
                        console.log(xhr.responseText);

                        if (xhr.responseJSON?.errors) {

                            let pesan = '';

                            $.each(xhr.responseJSON.errors, function(key, value) {
                                pesan += value.join('\n') + '\n';
                            });

                            alert(pesan);

                        } else {

                            alert(
                                xhr.responseJSON?.message ??
                                'Import gagal.'
                            );

                        }

                    },

                    complete: function() {

                        console.log('10. COMPLETE');

                        btn
                            .prop('disabled', false)
                            .html('🚀 Import Sekarang');

                    }

                });

            });

            // ================= HELPER =================
            function resetImportModal() {

                $('#importWbpForm')[0].reset();

                $('#previewResult').hide();

                $('#btnImportNow').hide();

                $('#previewMode').text('-');
                $('#previewTotal').text('0');
                $('#previewInsert').text('0');
                $('#previewUpdate').text('0');
                $('#previewInvalid').text('0');

                $('#importExcelModal').removeClass('active');

            }

        });
    </script>
@endsection
