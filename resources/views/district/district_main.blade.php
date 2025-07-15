@extends('layouts.app')
@section('content')
 <div style="background-color: #f3f6fa; min-height: 100vh; padding-top: 60px; padding-bottom: 40px;">
    <div class="container">
            <div class="container py-4">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body">

            <!-- Top Header: Title (left) + Archive Button (right) -->
<div class="d-flex justify-content-between align-items-center mb-4 px-2 py-2 bg-light rounded-3 shadow-sm border">
    <div>
        <h4 class="mb-0 text-primary fw-bold">
            <i class="bi bi-table me-2"></i>
            Material Source Inventory
            <span class="badge bg-primary bg-opacity-25 text-primary ms-2">{{ $totalMaterials }}</span>
        </h4>
    </div>

          <a href="{{ route('archived') }}" class="btn btn-info d-flex align-items-center">
              <i class="bi bi-archive-fill me-2"></i> Archive
          </a>
</div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                          <th>#</th>  
                          <th>Name of Material Source</th>
                            <th>Access Road</th>
                            <th>Directional Flow</th>
                            <th>Type of Source</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$material->material_source_name}}</td>
                            <td>{{$material->access_road}}</td>
                            <td>{{$material->directional_flow}}</td>
                            <td>{{$material->source_type}}</td>
                            <td>
                                @if ($material->user_id_validation == 3)
                                    <span class="badge bg-primary">Regional Approval</span>
                                @elseif ($material->user_id_validation == 2)
                                    @if ($material->status === 'Disapproved')
                                        <button type="button" class="badge bg-danger border-0" data-bs-toggle="modal" data-bs-target="#commentModal{{ $material->id }}">
                                            With comments by Region
                                        </button>
                                    @else
                                        <span class="badge bg-info text-dark">Central Approval</span>
                                    @endif
                                @elseif ($material->user_id_validation == 1)
                                    @if ($material->status === 'Disapproved')
                                        <button type="button" class="badge bg-danger border-0" data-bs-toggle="modal" data-bs-target="#commentModal{{ $material->id }}">
                                            With comments by Central
                                        </button>
                                    @else
                                        <span class="badge bg-success">Approved</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                              <button class="btn btn-sm btn-primary me-1 view-btn" 
                                  data-bs-toggle="modal"
                                  data-bs-target="#viewDataModal"
                                  data-name="{{ $material->material_source_name }}"
                                  data-road="{{ $material->access_road }}"
                                  data-flow="{{ $material->directional_flow }}"
                                  data-type="{{ $material->source_type }}"
                                  data-use="{{ $material->potential_uses }}"
                                  data-recommendation="{{ $material->future_use_recommendation }}"
                                  data-province="{{ $material->province }}"
                                  data-municipality="{{ $material->municipality }}"
                                  data-barangay="{{ $material->barangay }}"
                                  data-renewability="{{ $material->renewability }}"
                                  data-processing="{{ $material->processing_plant_info }}"
                                  data-observations="{{ $material->observations }}"
                                  data-permittee="{{ $material->permittee_name }}"
                                  data-quarry-date="{{ $material->quarry_permit_date }}"
                                  data-quality-date="{{ $material->quality_test_date }}"
                                  data-quality-result="{{ $material->quality_test_result }}"
                                  data-quarry-permit="{{ $material->quarry_permit }}"
                                  data-quality-attachment="{{ $material->quality_test_attachment }}"
                                  data-latitude="{{ $material->latitude }}"
                                  data-longitude="{{ $material->longitude }}"
                                  >
                                  <i class="bi bi-eye-fill me-1"></i> View
                              </button>
                            
                           
                               <a href="{{ route('edit.form', $material->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>

                              @if (in_array(auth()->user()->role_id, [1, 2]))
                              <button type="button" class="btn btn-sm btn-success glen" data-id="{{ $material->id }}" data-bs-toggle="modal" data-bs-target="#validateModal">
                                Validate
                              </button>
                              @endif

                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $material->id }}">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                        <!-- Add more rows here -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
    </div>
 </div>




 <!-- Modal -->
@foreach($materials as $material)
<div class="modal fade" id="commentModal{{ $material->id }}" tabindex="-1" aria-labelledby="commentModalLabel{{ $material->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="commentModalLabel{{ $material->id }}">Reviewer Comments</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{ $material->reason_status ?? 'No comments provided.' }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach




