document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form[action*='kritik-saran']");
    const namaInput = document.getElementById("nama_lengkap");
    const nikInput = document.getElementById("nik");
    const fileInput = document.getElementById("foto_ktp");
    const pesanInput = document.querySelector("textarea[name='pesan']");
    const jenisSelect = document.querySelector("select[name='jenis']");

    // Global dari blade
    const ocrRoute = window.KRITIK?.ocrRoute || null;
    const csrfToken = window.KRITIK?.csrfToken || null;

    /* ===================== HELPER ===================== */
    function onlyDigits(str) {
        return (str || "").toString().replace(/[^0-9]/g, "");
    }

    function onlyLettersSpace(str) {
        return (str || "")
            .toString()
            .replace(/[0-9]/g, "")           // hapus angka
            .replace(/[^A-Za-z\s\.\']/g, "") // hapus simbol aneh
            .toUpperCase();
    }

    function setLoadingOCR(isLoading) {
        if (!nikInput || !namaInput) return;

        if (isLoading) {
            nikInput.value = "Membaca NIK...";
            namaInput.value = "Membaca Nama...";
            nikInput.readOnly = true;
            namaInput.readOnly = true;
        } else {
            nikInput.readOnly = false;
            namaInput.readOnly = false;
        }
    }

    /* ===================== VALIDASI INPUT LIVE ===================== */

    // Nama: uppercase + huruf saja
    if (namaInput) {
        namaInput.addEventListener("input", function () {
            this.value = onlyLettersSpace(this.value);
        });
    }

    // NIK: angka saja + max 16 digit
    if (nikInput) {
        nikInput.addEventListener("input", function () {
            this.value = onlyDigits(this.value).slice(0, 16);
        });
    }

    // Pesan: trim spasi depan
    if (pesanInput) {
        pesanInput.addEventListener("input", function () {
            // jangan hapus isi pesan, cuma rapihin
            this.value = this.value.replace(/^\s+/, "");
        });
    }

    /* ===================== OCR FOTO KTP ===================== */
    if (fileInput) {
        fileInput.addEventListener("change", function () {
            if (!this.files || !this.files[0]) return;

            // Kalau OCR route belum ada, skip
            if (!ocrRoute || !csrfToken) return;

            setLoadingOCR(true);

            const formData = new FormData();
            formData.append("foto_ktp", this.files[0]);
            formData.append("_token", csrfToken);

            fetch(ocrRoute, {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    // kalau format return {nik, nama} atau {success, nik, nama}
                    const nik = data?.nik ? onlyDigits(data.nik).slice(0, 16) : "";
                    const nama = data?.nama ? onlyLettersSpace(data.nama) : "";

                    nikInput.value = nik;
                    namaInput.value = nama;

                    setLoadingOCR(false);
                })
                .catch(() => {
                    nikInput.value = "";
                    namaInput.value = "";
                    setLoadingOCR(false);
                });
        });
    }

    /* ===================== VALIDASI SUBMIT KETAT ===================== */
    if (form) {
        form.addEventListener("submit", function (e) {
            let errors = [];

            const nama = namaInput ? namaInput.value.trim() : "";
            const nik = nikInput ? onlyDigits(nikInput.value) : "";
            const jenis = jenisSelect ? jenisSelect.value : "";
            const pesan = pesanInput ? pesanInput.value.trim() : "";

            if (!nama) errors.push("Nama Lengkap wajib diisi.");
            if (!jenis) errors.push("Jenis Kritik/Saran wajib dipilih.");
            if (!nik || nik.length !== 16) errors.push("NIK wajib 16 digit angka.");
            if (!fileInput || !fileInput.value) errors.push("Foto KTP wajib diupload.");
            if (!pesan) errors.push("Pesan wajib diisi.");
            if (pesan.length > 0 && pesan.length < 10) errors.push("Pesan minimal 10 karakter.");
            if (pesan.length > 1000) errors.push("Pesan maksimal 1000 karakter.");

            // Anti spam sederhana: huruf sama berulang
            const pesanNoSpace = pesan.replace(/\s+/g, "");
            if (pesanNoSpace.length >= 15) {
                const allSame = /^(.)(\1)+$/.test(pesanNoSpace);
                if (allSame) errors.push("Pesan terdeteksi tidak valid (spam).");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert("❌ FORM BELUM VALID:\n\n" + errors.join("\n"));
            }
        });
    }

});
