<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'IT Dashboard')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
html, body { 
    height: 100%;
    margin: 0; 
    padding: 0; 
}

body { 
    background: #f4f6f8;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.header-bar {
    background: #0a3d62;
    color: #fff;
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: auto;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, .25);
}

.header-bar h4 {
    margin: 0;
    font-size: 1.25rem;
}

.header-bar > div:last-child {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.95rem;
    flex-wrap: wrap;
}

.header-bar button {
    padding: 6px 12px;
    font-size: 0.875rem;
}

.layout-wrapper {
    display: flex;
    flex-wrap: nowrap;
    height: calc(100vh - 65px);
    width: 100%;
    min-height: 400px;
}

.sidebar {
    background: #0f172a;
    color: #e5e7eb;
    width: 250px;
    padding-top: 12px;
    flex-shrink: 0;
    overflow-y: auto;
}

.sidebar a {
    color: #e5e7eb;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 10px 16px;
    margin: 4px 8px;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all .2s;
}

.sidebar a i {
    margin-right: 10px;
    width: 18px;
    text-align: center;
}

.sidebar a.active { 
    background: #2563eb;
    color: white;
}

.sidebar a:hover { 
    background: #1e293b;
    padding-left: 18px;
}

.content { 
    padding: 20px;
    flex: 1;
    overflow-y: auto;
    width: calc(100% - 250px);
    background: #f4f6f8;
}

.stat-card { 
    border-radius: 12px;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .layout-wrapper {
        flex-direction: column;
        height: auto;
    }

    .sidebar {
        width: 100%;
        min-height: auto;
        padding-top: 0;
        display: flex;
        flex-wrap: wrap;
        overflow-x: auto;
        overflow-y: visible;
        border-bottom: 1px solid #1e293b;
    }

    .sidebar a {
        margin: 0 4px;
        padding: 10px 12px;
        font-size: 0.85rem;
        white-space: nowrap;
        border-radius: 0;
    }

    .sidebar a i {
        margin-right: 6px;
    }

    .sidebar a.active {
        border-left: none;
        border-bottom: 3px solid #3b82f6;
        border-radius: 0;
    }

    .content {
        padding: 15px;
        width: 100%;
    }

    .header-bar {
        padding: 10px 12px;
    }

    .header-bar h4 {
        font-size: 1rem;
    }

    .header-bar > div:last-child {
        font-size: 0.85rem;
        gap: 8px;
    }

    .header-bar button {
        padding: 5px 10px;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .header-bar {
        padding: 8px;
        flex-direction: column;
        align-items: flex-start;
    }

    .header-bar h4 {
        font-size: 0.95rem;
        width: 100%;
    }

    .header-bar > div:last-child {
        width: 100%;
        justify-content: space-between;
        font-size: 0.8rem;
    }

    .sidebar {
        order: -1;
    }

    .sidebar a {
        padding: 8px 10px;
        font-size: 0.75rem;
        margin: 2px 2px;
    }

    .sidebar a i {
        margin-right: 4px;
    }

    .content {
        padding: 12px;
    }
}
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

<div class="layout-wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="{{ route('it.dashboard') }}"
           class="{{ request()->routeIs('it.dashboard') ? 'active' : '' }}">
            <i class="fas fa-gauge me-2"></i> Dashboard
        </a>

        <a href="{{ route('it.users.index') }}"
           class="{{ request()->routeIs('it.users.*') ? 'active' : '' }}">
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
