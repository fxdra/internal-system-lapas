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

    <div class="container my-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <form method="get" id="formTanggal">
                    <div class="mb-3 row align-items-center">
                        <!-- Label -->
                        <label for="tanggalKunjungan"
                            class="col-12 col-md-4 col-form-label text-md-end mb-1 mb-md-0 text-dark">
                            Pilih Tanggal Kunjungan
                        </label>

                        <!-- Input -->
                        <div class="col-12 col-md-8">
                            <input type="date" name="tanggal" class="form-control" id="tanggalKunjungan" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-5 fw-semibold text-white" id="sesiContainer">
        <div class="row">

            <!-- SESI PAGI -->
            <div class="col-md-6 col-6">
                <div class="card shadow h-100 border-0 custom-card">
                    <div class="card-body text-center text-white">
                        <h5 class="card-title fw-bold text-white">
                            Pagi
                        </h5>

                        <h5 class="card-title fw-bold text-white">
                            {{-- Keterangan dari database --}}
                        </h5>

                        <p class="mb-1 text-white">
                            <strong>Jam</strong><br>
                            08.00 – 12.00 WIB
                        </p>

                        <hr>

                        <p class="mb-2 text-white">
                            <strong>Jumlah Pengunjung</strong>
                        </p>

                        <h2 class="fw-bold text-white">
                            15 / 30
                        </h2>

                        <span class="badge bg-success">
                            Kuota Tersedia
                        </span>

                        <hr>

                        <div class="button">
                            <button id="btnSesiPagi" class="btn btn-success text-white">
                                PILIH
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SESI SIANG -->
            <div class="col-md-6 col-6">
                <div class="card shadow h-100 border-0 custom-card">
                    <div class="card-body text-center text-white">
                        <h5 class="card-title fw-bold text-white">
                            Siang
                        </h5>

                        <h5 class="card-title fw-bold text-white">
                            {{-- Keterangan dari database --}}
                        </h5>

                        <p class="mb-1 text-white">
                            <strong>Jam</strong><br>
                            13.00 – 15.00 WIB
                        </p>

                        <hr>

                        <p class="mb-2 text-white">
                            <strong>Jumlah Pengunjung</strong>
                        </p>

                        <h2 class="fw-bold text-white">
                            30 / 30
                        </h2>

                        <span class="badge bg-danger">
                            Kuota Penuh
                        </span>

                        <hr>

                        <div class="button">
                            <button id="btnSesiSiang" class="btn btn-success text-white">
                                PILIH
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container my-3">
        <div class="row mt-4">
            <div class="col-12 ">
                <div class="alert alert-info border-0 shadow-sm rounded-4 text-center text-white">
                    <i data-feather="info" class="me-1"></i>
                    <strong>Catatan Kunjungan Tatap Muka</strong><br>
                    <span class="small">
                        Kunjungan tatap muka hanya dapat dilakukan sesuai
                        <strong>jadwal sesi</strong> dan <strong>kuota yang tersedia</strong>.
                        Harap datang tepat waktu dan membawa identitas yang berlaku.
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Sesi Berakhir --}}
    <!-- Modal Sesi Habis -->
    <div class="modal fade" id="sesiHabisModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sesi Berakhir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Maaf, sesi ini sudah berakhir.
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kuota Penuh -->
    <div class="modal fade" id="kuotaPenuhModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Pemberitahuan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    ❌ <strong>Kuota Tidak Tersedia.</strong><br>
                    Silakan pilih sesi atau hari lain.
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Hari Libur --}}

    <div class="modal fade" id="hariLiburModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Pemberitahuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    ⚠️ <strong>Hari ini adalah hari libur.</strong><br>
                    Silakan pilih tanggal lain.
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

 <script>
