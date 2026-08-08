<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Semua Kamar</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, Helvetica, sans-serif;
            background:#fff;
        }

        /* =========================
           BUTTON PRINT
        ========================= */
        .no-print{
            margin:15px;
        }

        .no-print button{
            padding:10px 18px;
            font-size:16px;
            cursor:pointer;
        }

        /* =========================
           PAGE A4
        ========================= */
        .page{
            width:210mm;
            min-height:297mm;
            padding:15mm;

            display:flex;
            justify-content:center;
            align-items:center;

            page-break-after:always;
            break-after:page;
        }

        /* =========================
           CARD
        ========================= */
        .card{
            width:100%;
            height:100%;

            border:2px solid #333;
            border-radius:12px;

            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;

            padding:20px;
        }

        /* =========================
           BARCODE
        ========================= */
        .barcode-img{
            width:100%;
            max-width:450px;
            height:auto;
            object-fit:contain;
            margin-bottom:25px;
        }

        .barcode-empty{
            width:300px;
            height:300px;
            background:#eee;
            margin-bottom:25px;
        }

        /* =========================
           TEXT
        ========================= */
        .title{
            font-size:32px;
            font-weight:bold;
            text-align:center;
            margin-bottom:10px;
        }

        .sub{
            font-size:22px;
            text-align:center;
            color:#444;
        }

        @media print{

            .no-print{
                display:none !important;
            }

            body{
                margin:0;
                -webkit-print-color-adjust:exact;
                print-color-adjust:exact;
            }

            @page{
                size:A4;
                margin:0;
            }

        }
    </style>

</head>
<body>

<div class="no-print">
    <button onclick="window.print()">🖨 Print</button>
</div>

{{-- LOOP PER BLOK --}}
@foreach($groups as $blok => $kamars)

    {{-- LOOP KAMAR DALAM BLOK --}}
    @foreach($kamars as $k)

        <div class="page">

            <div class="card">

                {{-- BARCODE --}}
                @if(!empty($k->img_barcode))

                    <img
                        class="barcode-img"
                        src="{{ asset('storage/'.$k->img_barcode) }}"
                        alt="Barcode">

                @else

                    <div class="barcode-empty"></div>

                @endif

                {{-- NAMA BLOK --}}
                <div class="title">
                   BLOK {{ $blok }}
                </div>

                {{-- NAMA KAMAR --}}
                <div class="sub">
                    {{ $k->nama_kamar }}
                </div>

            </div>

        </div>

    @endforeach

@endforeach

</body>
</html>