<header class="nxl-header">
    <div class="header-wrapper">
        <!--! [Start] Header Left !-->
        <div class="header-left d-flex align-items-center gap-4">
            <!--! [Start] nxl-head-mobile-toggler !-->
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <!--! [End] nxl-lavel-mega-menu !-->
        </div>
        <!--! [End] Header Left !-->
        <!--! [Start] Header Right !-->
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">
                <div class="nxl-h-item d-none d-sm-flex">
                    <div class="full-screen-switcher">
                        <a href="javascript:void(0);" class="nxl-head-link me-0"
                            onclick="$('body').fullScreenHelper('toggle');">
                            <i class="feather-maximize maximize"></i>
                            <i class="feather-minimize minimize"></i>
                        </a>
                    </div>
                </div>
                <div class="nxl-h-item dark-light-theme">
                    <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                        <i class="feather-moon"></i>
                    </a>
                    <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                        <i class="feather-sun"></i>
                    </a>
                </div>
                <div class="dropdown nxl-h-item">
                    <a class="nxl-head-link me-3" data-bs-toggle="dropdown" href="#" role="button"
                        data-bs-auto-close="outside">
                        <i class="feather-bell"></i>
                        <span class="badge bg-danger nxl-h-badge" id="notifBadge">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu" id="notifList">
                        <div class="text-center p-2">
                            <span class="text-muted small">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="dropdown nxl-h-item">
                    <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                        <img src="../../../assets/images/avatar/1.png" alt="user-image"
                            class="img-fluid user-avtar me-0" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <img src="../../../assets/images/avatar/1.png" alt="user-image"
                                    class="img-fluid user-avtar" />
                                <div>
                                    <h6 class="text-dark mb-0">{{ auth('admin')->user()->nama }}<span
                                            class="badge bg-soft-success text-success ms-1">PRO</span></h6>
                                    <span class="fs-12 fw-medium text-muted">{{ auth('admin')->user()->role }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>
                        <a href="/admin-banceuy/setting" class="dropdown-item">
                            <i class="feather-settings"></i>
                            <span>Setting</span>
                        </a>
                        <a href="/logout" class="dropdown-item">
                            <i class="feather-log-out"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--! [End] Header Right !-->
    </div>
</header>

{{-- Notification --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        let notifData = [];
        const readKey = 'readNotifications';
        let readNotifs = new Set(JSON.parse(localStorage.getItem(readKey) || '[]'));

        function loadNotifications() {
            $.get('/admin/notifications', function(res) {
                notifData = res.pengunjungs;

                const unreadNotifs = notifData.filter(p => !readNotifs.has(p.id));
                const notifBadgeCount = unreadNotifs.length;
                $('#notifBadge').text(notifBadgeCount);

                let html = '';
                if (notifData.length === 0 || unreadNotifs.length === 0) {
                    html =
                        '<div class="text-center p-2 text-muted small">Tidak ada pendaftaran yang tersedia</div>';
                } else {
                    notifData.forEach(p => {
                        if (readNotifs.has(p.id)) return;

                        let time = new Date(p.created_at).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        html += `
                    <div class="notifications-item d-flex align-items-center py-2 px-3 border-bottom" style="gap:10px;">
                        <img src="${p.foto_ktp ? '/storage/' + p.foto_ktp : '/assets/images/avatar/default.png'}"
                             alt="Avatar"
                             class="rounded-circle border"
                             style="width:45px; height:45px; object-fit:cover; flex-shrink:0;">
                        <div class="notifications-desc flex-fill">
                            <a href="javascript:void(0);" class="notif-click d-block text-truncate"
                               style="max-width:250px;" data-id="${p.id}">
                                <span class="fw-semibold text-dark">${p.nama_pengunjung}</span> mengunjungi
                                <span class="fw-semibold">${p.nama_wbp}</span>
                            </a>
                            <small class="text-muted d-block mt-1" style="font-size:0.8rem;">
                                ${time}
                            </small>
                        </div>
                    </div>
                    `;
                    });

                    html += `
                <div class="text-center notifications-footer mt-2">
                    <a href="/admin-banceuy/laporan-data-kunjungan" class="fs-13 fw-semibold text-dark">All Notifications</a>
                </div>`;
                }

                $('#notifList').html(html);
            });
        }

        loadNotifications();
        setInterval(loadNotifications, 30000);

        // Klik notif → tandai sebagai dibaca, lalu redirect
        $(document).on('click', '.notif-click', function() {
            const id = $(this).data('id');
            if (!readNotifs.has(id)) {
                readNotifs.add(id);
                localStorage.setItem(readKey, JSON.stringify([...readNotifs]));
            }

            // update badge
            const unreadCount = notifData.filter(p => !readNotifs.has(p.id)).length;
            $('#notifBadge').text(unreadCount);

            // redirect ke halaman laporan
            window.location.href = '/admin-banceuy/laporan-data-kunjungan';
        });

        // Klik dropdown → semua notif dianggap dibaca
        $('[data-bs-toggle="dropdown"]').on('click', function() {
            notifData.forEach(p => readNotifs.add(p.id));
            localStorage.setItem(readKey, JSON.stringify([...readNotifs]));
            $('#notifBadge').text(0);
            loadNotifications();
        });

    });
</script>
