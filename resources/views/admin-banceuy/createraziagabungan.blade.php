@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid md-3">

    <div class="mb-4">

        <a
            href="{{ route('raziagabungan.index') }}"
            class="btn btn-secondary d-inline-block w-auto">

            Kembali

        </a>

        <h3 class="fw-bold text-center mb-0">

            Form Razia Gabungan

        </h3>

    </div>
    
    <div class="card shadow-sm">

        <div class="card-body">
            
            <form
                action="{{ route('raziagabungan.store') }}"
                method="POST">

                @csrf
                
                <div class="row">
                
                <div class="col-md-6 mb-3">
                    
                    <label>
                        Tanggal Kegiatan</label>
                    
                    <input
                        type="date"
                        name="tanggal_kegiatan"
                        class="form-control">

                </div>
                
                <div class="col-md-3 mb-3">
                    <label>Jam Mulai</label>

                    <input
                        type="time"
                        name="jam_mulai"
                        class="form-control">

                </div>

                <div class="col-md-3 mb-3">
                    <label>Jam Selesai</label>
                    
                    <input
                        type="time"
                        name="jam_selesai"
                        class="form-control">

                </div>
                
            </div>

                <div class="mb-3">
                    <label>Lokasi</label>

                    <input
                        type="text"
                        name="lokasi"
                        class="form-control">

                </div>

                <div class="mb-3">
                    <label>Pimpinan Kegiatan</label>

                    <input
                        type="text"
                        name="pimpinan_kegiatan"
                        class="form-control">

                </div>

                <button class="btn btn-primary">

                    Simpan

                </button>

            </form>

            </div>
            
        </div>
        
    </div>

</div>

@endsection
