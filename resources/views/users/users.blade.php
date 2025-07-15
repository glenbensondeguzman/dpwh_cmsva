@extends('layouts.app')
@section('content')
<div style="background-color: #f3f6fa; min-height: 100vh; padding-top: 60px; padding-bottom: 40px;">
  <div class="container">
    <div class="card shadow-lg border-0 rounded-4 p-4">
      <div class="card-body">
        

<div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-3 shadow-sm border border-primary-subtle">
  <div>
    <h3 class="text-primary fw-bold mb-1 d-flex align-items-center">
      <i class="bi bi-people-fill me-2 fs-3"></i>
      <span>Users Module</span>
    </h3>
    <p class="text-muted mb-0">Manage registered users in the system with ease and clarity.</p>
  </div>

  <a href="{{ route('users.add.form') }}" class="btn btn-outline-primary d-flex align-items-center">
    <i class="bi bi-person-plus-fill me-2"></i>
    Add User
  </a>
</div>


        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
          <table class="table table-hover table-striped align-middle text-center">
            <thead class="table-primary">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Photo</th>
                <th scope="col">Name</th>
                <th scope="col">Role</th>                
                <th scope="col">Office/Unit</th>
                <th scope="col">Contact Numer</th>
                <th scope="col">Email</th>
                <th scope="col">Region</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <img src="{{ asset('storage/uploads/'.$user->photo) }}" width="50" height="50" class="rounded-circle border shadow-sm" alt="Profile">
                </td>
                <td>{{ $user->name }}</td>
                <td>
                @if($user->role_id == 1)
                  Central Office
                @elseif($user->role_id == 2)
                  Regional Office
                @elseif($user->role_id == 3)
                  District Engineering Office
                @else
                  Unknown
                @endif
              </td>
                <td>{{ $user->office_unit}}</td>
                <td>{{ $user->contact_number }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->region }}</td>
                <td>
                  <a href="{{ route('users.edit.form', $user->id) }}" class="btn btn-warning btn-sm me-1">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $user->id }}">
                    <i class="bi bi-trash3-fill"></i> Delete
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-btn').forEach(button => {
      button.addEventListener('click', function () {
        const userId = this.getAttribute('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "This action cannot be undone!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`/users/delete/${userId}`, {
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
