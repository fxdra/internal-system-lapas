<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data HP Petugas</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            background:#eef2f7;
            font-family:Arial,sans-serif;
            padding:30px 15px;
        }

        /* ================= CARD ================= */

        .id-card{
            max-width:1100px;
            margin:auto;
            background:#fff;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,.08);
            border:1px solid #e5e7eb;
        }

        /* ================= HEADER ================= */

        .id-header{
            background:linear-gradient(135deg,#0f172a,#1e293b);
            padding:24px 30px;
            color:#fff;

            display:flex;
            align-items:center;
            gap:20px;
        }

        .logo-box{
            width:140px;
            height:90px;
            background:#fff;
            border-radius:16px;

            display:flex;
            align-items:center;
            justify-content:center;

            overflow:hidden;
            padding:10px;

            flex-shrink:0;
        }

        .logo-box img{
            width:100%;
            height:auto;
            object-fit:contain;
        }

        .title-box h2{
            font-size:28px;
            font-weight:700;
            margin-bottom:6px;
        }

        .title-box p{
            font-size:14px;
            opacity:.9;
        }
        
         /* ================= HEADER ================= */
        .upt-header{
            width:100%;
            background:#ffffff;
            text-align:center;
        
            font-size:24px;
            font-weight:800;
            color:#244280;
        
            padding:25px 20px 10px;
            letter-spacing:.5px;
            line-height:1.4;
            
            font-family: "Alfa Slab One", serif;
            font-weight: 400;
            font-style: normal;
        
            border-bottom:1px solid #e5e7eb;
        }

        /* ================= CONTENT ================= */

        .id-content{
            padding:30px;
        }

        .main-grid{
            display:grid;
            grid-template-columns:280px 1fr;
            gap:30px;
        }

        /* ================= LEFT PANEL ================= */

        .left-panel{
            background:#f8fafc;
            border-radius:20px;
            padding:20px;
            border:1px solid #e2e8f0;
        }

        .photo-box img{
            width:100%;
            border-radius:18px;
            border:4px solid #fff;
            box-shadow:0 3px 10px rgba(0,0,0,.08);
        }

        .no-image{
            width:100%;
            height:350px;
            background:#e2e8f0;
            border-radius:18px;

            display:flex;
            align-items:center;
            justify-content:center;

            color:#64748b;
            font-size:14px;
        }

        /* ================= RIGHT PANEL ================= */

        .section-title{
            font-size:20px;
            font-weight:700;
            margin-bottom:25px;
            color:#0f172a;
        }

        .info-table{
            margin-bottom:35px;
        }

        .info-row{
            display:grid;
            grid-template-columns:170px 10px 1fr;
            gap:10px;
            margin-bottom:16px;
            align-items:start;
        }

        .info-label{
            font-weight:700;
            color:#334155;
        }

        .info-value{
            color:#111827;
            font-weight:500;
        }

        /* ================= HP GRID ================= */

        .hp-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
        }

        .hp-card{
            background:#f8fafc;
            border-radius:18px;
            overflow:hidden;
            border:1px solid #e2e8f0;
            transition:.2s;
        }

        .hp-card:hover{
            transform:translateY(-3px);
        }

        .hp-image{
            width:100%;
            height:220px;
            object-fit:cover;
            background:#e5e7eb;
        }

        .hp-body{
            padding:16px;
        }

        .hp-title{
            font-size:17px;
            font-weight:700;
            color:#0f172a;
            margin-bottom:10px;
        }

        .hp-item{
            margin-bottom:8px;
            font-size:14px;
            color:#475569;
        }

        /* ================= FOOTER ================= */

        .id-footer{
            background:#f8fafc;
            padding:18px;
            text-align:center;
            color:#64748b;
            border-top:1px solid #e5e7eb;
            font-size:13px;
        }

        /* ================= MOBILE ================= */

        @media(max-width:768px){

            .main-grid{
                grid-template-columns:1fr;
            }

            .id-header{
                flex-direction:column;
                text-align:center;
            }

            .title-box h2{
                font-size:22px;
            }

            .info-row{
                grid-template-columns:120px 10px 1fr;
            }
        }

    </style>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
    
</head>

<body>

    <div class="id-card">
<div style="text-align:center;margin-top:20px;">
    <button onclick="exportAsImage()" style="
        padding:10px 18px;
        background:#0f172a;
        color:#fff;
        border:none;
        border-radius:10px;
        cursor:pointer;
        font-weight:600;
    ">
        Export Gambar
    </button>
