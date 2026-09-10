@php

    $role = Auth::guard('admin')->user()?->role;

    $permissions = [
        // Full Access
        'fullAccess' => in_array($role, ['superadmin', 'admin', 'kplp', 'ka_kplp']),

        // Bidang
        'registrasi' => $role === 'registrasi',
        'binadik' => $role === 'binadik',
        'giatja' => $role === 'giatja',
        'klinik' => $role === 'klinik',
        'kamtib' => $role === 'kamtib',

        // Data Hunian
        'dataHunian' => in_array($role, ['superadmin', 'admin', 'kplp', 'ka_kplp', 'registrasi', 'binadik']),

        // Print Sterek
        'printSterek' => in_array($role, ['superadmin', 'admin', 'kplp', 'ka_kplp', 'registrasi', 'binadik']),
    ];

@endphp

@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        .kamar-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            align-items: start;
        }

        @media(max-width:768px) {
            .kamar-grid {
                grid-template-columns: 1fr;
            }
        }

        .main-row {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);

            /* FIX GRID */
            align-self: start;
            height: max-content;
        }

        .nama-col {
            font-size: 16px;
            font-weight: 600;
        }

        .sub-info {
            font-size: 13px;
            color: #6c757d;
        }

        .kamar-item {
            overflow: hidden;
        }

        .dropdown-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .toggle-btn {
            border: none;
            background: none;
            font-size: 22px;
            transition: .25s;
        }

        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition:
                max-height .25s ease,
                padding .25s ease;
            padding-top: 0;
        }

        .kamar-item.open .dropdown-content {
            max-height: 500px;
            padding-top: 15px;
        }

        .kamar-item.open .toggle-btn {
            transform: rotate(180deg);
        }

        /* ================= MOBILE DATA KAMAR ================= */
        @media (max-width: 768px) {

            /* ================= PAGE TITLE ================= */

            .container-fluid h2,
            .container-fluid h3,
            .container-fluid h4 {
                margin-bottom: 10px;
            }

            /* ================= ACTION BUTTON ================= */

            .container-fluid .d-flex.flex-wrap.gap-2.mb-3 {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 8px !important;
                margin-bottom: 16px !important;
            }

            .container-fluid .d-flex.flex-wrap.gap-2.mb-3 .btn {
                width: 100%;
                min-height: 42px;
                padding: 9px 8px;
                font-size: 10px;
                font-weight: 700;
                line-height: 1.2;
                white-space: nowrap;
            }

            .container-fluid .d-flex.flex-wrap.gap-2.mb-3 .btn-success {
                grid-column: 1 / -1;
            }

            /* ================= SEARCH ================= */

            input[placeholder="Cari kamar..."] {
                height: 44px;
                padding: 10px 13px;
                font-size: 13px;
                border-radius: 10px;
                margin-bottom: 14px;
            }

            /* ================= KAMAR GRID ================= */

            .kamar-grid {
                grid-template-columns: 1fr;
                gap: 9px;
            }

            /* ================= KAMAR CARD ================= */

            .main-row {
                padding: 12px 14px;
                border-radius: 13px;
                min-height: 68px;
                box-shadow: 0 2px 7px rgba(0, 0, 0, .045);
            }

            /* ================= HEADER KAMAR ================= */

            .dropdown-header {
                min-height: 44px;
                gap: 10px;
            }

            .nama-col {
                font-size: 15px;
                line-height: 1.2;
            }

            .sub-info {
                margin-top: 4px;
                font-size: 11px;
                line-height: 1.2;
            }

            /* ================= TOGGLE ================= */

            .toggle-btn {
                flex-shrink: 0;
                width: 32px;
                height: 32px;

                display: flex;
                align-items: center;
                justify-content: center;

                padding: 0;
                font-size: 19px;
                line-height: 1;
            }

            /* ================= DROPDOWN ================= */

            .kamar-item.open .dropdown-content {
                max-height: 500px;
                padding-top: 12px;
            }

        }
    </style>

    <div class="container-fluid my-3">

        <h4 class="fw-bold">Data Kamar</h4>

        <div class="d-flex flex-wrap gap-2 mb-3">

            @if ($permissions['fullAccess'])
                <button class="btn btn-primary" id="openStatusModal">
                    <i class="bi bi-pencil-square me-1"></i>
                    Ubah Status Kamar
                </button>

                <a href="{{ url('/admin-banceuy/kamar/print') }}" target="_blank" class="btn btn-dark">
                    <i class="bi bi-printer me-1"></i>
                    Print Barcode Semua Kamar
                </a>
            @endif

            @if ($permissions['dataHunian'])
                <a href="{{ url('/kamar-with-wbps') }}" class="btn btn-success">
                    <i class="bi bi-building me-1"></i>
                    Data Hunian Kamar
                </a>
            @endif

        </div>

        <input type="text" id="search" class="form-control mb-3" placeholder="Cari kamar...">

        <div id="tableWrapper">

            <div class="kamar-grid">

                @forelse($kamar as $k)
                    <div class="main-row kamar-item">

                        <div class="dropdown-header">

                            <div>

                                <div class="nama-col">
                                    BLOK {{ $k->kode_blok }}
                                </div>

                                <div class="sub-info">
                                    {{ $k->lokasi_sel }}
                                </div>

                            </div>

                            <button type="button" class="toggle-btn">
                                ▼
                            </button>

                        </div>

                        <div class="dropdown-content">

                            <div>

                                <b>Barcode</b>

                                <div class="mt-2">

                                    @if ($k->img_barcode)
                                        <img src="{{ asset('storage/' . $k->img_barcode) }}"
                                            style="
                                            width:140px;
                                            border-radius:8px;
                                        ">
                                    @else
                                        Tidak Ada Barcode
                                    @endif

                                </div>

                            </div>

                            <div class="mt-3">

                                <b>Status Kamar</b>

                                @php

                                    $badge = 'bg-secondary';

                                    if ($k->status_kamar == 'Terbuka') {
                                        $badge = 'bg-success';
                                    }

                                    if ($k->status_kamar == 'Tertutup') {
                                        $badge = 'bg-danger';
                                    }

                                @endphp

                                <div class="mt-2">

                                    <span class="badge {{ $badge }}">
                                        {{ $k->status_kamar }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center">
                        Data tidak ditemukan
                    </div>
                @endforelse

            </div>

            <div class="mt-3">
                {{ $kamar->links() }}
            </div>

        </div>

    </div>

    {{-- ================= MODAL UPDATE STATUS KAMAR================= --}}
    <div id="modalStatus"
        style="
            display:none;
            position:fixed;
            inset:0;
            background:rgba(0,0,0,.5);
            z-index:9999;
            ">

        <div
            style="
            background:#fff;
            max-width:400px;
            margin:8% auto;
            padding:20px;
            border-radius:10px;
            ">

            <h5>Ubah Status Kamar</h5>

            <select id="kamarSelect" class="form-control mt-2">
                <option value="">Pilih Kamar</option>

                @foreach ($kamarUntukSelect as $k)
                    <option value="{{ $k->id }}">
                        BLOK {{ $k->kode_blok }} - {{ $k->lokasi_sel }}
                    </option>
                @endforeach
            </select>

            {{-- PILIH STATUS --}}
            <select id="statusSelect" class="form-control mt-2">
                <option value="Terbuka">Terbuka</option>
                <option value="Tertutup">Tertutup</option>
            </select>

            <div class="mt-3 d-flex justify-content-end gap-2">
                <button id="saveStatus" class="btn btn-success btn-sm">Simpan</button>
                <button id="closeStatus" class="btn btn-danger btn-sm">Batal</button>
            </div>

        </div>
    </div>

    {{-- ================= MODAL DETAIL KAMAR================= --}}
    <div id="modalDetail" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;">
        <div style="background:#fff;max-width:450px;margin:8% auto;padding:20px;border-radius:10px;">

            <div class="d-flex justify-content-between mb-2">
                <h5>Detail Kamar</h5>
                <button id="closeModal" class="btn btn-sm btn-danger">X</button>
            </div>

            <div id="detailBody">Loading...</div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).off(
            'click',
            '.toggle-btn'
        );

        $(document).on(
            'click',
            '.toggle-btn',
            function(e) {

                e.preventDefault();

                e.stopPropagation();

                const card =
                    $(this)
                    .closest(
                        '.kamar-item'
                    );

                const opened =
                    card
                    .hasClass(
                        'open'
                    );

                $('.kamar-item')
                    .removeClass(
                        'open'
                    );

                if (
                    !opened
                ) {
                    card
                        .addClass(
                            'open'
                        );
                }

            });
    </script>
    <script>
        $(function() {

            // ================= SEARCH =================
            let t;
            $('#search').on('input', function() {

                clearTimeout(t);

                t = setTimeout(() => {

                    $('#tableWrapper').html('<div class="text-center p-3">Loading...</div>');

                    $.get(window.location.pathname, {
                        search: $('#search').val()
                    }, function(res) {

                        $('#tableWrapper').html(
                            $(res).find('#tableWrapper').html()
                        );

                    });

                }, 300);

            });

            // ================= DETAIL =================
            $(document).on('click', '.btn-detail', function() {

                let id = $(this).data('id');

                $('#modalDetail').fadeIn(200);
                $('#detailBody').html('Loading...');

                $.get('/admin-banceuy/kamar/' + id + '/detail', function(res) {

                    let nama = res.nama_kamar ?? '-';
                    let kode = res.kode_kamar ?? '-';
                    let blok = res.blok ?? '-';
                    let sel = res.sel ?? '-';
                    let status = res.status ?? '-';
                    let barcodeId = res.barcode ?? '-';
                    let imgBarcode = res.img_barcode ?? null;

                    // BADGE
                    let badge = '';

                    if (status === 'Terbuka') {
                        badge =
                            `<span style="background:#28a745;color:#fff;padding:5px 10px;border-radius:6px;">Terbuka</span>`;
                    } else if (status === 'Tertutup') {
                        badge =
                            `<span style="background:#dc3545;color:#fff;padding:5px 10px;border-radius:6px;">Tertutup</span>`;
                    } else {
                        badge =
                            `<span style="background:#6c757d;color:#fff;padding:5px 10px;border-radius:6px;">-</span>`;
                    }

                    // BARCODE IMAGE
                    let barcode = '-';

                    if (imgBarcode) {
                        barcode = `<img
            src="/storage/${imgBarcode}"
            onerror="this.style.display='none'"
            style="margin:10;width:100%;max-width:250px;margin-top:10px;border-radius:6px;">`;
                    }

                    $('#detailBody').html(`
        <h5>${nama}</h5>
        <hr>

        <p><b>Kode:</b> ${kode}</p>
        <p><b>Blok:</b> ${blok}</p>
        <p><b>Sel:</b> ${sel}</p>

        <p><b>Status:</b> ${badge}</p>

        <p><b>Barcode ID:</b> ${barcodeId}</p>

        <div>
            <b>Barcode:</b><br>
            ${barcode}
        </div>
    `);

                });

            });

            $('#closeModal').click(() => $('#modalDetail').fadeOut(200));

        });



        // OPEN MODAL
        $('#openStatusModal').click(function() {
            $('#modalStatus').fadeIn(200);
        });

        // CLOSE
        $('#closeStatus').click(function() {
            $('#modalStatus').fadeOut(200);
        });

        $('#saveStatus').click(function() {

            let kamarId = $('#kamarSelect').val();
            let status = $('#statusSelect').val();

            if (!kamarId) {
                alert('Pilih kamar dulu!');
                return;
            }

            $.ajax({
                url: '/admin-banceuy/kamar/update-status', // ⬅️ karena controller tanpa {id}
                method: 'POST',
                data: {
                    kamar_id: kamarId,
                    status_kamar: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {

                    console.log(res);

                    alert(res.message);

                    $('#modalStatus').fadeOut(200);

                    location.reload();

                },
                error: function(xhr) {
                    console.log(xhr.responseJSON); // 🔥 DEBUG
                    alert('Gagal update');
                }
            });

        });


        $('#openUploadPdf').click(function() {
            $('#modalPdf').fadeIn(200);
        });

        $('#closePdf').click(function() {
            $('#modalPdf').fadeOut(200);
        });

        $('#formPdf').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/admin-banceuy/kamar/upload-pdf',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {
                    alert(res.message);
                    $('#modalPdf').fadeOut(200);

                    // optional refresh
                    location.reload();
                },

                error: function(xhr) {

                    console.log('ERROR RESPONSE:', xhr.responseText);

                    if (xhr.status === 419) {
                        alert('Session expired (419). Refresh halaman.');
                    } else if (xhr.status === 422) {
                        alert('Validasi gagal. Cek file & kamar.');
                    } else {
                        alert('Upload gagal');
                    }
                }
            });

        });
    </script>
@endsection
