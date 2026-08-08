@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">

    <a  
        href="{{ route('pemindahanwbp.index') }}"
        class="btn btn-secondary mb-3"
        style="width:auto !important; display:inline-block !important;">
        
        Kembali
        
    </a>

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

    <div class="card mb-3">
        <div class="card-header">
            <strong>Edit Pemindahan WBP</strong>
        </div>

        <div class="card-body">

            <form action="{{ route('pemindahanwbp.update', $kegiatan->id) }}"
                  method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Hari / Tanggal</label>
                        <input type="date"
                               name="hari_tanggal"
                               value="{{ $kegiatan->hari_tanggal }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Jam Mulai</label>
                        <input type="time"
                               name="jam_mulai"
                               value="{{ substr($kegiatan->jam_mulai,0,5) }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Jam Tiba</label>
                        <input type="time"
                               name="jam_tiba"
                               value="{{ substr($kegiatan->jam_tiba,0,5) }}"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Lokasi</label>
                    <input type="text"
                           name="lokasi"
                           value="{{ $kegiatan->lokasi }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Lokasi Tujuan</label>
                    <input type="text"
                           name="lokasi_tujuan"
                           value="{{ $kegiatan->lokasi_tujuan }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Nomor Surat Izin</label>
                    <input type="text"
                           name="nomor_surat_izin"
                           value="{{ $kegiatan->nomor_surat_izin }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Nomor Surat Persetujuan</label>
                    <input type="text"
                           name="nomor_surat_persetujuan"
                           value="{{ $kegiatan->nomor_surat_persetujuan }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Nama WBP</label>
                    <input name="nama_wbp"
                              class="form-control"
                              required>{{ $kegiatan->nama_wbp }}
                </div>

                <div class="mb-3">
                    <label>Jumlah WBP</label>
                    <input type="number"
                           name="jumlah_wbp"
                           value="{{ $kegiatan->jumlah_wbp }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Jumlah Anggota Petugas Lapas</label>
                    <input type="number"
                           name="jumlah_petugas"
                           value="{{ $kegiatan->jumlah_petugas }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Jumlah Anggota Polisi</label>
                    <input type="number"
                           name="jumlah_polisi"
                           value="{{ $kegiatan->jumlah_polisi }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Total Update WBP</label>
                    <input type="number"
                           name="total_update_wbp"
                           value="{{ $kegiatan->total_update_wbp }}"
                           class="form-control"
                           required>
                </div>

                <button class="btn btn-primary">
                    Update Data
                </button>

            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Upload Dokumentasi</strong>
        </div>

        <div class="card-body">

            <form action="{{ route('pemindahanwbp.upload-foto', $kegiatan->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <input type="file"
                           name="foto"
                           class="form-control"
                           required>
                </div>

                <button class="btn btn-success">
                    Upload Foto
                </button>
            </form>

            @if($foto)
                <div class="mt-3">
                    <img src="{{ asset('storage/pemindahan-wbp/' . $foto) }}"
                         style="max-width:400px;"
                         class="img-thumbnail">
                </div>

                <div class="mt-3">
                    <a href="{{ route('pemindahanwbp.preview', $kegiatan->id) }}"
                       class="btn btn-primary">
                        Preview Laporan
                    </a>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection