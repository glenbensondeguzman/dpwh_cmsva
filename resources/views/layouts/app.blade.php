<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Bootstrap 5 -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <style>

        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    @include('partials.header')
        <div style="background-color: #f3f6fa; min-height: 100vh; padding-top: 50px; padding-bottom: 40px;">
        @yield('content')
        </div>
    @include('partials.footer')

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get lat/lng values from input fields (or use default)
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        let initLat = parseFloat(latInput.value);
        let initLng = parseFloat(lngInput.value);

        if (isNaN(initLat) || isNaN(initLng)) {
            initLat = 13.41;
            initLng = 122.56;
        }

        // Initialize the map
        const map = L.map('map').setView([initLat, initLng], 13);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);


        let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);


        marker.on('dragend', function () {
            const position = marker.getLatLng();
            latInput.value = position.lat.toFixed(6);
            lngInput.value = position.lng.toFixed(6);
        });

 
        function updateMapFromInput() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);

            if (!isNaN(lat) && !isNaN(lng)) {
                const newLatLng = L.latLng(lat, lng);
                marker.setLatLng(newLatLng);
                map.setView(newLatLng, 13);
            }
        }

   
        map.on('click', function (e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            marker.setLatLng(e.latlng);
            latInput.value = lat;
            lngInput.value = lng;
        });

        latInput.addEventListener('change', updateMapFromInput);
        lngInput.addEventListener('change', updateMapFromInput);
    });
</script>

<script>
    let modalMap; // Global map variable
    let modalMarker;

    const viewModal = document.getElementById('viewDataModal');

    viewModal.addEventListener('shown.bs.modal', function () {
        const lat = parseFloat(document.getElementById('modal-latitude').innerText) || 13.41;
        const lng = parseFloat(document.getElementById('modal-longitude').innerText) || 122.56;

        const latLng = [lat, lng];

        // If map already exists, just update it
        if (modalMap) {
            modalMap.setView(latLng, 13);
            modalMarker.setLatLng(latLng);
        } else {
            modalMap = L.map('modal-map').setView(latLng, 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(modalMap);

            modalMarker = L.marker(latLng).addTo(modalMap);
        }

        // Fix rendering if modal was hidden during init
        setTimeout(() => {
            modalMap.invalidateSize();
        }, 200);
    });
</script>


</body>
</html>