<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Phu Xuan Events'))</title>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Premium Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS Variables and Styling -->
    <style>
        :root {
            --pxu-primary: #1B2A4A;
            --pxu-secondary: #E8450A;
            --pxu-primary-light: #2c426f;
            --pxu-secondary-light: #ff6028;
            --pxu-bg: #f8fafc;
            --pxu-dark: #0f172a;
            --pxu-gray: #64748b;
            --pxu-card-bg: rgba(255, 255, 255, 0.9);
            --pxu-shadow: 0 10px 30px -10px rgba(27, 42, 74, 0.08);
            --pxu-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--pxu-bg);
            color: var(--pxu-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styling */
        .navbar-pxu {
            background-color: var(--pxu-primary);
            border-bottom: 3px solid var(--pxu-secondary);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-pxu .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff !important;
            font-size: 1.5rem;
        }

        .navbar-pxu .navbar-brand span {
            color: var(--pxu-secondary);
        }

        .navbar-pxu .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: var(--pxu-transition);
        }

        .navbar-pxu .nav-link:hover, 
        .navbar-pxu .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Premium Buttons */
        .btn-pxu-primary {
            background-color: var(--pxu-primary);
            color: #ffffff;
            font-weight: 600;
            border: 2px solid var(--pxu-primary);
            border-radius: 8px;
            transition: var(--pxu-transition);
        }

        .btn-pxu-primary:hover {
            background-color: var(--pxu-primary-light);
            border-color: var(--pxu-primary-light);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-pxu-secondary {
            background-color: var(--pxu-secondary);
            color: #ffffff;
            font-weight: 600;
            border: 2px solid var(--pxu-secondary);
            border-radius: 8px;
            transition: var(--pxu-transition);
        }

        .btn-pxu-secondary:hover {
            background-color: var(--pxu-secondary-light);
            border-color: var(--pxu-secondary-light);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-pxu-outline {
            background-color: transparent;
            color: var(--pxu-primary);
            font-weight: 600;
            border: 2px solid var(--pxu-primary);
            border-radius: 8px;
            transition: var(--pxu-transition);
        }

        .btn-pxu-outline:hover {
            background-color: var(--pxu-primary);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* Card and Glassmorphism */
        .card-pxu {
            background: var(--pxu-card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 16px;
            box-shadow: var(--pxu-shadow);
            transition: var(--pxu-transition);
        }

        .card-pxu:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(27, 42, 74, 0.15);
        }

        /* Page Headers */
        .page-header-pxu {
            position: relative;
            background: linear-gradient(135deg, var(--pxu-primary) 0%, #101c36 100%);
            color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
            border-bottom: 5px solid var(--pxu-secondary);
        }

        .page-header-pxu::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(232, 69, 10, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Footer */
        footer {
            background-color: var(--pxu-primary);
            color: rgba(255, 255, 255, 0.7);
            border-top: 4px solid var(--pxu-secondary);
            margin-top: auto;
        }

        footer a {
            color: var(--pxu-secondary);
            text-decoration: none;
            transition: var(--pxu-transition);
        }

        footer a:hover {
            color: var(--pxu-secondary-light);
            text-decoration: underline;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-pxu sticky-top">
        <div class="container py-1">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="fa-solid fa-graduation-cap me-2 text-warning fs-3"></i>
                PHÚ XUÂN <span>EVENTS</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('events') || Request::is('/') ? 'active' : '' }}" href="{{ route('events.index') }}">
                            <i class="fa-solid fa-calendar-days me-1"></i> Sự kiện công khai
                        </a>
                    </li>
                    
                    @auth
                        @if(auth()->user()->isStudent())
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('my-registrations') ? 'active' : '' }}" href="{{ route('registrations.my') }}">
                                    <i class="fa-solid fa-clipboard-list me-1"></i> Sự kiện của tôi
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isOrganizer() || auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('events/create') ? 'active' : '' }}" href="{{ route('events.create') }}">
                                    <i class="fa-solid fa-circle-plus me-1"></i> Đăng sự kiện
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-chart-pie me-1"></i> Dashboard Admin
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <!-- Authenticated User Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2 border-0 px-3 py-2 bg-white-5" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px; background: rgba(255,255,255,0.1);">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-circle-user text-light fs-5"></i>
                                @endif
                                <span>{{ auth()->user()->name }}</span>
                                <span class="badge bg-warning text-dark text-capitalize ms-1" style="font-size: 0.75rem;">{{ auth()->user()->role }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userMenuButton" style="border-radius: 12px; overflow: hidden;">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                        <i class="fa-regular fa-id-card me-2 text-primary"></i> Hồ sơ cá nhân
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Guest Actions -->
                        <a href="{{ route('login') }}" class="btn text-white fw-bold"><i class="fa-solid fa-key me-1"></i> Đăng nhập</a>
                        <a href="{{ route('register') }}" class="btn btn-pxu-secondary py-2 px-3"><i class="fa-solid fa-user-plus me-1"></i> Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Slot -->
    <main class="container py-4 my-2 flex-grow-1">
        <!-- System Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow border-0 rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-check me-2 fs-4 text-success"></i>
                <div>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow border-0 rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="fa-solid fa-circle-xmark me-2 fs-4 text-danger"></i>
                <div>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
        @if(isset($slot))
            {{ $slot }}
        @endif
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center">
        <div class="container">
            <p class="mb-1 fw-bold">&copy; {{ date('Y') }} Trường Đại Học Phú Xuân - Khoa Công nghệ Thông tin</p>
            <p class="mb-0 text-white-50 small">Bài tập lớn IT3042 - Lập trình Backend Laravel. Thiết kế bởi Nhóm PXU & AI Coding Assistant.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
