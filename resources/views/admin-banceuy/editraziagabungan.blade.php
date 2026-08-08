@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">
    
<div class="d-flex justify-content-between align-items-center mb-3">
    
    <a href="{{ route('raziagabungan.index') }}"
       class="btn btn-secondary">
        Kembali
    </a>

</div>

<div class="container-fluid my-3">

    {{-- ALERT --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- MASTER KEGIATAN --}}
    {{-- ========================================================= --}}

    <div class="card mb-3">
    <div class="card-header">
        <strong>Informasi Kegiatan Razia Gabungan</strong>
    </div>

    <div class="card-body">

        <form
            action="{{ route('raziagabungan.update', $kegiatan->id) }}"
            method="POST">

            @csrf

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Tanggal Kegiatan</label>
                    <input
                        type="date"
                        name="tanggal_kegiatan"
                        class="form-control"
                        value="{{ $kegiatan->tanggal_kegiatan }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Jam Mulai</label>
                    <input
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        value="{{ $kegiatan->jam_mulai }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Jam Selesai</label>
                    <input
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        value="{{ $kegiatan->jam_selesai }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Lokasi</label>
                    <input
                        type="text"
                        name="lokasi"
                        class="form-control"
                        value="{{ $kegiatan->lokasi }}">
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Nomor Surat Dirjen IMIPAS</label>
                    <input
                        type="text"
                        name="nomor_surat_dirjen"
                        class="form-control"
                        value="{{ $kegiatan->nomor_surat_dirjen ?? '' }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tanggal Surat Dirjen IMIPAS</label>
                    <input
                        type="date"
                        name="tanggal_surat_dirjen"
                        class="form-control"
                        value="{{ $kegiatan->tanggal_surat_dirjen ?? '' }}">
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Nama Arahan Menteri IMIPAS</label>
                    <input
                        type="text"
                        name="nama_arahan_menteri"
                        class="form-control"
                        value="{{ $kegiatan->nama_arahan_menteri ?? '' }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tanggal Arahan Menteri IMIPAS</label>
                    <input
                        type="date"
                        name="tanggal_arahan_menteri"
                        class="form-control"
                        value="{{ $kegiatan->tanggal_arahan_menteri ?? '' }}">
                </div>

            </div>
            
            <div class="row">
            
            <div class="col-md-6 mb-3">
                <label>Nomor Keputusan Menteri IMIPAS</label>
                <input
                        type="text"
                        name="nomor_keputusan_menteri"
                        class="form-control"
                        value="{{ $kegiatan->nomor_keputusan_menteri ?? '' }}">
            </div>
            
            <div class="col-md-6 mb-3">
                <label> Tahun Keputusan Menteri IMIPAS</label>
                <input
                        type="text"
                        name="tahun_keputusan_menteri"
                        class="form-control"
                        value="{{ $kegiatan->tahun_keputusan_menteri ?? '' }}">
            </div>

            <div class="mb-3">
                <label>Pimpinan Kegiatan</label>
                <input
                    type="text"
                    name="pimpinan_kegiatan"
                    class="form-control"
                    value="{{ $kegiatan->pimpinan_kegiatan }}">
            </div>
            
            <div class="container-fluid my-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary">
                Update Master Kegiatan
            </button>
            
            </div>

        </form>

    </div>
