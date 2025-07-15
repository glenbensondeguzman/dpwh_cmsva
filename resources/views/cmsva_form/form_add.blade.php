@extends('layouts.app')
@section('content')
 <div style="background-color: #f3f6fa; min-height: 100vh; padding-top: 60px; padding-bottom: 40px;">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="card-body">

                <!-- Header / Title -->
<div class="mb-4 text-center py-3 px-2 bg-white rounded-3 shadow-sm border border-primary-subtle">
    <h3 class="text-primary fw-bold mb-1 d-flex justify-content-center align-items-center">
        <i class="bi bi-clipboard-check-fill me-2 fs-3"></i>
        <span>Material Source Inventory Form</span>
    </h3>
    <p class="text-muted mb-0">Please fill in all required information accurately to ensure data consistency.</p>
</div>


                <!-- FORM START -->
                <form action="{{route('add')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <!-- Basic Info -->
                        <div class="col-12">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-geo-alt-fill me-2"></i>Basic Information
                            </h5>
                            <hr>
                        </div>
                        <!-- Shortcut method -->
                         @foreach ([
                                ['material_source_name', 'Name of Material Source'],
                                ['access_road', 'Access Road to the Material Source'],
                                ['directional_flow', 'Directional Flow of Access Road'],
                                ['source_type', 'Type of Source'],
                                ['potential_uses', 'Potential Use/s'],
                            ] as [$name, $label])
                                <div class="col-md-4">
                                    <label class="form-label text-primary fw-semibold">{{ $label }}</label>

                                    @if ($name === 'source_type')
                                        <select name="{{ $name }}" class="form-select" required>
                                            <option value="" disabled selected>Select source type</option>
                                            <option value="River">River</option>
                                            <option value="Mountain">Mountain</option>
                                        </select>
                                    @else
                                        <input type="text" name="{{ $name }}" class="form-control" required>
                                    @endif

                                </div>
                            @endforeach

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Recommendation for Future Use</label>
                            <textarea name="future_use_recommendation" class="form-control" rows="2"></textarea>
                        </div>

                            <!-- Province Dropdown -->
                            <div class="col-md-4">
                                <label class="form-label text-primary fw-semibold">Province</label>
                                <select id="province" name="province" class="form-select" required>
                                    <option value="">Select Province</option>
                                </select>
                            </div>

                            <!-- Municipality Dropdown -->
                            <div class="col-md-4">
                                <label class="form-label text-primary fw-semibold">Municipality/City</label>
                                <select id="municipality" name="municipality" class="form-select" required>
                                    <option value="">Select Municipality</option>
                                </select>
                            </div>

                            <!-- Barangay Dropdown -->
                            <div class="col-md-4">
                                <label class="form-label text-primary fw-semibold">Barangay</label>
                                <select id="barangay" name="barangay" class="form-select" required>
                                    <option value="">Select Barangay</option>
                                </select>
                            </div>




                        <!-- Renewability -->
                        <div class="col-12 pt-3">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-recycle me-2"></i>Renewability Information
                            </h5>
                            <hr>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Is the Source Renewable?</label>
                            <select id="renewability" name="renewability" class="form-select" required>
                                <option value="">Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>


                        <!-- Site Info -->
                        <div class="col-12 pt-3">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-building me-2"></i>Site & Equipment Information
                            </h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Processing Plant and/or Equipment on Site</label>
                            <textarea name="processing_plant_info" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Observation and Other Info</label>
                            <textarea name="observations" class="form-control" rows="2"></textarea>
                        </div>


                        


                        <!-- Location -->
                        <div class="col-12 pt-3">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-recycle me-2"></i>Location Information
                            </h5>
                            <hr>
                        </div>

                        <div class="container">
                            <label class="form-label text-primary fw-semibold">Click on the Map of the location of Source</label>
                            <div id="map"></div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" required>
                                </div>
                            </div>
                        </div>


                        <!-- File Uploads -->
                        <div class="col-12 pt-3">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-folder2-open me-2"></i>Permit & Test Results
                            </h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Approved Quarry Permit (Upload)</label>
                            <input type="file" name="quarry_permit" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Quarry Permit - Date Issued</label>
                            <input type="date" name="quarry_permit_date" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Name of Owner/Permittee</label>
                            <input type="text" name="permittee_name" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Quality Test Result - Attachment</label>
                            <input type="file" name="quality_test_attachment" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Quality Test Result - Date</label>
                            <input type="date" name="quality_test_date" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Result (Passed/Failed)</label>
                            <select name="quality_test_result" class="form-select">
                                <option value="">Select</option>
                                <option value="Passed">Passed</option>
                                <option value="Failed">Failed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Prepared By</label>
                            <input type="text" name="prepared_by" value="{{auth()->user()->name;}}" class="form-control" disabled>
                        </div>

            
                            <input type="text" name="user_id" value="{{auth()->user()->id;}}" class="form-control" hidden>



                        <!-- Submit -->
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-lg btn-primary px-5 shadow">
                                <i class="bi bi-check-circle me-2"></i>Submit Form
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
 </div>
@endsection









<script>
    document.addEventListener('DOMContentLoaded', function () {
        const provinceSelect = document.getElementById('province');
        const municipalitySelect = document.getElementById('municipality');
        const barangaySelect = document.getElementById('barangay');

        // Load provinces on page load
        fetch('https://psgc.gitlab.io/api/provinces/')
            .then(res => res.json())
            .then(data => {
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.setAttribute('data-code', province.code);
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            });

        // Load municipalities/cities on province change
        provinceSelect.addEventListener('change', function () {
            municipalitySelect.innerHTML = '<option value="">Select Municipality</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            
            const selected = this.options[this.selectedIndex];
            const provinceCode = selected.getAttribute('data-code');

            fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(municipality => {
                        const option = document.createElement('option');
                        option.value = municipality.name;
                        option.setAttribute('data-code', municipality.code);
                        option.textContent = municipality.name;
                        municipalitySelect.appendChild(option);
                    });
                });
        });

        // Load barangays on municipality/city change
        municipalitySelect.addEventListener('change', function () {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

            const selected = this.options[this.selectedIndex];
            const cityCode = selected.getAttribute('data-code');

            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.name;
                        option.textContent = barangay.name;
                        barangaySelect.appendChild(option);
                    });
                });
        });
    });
</script>
