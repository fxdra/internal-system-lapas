@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        .input-error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 4px;
        }

        .input-invalid {
            border-color: #dc3545;
        }

        /* MODAL */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
        }

        .custom-modal-content {
            background: #fff;
            width: 500px;
            max-width: 95%;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* TABLE RESPONSIVE */
        .responsive-table td,
        .responsive-table th {
            white-space: normal !important;
            word-break: break-word;
        }

        .judul-col {
            max-width: 200px;
            word-wrap: break-word;
        }

        /* HP */
        @media (max-width:768px) {
            .responsive-table thead {
                display: none;
            }

            .table-responsive {
                overflow-x: hidden !important;
            }

            .responsive-table .foto-col,
            .responsive-table .kamar-col,
            .responsive-table .status-col,
            .responsive-table .col-no,
            .responsive-table .barcode-col {
                display: none;
            }

            .judul-col {
                max-width: 100%;
                font-size: 15px;
                line-height: 1.4;
            }

            .responsive-table .main-row {
                display: block;
                border-bottom: 1px solid #ddd;
                padding: 10px;
                cursor: pointer;
                background: #fff;
            }

            .responsive-table .judul-col {
                display: block;
                font-size: 16px;
            }

            .detail-row td {
                background: #f9f9f9;
            }
        }
    </style>

    <div class="container-fluid my-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Pengunjung Perpustakaan</h4>
                <small class="text-muted">Kelola data pengunjung</small>
            </div>
        </div>

        {{-- SEARCH --}}
        <input type="text" name="search" class="form-control mb-3" placeholder="Cari nama, kamar, buku...">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success d-none" id="alertSuccess">{{ session('success') }}</div>
        @endif

        {{-- FORM TAMBAH --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Pengunjung</h5>
                <form id="formPengunjung" method="POST" action="{{ route('admin.pengunjung.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">

                        {{-- BUKU --}}
                        <div class="col-lg-4" style="position:relative;">
                            <label>Buku</label>
                            <input type="text" name="buku_input" id="buku_input" class="form-control" autocomplete="off"
                                placeholder="Ketik nama buku...">
                            <input type="hidden" name="buku_id" id="buku_id">
                            <div class="input-error" id="error_buku_id"></div>
                            <div id="buku_list" class="border"
                                style="display:none; max-height:150px; overflow-y:auto; position:absolute; width:100%; background:#fff; z-index:1000;">
                            </div>
                        </div>

                        {{-- NAMA PEMINJAM --}}
                        <div class="col-lg-4 position-relative">
                            <label>Nama Peminjam</label>
                            <input type="text" name="nama_peminjam_input" id="nama_peminjam_input" class="form-control"
                                autocomplete="off" placeholder="Ketik nama...">
                            <input type="hidden" name="nama_peminjam" id="nama_peminjam">
                            <div class="input-error" id="error_nama_peminjam"></div>

                            <div id="nama_list" class="border"
                                style="display:none; max-height:150px; overflow-y:auto; position:absolute; background:#fff; z-index:1000; width:100%;">
                            </div>
                        </div>

                        {{-- KAMAR / SEL --}}
                        <div class="col-lg-4 position-relative">
                            <label>Kamar / Sel</label>
                            <input type="text" name="kamar_sel_input" id="kamar_sel_input" class="form-control"
                                autocomplete="off" placeholder="Ketik kamar / sel...">
                            <input type="hidden" name="kamar_sel" id="kamar_sel">
                            <div class="input-error" id="error_kamar_sel"></div>

                            <div id="kamar_list" class="border"
                                style="display:none; max-height:150px; overflow-y:auto; position:absolute; background:#fff; z-index:1000; width:100%;">
                            </div>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-lg-3">
                            <label>Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control">
                            <div class="input-error" id="error_tanggal_pinjam"></div>
                        </div>
                        <div class="col-lg-3">
                            <label>Tanggal Kembali</label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control">
                            <div class="input-error" id="error_tanggal_kembali"></div>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-lg-2">
                            <label>Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="PEMINJAMAN">PEMINJAMAN</option>
                                <option value="PENGEMBALIAN">PENGEMBALIAN</option>
                            </select>
                            <div class="input-error" id="error_status"></div>
                        </div>

                        <div class="col-lg-2">
                            <label>Status Barcode</label>
                            <select name="status_barcode" id="status_barcode" class="form-select">
                                <option value="VALID">VALID</option>
                                <option value="EXPIRED">EXPIRED</option>
                            </select>
                            <div class="input-error" id="error_status_barcode"></div>
                        </div>

                        {{-- FOTO --}}
                        <div class="col-lg-3">
                            <label>Foto Buku</label>
                            <input type="file" name="foto_buku" class="form-control">
                        </div>

                    </div>
                    <button class="btn btn-success mt-3">Simpan</button>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0 responsive-table">
                    <thead>
                        <tr>
                            <th class="col-no" style="width: 80px;">No</th>
                            <th>Foto Buku</th>
                            <th>Judul Buku</th>
                            <th>Nama Peminjam</th>
                            <th>Kamar/Sel</th>
                            <th>Status</th>
                            <th>Status Barcode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengunjungs as $i => $p)
                            <tr class="main-row">
                                <td class="col-no">{{ $pengunjungs->firstItem() + $i }}</td>
                                <td class="foto-col">
                                    @if ($p->foto_buku)
                                        <img src="{{ asset('storage/' . $p->foto_buku) }}"
                                            style="width:60px;height:90px;object-fit:cover">
                                    @endif
                                </td>
                                <td class="judul-col">{{ strtoupper($p->buku->judul ?? '-') }}</td>
                                <td class="nama-col">{{ strtoupper($p->nama_peminjam) }}</td>
                                <td class="kamar-col">{{ strtoupper($p->kamar_sel) }}</td>
                                <td class="status-col">
                                    <span
                                        class="badge {{ $p->status == 'PEMINJAMAN' ? 'bg-warning' : 'bg-success' }}">{{ $p->status }}</span>
                                </td>
                                <td class="barcode-col">
                                    <span
                                        class="badge {{ $p->status_barcode == 'VALID' ? 'bg-success' : 'bg-danger' }}">{{ $p->status_barcode }}</span>
                                </td>
                                <td class="aksi-col text-center">
                                    <div class="d-flex flex-row flex-md-column gap-1 justify-content-center">

                                        <span class="badge bg-warning text-dark cursor-pointer btn-edit"
                                            style="padding: 5px 8px; font-size: 0.8rem;" data-id="{{ $p->id }}"
                                            data-buku="{{ $p->buku_id }}" data-nama="{{ $p->nama_peminjam }}"
                                            data-kamar="{{ $p->kamar_sel }}" data-pinjam="{{ $p->tanggal_pinjam }}"
                                            data-kembali="{{ $p->tanggal_kembali }}" data-status="{{ $p->status }}"
                                            data-status_barcode="{{ $p->status_barcode }}">
                                            Edit
                                        </span>

                                        <span class="badge bg-danger cursor-pointer btn-delete"
                                            style="padding: 5px 8px; font-size: 0.8rem;" data-id="{{ $p->id }}">
                                            Hapus
                                        </span>

                                    </div>
                                </td>
                            </tr>
                            <tr class="detail-row" style="display:none;">
                                <td colspan="8">
                                    <div class="p-2">
                                        @if ($p->foto_buku)
                                            <img src="{{ asset('storage/' . $p->foto_buku) }}"
                                                style="width:80px;height:120px;object-fit:cover" class="mb-2">
                                        @endif
                                        <p><b>Nama:</b> {{ strtoupper($p->nama_peminjam) }}</p>
                                        <p><b>Kamar:</b> {{ strtoupper($p->kamar_sel) }}</p>
                                        <p><b>Status:</b> {{ $p->status }}</p>
                                        <p><b>Status Barcode:</b> {{ $p->status_barcode }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        {{-- MODAL EDIT --}}
        <div id="modalEdit" class="custom-modal">
            <div class="custom-modal-content">
                <div class="d-flex justify-content-between mb-2">
                    <h5>Edit Pengunjung</h5>
                    <button type="button" id="closeModal" class="btn btn-sm btn-danger">X</button>
                </div>

                <form method="POST" id="formEdit" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- BUKU --}}
                    <select name="buku_id" id="e_buku" class="form-select mb-2">
                        <option value="">-- Pilih Buku --</option>
                        @foreach ($bukus as $b)
                            <option value="{{ $b->id }}">{{ strtoupper($b->judul) }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="nama_peminjam" id="e_nama" class="form-control mb-2"
                        placeholder="Nama Peminjam">
                    <input type="text" name="kamar_sel" id="e_kamar" class="form-control mb-2"
                        placeholder="Kamar/Sel">
                    <input type="date" name="tanggal_pinjam" id="e_pinjam" class="form-control mb-2">
                    <input type="date" name="tanggal_kembali" id="e_kembali" class="form-control mb-2">

                    <select name="status" id="e_status" class="form-select mb-2">
                        <option value="PEMINJAMAN">PEMINJAMAN</option>
                        <option value="PENGEMBALIAN">PENGEMBALIAN</option>
                    </select>

                    <select name="status_barcode" id="e_status_barcode" class="form-select mb-2">
                        <option value="VALID">VALID</option>
                        <option value="EXPIRED">EXPIRED</option>
                    </select>

                    <input type="file" name="foto_buku" class="form-control mb-2">

                    <button class="btn btn-success w-100">Update</button>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {

                // ================= UPPERCASE OTOMATIS =================
                $('#buku_input,#nama_peminjam_input,#kamar_sel_input,#e_nama,#e_kamar').on('input', function() {
                    this.value = this.value.toUpperCase();
                });


                // ================= TOGGLE DETAIL HP =================
                $('.main-row').click(function() {
                    // hanya aktif di layar kecil
                    if (window.innerWidth <= 768) {
                        let detailRow = $(this).next('.detail-row');

                        // tutup semua dulu (optional biar clean)
                        $('.detail-row').not(detailRow).slideUp(150);

                        // toggle yang diklik
                        detailRow.slideToggle(150);
                    }
                });

                // ================= AUTOCOMPLETE BUKU =================
                let bukus = @json($bukus);
                let $bukuInput = $('#buku_input'),
                    $bukuHidden = $('#buku_id'),
                    $bukuList = $('#buku_list');

                $bukuInput.on('input', function() {
                    let q = $(this).val().toLowerCase().trim();
                    if (!q.length) {
                        $bukuList.hide();
                        $bukuHidden.val('');
                        return;
                    }
                    let matches = bukus.filter(b => b.judul.toLowerCase().includes(q));
                    if (!matches.length) {
                        $bukuList.hide();
                        $bukuHidden.val('');
                        return;
                    }
                    let html = matches.map(b =>
                        `<div class="p-2 buku-item" data-id="${b.id}" style="cursor:pointer;">${b.judul.toUpperCase()}</div>`
                    ).join('');
                    $bukuList.html(html).show();
                });

                $(document).on('click', '.buku-item', function() {
                    $bukuHidden.val($(this).data('id'));
                    $bukuInput.val($(this).text());
                    $bukuList.hide();
                });

                // ================= AUTOCOMPLETE NAMA =================
                let wbps = @json(App\Models\Wbp::all());
                let $namaInput = $('#nama_peminjam_input'),
                    $namaHidden = $('#nama_peminjam'),
                    $namaList = $('#nama_list');

                $namaInput.on('input', function() {
                    let q = $(this).val().toLowerCase().trim();
                    if (!q.length) {
                        $namaList.hide();
                        $namaHidden.val('');
                        return;
                    }
                    let matches = wbps.filter(w => w.nama.toLowerCase().includes(q));
                    if (!matches.length) {
                        $namaList.hide();
                        $namaHidden.val('');
                        return;
                    }
                    let html = matches.map(w =>
                        `<div class="p-2 nama-item" data-id="${w.id}" style="cursor:pointer;">${w.nama}</div>`
                    ).join('');
                    $namaList.html(html).show();
                });

                $(document).on('click', '.nama-item', function() {
                    $namaHidden.val($(this).text());
                    $namaInput.val($(this).text());
                    $namaList.hide();
                });

                // ================= AUTOCOMPLETE KAMAR =================
                let $kamarInput = $('#kamar_sel_input'),
                    $kamarHidden = $('#kamar_sel'),
                    $kamarList = $('#kamar_list');

                $kamarInput.on('input', function() {
                    let q = $(this).val().toLowerCase().trim();
                    if (!q.length) {
                        $kamarList.hide();
                        $kamarHidden.val('');
                        return;
                    }
                    let matches = wbps.filter(w => `${w.lokasi_blok} - ${w.lokasi_sel}`.toLowerCase().includes(
                        q));
                    if (!matches.length) {
                        $kamarList.hide();
                        $kamarHidden.val('');
                        return;
                    }
                    let html = matches.map(w =>
                        `<div class="p-2 kamar-item" data-id="${w.id}" data-kamar="${w.lokasi_blok} - ${w.lokasi_sel}" style="cursor:pointer;">${w.lokasi_blok} - ${w.lokasi_sel}</div>`
                    ).join('');
                    $kamarList.html(html).show();
                });

                $(document).on('click', '.kamar-item', function() {
                    let kamar = $(this).data('kamar');
                    $kamarHidden.val(kamar);
                    $kamarInput.val(kamar);
                    $kamarList.hide();
                });

                // ================= HIDE AUTOCOMPLETE =================
                $(document).click(function(e) {
                    if (!$(e.target).closest('#buku_input,#buku_list').length) $bukuList.hide();
                    if (!$(e.target).closest('#nama_peminjam_input,#nama_list').length) $namaList.hide();
                    if (!$(e.target).closest('#kamar_sel_input,#kamar_list').length) $kamarList.hide();
                });

                // ================= MODAL EDIT =================
                $('.btn-edit').click(function() {
                    let d = $(this).data();
                    $('#formEdit').attr('action', "{{ route('admin.pengunjung.update', ':id') }}".replace(
                        ':id', d.id));
                    $('#e_buku').val(d.buku);
                    $('#e_nama').val(d.nama);
                    $('#e_kamar').val(d.kamar);
                    $('#e_pinjam').val(d.pinjam);
                    $('#e_kembali').val(d.kembali);
                    $('#e_status').val(d.status);

                    // FIX STATUS BARCODE TERUPDATE
                    $('#e_status_barcode option').each(function() {
                        $(this).prop('selected', $(this).val() === d.status_barcode);
                    });

                    $('#modalEdit').fadeIn(200);
                });

                $('#closeModal').click(() => $('#modalEdit').fadeOut(200));
                $(window).click(e => {
                    if ($(e.target).is('#modalEdit')) $('#modalEdit').fadeOut(200);
                });


                // ================= DELETE DATA =================
                $('.btn-delete').click(function() {
                    let id = $(this).data('id');

                    if (confirm('Yakin ingin hapus? Data tidak bisa dikembalikan!')) {
                        $.ajax({
                            url: "{{ route('admin.pengunjung.destroy', ':id') }}".replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.success) {
                                    alert(res.message || 'Data berhasil dihapus');
                                    location.reload();
                                } else {
                                    alert(res.message || 'Tidak bisa hapus data');
                                }
                            },
                            error: function(xhr) {
                                let msg = 'Terjadi kesalahan sistem';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                alert(msg);
                            }
                        });
                    }

                });


                // ================= VALIDASI CREATE =================
                function validasiCreate() {
                    let valid = true;
                    let buku = $('#buku_id'),
                        nama = $('#nama_peminjam'),
                        kamar = $('#kamar_sel'),
                        pinjam = $('#tanggal_pinjam'),
                        kembali = $('#tanggal_kembali'),
                        status = $('#status');
                    if (buku.val() == '') {
                        setError(buku, 'Buku wajib dipilih');
                        valid = false;
                    } else clearError(buku);
                    if (nama.val().trim() == '') {
                        setError(nama, 'Nama wajib diisi');
                        valid = false;
                    } else clearError(nama);
                    if (kamar.val().trim() == '') {
                        setError(kamar, 'Kamar wajib diisi');
                        valid = false;
                    } else clearError(kamar);
                    if (pinjam.val() == '') {
                        setError(pinjam, 'Tanggal pinjam wajib diisi');
                        valid = false;
                    } else clearError(pinjam);
                    if (kembali.val() != '' && kembali.val() < pinjam.val()) {
                        setError(kembali, 'Tanggal kembali harus >= tanggal pinjam');
                        valid = false;
                    } else clearError(kembali);
                    if (!['PEMINJAMAN', 'PENGEMBALIAN'].includes(status.val())) {
                        setError(status, 'Status tidak valid');
                        valid = false;
                    } else clearError(status);
                    return valid;
                }

                // ================= VALIDASI EDIT =================
                function validasiEdit() {
                    let valid = true;
                    let buku = $('#e_buku'),
                        nama = $('#e_nama'),
                        kamar = $('#e_kamar'),
                        pinjam = $('#e_pinjam'),
                        kembali = $('#e_kembali'),
                        status = $('#e_status');
                    if (buku.val() == '') {
                        setError(buku, 'Buku wajib dipilih');
                        valid = false;
                    } else clearError(buku);
                    if (nama.val().trim() == '') {
                        setError(nama, 'Nama wajib diisi');
                        valid = false;
                    } else clearError(nama);
                    if (kamar.val().trim() == '') {
                        setError(kamar, 'Kamar wajib diisi');
                        valid = false;
                    } else clearError(kamar);
                    if (pinjam.val() == '') {
                        setError(pinjam, 'Tanggal pinjam wajib diisi');
                        valid = false;
                    } else clearError(pinjam);
                    if (kembali.val() != '' && kembali.val() < pinjam.val()) {
                        setError(kembali, 'Tanggal kembali harus >= tanggal pinjam');
                        valid = false;
                    } else clearError(kembali);
                    if (!['PEMINJAMAN', 'PENGEMBALIAN'].includes(status.val())) {
                        setError(status, 'Status tidak valid');
                        valid = false;
                    } else clearError(status);
                    return valid;
                }

                // ================= SET ERROR FUNCTIONS =================
                function setError(el, msg) {
                    el.addClass('input-invalid');
                    $('#error_' + el.attr('id')).text(msg);
                }

                function clearError(el) {
                    el.removeClass('input-invalid');
                    $('#error_' + el.attr('id')).text('');
                }

                // ================= SUBMIT HANDLER =================
                $('#formPengunjung').submit(function(e) {
                    if (!validasiCreate()) e.preventDefault();
                });
                $('#formEdit').submit(function(e) {
                    if (!validasiEdit()) e.preventDefault();
                });

            });
        </script>
    @endsection
