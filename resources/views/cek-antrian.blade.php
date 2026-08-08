@extends('startup_view.main') {{-- sesuaikan layout kamu --}}

@section('content')
    <style>
        #sesiContainer {
            padding-left: 12px;
            padding-right: 12px;
        }

        /* ===============================
       CARD SESI (GLASS MODERN)
    ================================ */
        .custom-card {
            background: rgb(105, 119, 126);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            transition: all .25s ease;
        }

        .custom-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .25);
        }

        /* ===============================
       CARD BODY
    ================================ */
        .custom-card .card-body {
            padding: 20px 16px;
        }

        /* ===============================
       TITLE SESI
    ================================ */
        .custom-card h5.card-title {
            font-size: 1.1rem;
            letter-spacing: .5px;
        }

        /* ===============================
       JAM KUNJUNGAN
    ================================ */
        .custom-card p {
            font-size: .9rem;
            color: rgba(255, 255, 255, .85);
        }

        /* ===============================
       JUMLAH PENGUNJUNG
    ================================ */
        .custom-card h2 {
            font-size: 2rem;
            margin-bottom: 4px;
        }

        /* ===============================
       BADGE
    ================================ */
        .custom-card .badge {
            font-size: .75rem;
            padding: 6px 12px;
            border-radius: 999px;
        }

        /* ===============================
       BUTTON PILIH
    ================================ */
        .btn-sesi-siang {
            border-radius: 999px;
            padding: 8px 22px;
            font-weight: 600;
            transition: all .2s ease;
        }

        .btn-sesi-siang:hover {
            transform: scale(1.05);
        }

        /* ===============================
       ALERT CATATAN
    ================================ */
        .alert-warning {
            background: rgba(255, 193, 7, .12);
            border: 1px solid rgba(255, 193, 7, .35);
            color: #fff;
        }

        /* ===============================
       MODAL CLEAN LOOK
    ================================ */
        .modal-content {
            border-radius: 16px;
            border: none;
        }

        /* ===============================
       INPUT DATE
    ================================ */
        input[type="date"] {
            border-radius: 12px;
            padding: 10px 14px;
        }

        /* ===============================
       RESPONSIVE MOBILE
    ================================ */
        @media (max-width: 576px) {

            .custom-card h2 {
                font-size: 1.6rem;
            }

            .custom-card h5.card-title {
                font-size: 1rem;
            }

            .custom-card p {
                font-size: .85rem;
            }

            .btn-sesi-siang {
                padding: 6px 18px;
                font-size: .85rem;
            }

            .alert-warning {
                font-size: .85rem;
            }
        }
    </style>

    <div class="container my-5">
        <div class="card shadow-lg border-0 custom-card">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1">CEK NOMOR ANTRIAN</h4>
                    <p class="text-muted mb-0">Masukkan No. Identitas untuk melihat bukti kunjungan</p>
                </div>

                <!-- INPUT NIK -->
                <div class="mb-4 input_nik_pengunjung">
                    <label for="nik_pengunjung" class="form-label fw-bold">No. Identitas *</label>
                    <input type="tel" class="form-control text-center" id="nik_pengunjung" placeholder="Masukkan NIK..."
                        maxlength="16">
                    <div class="form-text text-muted text-center mt-2">
                        Input akan otomatis mencari data ketika 16 digit sudah lengkap
                    </div>
                </div>

                <!-- HASIL ANTRIAN -->
                <div id="hasilAntrian"></div>

            </div>
        </div>
    </div>

    <!-- MODAL ERROR -->
    <div class="modal fade" id="modalError" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="modalErrorBody"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            function safeText(v) {
                return (v === null || v === undefined || v === '') ? '-' : v;
            }

            function formatTanggalIndo(tanggal) {
                if (!tanggal) return '-';
                const onlyDate = tanggal.toString().split(' ')[0];
                const d = new Date(onlyDate);
                if (isNaN(d.getTime())) return tanggal;

                return new Intl.DateTimeFormat('id-ID', {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }).format(d);
            }

            function badgeStatus(status) {
                if (!status) return `<span class="badge bg-secondary">-</span>`;

                const s = status.toString().toLowerCase();

                if (s.includes('pending')) return `<span class="badge bg-warning text-dark">PENDING</span>`;
                if (s.includes('menunggu')) return `<span class="badge bg-warning text-dark">MENUNGGU</span>`;
                if (s.includes('selesai')) return `<span class="badge bg-success">SELESAI</span>`;
                if (s.includes('batal')) return `<span class="badge bg-danger">BATAL</span>`;
                if (s.includes('ditolak')) return `<span class="badge bg-danger">DITOLAK</span>`;

                return `<span class="badge bg-info text-dark">${status}</span>`;
            }

            function getJamKunjunganBySesi(sesi) {
                if (!sesi) return '-';
                const s = sesi.toString().toUpperCase();
                if (s === 'PAGI') return '08:00 - 12:00 WIB';
                if (s === 'SIANG') return '13:00 - 15:00 WIB';
                return '-';
            }

            function renderPengikutTable(pengikutArr) {
                if (!pengikutArr || pengikutArr.length === 0) {
                    return `
                            <div class="alert alert-warning mb-0">
                                Tidak ada pengikut.
                            </div>
                        `;
                }

                return `
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">No</th>
                                        <th>Nama Pengikut</th>
                                        <th>No Identitas</th>
                                        <th>Jenis Identitas</th>
                                        <th>Hubungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${pengikutArr.map((p, i) => `
                                            <tr>
                                                <td>${i + 1}</td>
                                                <td class="fw-bold text-uppercase">${safeText(p.nama_pengikut)}</td>
                                                <td>${safeText(p.nik_pengikut)}</td>
                                                <td>${safeText(p.tipe_identitas)}</td>
                                                <td>${safeText(p.hubungan_pengunjung)}</td>
                                            </tr>
                                        `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
            }

            // ✅ TITIPAN BARANG (TEXT ONLY, TANPA FOTO)
            function renderTitipanBarangTextOnly(pengunjung) {
                const titipBarang = (pengunjung.titip_barang || '').toString().trim();
                const jenisBarang = (pengunjung.jenis_barang || '').toString().trim();

                if (!titipBarang && !jenisBarang) {
                    return `
                            <div class="alert alert-secondary mb-0">
                                Tidak ada titipan barang.
                            </div>
                        `;
                }

                return `
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted">Titip Barang</div>
                                <div class="fw-bold">${safeText(titipBarang)}</div>
                            </div>

                            <div class="col-md-6">
                                <div class="small text-muted">Jenis Barang</div>
                                <div class="fw-bold">${safeText(jenisBarang)}</div>
                            </div>
                        </div>
                    `;
            }

            $('#nik_pengunjung').on('input', function() {

                this.value = this.value.replace(/[^0-9]/g, '');
                const nik = $(this).val();

                if (nik.length === 16) {

                    $.ajax({
                        url: '{{ route('cek-antrian') }}',
                        type: 'GET',
                        data: {
                            nik: nik
                        },
                        success: function(res) {

                            if (!res.success) {
                                $('#modalErrorBody').text(res.message ||
                                'Data tidak ditemukan');
                                var modal = new bootstrap.Modal(document.getElementById(
                                    'modalError'));
                                modal.show();
                                $('#hasilAntrian').html('');
                                return;
                            }

                            const p = res.pengunjung;

                            // ==== DATA UTAMA ====
                            const tanggalKunjungan = formatTanggalIndo(p.tanggal_kunjungan);
                            const sesi = safeText(p.sesi_kunjungan);
                            const jamKunjungan = getJamKunjunganBySesi(p.sesi_kunjungan);

                            const statusBarcode = safeText(p.status_barcode);
                            const idBarcode = safeText(p.id_barcode);

                            // ==== DATA WBP (RELASI) ====
                            const wbp = p.wbp || {};
                            const noReg = safeText(wbp.no_reg_instansi || p.no_reg_instansi);
                            const namaWbp = safeText(wbp.nama || p.nama_wbp);
                            const blok = safeText(wbp.lokasi_blok);
                            const sel = safeText(wbp.lokasi_sel);

                            // ==== DATA PENGUNJUNG ====
                            const namaPengunjung = safeText(p.nama_pengunjung);
                            const nikPengunjung = safeText(p.nik_pengunjung);
                            const jenisIdentitas = safeText(p.jenis_identitas);
                            const hubungan = safeText(p.hubungan);
                            const noWa = safeText(p.no_wa);
                            const alamat = safeText(p.alamat);

                            // ==== DATA PENGIKUT ====
                            const pengikutArr = (p.pengikut && Array.isArray(p.pengikut)) ? p
                                .pengikut : [];

                            // ==== QR CODE (SATU-SATUNYA FOTO) ====
                            const qrImg = p.img_barcode ? `/storage/${p.img_barcode}` : null;

                            let html = `
                                    <div class="text-center mb-4">
                                        <h4 class="fw-bold mb-1">BUKTI PENDAFTARAN KUNJUNGAN</h4>
                                        <p class="text-muted mb-0">Data ditemukan</p>
                                    </div>

                                    <div class="row g-4">

                                        <!-- QR CODE -->
                                        <div class="col-md-4 text-center">
                                            <div class="p-3 border rounded">
                                                <h6 class="fw-bold mb-3">QR CODE</h6>

                                                ${qrImg ? `
                                                        <img src="${qrImg}" class="img-fluid" style="max-width:220px;" alt="QR Code">
                                                    ` : `
                                                        <p class="text-danger mb-0">QR belum tersedia</p>
                                                    `}

                                                <div class="mt-3">
                                                    <div class="small text-muted">ID Barcode</div>
                                                    <div class="fw-bold">${idBarcode}</div>
                                                </div>

                                                <div class="mt-3">
                                                    <div class="small text-muted">Status Barcode</div>
                                                    <div class="fw-bold">${badgeStatus(statusBarcode)}</div>
                                                </div>

                                                ${qrImg ? `
                                                        <div class="mt-3">
                                                            <button onclick="window.print()" class="btn btn-outline-primary btn-sm no-print">
                                                                🖨 Cetak
                                                            </button>
                                                        </div>
                                                    ` : ``}
                                            </div>
                                        </div>

                                        <!-- DETAIL -->
                                        <div class="col-md-8">
                                            <div class="p-3 border rounded">
                                                <h6 class="fw-bold mb-3">DETAIL KUNJUNGAN</h6>

                                                <div class="row g-3">

                                                    <div class="col-12">
                                                        <div class="fw-bold mb-2">Data WBP</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">No. Registerasi</div>
                                                        <div class="fw-bold">${noReg}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Nama WBP</div>
                                                        <div class="fw-bold">${namaWbp}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Blok</div>
                                                        <div class="fw-bold">${blok}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Kamar / Sel</div>
                                                        <div class="fw-bold">${sel}</div>
                                                    </div>

                                                    <hr class="my-2">

                                                    <div class="col-12">
                                                        <div class="fw-bold mb-2">Data Pengunjung</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Nama Pengunjung</div>
                                                        <div class="fw-bold">${namaPengunjung}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">No. Identitas</div>
                                                        <div class="fw-bold">${nikPengunjung}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Jenis Identitas</div>
                                                        <div class="fw-bold">${jenisIdentitas}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">No WhatsApp</div>
                                                        <div class="fw-bold">${noWa}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Hubungan</div>
                                                        <div class="fw-bold">${hubungan}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Alamat</div>
                                                        <div class="fw-bold">${alamat}</div>
                                                    </div>

                                                    <hr class="my-2">

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Tanggal Kunjungan</div>
                                                        <div class="fw-bold">${tanggalKunjungan}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Sesi Kunjungan</div>
                                                        <div class="fw-bold">${sesi}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Jam Kunjungan</div>
                                                        <div class="fw-bold">${jamKunjungan}</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="small text-muted">Catatan</div>
                                                        <span class="badge bg-success">
                                                            Harap tunjukkan QR ke petugas
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- DATA PENGIKUT -->
                                        <div class="col-12">
                                            <div class="p-3 border rounded">
                                                <h6 class="fw-bold mb-3">DATA PENGIKUT</h6>
                                                ${renderPengikutTable(pengikutArr)}
                                            </div>
                                        </div>

                                        <!-- TITIPAN BARANG (TEXT ONLY) -->
                                        <div class="col-12">
                                            <div class="p-3 border rounded">
                                                <h6 class="fw-bold mb-3">TITIPAN BARANG / BARANG BAWAAN</h6>
                                                ${renderTitipanBarangTextOnly(p)}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mt-3 text-end">
                                                <a href="/" class="btn btn-secondary no-print">
                                                    Kembali
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                `;

                            $('#hasilAntrian').html(html);
                        },
                        error: function() {
                            $('#modalErrorBody').text('Terjadi kesalahan saat mengambil data');
                            var modal = new bootstrap.Modal(document.getElementById(
                                'modalError'));
                            modal.show();
                            $('#hasilAntrian').html('');
                        }
                    });

                } else {
                    $('#hasilAntrian').html('');
                }

            });
        });
    </script>
@endsection
