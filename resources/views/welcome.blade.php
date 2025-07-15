<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CMSVA</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <style>
    body {
      background-color: #2c2c2c;color: #fff;font-family: 'Segoe UI', sans-serif;
    }

    .info-box {
      background-color: #1e1e1e;border: 1px solid #555;padding: 1rem;margin-bottom: 1rem;border-radius: 6px;
    }

    .info-box h4 {font-size: 1rem;border-bottom: 1px solid #555;padding-bottom: .5rem;margin-bottom: 1rem;
    }

    .map-container {height: 600px;background: #ccc;border-radius: 6px;
    }

    .header-bar {background: #444;padding: 1rem;font-size: 1rem;border-bottom: 2px solid #00aaff;
    }

    .highlight-orange {color: orange;
    }

    .section-title {font-weight: bold;color: #fff;
    }

    .bg-dark-list {background-color: #1e1e1e;border: none;
    }

    .tab-btns .btn {width: 33%;
    }

    .pie-label span {display: inline-block;width: 12px;height: 12px;border-radius: 50%;margin-right: 6px;
    }

    .scrollable-list {max-height: 160px;overflow-y: auto;
    }

    .map-filters select {background: #2c2c2c;color: #fff;border: 1px solid #777;
    }

           #map { height: 600px;  }

  </style>
</head>
<body>
  <!-- Disclaimer -->
<div class="header-bar text-white d-flex justify-content-between align-items-center">


<div class="fw-bold">
  <div style="font-size: 1.25rem; color: #FFA500; letter-spacing: 0.5px;">Department of Public Works and Highways</div>
  <div style="font-size: 1.1rem;">Construction Material Sources Validation Web Map</div>
</div>
    
  
 
<div class="d-flex gap-2">
    <div class="d-flex justify-content-end mb-2 map-filters">
      <form method="GET" action="{{ url('/') }}" class="d-flex align-items-center">
        <label for="region" class="me-2">Filter by Region:</label>
        <select name="region" id="region" onchange="this.form.submit()" class="form-select" style="width: 220px;">
          <option value="">-- Regions --</option>
          @foreach($regions as $region)
            <option value="{{ $region }}" {{ $selectedRegion == $region ? 'selected' : '' }}>
              {{ $region }}
            </option>
          @endforeach
        </select>
      </form>
    </div>
          @if (Route::has('login'))
                
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-info text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
</div>



  <div class="container-fluid p-3">
 <div class="row">
  <!-- Left Panel: Totals and Chart -->
  <div class="col-lg-3">
    <!-- Total Number of Sources Approved -->
    <div class="info-box text-center">
      <h4>Total Number of Sources Approved</h4>
      <div class="display-5 fw-bold highlight-orange">{{ $totalMaterials }}</div>
    </div>

    <!-- Pie/Bar Chart Box -->
    <div class="info-box p-4 bg-dark text-white rounded-4 shadow-sm">
      <h4>Total Number for Approval</h4>

      <div class="pie-label small mb-2">
        <div><span style="background-color: red; display:inline-block; width:12px; height:12px; margin-right:6px;"></span> Central Office: {{ $centralForApproval }}</div>
        <div><span style="background-color: blue; display:inline-block; width:12px; height:12px; margin-right:6px;"></span> Regional Office: {{ $regionalForApproval }}</div>
      </div>

      <small class="text-light">Note: Click on the chart to filter by classification.</small>

      <div class="mt-3 d-flex justify-content-center">
        <div style="width: 250px; height: 250px;">
          <canvas id="approvalChart" width="250" height="250"></canvas>
        </div>
      </div>

      <div class="mt-3 d-flex justify-content-center gap-2">
        <button class="btn btn-sm btn-outline-light" onclick="showPieChart()">Pie Chart</button>
        <button class="btn btn-sm btn-outline-light" onclick="showBarChart()">Bar Graph</button>
        <button class="btn btn-sm btn-outline-light">Map Legend</button>
      </div>
    </div>
  </div>

  <!-- Center Panel: Lists -->
  <div class="col-lg-3">
<!-- List of Approved Sources -->
<div class="info-box p-3" style="font-size: 13px; height: 300px;">
  <h6 class="fw-bold text-warning mb-2" style="font-size: 14px;">Approved Sources</h6>
  <input type="text" id="searchCentral" class="form-control form-control-sm mb-1" placeholder="Search...">
  <div class="scrollable-list" style="max-height: 180px; overflow-y: auto;">
    <ul class="list-group list-group-flush" id="centralList">
      @foreach ($centralForApprovalLists as $centralForApprovalList)
        <li class="list-group-item py-1 px-2 bg-dark-list text-white" style="font-size: 12px;">
          {{ $centralForApprovalList->material_source_name }} | {{ $centralForApprovalList->source_type }}
        </li>
      @endforeach
    </ul>
  </div>
