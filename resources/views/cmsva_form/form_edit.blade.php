@extends('layouts.app')
@section('content')
 <div style="background-color: #f3f6fa; min-height: 100vh; padding-top: 60px; padding-bottom: 40px;">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="card-body">

                <!-- Header / Title -->
                <!-- Form Header -->
                <div class="mb-4 text-center">
                    <h3 class="text-primary fw-bold mb-1">
                        <i class="bi bi-clipboard-check-fill me-2"></i>
                        Material Source Inventory Form
                    </h3>
                    <p class="text-muted">Please fill in all required information accurately.</p>
                </div>

                <!-- Form Start -->
              <form action="{{route('edit', $materialSource->id)}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')

                    <div class="row g-4">

                        <!-- Basic Info -->
                        <div class="col-12">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-geo-alt-fill me-2"></i>Basic Information
                            </h5>
                            <hr>
                        </div>

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
                                        <option value="" disabled {{ old($name, $materialSource->$name ?? '') == '' ? 'selected' : '' }}>Select source type</option>
                                        <option value="River" {{ old($name, $materialSource->$name ?? '') == 'River' ? 'selected' : '' }}>River</option>
                                        <option value="Mountain" {{ old($name, $materialSource->$name ?? '') == 'Mountain' ? 'selected' : '' }}>Mountain</option>
                                    </select>
                                @else
                                    <input type="text" name="{{ $name }}" class="form-control"
                                        value="{{ old($name, $materialSource->$name ?? '') }}" required>
                                @endif
                            </div>
                        @endforeach

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Recommendation for Future Use</label>
                            <textarea name="future_use_recommendation" class="form-control" rows="2">{{ old('future_use_recommendation', $materialSource->future_use_recommendation ?? '') }}</textarea>
                        </div>

                        <!-- Location  -->

                            <div class="col-md-4">
                                <label class="form-label text-primary fw-semibold">Province</label>
                                <select  id="province" name="province" class="form-select" required>
                                    <option value="">Select Province</option>
                                    {{-- Populate via JavaScript--}}
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-primary fw-semibold">Municipality/City</label>
                                <select id="municipality" name="municipality" class="form-select" required>
                                    <option value="">Select Municipality/City</option>
                                    {{-- Populate via JavaScript--}}
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-primary fw-semibold">Barangay</label>
                                <select id="barangay" name="barangay" class="form-select" required>
                                    <option value="">Select Barangay</option>
                                    {{-- Populate via JavaScript--}}
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
                                <option value="yes" {{ old('renewability', $materialSource->renewability ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ old('renewability', $materialSource->renewability ?? '') == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <!-- Site & Equipment -->
                        <div class="col-12 pt-3">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-building me-2"></i>Site & Equipment Information
                            </h5>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Processing Plant and/or Equipment on Site</label>
                            <textarea name="processing_plant_info" class="form-control" rows="2">{{ old('processing_plant_info', $materialSource->processing_plant_info ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Observation and Other Info</label>
                            <textarea name="observations" class="form-control" rows="2">{{ old('observations', $materialSource->observations ?? '') }}</textarea>
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
                                    <input type="text" name="latitude" value="{{ old('latitude', $materialSource->latitude ?? '') }}" id="latitude" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" name="longitude" value="{{ old('longitude', $materialSource->longitude ?? '') }}" id="longitude" class="form-control" required>
                                </div>
                            </div>
                        </div>


                        <!-- Permit & Test Results -->
                        <div class="col-12 pt-3">
                            <h5 class="text-orange fw-semibold">
                                <i class="bi bi-folder2-open me-2"></i>Permit & Test Results
                            </h5>
                            <hr>
                        </div>

                        <div class="col-md-6">

                            <label class="form-label text-primary fw-semibold">Approved Quarry Permit (Upload)</label>
                            @if (!empty($materialSource->quarry_permit))
                            <a  class="btn btn-sm btn-success" href="{{ asset('storage/uploads/' . $materialSource->quarry_permit) }}" target="_blank">Download</a>
                            @endif
                            <input type="file" name="quarry_permit" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Quarry Permit - Date Issued</label>
                            <input type="date" name="quarry_permit_date" class="form-control"
                                value="{{ old('quarry_permit_date', $materialSource->quarry_permit_date ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Name of Owner/Permittee</label>
                            <input type="text" name="permittee_name" class="form-control"
                                value="{{ old('permittee_name', $materialSource->permittee_name ?? '') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-primary fw-semibold">Quality Test Result - Attachment</label>
                            @if (!empty($materialSource->quality_test_attachment))
                            <a  class="btn btn-sm btn-success" href="{{ asset('storage/uploads/' . $materialSource->quality_test_attachment) }}" target="_blank">Download</a>
                            @endif
                            <input type="file" name="quality_test_attachment" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Quality Test Result - Date</label>
                            <input type="date" name="quality_test_date" class="form-control"
                                value="{{ old('quality_test_date', $materialSource->quality_test_date ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Result (Passed/Failed)</label>
                            <select name="quality_test_result" class="form-select">
                                <option value="">Select</option>
                                <option value="Passed" {{ old('quality_test_result', $materialSource->quality_test_result ?? '') == 'Passed' ? 'selected' : '' }}>Passed</option>
                                <option value="Failed" {{ old('quality_test_result', $materialSource->quality_test_result ?? '') == 'Failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-primary fw-semibold">Prepared By</label>
                            <input type="text" name="prepared_by" class="form-control"
                                value="{{ auth()->user()->name }}" disabled>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ isset($materialSource) ? 'Update' : 'Submit' }} Form
                        </button>
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

        // These values come from Laravel (old input or model data)
        const selectedProvince = @json(old('province', $materialSource->province ?? ''));
        const selectedMunicipality = @json(old('municipality', $materialSource->municipality ?? ''));
        const selectedBarangay = @json(old('barangay', $materialSource->barangay ?? ''));

        // Load provinces
        fetch('https://psgc.gitlab.io/api/provinces/')
            .then(res => res.json())
            .then(data => {
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.setAttribute('data-code', province.code);
                    option.textContent = province.name;
                    if (province.name === selectedProvince) {
                        option.selected = true;
                    }
                    provinceSelect.appendChild(option);
                });

                if (selectedProvince) {
                    // Trigger municipality fetch
                    provinceSelect.dispatchEvent(new Event('change'));
                }
            });

        // Load municipalities when province changes
        provinceSelect.addEventListener('change', function () {
            municipalitySelect.innerHTML = '<option value="">Select Municipality</option>';
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

            const selected = this.options[this.selectedIndex];
            const provinceCode = selected.getAttribute('data-code');

            if (!provinceCode) return;

            fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(municipality => {
                        const option = document.createElement('option');
                        option.value = municipality.name;
                        option.setAttribute('data-code', municipality.code);
                        option.textContent = municipality.name;
                        if (municipality.name === selectedMunicipality) {
                            option.selected = true;
                        }
                        municipalitySelect.appendChild(option);
                    });

                    if (selectedMunicipality) {
                        municipalitySelect.dispatchEvent(new Event('change'));
                    }
                });
        });

        // Load barangays when municipality changes
        municipalitySelect.addEventListener('change', function () {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

            const selected = this.options[this.selectedIndex];
            const cityCode = selected.getAttribute('data-code');

            if (!cityCode) return;

            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(barangay => {
                        const option = document.createElement('option');
                        option.value = barangay.name;
                        option.textContent = barangay.name;
                        if (barangay.name === selectedBarangay) {
                            option.selected = true;
                        }
                        barangaySelect.appendChild(option);
                    });
                });
        });
    });
</script>