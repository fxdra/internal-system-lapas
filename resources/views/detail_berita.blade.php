@extends('startup_view.main')

@section('content')
<div class="container py-4">

    {{-- ================== BREADCRUMB ================== --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="/" class="text-decoration-none text-white-50">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('/berita') }}" class="text-decoration-none text-white-50">Berita</a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">
                Detail
            </li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">

        {{-- ================== MAIN CONTENT ================== --}}
        <div class="col-12 col-lg-8">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden glass-card">

                {{-- Thumbnail Responsive --}}
                @if($berita->thumbnail)
                    <div class="ratio ratio-16x9">
                        <img src="{{ asset('storage/'.$berita->thumbnail) }}"
                            class="w-100 h-100"
                            style="object-fit: cover;"
                            alt="{{ $berita->judul }}">
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center glass-placeholder">
                        <span class="text-white-50">Tidak ada thumbnail</span>
                    </div>
                @endif

                <div class="card-body p-3 p-md-4 p-lg-5">

                    {{-- Judul --}}
                    <h2 class="fw-bold text-white mb-2 fs-4 fs-md-3">
                        {{ $berita->judul }}
                    </h2>

                    {{-- Meta info --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">

                        <span class="badge rounded-pill bg-light bg-opacity-10 text-white px-3 py-2">
                            <i data-feather="calendar" class="me-1"></i>
                            {{ $berita->published_at ? $berita->published_at->format('d M Y H:i') : $berita->created_at->format('d M Y H:i') }}
                        </span>

                        @if($berita->is_active)
                            <span class="badge rounded-pill bg-success bg-opacity-25 text-success px-3 py-2">
                                <i data-feather="check-circle" class="me-1"></i> Aktif
                            </span>
                        @else
                            <span class="badge rounded-pill bg-secondary bg-opacity-25 text-white px-3 py-2">
                                <i data-feather="x-circle" class="me-1"></i> Nonaktif
                            </span>
                        @endif

                    </div>

                    {{-- Isi --}}
                    <div class="berita-content">
                        {!! $berita->isi !!}
                    </div>

                </div>
            </div>
        </div>

        {{-- ================== SIDEBAR ================== --}}
        <div class="col-12 col-lg-4">

            {{-- sticky hanya desktop --}}
            <div class="sticky-lg-top" style="top: 90px;">

                <div class="card border-0 shadow-lg rounded-4 glass-card">
                    <div class="card-body p-3 p-md-4">

                        <h5 class="fw-bold text-white mb-3">
                            Navigasi
                        </h5>

                        <div class="d-grid gap-2">
                            <a href="{{ url('/berita') }}" class="btn btn-light rounded-pill">
                                <i data-feather="arrow-left" class="me-1"></i> Kembali ke Berita
                            </a>

                            <a href="/" class="btn btn-outline-light rounded-pill">
                                <i data-feather="home" class="me-1"></i> Home
                            </a>
                        </div>

                        <hr class="border-light border-opacity-25 my-4">

                        <h6 class="fw-bold text-white mb-2">Info</h6>
                        <p class="text-white-50 mb-0 small">
                            Halaman ini menampilkan detail berita terbaru dari Lapas Kelas IIA Banceuy Bandung.
                        </p>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- ================== STYLE GLASS + RESPONSIVE ================== --}}
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.06);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.10);
    }

    .glass-placeholder {
        height: 220px;
        background: rgba(255,255,255,.06);
    }

    /* isi berita biar rapi + responsive */
    .berita-content {
        line-height: 1.85;
        font-size: 15.5px;
        color: rgba(255,255,255,.88);
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .berita-content p {
        margin-bottom: 14px;
        color: rgba(255,255,255,.88);
    }

    .berita-content h1,
    .berita-content h2,
    .berita-content h3,
    .berita-content h4,
    .berita-content h5,
    .berita-content h6 {
        color: #fff;
        font-weight: 800;
        margin-top: 18px;
        margin-bottom: 12px;
    }

    .berita-content a {
        color: #9bd1ff;
        text-decoration: none;
        font-weight: 600;
    }
    .berita-content a:hover {
        text-decoration: underline;
    }

    .berita-content img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 16px;
        margin: 12px 0;
        display: block;
    }

    /* kalau ada table dari editor -> biar bisa scroll di hp */
    .berita-content table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
    }
    .berita-content table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .berita-content ul,
    .berita-content ol {
        padding-left: 20px;
        margin-bottom: 14px;
        color: rgba(255,255,255,.88);
    }

    .berita-content blockquote {
        border-left: 4px solid rgba(255,255,255,.25);
        padding-left: 14px;
        margin: 14px 0;
        color: rgba(255,255,255,.85);
        font-style: italic;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.feather) feather.replace();
    });
</script>
@endsection
