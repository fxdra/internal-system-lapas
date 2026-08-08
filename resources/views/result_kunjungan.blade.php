@extends('startup_view.main') {{-- sesuaikan layout kamu --}}

@section('content')

<style>



#sesiContainer {
    padding-left: 12px;
    padding-right: 12px;
}

/* ===============================
   CARD SESI (GLASS MODERN)
================================ */
.custom-card {
    background: #69777e;
    backdrop-filter: blur(12px);
    border-radius: 18px;
    transition: all .25s ease;
}

.custom-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,.25);
}

/* ===============================
   CARD BODY
================================ */
.custom-card .card-body {
    padding: 20px 16px;
}

/* ===============================
   TITLE SESI
================================ */
.custom-card h5.card-title {
    font-size: 1.1rem;
    letter-spacing: .5px;
}

/* ===============================
   JAM KUNJUNGAN
================================ */
.custom-card p {
    font-size: .9rem;
    color: rgba(255,255,255,.85);
}

/* ===============================
   JUMLAH PENGUNJUNG
================================ */
.custom-card h2 {
    font-size: 2rem;
    margin-bottom: 4px;
}

/* ===============================
   BADGE
================================ */
.custom-card .badge {
    font-size: .75rem;
    padding: 6px 12px;
    border-radius: 999px;
}

/* ===============================
   BUTTON PILIH
================================ */
.btn-sesi-siang {
    border-radius: 999px;
    padding: 8px 22px;
    font-weight: 600;
    transition: all .2s ease;
}

.btn-sesi-siang:hover {
    transform: scale(1.05);
}

/* ===============================
   ALERT CATATAN
================================ */
.alert-warning {
    background: rgba(255, 193, 7, .12);
    border: 1px solid rgba(255, 193, 7, .35);
    color: #fff;
}

/* ===============================
   MODAL CLEAN LOOK
================================ */
.modal-content {
    border-radius: 16px;
    border: none;
}

/* ===============================
   INPUT DATE
================================ */
input[type="date"] {
    border-radius: 12px;
    padding: 10px 14px;
}