</div>

<!-- List of For Approval -->
<div class="info-box p-2 mt-2" style="font-size: 13px;height: 300px;">
  <h6 class="fw-bold text-warning mb-2" style="font-size: 14px;">For Approval</h6>
  <input type="text" id="searchRegional" class="form-control form-control-sm mb-1" placeholder="Search...">
  <div class="scrollable-list" style="max-height: 180px; overflow-y: auto;">
    <ul class="list-group list-group-flush" id="regionalList">
      @foreach ($regionalForApprovalLists as $regionalForApprovalList)
        <li class="list-group-item py-1 px-2 bg-dark-list text-white" style="font-size: 12px;">
          {{ $regionalForApprovalList->material_source_name }} | {{ $regionalForApprovalList->source_type }}
        </li>
      @endforeach
    </ul>
  </div>
</div>

  </div>

  <!-- Right Panel: Map -->
  <div class="col-lg-6">
    <!-- Region Filter -->

    <!-- Map Display -->
    <div id="map" style="height: 100%; min-height: 400px;"></div>
  </div>
</div>

      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
    const map = L.map('map').setView([12.8797, 121.7740], 6); // Center: Philippines

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Location data from PHP
    const locations = @json($locations);

    locations.forEach(loc => {
        if (loc.latitude && loc.longitude) {
            let color = 'gray'; // default

            if (loc.user_id_validation == 2) {
                color = 'red';
            } else if (loc.user_id_validation == 3) {
                color = 'blue';
            }

            L.circleMarker([loc.latitude, loc.longitude], {
                radius: 8,
                color: color,
                fillColor: color,
                fillOpacity: 0.8
            }).addTo(map)
              .bindPopup(`
  <div style="font-size: 13px; line-height: 1.4;">
    <strong style="font-size: 15px; color: #FFA500;">${loc.material_source_name}</strong><br>
    <strong>Access Road:</strong> ${loc.access_road}<br>
    <strong>Directional Flow:</strong> ${loc.directional_flow}<br>
    <strong>Source Type:</strong> ${loc.source_type}<br>
    <strong>Potential Uses:</strong> ${loc.potential_uses}<br>
    <strong>Future Use Recommendation:</strong> ${loc.future_use_recommendation}<br>
    <strong>Location:</strong> ${loc.barangay}, ${loc.municipality}, ${loc.province}<br>
    <strong>Renewability:</strong> ${loc.renewability}<br>
    <strong>Processing Plant:</strong> ${loc.processing_plant_info}<br>
    <strong>Observations:</strong> ${loc.observations}<br>
    <strong>Permittee Name:</strong> ${loc.permittee_name}<br>
    <strong>Quality Test Result:</strong> ${loc.quality_test_result}<br>
    <strong>Quality Test Date:</strong> ${loc.quality_test_date}<br>
    <strong>Prepared By:</strong> ${loc.prepared_by}<br>
    ${loc.quality_test_attachment ? `<a href='/storage/uploads/${loc.quality_test_attachment}' target='_blank'>View Test Attachment</a>` : ''}
  </div>
`)
        }
    });
</script>


<script>
  let chartInstance = null;

  const data = {
    labels: ['Central Office', 'Regional Office'],
    datasets: [{
      label: 'For Approval',
      data: [{{ $centralForApproval }}, {{ $regionalForApproval }}],
      backgroundColor: ['red', 'blue'],
      borderColor: ['#fff', '#fff'],
      borderWidth: 1
    }]
  };

  const configPie = {
    type: 'pie',
    data: data,
    options: {
      responsive: true,
      onClick: (e, elements) => {
        if (elements.length) {
          const label = data.labels[elements[0].index];
          alert(`Filter by: ${label}`);
        }
      }
    }
  };

  const configBar = {
    type: 'bar',
    data: data,
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      onClick: (e, elements) => {
        if (elements.length) {
          const label = data.labels[elements[0].index];
          alert(`Filter by: ${label}`);
        }
      }
    }
  };

  function showPieChart() {
    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(document.getElementById('approvalChart'), configPie);
  }

  function showBarChart() {
    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(document.getElementById('approvalChart'), configBar);
  }

  // Show Pie Chart by default
  window.onload = showPieChart;
</script>

<script>
  function setupSearch(inputId, listId) {
    const input = document.getElementById(inputId);
    const list = document.getElementById(listId).getElementsByTagName('li');

    input.addEventListener('keyup', function () {
      const filter = input.value.toLowerCase();
      Array.from(list).forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }

  // Apply to both search inputs
  setupSearch('searchCentral', 'centralList');
  setupSearch('searchRegional', 'regionalList');
</script>

</body>
</html>
