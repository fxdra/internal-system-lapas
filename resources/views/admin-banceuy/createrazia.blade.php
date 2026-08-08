@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">

    <div class="mb-4">

        <a
            href="{{ route('razia.index') }}"
            class="btn btn-secondary d-inline-block w-auto">

            Kembali

        </a>

        <h4 class="fw-bold text-center mb-0">

            Form Razia Internal

        </h4>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">
            
@if ($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif

<div class="card shadow-sm">

    <div class="card-body">

        <form method="POST"
              action="{{ route('razia.store') }}">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Tanggal Razia
                    </label>

                    <input
                        type="date"
                        name="tanggal_razia"
                        class="form-control"
                        value="{{ old('tanggal_razia') }}"
                        required>

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        value="{{ old('jam_mulai') }}"
                        required>

                </div>

                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        value="{{ old('jam_selesai') }}"
                        required>

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Lokasi
                </label>

                <input
                    type="text"
                    name="lokasi"
                    class="form-control"
                    value="{{ old('lokasi') }}"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Pimpinan Razia
                </label>

                <select
                    name="pimpinan_razia"
                    class="form-select"
                    required>

                    <option value="">
                        Pilih Pimpinan
                    </option>

                    <option value="KPLP">
                        KPLP
                    </option>

                    <option value="Kasi Kamtib">
                        Kasi Kamtib
                    </option>

                    <option value="Kalapas">
                        Kalapas
                    </option>

                </select>

            </div>

            <div class="text-end">

                <button
                    type="submit"
                    class="btn btn-primary">

                    Input Detail Razia

                </button>

            </div>

        </form>

    </div>

</div>

</div>

@endsection
