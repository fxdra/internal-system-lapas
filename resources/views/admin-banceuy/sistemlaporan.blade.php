@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-4">

    <div class="mb-4">

        <h3 class="fw-bold">
            Sistem Generate Laporan
        </h3>

        <p class="text-muted">
            Pilih jenis laporan yang ingin dibuat
        </p>

    </div>

    <div class="row">

        {{-- RAZIA INTERNAL --}}
        <div class="col-md-3 mb-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <h5 class="fw-bold">
                        Razia Internal
                    </h5>

                    <p class="text-muted">
                        Laporan Razia Internal Lapas
                    </p>

                    <a
                        href="{{ route('razia.index') }}"
                        class="btn btn-success">

                        Buat

                    </a>

                </div>

            </div>

        </div>

        {{-- RAZIA GABUNGAN --}}
        <div class="col-md-3 mb-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <h5 class="fw-bold">
                        Razia Gabungan
                    </h5>

                    <p class="text-muted">
                        Laporan Razia Gabungan TNI-Polri-BNN
                    </p>

                    <a
                        href="{{ route('raziagabungan.index') }}"
                        class="btn btn-success">

                        Buat

                    </a>

                </div>

            </div>

        </div>

        {{-- PENERIMAAN WBP --}}
        <div class="col-md-3 mb-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <h5 class="fw-bold">
                        Penerimaan WBP
                    </h5>

                    <p class="text-muted">
                        Laporan Penerimaan Warga Binaan
                    </p>

                    <a
                        href="{{ route('penerimaanwbp.index') }}"
                        class="btn btn-success">

                        Buat

                    </a>

                </div>

            </div>

        </div>

        {{-- PEMINDAHAN WBP --}}
        <div class="col-md-3 mb-3">

            <div class="card shadow-sm h-100">

                <div class="card-body text-center">

                    <h5 class="fw-bold">
                        Pemindahan WBP
                    </h5>

                    <p class="text-muted">
                        Laporan Pemindahan Warga Binaan
                    </p>

                    <a
                        
                        href="{{ route('pemindahanwbp.index') }}"
                        class="btn btn-success">
                        
                        Buat
                    </a>
                    
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
