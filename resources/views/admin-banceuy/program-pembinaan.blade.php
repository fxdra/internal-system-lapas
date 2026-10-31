@extends('admin-banceuy.partisi.main')

@section('content')

    <style>
        .program-pembinaan-page .pp-stat-icon {
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 22px;
        }


        /* WBP TERPILIH - GRID HORIZONTAL */
        .program-pembinaan-page #selectedWbpsList {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            justify-content: start;
            gap: 14px 24px;
            width: 100%;
            text-align: left;
        }

        /* ITEM WBP */
        .program-pembinaan-page #selectedWbpsList [data-selected-wbp] {
            display: flex !important;
            flex-direction: row;
            justify-content: flex-start !important;
            align-items: flex-start;
            gap: 12px;
            min-width: 0;
            width: 100%;
            margin: 0 !important;
            padding: 0 0 10px !important;
            border: 0 !important;
            border-bottom: none !important;
            border-radius: 0 !important;
            background: transparent !important;
        }

        /* INFORMASI WBP */
        .program-pembinaan-page #selectedWbpsList [data-selected-wbp]>div {
            flex: 1 1 auto;
            min-width: 0;
            max-width: 280px;
            overflow-wrap: anywhere;
        }

        /* NAMA WBP */
        .program-pembinaan-page #selectedWbpsList .fw-semibold {
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        /* NOMOR REGISTRASI */
        .program-pembinaan-page #selectedWbpsList .text-muted {
            display: block;
            margin-top: 3px;
            font-size: 12px;
        }

        /* TOMBOL HAPUS */
        .program-pembinaan-page #selectedWbpsList .btn-hapus-wbp {
            display: inline-flex;
            flex: 0 0 64px;
            width: 64px;
            min-width: 64px;
            justify-content: center;
            align-items: center;
            margin: 0px 0 0 !important;
            padding: 5px 0;
            text-align: center;
            white-space: nowrap;
        }

        /* TABLET DAN MOBILE */
        @media (max-width: 767.98px) {
            .program-pembinaan-page #selectedWbpsList {
                grid-template-columns: minmax(0, 1fr);
                gap: 12px;
            }

            .program-pembinaan-page #selectedWbpsList [data-selected-wbp]>div {
                max-width: 100%;
            }
        }


        /* FORM DAN INPUT */
        .program-pembinaan-page .form-control,
        .program-pembinaan-page .form-select,
        .program-pembinaan-page .input-group-text {
            border-radius: 10px;
        }

        .program-pembinaan-page .input-group .form-control {
            border-radius: 0 10px 10px 0 !important;
        }

        .program-pembinaan-page .input-group .input-group-text {
            border-radius: 10px 0 0 10px !important;
        }

        .program-pembinaan-page .input-group .form-control:first-child {
            border-radius: 10px 0 0 10px;
        }

        .program-pembinaan-page .input-group .btn:last-child {
            border-radius: 0 10px 10px 0;
        }

        .program-pembinaan-page .btn-cari-wbp {
            border-radius: 10px !important;
        }

        .program-pembinaan-page .rounded-pill {
            font-weight: 500;
        }

        /* TABEL */
        .program-pembinaan-page .pp-table th {
            white-space: nowrap;
            font-size: 13px;
            color: #475569;
        }

        .program-pembinaan-page .pp-table td {
            font-size: 13px;
        }

        .program-pembinaan-page .form-check-input {
            width: 17px;
            height: 17px;
            cursor: pointer;
        }

        /* DETAIL RIWAYAT PEMBINAAN */
        .program-pembinaan-page .pp-detail-row>td {
            background: transparent;
        }

        .program-pembinaan-page .pp-detail-content {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .program-pembinaan-page .pp-detail-content .form-control,
        .program-pembinaan-page .pp-detail-content .form-select {
            border-radius: 10px;
        }

        .program-pembinaan-page .pp-detail-content .form-label {
            color: #334155;
        }

        /* PAGINATION */
        .program-pembinaan-page .pp-pagination .small.text-muted {
            display: none !important;
        }

        .program-pembinaan-page .pp-pagination-wrap {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-top: 18px;
            padding: 14px 16px;
            background: #f8fafc;
            border: 1px solid #e8edf4;
            border-radius: 14px;
        }

        .program-pembinaan-page .pp-pagination-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
            color: #64748b;
            font-size: 13px;
        }

        .program-pembinaan-page .pp-pagination-info strong {
            color: #334155;
            font-weight: 600;
        }

        .program-pembinaan-page .pp-pagination-label {
            color: #334155;
            font-size: 13px;
            font-weight: 700;
        }

        .program-pembinaan-page .pp-pagination nav {
            margin: 0;
        }

        .program-pembinaan-page .pp-pagination .pagination {
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 5px;
            margin: 0;
        }

        .program-pembinaan-page .pp-pagination .page-item {
            margin: 0;
        }

        .program-pembinaan-page .pp-pagination .page-link {
            display: inline-flex;
            min-width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            padding: 0 11px;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 9px !important;
            box-shadow: 0 1px 2px rgb(15 23 42 / 4%);
            transition: color .2s ease, background-color .2s ease,
                border-color .2s ease, box-shadow .2s ease;
        }

        .program-pembinaan-page .pp-pagination .page-link:hover {
            color: var(--bs-primary);
            background: #f8faff;
            border-color: var(--bs-primary);
        }

        .program-pembinaan-page .pp-pagination .page-item.active .page-link {
            color: #fff;
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            box-shadow: 0 3px 8px rgb(13 110 253 / 20%);
        }

        .program-pembinaan-page .pp-pagination .page-item.disabled .page-link {
            color: #a0aab8;
            background: #f8fafc;
            border-color: #e8edf4;
            box-shadow: none;
        }

        /* FOOTER RIWAYAT TANPA CARD */
        .program-pembinaan-page .pp-pagination-clean {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 14px;
            padding: 0;
            background: transparent;
            border: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .program-pembinaan-page .pp-pagination-clean .pp-pagination-info {
            gap: 3px;
        }

        /* ================= DARK THEME PROGRAM PEMBINAAN ================= */

        /* Badge jumlah WBP dipilih */
        html.app-skin-dark .badge.bg-light.text-dark.border {
            background-color: #1e293b !important;
            color: #cbd5e1 !important;
            border-color: #334155 !important;
        }

        /* Icon kalender pada input date saat dark theme */
        .app-skin-dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        /* Header tabel program pembinaan */
        html.app-skin-dark .table thead th {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            border-color: #283449 !important;
        }

        /* Checkbox pilih semua */
        html.app-skin-dark .table thead .form-check-input {
            background-color: #1e293b;
            border-color: #64748b;
        }

        html.app-skin-dark .table thead .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        /* Dropdown Program Pembinaan */
        html.app-skin-dark .form-select,
        html.app-skin-dark select.form-select {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        /* Opsi dropdown */
        html.app-skin-dark .form-select option,
        html.app-skin-dark select option {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
        }

        /* Opsi yang dipilih */
        html.app-skin-dark .form-select option:checked,
        html.app-skin-dark select option:checked {
            background-color: #1d4ed8 !important;
            color: #ffffff !important;
        }

        /* Container informasi dan pagination */
        html.app-skin-dark .pp-pagination-wrap {
            background: #0f172a !important;
            border-color: #283449 !important;
            color: #e2e8f0 !important;
        }

        /* Label Data WBP */
        html.app-skin-dark .pp-pagination-info,
        html.app-skin-dark .pp-pagination-label {
            color: #cbd5e1 !important;
        }

        /* Angka jumlah data */
        html.app-skin-dark .pp-pagination-info strong {
            color: #f1f5f9 !important;
        }

        /* Tombol pagination */
        html.app-skin-dark .pp-pagination .page-link {
            background-color: #111c30 !important;
            color: #cbd5e1 !important;
            border-color: #283449 !important;
            box-shadow: none !important;
        }

        /* Halaman aktif */
        html.app-skin-dark .pp-pagination .page-item.active .page-link {
            background-color: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
        }

        /* Hover tombol */
        html.app-skin-dark .pp-pagination .page-link:hover {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border-color: #334155 !important;
        }

        /* Tombol disabled */
        html.app-skin-dark .pp-pagination .page-item.disabled .page-link {
            background-color: #0f172a !important;
            color: #64748b !important;
            border-color: #283449 !important;
        }

        /* DARK THEME - DETAIL KEIKUTSERTAAN */
        .app-skin-dark .pp-detail-content {
            background-color: #111827;
            color: #e5e7eb;
        }

        /* Judul dan label */
        .app-skin-dark .pp-detail-content h6,
        .app-skin-dark .pp-detail-content .form-label {
            color: #f3f4f6 !important;
        }

        /* Deskripsi WBP dan program */
        .app-skin-dark .pp-detail-content .text-muted {
            color: #9ca3af !important;
        }

        /* Input, textarea, dan dropdown */
        .app-skin-dark .pp-detail-content .form-control,
        .app-skin-dark .pp-detail-content .form-select {
            background-color: #0f172a !important;
            color: #e5e7eb !important;
            border-color: #374151 !important;
        }

        /* Placeholder */
        .app-skin-dark .pp-detail-content .form-control::placeholder {
            color: #9ca3af !important;
        }

        /* Fokus input */
        .app-skin-dark .pp-detail-content .form-control:focus,
        .app-skin-dark .pp-detail-content .form-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.2) !important;
        }

        /* Opsi dropdown */
        .app-skin-dark .pp-detail-content .form-select option {
            background-color: #0f172a;
            color: #e5e7eb;
        }

        /* Ikon kalender */
        .app-skin-dark .pp-detail-content input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        /* Tombol simpan */
        .app-skin-dark .pp-detail-content .btn-primary {
            color: #ffffff !important;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .program-pembinaan-page .card-body {
                padding: 1rem !important;
            }

            .program-pembinaan-page .pp-stat-icon {
                width: 44px;
                height: 44px;
                flex-basis: 44px;
            }

            .program-pembinaan-page .btn {
                max-width: 100%;
            }

            .program-pembinaan-page .table-responsive {
                font-size: 12px;
            }
        }

        @media (max-width: 575.98px) {
            .program-pembinaan-page .pp-pagination-wrap {
                justify-content: center;
                text-align: center;
            }

            .program-pembinaan-page .pp-pagination-info {
                align-items: center;
                width: 100%;
            }

            .program-pembinaan-page .pp-pagination {
                width: 100%;
            }

            .program-pembinaan-page .pp-pagination .pagination {
                justify-content: center;
            }

            @media (max-width: 575.98px) {
                .program-pembinaan-page .pp-pagination-clean {
                    justify-content: center;
                    text-align: center;
                }
            }
        }
    </style>

    <div class="container-fluid my-3 program-pembinaan-page">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">Program Pembinaan WBP</h4>
                <small class="text-muted">
                    Pengelolaan keikutsertaan pembinaan kepribadian dan kemandirian.
                </small>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="fw-semibold mb-1">Periksa kembali data yang dimasukkan.</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="pp-stat-icon bg-primary-subtle text-primary">
                            <i class="feather-grid"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Program Aktif</div>
                            <h3 class="fw-bold mb-0">
                                {{ number_format($statistik['total_program']) }}
                            </h3>
                            <small class="text-muted">Program pembinaan tersedia</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="pp-stat-icon bg-success-subtle text-success">
                            <i class="feather-users"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Keikutsertaan Aktif</div>
                            <h3 class="fw-bold mb-0">
                                {{ number_format($statistik['total_wbp_aktif']) }}
                            </h3>
                            <small class="text-muted">Data keikutsertaan aktif</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex align-items-center gap-3">
                        <div class="pp-stat-icon bg-info-subtle text-info">
                            <i class="feather-check-circle"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Keikutsertaan Selesai</div>
                            <h3 class="fw-bold mb-0">
                                {{ number_format($statistik['total_selesai']) }}
                            </h3>
                            <small class="text-muted">Riwayat pembinaan selesai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER PROGRAM PEMBINAAN --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Filter Program</h5>
                    <small class="text-muted">
                        Pilih program pembinaan untuk menyaring daftar WBP serta riwayat.
                    </small>
                </div>

                <form method="GET" action="{{ route('program-pembinaan.index') }}" id="filterProgramForm">
                    <div class="row g-3 align-items-end">

                        <div class="col-12 col-md-8">
                            <label for="filterProgram" class="form-label fw-semibold">
                                Nama Program
                            </label>
                            <select name="program_id" id="filterProgram" class="form-select">
                                <option value="">Semua Program</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}" data-kategori="{{ $program->kategori }}"
                                        {{ (string) request('program_id') === (string) $program->id ? 'selected' : '' }}>
                                        {{ $program->nama_program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="feather-filter me-1"></i> Terapkan
                            </button>
                            <a href="{{ route('program-pembinaan.index') }}" class="btn btn-light rounded-pill px-4">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- INPUT MASSAL WBP --}}
        @if (empty($selectedProgram))
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">Tambah Keikutsertaan WBP</h5>
                            <small class="text-muted">
                                Pilih program pembinaan, cari WBP, lalu centang WBP yang akan ditambahkan.
                            </small>
                        </div>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                            Input Massal
                        </span>
                    </div>

                    {{-- FORM PENCARIAN WBP --}}
                    <form method="GET" action="{{ route('program-pembinaan.index') }}" class="mb-3">
                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                        @if ($selectedProgram)
                            <input type="hidden" name="program_id" value="{{ $selectedProgram->id }}">
                        @endif

                        <div class="row mb-3">
                            {{-- FILTER BLOK --}}
                            <div class="col-md-6 mb-2">
                                <label for="filterBlok" class="form-label">Blok</label>
                                <select name="blok" id="filterBlok" class="form-control">
                                    <option value="">Pilih Blok</option>
                                    @foreach ($blokList as $itemBlok)
                                        <option value="{{ $itemBlok }}"
                                            {{ ($blok ?? '') == $itemBlok ? 'selected' : '' }}>
                                            Blok {{ $itemBlok }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- FILTER KAMAR --}}
                            <div class="col-md-6 mb-2">
                                <label for="filterKamar" class="form-label">Kamar</label>
                                <select name="kamar_id" id="filterKamar" class="form-control"
                                    {{ empty($blok) ? 'disabled' : '' }}>

                                    <option value="">
                                        {{ empty($blok) ? 'Pilih Blok Terlebih Dahulu' : 'Semua Kamar' }}
                                    </option>

                                    @foreach ($kamarList as $itemKamar)
                                        <option value="{{ $itemKamar->id }}" data-blok="{{ $itemKamar->kode_blok }}"
                                            {{ (string) ($kamarId ?? '') === (string) $itemKamar->id ? 'selected' : '' }}>
                                            {{ $itemKamar->lokasi_sel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 align-items-end">
                            <div class="col-12">
                                <label for="searchWbp" class="form-label fw-semibold">
                                    Cari WBP
                                </label>
                            </div>

                            <div class="col-12 col-md-9">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="feather-search"></i>
                                    </span>
                                    <input type="text" id="searchWbp" name="search" class="form-control"
                                        placeholder="Nama atau nomor registrasi..." value="{{ $search }}">
                                </div>
                            </div>

                            <div class="col-12 col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 btn-cari-wbp">
                                    Cari
                                </button>

                                @if ($search !== '')
                                    <a href="{{ route(
                                        'program-pembinaan.index',
                                        array_filter([
                                            'kategori' => $kategori,
                                            'program_id' => $selectedProgram?->id,
                                            'blok' => $blok,
                                            'kamar_id' => $kamarId,
                                            'selected_ids' => $selectedIds->all(),
                                        ]),
                                    ) }}"
                                        class="btn btn-light">
                                        Hapus
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div id="selectedIdsFilter">
                            @foreach ($selectedIds as $id)
                                <input type="hidden" name="selected_ids[]" value="{{ $id }}">
                            @endforeach
                        </div>

                    </form>

                    <form method="POST" action="{{ route('program-pembinaan.store') }}" id="formTambahPembinaan">
                        @csrf

                        {{-- DATA WBP TERPILIH UNTUK JAVASCRIPT --}}
                        <div class="d-none">
                            @foreach ($selectedWbps as $item)
                                <span class="selected-wbp-data" data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama }}" data-nomor="{{ $item->no_reg_instansi }}">
                                </span>
                            @endforeach
                        </div>

                        {{-- HIDDEN INPUT UNTUK MENYIMPAN ID WBP --}}
                        <div id="selectedWbpsContainer">
                            @foreach ($selectedWbps as $selectedWbp)
                                <input type="hidden" name="wbp_ids[]" value="{{ $selectedWbp->id }}">
                            @endforeach
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-lg-6">
                                <label for="programInput" class="form-label fw-semibold">
                                    Program Pembinaan <span class="text-danger">*</span>
                                </label>
                                <select name="program_pembinaan_id" id="programInput" class="form-select" required>
                                    <option value="">-- Pilih Program --</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}" data-kategori="{{ $program->kategori }}"
                                            {{ (string) old('program_pembinaan_id', $selectedProgram?->id) === (string) $program->id ? 'selected' : '' }}>
                                            {{ $program->kategori }} — {{ $program->nama_program }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="tanggalMulai" class="form-label fw-semibold">
                                    Tanggal Mulai <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="tanggal_mulai" id="tanggalMulai" class="form-control"
                                    value="{{ old('tanggal_mulai', now()->toDateString()) }}" required>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="indikator" class="form-label fw-semibold">
                                    Indikator Keaktifan <span class="text-danger">*</span>
                                </label>
                                <select name="indikator_keaktifan" id="indikator" class="form-select" required>
                                    <option value="Sangat Aktif"
                                        {{ old('indikator_keaktifan') === 'Sangat Aktif' ? 'selected' : '' }}>
                                        Sangat Aktif
                                    </option>
                                    <option value="Aktif"
                                        {{ old('indikator_keaktifan', 'Aktif') === 'Aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="Kurang Aktif"
                                        {{ old('indikator_keaktifan') === 'Kurang Aktif' ? 'selected' : '' }}>
                                        Kurang Aktif
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="keteranganMassal" class="form-label fw-semibold">
                                    Keterangan (Opsional)
                                </label>
                                <textarea name="keterangan" id="keteranganMassal" class="form-control" rows="2" maxlength="2000"
                                    placeholder="Keterangan kegiatan pembinaan...">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <div>
                                <h6 class="fw-bold mb-1">Daftar WBP</h6>
                                <small class="text-muted">
                                    Maksimal 25 WBP ditampilkan per halaman.
                                </small>
                            </div>

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge bg-light text-dark border">
                                    Dipilih: <span id="jumlahDipilih">0</span>
                                </span>

                                <button type="button" id="btnPilihHalaman"
                                    class="btn btn-sm btn-outline-primary rounded-pill">
                                    Pilih Halaman
                                </button>

                                <button type="button" id="btnBatalPilih"
                                    class="btn btn-sm btn-outline-secondary rounded-pill">
                                    Batal Pilih
                                </button>
                            </div>
                        </div>

                        {{-- DAFTAR WBP TERPILIH --}}
                        <div id="selectedWbpsPanel" class="border rounded-3 p-3 mb-3"
                            style="{{ $selectedWbps->isEmpty() ? 'display:none;' : '' }}">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">WBP Terpilih</h6>

                                <span class="badge bg-primary-subtle text-primary">
                                    <span id="jumlahWbpTerpilih">{{ $selectedWbps->count() }}</span> WBP
                                </span>
                            </div>

                            <div id="selectedWbpsList" class="d-flex flex-column gap-2">
                                @foreach ($selectedWbps as $selectedWbp)
                                    <div class="d-flex justify-content-between align-items-center gap-2 border-bottom pb-2"
                                        data-selected-wbp="{{ $selectedWbp->id }}">
                                        <div>
                                            <div class="fw-semibold">{{ $selectedWbp->nama }}</div>
                                            <small class="text-muted">
                                                {{ $selectedWbp->no_reg_instansi ?: '-' }}
                                            </small>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-wbp"
                                            data-id="{{ $selectedWbp->id }}">
                                            Hapus
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        {{-- TABEL WBP --}}
                        <div class="table-responsive">
                            <table class="table table-hover align-middle pp-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 48px;">
                                            <input type="checkbox" id="pilihSemua" class="form-check-input"
                                                aria-label="Pilih semua WBP di halaman ini">
                                        </th>
                                        <th>No.</th>
                                        <th>Nomor Registrasi</th>
                                        <th>Nama WBP</th>
                                        <th>Program Pembinaan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($wbps as $wbp)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input wbp-checkbox"
                                                    value="{{ $wbp->id }}" data-nama="{{ $wbp->nama }}"
                                                    data-nomor="{{ $wbp->no_reg_instansi }}">
                                            </td>

                                            <td>{{ $wbps->firstItem() + $loop->index }}</td>

                                            <td>{{ $wbp->no_reg_instansi ?: '-' }}</td>

                                            <td class="fw-semibold">{{ $wbp->nama }}</td>

                                            <td>
                                                @php
                                                    $namaProgramWbp = $wbp->riwayatPembinaan
                                                        ->pluck('program.nama_program')
                                                        ->filter()
                                                        ->unique()
                                                        ->values();
                                                @endphp

                                                @forelse ($namaProgramWbp as $namaProgram)
                                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                                        {{ $namaProgram }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted">
                                                        Belum mengikuti program
                                                    </span>
                                                @endforelse
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Tidak ada WBP yang cocok dengan pencarian,
                                                atau semua WBP sudah aktif di program terpilih.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="pp-pagination-wrap">
                            <div class="pp-pagination-info">
                                <span class="pp-pagination-label">Data WBP</span>
                                <span>
                                    Menampilkan
                                    <strong>{{ $wbps->lastItem() ?? 0 }}</strong>
                                    dari <strong>{{ number_format($wbps->total()) }}</strong> WBP
                                </span>
                            </div>

                            @if ($wbps->hasPages())
                                <nav class="pp-pagination" aria-label="Pagination daftar WBP">
                                    {{ $wbps->onEachSide(1)->links('pagination::bootstrap-5') }}
                                </nav>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" id="btnSimpanPembinaan" class="btn btn-primary rounded-pill px-4">
                                <i class="feather-save me-1"></i>
                                Simpan Keikutsertaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if (!empty($selectedProgram))
            {{-- RIWAYAT KEIKUTSERTAAN --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Riwayat Keikutsertaan</h5>
                            <small class="text-muted">
                                Data WBP berdasarkan filter program yang dipilih.
                            </small>
                        </div>
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                            {{ $riwayat->total() }} data
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle pp-table">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>WBP</th>
                                    <th>Program</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Indikator</th>
                                    <th>Status</th>
                                    <th style="min-width: 140px;">Detail</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($riwayat as $item)
                                    <tr>
                                        <td>{{ $riwayat->firstItem() + $loop->index }}</td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $item->wbp?->nama ?? 'Data WBP tidak ditemukan' }}
                                            </div>

                                            <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                                <small class="text-muted">
                                                    {{ $item->wbp?->no_reg_instansi ?? '-' }}
                                                </small>

                                                @if ($item->wbp?->kamar?->kode_blok && $item->wbp?->kamar?->lokasi_sel)
                                                    <span class="badge bg-light text-dark border">
                                                        Blok
                                                        {{ $item->wbp->kamar->kode_blok }}-{{ preg_replace('/\D+/', '', $item->wbp->kamar->lokasi_sel) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted border">
                                                        Kamar belum ditentukan
                                                    </span>
                                                @endif

                                            </div>
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $item->program?->nama_program ?? '-' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $item->program?->kategori ?? '-' }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}
                                            @if ($item->tanggal_selesai)
                                                <div class="small text-muted">
                                                    Selesai: {{ $item->tanggal_selesai->format('d-m-Y') }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($item->indikator_keaktifan === 'Sangat Aktif')
                                                <span class="badge bg-success-subtle text-success">
                                                    Sangat Aktif
                                                </span>
                                            @elseif ($item->indikator_keaktifan === 'Kurang Aktif')
                                                <span class="badge bg-danger-subtle text-danger">
                                                    Kurang Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle" style="color: #e7e413 !important;">
                                                    Aktif
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($item->status_keikutsertaan === 'Aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Selesai</span>
                                            @endif
                                        </td>

                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#detailPembinaan{{ $item->id }}"
                                                aria-expanded="false" aria-controls="detailPembinaan{{ $item->id }}">
                                                <i class="feather-edit-2 me-1"></i>
                                                Perbarui
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- DETAIL DAN FORM UPDATE --}}
                                    <tr class="pp-detail-row">
                                        <td colspan="7" class="p-0 border-0">
                                            <div class="collapse" id="detailPembinaan{{ $item->id }}">

                                                <div class="pp-detail-content p-3 p-md-4">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">
                                                                Perbarui Keikutsertaan
                                                            </h6>
                                                            <small class="text-muted">
                                                                {{ $item->wbp?->nama ?? 'Data WBP tidak ditemukan' }}
                                                                — {{ $item->program?->nama_program ?? '-' }}
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <form method="POST"
                                                        action="{{ route('program-pembinaan.keikutsertaan.update', $item->id) }}"
                                                        class="form-update-keikutsertaan">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="row g-3">

                                                            {{-- TANGGAL MULAI --}}
                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                <label class="form-label small fw-semibold">
                                                                    Tanggal Mulai
                                                                </label>

                                                                <input type="date" name="tanggal_mulai"
                                                                    class="form-control"
                                                                    value="{{ $item->tanggal_mulai?->format('Y-m-d') }}"
                                                                    max="{{ $item->tanggal_selesai?->format('Y-m-d') ?? now()->toDateString() }}"
                                                                    required>
                                                            </div>

                                                            {{-- INDIKATOR KEAKTIFAN --}}
                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                <label class="form-label small fw-semibold">
                                                                    Indikator Keaktifan
                                                                </label>

                                                                <select name="indikator_keaktifan" class="form-select"
                                                                    required>
                                                                    <option value="Sangat Aktif"
                                                                        {{ $item->indikator_keaktifan === 'Sangat Aktif' ? 'selected' : '' }}>
                                                                        Sangat Aktif
                                                                    </option>
                                                                    <option value="Aktif"
                                                                        {{ $item->indikator_keaktifan === 'Aktif' ? 'selected' : '' }}>
                                                                        Aktif
                                                                    </option>
                                                                    <option value="Kurang Aktif"
                                                                        {{ $item->indikator_keaktifan === 'Kurang Aktif' ? 'selected' : '' }}>
                                                                        Kurang Aktif
                                                                    </option>
                                                                </select>
                                                            </div>

                                                            {{-- STATUS KEIKUTSERTAAN --}}
                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                <label class="form-label small fw-semibold">
                                                                    Status Keikutsertaan
                                                                </label>

                                                                <select name="status_keikutsertaan"
                                                                    class="form-select status-keikutsertaan" required>
                                                                    <option value="Aktif"
                                                                        {{ $item->status_keikutsertaan === 'Aktif' ? 'selected' : '' }}>
                                                                        Aktif
                                                                    </option>
                                                                    <option value="Selesai"
                                                                        {{ $item->status_keikutsertaan === 'Selesai' ? 'selected' : '' }}>
                                                                        Selesai
                                                                    </option>
                                                                </select>
                                                            </div>

                                                            {{-- TANGGAL SELESAI --}}
                                                            <div class="col-12 col-md-6 col-lg-4 tanggal-selesai-wrapper"
                                                                style="{{ $item->status_keikutsertaan === 'Selesai' ? '' : 'display:none;' }}">

                                                                <label class="form-label small fw-semibold">
                                                                    Tanggal Selesai
                                                                </label>

                                                                <input type="date" name="tanggal_selesai"
                                                                    class="form-control"
                                                                    min="{{ $item->tanggal_mulai?->format('Y-m-d') }}"
                                                                    value="{{ $item->tanggal_selesai?->format('Y-m-d') }}">
                                                            </div>

                                                            {{-- KETERANGAN --}}
                                                            <div class="col-12">
                                                                <label class="form-label small fw-semibold">
                                                                    Keterangan
                                                                </label>

                                                                <textarea name="keterangan" class="form-control" rows="2" maxlength="2000">{{ $item->keterangan }}</textarea>
                                                            </div>

                                                            {{-- TOMBOL SIMPAN --}}
                                                            <div class="col-12 d-flex justify-content-end">
                                                                <button type="submit"
                                                                    class="btn btn-primary rounded-pill px-4">
                                                                    <i class="feather-save me-1"></i>
                                                                    Simpan Perubahan
                                                                </button>
                                                            </div>

                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="feather-inbox fs-2 text-muted"></i>
                                            <div class="fw-semibold mt-2">
                                                Belum ada riwayat keikutsertaan
                                            </div>
                                            <small class="text-muted">
                                                Pilih program dan tambahkan WBP untuk mulai mencatat pembinaan.
                                            </small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="pp-pagination-wrap pp-pagination-clean">
                        <div class="pp-pagination-info">
                            <span class="pp-pagination-label">Riwayat Keikutsertaan</span>
                            <span>
                                Menampilkan
                                <strong>{{ $riwayat->lastItem() ?? 0 }}</strong>
                                dari <strong>{{ number_format($riwayat->total()) }}</strong> data
                            </span>
                        </div>

                        @if ($riwayat->hasPages())
                            <nav class="pp-pagination" aria-label="Pagination riwayat keikutsertaan">
                                {{ $riwayat->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </nav>
                        @endif
                    </div>

                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const checkboxes = Array.from(
                document.querySelectorAll('.wbp-checkbox')
            );

            const pilihSemua = document.getElementById('pilihSemua');
            const btnPilihHalaman = document.getElementById('btnPilihHalaman');
            const btnBatalPilih = document.getElementById('btnBatalPilih');
            const jumlahDipilih = document.getElementById('jumlahDipilih');
            const formTambah = document.getElementById('formTambahPembinaan');
            const programInput = document.getElementById('programInput');

            const filterForm = document.querySelector(
                'form[method="GET"][action*="program-pembinaan"]'
            );
            const selectedIdsFilter = document.getElementById('selectedIdsFilter');
            const selectedContainer = document.getElementById('selectedWbpsContainer');
            const selectedPanel = document.getElementById('selectedWbpsPanel');
            const selectedList = document.getElementById('selectedWbpsList');
            const jumlahWbpTerpilih = document.getElementById('jumlahWbpTerpilih');

            const pilihan = new Map();

            function tambahWbp(id, nama, nomor) {
                pilihan.set(String(id), {
                    id: String(id),
                    nama: nama || 'Nama tidak tersedia',
                    nomor: nomor || '-'
                });
            }

            function sinkronkanPilihan() {
                checkboxes.forEach(cb => {
                    const row = cb.closest('tr');
                    const nama = row?.querySelector('td:nth-child(4)')
                        ?.textContent.trim() || '';
                    const nomor = row?.querySelector('td:nth-child(3)')
                        ?.textContent.trim() || '-';

                    if (cb.checked) {
                        tambahWbp(cb.value, nama, nomor);
                    } else {
                        pilihan.delete(String(cb.value));
                    }
                });
            }

            function renderPilihan() {
                if (!selectedContainer || !selectedList) return;

                selectedContainer.innerHTML = '';
                selectedList.innerHTML = '';

                pilihan.forEach(wbp => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'wbp_ids[]';
                    input.value = wbp.id;
                    selectedContainer.appendChild(input);

                    const item = document.createElement('div');
                    item.className = 'selected-wbp-item';
                    item.dataset.selectedWbp = wbp.id;

                    const info = document.createElement('div');

                    const nama = document.createElement('div');
                    nama.className = 'fw-semibold';
                    nama.textContent = wbp.nama;

                    const nomor = document.createElement('small');
                    nomor.className = 'text-muted';
                    nomor.textContent = wbp.nomor;

                    info.append(nama, nomor);

                    const hapus = document.createElement('button');
                    hapus.type = 'button';
                    hapus.className = 'btn btn-sm btn-outline-danger btn-hapus-wbp';
                    hapus.dataset.id = wbp.id;
                    hapus.textContent = 'Hapus';

                    item.append(info, hapus);
                    selectedList.appendChild(item);
                });

                if (selectedPanel) {
                    selectedPanel.style.display = pilihan.size ? '' : 'none';
                }

                if (jumlahDipilih) jumlahDipilih.textContent = pilihan.size;
                if (jumlahWbpTerpilih) jumlahWbpTerpilih.textContent = pilihan.size;

                if (pilihSemua) {
                    const terpilih = checkboxes.filter(cb => cb.checked).length;
                    pilihSemua.checked =
                        checkboxes.length > 0 && terpilih === checkboxes.length;
                    pilihSemua.indeterminate =
                        terpilih > 0 && terpilih < checkboxes.length;
                }

                // Bawa seluruh pilihan saat form pencarian/filter dikirim.
                if (selectedIdsFilter) {
                    selectedIdsFilter.innerHTML = '';

                    pilihan.forEach(wbp => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected_ids[]';
                        input.value = wbp.id;
                        selectedIdsFilter.appendChild(input);
                    });
                }
            }

            // Pulihkan pilihan dari URL, termasuk halaman pagination sebelumnya.
            document.querySelectorAll(
                '#selectedIdsFilter input[name="selected_ids[]"]'
            ).forEach(input => {
                const id = input.value;
                const item = document.querySelector(
                    `.selected-wbp-data[data-id="${CSS.escape(id)}"]`
                );

                if (item) {
                    tambahWbp(id, item.dataset.nama, item.dataset.nomor);
                }
            });

            // Pulihkan data awal yang dirender server melalui daftar pilihan.
            document.querySelectorAll('#selectedWbpsList [data-selected-wbp]')
                .forEach(item => {
                    const id = item.dataset.selectedWbp;
                    const nama = item.querySelector('.fw-semibold')?.textContent.trim();
                    const nomor = item.querySelector('.text-muted')?.textContent.trim();

                    tambahWbp(id, nama, nomor);
                });

            // Sinkronkan checkbox pada halaman aktif.
            checkboxes.forEach(cb => {
                cb.checked = pilihan.has(String(cb.value));

                cb.addEventListener('change', () => {
                    if (cb.checked) {
                        const row = cb.closest('tr');
                        tambahWbp(
                            cb.value,
                            row?.querySelector('td:nth-child(4)')?.textContent.trim(),
                            row?.querySelector('td:nth-child(3)')?.textContent.trim()
                        );
                    } else {
                        pilihan.delete(String(cb.value));
                    }

                    renderPilihan();
                });
            });

            pilihSemua?.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    if (this.checked) {
                        const row = cb.closest('tr');
                        tambahWbp(
                            cb.value,
                            row?.querySelector('td:nth-child(4)')?.textContent.trim(),
                            row?.querySelector('td:nth-child(3)')?.textContent.trim()
                        );
                        cb.checked = true;
                    } else {
                        cb.checked = false;
                        pilihan.delete(String(cb.value));
                    }
                });

                renderPilihan();
            });

            btnPilihHalaman?.addEventListener('click', () => {
                checkboxes.forEach(cb => {
                    const row = cb.closest('tr');
                    tambahWbp(
                        cb.value,
                        row?.querySelector('td:nth-child(4)')?.textContent.trim(),
                        row?.querySelector('td:nth-child(3)')?.textContent.trim()
                    );
                    cb.checked = true;
                });

                renderPilihan();
            });

            btnBatalPilih?.addEventListener('click', () => {
                pilihan.clear();
                checkboxes.forEach(cb => cb.checked = false);
                renderPilihan();
            });

            selectedList?.addEventListener('click', event => {
                const button = event.target.closest('.btn-hapus-wbp');
                if (!button) return;

                const id = String(button.dataset.id);
                pilihan.delete(id);

                checkboxes.forEach(cb => {
                    if (String(cb.value) === id) cb.checked = false;
                });

                renderPilihan();
            });

            formTambah?.addEventListener('submit', event => {
                renderPilihan();

                if (!programInput.value) {
                    event.preventDefault();
                    alert('Pilih program pembinaan terlebih dahulu.');
                    programInput.focus();
                    return;
                }

                if (pilihan.size === 0) {
                    event.preventDefault();
                    alert('Pilih minimal satu WBP terlebih dahulu.');
                }
            });

            renderPilihan();

            // Tampilkan input tanggal selesai hanya jika status Selesai.
            document.querySelectorAll('.form-update-keikutsertaan').forEach(form => {
                const status = form.querySelector('.status-keikutsertaan');
                const wrapper = form.querySelector('.tanggal-selesai-wrapper');
                const tanggal = wrapper?.querySelector('input[name="tanggal_selesai"]');

                function perbaruiTanggalSelesai() {
                    if (!status || !wrapper) return;

                    const selesai = status.value === 'Selesai';
                    wrapper.style.display = selesai ? '' : 'none';

                    if (tanggal) {
                        tanggal.required = selesai;
                    }
                }

                status?.addEventListener('change', perbaruiTanggalSelesai);
                perbaruiTanggalSelesai();
            });

            // Filter kamar berdasarkan blok yang dipilih.
            const filterBlokKamar = document.getElementById('filterBlok');
            const filterKamar = document.getElementById('filterKamar');

            if (filterBlokKamar && filterKamar) {
                const semuaOpsiKamar = Array.from(filterKamar.options)
                    .filter(option => option.value !== '')
                    .map(option => ({
                        value: option.value,
                        blok: String(option.dataset.blok || ''),
                        text: option.textContent.trim()
                    }));

                function perbaruiOpsiKamar(resetPilihan = false) {
                    const blokTerpilih = String(filterBlokKamar.value);
                    const kamarSebelumnya = resetPilihan ? '' : filterKamar.value;

                    filterKamar.innerHTML = '';

                    const opsiAwal = document.createElement('option');
                    opsiAwal.value = '';

                    if (!blokTerpilih) {
                        opsiAwal.textContent = 'Pilih Blok Terlebih Dahulu';
                        filterKamar.disabled = true;
                        filterKamar.appendChild(opsiAwal);
                        return;
                    }

                    opsiAwal.textContent = 'Semua Kamar';
                    filterKamar.appendChild(opsiAwal);
                    filterKamar.disabled = false;

                    semuaOpsiKamar.forEach(kamar => {
                        if (kamar.blok !== blokTerpilih) return;

                        const opsi = document.createElement('option');
                        opsi.value = kamar.value;
                        opsi.textContent = kamar.text;
                        opsi.dataset.blok = kamar.blok;

                        filterKamar.appendChild(opsi);
                    });

                    const pilihanValid = Array.from(filterKamar.options)
                        .some(option => option.value === kamarSebelumnya);

                    filterKamar.value = pilihanValid ? kamarSebelumnya : '';
                }

                filterBlokKamar.addEventListener('change', function() {
                    perbaruiOpsiKamar(true);
                });

                perbaruiOpsiKamar();
            }

        });
    </script>
@endsection