</div>
        <!-- ================= HEADER ================= -->
        <div class="id-header">

            <div class="logo-box">

                @if($data->logo)

                    <img
                        src="{{ asset('storage/'.$data->logo) }}"
                        alt="Logo">

                @else

                    <span style="color:#64748b;font-size:12px;">
                        No Logo
                    </span>

                @endif

            </div>

            <div class="title-box">

                <h2>
                    {{ $data->title ?? 'DATA HP PETUGAS' }}
                </h2>

                <p>
                    {{ $data->subtitle ?? '-' }}
                </p>

            </div>

        </div>

        <!-- ================= NAMA UPT ================= -->
        
        <div class="upt-header">
            {{ $data->nama_upt ?? '-' }}
        </div>
        
        
        <!-- ================= CONTENT ================= -->
        <div class="id-content">

            <div class="main-grid">

                <!-- ================= LEFT ================= -->
                <div class="left-panel">

                    <div class="photo-box">

                        @if($data->foto_petugas)

                            <img
                                src="{{ asset('storage/'.$data->foto_petugas) }}"
                                alt="Foto Petugas">

                        @else

                            <div class="no-image">
                                Tidak ada foto
                            </div>

                        @endif

                    </div>

                </div>

                <!-- ================= RIGHT ================= -->
                <div class="right-panel">

                    <div class="section-title">
                        Informasi Petugas
                    </div>

                    <div class="info-table">

                        <div class="info-row">
                            <div class="info-label">Nama</div>
                            <div>:</div>
                            <div class="info-value">
                                {{ $data->nama ?? '-' }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">NIP</div>
                            <div>:</div>
                            <div class="info-value">
                                {{ $data->nip ?? '-' }}
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Jabatan</div>
                            <div>:</div>
                            <div class="info-value">
                                {{ $data->jabatan ?? '-' }}
                            </div>
                        </div>

                    </div>
                    
                    <!-- ================= HP ================= -->

<div class="section-title">
    Data Handphone
</div>

@php
    $fotoHp  = is_array($data->foto_handphone) ? $data->foto_handphone : [];
    $jenisHp = is_array($data->jenis_hp) ? $data->jenis_hp : [];
    $warnaHp = is_array($data->warna_hp) ? $data->warna_hp : [];

    // hitung data HP yang benar-benar ada
    $jumlahHp = count(array_filter($jenisHp));
@endphp

{{-- ================= JIKA HANYA 1 HP ================= --}}

@if($jumlahHp == 1)

    @foreach($jenisHp as $i => $hp)

        @if(!empty($hp))

            <div class="hp-card" style="
                max-width:300px;
            ">

                {{-- FOTO HP --}}

                @if(isset($fotoHp[$i]) && $fotoHp[$i])

                    <img
                        src="{{ asset('storage/'.$fotoHp[$i]) }}"
                        class="hp-image"
                        alt="Foto HP">

                @else

                    <div class="no-image" style="height:220px;">
                        Tidak ada foto
                    </div>

                @endif

                {{-- DETAIL HP --}}

                <div class="hp-body">

                    <div class="hp-title">
                        Handphone
                    </div>

                    <div class="hp-item">
                        <strong>Jenis :</strong>
                        {{ $jenisHp[$i] ?? '-' }}
                    </div>

                    <div class="hp-item">
                        <strong>Warna :</strong>
                        {{ $warnaHp[$i] ?? '-' }}
                    </div>

                </div>

            </div>

        @endif

    @endforeach

{{-- ================= JIKA LEBIH DARI 1 HP ================= --}}

@elseif($jumlahHp > 1)

    <div class="hp-grid">

        @foreach($jenisHp as $i => $hp)

            @if(!empty($hp))

                <div class="hp-card">

                    {{-- FOTO HP --}}

                    @if(isset($fotoHp[$i]) && $fotoHp[$i])

                        <img
                            src="{{ asset('storage/'.$fotoHp[$i]) }}"
                            class="hp-image"
                            alt="Foto HP">

                    @else

                        <div class="no-image" style="height:220px;">
                            Tidak ada foto
                        </div>

                    @endif

                    {{-- BODY CARD --}}

                    <div class="hp-body">

                        <div class="hp-title">
                            Handphone {{ $loop->iteration }}
                        </div>

                        <div class="hp-item">
                            <strong>Jenis :</strong>
                            {{ $jenisHp[$i] ?? '-' }}
                        </div>

                        <div class="hp-item">
                            <strong>Warna :</strong>
                            {{ $warnaHp[$i] ?? '-' }}
                        </div>

                    </div>

                </div>

            @endif

        @endforeach

    </div>

{{-- ================= JIKA TIDAK ADA DATA ================= --}}

@else

    <div class="no-image" style="
        height:180px;
        border-radius:20px;
    ">
        Tidak ada data handphone
    </div>

@endif

                </div>

            </div>

        </div>

        <!-- ================= FOOTER ================= -->

        <div class="id-footer">
            Data inventaris handphone petugas Banceuy
        </div>

    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
function exportAsImage() {
    const element = document.querySelector(".id-card");

    html2canvas(element, {
        scale: 2,
        useCORS: true,
        backgroundColor: "#ffffff"
    }).then(canvas => {
        const link = document.createElement("a");
        link.download = "data-hp-petugas.png";
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}
</script>
</html>