@extends('admin-banceuy.partisi.main')

@section('content')
<div class="container-fluid my-3">

    <h4 class="fw-bold mb-2">Profile</h4>
    <small class="text-muted">Kelola semua halaman profile dalam 1 menu</small>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <div class="fw-bold mb-1">Validasi gagal:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mt-3">
        <div class="card-body p-4">

            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf

                {{-- ================== Sejarah Singkat ================== --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Sejarah Singkat</label>

                    <input id="sejarah_singkat" type="hidden" name="sejarah_singkat"
                        value="{{ old('sejarah_singkat', $profile->sejarah_singkat ?? '') }}">

                    <trix-editor input="sejarah_singkat" class="trix-content"></trix-editor>
                </div>

                {{-- ================== Struktur Organisasi ================== --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Struktur Organisasi</label>

                    <input id="struktur_organisasi" type="hidden" name="struktur_organisasi"
                        value="{{ old('struktur_organisasi', $profile->struktur_organisasi ?? '') }}">

                    <trix-editor input="struktur_organisasi" class="trix-content"></trix-editor>
                </div>

                {{-- ================== Visi & Misi ================== --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Visi & Misi</label>

                    <input id="visi_misi" type="hidden" name="visi_misi"
                        value="{{ old('visi_misi', $profile->visi_misi ?? '') }}">

                    <trix-editor input="visi_misi" class="trix-content"></trix-editor>
                </div>

                {{-- ================== Tugas & Fungsi ================== --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Kedudukan, Tugas dan Fungsi</label>

                    <input id="tugas_fungsi" type="hidden" name="tugas_fungsi"
                        value="{{ old('tugas_fungsi', $profile->tugas_fungsi ?? '') }}">

                    <trix-editor input="tugas_fungsi" class="trix-content"></trix-editor>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="feather-save me-1"></i> Simpan Semua
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection



    {{-- Trix CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.1.2/dist/trix.css">
    <style>
        /* biar rapih */
        trix-editor {
            min-height: 200px;
            background: #fff;
            border-radius: 14px;
            padding: 10px;
        }
    </style>


    {{-- Trix JS --}}
    <script src="https://cdn.jsdelivr.net/npm/trix@2.1.2/dist/trix.umd.min.js"></script>

    <script>
        // FIX: paksa trix load isi dari hidden input (database)
        document.addEventListener("trix-initialize", function (event) {
            const inputId = event.target.getAttribute("input");
            const hiddenInput = document.getElementById(inputId);

            if (hiddenInput && hiddenInput.value) {
                event.target.editor.loadHTML(hiddenInput.value);
            }
        });
    </script>
