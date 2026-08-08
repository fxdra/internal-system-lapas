document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form[action='/daftar-pengunjung']");
    const container = document.getElementById("pengikutContainer");
    const addBtn = document.getElementById("addPengikut");
    const removeBtn = document.getElementById("removePengikut");
    let jumlahPengikut = 0;
    const maxPengikut = 2;

    // Ambil route & csrf dari global Blade
    const ocrRoute = window.PENGIKUT.ocrRoute;
    const csrfToken = window.PENGIKUT.csrfToken;

    /* ==================== CREATE DYNAMIC PENGIKUT ==================== */
    function createPengikutForm(index) {
        const div = document.createElement("div");
        div.className = "border rounded p-3 mb-3 pengikut-item";
        div.innerHTML = `
            <h6 class="fw-bold mb-3">Data Pengikut ${index}</h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Identitas</label>
                    <select class="form-select" name="tipe_identitas_pengikut[]">
                        <option value="">-- Pilih --</option>
                        <option value="KTP">KTP</option>
                        <option value="SIM">SIM</option>
                        <option value="PASPOR">PASPOR</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                 <div class="col-md-4">
                    <label class="form-label">Foto KTP</label>
                    <input type="file" class="form-control foto-ktp-pengikut" name="foto_ktp_pengikut[]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Identitas</label>
                    <input type="tel" class="form-control nik-pengikut" name="nik_pengikut[]" minlength="14" maxlength="16">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control nama-pengikut" name="nama_pengikut[]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-select" name="jk_pengikut[]">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat_pengikut[]" rows="2"></textarea>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hubungan</label>
                    <input type="text" class="form-control" name="hubungan_pengikut[]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah Anak</label>
                    <input type="number" class="form-control" name="jumlah_anak_pengikut[]" min="0">
                </div>
            </div>
        `;

        const fileInput = div.querySelector('.foto-ktp-pengikut');
        const nikInput = div.querySelector('.nik-pengikut');
        const namaInput = div.querySelector('.nama-pengikut');

        // Batasi input NIK hanya angka
        nikInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // OCR KTP pengikut
        fileInput.addEventListener('change', function() {
            if (!this.files[0]) return;

            nikInput.value = 'Membaca NIK...';
            namaInput.value = 'Membaca Nama...';
            nikInput.readOnly = true;
            namaInput.readOnly = true;

            const formData = new FormData();
            formData.append('foto_ktp', this.files[0]);
            formData.append('_token', csrfToken);

            fetch(ocrRoute, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    nikInput.value = data.nik || '';
                    namaInput.value = data.nama ? data.nama.replace(/[0-9]/g,'').toUpperCase() : '';
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

        return div;
    }

    /* ==================== ADD / REMOVE PENGIKUT ==================== */
    addBtn.addEventListener("click", function () {
        if (jumlahPengikut >= maxPengikut) return;
        jumlahPengikut++;
        container.appendChild(createPengikutForm(jumlahPengikut));
        removeBtn.style.display = "inline-block";
        addBtn.disabled = jumlahPengikut >= maxPengikut;

        // Auto uppercase & no number untuk text input pengikut
        container.querySelectorAll('input[type="text"]').forEach(input => {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[0-9]/g, '').toUpperCase();
            });
        });
    });

    removeBtn.addEventListener("click", function () {
        if (jumlahPengikut === 0) return;
        container.removeChild(container.lastElementChild);
        jumlahPengikut--;
        removeBtn.style.display = jumlahPengikut > 0 ? "inline-block" : "none";
        addBtn.disabled = false;
    });

    /* ==================== VALIDASI FORM UTAMA ==================== */
    // Text inputs utama -> uppercase & no numbers
    form.querySelectorAll('input[type="text"]').forEach(input => {
        input.addEventListener("input", function () {
            this.value = this.value.replace(/[0-9]/g, '').toUpperCase();
        });
    });

    // Input NIK pengunjung -> hanya angka
    const nikPengunjung = form.querySelector('input[name="nik_pengunjung"]');
    if (nikPengunjung) {
        nikPengunjung.addEventListener("input", function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // No WA -> hanya angka
    form.querySelectorAll('input[name="no_wa"]').forEach(input => {
        input.addEventListener("input", function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    /* ==================== VALIDASI FORM SUBMIT ==================== */
    form.addEventListener("submit", function (e) {
        let valid = true;
        let messages = [];

        // Validasi input utama
        form.querySelectorAll("input[required], select[required], textarea[required]").forEach(el => {
            if (!el.value || el.value.trim() === "") {
                valid = false;
                messages.push(`${el.previousElementSibling.innerText} wajib diisi.`);
            }
        });

        // Validasi NIK 16 digit
        if (nikPengunjung && nikPengunjung.value.length !== 16) {
            valid = false;
            messages.push("No. Identitas harus 16 digit.");
        }

        // Validasi WA 11-15 digit
        const wa = form.querySelector('input[name="no_wa"]');
        if (wa && (wa.value.length < 11 || wa.value.length > 15)) {
            valid = false;
            messages.push("Nomor WhatsApp harus 11-15 digit.");
        }

        // Validasi pengikut
        const pengikutItems = document.querySelectorAll(".pengikut-item");
        pengikutItems.forEach((item, index) => {
            const tipe = item.querySelector('[name="tipe_identitas_pengikut[]"]');
            const nikP = item.querySelector('[name="nik_pengikut[]"]');
            const nama = item.querySelector('[name="nama_pengikut[]"]');
            const jk = item.querySelector('[name="jk_pengikut[]"]');
            const alamat = item.querySelector('[name="alamat_pengikut[]"]');
            const hubungan = item.querySelector('[name="hubungan_pengikut[]"]');
            const fotoKtp = item.querySelector('[name="foto_ktp_pengikut[]"]');

            const filled = [tipe.value, nikP.value, nama.value, jk.value, alamat.value, hubungan.value].some(v => v.trim() !== "");
            if (!filled) return;

            if (!tipe.value) setError(tipe, "Pilih identitas");
            if (!nikP.value || nikP.value.length < 14) setError(nikP, "No identitas tidak valid");
            if (!nama.value) setError(nama, "Nama wajib diisi");
            if (!jk.value) setError(jk, "Pilih jenis kelamin");
            if (!alamat.value) setError(alamat, "Alamat wajib diisi");
            if (!hubungan.value) setError(hubungan, "Hubungan wajib diisi");
            if (!fotoKtp.value) setError(fotoKtp, "Foto KTP wajib diupload");

            function setError(el, msg) {
                el.classList.add("is-invalid");
                el.nextElementSibling.innerText = msg;
                messages.push(`Pengikut ${index + 1}: ${msg}`);
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            alert("❌ FORM BELUM VALID:\n\n" + messages.join("\n"));
        }
    });
});
