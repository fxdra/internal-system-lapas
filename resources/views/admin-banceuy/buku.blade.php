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

            .responsive-table .foto-col,
            .responsive-table .penulis-col,
            .responsive-table .kategori-col,
            .responsive-table .stock-col,
            .responsive-table .status-col,
            .responsive-table .col-no {
                display: none;
            }

            .judul-col {
                max-width: 100%;
                font-size: 15px;
                line-height: 1.4;
            }

            .responsive-table .aksi-col {
                display: block !important;
            }

            .aksi-wrapper {
                flex-direction: row !important;
                gap: 5px;
            }

            .aksi-wrapper .badge {
                width: 50%;
                font-size: 12px;
                padding: 5px;
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
                <h4 class="fw-bold mb-1">Buku</h4>
                <small class="text-muted">Kelola buku perpustakaan</small>
            </div>
        </div>

        {{-- SEARCH --}}
        <input type="text" name="search" class="form-control mb-3" placeholder="Cari judul, penulis, kategori...">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success d-none" id="alertSuccess">{{ session('success') }}</div>
        @endif

        {{-- FORM TAMBAH --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Tambah Buku</h5>
                <form id="formBuku" method="POST" action="{{ route('admin.buku.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label>Judul</label>
                            <input type="text" name="judul" id="judul" class="form-control">
                            <div class="input-error" id="error_judul"></div>
                        </div>
                        <div class="col-lg-3">
                            <label>Penulis</label>
                            <input type="text" name="penulis" id="penulis" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label>Publisher</label>
                            <input type="text" name="publisher" id="publisher" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label>Kategori</label>
                            <select name="kategori_id" id="kategori" class="form-select">
                                <option value="">-- Pilih --</option>
                                @foreach ($kategoris as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            <div class="input-error" id="error_kategori"></div>
                        </div>
                        <div class="col-lg-2">
                            <label>Tahun</label>
                            <input type="text" name="tahun" id="tahun" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal_publish" id="tanggal_publish" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <label>Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="TERSEDIA">TERSEDIA</option>
                                <option value="HABIS">HABIS</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi_rak" id="lokasi_rak" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <div class="col-lg-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" checked>
                                <label>Aktif</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label>Sinopsis</label>
                            <textarea name="sinopsis" id="sinopsis" class="form-control"></textarea>
                        </div>
                    </div>
                    <button class="btn btn-success mt-3">Simpan</button>
                </form>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle responsive-table">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bukus as $i => $buku)
                            <tr class="main-row">
                                <td class="col-no">{{ $bukus->firstItem() + $i }}</td>
                                <td class="foto-col">
                                    @if ($buku->foto)
                                        <img src="{{ asset('storage/' . $buku->foto) }}"
                                            style="width:60px;height:90px;object-fit:cover">
                                    @endif
                                </td>
                                <td class="judul-col fw-bold">{{ $buku->judul }}</td>
                                <td class="penulis-col">{{ $buku->penulis }}</td>
                                <td class="kategori-col">{{ $buku->kategori }}</td>
                                <td class="stock-col">{{ $buku->stock }}</td>
                                <td class="status-col">
                                    <span
                                        class="badge {{ $buku->status == 'TERSEDIA' ? 'bg-success' : 'bg-warning' }}">{{ $buku->status }}</span>
                                </td>
                                <td class="aksi-col text-center">
                                    <div class="d-flex flex-row flex-md-column gap-1 aksi-wrapper">
                                        <button class="btn btn-warning btn-sm flex-fill btn-edit"
                                            data-id="{{ $buku->id }}" data-judul="{{ $buku->judul }}"
                                            data-penulis="{{ $buku->penulis }}" data-publisher="{{ $buku->publisher }}"
                                            data-kategori="{{ $buku->kategori }}" data-tahun="{{ $buku->tahun }}"
                                            data-tanggal="{{ $buku->tanggal_publish }}" data-stock="{{ $buku->stock }}"
                                            data-status="{{ $buku->status }}" data-lokasi="{{ $buku->lokasi_rak }}"
                                            data-sinopsis="{{ $buku->sinopsis }}">
                                            Edit
                                        </button>

                                        <button class="btn btn-danger btn-sm flex-fill btn-delete"
                                            data-id="{{ $buku->id }}">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="detail-row" style="display:none;">
                                <td colspan="8">
                                    <div class="p-2">
                                        @if ($buku->foto)
                                            <img src="{{ asset('storage/' . $buku->foto) }}"
                                                style="width:80px;height:120px;object-fit:cover" class="mb-2">
                                        @endif
                                        <p><b>Penulis:</b> {{ $buku->penulis }}</p>
                                        <p><b>Kategori:</b> {{ $buku->kategori }}</p>
                                        <p><b>Stock:</b> {{ $buku->stock }}</p>
                                        <p><b>Status:</b> {{ $buku->status }}</p>
                                        <p><b>Lokasi:</b> {{ $buku->lokasi_rak }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEdit" class="custom-modal">
        <div class="custom-modal-content">
            <div class="d-flex justify-content-between mb-2">
                <h5>Edit Buku</h5>
                <button type="button" id="closeModal" class="btn btn-sm btn-danger">X</button>
            </div>

            <form method="POST" id="formEdit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="e_id" name="id">

                <input type="text" name="judul" id="e_judul" class="form-control mb-2">
                <input type="text" name="penulis" id="e_penulis" class="form-control mb-2">
                <input type="text" name="publisher" id="e_publisher" class="form-control mb-2">

                <select name="kategori" id="e_kategori" class="form-select mb-2">
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->nama }}">{{ $k->nama }}</option>
                    @endforeach
                </select>

                <input type="text" name="tahun" id="e_tahun" class="form-control mb-2">
                <input type="date" name="tanggal_publish" id="e_tanggal" class="form-control mb-2">
                <input type="number" name="stock" id="e_stock" class="form-control mb-2">

                <select name="status" id="e_status" class="form-select mb-2">
                    <option value="TERSEDIA">TERSEDIA</option>
                    <option value="DIPINJAM">DIPINJAM</option>
                </select>

                <input type="text" name="lokasi_rak" id="e_lokasi" class="form-control mb-2">
                <textarea name="sinopsis" id="e_sinopsis" class="form-control mb-2"></textarea>

                <input type="file" name="foto" class="form-control mb-2">

                <button class="btn btn-success w-100">Update</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // ================= UPPERCASE OTOMATIS =================
            $('#judul,#penulis,#publisher,#lokasi_rak,#e_judul,#e_penulis,#e_publisher,#e_lokasi').on('input',
                function() {
                    this.value = this.value.toUpperCase();
                });

            // ================= MODAL EDIT =================
            $(document).on('click', '.btn-edit', function() {
                let data = $(this).data();
                let url = "{{ route('admin.buku.update', ':id') }}".replace(':id', data.id);
                $('#formEdit').attr('action', url);

                $('#e_id').val(data.id);
                $('#e_judul').val(data.judul);
                $('#e_penulis').val(data.penulis);
                $('#e_publisher').val(data.publisher);
                $('#e_kategori').val(data.kategori);
                $('#e_tahun').val(data.tahun);
                $('#e_tanggal').val(data.tanggal);
                $('#e_stock').val(data.stock);
                $('#e_status').val(data.status);
                $('#e_lokasi').val(data.lokasi);
                $('#e_sinopsis').val(data.sinopsis);

                $('#modalEdit').fadeIn(200);
            });

            $('#closeModal').click(() => $('#modalEdit').fadeOut(200));
            $(window).click(e => {
                if ($(e.target).is('#modalEdit')) $('#modalEdit').fadeOut(200);
            });

            // ================= DELETE DATA =================
            $(document).on('click', '.btn-delete', function() {
                let id = $(this).data('id');
                if (confirm('Yakin ingin menghapus data buku ini?')) {
                    $.ajax({
                        url: "{{ route('admin.buku.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            alert(res.message || 'Data berhasil dihapus');
                            location.reload();
                        },
                        error: function(xhr) {
                            let msg = 'Terjadi kesalahan sistem';
                            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr
                                .responseJSON.message;
                            alert(msg);
                        }
                    });
                }
            });

            // ================= VALIDASI CREATE =================
            function validasiCreate() {
                let valid = true;
                let judul = $('#judul'),
                    kategori = $('#kategori'),
                    tanggal = $('#tanggal_publish'),
                    lokasi = $('#lokasi_rak'),
                    stock = $('#stock');

                if (judul.val().trim() == '') {
                    setError(judul, 'Judul wajib diisi');
                    valid = false;
                } else clearError(judul);
                if (kategori.val().trim() == '') {
                    setError(kategori, 'Kategori wajib dipilih');
                    valid = false;
                } else clearError(kategori);
                if (tanggal.val() == '') {
                    setError(tanggal, 'Tanggal wajib diisi');
                    valid = false;
                } else clearError(tanggal);
                if (lokasi.val().trim() == '') {
                    setError(lokasi, 'Lokasi wajib diisi');
                    valid = false;
                } else clearError(lokasi);
                if (stock.val() === '' || stock.val() < 0) {
                    setError(stock, 'Stock tidak valid');
                    valid = false;
                } else clearError(stock);

                return valid;
            }

            // ================= VALIDASI EDIT =================
            function validasiEdit() {
                let valid = true;
                let judul = $('#e_judul'),
                    kategori = $('#e_kategori'),
                    tanggal = $('#e_tanggal'),
                    lokasi = $('#e_lokasi'),
                    stock = $('#e_stock');

                if (judul.val().trim() == '') {
                    setError(judul, 'Judul wajib diisi');
                    valid = false;
                } else clearError(judul);
                if (kategori.val().trim() == '') {
                    setError(kategori, 'Kategori wajib dipilih');
                    valid = false;
                } else clearError(kategori);
                if (tanggal.val() == '') {
                    setError(tanggal, 'Tanggal wajib diisi');
                    valid = false;
                } else clearError(tanggal);
                if (lokasi.val().trim() == '') {
                    setError(lokasi, 'Lokasi wajib diisi');
                    valid = false;
                } else clearError(lokasi);
                if (stock.val() === '' || stock.val() < 0) {
                    setError(stock, 'Stock tidak valid');
                    valid = false;
                } else clearError(stock);

                return valid;
            }

            // ================= SET ERROR =================
            function setError(el, msg) {
                el.addClass('input-invalid');
                let id = el.attr('id');
                if ($('#error_' + id).length) $('#error_' + id).text(msg);
            }

            function clearError(el) {
                el.removeClass('input-invalid');
                let id = el.attr('id');
                if ($('#error_' + id).length) $('#error_' + id).text('');
            }

            // ================= SUBMIT =================
            $('#formBuku').submit(function(e) {
                if (!validasiCreate()) e.preventDefault();
            });
            $('#formEdit').submit(function(e) {
                if (!validasiEdit()) e.preventDefault();
            });
        });

        // ================= TOGGLE DETAIL ROW =================
        $(document).on('click', '.main-row', function() {
            // Hanya aktif untuk layar kecil (HP)
            if ($(window).width() <= 768) {
                $(this).next('.detail-row').slideToggle(200);
            }
        });
    </script>
@endsection
