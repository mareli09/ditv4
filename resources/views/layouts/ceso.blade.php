<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'CESO Staff Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body{ background:#f4f6f8; }
        .header-bar{ background:#0a3d62; color:white; padding:14px 18px; display:flex; justify-content:space-between; align-items:center; box-shadow:0 2px 4px rgba(0,0,0,.25); }
        .sidebar{ background:#0f172a; color:#e5e7eb; min-height:calc(100vh - 65px); width:250px; padding-top:15px; transition:.3s; }
        .sidebar a{ color:#e5e7eb; text-decoration:none; display:block; padding:10px 16px; margin:4px 8px; border-radius:10px; }
        .sidebar a:hover{ background:#1e293b; }
        .sidebar a.active{ background:#2563eb; color:white; }
        .content{ padding:25px; }
    </style>

    @stack('styles')
</head>
<body>

<div class="header-bar">
    <div class="d-flex align-items-center gap-2">
        <h4 class="fw-bold mb-0">CESO Staff</h4>
    </div>

    <div>
        <span class="me-3">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button class="btn btn-outline-light">Logout</button>
        </form>
    </div>
</div>

<div class="d-flex">
    <div class="sidebar">
        <a href="{{ route('ceso.dashboard') }}" class="{{ request()->routeIs('ceso.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line me-2"></i> Dashboard
        </a>
        <a href="{{ route('ceso.activities.index') }}" class="{{ request()->routeIs('ceso.activities.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt me-2"></i> Activities
        </a>
        <a href="#">
            <i class="fas fa-folder-open me-2"></i> Projects
        </a>
        <a href="#">
            <i class="fas fa-bullhorn me-2"></i> Announcements
        </a>
    </div>

    <div class="content w-100">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>