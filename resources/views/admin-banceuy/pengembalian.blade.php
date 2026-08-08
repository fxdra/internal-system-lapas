@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        /* ================= TABLE STYLING ================= */
        .responsive-table td,
        .responsive-table th {
            white-space: normal !important;
            word-break: break-word;
        }

        .judul-col {
            max-width: 200px;
            white-space: normal;
            word-wrap: break-word;
        }

        @media (max-width:768px) {
            .responsive-table thead {
                display: none;
            }

            .table-responsive {
                overflow-x: hidden !important;
            }

            .responsive-table .col-no,
            .responsive-table .buku-col,
            .responsive-table .tanggal-pinjam-col,
            .responsive-table .tanggal-kembali-col {
                display: none;
            }

            .judul-col {
                max-width: 100%;
                font-size: 16px;
                font-weight: bold;
            }

            .responsive-table .main-row {
                display: block;
                border-bottom: 1px solid #ddd;
                padding: 10px;
                cursor: pointer;
                background: #fff;
            }

            .detail-row td {
                background: #f9f9f9;
            }
        }
    </style>

    <div class="container-fluid my-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Pengunjung Peminjam</h4>
                <small class="text-muted">Data pengunjung yang meminjam buku</small>
            </div>
        </div>

        {{-- SEARCH --}}
        <input type="text" name="search" class="form-control mb-3" placeholder="Cari nama pengunjung atau judul buku...">

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle responsive-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama Pengunjung</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjaman as $i => $p)
                            <tr class="main-row">
                                <td class="col-no">{{ $i + 1 }}</td>
                                <td class="judul-col">{{ $p->nama_peminjam }}</td>
                                <td class="buku-col">{{ $p->buku->judul ?? '-' }}</td>
                                <td class="tanggal-pinjam-col">{{ $p->tanggal_pinjam }}</td>
                                <td class="tanggal-kembali-col">{{ $p->tanggal_kembali ?? '-' }}</td>
                            </tr>
                            <tr class="detail-row" style="display:none;">
                                <td colspan="5">
                                    <div class="p-2">
                                        <p><b>Nama Pengunjung:</b> {{ $p->nama_peminjam }}</p>
                                        <p><b>Buku:</b> {{ $p->buku->judul ?? '-' }}</p>
                                        <p><b>Tanggal Pinjam:</b> {{ $p->tanggal_pinjam }}</p>
                                        <p><b>Tanggal Kembali:</b> {{ $p->tanggal_kembali ?? '-' }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toggle detail row untuk HP
        $(document).on('click', '.main-row', function() {
            if ($(window).width() <= 768) {
                $(this).next('.detail-row').slideToggle(200);
            }
        });

        // Simple search
        $('input[name="search"]').on('input', function() {
            let term = $(this).val().toLowerCase();
            $('.responsive-table tbody tr.main-row').each(function() {
                let text = $(this).text().toLowerCase();
                if (text.indexOf(term) > -1) {
                    $(this).show();
                    $(this).next('.detail-row').hide(); // hide detail until clicked
                } else {
                    $(this).hide();
                    $(this).next('.detail-row').hide();
                }
            });
        });
    </script>
@endsection
