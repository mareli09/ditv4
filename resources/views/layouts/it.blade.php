<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'IT Dashboard')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body { background: #f4f6f8; }

.header-bar {
    background: #0a3d62;
    color: #fff;
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar {
    background: #0f172a;
    color: #e5e7eb;
    min-height: calc(100vh - 65px);
    width: 250px;
    padding-top: 15px;
}

.sidebar a {
    color: #e5e7eb;
    text-decoration: none;
    display: block;
    padding: 10px 16px;
    margin: 4px 8px;
    border-radius: 10px;
}

.sidebar a.active { background: #2563eb; color: white; }
.sidebar a:hover { background: #1e293b; }

.content { padding: 25px; }
.stat-card { border-radius: 14px; }
</style>
</head>

<body>

<!-- HEADER -->
<div class="header-bar">
    <h4 class="fw-bold mb-0">IT Staff Portal</h4>
    <div>
        <span class="me-3">{{ auth()->user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn btn-link text-white text-decoration-none p-0">
                <i class="fas fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="{{ route('it.dashboard') }}"
           class="{{ request()->routeIs('it.dashboard') ? 'active' : '' }}">
            <i class="fas fa-gauge me-2"></i> Dashboard
        </a>

        <a href="{{ route('it.users') }}"
           class="{{ request()->routeIs('it.users') ? 'active' : '' }}">
            <i class="fas fa-users-cog me-2"></i> User Management
        </a>
    </div>

    <div class="container-fluid content">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
