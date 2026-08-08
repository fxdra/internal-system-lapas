@extends('startup_view.main')

@section('content')

<div class="container py-4">

    <div class="mb-4 text-center">

        <h3 class="fw-bold text-dark mb-1">
            Daftar Penghuni Kamar
        </h3>

        <p class="text-dark mb-0">
            Data penghuni yang saat ini berada pada kamar tersebut
        </p>

    </div>

    @if($kamar->wbps->count())

        <div class="row gy-4">

            @foreach($kamar->wbps as $wbp)

            <div class="col-12">

                <div class="card border-0 shadow-lg rounded-4">

                    <div class="card-body p-4">

                        <div class="row g-4">

                            {{-- DATA --}}
                            <div class="col-md-9">

                                <div class="d-flex justify-content-between flex-wrap mb-3">

                                    <div>

                                        <h4 class="fw-bold mb-2">
                                            {{ $wbp->nama }}
                                        </h4>

                                        <span class="badge bg-primary rounded-pill">
                                            {{ $wbp->status_wbp ?? 'Aktif' }}
                                        </span>

                                    </div>

                                    <div class="text-md-end mt-3 mt-md-0">

                                        <small class="text-secondary">
                                            No Register Instansi
                                        </small>

                                        <div class="fw-bold">
                                            {{ $wbp->no_reg_instansi ?? '-' }}
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-lg-6">

                                        <table class="table table-borderless table-sm">

                                            <tr>
                                                <td width="180"><strong>Negara</strong></td>
                                                <td>{{ $wbp->negara ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Agama</strong></td>
                                                <td>{{ $wbp->agama ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Jenis Kejahatan</strong></td>
                                                <td>{{ $wbp->jenis_kejahatan ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Putusan</strong></td>
                                                <td>{{ $wbp->putusan ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Ekspirasi</strong></td>
                                                <td>{{ $wbp->ekspirasi ?? '-' }}</td>
                                            </tr>

                                        </table>

                                    </div>

                                    <div class="col-lg-6">

                                        <table class="table table-borderless table-sm">

                                            <tr>
                                                <td width="180"><strong>Blok</strong></td>
                                                <td>{{ $wbp->lokasi_blok ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Sel</strong></td>
                                                <td>{{ $wbp->lokasi_sel ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Status Kamar</strong></td>
                                                <td>{{ $wbp->status_kamar ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Masa 1/3</strong></td>
                                                <td>{{ $wbp->masa_1_3 ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Masa 1/2</strong></td>
                                                <td>{{ $wbp->masa_1_2 ?? '-' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Masa 2/3</strong></td>
                                                <td>{{ $wbp->masa_2_3 ?? '-' }}</td>
                                            </tr>

                                        </table>

                                    </div>

                                </div>

                                <div class="row mt-2 g-3">

                                    <div class="col-md-6">

                                        <div class="card border-0 bg-light rounded-4">

                                            <div class="card-body text-center">

                                                <small class="text-dark">
                                                    Total Bulan Remisi
                                                </small>

                                                <h3 class="fw-bold text-dark mb-0">
                                                    {{ $wbp->total_bulan_remisi ?? 0 }}
                                                </h3>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="card border-0 bg-light rounded-4">

                                            <div class="card-body text-center">

                                                <small class="text-dark">
                                                    Total Hari Remisi
                                                </small>

                                                <h3 class="fw-bold text-dark mb-0">
                                                    {{ $wbp->total_hari_remisi ?? 0 }}
                                                </h3>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="mt-4">

                                    <h6 class="fw-bold">
                                        Keperluan
                                    </h6>

                                    <div class="text-secondary">
                                        {{ $wbp->keperluan ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    @else

        <div class="card border-0 shadow rounded-4">

            <div class="card-body text-center py-5">

                <div class="display-1">
                    🏠
                </div>

                <h4 class="fw-bold mt-3">
                    Kamar Kosong
                </h4>

                <p class="text-secondary mb-0">
                    Belum terdapat penghuni pada kamar ini.
                </p>

            </div>

        </div>

    @endif

</div>

@endsection