<!-- [ Footer ] start -->
<style>
    .footer-developer-logo {
        display: block;
        width: 76px;
        height: auto;
        object-fit: contain;
        opacity: 0.85;
        transition: opacity 0.2s ease;
    }

    .footer-developer-logo:hover {
        opacity: 1;
    }

    /* Dark theme */
    .app-skin-dark .nxl-footer {
        color: #aab4c5;
    }

    .app-skin-dark .nxl-footer .text-muted {
        color: #aab4c5 !important;
    }

    /* Light theme */
    html:not(.app-skin-dark) .nxl-footer {
        color: #667085;
    }

    html:not(.app-skin-dark) .nxl-footer .text-muted {
        color: #667085 !important;
    }

    @media (max-width: 576px) {
        .footer-wrapper {
            justify-content: center !important;
            text-align: center;
        }
    }
</style>

<footer class="nxl-footer mt-4">
    <div class="footer-wrapper d-flex flex-wrap justify-content-between align-items-center gap-3 px-4 py-3">
        <div class="text-muted small">
            &copy; {{ date('Y') }} Internal Sistem Lapas Banceuy
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="text-muted small">Developed by</span>
            <img src="{{ asset('assets/images/logo/logo-am.png') }}" alt="AM Developer Logo" class="footer-developer-logo">
        </div>
    </div>
</footer>
<!-- [ Footer ] end -->
