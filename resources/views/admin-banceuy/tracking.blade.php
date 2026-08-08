@extends('admin-banceuy.partisi.main')

@section('content')
    <div class="container-fluid my-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-1">Live Tracking Map</h4>
                <small class="text-muted">Realtime GPS Monitoring (Free Map)</small>
            </div>
        </div>

        {{-- MAP --}}
        <div id="map" style="height:500px; width:100%; border-radius:12px;"></div>

        <hr>

        {{-- DEVICE LIST --}}
        <div id="deviceContainer"></div>

    </div>

    {{-- LEAFLET (FREE MAP) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let map;
        let markers = {};

        // ================= INIT MAP =================
        function initMap() {

            map = L.map('map').setView([-6.9, 107.6], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            loadDevices();
            setInterval(loadDevices, 3000);
        }

        // ================= LOAD DEVICE =================
        function loadDevices() {

            fetch('/admin/tracking/live')
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    if (data.length === 0) {
                        html = "<div class='alert alert-warning'>Tidak ada device aktif</div>";
                    }

                    data.forEach(function(d) {

                        let status = (d.lat && d.lon) ? "ONLINE" : "OFFLINE";
                        let color = (status === "ONLINE") ? "success" : "danger";

                        // ================= LIST =================
                        html += `
                <div class="card shadow-sm mb-2">
                    <div class="card-body">

                        <b>📱 ${d.device_id}</b><br>
                        <small>${d.android_id}</small><br>

                        <span class="badge bg-${color}">${status}</span>

                        <hr>

                        Lat: ${d.lat ?? '-'} <br>
                        Lon: ${d.lon ?? '-'}

                    </div>
                </div>
                `;

                        // ================= MARKER =================
                        if (d.lat && d.lon) {

                            let pos = [parseFloat(d.lat), parseFloat(d.lon)];

                            if (!markers[d.device_id]) {

                                markers[d.device_id] = L.marker(pos)
                                    .addTo(map)
                                    .bindPopup(d.device_id);

                            } else {
                                markers[d.device_id].setLatLng(pos);
                            }

                            map.setView(pos, 15);
                        }

                    });

                    document.getElementById('deviceContainer').innerHTML = html;

                })
                .catch(err => {
                    document.getElementById('deviceContainer').innerHTML =
                        "<div class='alert alert-danger'>Gagal load data</div>";
                });
        }

        // ================= START =================
        window.onload = initMap;
    </script>
@endsection
