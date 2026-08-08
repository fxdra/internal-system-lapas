@extends('admin-banceuy.partisi.main')

@section('content')

<style>
body {
    background: #e5e5e5;
}

.document-wrapper {
    padding: 30px 0;
}

.document-preview {
    width: 210mm;
    min-height: 297mm;
    margin: auto;
    background: #fff;
    padding: 25mm 20mm;
    box-shadow: 0 0 10px rgba(0,0,0,0.15);
    font-family: "Times New Roman", serif;
    font-size: 12pt;
    line-height: 1.8;
    color: #000;
}

.document-header {
    margin-bottom: 35px;
}

.document-title {
    text-align: center;
    font-size: 16pt;
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 5px;
}

.document-subtitle {
    text-align: center;
    margin-bottom: 30px;
}

.document-section {
    margin-bottom: 25px;
    text-align: justify;
}

.document-photo {
    width: 100%;
    margin-top: 15px;
    border: 1px solid #ccc;
}

.signature-section {
    margin-top: 70px;
    width: 100%;
}

.no-print {
    width: 210mm;
    margin: 0 auto 15px auto;
}

.preview-btn {
    display: inline-block !important;
    width: auto !important;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    color: #fff;
}

.btn-back {
    background: #6c757d;
}

.btn-download {
    background: #198754;
}

.download-bottom {
    width: 210mm;
    margin: 15px auto 0 auto;
    text-align: left;
}

@media print {
    body {
        background: #fff;
    }

    .document-preview {
        box-shadow: none;
        margin: 0;
        width: 100%;
    }

    .no-print,
    .download-bottom {
        display: none;
    }
}
</style>

<div class="document-wrapper">

    <div class="no-print">
        <a
            href="{{ route('penerimaanwbp.edit', $data['kegiatan']->id) }}"
            class="preview-btn btn-back">
            Kembali
        </a>
    </div>

    <div class="document-preview">

        <div class="document-header">
            <div class="document-title">
                LAPORAN PENERIMAAN WBP
            </div>

            <div class="document-subtitle">
                LEMBAGA PEMASYARAKATAN KELAS IIA BANCEUY BANDUNG
            </div>
        </div>

        <div class="document-section">
            Pada hari
            <strong>{{ $narrative['hari_tanggal'] }}</strong>
            pukul
            <strong>{{ $narrative['jam_mulai'] }}</strong>
            WIB sampai dengan selesai telah dilaksanakan kegiatan penerimaan WBP di
            <strong>{{ $narrative['lokasi'] }}</strong>.
        </div>

        <div class="document-section">
            Kegiatan penerimaan WBP ini berdasarkan Surat Direktorat Jenderal Pemasyarakatan Nomor
            <strong>{{ $narrative['nomor_surat_dirjenpas'] }}</strong>
            tanggal
            <strong>{{ $narrative['tanggal_surat_dirjenpas'] }}</strong>.
        </div>

        <div class="document-section">
            WBP yang diterima atas nama
            <strong>{{ $narrative['nama_wbp'] }}</strong>
            dkk sebanyak
            <strong>{{ $narrative['jumlah_wbp'] }}</strong>
            orang yang berasal dari
            <strong>{{ $narrative['asal_upt'] }}</strong>.
        </div>

        <div class="document-section">
            <div style="font-weight:bold; text-align:left; margin-bottom:10px;">
                Dokumentasi Kegiatan
            </div>

            @if(!empty($narrative['foto']))
                <img
                    src="{{ asset('storage/penerimaan-wbp/' . $narrative['foto']) }}"
                    class="document-photo">
            @else
                <p>Tidak ada dokumentasi.</p>
            @endif
        </div>

        <div class="signature-section">

            <div style="display:flex; justify-content:space-between; text-align:center;">
                <div style="width:40%;">
                    <strong>Ka. KPLP</strong>
                    <div style="height:50px;"></div>
                    <strong>{{ $narrative['nama_kplp'] }}</strong>
                </div>

                <div style="width:40%;">
                    <strong>Kasi. Kamtib</strong>
                    <div style="height:50px;"></div>
                    <strong>{{ $narrative['nama_kasi_kamtib'] }}</strong>
                </div>
            </div>

            <div style="margin-top:60px; text-align:center;">
                Mengetahui,
                <br>
                <strong>KALAPAS</strong>
                <div style="height:50px;"></div>
                <strong>{{ $narrative['nama_kalapas'] }}</strong>
            </div>

        </div>

    </div>

        <div class="download-bottom no-print">
    
        <button
            type="button"
            onclick="downloadAndRedirect()"
            class="btn btn-primary">
    
            Download DOCX
    
        </button>
    
    </div>

</div>

<script>
function downloadAndRedirect() {

    window.location.href =
        "{{ route('penerimaanwbp.download', $data['kegiatan']->id) }}";

    setTimeout(function () {
        window.location.href =
            "{{ route('penerimaanwbp.index') }}";
    }, 1000);
}
</script>

@endsection