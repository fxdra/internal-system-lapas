@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        /* ================== CSS MODAL ================== */
        .jq-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 15px;
        }

        .jq-modal-overlay.active {
            display: flex;
        }

        .jq-modal-card {
            background: #fff;
            width: 100%;
            max-width: 700px;
            border-radius: 14px;
            overflow: hidden;
        }

        .jq-modal-header {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .jq-modal-body {
            padding: 15px;
            max-height: 75vh;
            overflow-y: auto;
        }

        .jq-close {
            border: none;
            background: transparent;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
        }

        div.d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between p {
            display: none !important;
        }
    </style>

 

    {{-- ================= DATA KUNJUNGAN ================= --}}
    <div class="container my-5">
        <div class="card shadow-sm">
            <div
                class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>Data Kunjungan</span>
                <input type="text" id="searchKunjungan" class="form-control form-control-sm"
                    placeholder="Cari Nama Pengunjung / NIK / Nama WBP..." style="max-width: 320px"
                    value="{{ request('search') }}">
            </div>
            <div class="card-body">
                <div id="kunjunganWrapper">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengunjung</th>
                                    <th>No. Identitas</th>
                                    <th>Nama WBP</th>
                                    <th>Tanggal</th>
                                    <th>Sesi</th>
                                    <th>Foto KTP</th>
                                    <th>Foto Selfie</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengunjungs as $i => $p)
                                    <tr>
                                        <td class="text-center">
                                            {{ ($pengunjungs->currentPage() - 1) * $pengunjungs->perPage() + $loop->iteration }}
                                        </td>
                                        <td>{{ $p->nama_pengunjung }}</td>
                                        <td>{{ $p->nik_pengunjung }}</td>
                                        <td>{{ $p->nama_wbp }}</td>
                                        <td class="text-center">
                                            {{ \Carbon\Carbon::parse($p->tanggal_kunjungan)->format('d-m-Y') }}</td>
                                        <td class="text-center"><span
                                                class="badge bg-info">{{ strtoupper($p->sesi_kunjungan) }}</span></td>
                                        <td class="text-center">
                                            @if ($p->foto_ktp)
                                                <button type="button" class="btn btn-sm btn-success btn-view-foto"
                                                    data-id="{{ $p->id }}"
                                                    data-title="Foto KTP - {{ $p->nama_pengunjung }}"
                                                    data-img="{{ asset('storage/' . $p->foto_ktp) }}">Lihat</button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($p->foto_selfie)
                                                <button type="button" class="btn btn-sm btn-primary btn-view-selfie"
                                                    data-id="{{ $p->id }}"
                                                    data-title="Foto Selfie - {{ $p->nama_pengunjung }}"
                                                    data-img="{{ asset('storage/' . $p->foto_selfie) }}">Lihat</button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center"><span
                                                class="badge bg-success">{{ ucfirst($p->status_barcode) }}</span></td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-info btn-sm btn-view-detail mx-2"
                                                    data-id="{{ $p->id }}">Detail</button>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm btn-hapus-kunjungan mx-2"
                                                    data-id="{{ $p->id }}">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Data kunjungan belum tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($pengunjungs->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $pengunjungs->links('pagination::bootstrap-5') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL DETAIL/FOTO ================= --}}
    <div class="jq-modal-overlay" id="jqModal">
        <div class="jq-modal-card shadow-lg">
            <div class="jq-modal-header">
                <h5 id="jqModalTitle" class="fw-bold mb-0">Detail Kunjungan</h5>
                <button type="button" class="jq-close">&times;</button>
            </div>
            <div class="jq-modal-body" id="jqModalBody"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {

        // ================== CSRF ==================
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ================== MODAL OPEN / CLOSE ==================
        const openModal = (modalId) => $(modalId).addClass('active');
        const closeModal = (modalId) => $(modalId).removeClass('active');

        // Close modal klik tombol
        $(document).on('click', '.jq-close', function() {
            closeModal('#jqModal');
        });

        // Close modal klik overlay
        $(document).on('click', '.jq-modal-overlay', function(e) {
            if (e.target === this) closeModal('#' + this.id);
        });

        // ================== MODAL DETAIL / FOTO ==================
        const renderModalFoto = (title, img, id) => {
            $('#jqModalTitle').text(title);
            $('#jqModalBody').html(
                `<div class="text-center">
                    <img src="${img}" class="img-fluid rounded shadow-sm mb-3">
                    <div class="text-muted small">
                        ID Pengunjung: <strong>${id}</strong>
                    </div>
                </div>`
            );
            openModal('#jqModal');
        };

        $(document).on('click', '.btn-view-foto', function() {
            renderModalFoto($(this).data('title'), $(this).data('img'), $(this).data('id'));
        });

        $(document).on('click', '.btn-view-selfie', function() {
            renderModalFoto($(this).data('title'), $(this).data('img'), $(this).data('id'));
        });

        $(document).on('click', '.btn-view-detail', function() {
            const id = $(this).data('id');

            $('#jqModalTitle').text('Detail Kunjungan');
            $('#jqModalBody').html(
                '<div class="py-5 text-center"><div class="spinner-border text-primary"></div><div class="mt-2 small text-muted">Memuat data...</div></div>'
            );
            openModal('#jqModal');

            $.get(`/admin/kunjungan/${id}/detail`)
                .done(res => {
                    let pengikutHtml =
                        '<div class="text-muted fst-italic text-justify">Tidak ada pengikut</div>';

                    if (res.pengikut && res.pengikut.length > 0) {
                        pengikutHtml =
                            `<div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th style="width:60px">No</th>
                                            <th>Nama Pengikut</th>
                                            <th>No Identitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${res.pengikut.map((p,i)=>`
                                            <tr>
                                                <td class="text-center">${i+1}</td>
                                                <td class="text-justify">${p.nama_pengikut}</td>
                                                <td>${p.nik_pengikut}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>`;
                    }

                    $('#jqModalBody').html(`
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-2">Data WBP</h6>
                                <table class="table table-sm table-borderless mb-3">
                                    <tr><td class="fw-semibold" width="150">No Registrasi</td><td>:</td><td class="text-justify">${res.wbp?.no_reg_instansi ?? '-'}</td></tr>
                                    <tr><td class="fw-semibold">Nama</td><td>:</td><td class="text-justify">${res.wbp?.nama ?? '-'}</td></tr>
                                    <tr><td class="fw-semibold">Blok</td><td>:</td><td>${res.wbp?.lokasi_blok ?? '-'}</td></tr>
                                    <tr><td class="fw-semibold">Sel</td><td>:</td><td>${res.wbp?.lokasi_sel ?? '-'}</td></tr>
                                </table>

                                <hr class="my-3">

                                <h6 class="fw-bold text-primary mb-2">Data Pengunjung</h6>
                                <table class="table table-sm table-borderless mb-3">
                                    <tr><td class="fw-semibold" width="150">Nama</td><td>:</td><td class="text-justify">${res.nama_pengunjung}</td></tr>
                                    <tr><td class="fw-semibold">NIK</td><td>:</td><td>${res.nik_pengunjung}</td></tr>
                                    <tr><td class="fw-semibold">Tanggal</td><td>:</td><td>${res.tanggal_kunjungan}</td></tr>
                                    <tr><td class="fw-semibold">Sesi</td><td>:</td><td><span class="badge bg-info">${res.sesi_kunjungan ?? 'Pagi'}</span></td></tr>
                                </table>

                                <hr class="my-3">

                                <h6 class="fw-bold text-primary mb-2">Data Pengikut</h6>
                                ${pengikutHtml}
                            </div>
                        </div>
                    `);
                })
                .fail(() => {
                    $('#jqModalBody').html('<div class="alert alert-danger text-center">Gagal memuat data detail</div>');
                });
        });

        // ================== HAPUS KUNJUNGAN ==================
        $(document).on('click', '.btn-hapus-kunjungan', function() {
            const id = $(this).data('id');
            if (!confirm('Yakin ingin menghapus data ini?')) return;

            $.post(`/admin/kunjungan/${id}`, { _method: 'DELETE' })
                .done(() => location.reload())
                .fail(xhr => alert(xhr.responseJSON?.message ?? 'Gagal menghapus data'));
        });

        // ================== SEARCH LIVE & PAGINATION ==================
        let timerKunjungan;
        $(document).on('input', '#searchKunjungan', function() {
            clearTimeout(timerKunjungan);

            timerKunjungan = setTimeout(() => {
                $.get(window.location.pathname, {
                    search: $('#searchKunjungan').val()
                }, res => {
                    $('#kunjunganWrapper').html($(res).find('#kunjunganWrapper').html());
                });
            }, 300);
        });

        $(document).on('click', '#kunjunganWrapper .pagination a', function(e) {
            e.preventDefault();

            const url = $(this).attr('href');
            $.get(url, {
                search: $('#searchKunjungan').val()
            }, res => {
                $('#kunjunganWrapper').html($(res).find('#kunjunganWrapper').html());
            });
        });

        // ================== NONAKTIFKAN TANGGAL TERLARANG ==================
        const inputTanggal = document.getElementById('tanggal');
        if (inputTanggal) {
            const today = new Date();
            inputTanggal.min = today.toISOString().split('T')[0];

            inputTanggal.addEventListener('input', function() {
                const day = new Date(this.value).getDay();
                if (day === 0 || day === 5 || day === 6) {
                    alert('Kunjungan tidak tersedia pada hari Jumat, Sabtu, dan Minggu');
                    this.value = '';
                }
            });
        }

    });
</script>

@endsection
