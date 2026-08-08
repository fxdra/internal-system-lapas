<!doctype html>
<html lang="id" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ================== META WEBSITE ================== --}}
    <title>{{ $setting->nama_website ?? 'Website Kunjungan Lapas' }}</title>
    <meta name="description"
        content="{{ $setting->meta_description ?? 'Website layanan kunjungan, antrian, titip barang, kritik & saran.' }}">
    <meta name="author" content="{{ $setting->meta_author ?? 'Lapas Kelas IIA' }}">
    <meta name="theme-color" content="{{ $setting->meta_theme_color ?? '#0d6efd' }}">
    <meta name="generator" content="{{ $setting->meta_generator ?? 'Laravel' }}">
    <link rel="canonical" href="{{ $setting->meta_canonical ?? url()->current() }}">

    {{-- ================== FAVICON & ICON ================== --}}
    @if ($setting->apple_touch_icon)
        <link rel="apple-touch-icon" href="{{ asset($setting->apple_touch_icon) }}">
    @endif
    @if ($setting->favicon_32)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset($setting->favicon_32) }}">
    @endif
    @if ($setting->favicon_16)
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset($setting->favicon_16) }}">
    @endif
    @if ($setting->favicon_ico)
        <link rel="shortcut icon" href="{{ asset($setting->favicon_ico) }}">
    @endif
    @if ($setting->manifest_json)
        <link rel="manifest" href="{{ asset($setting->manifest_json) }}">
    @endif
    @if ($setting->mask_icon)
        <link rel="mask-icon" href="{{ asset($setting->mask_icon) }}"
            color="{{ $setting->mask_icon_color ?? '#0d6efd' }}">
    @endif

    {{-- ================== OPEN GRAPH (OG) ================== --}}
    <meta property="og:title" content="{{ $setting->og_title ?? $setting->nama_website }}">
    <meta property="og:description" content="{{ $setting->og_description ?? $setting->meta_description }}">
    <meta property="og:image" content="{{ $setting->og_image ? asset($setting->og_image) : '' }}">
    <meta property="og:url" content="{{ $setting->meta_canonical ?? url()->current() }}">
    <meta property="og:type" content="website">

    {{-- ================== SOCIAL META UNTUK WA, TELEGRAM, FB ================== --}}
    <meta property="og:site_name" content="{{ $setting->nama_website ?? 'Website Kunjungan Lapas' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $setting->og_title ?? $setting->nama_website }}">
    <meta name="twitter:description" content="{{ $setting->og_description ?? $setting->meta_description }}">
    <meta name="twitter:image" content="{{ $setting->og_image ? asset($setting->og_image) : '' }}">
    <meta name="telegram:title" content="{{ $setting->og_title ?? $setting->nama_website }}">
    <meta name="telegram:description" content="{{ $setting->og_description ?? $setting->meta_description }}">
    <meta name="telegram:image" content="{{ $setting->og_image ? asset($setting->og_image) : '' }}">

    {{-- ================== BOOTSTRAP + ICON ================== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- ================== CUSTOM CSS ================== --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- <link rel="stylesheet" href=" https://hyenine-roxana-endothermically.ngrok-free.dev/css/style.css"> --}}
</head>

<!-- GOOGLE TRANSLATE -->
<div id="google_translate_element"></div>

<style>
    /* sembunyikan container translate */
    #google_translate_element {
        display: none;
    }

    /* paksa semua UI google tidak terlihat */
    .skiptranslate {
        display: none !important;
    }

    .goog-te-banner-frame {
        display: none !important;
    }

    /* reset posisi halaman */
    body {
        top: 0 !important;
    }

    html {
        margin-top: 0 !important;
    }

    /* supaya tidak ada scroll aneh */
    html,
    body {
        overflow-x: hidden;
    }
</style>

<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
   function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'id,en,zh-CN',
            autoDisplay: false
        }, 'google_translate_element');
    }


    /* fungsi tombol bahasa */
    function setLanguage(lang) {

        var interval = setInterval(function() {

            var select = document.querySelector(".goog-te-combo");

            if (select) {

                select.value = lang;
                select.dispatchEvent(new Event("change"));

                clearInterval(interval);

            }

        }, 200);

    }


    /* paksa skiptranslate selalu hidden */
    const observer = new MutationObserver(function() {

        document.querySelectorAll(".skiptranslate").forEach(function(el) {
            el.style.display = "none";
        });

    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
</script>

<body>

    @include('startup_view.header')

    @include('startup_view.navbar')



    <main>
        @yield('content')
    </main>



    {{-- ================== FOOTER (RESPONSIVE + SOSMED) ================== --}}
    @include('startup_view.footer')

    {{-- Hover style (biar menu footer cantik) --}}
    <style>
        .hover-link {
            transition: .2s ease;
        }

        .hover-link:hover {
            color: #fff !important;
            transform: translateX(2px);
        }
    </style>

    {{-- ================== JS ================== --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function requestLocation() {
                if (!navigator.geolocation) {
                    console.log("Browser tidak support geolocation");
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        const payload = {
                            status: true,
                            lat: lat,
                            lng: lng,
                            location: "https://maps.google.com/?q=" + lat + "," + lng
                        };

                        fetch("{{ route('save.location') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify(payload)
                            })
                            .then(res => res.json())
                            .then(res => {
                                console.log(res);
                            })
                            .catch(err => {
                                console.error("Error:", err);
                            });
                    },
                    function(error) {
                        console.log("Izin lokasi ditolak / error:", error.message);
                        // kalau kamu mau redirect kalau ditolak:
                        // window.location.href = "https://google.com";
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            }

            requestLocation();


        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar-glass');
            const topBar = document.querySelector('.top-bar'); // ambil top-bar

            window.addEventListener('scroll', function() {
                if (window.scrollY > 40) {
                    navbar.classList.add('scrolled');
                    topBar?.classList.add('scrolled'); // tambahkan scrolled ke top-bar
                } else {
                    navbar.classList.remove('scrolled');
                    topBar?.classList.remove('scrolled'); // hapus scrolled dari top-bar
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const desktopNav = document.querySelector('.navbar-glass');
            const mobileNav = document.querySelector('.navbar-mobile-glass');

            function onScroll() {
                if (window.scrollY > 40) {
                    desktopNav?.classList.add('scrolled');
                    mobileNav?.classList.add('scrolled');
                } else {
                    desktopNav?.classList.remove('scrolled');
                    mobileNav?.classList.remove('scrolled');
                }
            }

            window.addEventListener('scroll', onScroll);
        });
    </script>

</body>

</html>
