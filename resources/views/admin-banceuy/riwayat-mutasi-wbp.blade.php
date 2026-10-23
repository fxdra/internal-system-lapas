<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Riwayat Mutasi WBP</title>

    <script>
        (function() {
            try {
                var theme = localStorage.getItem('app-skin-dark');

                if (theme === 'app-skin-dark') {
                    document.documentElement.classList.add('app-skin-dark');
                }
            } catch (e) {}
        })();
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: #fff;
            padding: 20px;
            font-size: 13px;
        }

        .header {
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .card-header {
            background: #222;
            color: #fff;
            padding: 10px 15px;
            font-weight: 600;
        }

        .table-fixed {
            table-layout: fixed;
            width: 100%;
            min-width: 1510px;
            border-collapse: collapse;
        }

        .table-fixed th,
        .table-fixed td {
            vertical-align: middle;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .table-fixed .text-truncate {
            display: block;
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .col-negara,
        .col-agama,
        .col-kejahatan,
        .col-putusan,
        .col-ekspirasi,
        .col-kamar,
        .col-alasan {
            text-align: center;
        }

        /* WIDTH KOLOM - PROPORSIONAL FIXED */

        .col-nama {
            width: 18%;
            min-width: 180px;
            max-width: 180px;
        }

        .col-negara {
            width: 7%;
            min-width: 90px;
            max-width: 90px;
        }

        .col-agama {
            width: 6%;
            min-width: 80px;
            max-width: 80px;
        }

        .col-putusan {
            width: 6%;
            min-width: 70px;
            max-width: 70px;
        }

        .col-ekspirasi {
            width: 10%;
            min-width: 120px;
            max-width: 120px;
        }

        .col-kejahatan {
            width: 16%;
            min-width: 170px;
            max-width: 170px;
        }

        .col-kamar {
            width: 12%;
            min-width: 140px;
            max-width: 140px;
        }

        .col-alasan {
            width: 15%;
            min-width: 180px;
            max-width: 180px;
        }

        .col-tanggal {
            width: 10%;
            min-width: 120px;
            max-width: 120px;
            text-align: center;
        }

        .col-action {
            width: 14%;
            min-width: 155px;
            text-align: center;
            white-space: nowrap;
        }

        .col-action .btn {
            white-space: nowrap;
        }

        /* =========================
        MUTASI HIDE
        ========================= */

        .mutasi-row-hidden {
            opacity: 0.5;
            background-color: #e9ecef !important;
        }

        .mutasi-row-hidden td {
            color: #6c757d !important;
        }

        /* DARK MODE - RIWAYAT MUTASI WBP */
        @media screen {
            html.app-skin-dark body {
                background: #0f172a !important;
                color: #e2e8f0;
            }

            /* CARD */
            html.app-skin-dark .card {
                background-color: #182235;
                color: #e2e8f0;
                border-color: #334155 !important;
            }

            html.app-skin-dark .card-body {
                color: #e2e8f0;
            }

            html.app-skin-dark .card-header {
                background-color: #263449;
                color: #f1f5f9;
                border-color: #334155;
            }

            html.app-skin-dark .header-block {
                background-color: #182235;
            }

            /* JUDUL DAN BREADCRUMB */
            html.app-skin-dark h4,
            html.app-skin-dark h5,
            html.app-skin-dark strong,
            html.app-skin-dark label,
            html.app-skin-dark .form-label {
                color: #e2e8f0;
            }

            html.app-skin-dark .breadcrumb-item,
            html.app-skin-dark .breadcrumb-item.active {
                color: #a8b5c7;
            }

            html.app-skin-dark .breadcrumb-item a {
                color: #93c5fd;
            }

            html.app-skin-dark .breadcrumb-item+.breadcrumb-item::before {
                color: #64748b;
            }

            /* INPUT DAN FILTER */
            html.app-skin-dark .form-control,
            html.app-skin-dark .form-select {
                background-color: #0f172a;
                color: #e2e8f0;
                border-color: #475569;
            }

            html.app-skin-dark .form-control::placeholder {
                color: #94a3b8;
            }

            html.app-skin-dark .form-control:focus,
            html.app-skin-dark .form-select:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 .2rem rgba(96, 165, 250, .15);
            }

            html.app-skin-dark .form-select option {
                background-color: #182235;
                color: #e2e8f0;
            }

            html.app-skin-dark input[type="date"]::-webkit-calendar-picker-indicator {
                filter: invert(1);
                cursor: pointer;
            }

            /* TABEL */
            html.app-skin-dark .table {
                --bs-table-color: #e2e8f0;
                --bs-table-bg: #182235;
                --bs-table-border-color: #334155;
                --bs-table-striped-bg: #202d42;
                --bs-table-striped-color: #e2e8f0;
                --bs-table-hover-bg: #263449;
                --bs-table-hover-color: #ffffff;

                color: #e2e8f0;
                border-color: #334155;
            }

            html.app-skin-dark .table-light {
                --bs-table-bg: #263449;
                --bs-table-color: #f1f5f9;
                --bs-table-border-color: #475569;

                background-color: #263449 !important;
                color: #f1f5f9 !important;
            }

            html.app-skin-dark .table-success {
                --bs-table-bg: #123c35;
                --bs-table-color: #d1fae5;
                --bs-table-border-color: #27675a;
            }

            /* MUTASI YANG DISEMBUNYIKAN */
            html.app-skin-dark .mutasi-row-hidden {
                background-color: #293244 !important;
                opacity: .65;
            }

            html.app-skin-dark .mutasi-row-hidden td {
                color: #a8b5c7 !important;
            }

            /* SELECT2 - PENCARIAN WBP */
            html.app-skin-dark .select2-container--default .select2-selection--single {
                background-color: #0f172a;
                border-color: #475569;
                color: #e2e8f0;
            }

            html.app-skin-dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #e2e8f0;
            }

            html.app-skin-dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #94a3b8;
            }

            html.app-skin-dark .select2-container--default .select2-selection--single .select2-selection__arrow b {
                border-top-color: #cbd5e1;
            }

            html.app-skin-dark .select2-dropdown {
                background-color: #182235;
                border-color: #475569;
                color: #e2e8f0;
            }

            html.app-skin-dark .select2-container--default .select2-results__option {
                background-color: #182235;
                color: #e2e8f0;
            }

            html.app-skin-dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #1d4ed8;
                color: #ffffff;
            }

            html.app-skin-dark .select2-container--default .select2-results__option[aria-selected="true"] {
                background-color: #263449;
                color: #ffffff;
            }

            html.app-skin-dark .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: #0f172a;
                color: #e2e8f0;
                border-color: #475569;
            }

            /* MODAL EDIT */
            html.app-skin-dark .modal-content {
                background-color: #182235;
                color: #e2e8f0;
                border-color: #334155;
            }

            html.app-skin-dark .modal-header,
            html.app-skin-dark .modal-footer {
                border-color: #334155;
            }

            html.app-skin-dark .modal-title {
                color: #f1f5f9;
            }

            html.app-skin-dark .modal-body label {
                color: #e2e8f0;
            }

            html.app-skin-dark .modal-content .btn-close {
                filter: invert(1);
            }

            /* RINGKASAN DAN GRAND TOTAL */
            html.app-skin-dark .badge.bg-success {
                color: #ffffff;
            }

            html.app-skin-dark .btn-outline-secondary {
                color: #cbd5e1;
                border-color: #64748b;
            }

            html.app-skin-dark .btn-outline-secondary:hover {
                background-color: #334155;
                color: #ffffff;
            }

            /* TANGGAL DAN TANDA TANGAN */
            html.app-skin-dark .text-muted,
            html.app-skin-dark .small {
                color: #a8b5c7 !important;
            }
        }


        /* =========================
                PRINT
        ========================= */

        @media print {

            .header-block,
            .no-print,
            .col-action {
                display: none !important;
            }

            body {
                background: #fff;
                padding: 0;
                font-size: 9px;
            }

            /* BARIS YANG DI-HIDE TIDAK IKUT PRINT */
            .mutasi-row-hidden {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                margin-bottom: 10px;
            }

            .card-header {
                background: #fff !important;
                color: #000 !important;
                border-bottom: 1px solid #000;
                padding: 5px;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .table-fixed {
                width: 100% !important;
                min-width: 0 !important;
                table-layout: fixed;
                border-collapse: collapse;
            }

            .table-fixed th,
            .table-fixed td {
                border: 1px solid #000 !important;
                padding: 2px !important;
                font-size: 8px !important;
                line-height: 1.1;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .table-fixed .text-truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            /* proporsi kolom saat print (FIXED & PROPORTIONAL) */

            .col-nama {
                width: 18%;
            }

            .col-negara {
                width: 7%;
            }

            .col-agama {
                width: 6%;
            }

            .col-putusan {
                width: 7%;
            }

            .col-ekspirasi {
                width: 9%;
            }

            .col-kejahatan {
                width: 14%;
            }

            .col-kamar {
                width: 14%;
            }

            .col-alasan {
                width: 13%;
            }

            .col-tanggal {
                width: 9%;
            }

            @page {
                size: A4 potrait, landscape;
                margin: 5mm;
            }
        }
    </style>

</head>

<body>

    <div class="card border-2 shadow-sm mb-4 header-block">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <div class="ms-3">
                        <h4 class="mb-1 fw-bold">
                            RIWAYAT MUTASI WBP
                        </h4>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="/admin-banceuy">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">
                                    Riwayat Mutasi
                                </li>
                            </ol>
                        </nav>
                    </div>

                </div>

                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>
                    🖨 Print
                </button>

            </div>

        </div>
    </div>

    <div class="card mb-3 no-print">

        <div class="card-body">

            @if (session('error'))
                <div class="alert alert-danger no-print">
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-3">

                        <label class="form-label">
                            Filter
                        </label>

                        <select name="filter" id="filter" class="form-select">
                            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>
                                Hari Ini
                            </option>

                            <option value="yesterday" {{ $filter == 'yesterday' ? 'selected' : '' }}>
                                Kemarin
                            </option>

                            <option value="3days" {{ $filter == '3days' ? 'selected' : '' }}>
                                3 Hari Terakhir
                            </option>

                            <option value="7days" {{ $filter == '7days' ? 'selected' : '' }}>
                                7 Hari Terakhir
                            </option>

                            <option value="1month" {{ $filter == '1month' ? 'selected' : '' }}>
                                1 Bulan Terakhir
                            </option>

                            <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>
                                Pilih Rentang Tanggal
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Cari Riwayat WBP
                        </label>

                        <select name="search" id="searchWbp" class="form-select">
                            <option value="">
                                Pilih WBP...
                            </option>
                            @foreach ($wbps as $wbp)
                                <option value="{{ $wbp->nama }}"
                                    {{ request('search') == $wbp->nama ? 'selected' : '' }}>
                                    {{ $wbp->nama }}
                                    —
                                    BLOK {{ $wbp->blok }}
                                    -
                                    {{ $wbp->kamar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 custom-range" style="{{ $filter == 'custom' ? '' : 'display:none' }}">
                        <label class="form-label">
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2 custom-range" style="{{ $filter == 'custom' ? '' : 'display:none' }}">
                        <label class="form-label">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (request('search'))

        @php
            $lastMutasi = $data->first();
        @endphp

        <div class="card mb-3">

            <div class="card-header">

                Riwayat Perpindahan Kamar WBP

            </div>

            <div class="card-body d-flex justify-content-between align-items-center">

                <div>

                    <div>
                        <strong>Nama WBP :</strong>
                        {{ strtoupper($lastMutasi?->nama ?? '-') }}
                    </div>

                    <div>
                        <strong>Total Riwayat :</strong>
                        {{ $totalMutasi }} Mutasi
                    </div>

                    <div>
                        <strong>Kamar Saat Ini :</strong>

                        <span class="badge bg-success fs-6">
                            BLOK {{ $lastMutasi?->kamarTujuan?->kode_blok ?? '-' }}
                            -
                            {{ $lastMutasi?->kamarTujuan?->lokasi_sel ?? '-' }}
                        </span>
                    </div>

                    <div>
                        <strong>Mutasi Terakhir :</strong>
                        {{ $lastMutasi?->created_at?->format('d-m-Y H:i') ?? '-' }}
                    </div>

                </div>

                <a href="{{ route('mutasi.riwayat') }}" class="btn btn-outline-secondary">
                    Reset
                </a>

            </div>

        </div>

        <div class="card">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover table-sm mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>Nama WBP</th>
                                <th>Kamar Asal</th>
                                <th>Kamar Tujuan</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($data as $row)
                                <tr class="{{ $row->id == $lastMutasi->id ? 'table-success' : '' }}">

                                    <td>{{ $row->nama }}</td>

                                    <td>
                                        BLOK {{ $row->kamarAsal->kode_blok ?? '-' }}
                                        -
                                        {{ $row->kamarAsal->lokasi_sel ?? '-' }}
                                    </td>

                                    <td>
                                        BLOK {{ $row->kamarTujuan->kode_blok ?? '-' }}
                                        -
                                        {{ $row->kamarTujuan->lokasi_sel ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}
                                    </td>

                                    <td>{{ $row->alasan }}</td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center">
                                        Tidak ada riwayat mutasi.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    @else
        @foreach ($grouped as $kamar => $items)
            @php
                $kamar = $items->first()?->kamarAsal;

                $kodeBlok = $kamar->kode_blok ?? '-';

                preg_match('/(\d+)/', $kamar->lokasi_sel ?? '', $kamarNo);

                $nomorKamar = $kamarNo[1] ?? '-';
            @endphp

            <div class="card">

                <div class="card-header">

                    BLOK {{ $kodeBlok }} - KAMAR {{ $nomorKamar }}

                    <span class="float-end">
                        Total:
                        {{ $items->count() }}
                    </span>

                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm align-middle mb-0 table-fixed">
                            <thead class="table-light">
                                <tr>
                                    <th class="col-nama text-center">Nama</th>
                                    <th class="col-kamar">Kamar Tujuan</th>
                                    <th class="col-agama">Agama</th>
                                    <th class="col-putusan">Pidana</th>
                                    <th class="col-ekspirasi">Ekspirasi</th>
                                    <th class="col-kejahatan">Jenis Kejahatan</th>
                                    <th class="col-tanggal">Tanggal</th>
                                    <th class="col-action text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($items as $row)
                                    <tr class="mutasi-row {{ $row->is_hidden ? 'mutasi-row-hidden' : '' }}"
                                        data-id="{{ $row->id }}">

                                        <td class="col-nama">
                                            <div class="text-truncate" title="{{ $row->nama }}">
                                                {{ $row->nama }}
                                            </div>
                                        </td>

                                        <td class="col-kamar">
                                            <div class="text-truncate" title="{{ $row->kamar_tujuan_nama }}">
                                                BLOK {{ $row->kamarTujuan->kode_blok ?? '-' }} -
                                                {{ $row->kamarTujuan->lokasi_sel ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="col-agama">
                                            <div class="text-truncate" title="{{ $row->agama }}">
                                                {{ $row->agama }}
                                            </div>
                                        </td>

                                        <td class="col-putusan">
                                            <div class="text-truncate">
                                                {{ $row->wbp?->putusan ? $row->wbp->putusan . ' Tahun' : '' }}
                                                {{ $row->wbp?->putusan_bulan ? $row->wbp->putusan_bulan . ' Bulan' : '' }}
                                            </div>
                                        </td>

                                        <td class="col-ekspirasi">
                                            <div class="text-truncate" title="{{ $row->ekspirasi }}">
                                                {{ $row->ekspirasi }}
                                            </div>
                                        </td>

                                        <td class="col-kejahatan">
                                            <div class="text-truncate" title="{{ $row->jenis_kejahatan }}">
                                                {{ $row->jenis_kejahatan }}
                                            </div>
                                        </td>

                                        <td class="col-tanggal">
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}
                                        </td>

                                        <td class="col-action">
                                            <div class="d-flex justify-content-center align-items-center gap-1">

                                                {{-- HIDE / SHOW --}}
                                                <button type="button"
                                                    class="btn btn-sm {{ $row->is_hidden ? 'btn-success' : 'btn-secondary' }} btn-hide-mutasi"
                                                    onclick="toggleHideMutasi(this)"
                                                    title="{{ $row->is_hidden ? 'Tampilkan kembali saat print' : 'Sembunyikan dari print' }}">

                                                    <i
                                                        class="fas {{ $row->is_hidden ? 'fa-eye' : 'fa-eye-slash' }}"></i>

                                                    <span class="hide-text">
                                                        {{ $row->is_hidden ? 'Show' : 'Hide' }}
                                                    </span>
                                                </button>

                                                {{-- EDIT --}}
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $row->id }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>

                                                {{-- HAPUS --}}
                                                <form action="{{ route('mutasi.destroy', $row->id) }}" method="POST"
                                                    class="m-0" onsubmit="return confirm('Yakin hapus data ini?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>

                                                </form>

                                            </div>
                                        </td>

                                    </tr>

                                    <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">

                                                <form action="{{ route('mutasi.update', $row->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Edit Riwayat Mutasi
                                                        </h5>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal">
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">

                                                        <div class="mb-3">
                                                            <label>Nama</label>
                                                            <input type="text" name="nama" class="form-control"
                                                                value="{{ $row->nama }}" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Kamar Tujuan</label>
                                                            <select name="kamar_tujuan_id" class="form-select"
                                                                required>
                                                                @foreach ($kamars as $kamar)
                                                                    <option value="{{ $kamar->id }}"
                                                                        {{ $row->kamar_tujuan_id == $kamar->id ? 'selected' : '' }}>
                                                                        BLOK {{ $kamar->kode_blok ?? '-' }} -
                                                                        {{ $kamar->lokasi_sel ?? '-' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Keterangan</label>
                                                            <textarea name="alasan" class="form-control" rows="4">{{ $row->alasan }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if (!request('search'))
        <div class="card">

            <div class="card-header">
                GRAND TOTAL MUTASI WBP
            </div>

            <div class="card-body">
                Total Mutasi:
                <strong>{{ $totalMutasi }}</strong>
            </div>

        </div>
    @endif

    @if (!request('search'))
        {{-- SIGNATURE --}}
        <div class="row mt-5">

            <div class="col-6"></div>

            <div class="col-6 text-center">

                <div class="fw-semibold">
                    Bandung, {{ $date }}
                </div>
                <div style="height:7px"></div>
                <div class="fw-semibold">
                    Mengetahui
                </div>

                <div class="fw-bold">
                    Ka. KPLP
                </div>

                <div style="height:90px;"></div>

                <div class="fw-bold text-decoration-underline">
                    ANDHIKA SAPUTRA
                </div>

                <div class="small">
                </div>

            </div>
    @endif

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const filter = document.getElementById('filter');

            function toggleCustom() {

                const show = filter.value === 'custom';

                document
                    .querySelectorAll('.custom-range')
                    .forEach(el => {
                        el.style.display = show ? '' : 'none';
                    });
            }

            filter.addEventListener('change', toggleCustom);

            toggleCustom();

            // =========================
            // SEARCH dengan SELECT2
            // =========================

            $('#searchWbp').select2({
                placeholder: 'Cari WBP...',
                allowClear: true,
                width: '100%'
            });

        });
    </script>

    <script>
        const toggleHideUrl = @json(route('mutasi.toggleHide', ['id' => '__ID__']));
    </script>

    <script>
        function toggleHideMutasi(button) {

            const row = button.closest('.mutasi-row');

            if (!row) {
                console.error('Row mutasi tidak ditemukan.');
                return;
            }

            const id = row.dataset.id;

            if (!id) {
                console.error('ID mutasi tidak ditemukan.');
                return;
            }

            const url = toggleHideUrl.replace('__ID__', id);

            console.log('Toggle Hide URL:', url);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),

                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {

                    const text = await response.text();

                    console.log('STATUS:', response.status);
                    console.log('RESPONSE:', text);

                    if (!response.ok) {
                        throw new Error(
                            `HTTP ${response.status}: ${text}`
                        );
                    }

                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error(
                            'Response bukan JSON: ' + text
                        );
                    }
                })
                .then(data => {

                    console.log('DATA JSON:', data);

                    if (!data.success) {
                        throw new Error(
                            data.message || 'Gagal mengubah status.'
                        );
                    }

                    row.classList.toggle(
                        'mutasi-row-hidden',
                        data.is_hidden
                    );

                    const icon = button.querySelector('i');
                    const text = button.querySelector('.hide-text');

                    if (data.is_hidden) {

                        button.classList.remove('btn-secondary');
                        button.classList.add('btn-success');

                        icon.className = 'fas fa-eye';
                        text.textContent = 'Show';

                        button.title = 'Tampilkan kembali saat print';

                    } else {

                        button.classList.remove('btn-success');
                        button.classList.add('btn-secondary');

                        icon.className = 'fas fa-eye-slash';
                        text.textContent = 'Hide';

                        button.title = 'Sembunyikan dari print';
                    }

                })
                .catch(error => {

                    console.error('ERROR TOGGLE HIDE:', error);

                    alert(
                        'Gagal mengubah status Hide/Show.\n\n' +
                        error.message
                    );
                });
        }
    </script>
</body>

</html>
