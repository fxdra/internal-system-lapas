@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        .jq-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.60);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 99999;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 18px;
            overflow-y: auto;
            /* penting biar HP bisa scroll */
        }

        /* container biar card tetap center */
        .jq-modal-overlay .container {
            max-width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* bikin center vertical */
        }

        /* Modal Card */
        .jq-modal-card {
            width: 100%;
            max-width: 820px;
            border-radius: 18px;
            overflow: hidden;
        }

        /* Body scroll */
        .jq-modal-body-scroll {
            max-height: 70vh;
            overflow-y: auto;
            padding: 18px;
        }

        /* Detail style */
        .jq-detail-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 6px;
        }

        .jq-detail-value {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            font-weight: 600;
            color: #0f172a;
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .jq-detail-textarea {
            width: 100%;
            min-height: 140px;
            resize: none;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            font-weight: 500;
            color: #0f172a;
        }

        .jq-detail-img {
            width: 100%;
            max-height: 360px;
            object-fit: contain;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 10px;
        }

        /* ===========================
           RESPONSIVE HP
        =========================== */
        @media (max-width: 576px) {
            .jq-modal-overlay {
                padding: 12px;
                align-items: flex-start;
                /* HP jangan center vertical, biar gak kepotong */
            }

            .jq-modal-overlay .container {
                min-height: auto;
                align-items: flex-start;
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .jq-modal-card {
                max-width: 100%;
                border-radius: 16px;
            }

            .jq-modal-body-scroll {
                max-height: 75vh;
                /* biar body tetap nyaman */
                padding: 14px;
            }
        }
    </style>


    <div class="container my-5">
        <div class="card shadow-sm border-0">

            {{-- HEADER --}}
            <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                <div>
                    <i class="feather-message-square me-2"></i>
                    Data Kritik & Saran
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('kritik.exportPdf') }}" class="btn btn-warning btn-sm fw-bold">
                        🧾 Export PDF
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- SEARCH --}}
                <div class="row mb-3">
                    <div class="col-md-5">
                        <input type="text" id="search" class="form-control"
                            placeholder="Cari nama / nik / jenis kritik..." autocomplete="off">
                    </div>

                    <div class="col-md-7 d-flex justify-content-end align-items-center">
                        <span class="badge bg-light text-dark border">
                            Total Data: <b id="totalData">{{ $kritik->total() }}</b>
                        </span>
                    </div>
                </div>

                {{-- TABLE WRAPPER --}}
                <div id="tableWrapper">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-light text-center align-middle">
                                <tr>
                                    <th style="width:70px;">No</th>
                                    <th>Nama Lengkap</th>
                                    <th style="width:120px;">Jenis</th>
                                    <th style="width:160px;">NIK</th>
                                    <th>Pesan (Singkat)</th>
                                    <th style="width:170px;">Tanggal</th>
                                    <th style="width:160px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($kritik as $i => $k)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($kritik->currentPage() - 1) * $kritik->perPage() + ($i + 1) }}
                                        </td>

                                        <td class="fw-semibold">{{ $k->nama_lengkap }}</td>

                                        <td class="text-center">
                                            <span class="badge bg-info text-dark">
                                                {{ strtoupper($k->jenis) }}
                                            </span>
                                        </td>

                                        <td class="text-center fw-semibold">{{ $k->nik }}</td>

                                        <td>{{ \Illuminate\Support\Str::limit($k->pesan, 60, '...') }}</td>

                                        <td class="text-center">
                                            {{ $k->created_at ? $k->created_at->format('d-m-Y H:i') : '-' }}
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-success btn-detail-kritik"
                                                    data-id="{{ $k->id }}">
                                                    Detail
                                                </button>

                                                <button type="button" class="btn btn-sm btn-danger btn-hapus-kritik"
                                                    data-id="{{ $k->id }}">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            Data kritik & saran belum tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    @if ($kritik->hasPages())
                        <div class="card-footer bg-white d-flex justify-content-center mt-3">
                            {{ $kritik->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>


    {{-- MODAL OVERLAY DETAIL --}}
    <div class="jq-modal-overlay d-none" id="jqModal" aria-hidden="true">
        <div class="container">
            <div class="card border-0 shadow-lg jq-modal-card">

                {{-- Header --}}
                <div class="card-header bg-primary text-white border-0">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <h5 id="jqModalTitle" class="fw-bold mb-0">Detail Kritik & Saran</h5>
                            <small class="opacity-75 d-block">Informasi detail pengunjung</small>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="jq-modal-body-scroll">
                    <div id="jqModalBody"></div>
                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 jq-close">
                            Tutup
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- CDN WAJIB --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            function openJqModal(title, html) {
                $('#jqModalTitle').text(title);
                $('#jqModalBody').html(html);

                $('#jqModal')
                    .removeClass('d-none')
                    .css('opacity', 0)
                    .animate({
                        opacity: 1
                    }, 120);

                $('body').css('overflow', 'hidden');
            }

            function closeJqModal() {
                $('#jqModal').animate({
                    opacity: 0
                }, 120, function() {
                    $('#jqModal').addClass('d-none');
                    $('#jqModalBody').html('');
                    $('#jqModal').css('opacity', '');
                });

                $('body').css('overflow', 'auto');
            }

            $(document).on('click', '.jq-close', function(e) {
                e.preventDefault();
                closeJqModal();
            });

            $(document).on('click', '#jqModal', function(e) {
                if (e.target.id === "jqModal") closeJqModal();
            });

            $(document).on('keydown', function(e) {
                if (e.key === "Escape") closeJqModal();
            });

            // =============================
            // DETAIL MODAL OVERLAY
            // =============================
            $(document).on('click', '.btn-detail-kritik', function() {
                const id = $(this).data('id');

                openJqModal("Detail Kritik & Saran", `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 fw-semibold text-muted">Memuat detail...</div>
                </div>
            `);

                $.get("{{ url('/admin-banceuy/kritik/detail') }}/" + id, function(res) {

                    if (!res.success) {
                        openJqModal("Detail Kritik & Saran", `
                        <div class="alert alert-danger mb-0 rounded-4">
                            ${res.message || 'Data tidak ditemukan'}
                        </div>
                    `);
                        return;
                    }

                    const k = res.data;

                    let badgeJenis =
                        `<span class="badge text-bg-secondary rounded-pill px-3 py-2">-</span>`;
                    if (k.jenis) {
                        const jenis = (k.jenis + '').toLowerCase();
                        badgeJenis = (jenis.includes('kritik')) ?
                            `<span class="badge text-bg-danger rounded-pill px-3 py-2">KRITIK</span>` :
                            `<span class="badge text-bg-success rounded-pill px-3 py-2">SARAN</span>`;
                    }

                    let fotoHtml = '';
                    if (k.foto_ktp_url) {
                        fotoHtml = `
                        <div class="col-12">
                            <div class="jq-detail-label">Foto KTP</div>
                            <img src="${k.foto_ktp_url}" class="jq-detail-img" alt="Foto KTP">
                            <div class="d-flex justify-content-end mt-2">
                                <a href="${k.foto_ktp_url}" target="_blank"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    🔍 Buka Foto
                                </a>
                            </div>
                        </div>
                    `;
                    }

                    const html = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="jq-detail-label">Nama Lengkap</div>
                            <div class="jq-detail-value">${k.nama_lengkap ?? '-'}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="jq-detail-label">Jenis</div>
                            <div class="d-flex align-items-center gap-2">
                                ${badgeJenis}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="jq-detail-label">NIK</div>
                            <div class="jq-detail-value">${k.nik ?? '-'}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="jq-detail-label">Tanggal</div>
                            <div class="jq-detail-value">${k.tanggal ?? '-'}</div>
                        </div>

                        <div class="col-12">
                            <div class="jq-detail-label">Pesan</div>
                            <textarea class="jq-detail-textarea" readonly>${k.pesan ?? '-'}</textarea>
                        </div>

                        ${fotoHtml}
                    </div>
                `;

                    openJqModal("Detail Kritik & Saran", html);

                }).fail(function(xhr) {
                    console.log(xhr.responseText);
                    openJqModal("Detail Kritik & Saran", `
                    <div class="alert alert-danger mb-0 rounded-4">
                        Gagal mengambil data detail (server error).
                    </div>
                `);
                });
            });
            
           $(document).on('click', '.btn-hapus-kritik', function() {
                const id = $(this).data('id');
                if (!confirm('Yakin ingin menghapus data ini?')) return;
            
                $.post(`/admin-banceuy/kritik/hapus/${id}`, {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                })
                .done(() => location.reload())
                .fail(xhr => alert(xhr.responseJSON?.message ?? 'Gagal menghapus data'));
            });
                       

        });
    </script>


    <style>
        .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between {
            display: none !important;
        }
    </style>
@endsection
