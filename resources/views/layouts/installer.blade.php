<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPOS - Web Application Installer</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="container" style="max-width: 650px;">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 mb-2" style="width: 50px; height: 50px;">
                <i class="bi bi-cart-check-fill fs-2"></i>
            </div>
            <h3 class="fw-bold mb-1">SmartPOS Setup Wizard</h3>
            <p class="text-muted small">Commercial Web Script Auto-Installer</p>
        </div>

        <div class="card card-custom p-4 shadow-sm">
            @include('partials.alerts')
            @yield('content')
        </div>

        <div class="text-center mt-3 text-muted small">
            &copy; {{ date('Y') }} SmartPOS Commercial Template.
        </div>
    </div>
</body>
</html>
