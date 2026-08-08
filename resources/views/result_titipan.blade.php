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
    background: rgba(255, 255, 255, 0.08);
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
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
        
                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1">HASIL TITIP BARANG</h4>
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
        
                                @if($titip->img_barcode)
                                    <img src="{{ asset('storage/'.$titip->img_barcode) }}"
                                         class="img-fluid"
                                         style="max-width: 220px;"
                                         alt="QR Code">
                                @else
                                    <p class="text-danger mb-0">QR belum tersedia</p>
                                @endif
        
                                <div class="mt-3">
                                    <div class="small text-muted">ID Barcode</div>
                                    <div class="fw-bold">{{ $titip->id_barcode }}</div>
                                </div>
        
                                @if($titip->img_barcode)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/'.$titip->img_barcode) }}"
                                           class="btn btn-outline-primary btn-sm"
                                           download>
                                            Download QR
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
        
                        {{-- DETAIL --}}
                        <div class="col-md-8">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold mb-3">DETAIL TITIP BARANG</h6>
        
                                <div class="row g-3">
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Tanggal Kunjungan</div>
                                        <div class="fw-bold">{{ $titip->tanggal_kunjungan }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Sesi Kunjungan</div>
                                        <div class="fw-bold">{{ $titip->sesi_kunjungan }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">No Reg Instansi</div>
                                        <div class="fw-bold">{{ $titip->no_reg_instansi }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Nama WBP</div>
                                        <div class="fw-bold">{{ $titip->nama_wbp }}</div>
                                    </div>
        
                                    @if($titip->wbp)
                                        <div class="col-md-6">
                                            <div class="small text-muted">WBP ID</div>
                                            <div class="fw-bold">{{ $titip->wbp->id }}</div>
                                        </div>
                                    @endif
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Status Barcode</div>
                                        <span class="badge bg-warning text-dark">
                                            {{ $titip->status_barcode }}
                                        </span>
                                    </div>
        
                                    <hr class="my-2">
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Jenis Identitas</div>
                                        <div class="fw-bold">{{ $titip->jenis_identitas }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">No Identitas</div>
                                        <div class="fw-bold">{{ $titip->nik_pengunjung }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Nama Pengunjung</div>
                                        <div class="fw-bold">{{ $titip->nama_pengunjung }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Jenis Kelamin</div>
                                        <div class="fw-bold">{{ $titip->jenis_kelamin }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">No WhatsApp</div>
                                        <div class="fw-bold">{{ $titip->no_wa }}</div>
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Hubungan</div>
                                        <div class="fw-bold">{{ $titip->hubungan_pengunjung }}</div>
                                    </div>
        
                                    <div class="col-12">
                                        <div class="small text-muted">Alamat Pengunjung</div>
                                        <div class="fw-bold">{{ $titip->alamat_pengunjung }}</div>
                                    </div>
        
                                    <hr class="my-2">
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Foto Identitas</div>
                                        @if($titip->foto_ktp)
                                            <a href="{{ asset('storage/'.$titip->foto_ktp) }}" target="_blank"
                                               class="btn btn-sm btn-outline-secondary mt-1">
                                                Lihat Foto
                                            </a>
                                        @endif
                                    </div>
        
                                    <div class="col-md-6">
                                        <div class="small text-muted">Foto Selfie</div>
                                        @if($titip->foto_selfie)
                                            <a href="{{ asset('storage/'.$titip->foto_selfie) }}" target="_blank"
                                               class="btn btn-sm btn-outline-secondary mt-1">
                                                Lihat Foto
                                            </a>
                                        @endif
                                    </div>
        
                                </div>
                            </div>
                        </div>
        
                        {{-- LIST BARANG --}}
                        <div class="col-12">
                            <div class="p-3 border rounded">
                                <h6 class="fw-bold mb-3">LIST BARANG YANG DITITIPKAN</h6>
        
                                @if($titip->items && $titip->items->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 60px;">No</th>
                                                    <th>Barang Dari</th>
                                                    <th>Jenis Barang</th>
                                                    <th>Jumlah</th>
                                                    <th style="width: 180px;">Foto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($titip->items as $i => $item)
                                                    <tr>
                                                        <td>{{ $i+1 }}</td>
                                                        <td class="text-uppercase fw-bold">{{ $item->barang_dari }}</td>
                                                        <td>{{ $item->jenis_barang }}</td>
                                                        <td>{{ $item->jumlah_barang }}</td>
                                                        <td>
                                                            @if($item->foto_barang)
                                                                <a href="{{ asset('storage/'.$item->foto_barang) }}" target="_blank"
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    Lihat Foto
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        Tidak ada data barang titipan.
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
