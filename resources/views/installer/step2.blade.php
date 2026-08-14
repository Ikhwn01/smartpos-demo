@extends('layouts.installer')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-2-circle-fill text-primary me-2"></i> Step 2: Database Configuration</h5>
<p class="text-muted small mb-4">Enter your MySQL database host, name, user, and password credentials.</p>

<form action="{{ route('install.step2.save') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label small fw-semibold">Database Driver Connection</label>
        <select name="db_connection" id="db_connection" class="form-select" onchange="toggleDbFields()">
            <option value="sqlite">SQLite (Local File DB - Instant Ready)</option>
            <option value="mysql" selected>MySQL / MariaDB</option>
        </select>
    </div>

    <div id="mysql-fields">
        <div class="row g-2 mb-3">
            <div class="col-8">
                <label class="form-label small fw-semibold">Database Host</label>
                <input type="text" name="db_host" class="form-control" value="127.0.0.1">
            </div>
            <div class="col-4">
                <label class="form-label small fw-semibold">Port</label>
                <input type="text" name="db_port" class="form-control" value="3306">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Database Name</label>
            <input type="text" name="db_database" class="form-control" value="smartpos_db" placeholder="smartpos">
        </div>

        <div class="row g-2 mb-3">
            <div class="col-6">
                <label class="form-label small fw-semibold">DB Username</label>
                <input type="text" name="db_username" class="form-control" value="root">
            </div>
            <div class="col-6">
                <label class="form-label small fw-semibold">DB Password</label>
                <input type="password" name="db_password" class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('install.step1') }}" class="btn btn-outline-secondary">Back</a>
        <button type="submit" class="btn btn-primary fw-semibold">Test Connection & Save <i class="bi bi-arrow-right ms-1"></i></button>
    </div>
</form>

<script>
function toggleDbFields() {
    const conn = document.getElementById('db_connection').value;
    document.getElementById('mysql-fields').style.display = conn === 'sqlite' ? 'none' : 'block';
}
</script>
@endsection
