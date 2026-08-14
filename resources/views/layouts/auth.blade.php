<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - {{ setting('store_name', 'SmartPOS') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light py-5">
    <div class="container" style="max-width: 420px;">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 mb-2" style="width: 54px; height: 54px;">
                <i class="bi bi-cart-check-fill fs-2"></i>
            </div>
            <h3 class="fw-bold mb-1">{{ setting('store_name', 'SmartPOS') }}</h3>
            <p class="text-muted small">All-in-One Point of Sale & Inventory System</p>
        </div>

        @yield('content')

        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} {{ setting('store_name', 'SmartPOS') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
