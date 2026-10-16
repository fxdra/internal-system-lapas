@extends('admin-banceuy.partisi.main')

@section('content')

    <style>
        .program-pembinaan-page .pp-pagination .small.text-muted {
            display: none !important;
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

        {{-- FILTER KATEGORI DAN PROGRAM --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="mb-3">
                    <h5 class="fw-bold mb-1">Filter Program</h5>
                    <small class="text-muted">
                        Pilih kategori dan program untuk menyaring daftar WBP serta riwayat.
                    </small>
                </div>

                <form method="GET" action="{{ route('program-pembinaan.index') }}" id="filterProgramForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4">
                            <label for="filterKategori" class="form-label fw-semibold">
                                Kategori Pembinaan
                            </label>
                            <select name="kategori" id="filterKategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                <option value="Kepribadian" {{ $kategori === 'Kepribadian' ? 'selected' : '' }}>
                                    Pembinaan Kepribadian
                                </option>
                                <option value="Kemandirian" {{ $kategori === 'Kemandirian' ? 'selected' : '' }}>
                                    Pembinaan Kemandirian
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
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
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Tambah Keikutsertaan WBP</h5>
                        <small class="text-muted">
                            Pilih program, cari WBP, lalu centang WBP yang akan ditambahkan.
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
                                <option value="">Semua Blok</option>
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
                            <select name="kamar_id" id="filterKamar" class="form-control">
                                <option value="">Semua Kamar</option>
                                @foreach ($kamarList as $itemKamar)
                                    <option value="{{ $itemKamar->id }}" data-blok="{{ $itemKamar->kode_blok }}"
                                        {{ (string) ($kamarId ?? '') === (string) $itemKamar->id ? 'selected' : '' }}>
                                        {{ $itemKamar->lokasi_sel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <label for="searchWbp" class="form-label fw-semibold">
                        Cari WBP
                    </label>
                    <div class="row g-2">
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
                            <button type="submit" class="btn btn-primary flex-grow-1">Cari</button>

                            @if ($search !== '')
                                <a href="{{ route(
                                    'program-pembinaan.index',
                                    array_filter([
                                        'kategori' => $kategori,
                                        'program_id' => $selectedProgram?->id,
                                    ]),
                                ) }}"
                                    class="btn btn-light">
                                    Hapus
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ route('program-pembinaan.store') }}" id="formTambahPembinaan">
                    @csrf

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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($wbps as $wbp)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input wbp-checkbox" name="wbp_ids[]"
                                                value="{{ $wbp->id }}"
                                                {{ in_array((string) $wbp->id, array_map('strval', (array) old('wbp_ids', [])), true) ? 'checked' : '' }}
                                                aria-label="Pilih {{ $wbp->nama }}">
                                        </td>
                                        <td>{{ $wbps->firstItem() + $loop->index }}</td>
                                        <td>{{ $wbp->no_reg_instansi ?: '-' }}</td>
                                        <td class="fw-semibold">{{ $wbp->nama }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
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
                                <strong>{{ $wbps->firstItem() ?? 0 }}–{{ $wbps->lastItem() ?? 0 }}</strong>
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
                                <th style="min-width: 330px;">Perbarui Data</th>
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
                                        <small class="text-muted">
                                            {{ $item->wbp?->no_reg_instansi ?? '-' }}
                                        </small>
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
                                            <span class="badge bg-warning-subtle text-warning-emphasis">
                                                Kurang Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary">
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
                                        <form method="POST"
                                            action="{{ route('program-pembinaan.keikutsertaan.update', $item->id) }}"
                                            class="form-update-keikutsertaan">
                                            @csrf
                                            @method('PUT')

                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <label class="form-label small mb-1">
                                                        Indikator Keaktifan
                                                    </label>
                                                    <select name="indikator_keaktifan" class="form-select form-select-sm"
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

                                                <div class="col-12">
                                                    <label class="form-label small mb-1">
                                                        Status Keikutsertaan
                                                    </label>
                                                    <select name="status_keikutsertaan"
                                                        class="form-select form-select-sm status-keikutsertaan" required>
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

                                                <div class="col-12 tanggal-selesai-wrapper"
                                                    style="{{ $item->status_keikutsertaan === 'Selesai' ? '' : 'display:none;' }}">
                                                    <label class="form-label small mb-1">
                                                        Tanggal Selesai
                                                    </label>
                                                    <input type="date" name="tanggal_selesai"
                                                        class="form-control form-control-sm"
                                                        min="{{ $item->tanggal_mulai?->format('Y-m-d') }}"
                                                        value="{{ $item->tanggal_selesai?->format('Y-m-d') }}">
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label small mb-1">
                                                        Keterangan
                                                    </label>
                                                    <textarea name="keterangan" class="form-control form-control-sm" rows="2" maxlength="2000">{{ $item->keterangan }}</textarea>
                                                </div>

                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                        <i class="feather-save me-1"></i>
                                                        Simpan Perubahan
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="feather-inbox fs-2 text-muted"></i>
                                        <div class="fw-semibold mt-2">Belum ada riwayat keikutsertaan</div>
                                        <small class="text-muted">
                                            Pilih program dan tambahkan WBP untuk mulai mencatat pembinaan.
                                        </small>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pp-pagination-wrap">
                    <div class="pp-pagination-info">
                        <span class="pp-pagination-label">Riwayat Keikutsertaan</span>
                        <span>
                            Menampilkan
                            <strong>{{ $riwayat->firstItem() ?? 0 }}–{{ $riwayat->lastItem() ?? 0 }}</strong>
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

    </div>

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

        .program-pembinaan-page .card {
            overflow: hidden;
        }

        .program-pembinaan-page .form-control,
        .program-pembinaan-page .form-select,
        .program-pembinaan-page .input-group-text {
            border-radius: 10px;
        }

        .program-pembinaan-page .input-group .form-control {
            border-radius: 0;
        }

        .program-pembinaan-page .input-group .form-control:first-child {
            border-radius: 10px 0 0 10px;
        }

        .program-pembinaan-page .input-group .btn:last-child {
            border-radius: 0 10px 10px 0;
        }

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

        .program-pembinaan-page .rounded-pill {
            font-weight: 500;
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
            transition: color .2s ease, background-color .2s ease, border-color .2s ease,
                box-shadow .2s ease;
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
        }
    </style>

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
            const filterKategori = document.getElementById('filterKategori');
            const filterProgram = document.getElementById('filterProgram');

            function perbaruiPilihan() {
                const jumlah = checkboxes.filter(cb => cb.checked).length;

                if (jumlahDipilih) {
                    jumlahDipilih.textContent = jumlah;
                }

                if (pilihSemua) {
                    pilihSemua.checked = checkboxes.length > 0 &&
                        checkboxes.every(cb => cb.checked);

                    pilihSemua.indeterminate = jumlah > 0 &&
                        jumlah < checkboxes.length;
                }
            }

            if (pilihSemua) {
                pilihSemua.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    perbaruiPilihan();
                });
            }

            if (btnPilihHalaman) {
                btnPilihHalaman.addEventListener('click', function() {
                    checkboxes.forEach(cb => cb.checked = true);
                    perbaruiPilihan();
                });
            }

            if (btnBatalPilih) {
                btnBatalPilih.addEventListener('click', function() {
                    checkboxes.forEach(cb => cb.checked = false);
                    perbaruiPilihan();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', perbaruiPilihan);
            });

            perbaruiPilihan();

            if (formTambah) {
                formTambah.addEventListener('submit', function(event) {
                    const terpilih = checkboxes.some(cb => cb.checked);

                    if (!programInput.value) {
                        event.preventDefault();
                        alert('Pilih program pembinaan terlebih dahulu.');
                        programInput.focus();
                        return;
                    }

                    if (!terpilih) {
                        event.preventDefault();
                        alert('Pilih minimal satu WBP terlebih dahulu.');
                    }
                });
            }

            // Filter opsi program sesuai kategori.
            function filterOpsiProgram() {
                if (!filterKategori || !filterProgram) return;

                const kategori = filterKategori.value;
                const selectedOption = filterProgram.options[filterProgram.selectedIndex];

                if (
                    selectedOption &&
                    selectedOption.value &&
                    kategori &&
                    selectedOption.dataset.kategori !== kategori
                ) {
                    filterProgram.value = '';
                }

                Array.from(filterProgram.options).forEach(option => {
                    if (!option.value) {
                        option.hidden = false;
                        return;
                    }

                    option.hidden = Boolean(
                        kategori && option.dataset.kategori !== kategori
                    );
                });
            }

            if (filterKategori && filterProgram) {
                filterKategori.addEventListener('change', filterOpsiProgram);
                filterOpsiProgram();
            }

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
                        blok: option.dataset.blok,
                        text: option.textContent.trim()
                    }));

                function perbaruiOpsiKamar(resetPilihan = false) {
                    const blokTerpilih = filterBlokKamar.value;
                    const kamarSebelumnya = resetPilihan ?
                        '' :
                        filterKamar.value;

                    filterKamar.innerHTML = '';

                    const opsiSemua = document.createElement('option');
                    opsiSemua.value = '';
                    opsiSemua.textContent = 'Semua Kamar';
                    filterKamar.appendChild(opsiSemua);

                    semuaOpsiKamar.forEach(kamar => {
                        if (blokTerpilih && kamar.blok !== blokTerpilih) {
                            return;
                        }

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

                perbaruiOpsiKamar();

                filterBlokKamar.addEventListener('change', function() {
                    perbaruiOpsiKamar(true);
                });
            }
        });
    </script>
@endsection
