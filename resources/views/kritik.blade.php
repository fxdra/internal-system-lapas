@extends('startup_view.main')

@section('content')
    <style>
        .kritik-card {
            background: rgb(105, 119, 126);
        }
    </style>
    <div class="container my-3">

        <div class="card shadow-sm kritik-card">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-2">Kritik & Saran</h4>
                <p class="text-muted mb-4">Silakan isi form berikut dengan data yang benar.</p>

                @if (session('success'))
                    <div class="alert alert-success fw-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/kritik-saran" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Foto KTP</label>
                            <input type="file" class="form-control" id="foto_ktp" name="foto_ktp" accept="image/*"
                                required>
                            <div class="form-text">Max 2MB (JPG/PNG).</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK</label>
                            <input type="tel" class="form-control" id="nik" name="nik" minlength="16"
                                maxlength="16" value="{{ old('nik') }}" required>
                            <div class="form-text">Wajib 16 digit (angka saja).</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                value="{{ old('nama_lengkap') }}" required>
                            <div class="form-text">Nama akan otomatis uppercase.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis</label>
                            <select class="form-select" name="jenis" required>
                                <option value="">-- Pilih --</option>
                                <option value="KRITIK" {{ old('jenis') == 'KRITIK' ? 'selected' : '' }}>KRITIK
                                </option>
                                <option value="SARAN" {{ old('jenis') == 'SARAN' ? 'selected' : '' }}>SARAN
                                </option>
                            </select>
                        </div>


                        <div class="col-12">
                            <label class="form-label fw-semibold">Pesan Kritik / Saran</label>
                            <textarea class="form-control" name="pesan" rows="4" required>{{ old('pesan') }}</textarea>
                            <div class="form-text">Minimal 10 karakter, maksimal 1000.</div>
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                Kirim
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>

    {{-- Global untuk JS external --}}
    <script>
        window.KRITIK = {
            ocrRoute: "{{ route('ocr.ktp') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>

    <script src="{{ asset('js/kritik.js') }}"></script>

@endsection
