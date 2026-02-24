<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'CESO Staff Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .header-bar {
            background: #0a3d62;
            color: white;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .25);
            flex-wrap: wrap;
            gap: 12px;
        }

        .header-bar h4 {
            font-size: 1.25rem;
            margin: 0;
        }

        .header-bar div:last-child {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            font-size: 0.875rem;
        }

        .header-bar button {
            padding: 6px 12px;
            font-size: 0.875rem;
        }

        /* Layout wrapper for flex */
        .layout-wrapper {
            display: flex;
            min-height: calc(100vh - 65px);
            flex-wrap: nowrap;
        }

        .sidebar {
            background: #0f172a;
            color: #e5e7eb;
            width: 250px;
            padding-top: 12px;
            padding-bottom: 20px;
            transition: all .3s ease;
            overflow-y: auto;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
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

        .sidebar a:hover {
            background: #1e293b;
            padding-left: 18px;
        }

        .sidebar a.active {
            background: #2563eb;
            color: white;
            padding-left: 14px;
            border-left: 3px solid #3b82f6;
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

        .content {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
            width: calc(100% - 250px);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .hamburger-toggle {
                display: block;
            }

            .layout-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                padding-top: 0;
                display: none;
                overflow-x: auto;
                overflow-y: visible;
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
                font-size: 0.85rem;
                white-space: nowrap;
            }

            .sidebar a i {
                margin-right: 6px;
            }

            .sidebar a.active {
                padding-left: 10px;
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

            .header-bar div:last-child {
                font-size: 0.8rem;
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
                flex-direction: row;
                align-items: center;
            }

            .header-bar h4 {
                font-size: 0.95rem;
                flex: 1;
            }

            .header-bar div:last-child {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .sidebar {
                position: fixed;
                top: 65px;
                left: 0;
                right: 0;
                z-index: 99;
            }

            .content {
                padding: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="header-bar">
        <button class="hamburger-toggle" id="sidebarToggle" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <h4 class="fw-bold mb-0">CESO Staff</h4>
        <div>
            <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button class="btn btn-outline-light">Logout</button>
            </form>
        </div>
    </div>

    <div class="layout-wrapper">
        <div class="sidebar" id="sidebar">
            <a href="{{ route('ceso.dashboard') }}" class="{{ request()->routeIs('ceso.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('ceso.activities.index') }}"
                class="{{ request()->routeIs('ceso.activities.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> Activities
            </a>
            <a href="{{ route('ceso.projects.index') }}"
                class="{{ request()->routeIs('ceso.projects.*') ? 'active' : '' }}">
                <i class="fas fa-diagram-project"></i> Projects
            </a>
            <a href="{{ route('ceso.announcements.index') }}"
                class="{{ request()->routeIs('ceso.announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> Announcements
            </a>
            <a href="{{ route('ceso.website.index') }}"
                class="{{ request()->routeIs('ceso.website.*') ? 'active' : '' }}">
                <i class="fas fa-globe"></i> Website
            </a>
            <a href="{{ route('ceso.profile') }}"
                class="{{ request()->routeIs('ceso.profile') ? 'active' : '' }}">
                <i class="fas fa-user-cog"></i> Profile Settings
            </a>
        </div>

        <div class="content w-100">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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