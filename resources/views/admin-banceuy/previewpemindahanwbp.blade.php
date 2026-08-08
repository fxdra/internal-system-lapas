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
    box-shadow: 0 0 10px rgba(0,0,0,.15);
    font-family: "Times New Roman", serif;
    font-size: 12pt;
    line-height: 1.8;
}

.no-print {
    width: 210mm;
    margin: 0 auto 15px auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.download-bottom {
    width: 210mm;
    margin: 15px auto 0 auto;
    text-align: left;
}

.document-photo {
    width: 100%;
    margin-top: 10px;
}
</style>

<div class="document-wrapper">

    <div class="no-print">
        <a href="{{ route('pemindahanwbp.edit', $data['kegiatan']->id) }}"
           class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="document-preview">

        <center>
            <h4><u>LAPORAN PEMINDAHAN WBP</u></h4>
            <p>LEMBAGA PEMASYARAKATAN KELAS IIA BANCEUY BANDUNG</p>
        </center>

        <p>
            Pada hari <b>{{ $narrative['hari_tanggal'] }}</b>
            pukul <b>{{ $narrative['jam_mulai'] }}</b> WIB,
            telah dilaksanakan kegiatan pemindahan WBP dari
            <b>{{ $narrative['lokasi'] }}</b>
            menuju
            <b>{{ $narrative['lokasi_tujuan'] }}</b>.
        </p>

        <p>
            WBP tiba pada pukul
            <b>{{ $narrative['jam_tiba'] }}</b> WIB.
        </p>

        <p>
            Berdasarkan surat izin nomor
            <b>{{ $narrative['nomor_surat_izin'] }}</b>
            dan surat persetujuan nomor
            <b>{{ $narrative['nomor_surat_persetujuan'] }}</b>.
        </p>

        <p>
            WBP yang dipindahkan:
            <b>{{ $narrative['nama_wbp'] }}</b>
            dkk sebanyak
            <b>{{ $narrative['jumlah_wbp'] }}</b>
            orang.
        </p>

        <p>
            Pengawalan dilakukan oleh
            <b>{{ $narrative['jumlah_petugas'] }}</b>
            petugas lapas dan
            <b>{{ $narrative['jumlah_polisi'] }}</b>
            personel kepolisian.
        </p>

        <p>
            Total WBP di Lembaga Pemasyarakatan Kelas IIA Banceuy Bandung saat ini:
            <b>{{ $narrative['total_update_wbp'] }}</b> orang.
        </p>

        <p><b>Dokumentasi Kegiatan</b></p>

        @if($narrative['foto'])
            <img src="{{ asset('storage/pemindahan-wbp/' . $narrative['foto']) }}"
                 class="document-photo">
        @endif

        <div style="margin-top:70px; display:flex; justify-content:space-between; text-align:center;">
            <div style="width:40%;">
                <strong>Ka. KPLP</strong>
                <div style="height:60px;"></div>
                <strong>{{ $narrative['nama_kplp'] }}</strong>
            </div>

            <div style="width:40%;">
                <strong>Kasi. Kamtib</strong>
                <div style="height:60px;"></div>
                <strong>{{ $narrative['nama_kasi_kamtib'] }}</strong>
            </div>
        </div>

        <div style="margin-top:60px; text-align:center;">
            Mengetahui,<br>
            <strong>KALAPAS</strong>
            <div style="height:60px;"></div>
            <strong>{{ $narrative['nama_kalapas'] }}</strong>
        </div>

    </div>
    
    <div class="download-bottom no-print">
            
            <a  
                href="#"
                onclick="downloadAndRedirect(event)"
                class="btn btn-primary">
                Download DOCX
                
            </a>
            
        </div>
    
    <iframe
        id="downloadFrame"
        style="display:none;">
    </iframe>
    
</div>

<script>
function downloadAndRedirect(e)
{
    e.preventDefault();

    document.getElementById('downloadFrame').src =
        "{{ route('pemindahanwbp.download', $data['kegiatan']->id) }}";

    setTimeout(function () {
        window.location.href =
            "{{ route('pemindahanwbp.index') }}";
    }, 1500);
}
</script>

@endsection