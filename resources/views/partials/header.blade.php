<!-- DPWH Themed Fixed Header with Navigation -->
<header class="bg-dark shadow-sm py-3 fixed-top">
    <div class="container d-flex flex-wrap align-items-center justify-content-between text-white">
        <!-- Logo and Title -->
        <div class="d-flex align-items-center mb-2 mb-lg-0">
            <img src="{{ asset('/assets/dpwh-logo.png') }}" alt="DPWH Logo" height="60" class="me-3 bg-white p-1 rounded">
            <div>
                <h5 class="mb-0 fw-bold" style="color: #FFA500;">Department of Public Works and Highways

                </h5>
                <small class="text-light">Construction Material Sources Validation Web Application - 
                @if(auth()->user()->role_id == 1)
                  Central Office
                @elseif(auth()->user()->role_id == 2)
                  Regional Office
                @elseif(auth()->user()->role_id == 3)
                  District Engineering Office
                @else
                  Unknown
                @endif
                </small>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="d-flex gap-3 align-items-center">
         @auth

                 <a href="{{route('dashboard')}}"  class="text-decoration-none fw-semibold {{ request()->routeIs('dashboard') ? 'text-warning' : 'text-white' }}">List of Inventory</a>

            @if (auth()->user()->role_id == 3)
                 <a href="{{route('add.form')}}" class="text-decoration-none fw-semibold {{ request()->routeIs('add.form') ? 'text-warning' : 'text-white' }}">Inventory Form</a>
            @endif

            @if (in_array(auth()->user()->role_id, [1, 2]))
            <a href="{{route('users.users')}}" class="text-decoration-none fw-semibold {{ request()->routeIs('users.users') ? 'text-warning' : 'text-white' }}">Manage Users</a>
            @endif
            
            <a href="{{route('profile.edit')}}" class="text-decoration-none fw-semibold {{ request()->routeIs('profile.edit') ? 'text-warning' : 'text-white' }}">My Profile</a>
            
            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">Log out</button>
            </form>
        @endauth
        </nav>
    </div>
</header>