/* ===============================
   RESPONSIVE MOBILE
================================ */
@media (max-width: 576px) {

    .custom-card h2 {
        font-size: 1.6rem;
    }

    .custom-card h5.card-title {
        font-size: 1rem;
    }

    .custom-card p {
        font-size: .85rem;
    }

    .btn-sesi-siang {
        padding: 6px 18px;
        font-size: .85rem;
    }

    .alert-warning {
        font-size: .85rem;
    }
}



    /* ===================================================== */
    /* =================== BARCODE UI ====================== */
    /* ===================================================== */
    .barcode-wrap {
        width: 100%;
    }

    .barcode-box {
        width: clamp(120px, 60vw, 260px); /* responsive */
        aspect-ratio: 1 / 1;              /* selalu kotak */
        background: #fff;
        border-radius: 16px;
        padding: 10px;
        border: 1px solid rgba(0, 0, 0, .08);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .barcode-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    /* HP super kecil */
    @media (max-width: 360px) {
        .barcode-box {
            width: clamp(100px, 70vw, 220px);
            padding: 8px;
            border-radius: 14px;
        }
    }

    /* ===================================================== */
    /* ================== RESPONSIVE HP ==================== */
    /* ===================================================== */
    @media (max-width: 576px) {

      

        .titip-barang {
            background: #111010;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    }
</style>

    <div class="container my-5">
                <div class="card shadow-lg border-0 custom-card">
                    <div class="card-body p-4">
        
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1">BUKTI PENDAFTARAN KUNJUNGAN</h4>
                        <p class="text-muted mb-0">Data berhasil disimpan & QR Code berhasil dibuat</p>
                    </div>
        
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
        
                    <div class="row g-4">
        
                        {{-- QR CODE --}}
                        <div class="col-md-4 text-center">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold mb-3">QR CODE</h6>
        
                                @if($pengunjung->img_barcode)
                                    <img src="{{ asset('storage/'.$pengunjung->img_barcode) }}"
                                         class="img-fluid"
                                         style="max-width: 220px;"
                                         alt="QR Code">
                                @else
                                    <p class="text-danger mb-0">QR belum tersedia</p>
                                @endif
        
                                <div class="mt-3">
                                    <div class="small text-muted">ID Barcode</div>
                                    <div class="fw-bold">{{ $pengunjung->id_barcode }}</div>
                                </div>
        
                                @if($pengunjung->img_barcode)
                                    <div class="mt-3">
                                        <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
                                            🖨 Cetak
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
        
                        {{-- DETAIL --}}
                        <div class="col-md-8">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold mb-3">DETAIL KUNJUNGAN</h6>
        
                                <div class="row g-3">
        
                                    {{-- DATA WBP --}}
                                    <div class="col-12">
                                        <div class="fw-bold mb-2">Data WBP</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">No. Registerasi</div>
                                        <div class="fw-bold">{{ $pengunjung->wbp->no_reg_instansi ?? '-' }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Nama WBP</div>
                                        <div class="fw-bold">{{ $pengunjung->wbp->nama ?? '-' }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Blok</div>
                                        <div class="fw-bold">{{ $pengunjung->wbp->lokasi_blok ?? '-' }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Kamar / Sel</div>
                                        <div class="fw-bold">{{ $pengunjung->wbp->lokasi_sel ?? '-' }}</div>
                                    </div>
        
                                    <hr class="my-2">
        
                                    {{-- DATA PENGUNJUNG --}}
                                    <div class="col-12">
                                        <div class="fw-bold mb-2">Data Pengunjung</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Nama Pengunjung</div>
                                        <div class="fw-bold">{{ $pengunjung->nama_pengunjung }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">No. Identitas</div>
                                        <div class="fw-bold">{{ $pengunjung->nik_pengunjung }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Tanggal Kunjungan</div>
                                        <div class="fw-bold">
                                            {{ \Carbon\Carbon::parse($pengunjung->tanggal_kunjungan)->locale('id')->translatedFormat('l, d F Y') }}
                                        </div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Sesi Kunjungan</div>
                                        <div class="fw-bold">{{ $pengunjung->sesi_kunjungan ?? '-' }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Jam Kunjungan</div>
                                        <div class="fw-bold">
                                            @php
                                                if ($pengunjung->sesi_kunjungan === 'PAGI') {
                                                    $jamKunjungan = '08:00 - 12:00';
                                                } elseif ($pengunjung->sesi_kunjungan === 'SIANG') {
                                                    $jamKunjungan = '13:00 - 15:00';
                                                } else {
                                                    $jamKunjungan =
                                                        \Carbon\Carbon::parse($pengunjung->created_at)->format('H:i') .
                                                        ' - Selesai';
                                                }
                                            @endphp
                                            {{ $jamKunjungan }} WIB
                                        </div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Catatan</div>
                                        <span class="badge bg-success">
                                            Harap tunjukkan QR ke petugas
                                        </span>
                                    </div>
        
                                </div>
                            </div>
                        </div>
        
                        {{-- DATA PENGIKUT --}}
                        <div class="col-12">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold mb-3">DATA PENGIKUT</h6>
        
                                @if ($pengunjung->pengikut->count())
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 60px;">No</th>
                                                    <th>Nama Pengikut</th>
                                                    <th>No. Identitas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pengunjung->pengikut as $i => $p)
                                                    <tr>
                                                        <td>{{ $i + 1 }}</td>
                                                        <td class="fw-bold">{{ $p->nama_pengikut }}</td>
                                                        <td>{{ $p->nik_pengikut }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        Tidak ada pengikut.
                                    </div>
                                @endif
        
                                <div class="mt-3 text-end">
                                    <a href="/" class="btn btn-secondary">
                                        Kembali
                                    </a>
                                </div>
                            </div>
                        </div>
        
                    </div>
        
                </div>
            </div>
        </div>
   
@endsection
