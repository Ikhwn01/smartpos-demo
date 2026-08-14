<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>500 - Internal Server Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center p-5 bg-white rounded shadow-sm border" style="max-width: 480px;">
        <i class="bi bi-gear-wide-connected text-danger display-1 mb-3"></i>
        <h2 class="fw-bold mb-2">500 - Server Error</h2>
        <p class="text-muted small mb-4">Something went wrong on our servers. Our technical team has been notified. Please try again shortly.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary fw-semibold">Return to Dashboard</a>
    </div>
</body>
</html>
