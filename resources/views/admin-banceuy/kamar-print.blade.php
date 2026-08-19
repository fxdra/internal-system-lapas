<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print Semua Kamar</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
        }

        /* =========================
           BUTTON PRINT
        ========================= */

        .no-print {
            margin: 15px;
        }

        .no-print button {
            padding: 10px 18px;
            font-size: 16px;
            cursor: pointer;
        }

        /* =========================
           PAGE A4
        ========================= */

        .page {
            width: 210mm;
            height: 297mm;
            padding: 10mm;

            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(3, 1fr);

            gap: 6mm;

            page-break-after: always;
            break-after: page;
        }

        .page:last-child {
            page-break-after: auto;
            break-after: auto;
        }

        /* =========================
           CARD BARCODE
        ========================= */

        .card {
            width: 100%;
            height: 100%;

            border: 1.5px solid #333;
            border-radius: 5px;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            padding: 5mm;

            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* =========================
           BARCODE
        ========================= */

        .barcode-img {
            width: 55mm;
            height: 55mm;

            object-fit: contain;

            margin-bottom: 4mm;
        }

        .barcode-empty {
            width: 55mm;
            height: 55mm;

            background: #eee;

            margin-bottom: 4mm;
        }

        /* =========================
           TEXT
        ========================= */

        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;

            margin-bottom: 2mm;
        }

        .sub {
            font-size: 13px;
            text-align: center;

            color: #444;
        }

        /* =========================
           PRINT
        ========================= */

        @media print {

            .no-print {
                display: none !important;
            }

            body {
                margin: 0;

                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>

</head>

<body>

    <div class="no-print">
        <button onclick="window.print()">🖨 Print</button>
    </div>


    @php
        /*
        |--------------------------------------------------------------------------
        | FLATTEN SEMUA KAMAR
        |--------------------------------------------------------------------------
        | Controller sudah mengurutkan berdasarkan kode_blok
        | kemudian nomor kamar.
        */

        $allKamars = $groups->flatten(1)->values();

        /*
        |--------------------------------------------------------------------------
        | BAGI MENJADI 6 KAMAR PER HALAMAN
        |--------------------------------------------------------------------------
        */

        $pages = $allKamars->chunk(6);
    @endphp


    @foreach ($pages as $kamars)
        <div class="page">

            @foreach ($kamars as $k)
                <div class="card">

                    {{-- BARCODE --}}
                    @if (!empty($k->img_barcode))
                        <img class="barcode-img" src="{{ asset('storage/' . $k->img_barcode) }}"
                            alt="Barcode {{ $k->kode_kamar }}">
                    @else
                        <div class="barcode-empty"></div>
                    @endif


                    {{-- NAMA BLOK --}}
                    <div class="title">
                        BLOK {{ $k->kode_blok ?? $k->lokasi_blok }}
                    </div>


                    {{-- NAMA KAMAR --}}
                    <div class="sub">
                        {{ $k->nama_kamar }}
                    </div>

                </div>
            @endforeach

        </div>
    @endforeach

</body>

</html>
