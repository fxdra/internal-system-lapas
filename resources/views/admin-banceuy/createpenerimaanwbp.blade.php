@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">

    <a href="{{ route('penerimaanwbp.index') }}"
       class="btn btn-secondary d-inline-block w-auto mb-3">
        Kembali
    </a>

    <h3 class="fw-bold text-center mb-3">

            Form Penerimaan WBP

        </h3>
        
        <div class="card shadow-sm">
            
        <div class="card-body">

            <form action="{{ route('penerimaanwbp.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Hari / Tanggal</label>
                        <input type="date"
                               name="hari_tanggal"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Jam Mulai</label>
                        <input type="time"
                               name="jam_mulai"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Lokasi</label>
                        <input type="text"
                               name="lokasi"
                               class="form-control"
                               required>
                    </div>
                </div>
                
                <div class="row">
                
                <div class="col-md-6 mb-3">
                    <label>Nomor Surat Dirjenpas</label>
                    <input type="text"
                           name="nomor_surat_dirjenpas"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Tanggal Surat Dirjenpas</label>
                    <input type="date"
                           name="tanggal_surat_dirjenpas"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label>Nama WBP</label>
                    <input type="text"
                           name="nama_wbp"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Jumlah WBP</label>
                    <input type="number"
                           name="jumlah_wbp"
                           class="form-control"
                           min="1"
                           required>
                </div>

                <div class="mb-3">
                    <label>Asal UPT</label>
                    <input type="text"
                           name="asal_upt"
                           class="form-control"
                           required>
                </div>

                <button 
                    type="submit"
                    class="btn btn-primary"
                    style="width:auto !important; display:inline-block !important;">
                    Simpan
                </button>

            </form>
        </div>
    </div>
</div>

@endsection