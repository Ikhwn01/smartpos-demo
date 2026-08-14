@extends('layouts.app')

@section('title', 'System Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">System Notifications & Stock Alerts</h4>
        <p class="text-muted small mb-0">Low stock notifications, out of stock alerts, and new transaction logs.</p>
    </div>
    <form action="{{ route('notifications.read-all') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-check-all me-1"></i> Mark All as Read</button>
    </form>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($notifications as $notif)
            <div class="list-group-item p-3 {{ $notif->is_read ? 'bg-light' : 'border-start border-3 border-warning' }}">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="m-0 fw-bold {{ $notif->type === 'out_of_stock' ? 'text-danger' : ($notif->type === 'low_stock' ? 'text-warning' : 'text-primary') }}">
                        {{ $notif->title }}
                    </h6>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-2 text-muted small">{{ $notif->message }}</p>
                @if(!$notif->is_read)
                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link p-0 text-decoration-none small">Mark as read & view</button>
                </form>
                @endif
            </div>
            @empty
            <div class="p-4 text-center text-muted">No notifications found.</div>
            @endforelse
        </div>
    </div>
    @if($notifications->hasPages())
    <div class="card-footer bg-transparent border-top">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
