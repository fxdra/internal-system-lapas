<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live GPS Tracking</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Leaflet -->
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>

        body{
            background:#0f172a;
        }

        #map{
            height:calc(100vh - 180px);
            width:100%;
        }

        .leaflet-container{
            background:#111827;
        }

    </style>

</head>
<body>

<div class="min-h-screen">

    <!-- HEADER -->

    <header class="bg-slate-900 border-b border-slate-700">

        <div class="max-w-7xl mx-auto px-8 py-5 flex justify-between items-center">

            <div>

                <h1 class="text-3xl font-bold text-white">

                    <i class="fa-solid fa-location-dot text-red-500"></i>

                    Live GPS Tracking

                </h1>

                <p class="text-slate-400 mt-1">

                    Enterprise Device Monitoring Dashboard

                </p>

            </div>

            <div>

                <span
                    class="px-4 py-2 rounded-full bg-green-600 text-white">

                    ● ONLINE

                </span>

            </div>

        </div>

    </header>

    <!-- CONTENT -->

    <div class="max-w-7xl mx-auto p-6">

        <div class="grid grid-cols-12 gap-6">

            <!-- MAP -->

            <div class="col-span-8">

                <div class="bg-slate-800 rounded-2xl overflow-hidden shadow-xl">

                    <div class="px-6 py-4 border-b border-slate-700">

                        <h2 class="text-white text-xl font-semibold">

                            <i class="fa-solid fa-map"></i>

                            Live Map

                        </h2>

                    </div>

                    <div id="map"></div>

                </div>

            </div>

            <!-- RIGHT -->

            <div class="col-span-4 space-y-6">

                <!-- DEVICE -->

                <div class="bg-slate-800 rounded-2xl shadow-xl p-6">

                    <h2 class="text-white text-xl font-semibold mb-5">

                        Device Information

                    </h2>

                    <div class="space-y-4">

                        <div>

                            <p class="text-slate-400 text-sm">
                                Device ID
                            </p>

                            <p id="device_id"
                               class="text-white font-semibold break-all">

                                {{ $tracking->device_id }}

                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-sm">
                                Latitude
                            </p>

                            <p id="latitude"
                               class="text-green-400">

                                {{ $tracking->latitude }}

                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-sm">
                                Longitude
                            </p>

                            <p id="longitude"
                               class="text-green-400">

                                {{ $tracking->longitude }}

                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-sm">
                                Accuracy
                            </p>

                            <p id="accuracy"
                               class="text-yellow-400">

                                {{ $tracking->accuracy }} Meter

                            </p>

                        </div>

                        <div>

                            <p class="text-slate-400 text-sm">
                                Last Update
                            </p>

                            <p id="updated_at"
                               class="text-white">

                                {{ $tracking->created_at }}

                            </p>

                        </div>

                    </div>

                </div>

                <!-- PHOTO -->

                <div class="bg-slate-800 rounded-2xl shadow-xl p-6">

                    <h2 class="text-white text-xl font-semibold mb-4">

                        Latest Photo

                    </h2>

                    <img id="photo"

                         src="{{ asset('storage/'.$tracking->image) }}"

                         class="rounded-xl w-full border border-slate-700 object-cover">

                </div>

            </div>

        </div>

    </div>

</div>

<script>

const API_KEY = "4d21BJu8GpJEa9TRYlFQ";

const INITIAL_LAT = Number("{{ $tracking->latitude }}");
const INITIAL_LNG = Number("{{ $tracking->longitude }}");
const INITIAL_ACC = Number("{{ $tracking->accuracy }}");

// =========================================
// MAP
// =========================================

const map = L.map("map", {
    zoomControl: true,
    preferCanvas: true,
    minZoom: 3,
    maxZoom: 19,
    zoomSnap: 0.5,
    zoomDelta: 0.5
}).setView([INITIAL_LAT, INITIAL_LNG], 19);

// =========================================
// MAPTILER HYBRID
// =========================================

L.tileLayer(
    `https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}.jpg?key=${API_KEY}`,
    {
        tileSize: 512,
        zoomOffset: -1,
        maxZoom: 19,
        detectRetina: true,
        updateWhenIdle: true,
        keepBuffer: 8,
        attribution:
            '&copy; MapTiler &copy; OpenStreetMap'
    }
).addTo(map);

// =========================================
// SCALE
// =========================================

L.control.scale({
    metric: true,
    imperial: false
}).addTo(map);

// =========================================
// MARKER
// =========================================

const marker = L.marker([INITIAL_LAT, INITIAL_LNG]).addTo(map);

// =========================================
// ACCURACY
// =========================================

const accuracyCircle = L.circle(
    [INITIAL_LAT, INITIAL_LNG],
    {
        radius: INITIAL_ACC,
        color: "#00ff66",
        fillColor: "#00ff66",
        fillOpacity: 0.15,
        weight: 2
    }
).addTo(map);

marker.bindPopup(
    `<b>{{ $tracking->device_id }}</b>`
).openPopup();

// =========================================
// FOLLOW MODE
// =========================================

let followLocation = true;

map.on("dragstart", () => {
    followLocation = false;
});

map.on("dblclick", () => {
    followLocation = true;
});

// =========================================
// CACHE
// =========================================

let lastPhoto = "";
let loading = false;

// =========================================
// REFRESH
// =========================================

async function refreshLocation(){

    if(loading) return;

    loading = true;

    try{

        const response = await fetch(
            "{{ route('tracking.latest') }}",
            {
                cache:"no-store"
            }
        );

        if(!response.ok){
            loading = false;
            return;
        }

        const data = await response.json();

        const lat = Number(data.latitude);
        const lng = Number(data.longitude);
        const acc = Number(data.accuracy);

        if(isNaN(lat) || isNaN(lng)){
            loading = false;
            return;
        }

        // Marker

        marker.setLatLng([lat,lng]);

        // Circle

        accuracyCircle.setLatLng([lat,lng]);

        accuracyCircle.setRadius(acc);

        // Popup

        marker.setPopupContent(`
            <b>${data.device_id}</b><br>
            Accuracy : ${acc} Meter
        `);

        // Auto Follow

        if(followLocation){

            const center = map.getCenter();

            const distance =
                center.distanceTo(
                    L.latLng(lat,lng)
                );

            if(distance > 20){

                map.panTo(
                    [lat,lng],
                    {
                        animate:true
                    }
                );

            }

        }

        // Device

        device_id.textContent =
            data.device_id ?? "-";

        latitude.textContent =
            lat;

        longitude.textContent =
            lng;

        accuracy.textContent =
            acc + " Meter";

        updated_at.textContent =
            data.created_at ?? "-";

        // Photo

        if(
            data.photo_path &&
            data.photo_path !== lastPhoto
        ){

            lastPhoto = data.photo_path;

            photo.src =
                "/storage/" +
                data.photo_path +
                "?t=" +
                Date.now();

        }

    }
    catch(err){

        console.error(err);

    }

    loading = false;

}

// =========================================
// START
// =========================================

window.addEventListener("load",()=>{

    map.invalidateSize();

    refreshLocation();

    setInterval(refreshLocation,3000);

});

window.addEventListener("resize",()=>{

    map.invalidateSize();

});

</script>

</body>
</html>