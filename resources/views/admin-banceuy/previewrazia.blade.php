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

    background: #ffffff;

    padding:
        25mm
        20mm
        25mm
        20mm;

    box-shadow:
        0 0 10px rgba(0,0,0,0.15);

    font-family:
        "Times New Roman",
        serif;

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

.document-section-title {

    font-weight: bold;

    margin-bottom: 10px;
}

.document-table {

    width: 100%;

    border-collapse: collapse;

    margin-top: 15px;
}

.document-table th,
.document-table td {

    border: 1px solid #000;

    padding: 8px;

    vertical-align: top;
}

.signature-section {

    margin-top: 70px;

    width: 100%;
}

.signature-box {

    width: 300px;

    margin-left: auto;

    text-align: center;
}

.signature-space {
    height: 90px;
}

.no-print {

    width: 210mm;

    margin:
        0 auto
        15px auto;

    display: flex;

    justify-content: space-between;

    align-items: center;
}

.btn-mini {

    display: inline-block;

    padding:
        6px
        14px;

    font-size: 12px;

    border-radius: 4px;

    text-decoration: none;

    border: none;

    cursor: pointer;
}

.btn-back {

    background: #6c757d;

    color: #fff;
}

.btn-download {

    background: #198754;

    color: #fff;
}

.download-wrapper {

    text-align: center;

    margin-top: 20px;
}

.document-photo {

    width: 100%;

    margin-top: 15px;

    border: 1px solid #ccc;
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
    .download-wrapper {

        display: none;
    }
}

</style>

<div class="document-wrapper">

<div class="no-print">

    <a
        href="{{ route('razia.index') }}"
        class="btn-mini btn-back">

        Kembali

    </a>

</div>

<div class="document-preview">

    <div class="document-header">

        <div class="document-title">
            LAPORAN HASIL RAZIA
        </div>

        <div class="document-subtitle">
            LEMBAGA PEMASYARAKATAN
        </div>

    </div>

    <div class="document-section">

        Pada hari

        <strong>
            {{ $data['hari_razia'] }}
        </strong>

        pukul

        <strong>
            {{ substr($data['jam_mulai'],0,5) }}
        </strong>

        sampai dengan

        <strong>
            {{ substr($data['jam_selesai'],0,5) }}
        </strong>

        telah dilaksanakan kegiatan razia
        yang dipimpin oleh

        <strong>
            {{ $data['pimpinan_razia'] }}
        </strong>

        di

        <strong>
            {{ $data['lokasi'] }}
        </strong>.

    </div>

    <div class="document-section">

        <div class="document-section-title">
            Ringkasan Kegiatan
        </div>

        Jumlah kamar yang dirazia :

        <strong>
            {{ $data['jumlah_kamar'] }}
        </strong>

        kamar.

        <br><br>

        Jumlah barang temuan :

        <strong>
            {{ $data['jumlah_barang_temuan'] }}
        </strong>

        jenis barang.

        <br><br>

        Total barang temuan :

        <strong>
            {{ $data['jumlah_total_temuan'] }}
        </strong>

        item.

    </div>

    <div class="document-section">

        <div class="document-section-title">
            Kamar Yang Dirazia
        </div>

        <ol>

            @foreach($data['daftar_kamar'] as $kamar)

                <li>
                    {{ $kamar }}
                </li>

            @endforeach

        </ol>

    </div>

    <div class="document-section">

        <div class="document-section-title">
            Barang Temuan
        </div>

        <table class="document-table">

            <thead>

                <tr>

                    <th width="10%">
                        No
                    </th>

                    <th>
                        Nama Barang
                    </th>

                    <th width="20%">
                        Jumlah
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($data['barang_temuan'] as $index => $item)

                    <tr>

                        <td align="center">
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $item['nama_barang'] }}
                        </td>

                        <td align="center">
                            {{ $item['jumlah'] }}
                            {{ $item['satuan'] }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" align="center">
                            Tidak ada barang temuan
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="document-section">

        <div class="document-section-title">
            Personel Pengamanan
        </div>

        Staff KPLP :
        {{ $data['staff_kplp'] }}

        <br>

        {{ $data['petugas_piket_jabatan'] }} :
        {{ $data['petugas_piket_jumlah'] }}

        <br>

        Regu Pengamanan :
        {{ $data['regu_pengamanan'] }}

    </div>

    @if(count($data['foto']))

        <div class="document-section">

            <div class="document-section-title">
                Dokumentasi Kegiatan
            </div>

            @foreach($data['foto'] as $item)

                <img
                    src="{{ asset('storage/razia/' . $item) }}"
                    class="document-photo">

            @endforeach

        </div>

    @endif

    <div class="signature-section">

        <div class="signature-box">

            Mengetahui,

            <br>

            Kepala Pengamanan Lapas

            <div class="signature-space"></div>

            <strong>

                {{ $data['nama_kplp'] }}

            </strong>

        </div>

    </div>

</div>

        <div class="download-wrapper no-print">

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
            "{{ route('razia.download', $data['id']) }}";
    
        setTimeout(function () {
            window.location.href =
                "{{ route('razia.index') }}";
        }, 1000);
    }
    
    </script>

@endsection
