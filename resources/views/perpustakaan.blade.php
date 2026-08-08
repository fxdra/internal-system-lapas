@extends('startup_view.main')

@section('content')
    <!-- Slick CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    <div class="container my-5">

        <h1 class="text-center mb-4 fw-bold text-dark">Daftar Buku Perpustakaan</h1>

        <!-- Live Search -->
        <div class="row mb-4">
            <div class="col-md-6 offset-md-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari buku...">
            </div>
        </div>

        <!-- Slick Slider / HP -->
        <div class="buku-slider d-block d-md-none">
            @foreach ($bukus as $buku)
                <div class="buku-card p-2" data-judul="{{ strtolower($buku->judul) }}"
                    data-penulis="{{ strtolower($buku->penulis ?? '') }}"
                    data-kategori="{{ strtolower($buku->kategori->nama ?? '') }}">
                    <div class="card h-100 shadow-sm hover-card text-center rounded-card">
                        <img src="{{ $buku->foto ? asset('storage/' . $buku->foto) : 'https://via.placeholder.com/200x300?text=No+Image' }}"
                            class="card-img-top rounded-top" alt="{{ $buku->judul }}">
                        <div class="card-body p-2">
                            <h6 class="card-title mb-2">{{ $buku->judul }}</h6>
                            <button class="btn btn-light text-dark btn-sm detail-btn" data-bs-toggle="modal"
                                data-bs-target="#detailModal" data-buku='@json($buku)'>Detail</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Grid Desktop / Tablet -->
        <div class="row d-none d-md-flex" id="bukuContainer">
            @foreach ($bukus as $buku)
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4 buku-card" data-judul="{{ strtolower($buku->judul) }}"
                    data-penulis="{{ strtolower($buku->penulis ?? '') }}"
                    data-kategori="{{ strtolower($buku->kategori->nama ?? '') }}">
                    <div class="card h-100 shadow-sm hover-card rounded-card text-center">
                        <img src="{{ $buku->foto ? asset('storage/' . $buku->foto) : 'https://via.placeholder.com/200x300?text=No+Image' }}"
                            class="card-img-top rounded-top" alt="{{ $buku->judul }}"
                            style="height:300px; object-fit:cover;">
                        <div class="card-body p-2">
                            <h5 class="card-title mb-2">{{ $buku->judul }}</h5>
                            <button class="btn btn-light btn-sm detail-btn text-dark fw-bold" data-bs-toggle="modal"
                                data-bs-target="#detailModal" data-buku='@json($buku)'>Detail</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Detail Buku -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="modalFoto" src="" class="img-fluid mb-3"
                                style="height:300px; object-fit:cover;">
                        </div>
                        <div class="col-md-8">
                            <h5 id="modalJudul"></h5>
                            <p><strong>Penulis:</strong> <span id="modalPenulis"></span></p>
                            <p><strong>Publisher:</strong> <span id="modalPublisher"></span></p>
                            <p><strong>Kategori:</strong> <span id="modalKategori"></span></p>
                            <p><strong>Tahun:</strong> <span id="modalTahun"></span></p>
                            <p><strong>Tanggal Publish:</strong> <span id="modalTanggal"></span></p>
                            <p><strong>Sinopsis:</strong> <span id="modalSinopsis"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .hover-card {
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Rounded Card Fix untuk HP & Desktop */
        .rounded-card {
            border-radius: 12px;
            /* full rounded */
            overflow: hidden;
            /* pastikan gambar & body ikut rounded */
        }

        /* Slick Slider */
        .buku-slider .slick-slide {
            opacity: 0.7;
            filter: blur(1.5px);
            transform: scale(0.85);
            transition: all 0.5s ease;
            margin: 15px 5px;
            min-height: 360px;
            /* lebih tinggi supaya tombol tidak terpotong */
            display: flex;
            flex-direction: column;
            justify-content: space-between;

            overflow: visible;
            /* FIX clipping border-radius */
        }

        .buku-slider .slick-slide .card-img-top {
            height: 220px;
            object-fit: cover;
        }

        /* Slide tengah lebih besar */
        .buku-slider .slick-center {
            opacity: 1;
            filter: blur(0);
            transform: scale(1.15);
        }

        /* Tombol Detail selalu muncul */
        .buku-slider .detail-btn {
            display: inline-block;
        }

        /* Optional: smooth scaling for slider images */
        .buku-slider .slick-slide img {
            transition: transform 0.5s ease;
        }

        .buku-slider .slick-center img {
            transform: scale(1.05);
        }

        .buku-slider .slick-list {
            overflow: visible;
            padding-top: 15px;
        }
    </style>

    <!-- jQuery & Slick JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script>
        $(document).ready(function() {

            // Slick Slider HP
            $('.buku-slider').slick({
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 3,
                arrows: true,
                infinite: true,
                swipeToSlide: true,
                speed: 600,
                cssEase: 'ease-in-out',
                useTransform: true,
                responsive: [{
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 1,
                            centerPadding: '40px'
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            centerPadding: '20px'
                        }
                    }
                ]
            });

            // Live Search
            $('#searchInput').on('keyup', function() {
                let filter = $(this).val().toLowerCase();
                $('.buku-card').each(function() {
                    let judul = $(this).data('judul');
                    let penulis = $(this).data('penulis');
                    let kategori = $(this).data('kategori');
                    if (judul.includes(filter) || penulis.includes(filter) || kategori.includes(
                            filter)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('.buku-slider').slick('setPosition'); // refresh slider
            });

            // Tombol Detail Modal
            $('.detail-btn').on('click', function() {
                const buku = $(this).data('buku');
                $('#modalFoto').attr('src', buku.foto ? '/storage/' + buku.foto :
                    'https://via.placeholder.com/200x300?text=No+Image');
                $('#modalJudul').text(buku.judul);
                $('#modalPenulis').text(buku.penulis ?? '-');
                $('#modalPublisher').text(buku.publisher ?? '-');
                $('#modalKategori').text(buku.kategori?.nama ?? '-');
                $('#modalTahun').text(buku.tahun ?? '-');
                $('#modalTanggal').text(buku.tanggal_publish ? new Date(buku.tanggal_publish)
                    .toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }) : '-');
                $('#modalSinopsis').text(buku.sinopsis ?? '-');
            });

        });
    </script>
@endsection
