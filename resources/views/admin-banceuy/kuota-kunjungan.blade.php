@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        /* ================== CSS MODAL ================== */
        .jq-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 15px;
        }

        .jq-modal-overlay.active {
            display: flex;
        }

        .jq-modal-card {
            background: #fff;
            width: 100%;
            max-width: 700px;
            border-radius: 14px;
            overflow: hidden;
        }

        .jq-modal-header {
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .jq-modal-body {
            padding: 15px;
            max-height: 75vh;
            overflow-y: auto;
        }

        .jq-close {
            border: none;
            background: transparent;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
        }

        /* Hilangkan teks "Showing 1 to ..." pagination bootstrap */
        div.d-none.flex-sm-fill.d-sm-flex.align-items-sm-center.justify-content-sm-between p {
            display: none !important;
        }
    </style>

    {{-- ================= KUOTA KUNJUNGAN ================= --}}
    <div class="container my-5">
        <div class="card shadow-sm">
            <div
                class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>Kuota Kunjungan</span>

                <input type="text" id="searchKuota" class="form-control form-control-sm"
                    placeholder="Cari tanggal / keterangan..." style="max-width: 320px"
                    value="{{ request('search') }}">
            </div>

            <div class="card-body">
                <button class="btn btn-success mb-3" id="btnTambahKuota">Tambah Kuota Kunjungan</button>

                <div id="kuotaWrapper">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal / Hari</th>
                                    <th>Kuota</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="text-center">
                                @forelse ($kuota as $k)
                                    <tr>
                                        <td>
                                            {{ ($kuota->currentPage() - 1) * $kuota->perPage() + $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($k->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                                        </td>
                                        <td>{{ $k->kuota }}</td>
                                        <td>{{ $k->keterangan }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-info btn-sm btn-edit-kuota mx-2"
                                                    data-id="{{ $k->id }}">Edit</button>

                                                <button type="button" class="btn btn-danger btn-sm btn-hapus-kuota mx-2"
                                                    data-id="{{ $k->id }}">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Data tidak tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($kuota->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $kuota->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH / EDIT KUOTA ================= --}}
    <div class="jq-modal-overlay" id="kuotaModal">
        <div class="jq-modal-card shadow-lg">
            <div class="jq-modal-header">
                <h5 id="kuotaModalTitle" class="fw-bold mb-0">Tambah Kuota Kunjungan</h5>
                <button type="button" class="jq-close">&times;</button>
            </div>

            <div class="jq-modal-body px-4 py-3">
                <form id="formKuota" autocomplete="off">
                    @csrf
                    <input type="hidden" id="kuota_id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                        <input type="date" id="tanggal" class="form-control" name="tanggal" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Kuota</label>
                        <input type="number" id="kuota" class="form-control" min="1"
                            placeholder="Masukkan jumlah kuota" name="kuota" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan <span class="text-muted">(opsional)</span></label>
                        <textarea id="keterangan" rows="3" class="form-control"
                            placeholder="Contoh: Kuota tambahan hari libur" name="keterangan"></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button class="btn btn-primary btn-lg">💾 Simpan Kuota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {

            // ================== CSRF ==================
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ================== MODAL OPEN / CLOSE ==================
            const openModal = (modalId) => $(modalId).addClass('active');
            const closeModal = (modalId) => $(modalId).removeClass('active');

            // Close modal klik tombol
            $(document).on('click', '.jq-close', function() {
                closeModal('#kuotaModal');
            });

            // Close modal klik overlay
            $(document).on('click', '.jq-modal-overlay', function(e) {
                if (e.target === this) closeModal('#' + this.id);
            });

            // ================== KUOTA CRUD ==================
            $('#btnTambahKuota').on('click', function() {
                $('#kuotaModalTitle').text('Tambah Kuota Kunjungan');
                $('#formKuota')[0].reset();
                $('#kuota_id').val('');
                openModal('#kuotaModal');
            });

            $(document).on('click', '.btn-edit-kuota', function() {
                const id = $(this).data('id');
                $('#kuotaModalTitle').text('Edit Kuota Kunjungan');
                openModal('#kuotaModal');

                $.get(`/admin-banceuy/kuota/${id}`, function(res) {
                    $('#kuota_id').val(res.id);
                    $('#tanggal').val(res.tanggal);
                    $('#kuota').val(res.kuota);
                    $('#keterangan').val(res.keterangan);
                }).fail(() => alert('Gagal mengambil data kuota'));
            });

            $('#formKuota').on('submit', function(e) {
                e.preventDefault();

                const id = $('#kuota_id').val();
                const isEdit = id !== '';
                const url = isEdit ? `/admin-banceuy/kuota/${id}` : `/admin-banceuy/kuota`;

                const data = {
                    tanggal: $('#tanggal').val(),
                    kuota: $('#kuota').val(),
                    keterangan: $('#keterangan').val()
                };

                if (isEdit) data._method = 'PUT';

                $.post(url, data)
                    .done(() => location.reload())
                    .fail(xhr => alert(xhr.responseJSON?.message ?? 'Gagal menyimpan data'));
            });

            $(document).on('click', '.btn-hapus-kuota', function() {
                const id = $(this).data('id');
                if (!confirm('Yakin ingin menghapus kuota ini?')) return;

                $.post(`/admin-banceuy/kuota/${id}`, {
                        _method: 'DELETE'
                    })
                    .done(() => location.reload())
                    .fail(xhr => alert(xhr.responseJSON?.message ?? 'Gagal menghapus data'));
            });

            // ================== SEARCH LIVE & PAGINATION ==================
            let timerKuota;

            $(document).on('input', '#searchKuota', function() {
                clearTimeout(timerKuota);
                timerKuota = setTimeout(() => {
                    $.get(window.location.pathname, {
                        search: $('#searchKuota').val()
                    }, res => {
                        $('#kuotaWrapper').html($(res).find('#kuotaWrapper').html());
                    });
                }, 300);
            });

            $(document).on('click', '#kuotaWrapper .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                $.get(url, {
                    search: $('#searchKuota').val()
                }, res => {
                    $('#kuotaWrapper').html($(res).find('#kuotaWrapper').html());
                });
            });

            // ================== NONAKTIFKAN TANGGAL TERLARANG ==================
            const inputTanggal = document.getElementById('tanggal');
            const today = new Date();
            inputTanggal.min = today.toISOString().split('T')[0];

            inputTanggal.addEventListener('input', function() {
                const day = new Date(this.value).getDay();
                if (day === 0 || day === 5 || day === 6) {
                    alert('Kunjungan tidak tersedia pada hari Jumat, Sabtu, dan Minggu');
                    this.value = '';
                }
            });

        });
    </script>
@endsection
