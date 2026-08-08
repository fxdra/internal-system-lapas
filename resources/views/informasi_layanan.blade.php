@extends('startup_view.main')

@section('content')
    {{-- FontAwesome untuk icon sosial media --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        .section-title {
            font-size: 20px;
            letter-spacing: 1px;
        }

        .glass-card {
            background: rgba(105, 119, 126, 0.774);
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
    <div class="container accordion mt-3" id="informasiAccordion">

        @forelse($informasis as $i => $info)
            <div class="glass-card mb-4">

                <h2 class="accordion-header p-3" id="heading{{ $i }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $i }}" aria-expanded="false"
                        aria-controls="collapse{{ $i }}">

                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $info->judul }}</span>
                            </div>

                            {{-- 📅 Published Date --}}
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
                            {!! $info->isi !!}
                        </div>

                        {{-- Social Media --}}
                        <div class="d-flex flex-wrap gap-2 mb-3">

                            @if ($info->instagram)
                                <a href="{{ $info->instagram }}" target="_blank"
                                    class="btn btn-outline-light btn-sm btn-modern">
                                    <i class="fab fa-instagram me-1"></i> Instagram
                                </a>
                            @endif

                            @if ($info->facebook)
                                <a href="{{ $info->facebook }}" target="_blank"
                                    class="btn btn-outline-light btn-sm btn-modern">
                                    <i class="fab fa-facebook me-1"></i> Facebook
                                </a>
                            @endif

                            @if ($info->twitter)
                                <a href="{{ $info->twitter }}" target="_blank"
                                    class="btn btn-outline-light btn-sm btn-modern">
                                    <i class="fab fa-twitter me-1"></i> Twitter
                                </a>
                            @endif

                        </div>

                        {{-- Contact Support --}}
                        {{-- @if ($info->contact_support)
                            <div class="small text-info">
                                <i class="fas fa-envelope me-1"></i>
                                <a href="mailto:{{ $info->contact_support }}" class="text-info">
                                    {{ $info->contact_support }}
                                </a>
                            </div>
                        @endif --}}

                    </div>
                </div>

            </div>

        @empty
            <div class="alert alert-light rounded-4 shadow-sm text-center">
                Belum ada informasi layanan.
            </div>
        @endforelse

    </div>


    {{-- Feather icons --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.feather) feather.replace();
        });
    </script>
@endsection
