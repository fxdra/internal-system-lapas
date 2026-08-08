@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">

<div class="d-flex justify-content-between align-items-center mb-3">

    <a href="{{ route('razia.index') }}"
       class="btn btn-secondary">
        Kembali
    </a>

</div>

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

{{-- DATA KEGIATAN --}}

    <div class="card mb-3">

    <div class="card-header">
        Data Kegiatan
    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('razia.update',$kegiatan->id) }}">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label>Tanggal Razia</label>

                    <input
                        type="date"
                        name="tanggal_razia"
                        class="form-control"
                        value="{{ $kegiatan->tanggal_razia }}"
                        required>

                </div>

                <div class="col-md-3 mb-3">

                    <label>Jam Mulai</label>

                    <input
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        value="{{ substr($kegiatan->jam_mulai,0,5) }}"
                        required>

                </div>

                <div class="col-md-3 mb-3">

                    <label>Jam Selesai</label>

                    <input
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        value="{{ substr($kegiatan->jam_selesai,0,5) }}"
                        required>

                </div>

            </div>

            <div class="mb-3">

                <label>Lokasi</label>

                <input
                    type="text"
                    name="lokasi"
                    class="form-control"
                    value="{{ $kegiatan->lokasi }}"
                    required>

            </div>

            <div class="mb-3">

                <label>Pimpinan Razia</label>

                <input
                    type="text"
                    name="pimpinan_razia"
                    class="form-control"
                    value="{{ $kegiatan->pimpinan_razia }}"
                    required>

            </div>

            <button class="btn btn-primary">
                Update Data Kegiatan
            </button>

        </form>

    </div>

</div>

{{-- KAMAR --}}

    <div class="card mt-3">

    <div class="card-header">
        <strong>Kamar Yang Dirazia</strong>
    </div>

    <div class="card-body">

        @php
            $groupKamar = $kamar->groupBy('nama_blok');
        @endphp

        <form
            action="{{ route('razia.save-kamar',$kegiatan->id) }}"
            method="POST">

            @csrf

            @foreach($groupKamar as $namaBlok => $listKamar)

        <details class="mb-3">

    <summary class="fw-bold text-primary">
        BLOK {{ strtoupper($namaBlok) }}
    </summary>

    <div class="mt-3">

        <div class="row">

            @foreach($listKamar as $item)

                <div class="col-md-3 col-sm-4 col-6 mb-2">

                    <label>

                        <input
                            type="checkbox"
                            name="kamar[]"
                            value="{{ $item->id }}"
                            {{ in_array($item->id,$kamarTerpilih) ? 'checked' : '' }}>

                        {{ $item->nama_kamar }}

                    </label>

                </div>

            @endforeach

        </div>

    </div>

</details>

@endforeach

            <button
                class="btn btn-success">
                Simpan Kamar
            </button>

        </form>

    </div>

</div>

{{-- BARANG TEMUAN --}}

    <div class="card mt-3">

    <div class="card-header">
        <strong>Barang Temuan</strong>
    </div>

    <div class="card-body">

        <form
            action="{{ route('razia.save-barang',$kegiatan->id) }}"
            method="POST">

            @csrf

            <div class="row mb-3">

                <div class="col-md-6">

                    <select
                        id="barangSelect"
                        class="form-select">

                        <option value="">
                            Pilih Barang Temuan
                        </option>

                        @foreach($barang as $b)

                            <option
                                value="{{ $b->id }}"
                                data-nama="{{ $b->nama_barang }}"
                                data-satuan="{{ $b->satuan }}">

                                {{ $b->nama_barang }}
                                ({{ $b->satuan }})

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

                <div class="col-md-3">

                    <button
                        type="button"
                        id="btnTambahBarang"
                        class="btn btn-primary w-100">

                        Tambah Barang

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

            <button
                type="submit"
                class="btn btn-success mt-3">

                Simpan Barang Temuan

            </button>

        </form>

    </div>

</div>

{{-- PERSONEL --}}

    <div class="card mb-3">

    <div class="card-body">

        <div class="card mt-3">

    <div class="card-header">
        <strong>Personel</strong>
    </div>

    <div class="card-body">

        @php

            $staffKplp = $personel
                ->where('kategori','STAFF_KPLP')
                ->first();

            $karupam = $personel
                ->where('kategori','KARUPAM')
                ->first();

            $wakarupam = $personel
                ->where('kategori','WAKARUPAM')
                ->first();

            $regu = $personel
                ->where('kategori','REGU_PENGAMANAN')
                ->first();

        @endphp

        <form
            action="{{ route('razia.save-personel',$kegiatan->id) }}"
            method="POST">

            @csrf

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Staff KPLP
                    </label>

                    <input
                        type="number"
                        min="0"
                        class="form-control"
                        name="staff_kplp"
                        value="{{ $staffKplp->jumlah ?? 0 }}">

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Karupam
                    </label>

                    <input
                        type="number"
                        min="0"
                        class="form-control"
                        name="karupam"
                        value="{{ $karupam->jumlah ?? 0 }}">

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Wakarupam
                    </label>

                    <input
                        type="number"
                        min="0"
                        class="form-control"
                        name="wakarupam"
                        value="{{ $wakarupam->jumlah ?? 0 }}">

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Regu Pengamanan
                    </label>

                    <input
                        type="number"
                        min="0"
                        class="form-control"
                        name="regu_pengamanan"
                        value="{{ $regu->jumlah ?? 0 }}">

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-success">

                Simpan Personel

            </button>

        </form>

    </div>

</div>

    </div>

</div>

{{-- FOTO --}}

    <div class="card mb-3">

    <div class="card mt-3">

    <div class="card-header">
        <strong>Foto Kolase</strong>
    </div>

    <div class="card-body">

        @if($foto->count())

            <div class="mb-3">

                <img
                    src="{{ asset('storage/razia/'.$foto->first()->nama_file) }}"
                    class="img-fluid rounded border"
                    style="max-height:400px;">

            </div>

        @endif

        <form
            action="{{ route('razia.upload-foto',$kegiatan->id) }}"
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
            <a
            href="{{ route('razia.preview',$kegiatan->id) }}"
            class="btn btn-primary">
        
            Preview Laporan
    
            </a>
        
            </div>

            </div>

            </div>

    <script>

        $('#btnTambahBarang').click(function(){

    let barangId =
        $('#barangSelect').val();

    let barangNama =
        $('#barangSelect option:selected')
        .data('nama');

    let barangSatuan =
        $('#barangSelect option:selected')
        .data('satuan');

    let jumlah =
        $('#jumlahBarang').val();

    if(!barangId || !jumlah){

        alert(
            'Pilih barang dan isi jumlah'
        );

        return;
    }

    $('#listBarangTemuan').append(`

        <div class="border rounded p-2 mb-2">

            ${barangNama}
            (${jumlah} ${barangSatuan})

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

    $('#barangSelect').val('');
    $('#jumlahBarang').val('');

});

        $(document).on(
    'click',
    '.hapusBarang',
    function(){

        $(this)
            .closest('.border')
            .remove();

    }
);

    </script>

@endsection
