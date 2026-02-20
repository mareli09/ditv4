<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'CESO')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --ceso-blue: #0a3d62;
        }

        .navbar-ceso {
            background-color: var(--ceso-blue);
        }

        .navbar-ceso .nav-link {
            color: rgba(255, 255, 255, .85);
            font-weight: 500;
        }

        .navbar-ceso .nav-link:hover,
        .navbar-ceso .nav-link.active {
            color: #ffffff;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .navbar-ceso .navbar-brand {
            color: #ffffff;
        }

        .about-section {
            background: url("https://images.unsplash.com/photo-1521791136064-7986c2920216") center/cover no-repeat;
            color: white;
        }

        .about-overlay {
            background: rgba(0, 0, 0, .65);
            padding: 60px 20px;
            border-radius: 10px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            color: #0a3d62;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 8px;
            font-size: 18px;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-ceso px-4">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">CESO</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navMenu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#contact">Contact</a>
                </li>

                @guest
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

    @yield('content')

    <!-- FOOTER -->
    <footer class="bg-primary text-white text-center py-4">

        <div class="mb-3">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
        </div>

        <div class="mb-2">
            <a href="#" class="text-white mx-2">Privacy Policy</a>
            <a href="#" class="text-white mx-2">Terms of Service</a>
            <a href="#" class="text-white mx-2">Accessibility</a>
        </div>

        <div class="small">
            © {{ date('Y') }} Community Extension Services Office. All rights reserved.
        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>