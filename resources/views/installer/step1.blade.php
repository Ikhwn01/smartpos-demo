@extends('layouts.installer')

@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-1-circle-fill text-primary me-2"></i> Step 1: System Requirements</h5>
<p class="text-muted small mb-4">Checking server PHP version, extensions, and directory write permissions.</p>

<ul class="list-group mb-4">
    @foreach($requirements as $name => $status)
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>{{ $name }}</span>
        @if($status)
        <span class="badge bg-success rounded-pill"><i class="bi bi-check-lg me-1"></i> Passed</span>
        @else
        <span class="badge bg-danger rounded-pill"><i class="bi bi-x-lg me-1"></i> Failed</span>
        @endif
    </li>
    @endforeach
</ul>

<div class="d-flex justify-content-between align-items-center">
    <span class="small text-muted">Step 1 of 6</span>
    @if($allPassed)
    <a href="{{ route('install.step2') }}" class="btn btn-primary fw-semibold">Continue to Step 2 <i class="bi bi-arrow-right ms-1"></i></a>
    @else
    <button class="btn btn-secondary" disabled>Fix Server Requirements to Proceed</button>
    @endif
</div>
@endsection
