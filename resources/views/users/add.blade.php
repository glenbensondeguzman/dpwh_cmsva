@extends('layouts.app')
@section('content')
 <div style="background-color: #f3f6fa; min-height: 100vh; padding-top: 60px; padding-bottom: 40px;">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="card-body">



                <div class="mb-4 p-3 bg-light rounded-3 shadow-sm border border-primary-subtle">
  <h3 class="text-primary fw-bold mb-1 d-flex align-items-center">
    <i class="bi bi-person-plus-fill me-2 fs-3"></i>
    <span>Create New User</span>
  </h3>
  <p class="text-muted mb-0">Please fill out the form to add a new user account.</p>
</div>

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>There were some issues with your input:</strong>
    <ul class="mb-0 mt-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<form action="{{ route('users.add') }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white rounded-3 shadow-sm border">
  @csrf

  <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>

  
    <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Office/Unit</label>
    <input type="text" name="office_unit" class="form-control"  required>
  </div>

    <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Contact Number</label>
    <input type="text" name="contact_number" class="form-control"  required>
  </div>

   <!-- Region Dropdown -->
@php
    $userRole = Auth::user()->role_id;
    $userRegion = Auth::user()->region;
@endphp

<div class="mb-3"> 
  <label class="form-label">Region</label>
  <select name="region" class="form-select" required {{ $userRole == 2 ? 'disabled' : '' }}>
    <option value="">Select Region</option>
    <option value="NCR" {{ $userRegion == 'NCR' ? 'selected' : '' }}>NCR</option>
    <option value="NIR" {{ $userRegion == 'NIR' ? 'selected' : '' }}>NIR</option>
    <option value="CAR" {{ $userRegion == 'CAR' ? 'selected' : '' }}>CAR</option>
    <option value="Region 1" {{ $userRegion == 'Region 1' ? 'selected' : '' }}>Region 1</option>
    <option value="Region 2" {{ $userRegion == 'Region 2' ? 'selected' : '' }}>Region 2</option>
    <option value="Region 3" {{ $userRegion == 'Region 3' ? 'selected' : '' }}>Region 3</option>
    <option value="Region 4-A" {{ $userRegion == 'Region 4-A' ? 'selected' : '' }}>Region 4-A</option>
    <option value="Region 4-B" {{ $userRegion == 'Region 4-B' ? 'selected' : '' }}>Region 4-B</option>
    <option value="Region 5" {{ $userRegion == 'Region 5' ? 'selected' : '' }}>Region 5</option>
    <option value="Region 6" {{ $userRegion == 'Region 6' ? 'selected' : '' }}>Region 6</option>
    <option value="Region 7" {{ $userRegion == 'Region 7' ? 'selected' : '' }}>Region 7</option>
    <option value="Region 8" {{ $userRegion == 'Region 8' ? 'selected' : '' }}>Region 8</option>
    <option value="Region 9" {{ $userRegion == 'Region 9' ? 'selected' : '' }}>Region 9</option>
    <option value="Region 10" {{ $userRegion == 'Region 10' ? 'selected' : '' }}>Region 10</option>
    <option value="Region 11" {{ $userRegion == 'Region 11' ? 'selected' : '' }}>Region 11</option>
    <option value="Region 12" {{ $userRegion == 'Region 12' ? 'selected' : '' }}>Region 12</option>
    <option value="Region 13" {{ $userRegion == 'Region 13' ? 'selected' : '' }}>Region 13</option>
  </select>

  @if ($userRole == 2)
    <!-- Hidden input to still pass region value even if disabled -->
    <input type="hidden" name="region" value="{{ $userRegion }}">
  @endif
</div>



<div class="mb-3"> 
    <label for="role_id" class="form-label fw-semibold text-primary">Select Role</label>
    <select name="role_id" id="role_id" class="form-select" required>
        <option value="">-- Choose Role --</option>

        {{-- If user is Central Office (role_id = 1), show all --}}
        @if($userRole == 1)
            <option value="1">Central Office</option>
        @endif

        {{-- Show if user is Central or Regional --}}
        @if($userRole == 1 || $userRole == 2)
            <option value="2">Regional Office</option>
        @endif

        {{-- Always allow selection of District Engineering Office --}}
        <option value="3">District Engineering Office</option>
    </select>
</div>


  <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Photo</label>
    <input type="file" name="photo" class="form-control" required>
  </div>
  

  <div class="text-end">
    <button type="submit" class="btn btn-primary px-4">
      <i class="bi bi-check-circle-fill me-1"></i> Add User
    </button>
  </div>
</form>



              
            </div>
        </div>
      </div>
 </div>
@endsection


