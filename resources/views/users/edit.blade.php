@extends('layouts.app')
@section('content')
 <div style="background-color: #f3f6fa; min-height: 100vh; padding-top: 60px; padding-bottom: 40px;">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <div class="card-body">


                <div class="mb-4 p-3 bg-light rounded-3 shadow-sm border border-primary-subtle">
  <h3 class="text-primary fw-bold mb-1 d-flex align-items-center">
    <i class="bi bi-pencil-square me-2 fs-3"></i>
    <span>Update User</span>
  </h3>
  <p class="text-muted mb-0">Modify the user information below and save your changes.</p>
</div>

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>There were some problems with your input:</strong>
    <ul class="mb-0 mt-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<form action="{{ route('users.edit', $user->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white rounded-3 shadow-sm border">
  @csrf
  @method('PUT')

  <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
  </div>

  <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
  </div>

    <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Office/Unit</label>
    <input type="text" name="office_unit" class="form-control" value="{{ old('office_unit', $user->office_unit) }}" required>
  </div>

    <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Contact Number</label>
    <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $user->contact_number) }}" required>
  </div>

<div class="mb-3">
  <label for="region" class="form-label fw-semibold text-primary">Region</label>
  <select name="region" id="region" value="{{$user->region}}" class="form-select @error('region') is-invalid @enderror" disabled>
    <option value="">Select Region</option>
    <option value="NCR" {{ old('region', $user->region ?? '') == 'NCR' ? 'selected' : '' }}>NCR</option>
    <option value="NIR" {{ old('region', $user->region ?? '') == 'NIR' ? 'selected' : '' }}>NIR</option>
    <option value="CAR" {{ old('region', $user->region ?? '') == 'CAR' ? 'selected' : '' }}>CAR</option>
    <option value="Region 1" {{ old('region', $user->region ?? '') == 'Region 1' ? 'selected' : '' }}>Region 1</option>
    <option value="Region 2" {{ old('region', $user->region ?? '') == 'Region 2' ? 'selected' : '' }}>Region 2</option>
    <option value="Region 3" {{ old('region', $user->region ?? '') == 'Region 3' ? 'selected' : '' }}>Region 3</option>
    <option value="Region 4-A" {{ old('region', $user->region ?? '') == 'Region 4-A' ? 'selected' : '' }}>Region 4-A</option>
    <option value="Region 4-B" {{ old('region', $user->region ?? '') == 'Region 4-B' ? 'selected' : '' }}>Region 4-B</option>
    <option value="Region 5" {{ old('region', $user->region ?? '') == 'Region 5' ? 'selected' : '' }}>Region 5</option>
    <option value="Region 6" {{ old('region', $user->region ?? '') == 'Region 6' ? 'selected' : '' }}>Region 6</option>
    <option value="Region 7" {{ old('region', $user->region ?? '') == 'Region 7' ? 'selected' : '' }}>Region 7</option>
    <option value="Region 8" {{ old('region', $user->region ?? '') == 'Region 8' ? 'selected' : '' }}>Region 8</option>
    <option value="Region 9" {{ old('region', $user->region ?? '') == 'Region 9' ? 'selected' : '' }}>Region 9</option>
    <option value="Region 10" {{ old('region', $user->region ?? '') == 'Region 10' ? 'selected' : '' }}>Region 10</option>
    <option value="Region 11" {{ old('region', $user->region ?? '') == 'Region 11' ? 'selected' : '' }}>Region 11</option>
    <option value="Region 12" {{ old('region', $user->region ?? '') == 'Region 12' ? 'selected' : '' }}>Region 12</option>
    <option value="Region 13" {{ old('region', $user->region ?? '') == 'Region 13' ? 'selected' : '' }}>Region 13</option>
  </select>
  @error('region')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>







@php
    $userRole = Auth::user()->role_id;
    $selectedRole = old('role_id', $user->role_id ?? '');
@endphp

<div class="mb-3"> 
    <label for="role" class="form-label fw-semibold text-primary">Select Role</label>
    <select name="role_id" id="role" class="form-select" required>
        <option value="{{ old($user->role_id)}}">-- Choose Role --</option>

        {{-- Show Central Office option only if current user is Central Office --}}
        @if($userRole == 1)
            <option value="1"  {{ old('role_id', $user->role_id ?? '') == '1' ? 'selected' : '' }}>Central Office</option>
        @endif

        {{-- Show Regional Office option if user is Central or Regional --}}
        @if($userRole == 1 || $userRole == 2)
            <option value="2" {{ old('role_id', $user->role_id ?? '') == '2' ? 'selected' : '' }}>Regional Office</option>
        @endif

        {{-- Show District Office for all --}}
        <option value="3" {{ old('role_id', $user->role_id ?? '') == '3' ? 'selected' : '' }}>District Engineering Office</option>
    </select>
</div>

  <div class="mb-3">
    <label class="form-label fw-semibold text-primary">Photo</label>
    @if ($user->photo)
      <div class="mb-2">
        <img src="{{ asset('storage/uploads/' . $user->photo) }}" width="80" class="rounded shadow-sm border">
      </div>
    @endif
    <input type="file" name="photo" class="form-control" accept="image/*">
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-success px-4">
      <i class="bi bi-save-fill me-1"></i> Update User
    </button>
  </div>
</form>


              
            </div>
        </div>
      </div>
 </div>
@endsection


