@extends('admin-banceuy.partisi.main')

@section('content')

    <style>
        /* Seluruh styling dibatasi untuk halaman Halo Minna. */
        .halo-minna-page {
            color: #263247;
        }

        .halo-minna-page .hm-table {
            margin-bottom: 0;
            white-space: nowrap;
        }

        .halo-minna-page .hm-table thead th {
            font-size: .82rem;
            font-weight: 700;
            color: #526078;
            padding: 14px 12px;
            border-bottom-width: 1px;
        }

        .halo-minna-page .hm-table tbody td {
            padding: 13px 12px;
            font-size: .9rem;
        }

        .halo-minna-page .hm-pagination-wrap {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
        }

        .halo-minna-page .hm-pagination {
            max-width: 100%;
            overflow-x: auto;
        }

        .halo-minna-page .hm-pagination .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
            gap: 3px;
        }

        .halo-minna-page .hm-card-heading {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .halo-minna-page .hm-card-icon {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.2rem;
        }

        .halo-minna-page .hm-profile-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #eaf2ff;
            color: #2563eb;
            font-size: 1.1rem;
        }

        .halo-minna-page .hm-info-box {
            height: 100%;
            padding: 14px 16px;
            border: 1px solid #e8edf4;
            border-radius: 14px;
            background: #fafbfd;
            overflow-wrap: anywhere;
        }

        .halo-minna-page .hm-access-box {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            background: #f8fafc;
        }

        .halo-minna-page .hm-access-code {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            overflow-wrap: anywhere;
        }

        .halo-minna-page .form-label {
            font-size: .9rem;
        }

        .halo-minna-page .form-control,
        .halo-minna-page .form-select {
            min-height: 43px;
            border-color: #dfe5ee;
            border-radius: 10px;
        }

        .halo-minna-page textarea.form-control {
            min-height: auto;
        }

        .halo-minna-page .form-control:focus,
        .halo-minna-page .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .10);
        }

        .halo-minna-page .btn {
            transition: background-color .15s ease, border-color .15s ease;
        }

        .halo-minna-page .hm-autocomplete-suggestions {
            position: absolute;
            z-index: 1050;
            top: calc(100% - 26px);
            left: 0;
            right: 0;
            max-height: 320px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .12);
        }

        .halo-minna-page .hm-autocomplete-item {
            display: block;
            width: 100%;
            padding: 12px 15px;
            border: 0;
            border-bottom: 1px solid #eef2f7;
            background: #fff;
            color: #263247;
            text-align: left;
        }

        .halo-minna-page .hm-autocomplete-item:last-child {
            border-bottom: 0;
        }

        .halo-minna-page .hm-autocomplete-item:hover,
        .halo-minna-page .hm-autocomplete-item:focus {
            background: #f1f6ff;
            outline: none;
        }

        .halo-minna-page .hm-autocomplete-item-name {
            display: block;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .halo-minna-page .hm-autocomplete-item-reg {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-size: .82rem;
        }

        .halo-minna-page .hm-autocomplete-message {
            padding: 14px 15px;
            color: #64748b;
            font-size: .9rem;
        }


        /* LIGHT THEME - Petunjuk Pengisian */
        html:not(.app-skin-dark) .halo-minna-page .alert.alert-info {
            background-color: #eaf4ff !important;
            color: #24527a !important;
            border-color: #d5e8fb !important;
        }

        html:not(.app-skin-dark) .halo-minna-page .alert.alert-info .feather-info {
            color: #212ddb !important;
        }

        html:not(.app-skin-dark) .halo-minna-page .alert.alert-info .fw-semibold,
        html:not(.app-skin-dark) .halo-minna-page .alert.alert-info .small {
            color: #24527a !important;
        }


        /* DARK THEME HALO MINNA */
        html.app-skin-dark .table thead th {
            background: #0f172a !important;
            color: #cbd5e1 !important;
            border-color: #283449 !important;
        }

        /* Icon kalender pada input date saat dark theme */
        .app-skin-dark input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        html.app-skin-dark .table thead,
        html.app-skin-dark .table thead tr,
        html.app-skin-dark .table thead th,
        html.app-skin-dark .table-light thead th {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            border-color: #283449 !important;
        }

        /* Badge Register F */
        html.app-skin-dark .halo-minna-page .hm-table .badge {
            background-color: #283449 !important;
        }

        /* Pagination: tombol angka, panah, dan ellipsis */
        html.app-skin-dark .halo-minna-page .hm-pagination .page-link {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            border-color: #283449 !important;
        }

        /* Halaman aktif */
        html.app-skin-dark .halo-minna-page .hm-pagination .page-item.active .page-link {
            background-color: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
        }

        /* Hover tombol pagination */
        html.app-skin-dark .halo-minna-page .hm-pagination .page-link:hover {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }

        /* Tombol pagination nonaktif */
        html.app-skin-dark .halo-minna-page .hm-pagination .page-item.disabled .page-link {
            background-color: #111c30 !important;
            color: #64748b !important;
            border-color: #283449 !important;
        }

        /* Box Informasi WBP */
        html.app-skin-dark .halo-minna-page .hm-info-box {
            background: #0f172a !important;
            border-color: #283449 !important;
            color: #e2e8f0 !important;
        }

        /* Label informasi: Nomor Registrasi, Putusan, dan lainnya */
        html.app-skin-dark .halo-minna-page .hm-info-box .text-muted {
            color: #94a3b8 !important;
        }

        /* Nilai informasi WBP */
        html.app-skin-dark .halo-minna-page .hm-info-box .fw-semibold {
            color: #f1f5f9 !important;
        }

        /* Kotak Kode Akses Halo Minna */
        html.app-skin-dark .halo-minna-page .hm-access-box {
            background: #0f172a !important;
            border-color: #283449 !important;
            color: #e2e8f0 !important;
        }

        /* Label Kode Akses */
        html.app-skin-dark .halo-minna-page .hm-access-box .text-muted {
            color: #94a3b8 !important;
        }

        /* Teks Kode Akses */
        html.app-skin-dark .halo-minna-page .hm-access-code {
            color: #f1f5f9 !important;
        }

        /* Tombol Salin Kode */
        html.app-skin-dark .halo-minna-page .hm-access-box .btn-outline-primary {
            color: #93c5fd !important;
            border-color: #3b82f6 !important;
        }

        html.app-skin-dark .halo-minna-page .hm-access-box .btn-outline-primary:hover {
            background: #1e3a5f !important;
            color: #ffffff !important;
        }

        /* Tombol Generate Ulang */
        html.app-skin-dark .halo-minna-page .hm-access-box .btn-outline-danger {
            color: #fca5a5 !important;
            border-color: #ef4444 !important;
        }

        html.app-skin-dark .halo-minna-page .hm-access-box .btn-outline-danger:hover {
            background: #7f1d1d !important;
            color: #ffffff !important;
        }

        /* DARK THEME - DROPDOWN HALO MINNA */
        html.app-skin-dark .halo-minna-page .form-select {
            background-color: #111827 !important;
            color: #e2e8f0 !important;
            border-color: #374151 !important;
            color-scheme: dark;
        }

        /* OPTION DROPDOWN */
        html.app-skin-dark .halo-minna-page .form-select option {
            background-color: #1f2937 !important;
            color: #e2e8f0 !important;
        }

        /* OPTION YANG DIPILIH / HOVER */
        html.app-skin-dark .halo-minna-page .form-select option:checked {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        /* FOCUS DROPDOWN */
        html.app-skin-dark .halo-minna-page .form-select:focus {
            background-color: #111827 !important;
            color: #e2e8f0 !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 .2rem rgba(59, 130, 246, .2) !important;
        }

        @media (max-width: 575.98px) {
            .halo-minna-page .card-body {
                padding: 1rem !important;
            }

            .halo-minna-page .hm-access-box {
                align-items: stretch;
            }

            .halo-minna-page .hm-access-box .btn {
                width: 100%;
            }

            .halo-minna-page .hm-pagination-wrap {
                align-items: flex-start;
            }
        }
    </style>

    <div class="container-fluid my-3 halo-minna-page">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-1">Halo Minna</h4>
                <small class="text-muted">
                    Pengelolaan tahapan integrasi dan data penjamin Warga Binaan Pemasyarakatan.
                </small>
            </div>

            @if ($wbpTerpilih)
                <a href="{{ route('halo-minna.index') }}" class="btn btn-light rounded-pill px-4">
                    <i class="feather-arrow-left me-1"></i>
                    Kembali ke Daftar
                </a>
            @endif
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{-- DAFTAR WBP --}}
        @if (!$wbpTerpilih)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Daftar WBP</h5>
                        </div>

                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            {{ number_format($wbps->total()) }} WBP
                        </span>
                    </div>

                    <form method="GET" action="{{ route('halo-minna.index') }}" class="mb-4" id="haloMinnaSearchForm">
                        <label for="searchWbp" class="form-label fw-semibold">Cari WBP</label>

                        <div class="hm-autocomplete position-relative"
                            data-autocomplete-url="{{ route('halo-minna.autocomplete') }}"
                            data-detail-url="{{ route('halo-minna.index') }}">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="feather-search"></i>
                                </span>
                                <input type="text" id="searchWbp" name="search" class="form-control"
                                    placeholder="Ketik nama atau nomor registrasi..." value="{{ $search }}"
                                    autocomplete="off" aria-autocomplete="list" aria-controls="haloMinnaSuggestions"
                                    aria-expanded="false">
                            </div>

                            <div id="haloMinnaSuggestions" class="hm-autocomplete-suggestions d-none" role="listbox"
                                aria-label="Saran WBP"></div>

                            <small class="text-muted d-block mt-2">
                                Ketik minimal 2 karakter, lalu pilih WBP dari saran yang muncul.
                            </small>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle hm-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 65px;">No.</th>
                                    <th>Nomor Registrasi</th>
                                    <th>Nama WBP</th>
                                    <th>Putusan</th>
                                    <th>Register F</th>
                                    <th>Jenis Usulan</th>
                                    <th style="min-width: 120px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($wbps as $wbp)
                                    @php
                                        $dataSimproin = $wbp->simproin;
                                        $tahunPutusan = (int) ($wbp->putusan ?? 0);
                                        $bulanPutusan = (int) ($wbp->putusan_bulan ?? 0);

                                        $formatPutusan = [];
                                        if ($tahunPutusan > 0) {
                                            $formatPutusan[] = $tahunPutusan . ' Tahun';
                                        }
                                        if ($bulanPutusan > 0) {
                                            $formatPutusan[] = $bulanPutusan . ' Bulan';
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $wbps->firstItem() + $loop->index }}</td>

                                        <td>
                                            {{ $wbp->no_reg_instansi ?: '-' }}
                                        </td>

                                        <td>
                                            <div class="fw-semibold">{{ $wbp->nama }}</div>
                                            <small class="text-muted">
                                                Status WBP: {{ $wbp->status_wbp ?? '-' }}
                                            </small>
                                        </td>

                                        <td>
                                            {{ $formatPutusan ? implode(' ', $formatPutusan) : ($wbp->putusan ?: '-') }}
                                        </td>

                                        <td>
                                            @if ($dataSimproin)
                                                <span
                                                    class="badge {{ $dataSimproin->register_f === 'Ya' ? 'bg-danger-subtle text-danger' : 'bg-light text-dark border' }}">
                                                    {{ $dataSimproin->register_f }}
                                                </span>
                                            @else
                                                <span class="text-muted">Belum diinput</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $dataSimproin?->jenis_usulan ?? 'Belum Usul' }}
                                        </td>

                                        <td>
                                            <a href="{{ route(
                                                'halo-minna.index',
                                                array_filter(
                                                    [
                                                        'wbp_id' => $wbp->id,
                                                        'search' => $search,
                                                        'page' => request('page'),
                                                    ],
                                                    fn($value) => $value !== null && $value !== '',
                                                ),
                                            ) }}"
                                                class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="feather-edit-2 me-1"></i>
                                                Kelola
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            Tidak ada data WBP yang sesuai dengan pencarian.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="hm-pagination-wrap">
                        <div class="small text-muted">
                            Menampilkan
                            <strong>{{ $wbps->lastItem() ?? 0 }}</strong>
                            dari <strong>{{ number_format($wbps->total()) }}</strong> WBP
                        </div>

                        @if ($wbps->hasPages())
                            <div class="hm-pagination">
                                {{ $wbps->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- FORM DETAIL WBP --}}
        @if ($wbpTerpilih)
            @php
                $data = $registrasi;
                $tahunPutusan = (int) ($wbpTerpilih->putusan ?? 0);
                $bulanPutusan = (int) ($wbpTerpilih->putusan_bulan ?? 0);

                $formatPutusan = [];
                if ($tahunPutusan > 0) {
                    $formatPutusan[] = $tahunPutusan . ' Tahun';
                }
                if ($bulanPutusan > 0) {
                    $formatPutusan[] = $bulanPutusan . ' Bulan';
                }

                $kodeAkses = $data?->kode_akses;
            @endphp

            {{-- IDENTITAS WBP --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="hm-profile-icon">
                                    <i class="feather-user"></i>
                                </span>
                                <span class="text-muted small">Identitas Warga Binaan</span>
                            </div>

                            <h5 class="fw-bold mb-1">{{ $wbpTerpilih->nama }}</h5>

                            <div class="text-muted small">
                                No. Registrasi:
                                <strong>{{ $wbpTerpilih->no_reg_instansi ?: '-' }}</strong>
                            </div>
                        </div>

                        <span
                            class="badge {{ $data ? 'bg-success-subtle text-success' : 'bg-light text-dark border' }} rounded-pill px-3 py-2">
                            {{ $data ? 'Data Halo Minna Tersedia' : 'Belum Terdaftar Halo Minna' }}
                        </span>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="hm-info-box">
                                <small class="text-muted d-block mb-1">Nomor Registrasi</small>
                                <div class="fw-semibold">{{ $wbpTerpilih->no_reg_instansi ?: '-' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="hm-info-box">
                                <small class="text-muted d-block mb-1">Putusan</small>
                                <div class="fw-semibold">
                                    {{ $formatPutusan ? implode(' ', $formatPutusan) : ($wbpTerpilih->putusan ?: '-') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="hm-info-box">
                                <small class="text-muted d-block mb-1">Status WBP</small>
                                <div class="fw-semibold">{{ $wbpTerpilih->status_wbp ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="hm-info-box">
                                <small class="text-muted d-block mb-1">Masa 1/2</small>
                                <div class="fw-semibold">
                                    {{ $wbpTerpilih->masa_1_2 ? \Illuminate\Support\Carbon::parse($wbpTerpilih->masa_1_2)->format('d-m-Y') : '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="hm-info-box">
                                <small class="text-muted d-block mb-1">Masa 2/3</small>
                                <div class="fw-semibold">
                                    {{ $wbpTerpilih->masa_2_3 ? \Illuminate\Support\Carbon::parse($wbpTerpilih->masa_2_3)->format('d-m-Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hm-access-box mt-4">
                        <div>
                            <small class="text-muted d-block mb-1">Kode Akses Halo Minna</small>
                            <div class="hm-access-code" id="kodeAksesHaloMinna">
                                {{ $kodeAkses ?: 'Kode dibuat saat data pertama kali disimpan' }}
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @if ($kodeAkses)
                                <button type="button" class="btn btn-outline-primary rounded-pill px-3"
                                    id="btnSalinKodeAkses" data-kode="{{ $kodeAkses }}">
                                    <i class="feather-copy me-1"></i>
                                    Salin Kode
                                </button>
                            @endif

                            @if ($wbpTerpilih && $registrasi)
                                <form action="{{ route('halo-minna.generate-ulang-kode', $wbpTerpilih->id) }}"
                                    method="POST" class="d-inline"
                                    onsubmit="return confirm('Generate ulang kode akses Halo Minna? Kode lama akan langsung tidak berlaku dan keluarga harus menggunakan kode baru.');">
                                    @csrf

                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <input type="hidden" name="page" value="{{ request('page', 1) }}">

                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                                        <i class="feather-refresh-cw me-1"></i>
                                        Generate Ulang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PETUNJUK --}}
            <div class="alert alert-info border-0 rounded-4 mb-4">
                <div class="d-flex gap-2">
                    <i class="feather-info mt-1"></i>
                    <div>
                        <div class="fw-semibold mb-1">Petunjuk pengisian</div>
                        <div class="small">
                            Simpan perubahan di masing-masing kartu setelah selesai mengisi.
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU 1: IDENTITAS & REGISTRASI --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="hm-card-heading mb-4">
                        <div class="hm-card-icon bg-primary-subtle text-primary">
                            <i class="feather-clipboard"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">1. Identitas & Registrasi</h5>
                            <small class="text-muted">Status register dan jenis usulan integrasi.</small>
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('halo-minna.simpan', ['wbp' => $wbpTerpilih->id, 'bagian' => 'identitas']) }}">
                        @csrf

                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="page" value="{{ request('page') }}">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="register_f" class="form-label fw-semibold">
                                    Register F <span class="text-danger">*</span>
                                </label>

                                <select name="register_f" id="register_f" class="form-select" required>
                                    <option value="Tidak"
                                        {{ old('register_f', $data?->register_f ?? 'Tidak') === 'Tidak' ? 'selected' : '' }}>
                                        Tidak
                                    </option>
                                    <option value="Ya"
                                        {{ old('register_f', $data?->register_f ?? 'Tidak') === 'Ya' ? 'selected' : '' }}>
                                        Ya
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="jenis_usulan" class="form-label fw-semibold">
                                    Jenis Usulan <span class="text-danger">*</span>
                                </label>

                                <select name="jenis_usulan" id="jenis_usulan" class="form-select" required>
                                    @foreach (['Belum Usul', 'Asimilasi', 'Cuti Bersyarat', 'Cuti Mengunjungi Keluarga', 'Cuti Menjelang Bebas', 'Pembebasan Bersyarat'] as $jenisUsulan)
                                        <option value="{{ $jenisUsulan }}"
                                            {{ old('jenis_usulan', $data?->jenis_usulan ?? 'Belum Usul') === $jenisUsulan ? 'selected' : '' }}>
                                            {{ $jenisUsulan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="feather-save me-1"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KARTU 2: BERKAS & LITMAS --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="hm-card-heading mb-4">
                        <div class="hm-card-icon bg-info-subtle text-info">
                            <i class="feather-folder"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">2. Alur Berkas & Litmas</h5>
                            <small class="text-muted">Pengambilan berkas sampai dengan Sidang TPP.</small>
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('halo-minna.simpan', ['wbp' => $wbpTerpilih->id, 'bagian' => 'berkas']) }}">
                        @csrf

                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="page" value="{{ request('page') }}">

                        <div class="row g-3">
                            @foreach ([
            'tgl_pengambilan_berkas' => 'Pengambilan Berkas',
            'tgl_pengembalian_berkas' => 'Pengembalian Berkas',
            'tgl_permintaan_litmas' => 'Permintaan Litmas',
            'tgl_litmas_bapas' => 'Litmas Bapas',
            'tgl_hasil_litmas' => 'Hasil Litmas',
            'tgl_sidang_tpp' => 'Sidang TPP',
        ] as $field => $label)
                                <div class="col-12 col-md-6">
                                    <label for="{{ $field }}" class="form-label fw-semibold">
                                        {{ $label }}
                                    </label>

                                    <input type="date" name="{{ $field }}" id="{{ $field }}"
                                        class="form-control"
                                        value="{{ old($field, $data?->{$field}?->format('Y-m-d')) }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="feather-save me-1"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KARTU 3: SDP & SK --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="hm-card-heading mb-4">
                        <div class="hm-card-icon bg-warning-subtle text-warning">
                            <i class="feather-file-text"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">3. Monitoring SDP & SK</h5>
                            <small class="text-muted">Monitoring proses verifikasi hingga otorisasi SK.</small>
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('halo-minna.simpan', ['wbp' => $wbpTerpilih->id, 'bagian' => 'sdp_sk']) }}">
                        @csrf

                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="page" value="{{ request('page') }}">

                        <div class="row g-3">
                            @foreach ([
            'tgl_input_sdp' => 'Input SDP',
            'tgl_verifikasi_ditjen' => 'Verifikasi Ditjen',
            'tgl_persetujuan_menteri' => 'Persetujuan Menteri',
            'tgl_otorisasi_sk' => 'Otorisasi SK',
        ] as $field => $label)
                                <div class="col-12 col-md-6">
                                    <label for="{{ $field }}" class="form-label fw-semibold">
                                        {{ $label }}
                                    </label>

                                    <input type="date" name="{{ $field }}" id="{{ $field }}"
                                        class="form-control"
                                        value="{{ old($field, $data?->{$field}?->format('Y-m-d')) }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="feather-save me-1"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KARTU 4: TAHAP AKHIR --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="hm-card-heading mb-4">
                        <div class="hm-card-icon bg-success-subtle text-success">
                            <i class="feather-check-circle"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">4. Tahap Akhir</h5>
                            <small class="text-muted">Pelaksanaan program dan status pembebasan WBP.</small>
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('halo-minna.simpan', ['wbp' => $wbpTerpilih->id, 'bagian' => 'tahap_akhir']) }}">
                        @csrf

                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="page" value="{{ request('page') }}">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="tgl_pelaksanaan" class="form-label fw-semibold">
                                    Pelaksanaan
                                </label>

                                <input type="date" id="tgl_pelaksanaan" class="form-control"
                                    value="{{ old('tgl_pelaksanaan', $data?->tgl_pelaksanaan?->format('Y-m-d')) }}"
                                    readonly>

                                <div class="form-text">
                                    Tanggal pelaksanaan menyesuaikan hari kerja berdasarkan tanggal SK Turun.
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="tgl_pembebasan" class="form-label fw-semibold">
                                    Pembebasan
                                </label>

                                <input type="date" name="tgl_pembebasan" id="tgl_pembebasan" class="form-control"
                                    value="{{ old('tgl_pembebasan', $data?->tgl_pembebasan?->format('Y-m-d')) }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="status_bebas" class="form-label fw-semibold">
                                    Status Bebas <span class="text-danger">*</span>
                                </label>

                                <select name="status_bebas" id="status_bebas" class="form-select" required>
                                    @foreach (['Belum Bebas', 'Sudah Bebas', 'Batal'] as $statusBebas)
                                        <option value="{{ $statusBebas }}"
                                            {{ old('status_bebas', $data?->status_bebas ?? 'Belum Bebas') === $statusBebas ? 'selected' : '' }}>
                                            {{ $statusBebas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="feather-save me-1"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- KARTU 5: PENJAMIN --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="hm-card-heading mb-4">
                        <div class="hm-card-icon bg-secondary-subtle text-secondary">
                            <i class="feather-users"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">5. Data Penjamin</h5>
                            <small class="text-muted">Informasi penjamin atau keluarga WBP.</small>
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ route('halo-minna.simpan', ['wbp' => $wbpTerpilih->id, 'bagian' => 'penjamin']) }}">
                        @csrf

                        <input type="hidden" name="search" value="{{ $search }}">
                        <input type="hidden" name="page" value="{{ request('page') }}">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="nama_penjamin" class="form-label fw-semibold">
                                    Nama Penjamin
                                </label>

                                <input type="text" name="nama_penjamin" id="nama_penjamin" class="form-control"
                                    maxlength="255" value="{{ old('nama_penjamin', $data?->nama_penjamin) }}"
                                    placeholder="Masukkan nama penjamin">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="hubungan_penjamin" class="form-label fw-semibold">
                                    Hubungan dengan WBP
                                </label>

                                <input type="text" name="hubungan_penjamin" id="hubungan_penjamin"
                                    class="form-control" maxlength="100"
                                    value="{{ old('hubungan_penjamin', $data?->hubungan_penjamin) }}"
                                    placeholder="Contoh: Istri, suami, orang tua">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="no_hp_penjamin" class="form-label fw-semibold">
                                    Nomor HP Penjamin
                                </label>

                                <input type="text" name="no_hp_penjamin" id="no_hp_penjamin" class="form-control"
                                    maxlength="20" value="{{ old('no_hp_penjamin', $data?->no_hp_penjamin) }}"
                                    placeholder="Masukkan nomor HP">
                            </div>

                            <div class="col-12">
                                <label for="alamat_penjamin" class="form-label fw-semibold">
                                    Alamat Penjamin
                                </label>

                                <textarea name="alamat_penjamin" id="alamat_penjamin" class="form-control" rows="3"
                                    placeholder="Masukkan alamat lengkap penjamin">{{ old('alamat_penjamin', $data?->alamat_penjamin) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="feather-save me-1"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Autocomplete pencarian WBP untuk Halo Minna.
        document.addEventListener('DOMContentLoaded', function() {
            const wrapper = document.querySelector('.hm-autocomplete');
            const input = document.getElementById('searchWbp');
            const suggestions = document.getElementById('haloMinnaSuggestions');

            if (!wrapper || !input || !suggestions) return;

            const autocompleteUrl = wrapper.dataset.autocompleteUrl;
            const detailUrl = wrapper.dataset.detailUrl;
            let timer = null;
            let controller = null;
            let requestNumber = 0;

            function hideSuggestions() {
                suggestions.classList.add('d-none');
                suggestions.replaceChildren();
                input.setAttribute('aria-expanded', 'false');
            }

            function showMessage(message) {
                suggestions.replaceChildren();
                const item = document.createElement('div');
                item.className = 'hm-autocomplete-message';
                item.textContent = message;
                suggestions.appendChild(item);
                suggestions.classList.remove('d-none');
                input.setAttribute('aria-expanded', 'true');
            }

            function renderResults(items) {
                suggestions.replaceChildren();

                if (!items.length) {
                    showMessage('WBP tidak ditemukan.');
                    return;
                }

                items.forEach(function(wbp) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'hm-autocomplete-item';
                    button.setAttribute('role', 'option');
                    button.dataset.wbpId = wbp.id;

                    const name = document.createElement('span');
                    name.className = 'hm-autocomplete-item-name';
                    name.textContent = wbp.nama || '(Nama tidak tersedia)';

                    const reg = document.createElement('span');
                    reg.className = 'hm-autocomplete-item-reg';
                    reg.textContent = 'No. Registrasi: ' + (wbp.no_reg_instansi || '-');

                    button.append(name, reg);
                    suggestions.appendChild(button);
                });

                suggestions.classList.remove('d-none');
                input.setAttribute('aria-expanded', 'true');
            }

            input.addEventListener('input', function() {
                clearTimeout(timer);

                if (controller) controller.abort();

                const query = input.value.trim();
                if (query.length < 2) {
                    hideSuggestions();
                    return;
                }

                timer = setTimeout(async function() {
                    controller = new AbortController();
                    const thisRequest = ++requestNumber;
                    showMessage('Mencari WBP...');

                    try {
                        const url = new URL(autocompleteUrl, window.location.origin);
                        url.searchParams.set('q', query);

                        const response = await fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json'
                            },
                            signal: controller.signal
                        });

                        if (!response.ok) throw new Error('Gagal mengambil saran WBP.');

                        const data = await response.json();
                        if (thisRequest !== requestNumber) return;

                        renderResults(data);
                    } catch (error) {
                        if (error.name === 'AbortError') return;
                        showMessage('Saran WBP gagal dimuat. Coba lagi.');
                    }
                }, 300);
            });

            suggestions.addEventListener('click', function(event) {
                const option = event.target.closest('.hm-autocomplete-item');
                if (!option) return;

                const wbpId = option.dataset.wbpId;
                if (!wbpId) return;

                const url = new URL(detailUrl, window.location.origin);
                url.searchParams.set('wbp_id', wbpId);

                const query = input.value.trim();
                if (query) url.searchParams.set('search', query);

                window.location.href = url.toString();
            });

            document.addEventListener('click', function(event) {
                if (!wrapper.contains(event.target)) hideSuggestions();
            });

            input.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') hideSuggestions();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tombolSalin = document.getElementById('btnSalinKodeAkses');

            if (!tombolSalin) {
                return;
            }

            tombolSalin.addEventListener('click', async function() {
                const kode = this.dataset.kode;

                if (!kode) {
                    return;
                }

                try {
                    await navigator.clipboard.writeText(kode);

                    const teksAwal = this.innerHTML;
                    this.innerHTML = '<i class="feather-check me-1"></i> Tersalin';

                    setTimeout(() => {
                        this.innerHTML = teksAwal;
                    }, 1800);
                } catch (error) {
                    // Fallback untuk browser yang tidak menyediakan Clipboard API.
                    const inputSementara = document.createElement('textarea');
                    inputSementara.value = kode;
                    inputSementara.style.position = 'fixed';
                    inputSementara.style.opacity = '0';

                    document.body.appendChild(inputSementara);
                    inputSementara.select();

                    const berhasil = document.execCommand('copy');
                    inputSementara.remove();

                    if (berhasil) {
                        const teksAwal = this.innerHTML;
                        this.innerHTML = '<i class="feather-check me-1"></i> Tersalin';

                        setTimeout(() => {
                            this.innerHTML = teksAwal;
                        }, 1800);
                    } else {
                        alert('Kode akses: ' + kode);
                    }
                }
            });
        });
    </script>
@endsection
