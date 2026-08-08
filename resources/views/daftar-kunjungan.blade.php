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

                <form action="/daftar-pengunjung" method="POST" enctype="multipart/form-data" autocomplete="off">
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
                    <input type="hidden" name="sesi_kunjungan" value="{{ strtoupper(request('sesi')) }}">


                    <!-- ================= DATA PENGUNJUNG ================= -->
                    <h4 class="fw-bold mb-2">DATA PENGUNJUNG</h4>
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

                        <div class="col-md-4">
                            <label class="form-label">Jumlah Anak *</label>
                            <input type="number" class="form-control" name="jumlah_anak_pengunjung" min="0"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kunjungan</label>

                            <input type="date" class="form-control" id="tanggal_view"
                                value="{{ request()->query('tanggal') }}" disabled>

                            <input type="hidden" name="tanggal_kunjungan" id="tanggal_kunjungan"
                                value="{{ request()->query('tanggal') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Titip Barang?</label>
                            <select class="form-select" name="titip_barang" id="titip_barang" required>
                                <option value="tidak">Tidak</option>
                                <option value="ya">Ya</option>
                            </select>
                        </div>

                    </div>

                    <!-- FORM BARANG -->
                    <div id="form_barang" class="row g-3 mt-3" style="display:none;">
                        <div class="col-md-6">
                            <label class="form-label">Foto Barang *</label>
                            <input type="file" class="form-control" name="foto_barang" accept="image/jpeg,image/png">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Barang *</label>
                            <input type="text" class="form-control" name="jenis_barang">
                        </div>
                    </div>


                    <!-- ================= DATA PENGIKUT ================= -->
                    <hr class="my-4">
                    <h4 class="fw-bold">DATA PENGIKUT</h4>
                    <p class="text-muted">
                        Maksimal 2 orang. Klik (+) untuk menambah pengikut.
                    </p>

                    <div id="pengikutContainer"></div>

                    <div class="d-flex gap-2 mb-4">
                        <button type="button" class="btn btn-danger btn-sm" id="removePengikut" style="display:none">
                            − Hapus
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="addPengikut">
                            + Tambah
                        </button>
                    </div>


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
                                <a href="/tatap-muka" class="btn btn-danger w-100 py-2 fw-bold">
                                    KEMBALI
                                </a>
                            </div>
                        </div>
                    </div>


                </form>
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


    {{-- Titip Barang --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectTitip = document.getElementById('titip_barang');
            const formBarang = document.getElementById('form_barang');
            const inputsBarang = formBarang.querySelectorAll('input');

            function toggleFormBarang() {
                if (selectTitip.value === 'ya') {
                    formBarang.style.display = 'flex';

                    inputsBarang.forEach(input => {
                        input.required = true;
                        input.disabled = false; // 🔥 WAJIB
                    });

                } else {
                    formBarang.style.display = 'none';

                    inputsBarang.forEach(input => {
                        input.required = false;
                        input.disabled = true; // 🔥 INI KUNCI
                        input.value = '';
                    });
                }
            }

            selectTitip.addEventListener('change', toggleFormBarang);
            toggleFormBarang();
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

    {{-- Ocr Validasi Pengikut --}}
    <script>
        window.PENGIKUT = {
            ocrRoute: "{{ route('ocr.ktp') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    <script src="../../js/pengikut.js"></script>
    <script src="../../js/tanggal_terkini.js"></script>

@endsection
