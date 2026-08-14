<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>419 - Session Expired</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center p-5 bg-white rounded shadow-sm border" style="max-width: 480px;">
        <i class="bi bi-clock-history text-secondary display-1 mb-3"></i>
        <h2 class="fw-bold mb-2">419 - Page Session Expired</h2>
        <p class="text-muted small mb-4">Your session token has expired due to inactivity. Please refresh and try again.</p>
        <a href="{{ route('login') }}" class="btn btn-primary fw-semibold">Go to Login Page</a>
    </div>
</body>
</html>
