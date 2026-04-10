<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Panel</title>
    <!-- Simple UI with Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; color: white; padding-top: 20px; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active, .sidebar a.text-white { color: #fff; background-color: #495057; }
        .sidebar .submenu { font-size: 0.95em; }
        .sidebar-heading { padding: 10px 20px; font-size: 0.85em; text-transform: uppercase; color: #6c757d; font-weight: bold; }
        .main-content { padding: 20px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar">
                <h4 class="text-center mb-4">Operator Wikrama</h4>
                
                <div class="sidebar-heading">Menu</div>
                <a href="{{ route('staff.dashboard') }}" class="{{ Route::is('staff.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                
                <div class="sidebar-heading mt-3">Items Data</div>
                <a href="{{ route('staff.items') }}" class="submenu {{ Route::is('staff.items') ? 'active' : '' }}">
                    <i class="bi bi-box me-2"></i> Items
                </a>
                <a href="{{ route('staff.lending') }}" class="submenu {{ Route::is('staff.lending') ? 'active' : '' }}">
                    <i class="bi bi-journal-arrow-up me-2"></i> Lending
                </a>
                
                <div class="sidebar-heading mt-3">Accounts</div>
                <a data-bs-toggle="collapse" href="#collapseUsers" role="button" aria-expanded="false" aria-controls="collapseUsers" class="submenu d-flex align-items-center">
                    <i class="bi bi-people me-2"></i> <span>Users</span>
                    <i class="bi bi-chevron-down ms-auto" style="font-size: 0.8em;"></i>
                </a>
                <div class="collapse {{ Route::is('staff.users.*') ? 'show' : '' }}" id="collapseUsers">
                    <a href="{{ route('staff.users.edit') }}" class="submenu ms-3 ps-3 border-start border-secondary {{ Route::is('staff.users.edit') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill me-2"></i> Edit
                    </a>
                </div>

                <div class="mt-5">
                    <a href="#" class="text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div></div>
                    <div class="text-end">
                        <div class="text-muted small">{{ now()->format('d F Y') }}</div>
                        <strong>Welcome back, {{ Auth::user()->name ?? 'Operator Wikrama' }}</strong>
                    </div>
                </div>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>