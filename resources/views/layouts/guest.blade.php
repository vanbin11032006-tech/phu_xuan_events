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
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --pxu-primary: #1B2A4A;
            --pxu-secondary: #E8450A;
            --pxu-primary-light: #2c426f;
            --pxu-secondary-light: #ff6028;
            --pxu-bg: #f8fafc;
            --pxu-dark: #0f172a;
            --pxu-shadow: 0 10px 30px -10px rgba(27, 42, 74, 0.08);
            --pxu-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, var(--pxu-primary) 0%, #0d1525 100%);
            color: var(--pxu-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            transition: var(--pxu-transition);
        }

        .auth-header {
            background: var(--pxu-primary);
            border-bottom: 4px solid var(--pxu-secondary);
            color: #ffffff;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .auth-header h2 {
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 0.25rem;
        }

        .auth-header h2 span {
            color: var(--pxu-secondary);
        }

        .auth-body {
            padding: 2rem 2.5rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--pxu-secondary);
            box-shadow: 0 0 0 0.25rem rgba(232, 69, 10, 0.15);
        }

        .btn-pxu-secondary {
            background-color: var(--pxu-secondary);
            color: #ffffff;
            font-weight: 600;
            border: 2px solid var(--pxu-secondary);
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            width: 100%;
            transition: var(--pxu-transition);
        }

        .btn-pxu-secondary:hover {
            background-color: var(--pxu-secondary-light);
            border-color: var(--pxu-secondary-light);
            color: #ffffff;
            transform: translateY(-2px);
        }

        a {
            color: var(--pxu-primary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--pxu-transition);
        }

        a:hover {
            color: var(--pxu-secondary);
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-header">
            <a href="/" class="text-white text-decoration-none d-inline-block mb-2">
                <i class="fa-solid fa-graduation-cap text-warning fs-1"></i>
            </a>
            <h2>ĐẠI HỌC <span>PHÚ XUÂN</span></h2>
            <p class="mb-0 text-white-50 small">Cổng thông tin Đăng ký & Quản lý Sự kiện</p>
        </div>
        <div class="auth-body">
            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
