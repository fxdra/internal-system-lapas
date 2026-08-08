@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        /* ================= PRINT STYLE ================= */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body * {
                visibility: hidden;
            }

            #print-container,
            #print-container * {
                visibility: visible;
            }

            #print-container {
                width: 80mm;
                font-size: 12px;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 5px;
            }

            img {
                max-width: 100%;
                display: block;
                margin: 0 auto;
            }

            .no-print {
                display: none !important;
            }
        }

        /* ================= DETAIL CARD STYLE ================= */
        .detail-card {
            max-width: 500px;
            margin: 20px auto;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .detail-card h5 {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .detail-card p {
            margin: 6px 0;
            font-size: 14px;
        }

        .detail-card img {
            max-width: 150px;
            display: block;
            margin: 10px auto;
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 2px;
        }

        .detail-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
    </style>

    <div class="container my-3">
        <div class="detail-card">
            <h5 class="text-center">Detail Peminjaman</h5>

            <div id="print-container">
                <p><b>Nama Pengunjung:</b> {{ $peminjaman->nama_peminjam }}</p>
                <p><b>Kamar/Sel:</b> {{ $peminjaman->kamar_sel }}</p>
                <p><b>Buku:</b> {{ $peminjaman->buku->judul ?? '-' }}</p>

                @if ($peminjaman->buku->foto_buku)
                    <img src="{{ asset('storage/' . $peminjaman->buku->foto_buku) }}" alt="Foto Buku">
                @endif

                <p><b>Tanggal Pinjam:</b> {{ $peminjaman->tanggal_pinjam }}</p>
                <p><b>Tanggal Kembali:</b> {{ $peminjaman->tanggal_kembali ?? '-' }}</p>
                <p><b>Status Barcode:</b> {{ $peminjaman->status_barcode }}</p>

                @if ($peminjaman->img_barcode)
                    <img src="{{ asset('storage/' . $peminjaman->img_barcode) }}" alt="Barcode">
                @endif
            </div>

            <div class="detail-buttons no-print">
                <button type="button" onclick="window.print()" class="btn btn-dark btn-sm">
                    🖨️ Print 80mm
                </button>
                <a href="{{ route('admin.pengunjung.pengunjungPeminjam') }}" class="btn btn-secondary btn-sm">
                    🔙 Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
