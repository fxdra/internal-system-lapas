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
    ];

@endphp

<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="/" class="b-brand d-flex align-items-center">

                <div class="brand-text">
                    <div class="fw-bold text-uppercase" style="font-size: 14px; line-height: 1.2;">
                        LEMBAGA PEMASYARAKATAN
                    </div>
                    <div class="fw-bold text-uppercase" style="font-size: 14px; opacity: .85; line-height: 1.2;">
                        KELAS IIA BANCEUY BANDUNG
                    </div>
                </div>

            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>MENU</label>
                </li>

                {{-- DASHBOARD --}}
                <li class="nxl-item">
                    <a href="/admin-banceuy" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-home"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>

                {{-- MANAJEMEN PENGGUNA --}}
                @if (in_array($role, ['superadmin', 'admin']))
                    <li class="nxl-item">
                        <a href="{{ route('manajemen-pengguna.index') }}" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-shield"></i>
                            </span>
                            <span class="nxl-mtext">Manajemen Pengguna</span>
                        </a>
                    </li>
                @endif


                {{-- MANAJEMEN KOMJA --}}
                @if ($permissions['fullAccess'])
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-briefcase"></i>
                            </span>
                            <span class="nxl-mtext">Manajemen Petugas</span>
                            <span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>


                        <ul class="nxl-submenu">

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/petugas-kplp">
                                    Data Petugas KPLP
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/hp-gasban">
                                    Data Hp Petugas
                                </a>
                            </li>
                            <li class="nxl-item">
                                <a class="nxl-link" href="#">
                                    Data Komandan Jaga
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/komandan-jaga">
                                    Anggota Komandan Jaga
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/kegiatan-komja">
                                    Kegiatan Komandan Jaga
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/laporan-komja">
                                    Laporan Komandan Jaga
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                <li class="nxl-item nxl-hasmenu">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Manajemen WBP</span>
                        <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                    </a>

                    <ul class="nxl-submenu">

                        <li class="nxl-item">
                            <a class="nxl-link" href="/admin-banceuy/laporan-data-wbp">
                                Data WBP
                            </a>
                        </li>

                        <li class="nxl-item">
                            <a class="nxl-link" href="/admin-banceuy/scenario-snapshot">
                                Data Mutasi Wbp
                            </a>
                        </li>

                        @if ($permissions['fullAccess'])
                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/laporan-data-kamar">
                                    Data Kamar
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/riwayat-mutasi">
                                    Riwayat Mutasi Wbp
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/kuota">
                                    Kuota Kunjungan
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/laporan-data-kritik">
                                    Kritik & Saran
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                {{-- MANAJEMEN KONTEN --}}
                @if ($permissions['fullAccess'])
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-file-text"></i></span>
                            <span class="nxl-mtext">Manajemen Konten</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>

                        <ul class="nxl-submenu">

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/berita">
                                    Berita
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/bulletin">
                                    Bulletin
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/kegiatan">
                                    Kegiatan
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/informasi-layanan">
                                    Informasi Layanan
                                </a>
                            </li>


                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/profile">
                                    Profile
                                </a>
                            </li>


                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/produk">
                                    Banceuy Shop
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if ($permissions['fullAccess'])
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-book"></i></span>
                            <span class="nxl-mtext">Perpustakaan</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>

                        <ul class="nxl-submenu">

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/daftar-buku">
                                    Daftar Buku
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/kategori">
                                    Kategori Buku
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/pengunjung-perpustakaan">
                                    Daftar Pengunjung Perpustakaan
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/peminjaman-buku">
                                    Peminjaman Buku
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/pengembalian-buku">
                                    Pengembalian Buku
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/donatur-buku">
                                    Daftar Donatur Buku
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/laporan-perpustakaan">
                                    Laporan Perpustakaan
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if ($permissions['fullAccess'])
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-cast"></i>
                            </span>
                            <span class="nxl-mtext">Laporan Kunjungan</span>
                            <span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>

                        <ul class="nxl-submenu">

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/laporan-data-kunjungan">
                                    Semua Pengunjung
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/data-kunjungan-status-pending">
                                    Pengunjung Pending
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/data-kunjungan-status-checkin">
                                    Pengunjung Check In
                                </a>
                            </li>

                            <li class="nxl-item">
                                <a class="nxl-link" href="/admin-banceuy/data-kunjungan-status-checkout">
                                    Pengunjung Check Out
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if ($permissions['fullAccess'])
                    <li class="nxl-item">
                        <a class="nxl-link" href="/admin/tracking">
                            <span class="nxl-micon"><i class="feather-map-pin"></i></span>
                            <span class="nxl-mtext">Live Tracking</span>
                        </a>
                    </li>
                @endif
            </ul>

            <ul>
                {{-- GENERATE REPORT --}}
                @if ($permissions['fullAccess'])
                    <li class="nxl-item">
                        <a class="nxl-link" href="{{ route('sistemlaporan') }}">
                            <span class="nxl-micon"><i class="feather-file"></i></span>
                            <span class="nxl-mtext">Manajemen Laporan</span>
                        </a>
                    </li>
                @endif
            </ul>

            {{-- MANAJEMEN KLINIK --}}
            @if (in_array($role, ['superadmin', 'admin', 'klinik']))
                <li class="nxl-item nxl-hasmenu">

                    <a href="javascript:void(0);" class="nxl-link">

                        <span class="nxl-micon">
                            <i class="feather-heart"></i>
                        </span>

                        <span class="nxl-mtext">
                            Manajemen Klinik
                        </span>

                        <span class="nxl-arrow">
                            <i class="feather-chevron-right"></i>
                        </span>

                    </a>

                    <ul class="nxl-submenu">

                        {{-- DATA WBP KLINIK --}}
                        <li class="nxl-item">
                            <a class="nxl-link" href="/admin-banceuy/klinik/wbp">
                                Data WBP Klinik
                            </a>
                        </li>

                        {{-- PEMERIKSAAN --}}
                        <li class="nxl-item">
                            <a class="nxl-link" href="/admin-banceuy/klinik/pemeriksaan">
                                Pemeriksaan
                            </a>
                        </li>

                        {{-- DIAGNOSIS --}}
                        <li class="nxl-item">
                            <a class="nxl-link" href="/admin-banceuy/klinik/diagnosis">
                                Diagnosis
                            </a>
                        </li>

                        {{-- THERAPY --}}
                        <li class="nxl-item">
                            <a class="nxl-link" href="/admin-banceuy/klinik/therapy">
                                Therapy
                            </a>
                        </li>

                        {{-- LAPORAN KLINIK --}}
                        <li class="nxl-item">
                            <a class="nxl-link" href="/admin-banceuy/klinik/laporan">
                                Laporan Klinik
                            </a>
                        </li>

                    </ul>

                </li>
            @endif

        </div>
    </div>
</nav>
