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

                <form action="/daftar-titip-barang" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <input type="hidden" name="sesi_kunjungan" id="sesi_kunjungan" value="">


                    <!-- ================= DATA PENGUNJUNG ================= -->
                    <h4 class="fw-bold mb-2">DATA TITIPAN BARANG</h4>
                    <p class="text-muted">
                        Untuk Pelayanan Tatap Muka, Mohon Isi Formulir Dengan Benar.
                    </p>
                    <p class="text-warning">
                        * Pengunjung diwajibkan membawa Identitas Asli saat berkunjung !
                    </p>
                    <hr>

                    <div class="row g-3">


                        <div class="col-md-4">
                            <label class="form-label">Pilih Identitas *</label>
                            <select class="form-select" name="jenis_identitas" required onchange="updateLength()">
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="PASPOR">PASPOR</option>
                                <option value="BUKU NIKAH">BUKU NIKAH</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Foto KTP / SIM / PASPOR / BUKU NIKAH *</label>
                            <input type="file" class="form-control" name="foto_ktp" accept="image/jpeg,image/png"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Foto Selfie Depan*</label>
                            <input type="file" class="form-control" name="foto_selfie" accept="image/jpeg,image/png"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. Identitas *</label>
                            <input type="tel" id="nik_pengunjung" class="form-control" name="nik_pengunjung"
                                placeholder="Masukan No. Identitas..." minlength="16" maxlength="16"
                                onkeypress="validate(event)" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" name="nama_pengunjung"
                                placeholder="Masukan Nama Lengkap..." required>
                        </div>


                        <div class="col-md-6 position-relative">
                            <label class="form-label">Nama Warga Binaan *</label>
                            <input type="text" class="form-control" id="nama_wbp" name="nama_wbp"
                                placeholder="Nama WBP BIN ...." required autocomplete="off">
                            <input type="hidden" id="no_reg_instansi" name="no_reg_instansi">
                            <div id="suggestions" class="list-group position-absolute" style="z-index: 1000;">
                            </div>
                        </div>



                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin *</label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap (Sesuai KTP) *</label>
                            <textarea class="form-control" name="alamat_pengunjung" rows="3" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor WhatsApp *</label>
                            <input type="tel" class="form-control" name="no_wa" minlength="11" maxlength="15"
                                onkeypress="validate(event)" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hubungan *</label>
                            <input type="text" class="form-control" name="hubungan_pengunjung"
                                placeholder="Contoh: Keluarga..." required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kunjungan</label>
                            <input type="date" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Titip Barang?</label>
                            <select class="form-select" name="titip_barang" id="titip_barang" required>
                                <option value="tidak">Tidak</option>
                                <option value="ya" selected>Ya</option> <!-- 🔑 set default ke "ya" -->
                            </select>
                        </div>

                    </div>

                    <!-- FORM BARANG (REPEATER) -->
                    <div id="form_barang" class="mt-4" style="display:none;">

                        <div class="row align-items-center g-2 mb-3">
                            <div class="col-12 col-md">
                                <h5 class="fw-bold mb-0">List Barang yang dititipkan</h5>
                                <small class="text-muted">Klik tambah untuk memasukkan lebih dari 1 barang</small>
                            </div>

                            <div class="col-12 col-md-auto">
                                <button type="button" class="btn btn-primary w-100 w-md-auto px-3 py-2 fw-bold"
                                    id="btnTambahBarang">
                                    + Tambah Barang
                                </button>
                            </div>
                        </div>

                        <div id="barangContainer" class="d-flex flex-column gap-3"></div>
                    </div>

                    <!-- ================= DATA PENGIKUT ================= -->
                    <!--<hr class="my-4">-->
                    <!--<h4 class="fw-bold">DATA PENGIKUT</h4>-->
                    <!--<p class="text-muted">-->
                    <!--    Maksimal 2 orang. Klik (+) untuk menambah pengikut.-->
                    <!--</p>-->

                    <!--<div id="pengikutContainer"></div>-->

                    <!--<div class="d-flex gap-2 mb-4">-->
                    <!--    <button type="button" class="btn btn-danger btn-sm" id="removePengikut"-->
                    <!--        style="display:none">-->
                    <!--        − Hapus-->
                    <!--    </button>-->
                    <!--    <button type="button" class="btn btn-primary btn-sm" id="addPengikut">-->
                    <!--        + Tambah-->
                    <!--    </button>-->
                    <!--</div>-->


                    <hr>

                    {{-- <!-- PERSETUJUAN -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" required>
                            <label class="form-check-label">
                                Saya menyetujui <a href="#" id="openModalLink">Syarat & Ketentuan</a>
                            </label>
                        </div> --}}

                    <!-- BUTTON -->
                    <div class="text-center">
                        <p class="text-warning">
                            Sebelum simpan, pastikan data sudah benar.
                        </p>

                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                    SIMPAN
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="/" class="btn btn-danger w-100 py-2 fw-bold">
                                    KEMBALI
                                </a>
                            </div>
                        </div>
                    </div>


                </form>

            </div>
        </div>
    </div>

    <div class="container my-3">
        <div class="row mt-2">
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm rounded-4 text-center text-white">
                    <i data-feather="package" class="me-1"></i>
                    <strong>Catatan Titipan Barang</strong><br>
                    <span class="small">
                        Titipan barang hanya diperbolehkan sesuai
                        <strong>ketentuan yang berlaku</strong>.
                        Barang akan diperiksa oleh petugas dan
                        <strong>dilarang membawa barang terlarang</strong>.
                        Pastikan data titipan diisi dengan benar.
                    </span>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validasi Laravel gagal
            @if ($errors->any())
                let errors = '';
                @foreach ($errors->all() as $error)
                    errors += "• {{ $error }}\n";
                @endforeach
                alert("Terjadi Kesalahan Validasi:\n" + errors);
            @endif

            // Error dari session (misal foreign key, query, dll)
            @if (session('error'))
                alert("Terjadi Kesalahan:\n{{ session('error') }}");
            @endif

            // Sukses
            @if (session('success'))
                alert("Sukses:\n{{ session('success') }}");
            @endif
        });
    </script>

    <script>
        function validate(evt) {
            const charCode = evt.which ? evt.which : evt.keyCode;

            // Allow: backspace, delete, tab, escape, enter
            if (
                charCode === 8 || // backspace
                charCode === 9 || // tab
                charCode === 13 || // enter
                charCode === 27 || // escape
                charCode === 46 // delete
            ) {
                return;
            }

            // Allow only numbers (0–9)
            if (charCode < 48 || charCode > 57) {
                evt.preventDefault();
            }
        }
    </script>

    <!--Titip Barang-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const selectTitip = document.getElementById("titip_barang");
            const formBarang = document.getElementById("form_barang");
            const barangContainer = document.getElementById("barangContainer");
            const btnTambahBarang = document.getElementById("btnTambahBarang");

            let indexBarang = 0;

            function templateBarang(idx) {
                return `
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold">Barang #${idx + 1}</div>
                            <button type="button" class="btn btn-danger btn-sm btnHapusBarang">
                                Hapus
                            </button>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Barang Dari *</label>
                                <select class="form-select barang-input" name="barang[${idx}][barang_dari]" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="keluarga">Keluarga</option>
                                    <option value="passmart">Passmart</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Foto Barang *</label>
                                <input type="file" class="form-control barang-input" name="barang[${idx}][foto_barang]"
                                    accept="image/jpeg,image/png" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jumlah Barang *</label>
                                <input type="number" class="form-control barang-input" name="barang[${idx}][jumlah_barang]"
                                    min="1" value="1" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Jenis Barang *</label>
                                <input type="text" class="form-control barang-input" name="barang[${idx}][jenis_barang]"
                                    placeholder="Contoh: Makanan, Pakaian, Obat, dll" required>
                            </div>

                        </div>

                    </div>
                </div>
                `;
            }

            function tambahBarang() {
                barangContainer.insertAdjacentHTML("beforeend", templateBarang(indexBarang));
                indexBarang++;
                updateNomorBarang();
            }

            function updateNomorBarang() {
                const cards = barangContainer.querySelectorAll(".card");
                cards.forEach((card, i) => {
                    const title = card.querySelector(".fw-bold");
                    if (title) title.textContent = `Barang #${i + 1}`;
                });
            }

            function setBarangFormAktif(aktif) {
                formBarang.style.display = aktif ? "block" : "none";

                if (!aktif) {
                    barangContainer.innerHTML = "";
                    indexBarang = 0;
                } else {
                    // kalau aktif dan belum ada barang, buat 1 default
                    if (barangContainer.children.length === 0) {
                        tambahBarang();
                    }
                }
            }

            // Toggle ketika Titip Barang berubah
            selectTitip.addEventListener("change", function() {
                const aktif = selectTitip.value === "ya";
                setBarangFormAktif(aktif);
            });

            // tombol tambah barang
            btnTambahBarang.addEventListener("click", function() {
                tambahBarang();
            });

            // tombol hapus barang (event delegation)
            barangContainer.addEventListener("click", function(e) {
                if (e.target.classList.contains("btnHapusBarang")) {
                    const card = e.target.closest(".card");
                    if (card) card.remove();

                    updateNomorBarang();

                    // kalau kosong semua, auto tambah 1 lagi biar tidak kosong
                    if (barangContainer.children.length === 0) {
                        tambahBarang();
                    }
                }
            });

            // jalankan saat pertama load
            setBarangFormAktif(selectTitip.value === "ya");
        });
    </script>


    {{-- search wbp --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('nama_wbp');
            const hiddenInput = document.getElementById('no_reg_instansi');
            const suggestions = document.getElementById('suggestions');

            let selectedItem = null; // Track item yang dipilih

            // Fungsi tampilkan suggestion
            function showSuggestions(data) {
                suggestions.innerHTML = '';
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.classList.add('list-group-item', 'list-group-item-action');
                    div.textContent = item.nama;
                    div.addEventListener('click', () => {
                        input.value = item.nama; // set input nama
                        hiddenInput.value = item.no_reg_instansi; // set hidden field
                        selectedItem = item;
                        suggestions.innerHTML = '';
                    });
                    suggestions.appendChild(div);
                });
            }

            // Event input untuk autocomplete
            input.addEventListener('input', function() {
                const query = this.value.trim();
                hiddenInput.value = ''; // reset hidden input saat ketik
                selectedItem = null;

                if (query.length < 1) {
                    suggestions.innerHTML = '';
                    return;
                }

                fetch(`/search-wbp?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            showSuggestions(data);
                        } else {
                            suggestions.innerHTML = ''; // tidak ada hasil
                        }
                    })
                    .catch(err => console.error('Error fetch WBP:', err));
            });

            // Klik di luar untuk hilangkan suggestion
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target)) {
                    suggestions.innerHTML = '';
                }
            });

            // Validasi sebelum submit form
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!hiddenInput.value) {
                        e.preventDefault();
                        alert('Silakan pilih nama WBP dari daftar suggestion agar foreign key valid.');
                        input.focus();
                    }
                });
            }
        });
    </script>


    {{-- Validasi Input Pengunjung Utama --}}

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form[action='/daftar-pengunjung']");

            // =========================
            // 1. TEXT INPUTS VALIDATION
            // =========================
            form.querySelectorAll('input[type="text"]').forEach(input => {
                input.addEventListener("input", function() {
                    // Hapus semua angka
                    this.value = this.value.replace(/[0-9]/g, '');
                    // UPPERCASE otomatis
                    this.value = this.value.toUpperCase();
                });
            });

            // =========================
            // 2. TEL / NO WA VALIDATION
            // =========================
            form.querySelectorAll('input[name="no_wa"]').forEach(input => {
                input.addEventListener("input", function() {
                    // Hanya angka
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            // =========================
            // 3. SUBMIT VALIDATION
            // =========================
            form.addEventListener("submit", function(e) {
                let valid = true;
                let messages = [];

                // Cek semua input required
                form.querySelectorAll("input, select, textarea").forEach(el => {
                    if (el.hasAttribute("required")) {
                        if (!el.value || el.value.trim() === "") {
                            valid = false;
                            messages.push(`${el.previousElementSibling.innerText} wajib diisi.`);
                        }
                    }
                });

                // Cek NIK 16 digit
                const nik = form.querySelector('input[name="nik_pengunjung"]');
                if (nik && nik.value.length !== 16) {
                    valid = false;
                    messages.push("No. Identitas harus 16 digit.");
                }

                // Cek nomor WA minimal 11 digit
                const wa = form.querySelector('input[name="no_wa"]');
                if (wa && (wa.value.length < 11 || wa.value.length > 15)) {
                    valid = false;
                    messages.push("Nomor WhatsApp harus 11-15 digit.");
                }

                if (!valid) {
                    e.preventDefault();
                    alert(messages.join("\n"));
                }
            });
        });
    </script>

    {{-- OCR Validasi --}}
    <script>
        document.querySelector('input[name="foto_ktp"]').addEventListener('change', function() {

            const nikInput = document.getElementById('nik_pengunjung');
            const namaInput = document.querySelector('input[name="nama_pengunjung"]');

            nikInput.value = 'Membaca NIK...';
            namaInput.value = 'Membaca Nama...';

            nikInput.readOnly = true;
            namaInput.readOnly = true;

            const formData = new FormData();
            formData.append('foto_ktp', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('ocr.ktp') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {

                    if (data.nik) {
                        nikInput.value = data.nik;
                    } else {
                        nikInput.value = '';
                    }

                    if (data.nama) {
                        namaInput.value = data.nama;
                    } else {
                        namaInput.value = '';
                    }

                    nikInput.readOnly = false;
                    namaInput.readOnly = false;
                })
                .catch(() => {
                    nikInput.value = '';
                    namaInput.value = '';
                    nikInput.readOnly = false;
                    namaInput.readOnly = false;
                });
        });
    </script>


    <!--Validasi Input-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // =============================
            // Helper: sanitasi input TEXT (tanpa angka)
            // =============================
            function sanitizeOnlyText(value) {
                // Izinkan huruf + spasi + titik + koma + strip + apostrophe
                // (hapus angka & simbol lain)
                return value
                    .replace(/[0-9]/g, "")
                    .replace(/[^A-Z\s.,'-]/g, "")
                    .replace(/\s{2,}/g, " ")
                    .trimStart();
            }

            // =============================
            // Helper: sanitasi input angka (tel/number)
            // =============================
            function sanitizeOnlyNumber(value) {
                return value.replace(/[^0-9]/g, "");
            }

            // =============================
            // AUTO KAPITAL untuk text & textarea tertentu
            // =============================
            function toUpperInput(el) {
                el.value = el.value.toUpperCase();
            }

            // =============================
            // VALIDASI REALTIME
            // =============================

            // 1) Semua input TEXT -> auto uppercase + tidak boleh angka
            document.querySelectorAll('input[type="text"]').forEach((el) => {
                el.addEventListener("input", function() {
                    toUpperInput(el);
                    el.value = sanitizeOnlyText(el.value.toUpperCase());
                });

                // block angka saat keypress
                el.addEventListener("keypress", function(e) {
                    const char = String.fromCharCode(e.which);
                    if (/[0-9]/.test(char)) e.preventDefault();
                });
            });

            // 2) TEXTAREA -> auto uppercase + tidak boleh angka (untuk alamat)
            document.querySelectorAll("textarea").forEach((el) => {
                el.addEventListener("input", function() {
                    el.value = el.value.toUpperCase();

                    // khusus alamat: boleh angka? kalau kamu mau alamat boleh angka, comment baris bawah
                    // el.value = sanitizeOnlyText(el.value);

                    // kalau alamat mau tetap boleh angka, pakai ini:
                    el.value = el.value.replace(/\s{2,}/g, " ").trimStart();
                });
            });

            // 3) Input TEL -> hanya angka
            document.querySelectorAll('input[type="tel"]').forEach((el) => {
                el.addEventListener("input", function() {
                    el.value = sanitizeOnlyNumber(el.value);
                });

                el.addEventListener("keypress", function(e) {
                    const char = String.fromCharCode(e.which);
                    if (!/[0-9]/.test(char)) e.preventDefault();
                });
            });

            // 4) Input NUMBER -> hanya angka
            document.querySelectorAll('input[type="number"]').forEach((el) => {
                el.addEventListener("input", function() {
                    el.value = sanitizeOnlyNumber(el.value);
                });

                el.addEventListener("keypress", function(e) {
                    const char = String.fromCharCode(e.which);
                    if (!/[0-9]/.test(char)) e.preventDefault();
                });
            });

            // =============================
            // VALIDASI KETAT SAAT SUBMIT
            // =============================
            const form = document.querySelector('form[action="/daftar-titip-barang"]');

            form.addEventListener("submit", function(e) {
                let valid = true;
                let msg = "";

                // Ambil semua field penting
                const nik = document.querySelector("#nik_pengunjung");
                const nama = form.querySelector('input[name="nama_pengunjung"]');
                const namaWbp = document.querySelector("#nama_wbp");
                const noWa = form.querySelector('input[name="no_wa"]');
                const hubungan = form.querySelector('input[name="hubungan_pengunjung"]');
                const jumlahAnak = form.querySelector('input[name="jumlah_anak_pengunjung"]');
                const tanggal = form.querySelector('input[name="tanggal_kunjungan"]');
                const alamat = form.querySelector('textarea[name="alamat_pengunjung"]');

                // rapihin uppercase final
                [nama, namaWbp, hubungan].forEach((el) => {
                    if (el && el.value) {
                        el.value = sanitizeOnlyText(el.value.toUpperCase());
                    }
                });

                if (alamat && alamat.value) {
                    alamat.value = alamat.value.toUpperCase().replace(/\s{2,}/g, " ").trim();
                }

                // Validasi NIK 16 digit
                if (!nik.value || nik.value.length !== 16) {
                    valid = false;
                    msg = "NIK wajib 16 digit angka.";
                    nik.focus();
                }

                // Validasi No WA minimal 11 max 15
                if (valid && (!noWa.value || noWa.value.length < 11 || noWa.value.length > 15)) {
                    valid = false;
                    msg = "Nomor WhatsApp wajib 11 sampai 15 digit angka.";
                    noWa.focus();
                }

                // Nama Pengunjung minimal 3 karakter
                if (valid && (!nama.value || nama.value.length < 3)) {
                    valid = false;
                    msg = "Nama pengunjung minimal 3 huruf.";
                    nama.focus();
                }

                // Nama WBP minimal 3 karakter
                if (valid && (!namaWbp.value || namaWbp.value.length < 3)) {
                    valid = false;
                    msg = "Nama WBP minimal 3 huruf.";
                    namaWbp.focus();
                }

                // Hubungan minimal 3 karakter
                if (valid && (!hubungan.value || hubungan.value.length < 3)) {
                    valid = false;
                    msg = "Hubungan minimal 3 huruf.";
                    hubungan.focus();
                }

                // Jumlah anak >= 0
                if (valid && (jumlahAnak.value === "" || parseInt(jumlahAnak.value) < 0)) {
                    valid = false;
                    msg = "Jumlah anak tidak boleh minus.";
                    jumlahAnak.focus();
                }

                // Tanggal wajib diisi
                if (valid && (!tanggal.value)) {
                    valid = false;
                    msg = "Tanggal kunjungan wajib diisi.";
                    tanggal.focus();
                }

                // Alamat minimal 5 karakter
                if (valid && (!alamat.value || alamat.value.length < 5)) {
                    valid = false;
                    msg = "Alamat wajib diisi minimal 5 karakter.";
                    alamat.focus();
                }

                // Jika tidak valid, stop submit
                if (!valid) {
                    e.preventDefault();

                    // pakai alert biasa
                    alert(msg);

                    // kalau kamu mau pakai SweetAlert, tinggal ganti ke Swal.fire(...)
                }
            });

        });
    </script>

    <script src="../../js/tanggal_terkini.js"></script>

@endsection
