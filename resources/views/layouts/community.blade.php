<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Community Dashboard - CESO')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* HEADER */
        .header-bar {
            background: #0a3d62;
            color: #fff;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .25);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-bar h4 {
            margin: 0;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-actions span {
            font-size: 14px;
        }

        .header-actions a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .header-actions a:hover {
            color: #e5e7eb;
        }

        /* CONTAINER */
        .dashboard-container {
            display: flex;
            min-height: calc(100vh - 65px);
        }

        /* SIDEBAR */
        .sidebar {
            background: #0f172a;
            color: #e5e7eb;
            width: 250px;
            padding-top: 15px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, .1);
            display: flex;
            flex-direction: column;
        }

        .sidebar a {
            color: #e5e7eb;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 4px 8px;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 14px;
        }

        .sidebar a i {
            width: 20px;
            margin-right: 10px;
        }

        .sidebar a.active {
            background: #2563eb;
            color: white;
            padding-left: 14px;
            border-left: 3px solid #3b82f6;
        }

        .sidebar a:hover {
            background: #1e293b;
            padding-left: 18px;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .content-header h3 {
            margin: 0;
            font-weight: 700;
            color: #0f172a;
        }

        /* STATS */
        .stat-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        }

        .stat-card .card-body {
            padding: 20px;
        }

        .stat-card h6 {
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            margin: 0;
            font-weight: 700;
            color: #0a3d62;
            font-size: 28px;
        }

        /* CHARTS */
        .chart-placeholder {
            height: 280px;
            background: linear-gradient(135deg, #e5e7eb 0%, #f3f4f6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
            border-radius: 12px;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px;
        }

        .card-body {
            padding: 20px;
        }

        .hamburger-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 8px 12px;
        }

        .sidebar.collapsed {
            display: none;
        }

        .sidebar.expanded {
            display: flex !important;
        }

        @media (max-width: 768px) {
            .hamburger-toggle {
                display: block;
            }

            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding-top: 0;
                display: none;
                overflow-x: auto;
                overflow-y: visible;
                height: auto;
                border-bottom: 1px solid #1e293b;
                position: absolute;
                top: 65px;
                left: 0;
                right: 0;
                background: #0f172a;
                z-index: 99;
            }

            .sidebar.expanded {
                display: flex !important;
            }

            .sidebar a {
                margin: 0 4px;
                padding: 10px 12px;
                white-space: nowrap;
                font-size: 0.85rem;
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
            }

            .header-bar {
                padding: 12px 16px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .header-bar h4 {
                font-size: 1rem;
            }

            .header-actions {
                font-size: 0.85rem;
            }

            .header-actions button {
                font-size: 0.85rem;
            }

            .stat-card h6 {
                font-size: 0.8rem;
            }

            .stat-card h3 {
                font-size: 1.5rem;
            }

            .chart-placeholder {
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            .header-bar {
                padding: 8px 12px;
                flex-direction: row;
                align-items: center;
                gap: 8px;
            }

            .header-bar h4 {
                font-size: 0.95rem;
                flex: 1;
            }

            .header-actions {
                display: flex;
                align-items: center;
                font-size: 0.8rem;
                gap: 8px;
            }

            .sidebar {
                position: fixed;
                top: 65px;
                left: 0;
                right: 0;
                z-index: 99;
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

            .content-header {
                flex-direction: column;
                gap: 8px;
            }

            .content-header h3 {
                font-size: 1.1rem;
            }

            .stat-card h3 {
                font-size: 1.25rem;
            }

            .stat-card h6 {
                font-size: 0.7rem;
            }

            .card-body {
                padding: 15px;
            }

            .card-header {
                padding: 12px;
            }

            .table-responsive {
                font-size: 0.8rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- HEADER -->
    <div class="header-bar">
        <button class="hamburger-toggle" id="sidebarToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <h4><i class="fas fa-leaf me-2" style="color: #3b82f6;"></i> Community Portal</h4>
        <div class="header-actions">
            <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light" style="border: none; background: transparent; cursor: pointer;">
                    <i class="fas fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <div class="sidebar" id="sidebar">
            <a href="{{ route('community.dashboard') }}" class="{{ request()->routeIs('community.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <a href="{{ route('community.activities') }}" class="{{ request()->routeIs('community.activities') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Activities
            </a>

            <a href="{{ route('announcements.index') }}" class="{{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> Announcements
            </a>

            <a href="{{ route('community.my-activities') }}" class="{{ request()->routeIs('community.my-activities') ? 'active' : '' }}">
                <i class="fas fa-user-check"></i> My Participation
            </a>

            <a href="{{ route('community.profile') }}" class="{{ request()->routeIs('community.profile') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> Profile Settings
            </a>
        </div>

        <!-- MAIN CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                // Toggle sidebar on hamburger click
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('expanded');
                });
                
                // Close sidebar when a link is clicked
                sidebar.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('expanded');
                    });
                });
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(event) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = sidebarToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('expanded')) {
                        sidebar.classList.remove('expanded');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
