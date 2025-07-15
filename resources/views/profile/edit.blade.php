@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Profile Header -->
            <div class="mb-4 p-3 bg-light rounded-3 shadow-sm border border-primary-subtle">
                <h3 class="text-primary fw-bold mb-1 d-flex align-items-center">
                    <i class="bi bi-person-circle me-2 fs-3"></i>
                    <span>My Profile</span>
                </h3>
                <p class="text-muted mb-0">Update your personal information and account details.</p>
            </div>

            <!-- Profile Card -->
            <div class="card shadow rounded-4 border-0">
                <div class="card-body">

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-1"></i> Profile updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-primary">Full Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', auth()->user()->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-primary">Email Address</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', auth()->user()->email) }}" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="office_unit" class="form-label fw-semibold text-primary">Office Unit</label>
                            <input type="text" name="office_unit" id="office_unit"
                                class="form-control @error('office_unit') is-invalid @enderror"
                                value="{{ old('office_unit', auth()->user()->office_unit) }}" required autocomplete="office_unit">
                            @error('office_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label fw-semibold text-primary">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number"
                                class="form-control @error('contact_number') is-invalid @enderror"
                                value="{{ old('contact_number', auth()->user()->contact_number) }}" required autocomplete="contact_number">
                            @error('office_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="mb-3">
                            <label for="region" class="form-label fw-semibold text-primary">Region</label>
                            <input type="text" name="region" id="region"
                                class="form-control @error('contact_number') is-invalid @enderror"
                                value="{{ old('region', auth()->user()->region) }}" required autocomplete="region" disabled>
                            
                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="form-text text-muted">
                                This field is locked. Only the <strong>Administrator</strong> can change your assigned region. If region is NONE, please contact your Administrator.
                            </div>
                        </div>

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                            <div class="mb-3">
                                <p class="text-warning small d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Your email address is unverified.
                                    <button form="send-verification" class="btn btn-sm btn-outline-warning ms-2">
                                        Resend Verification Email
                                    </button>
                                </p>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save-fill me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>

                    <!-- Hidden Verification Form -->
                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
                        <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-none">
                            @csrf
                        </form>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
