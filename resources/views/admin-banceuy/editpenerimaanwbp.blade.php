@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">

    <a href="{{ route('penerimaanwbp.index') }}"
       class="btn btn-secondary mb-3"
       style="width:auto !important; display:inline-block !important;">
        Kembali
    </a>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Edit Form Penerimaan WBP</strong>
        </div>

        <div class="card-body">

            <form action="{{ route('penerimaanwbp.update',$kegiatan->id) }}"
                  method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Hari / Tanggal</label>
                        <input type="date"
                               name="hari_tanggal"
                               class="form-control"
                               value="{{ $kegiatan->hari_tanggal }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Jam Mulai</label>
                        <input type="time"
                               name="jam_mulai"
                               class="form-control"
                               value="{{ $kegiatan->jam_mulai }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Lokasi</label>
                        <input type="text"
                               name="lokasi"
                               class="form-control"
                               value="{{ $kegiatan->lokasi }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Nomor Surat Dirjenpas</label>
                    <input type="text"
                           name="nomor_surat_dirjenpas"
                           class="form-control"
                           value="{{ $kegiatan->nomor_surat_dirjenpas }}">
                </div>

                <div class="mb-3">
                    <label>Tanggal Surat Dirjenpas</label>
                    <input type="date"
                           name="tanggal_surat_dirjenpas"
                           class="form-control"
                           value="{{ $kegiatan->tanggal_surat_dirjenpas }}">
                </div>

                <div class="mb-3">
                    <label>Nama WBP</label>
                    <input type="text"
                           name="nama_wbp"
                           class="form-control"
                           value="{{ $kegiatan->nama_wbp }}">
                </div>

                <div class="mb-3">
                    <label>Jumlah WBP</label>
                    <input type="number"
                           name="jumlah_wbp"
                           class="form-control"
                           value="{{ $kegiatan->jumlah_wbp }}">
                </div>

                <div class="mb-3">
                    <label>Asal UPT</label>
                    <input type="text"
                           name="asal_upt"
                           class="form-control"
                           value="{{ $kegiatan->asal_upt }}">
                </div>

                <button 
                    class="btn btn-primary">
                    Update Data
                </button>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Upload Foto
        </div>

        <div class="card-body">

            @if($foto)
                <img src="{{ asset('storage/penerimaan-wbp/'.$foto) }}"
                     class="img-fluid rounded border mb-3"
                     style="max-height:400px;">
            @endif

            <form action="{{ route('penerimaanwbp.upload-foto',$kegiatan->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <input type="file"
                       name="foto"
                       class="form-control"
                       required>

                <button class="btn btn-primary mt-3">
                    Upload Foto
                </button>
            </form>

            <a  
                
                href="{{ route('penerimaanwbp.preview',$kegiatan->id) }}"
                class="btn btn-success mt-3"
                type="submit"
                style="width:auto !important; display:inline-block !important;">
                Preview Laporan
            </a>

        </div>
    </div>

</div>

@endsection