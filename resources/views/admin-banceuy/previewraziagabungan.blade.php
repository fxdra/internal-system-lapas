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
    color: #000;
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

.no-print {
    width: 210mm;
    margin: 0 auto 15px auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.document-photo {
    width: 100%;
    border: 1px solid #ccc;
    margin-bottom: 15px;
}

@media print {
    body {
        background: white;
    }

    .document-preview {
        box-shadow: none;
        width: 100%;
        margin: 0;
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
                    href="{{ route('raziagabungan.edit', $data['kegiatan']->id) }}"
                    class="btn btn-secondary">
                    Kembali
                </a>
                
            </div>
            
        </div>

        <div class="document-preview">

            {{-- HEADER --}}
            <div class="mb-4">
                <div class="document-title">
                    LAPORAN HASIL RAZIA GABUNGAN
                </div>
            
                <div class="document-subtitle">
                    LEMBAGA PEMASYARAKATAN KELAS IIA BANCEUY BANDUNG
                </div>
            </div>

            {{-- NARASI PEMBUKA --}}
            <p style="text-align: justify; line-height: 1.9;">
                Pada hari
                <b>{{ $narrative['hari_tanggal'] }}</b>
                pukul
                <b>{{ $narrative['jam_mulai'] }}</b>
                WIB sampai dengan
                <b>{{ $narrative['jam_selesai'] }}</b>
                WIB telah dilaksanakan kegiatan razia gabungan yang dipimpin oleh
                <b>{{ $data['kegiatan']->pimpinan_kegiatan }}</b>
                di
                <b>{{ $narrative['lokasi'] }}</b>.
            </p>

            {{-- RINGKASAN --}}
            <h5 class="mt-4">
                <b>Ringkasan Kegiatan</b>
            </h5>

            <p>
                Jumlah kamar yang dirazia :
                <b>{{ count($data['kamar']) }}</b> kamar.
            </p>

            <p>
                Jumlah barang temuan :
                <b>{{ count($data['barang_temuan']) }}</b> jenis barang.
            </p>

            <p>
                Total barang temuan :
                <b>{{ collect($data['barang_temuan'])->sum('jumlah') }}</b> item.
            </p>

            {{-- KAMAR --}}
            <h5 class="mt-4">
                <b>Kamar Yang Dirazia</b>
            </h5>

            @if(count($data['kamar']) > 0)
                <ol>
                    @foreach($data['kamar'] as $kamar)
                        <li>
                            {{ $kamar->nama_blok }}
                            {{ $kamar->nama_kamar }}
                        </li>
                    @endforeach
                </ol>
            @else
                <p>-</p>
            @endif

            {{-- BARANG --}}
            <h5 class="mt-4">
                <b>Barang Temuan</b>
            </h5>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">No</th>
                            <th>Nama Barang</th>
                            <th width="20%">Jumlah</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data['barang_temuan'] as $index => $barang)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $barang->nama_barang }}
                                </td>

                                <td>
                                    {{ $barang->jumlah }}
                                    {{ $barang->satuan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    Nihil
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TES URINE --}}
            <h5 class="mt-4">
                <b>Tes Urine</b>
            </h5>

            <p>
                Pegawai :
                {{ $narrative['jumlah_pegawai_tes_urine'] }}
                orang
                ({{ ucfirst($narrative['hasil_pegawai_tes_urine']) }})
            </p>

            <p>
                WBP :
                {{ $narrative['jumlah_wbp_tes_urine'] }}
                orang
                ({{ ucfirst($narrative['hasil_wbp_tes_urine']) }})
            </p>

            {{-- PENANDATANGAN --}}
            <h5 class="mt-4">
                <b>Pejabat Penandatangan</b>
            </h5>

            <p>KPLP : {{ $narrative['nama_kplp'] }}</p>
            <p>Kasi Kamtib : {{ $narrative['nama_kasi_kamtib'] }}</p>
            <p>Kalapas : {{ $narrative['nama_kalapas'] }}</p>

            {{-- FOTO --}}
            <h5 class="mt-4">
                <b>Dokumentasi Kegiatan</b>
            </h5>

            @if(count($data['foto']) > 0)
                <div class="row">
                    @foreach($data['foto'] as $foto)
                        <div class="col-md-4 mb-3">
                            <img
                                src="{{ asset('storage/razia-gabungan/' . $foto) }}"
                                class="img-fluid rounded border"
                                style="width:100%; height:250px; object-fit:cover;">
                        </div>
                    @endforeach
                </div>
            @else
                <p>Tidak ada dokumentasi.</p>
            @endif

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
        
            document.getElementById('downloadFrame').src =
                "{{ route('penerimaanwbp.download', $data['kegiatan']->id) }}";
        
            setTimeout(function () {
                window.location.href =
                    "{{ route('penerimaanwbp.index') }}";
            }, 1000);
        }
    </script>

@endsection