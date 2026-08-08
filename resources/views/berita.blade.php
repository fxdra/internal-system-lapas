@extends('startup_view.main') {{-- sesuaikan layout kamu --}}

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
            <div>
                <h3 class="fw-bold text-white mb-0">Semua Berita</h3>
                <small class="text-white-50">Informasi & kegiatan terbaru Lapas Kelas IIA Banceuy</small>
            </div>

            <div class="d-flex gap-2">
                <a href="/" class="btn btn-sm btn-light rounded-pill px-3">
                    <i class="feather-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="card border-0 rounded-4 shadow-lg mb-4"
            style="background: rgba(255,255,255,.06); backdrop-filter: blur(12px);">
            <div class="card-body p-3">
                <form method="GET" action="{{ url('/berita') }}">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-white border-0">
                            <i class="feather-search"></i>
                        </span>
                        <input type="text" class="form-control bg-transparent text-white border-0" name="q"
                            placeholder="Cari berita..." value="{{ request('q') }}">
                        <button class="btn btn-primary rounded-pill px-4" type="submit">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ================== GRID (PC) ================== --}}
        <div class="row g-3 d-none d-lg-flex">
            @forelse($berita as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 rounded-4 shadow-lg h-100 overflow-hidden"
                        style="background: rgba(255,255,255,.06); backdrop-filter: blur(12px);">

                        {{-- Thumbnail --}}
                        @if ($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" class="w-100"
                                style="height: 260px; object-fit: cover;" alt="{{ $item->judul }}">
                        @else
                            <div class="w-100 d-flex align-items-center justify-content-center"
                                style="height: 260px; background: rgba(255,255,255,.08);">
                                <span class="text-white-50">Tidak ada thumbnail</span>
                            </div>
                        @endif

                        <div class="card-body p-3">
                            {{-- Tanggal --}}
                            <div class="text-white-50 small mb-2">
                                <i class="feather-calendar me-1"></i>
                                {{ $item->published_at ? $item->published_at->format('d M Y H:i') : $item->created_at->format('d M Y H:i') }}
                            </div>

                            {{-- Judul --}}
                            <h6 class="fw-bold text-white mb-2" style="min-height: 44px;">
                                {{ $item->judul }}
                            </h6>

                            {{-- Cuplikan isi --}}
                            <p class="text-white-50 mb-3" style="min-height: 60px;">
                                {!! \Illuminate\Support\Str::limit(strip_tags($item->isi), 130) !!}
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ url('/berita/' . $item->slug) }}"
                                    class="btn btn-sm btn-primary rounded-pill px-3">
                                    Baca Selengkapnya
                                </a>

                                <span class="badge rounded-pill bg-success bg-opacity-25 text-success px-3">
                                    <i class="feather-eye me-1"></i> Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light rounded-4 shadow-sm">
                        Belum ada berita.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ================== SLIDER (MOBILE/TABLET) ================== --}}
        <div class="d-block d-lg-none">
            <div class="berita-slick">
                @forelse($berita as $item)
                    <div class="px-2">
                        <div class="card border-0 rounded-4 shadow-lg h-100 overflow-hidden"
                            style="background: rgba(255,255,255,.06); backdrop-filter: blur(12px);">


                            <div class="card-body p-3">
                                {{-- Tanggal --}}
                                <div class="text-white-50 small mb-2">
                                    <i class="feather-calendar me-1"></i>
                                    {{ $item->published_at ? $item->published_at->format('d M Y H:i') : $item->created_at->format('d M Y H:i') }}
                                </div>

                                {{-- Judul --}}
                                <h6 class="fw-bold text-white mb-2">
                                    {{ $item->judul }}
                                </h6>

                                {{-- Cuplikan isi --}}
                                <p class="text-white-50 mb-3">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($item->isi), 130) !!}
                                </p>

                                <a href="{{ url('/berita/' . $item->slug) }}"
                                    class="btn btn-sm btn-primary rounded-pill px-3">
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light rounded-4 shadow-sm">
                        Belum ada berita.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $berita->links() }}
        </div>

    </div>

    {{-- ================== Slick CDN ================== --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <style>
        .berita-slick .slick-dots {
            bottom: -35px;
        }

        .berita-slick .slick-dots li button:before {
            font-size: 10px;
            opacity: .5;
            color: white;
        }

        .berita-slick .slick-dots li.slick-active button:before {
            opacity: 1;
            color: white;
        }

        .form-control:focus {
            outline: none;
            box-shadow: none;
        }
    </style>

    <script>
        $(document).ready(function() {
            if ($('.berita-slick').length) {
                $('.berita-slick').slick({
                    dots: true,
                    arrows: false,
                    infinite: true,
                    autoplay: true,
                    autoplaySpeed: 3500,
                    speed: 500,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerMode: true,
                    centerPadding: '30px',
                    responsive: [{
                            breakpoint: 768,
                            settings: {
                                centerPadding: '20px'
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                centerPadding: '12px'
                            }
                        }
                    ]
                });
            }
        });
    </script>
@endsection