<!-- View Data Modal -->
<div class="modal fade" id="viewDataModal" tabindex="-1" aria-labelledby="viewDataModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewDataModalLabel">
          <i class="bi bi-info-circle-fill me-2"></i>Material Source Information
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-4">Name of Material Source</dt>
          <dd class="col-sm-8" id="modal-name">{{ $material->material_source_name ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Access Road to the Material Source</dt>
          <dd class="col-sm-8" id="modal-road">{{ $material->access_road ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Directional Flow of Access Road</dt>
          <dd class="col-sm-8" id="modal-flow">{{ $material->directional_flow ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Type of Source</dt>
          <dd class="col-sm-8" id="modal-type">{{ $material->source_type ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Potential Use/s</dt>
          <dd class="col-sm-8" id="modal-use">{{ $material->potential_uses ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Recommendation for Future Use</dt>
          <dd class="col-sm-8" id="modal-recommendation">{{ $material->future_use_recommendation ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Province</dt>
          <dd class="col-sm-8" id="modal-province">{{ $material->province ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Municipality/City</dt>
          <dd class="col-sm-8" id="modal-municipality">{{ $material->municipality ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Barangay</dt>
          <dd class="col-sm-8" id="modal-barangay">{{ $material->barangay ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Is the Source Renewable?</dt>
          <dd class="col-sm-8" id="modal-renewability">{{ $material->renewability ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Latitude</dt>
          <dd class="col-sm-8" id="modal-latitude">{{ $material->latitude ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Longitude</dt>
          <dd class="col-sm-8" id="modal-longitude">{{ $material->longitude ?? 'N/A' }}</dd>


           <dt class="col-sm-4">Source Location Map</dt>
            <dd class="col-sm-8" id="modal-longitude">
              <div id="modal-map" style="height: 250px; width: 100%; border-radius: 8px;"></div></dd>

          <dt class="col-sm-4">Processing Plant and/or Heavy Equipment</dt>
          <dd class="col-sm-8" id="modal-processing">{{ $material->processing_plant_info ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Observation and Other Info</dt>
          <dd class="col-sm-8" id="modal-observations">{{ $material->observations ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Name of Owner/Permittee</dt>
          <dd class="col-sm-8" id="modal-permittee">{{ $material->permittee_name ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Approved Quarry Permit - Date Issued</dt>
          <dd class="col-sm-8" id="modal-quarry-date">{{ $material->quarry_permit_date ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Approved Quarry Permit - Attachment</dt>
          <dd class="col-sm-8" id="modal-quarry-file">
            @if (!empty($material->quarry_permit))
              <a class="btn btn-sm btn-success" href="{{ asset('storage/uploads/' . $material->quarry_permit) }}" target="_blank">Download</a>
            @else
              N/A
            @endif
          </dd>

          <dt class="col-sm-4">Quality Test Result - Date</dt>
          <dd class="col-sm-8" id="modal-quality-date">{{ $material->quality_test_date ?? 'N/A' }}</dd>

          <dt class="col-sm-4">Quality Test Result - Attachment</dt>
          <dd class="col-sm-8" id="modal-quality-file">
            @if (!empty($material->quality_test_attachment))
              <a  class="btn btn-sm btn-success" href="{{ asset('storage/uploads/' . $material->quality_test_attachment) }}" target="_blank">Download</a>
            @else
              N/A
            @endif
          </dd>

          <dt class="col-sm-4">Quality Test Result (Passed/Failed)</dt>
          <dd class="col-sm-8" id="modal-quality-result">{{ $material->quality_test_result ?? 'N/A' }}</dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>





<!-- Validation Modal -->
<div class="modal fade" id="validateModal" tabindex="-1" aria-labelledby="validateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="validateModalLabel">Update Material Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Alert -->
        <div id="modal-alert" class="alert d-none" role="alert"></div>

        <!-- Hidden ID -->
        <input type="hidden" id="material-id">

        <!-- Status -->
        <div class="mb-3">
          <label for="status-select" class="form-label">Status</label>
          <select class="form-select" id="status-select">
            <option value="" selected disabled>Select status</option>
            <option value="Approved">Approved</option>
            <option value="Disapproved">Disapproved</option>
          </select>
        </div>

        <!-- Reason -->
        <div class="mb-3 d-none" id="reason-group">
          <label for="reason-textarea" class="form-label">Reason for Failure</label>
          <textarea class="form-control" id="reason-textarea" rows="3" placeholder="Enter reason..."></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="submit-validation" class="btn btn-success">Submit</button>
      </div>
    </div>
  </div>
</div>








@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.view-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('modal-name').textContent = this.dataset.name || 'N/A';
            document.getElementById('modal-road').textContent = this.dataset.road || 'N/A';
            document.getElementById('modal-flow').textContent = this.dataset.flow || 'N/A';
            document.getElementById('modal-province').textContent = this.dataset.province || 'N/A';
            document.getElementById('modal-municipality').textContent = this.dataset.municipality || 'N/A';
            document.getElementById('modal-barangay').textContent = this.dataset.barangay || 'N/A';
            document.getElementById('modal-type').textContent = this.dataset.type || 'N/A';
            document.getElementById('modal-use').textContent = this.dataset.use || 'N/A';
            document.getElementById('modal-recommendation').textContent = this.dataset.recommendation || 'N/A';
            document.getElementById('modal-renewability').textContent = this.dataset.renewability || 'N/A';
            document.getElementById('modal-processing').textContent = this.dataset.processing || 'N/A';
            document.getElementById('modal-observations').textContent = this.dataset.observations || 'N/A';
            document.getElementById('modal-permittee').textContent = this.dataset.permittee || 'N/A';
            document.getElementById('modal-quarry-date').textContent = this.dataset.quarryDate || 'N/A';
            document.getElementById('modal-quality-date').textContent = this.dataset.qualityDate || 'N/A';
            document.getElementById('modal-quality-result').textContent = this.dataset.qualityResult || 'N/A';
            document.getElementById('modal-latitude').textContent = this.dataset.latitude || 'N/A';
            document.getElementById('modal-longitude').textContent = this.dataset.longitude || 'N/A';

            // File download links
            const quarryFile = this.dataset.quarryPermit;
            const qualityFile = this.dataset.qualityAttachment;

            document.getElementById('modal-quarry-file').innerHTML = quarryFile
                ? `<a href="/storage/uploads/${quarryFile}" target="_blank">Download</a>`
                : 'N/A';

            document.getElementById('modal-quality-file').innerHTML = qualityFile
                ? `<a href="/storage/uploads/${qualityFile}" target="_blank">Download</a>`
                : 'N/A';
        });
    });
});
</script>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function () {
        const material_id = this.getAttribute('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "This action can be deleted. You can restore it in Archived.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`/delete/${material_id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              }
            })
            .then(response => response.json())
            .then(data => {
              Swal.fire('Deleted!', data.success, 'success')
              .then(() => window.location.reload());
            })
            .catch(error => {
              Swal.fire('Error', 'Something went wrong!', 'error');
            });
          }
        });
      });
    });
  });
</script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  const alertBox = $('#modal-alert');

  // Open modal and initialize fields
  $('.glen').on('click', function () {
    const materialId = $(this).data('id');
    $('#material-id').val(materialId);
    $('#status-select').val('');
    $('#reason-group').addClass('d-none');
    $('#reason-textarea').val('');
    alertBox.addClass('d-none').removeClass('alert-success alert-danger').text('');
  });

  // Toggle reason box
  $('#status-select').on('change', function () {
    if ($(this).val() === 'Disapproved') {
      $('#reason-group').removeClass('d-none');
    } else {
      $('#reason-group').addClass('d-none');
      $('#reason-textarea').val('');
    }
  });

  // Submit validation
  $('#submit-validation').on('click', function () {
    const materialId = $('#material-id').val();
    const status = $('#status-select').val();
    const reason_status = $('#reason-textarea').val().trim();

    alertBox.addClass('d-none').removeClass('alert-success alert-danger').text('');

    if (!status) {
      alertBox.removeClass('d-none').addClass('alert-danger').text('Please select a status.');
      return;
    }

    if (status === 'Disapproved' && !reason_status) {
      alertBox.removeClass('d-none').addClass('alert-danger').text('Please provide a reason for failure.');
      return;
    }

    $.ajax({
      url: `/materials/${materialId}`,
      method: 'PUT',
      data: { status, reason_status },
      success: function (response) {
        alertBox.removeClass('d-none alert-danger').addClass('alert-success').text('Status updated successfully.');
        setTimeout(() => {
          $('#validateModal').modal('hide');
          window.location.reload(); // Remove this if you want to update without reload
        }, 1500);
      },
      error: function () {
        alertBox.removeClass('d-none').addClass('alert-danger').text('An error occurred while updating.');
      }
    });
  });
});
</script>