</div>


    {{-- ========================================================= --}}
    {{-- KAMAR --}}
    {{-- ========================================================= --}}

    <div class="card mb-3">

        <div class="card-header">
            <strong>Kamar Yang Dirazia</strong>
        </div>

        <div class="card-body">

            @php
                $groupKamar = $kamar->groupBy('nama_blok');
            @endphp

            <form
                action="{{ route('raziagabungan.save-kamar',$kegiatan->id) }}"
                method="POST">

                @csrf

                @foreach($groupKamar as $namaBlok => $listKamar)

                    <details class="mb-3">

                        <summary class="fw-bold text-primary">

                            BLOK {{ strtoupper($namaBlok) }}

                        </summary>

                        <div class="row mt-2">

                            @foreach($listKamar as $item)

                                <div class="col-md-3 mb-2">

                                    <label>

                                        <input
                                            type="checkbox"
                                            name="kamar_id[]"
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, $kamarTerpilih) ? 'checked' : '' }}>

                                        {{ $item->nama_kamar }}

                                    </label>

                                </div>

                            @endforeach

                        </div>

                    </details>

                @endforeach

                <button class="btn btn-success">

                    Simpan Kamar

                </button>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BARANG TEMUAN --}}
    {{-- ========================================================= --}}

    <div class="card mb-3">

        <div class="card-header">
            <strong>Barang Temuan</strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route('raziagabungan.save-barang',$kegiatan->id) }}"
                method="POST">

                @csrf

                <div class="row mb-3">

                    <div class="col-md-5">

                        <select
                            id="barangSelect"
                            class="form-select">

                            <option value="">
                                Pilih Barang
                            </option>

                            @foreach($barang as $b)

                                <option
                                    value="{{ $b->id }}"
                                    data-nama="{{ $b->nama_barang }}"
                                    data-satuan="{{ $b->satuan }}">

                                    {{ $b->nama_barang }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <input
                            type="number"
                            id="jumlahBarang"
                            min="1"
                            class="form-control"
                            placeholder="Jumlah">

                    </div>

                    <div class="col-md-2">

                        <button
                            type="button"
                            id="btnTambahBarang"
                            class="btn btn-primary">

                            Tambah

                        </button>

                    </div>

                </div>

                <div id="listBarangTemuan">

                    @foreach($barangTemuan as $item)

                        <div class="border rounded p-2 mb-2">

                            {{ $item->nama_barang }}
                            ({{ $item->jumlah }} {{ $item->satuan }})

                            <input
                                type="hidden"
                                name="barang_id[]"
                                value="{{ $item->barang_id }}">

                            <input
                                type="hidden"
                                name="jumlah[]"
                                value="{{ $item->jumlah }}">

                            <button
                                type="button"
                                class="btn btn-danger btn-sm float-end hapusBarang">

                                Hapus

                            </button>

                        </div>

                    @endforeach

                </div>

                <button class="btn btn-success mt-3">

                    Simpan Barang Temuan

                </button>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TES URINE --}}
    {{-- ========================================================= --}}

    <div class="card mb-3">

        <div class="card-header">
            <strong>Tes Urine</strong>
        </div>

        <div class="card-body">

            <form
                action="{{ route('raziagabungan.save-tes-urine',$kegiatan->id) }}"
                method="POST">

                @csrf

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Kategori</th>
                            <th>Jumlah Peserta</th>
                            <th>Hasil</th>

                        </tr>

                    </thead>

                    <tbody>

                        @php
                            $pegawai =
                                $tesUrine->firstWhere('kategori','PEGAWAI');

                            $wbp =
                                $tesUrine->firstWhere('kategori','WBP');
                        @endphp

                        <tr>

                            <td>

                                PEGAWAI

                                <input
                                    type="hidden"
                                    name="kategori[]"
                                    value="PEGAWAI">

                            </td>

                            <td>

                                <input
                                    type="number"
                                    name="jumlah_peserta[]"
                                    class="form-control"
                                    value="{{ $pegawai->jumlah_peserta ?? 0 }}">

                            </td>

                            <td>

                                <input
                                    type="text"
                                    name="hasil[]"
                                    class="form-control"
                                    value="{{ $pegawai->hasil ?? '' }}">

                            </td>

                        </tr>

                        <tr>

                            <td>

                                WBP

                                <input
                                    type="hidden"
                                    name="kategori[]"
                                    value="WBP">

                            </td>

                            <td>

                                <input
                                    type="number"
                                    name="jumlah_peserta[]"
                                    class="form-control"
                                    value="{{ $wbp->jumlah_peserta ?? 0 }}">

                            </td>

                            <td>

                                <input
                                    type="text"
                                    name="hasil[]"
                                    class="form-control"
                                    value="{{ $wbp->hasil ?? '' }}">

                            </td>

                        </tr>

                    </tbody>

                </table>

                <button class="btn btn-success">

                    Simpan Tes Urine

                </button>

            </form>

        </div>

    </div>

{{-- FOTO --}}

<div class="card mt-3">

    <div class="card-header">
        <strong>Foto Kolase</strong>
    </div>

    <div class="card-body">

        @if($foto->count())

            <div class="mb-3 text-center">

                <img src="{{ asset('storage/razia-gabungan/'.$foto->first()->nama_file) }}" class="img-fluid rounded border" style="max-height:400px;">

            </div>

        @endif

        <form
            action="{{ route('raziagabungan.upload-foto',$kegiatan->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <input
                type="file"
                name="foto"
                class="form-control"
                accept=".jpg,.jpeg,.png"
                required>

            <button
                type="submit"
                class="btn btn-primary mt-3">

                Upload Foto

            </button>

        </form>

    </div>

</div>

            {{-- PREVIEW --}}
            <div class="mt-4 text-center">
                <a
                    href="{{ route('raziagabungan.preview',$kegiatan->id) }}"
                    class="btn btn-primary">
                    Preview Laporan
                </a>
        </div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

$('#btnTambahBarang').click(function(){

    let barangId =
        $('#barangSelect').val();

    let barangNama =
        $('#barangSelect option:selected')
        .data('nama');

    let satuan =
        $('#barangSelect option:selected')
        .data('satuan');

    let jumlah =
        $('#jumlahBarang').val();

    if(!barangId || !jumlah){

        alert('Pilih barang dan isi jumlah');

        return;
    }

    $('#listBarangTemuan').append(`

        <div class="border rounded p-2 mb-2">

            ${barangNama}
            (${jumlah} ${satuan})

            <input
                type="hidden"
                name="barang_id[]"
                value="${barangId}">

            <input
                type="hidden"
                name="jumlah[]"
                value="${jumlah}">

            <button
                type="button"
                class="btn btn-danger btn-sm float-end hapusBarang">

                Hapus

            </button>

        </div>

    `);

});

$(document).on('click','.hapusBarang',function(){

    $(this).parent().remove();

});

</script>

@endsection