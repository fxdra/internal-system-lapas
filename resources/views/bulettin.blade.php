@extends('startup_view.main')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        .section-title {
            font-size: 20px;
            letter-spacing: 1px;
        }

        .glass-card {
            background: rgb(105, 119, 126);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            transition: all .4s ease;
            overflow: hidden;
        }

        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        }

        .accordion-button {
            background: transparent !important;
            color: #fff;
            font-weight: 600;
            font-size: 18px;
        }

        .accordion-button:not(.collapsed) {
            color: #9bd1ff;
        }

        .accordion-button::after {
            filter: invert(1);
        }

        .accordion-body {
            animation: fadeIn .4s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .published-date {
            font-size: 13px;
            color: #cfd8dc;
        }

        .btn-modern {
            border-radius: 50px;
            padding: 6px 18px;
            transition: .3s ease;
        }

        .btn-modern:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container py-5">

        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark section-title">
                Bulettin & Informasi Layanan
            </h2>
            <p class="text-dark" style="font-size: 14px;">
                Informasi terbaru Lapas Kelas IIA Banceuy Bandung.
            </p>
        </div>

        <div class="accordion" id="informasiAccordion">

            @forelse($bulettin as $i => $info)
                <div class="glass-card mb-4">

                    <h2 class="accordion-header p-3" id="heading{{ $i }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $i }}" aria-expanded="false"
                            aria-controls="collapse{{ $i }}">

                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $info->judul }}</span>
                                </div>

                                {{-- 🗓 Published Date --}}
                                @if ($info->published_at)
                                    <div class="published-date mt-1">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($info->published_at)->translatedFormat('d F Y') }}
                                    </div>
                                @endif
                            </div>

                        </button>
                    </h2>

                    <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $i }}" data-bs-parent="#informasiAccordion">

                        <div class="accordion-body text-white px-4 pb-4">

                            {{-- Thumbnail --}}
                            @if ($info->thumbnail)
                                <img src="{{ asset('storage/' . $info->thumbnail) }}" class="w-100 rounded-4 mb-4"
                                    style="max-height:320px; object-fit:cover;">
                            @endif

                            {{-- Isi --}}
                            <div class="mb-4">
                                {!! $info->edisi !!}
                            </div>

                            {{-- PDF --}}
                            @if ($info->upload_pdf)
                                <div>
                                    <a href="{{ asset('storage/' . $info->upload_pdf) }}" target="_blank"
                                        class="btn btn-outline-light btn-sm btn-modern me-2">
                                        <i class="fas fa-file-pdf me-1"></i> Baca PDF
                                    </a>

                                    <a href="{{ asset('storage/' . $info->upload_pdf) }}" download
                                        class="btn btn-outline-info btn-sm btn-modern">
                                        <i class="fas fa-download me-1"></i> Unduh
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            @empty
                <div class="alert alert-light rounded-4 shadow-sm text-center">
                    Belum ada bulettin.
                </div>
            @endforelse
        </div>
        <div class="container">
            <div class="text-center mt-5 text-white-50 small">
                Jangan lupa untuk mengikuti kabar terkini dari kami
            </div>
        </div>


    </div>
@endsection
