@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        /* ================== BASE STYLE ================== */
        .card .fw-bold {
            margin-bottom: 0;
        }

        .table-responsive {
            overflow-x: hidden;
            /* Disable horizontal scroll */
        }

        @media(max-width:768px) {
            h4 {
                font-size: 18px;
            }

            .card h6 {
                font-size: 13px;
            }

            .card h4 {
                font-size: 20px;
            }
        }
    </style>

    <div class="container-fluid my-3">
        <h4 class="fw-bold mb-3">Laporan Perpustakaan Enterprise</h4>

        {{-- ================= Statistik Ringkas ================= --}}
        <div class="row mb-4">
            @php
                $stats = [
                    ['label' => 'Total Buku', 'value' => $total_buku, 'color' => 'bg-primary'],
                    ['label' => 'Total Pengunjung', 'value' => $total_pengunjung, 'color' => 'bg-info'],
                    ['label' => 'Peminjaman Aktif', 'value' => $total_peminjaman, 'color' => 'bg-warning'],
                    ['label' => 'Pengembalian', 'value' => $total_pengembalian, 'color' => 'bg-success'],
                    ['label' => 'Barcode VALID', 'value' => $total_barcode_valid, 'color' => 'bg-success'],
                    ['label' => 'Barcode EXPIRED', 'value' => $total_barcode_expired, 'color' => 'bg-danger'],
                    ['label' => 'Total Kategori', 'value' => $total_kategori, 'color' => 'bg-secondary'],
                ];
            @endphp
            @foreach ($stats as $s)
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <div class="card text-center shadow-sm p-3">
                        <h6>{{ $s['label'] }}</h6>
                        <h4 class="fw-bold">{{ $s['value'] }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{-- ================= Grafik Peminjaman ================= --}}
    <div class="card shadow-sm mb-4 p-3">
        <h5 class="fw-bold mb-3">Grafik Peminjaman</h5>
        <canvas id="chartHarian" height="100"></canvas>
        <canvas id="chartBulanan" height="100" class="mt-4"></canvas>
        <canvas id="chartTahunan" height="100" class="mt-4"></canvas>
    </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const grafikHarian = @json($grafik_harian);
        const grafikBulanan = @json($grafik_bulanan);
        const grafikTahunan = @json($grafik_tahunan);

        function createChart(ctx, labels, data, title) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data,
                        borderColor: 'rgba(54,162,235,1)',
                        backgroundColor: 'rgba(54,162,235,0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: title
                        }
                    }
                }
            });
        }

        createChart(document.getElementById('chartHarian').getContext('2d'),
            grafikHarian.map(g => g.tanggal), grafikHarian.map(g => g.total), 'Peminjaman Per Hari');

        createChart(document.getElementById('chartBulanan').getContext('2d'),
            grafikBulanan.map(g => g.bulan), grafikBulanan.map(g => g.total), 'Peminjaman Per Bulan');

        createChart(document.getElementById('chartTahunan').getContext('2d'),
            grafikTahunan.map(g => g.tahun), grafikTahunan.map(g => g.total), 'Peminjaman Per Tahun');
    </script>
@endsection
