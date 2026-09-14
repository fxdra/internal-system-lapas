@php
    use Illuminate\Support\Facades\Auth;

    $user = Auth::guard('admin')->user();

    $fullAccess = $user && in_array($user->role, ['superadmin', 'admin', 'kplp', 'ka. kplp']);
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mutasi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        /* 🔥 CARD FIX BIAR TIDAK MELEBAR LIAR */
        .card {
            width: 100%;
            max-width: 100%;
            border-radius: 8px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            table-layout: auto;
        }

        .signature-box {
            width: 320px;
            margin-left: auto;
            margin-top: 60px;
            text-align: center;
        }

        .signature-space {
            height: 90px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .filter-info {
            font-size: 14px;
            color: #666;
        }

        /* ====================== PRINT ====================== */
        @media print {

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                box-sizing: border-box !important;
            }

            @page {
                size: A4 landscape;
                margin: 3mm;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                color: #000 !important;
                font-family: Arial, Helvetica, sans-serif !important;
                font-size: 10px !important;
                line-height: 1.2 !important;
            }

            .header-block,
            .no-print,
            .card-x {
                display: none !important;
            }

            .total-perkamar-new {
                display: none !important;
            }

            .container-fluid {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /*==================================================
      GRID
    ==================================================*/

            .print-grid {

                display: grid !important;

                grid-template-columns: repeat(6, minmax(0, 1fr)) !important;

                column-gap: 1.5mm !important;

                row-gap: 2mm !important;

                align-items: start !important;

            }

            /*==================================================
      CARD
    ==================================================*/

            .print-grid>.card {

                width: 100% !important;

                margin: 0 !important;

                padding: 0 !important;

                background: transparent !important;

                border: none !important;

                box-shadow: none !important;

                border-radius: 0 !important;

                page-break-inside: avoid !important;

                break-inside: avoid !important;

            }

            .card-header {

                width: 96% !important;

                margin: 0 auto 4px auto !important;

                padding: 0 !important;

                background: none !important;

                border: none !important;

                text-align: center !important;

                font-size: 10px !important;

                font-weight: 700 !important;

                line-height: 1.25 !important;

                color: #000 !important;

            }

            .card-header * {

                color: #000 !important;

                font-size: 10px !important;

                font-weight: 700 !important;

            }

            .card-body {

                padding: 0 !important;

                margin: 0 !important;

                background: none !important;

                border: none !important;

            }

            /*==================================================
      TABLE
    ==================================================*/

            .table-responsive {

                width: 100% !important;

                overflow: visible !important;

                margin: 0 !important;

                padding: 0 !important;

            }

            table {

                display: table !important;

                width: 96% !important;

                margin: 0 auto !important;

                table-layout: fixed !important;

                border-collapse: collapse !important;

            }

            .table {

                margin-bottom: 0 !important;

            }

            thead {
                display: table-header-group !important;
            }

            tbody {
                display: table-row-group !important;
            }

            tfoot {
                display: table-footer-group !important;
            }

            tr {

                display: table-row !important;

                page-break-inside: avoid !important;

            }

            th,
            td {

                display: table-cell !important;

                border: 0.8px solid #555 !important;

                padding: 2px !important;

                height: 18px !important;

                line-height: 1.2 !important;
                text-align: center !important;
                vertical-align: middle !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                font-size: 10px !important;
            }

            /*==================================================
      LEBAR KOLOM
    ==================================================*/

            th:nth-child(1),
            td:nth-child(1) {
                width: 32% !important;
            }

            th:nth-child(2),
            td:nth-child(2),
            th:nth-child(3),
            td:nth-child(3),
            th:nth-child(4),
            td:nth-child(4),
            th:nth-child(5),
            td:nth-child(5) {
                width: 17% !important;

            }

            thead th {
                background: #efefef !important;
                color: #000 !important;
                font-weight: 700 !important;
            }

            tfoot tr {

                display: table-row !important;

            }

            tfoot td,
            tfoot th {

                display: table-cell !important;
                background: #f5f5f5 !important;
                color: #000 !important;
                font-size: 10px !important;
                font-weight: 700 !important;
            }

            .badge {
                display: inline !important;
                background: none !important;
                border: none !important;
                color: #000 !important;
                padding: 0 !important;
                font-size: 10px !important;
                font-weight: 700 !important;
            }

            .desktop-row {
                display: table-row !important;
            }

            .mobile-row,
            .mobile-detail-row {
                display: none !important;
            }

            /*==================================================
      BOOTSTRAP
    ==================================================*/
            .bg-dark,
            .bg-primary,
            .bg-success,
            .bg-danger,
            .bg-secondary,
            .table-dark,
            .table-light {
                background: transparent !important;
                color: #000 !important;
            }

            .text-white {
                color: #000 !important;
            }

            /*==================================================
      SIGNATURE
    ==================================================*/
            .signature-box {
                width: 170px !important;
                margin-left: auto !important;
                margin-top: 10px !important;
                text-align: center !important;
                font-size: 10px !important;
            }

            .signature-space {
                height: 35px !important;
            }

        }
    </style>

    <style>
        .custom-breadcrumb {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .custom-breadcrumb .breadcrumb {
            margin-bottom: 0;
        }

        .custom-breadcrumb .breadcrumb-item a {
            text-decoration: none;
            color: #0d6efd;
            font-weight: 500;
        }

        .custom-breadcrumb .breadcrumb-item.active {
            color: #6c757d;
        }
    </style>
</head>

<body>

    <div class="container-fluid py-4 ">

        <div class="card border-2 shadow-sm mb-4 header-block">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center">

                        <div class="ms-3">
                            <h4 class="mb-1 fw-bold">
                                DATA MUTASI WBP
                            </h4>

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="/admin-banceuy">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Data Mutasi
                                    </li>
                                </ol>
                            </nav>
                        </div>

                    </div>

                    <div class="d-flex gap-2">

                        @if (in_array(Auth::guard('admin')->user()?->role, ['superadmin', 'admin', 'kplp', 'ka. kplp']))
                            <form action="{{ route('snapshot.reset') }}" method="POST"
                                onsubmit="return confirm('Yakin ingin mereset snapshot hari ini? Semua data mutasi hari ini akan dihapus.')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-rotate-left me-2"></i>
                                    Reset Snapshot
                                </button>


                            </form>
                        @endif

                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print me-2"></i>
                            🖨 Print
                        </button>

                    </div>

                </div>

            </div>
        </div>

        <div class="card mb-4 shadow-sm no-print">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-3 align-items-end">

                        {{-- FILTER --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Filter
                            </label>

                            <select name="filter" id="filter" class="form-select">

                                <option value="today" {{ request('filter', 'today') == 'today' ? 'selected' : '' }}>
                                    Hari Ini
                                </option>

                                <option value="yesterday" {{ request('filter') == 'yesterday' ? 'selected' : '' }}>
                                    Kemarin
                                </option>

                                <option value="3days" {{ request('filter') == '3days' ? 'selected' : '' }}>
                                    3 Hari Terakhir
                                </option>

                                <option value="7days" {{ request('filter') == '7days' ? 'selected' : '' }}>
                                    7 Hari Terakhir
                                </option>

                                <option value="1month" {{ request('filter') == '1month' ? 'selected' : '' }}>
                                    1 Bulan Terakhir
                                </option>

                                <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>
                                    Pilih Rentang Tanggal
                                </option>

                            </select>

                        </div>


                        {{-- DARI TANGGAL --}}
                        <div class="col-md-3 custom-range"
                            style="{{ request('filter') == 'custom' ? '' : 'display:none' }}">

                            <label class="form-label">
                                Dari Tanggal
                            </label>

                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date') }}">

                        </div>


                        {{-- SAMPAI TANGGAL --}}
                        <div class="col-md-3 custom-range"
                            style="{{ request('filter') == 'custom' ? '' : 'display:none' }}">

                            <label class="form-label">
                                Sampai Tanggal
                            </label>

                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date') }}">

                        </div>


                        {{-- BUTTON --}}
                        <div class="col-md-2">

                            <button type="submit" class="btn btn-primary w-100">

                                Terapkan

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- ALERT --}}
        @if ($grouped->flatten()->count() == 0)
            <div class="alert alert-warning">
                Tidak ada data mutasi pada periode ini.
            </div>
        @endif


        <div class="print-grid">

            {{-- DATA GROUP --}}
            @foreach ($grouped as $blok => $items)
                <div class="card mb-4 shadow-sm">

                    <div class="card-header bg-dark text-white">
                        <div class="d-flex justify-content-between">
                            <strong>Blok {{ $blok }}</strong>
                            <span class="total-perkamar-new">Total Kamar: {{ $items->count() }}</span>
                        </div>
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover table-striped mb-0">

                                <thead class="table-light">
                                    <tr>
                                        <th>KMR</th>
                                        <th>JML</th>
                                        <th>+</th>
                                        <th>-</th>
                                        <th>HSL</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($items as $row)
                                        {{-- DESKTOP --}}
                                        <tr class="desktop-row">
                                            <td>
                                                @php
                                                    preg_match('/\d+/', $row->lokasi_sel, $match);
                                                @endphp

                                                {{ $match[0] ?? '-' }}
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $row->sebelum }}</span></td>
                                            <td><span class="badge bg-success">{{ $row->masuk }}</span></td>
                                            <td><span class="badge bg-danger">{{ $row->keluar }}</span></td>
                                            <td><span class="badge bg-primary">{{ $row->sesudah }}</span></td>

                                        </tr>
                                    @endforeach

                                </tbody>

                                <tfoot>
                                    <tr class="table-dark fw-bold">
                                        <td>JUMLAH</td>
                                        <td>{{ $items->sum('sebelum') }}</td>
                                        <td>{{ $items->sum('masuk') }}</td>
                                        <td>{{ $items->sum('keluar') }}</td>
                                        <td>{{ $items->sum('sesudah') }}</td>

                                    </tr>
                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        {{-- GRAND TOTAL --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">
                Perhitungan Data Mutasi
            </div>

            <div class="card-body">

                <table class="table table-bordered mb-0">
                    <tr>
                        <th>Sebelum</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Sesudah</th>
                    </tr>

                    <tr class="fw-bold">
                        <td>{{ $grand['sebelum'] }}</td>
                        <td>{{ $grand['masuk'] }}</td>
                        <td>{{ $grand['keluar'] }}</td>
                        <td>{{ $grand['sesudah'] }}</td>
                    </tr>
                </table>

            </div>

        </div>


        <div class="card shadow-sm mb-4 card-x">

            <div class="card-header bg-success text-white">
                GRAND TOTAL WBP
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col">
                        Aktif :
                        <b>{{ $countAktif }}</b>
                    </div>

                    <div class="col">
                        BON :
                        <b>{{ $countBon }}</b>
                    </div>

                    <div class="col">
                        Sakit :
                        <b>{{ $countSakit }}</b>
                    </div>

                    <div class="col">
                        Pindah :
                        <b>{{ $countPindah }}</b>
                    </div>

                    <div class="col">
                        Pulang :
                        <b>{{ $countPulang }}</b>
                    </div>

                    <div class="col">
                        Meninggal :
                        <b>{{ $countMeninggal }}</b>
                    </div>

                </div>

                <hr>

                <h5>
                    Total WBP:
                    {{ $grandWbp }}
                </h5>

            </div>

        </div>

        {{-- SIGNATURE --}}
        <div class="signature-box">

            <div class="fw-semibold">
                Bandung, {{ $date }}
            </div>
            <div style="height:7px"></div>
            <div class="fw-semibold">
                Mengetahui
            </div>


            <div class="fw-bold">Ka. KPLP</div>

            <div class="signature-space"></div>

            <div class="signature-name">
                ANDHIKA SAPUTRA
            </div>


        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const filter = document.getElementById('filter');

            function toggleCustomDate() {

                document
                    .querySelectorAll('.custom-range')
                    .forEach(el => {

                        el.style.display =
                            filter?.value === 'custom' ?
                            'block' :
                            'none';

                    });

            }

            // trigger saat ganti filter
            filter?.addEventListener(
                'change',
                toggleCustomDate
            );

            // trigger saat load pertama
            toggleCustomDate();


            // =========================
            // MOBILE EXPAND ROW
            // =========================
            document
                .querySelectorAll('.mobile-row')
                .forEach(function(row) {

                    row.addEventListener(
                        'click',
                        function() {

                            const detail =
                                row.nextElementSibling;

                            if (
                                detail.style.display ===
                                'table-row'
                            ) {

                                detail.style.display =
                                    'none';

                                row.classList.remove(
                                    'active'
                                );

                            } else {

                                detail.style.display =
                                    'table-row';

                                row.classList.add(
                                    'active'
                                );

                            }

                        }
                    );

                });

        });
    </script>

</body>

</html>