document.addEventListener("DOMContentLoaded", function() {
    const sesiContainer = document.getElementById("sesiContainer");
    const btnPagi = document.getElementById("btnSesiPagi");
    const btnSiang = document.getElementById("btnSesiSiang");

    const modalKuota = new bootstrap.Modal(document.getElementById("kuotaPenuhModal"));
    const modalLibur = new bootstrap.Modal(document.getElementById("hariLiburModal"));
    const modalSesiHabis = new bootstrap.Modal(document.getElementById("sesiHabisModal"));

    const tanggalInput = document.getElementById("tanggalKunjungan");
    if (!sesiContainer || !btnPagi || !btnSiang || !tanggalInput) return;

    // ===== DEFAULT =====
    const DEFAULT_TOTAL = 100;

    // ===== TANGGAL WIB =====
    const today = new Date().toLocaleDateString("en-CA", {
        timeZone: "Asia/Jakarta"
    });

    tanggalInput.min = today;
    tanggalInput.value = today;

    function getCurrentTime() {
        const now = new Date(new Date().toLocaleString("en-US", {
            timeZone: "Asia/Jakarta"
        }));
        return {
            hours: now.getHours(),
            minutes: now.getMinutes()
        };
    }

    function isToday(dateStr) {
        return dateStr === today;
    }

    const SESSIONS = {
        pagi: { start: 8, end: 12 },
        siang: { start: 13, end: 15 }
    };

    function isSesiBerakhir(sesi, tanggalDipilih) {
        if (!isToday(tanggalDipilih)) return false;
        const { hours, minutes } = getCurrentTime();
        const endHour = SESSIONS[sesi].end;
        return hours > endHour || (hours === endHour && minutes > 0);
    }

    // ======================================================
    // 🔥 FIX: SAFE API HANDLING (ANTI NULL / ERROR / HTML RESPONSE)
    // ======================================================
    async function getKuotaByTanggal(tanggal) {
        try {
            const res = await fetch(`/api/kuota?tanggal=${tanggal}`);

            if (!res.ok) return DEFAULT_TOTAL;

            const data = await res.json();

            if (!data || typeof data.kuota === "undefined" || data.kuota === null) {
                return DEFAULT_TOTAL;
            }

            const kuota = parseInt(data.kuota);

            if (isNaN(kuota) || kuota < 0) {
                return DEFAULT_TOTAL;
            }

            return kuota;

        } catch (e) {
            return DEFAULT_TOTAL;
        }
    }

    async function renderSesi(tanggal) {
        if (tanggal < today) {
            alert("Tanggal sudah lewat, silakan pilih tanggal lain.");
            tanggalInput.value = today;
            return renderSesi(today);
        }

        const day = new Date(tanggal).getDay();

        // HARI LIBUR
        if ([5, 6, 0].includes(day)) {
            return disableAll("Libur", modalLibur);
        }

        // 🔥 AMBIL KUOTA DARI DB PER TANGGAL
        const kuota = await getKuotaByTanggal(tanggal);
        const perSesi = kuota / 2;

        renderCard("pagi", 0, perSesi, btnPagi, "-", tanggal);
        renderCard("siang", 0, perSesi, btnSiang, "-", tanggal);

        sesiContainer.classList.remove("d-none");
    }

    function renderCard(sesi, terpakai, limit, button, keterangan, tanggalDipilih) {
        terpakai = Math.min(terpakai, limit);

        const card = button.closest(".card-body");
        const jumlah = card.querySelector("h2");
        const badge = card.querySelector(".badge");

        jumlah.innerText = `${terpakai} / ${limit}`;
        setKeteranganCard(button, keterangan);

        if (isSesiBerakhir(sesi, tanggalDipilih)) {
            jumlah.className = "fw-bold text-dark";
            badge.className = "badge bg-dark";
            badge.innerText = "Sesi Berakhir";
            button.className = "btn btn-dark mt-2";
            button.onclick = () => modalSesiHabis.show();

        } else if (terpakai >= limit) {
            jumlah.className = "fw-bold text-danger";
            badge.className = "badge bg-danger";
            badge.innerText = "Kuota Penuh";
            button.className = "btn btn-secondary mt-2";
            button.onclick = () => modalKuota.show();

        } else {
            jumlah.className = "fw-bold text-success";
            badge.className = "badge bg-success";
            badge.innerText = "Kuota Tersedia";
            button.className = "btn btn-success mt-2";
            button.disabled = false;
            button.onclick = () =>
                window.location.href = `/daftar-kunjungan?sesi=${sesi}&tanggal=${tanggalDipilih}`;
        }
    }

    function disableButton(button, label, modal) {
        const card = button.closest(".card-body");
        const badge = card.querySelector(".badge");

        badge.className = "badge bg-dark";
        badge.innerText = label;

        button.className = "btn btn-dark mt-2";
        button.disabled = true;
        button.onclick = () => modal.show();
    }

    function setKeteranganCard(button, keterangan) {
        const card = button.closest(".card-body");
        const el = card.querySelectorAll("h5")[1];
        if (el) el.innerText = (keterangan ?? "-").toUpperCase();
    }

    function setJumlahCard(button, terpakai, limit) {
        const card = button.closest(".card-body");
        const jumlah = card.querySelector("h2");
        jumlah.innerText = `${terpakai} / ${limit}`;
    }

    function disableAll(label, modal) {
        disableButton(btnPagi, label, modal);
        disableButton(btnSiang, label, modal);

        setJumlahCard(btnPagi, "-", "-");
        setJumlahCard(btnSiang, "-", "-");

        setKeteranganCard(btnPagi, "-");
        setKeteranganCard(btnSiang, "-");

        sesiContainer.classList.remove("d-none");
    }

    // ===== INIT =====
    renderSesi(today);

    tanggalInput.addEventListener("change", function() {
        renderSesi(this.value);
    });
});
</script>
@endsection
