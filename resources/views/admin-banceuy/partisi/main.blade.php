<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lapas IIA Banceuy Dashboard</title>

    <link rel="shortcut icon" type="image/x-icon" href="../../../assets/images/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="../../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" type="text/css" href="../../../assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="../../../assets/vendors/css/daterangepicker.min.css" />

    <link rel="stylesheet" type="text/css" href="../../../../../../assets/css/theme.min.css" />
</head>

<body>
    <!--! ================================================================ !-->
    <!--! [Start] Navigation Manu !-->
    <!--! ================================================================ !-->
    @include('admin-banceuy.partisi.navbar')
    <!--! ================================================================ !-->
    <!--! [End]  Navigation Manu !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! [Start] Header !-->
    <!--! ================================================================ !-->
    @include('admin-banceuy.partisi.header')
    <!--! ================================================================ !-->
    <!--! [End] Header !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! [Start] Main Content !-->
    <!--! ================================================================ !-->
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->

            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    @yield('content')
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
        @include('admin-banceuy.partisi.footer')
    </main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! BEGIN: Theme Customizer !-->
    <!--! ================================================================ !-->

    <!--! ================================================================ !-->
    <!--! [End] Theme Customizer !-->
    <!--! ================================================================ !-->
    <!--! ================================================================ !-->
    <!--! Footer Script !-->
    <!--! ================================================================ !-->
    <!--! BEGIN: Vendors JS !-->

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const path = window.location.pathname;

            const titleEl = document.getElementById('page-title');
            const breadcrumbEl = document.getElementById('page-breadcrumb');

            let title = 'Dashboard';

            // ===== ROUTE MAPPING =====
            if (path === '/admin-banceuy' || path === '/admin-banceuy/') {
                title = 'Dashboard';
            } else if (path.includes('/admin-banceuy/laporan-data-kunjungan')) {
                title = 'Data Kunjungan';
            } else if (path.match(/\/kunjungan\/\d+\/edit/)) {
                title = 'Edit Kunjungan';
            } else if (path.match(/\/kunjungan\/\d+$/)) {
                title = 'Detail Kunjungan';
            } else if (path.includes('/kunjungan')) {
                title = 'Data Kunjungan';
            }

            // ===== SET TEXT =====
            if (titleEl) titleEl.textContent = title;
            if (breadcrumbEl) breadcrumbEl.textContent = title;

        });
    </script>


    <script src="../../../assets/vendors/js/vendors.min.js"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="../../../assets/vendors/js/daterangepicker.min.js"></script>
    <script src="../../../assets/vendors/js/apexcharts.min.js"></script>
    <script src="../../../assets/vendors/js/circle-progress.min.js"></script>
    <!--! END: Vendors JS !-->
    <!--! BEGIN: Apps Init  !-->
    <script src="../../../assets/js/common-init.min.js"></script>
    <script src="../../../assets/js/dashboard-init.min.js"></script>
    <!--! END: Apps Init !-->
    <!--! BEGIN: Theme Customizer  !-->
    <script src="../../../assets/js/theme-customizer-init.min.js"></script>
    <!--! END: Theme Customizer !-->
</body>

</html>
