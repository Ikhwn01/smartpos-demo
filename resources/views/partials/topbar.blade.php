<header class="topbar">
    <div class="d-flex align-items-center">
        <button id="btn-sidebar-toggle" class="btn-sidebar-toggle me-3">
            <i class="bi bi-list"></i>
        </button>

        <form action="{{ route('products.index') }}" method="GET" class="d-none d-md-flex align-items-center position-relative" style="width: 280px;">
            <i class="bi bi-search position-absolute ms-3 text-muted"></i>
            <input type="text" name="search" class="form-control ps-5 form-control-sm" placeholder="{{ __('messages.search') }}" value="{{ request('search') }}">
        </form>
    </div>

    <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- Language Switcher Dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm btn-light border rounded-pill px-2 d-flex align-items-center gap-1" data-bs-toggle="dropdown">
                <i class="bi bi-translate text-primary"></i>
                <span class="fw-bold small text-uppercase">{{ app()->getLocale() }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width: 160px;">
                <li>
                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                        <span>🇬🇧 English</span>
                        @if(app()->getLocale() === 'en') <i class="bi bi-check-lg ms-2"></i> @endif
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center justify-content-between {{ app()->getLocale() === 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">
                        <span>🇮🇩 Indonesia</span>
                        @if(app()->getLocale() === 'id') <i class="bi bi-check-lg ms-2"></i> @endif
                    </a>
                </li>
            </ul>
        </div>

        <!-- Theme Toggle -->
        <button id="btn-theme-toggle" class="btn btn-sm btn-light border rounded-circle" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-moon-stars-fill"></i>
        </button>

        <!-- Notifications Dropdown -->
        @php
            $unreadNotifications = \App\Models\Notification::where('is_read', false)->latest()->take(5)->get();
            $unreadCount = \App\Models\Notification::where('is_read', false)->count();
        @endphp
        <div class="dropdown">
            <button class="btn btn-sm btn-light border rounded-circle position-relative" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                    {{ $unreadCount }}
                </span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end p-0 shadow" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                    <h6 class="m-0 fw-bold">{{ __('messages.notifications') }}</h6>
                    @if($unreadCount > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none small">Mark all read</button>
                    </form>
                    @endif
                </div>
                <div class="list-group list-group-flush">
                    @forelse($unreadNotifications as $notif)
                    <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action p-3">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <strong class="mb-1 small {{ $notif->type === 'out_of_stock' ? 'text-danger' : 'text-warning' }}">{{ $notif->title }}</strong>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 small text-muted">{{ Str::limit($notif->message, 60) }}</p>
                    </a>
                    @empty
                    <div class="p-3 text-center text-muted small">No new notifications</div>
                    @endforelse
                </div>
                <div class="p-2 border-top text-center bg-light">
                    <a href="{{ route('notifications.index') }}" class="small text-primary text-decoration-none">View All Notifications</a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'User') . '&background=4f46e5&color=fff' }}" alt="Avatar" class="rounded-circle me-2" width="34" height="34">
                <div class="d-none d-sm-block text-start" style="line-height: 1.2;">
                    <div class="fw-semibold small text-truncate" style="max-width: 120px;">{{ auth()->user()->name ?? 'Guest' }}</div>
                    <span class="badge {{ auth()->user() && auth()->user()->isAdmin() ? 'bg-primary' : 'bg-secondary' }}" style="font-size: 0.65rem;">
                        {{ strtoupper(auth()->user()->role ?? 'STAFF') }}
                    </span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i> {{ __('messages.my_profile') }}</a></li>
                @if(auth()->user() && auth()->user()->isAdmin())
                <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="bi bi-sliders me-2"></i> {{ __('messages.store_settings') }}</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.logout') }}</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
