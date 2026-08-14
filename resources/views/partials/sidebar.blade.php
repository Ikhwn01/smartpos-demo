<aside id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-cart-check-fill me-2 fs-3"></i>
        <span>SmartPOS</span>
    </div>

    <div class="sidebar-menu">
        <div class="menu-category">{{ __('messages.main_terminal') }}</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> {{ __('messages.dashboard') }}
        </a>
        <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.index') ? 'active' : '' }}">
            <i class="bi bi-display"></i> {{ __('messages.pos_terminal') }}
        </a>

        <div class="menu-category">{{ __('messages.inventory_catalog') }}</div>
        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> {{ __('messages.products') }}
        </a>
        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> {{ __('messages.categories') }}
        </a>
        <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <i class="bi bi-boxes"></i> {{ __('messages.inventory') }}
        </a>

        <div class="menu-category">{{ __('messages.transactions') }}</div>
        <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> {{ __('messages.sales_history') }}
        </a>
        <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i> {{ __('messages.purchases') }}
        </a>
        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> {{ __('messages.expenses') }}
        </a>

        <div class="menu-category">{{ __('messages.directory') }}</div>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> {{ __('messages.customers') }}
        </a>
        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i> {{ __('messages.suppliers') }}
        </a>

        <div class="menu-category">{{ __('messages.analytics') }}</div>
        <a href="{{ route('reports.sales') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> {{ __('messages.reports') }}
        </a>

        @if(auth()->user() && auth()->user()->isAdmin())
        <div class="menu-category">{{ __('messages.system_admin') }}</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> {{ __('messages.user_management') }}
        </a>
        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> {{ __('messages.store_settings') }}
        </a>
        @endif
    </div>
</aside>
