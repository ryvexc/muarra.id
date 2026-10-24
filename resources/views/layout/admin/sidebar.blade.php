<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon">
            <i class="fa-solid fa-store"></i>
        </div>

        <div class="logo-text">
            <h1>Muarra POS</h1>
            <p>Flowbox Coffee</p>
        </div>
    </div>

    <nav>
        {{-- Main Menu --}}
        <div class="menu-group">
            <span class="menu-title">Main Menu</span>
            <a class="menu-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}" 
                href="{{ route('dashboard.index') }}">

                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <a class="menu-item {{ request()->routeIs('order.index') ? 'active' : '' }}" 
                href="{{ route('order.index') }}">

                <i class="fa-solid fa-cart-shopping"></i>
                <span>Orders</span>
            </a>
        </div>

        {{-- Items --}}
        <div class="menu-group">
            <span class="menu-title">Items</span>

            <a class="menu-item {{ request()->routeIs('product.*') ? 'active' : '' }}"
                href="{{ route('product.index') }}">

                <i class="fa-solid fa-box"></i>
                <span>Products</span>
            </a>

            <a class="menu-item {{ request()->routeIs('category.index') ? 'active' : '' }}"
                href="{{ route('category.index') }}">

                <i class="fa-solid fa-layer-group"></i>
                <span>Categories</span>
            </a>

            <a class="menu-item {{ request()->routeIs('discount.index') ? 'active' : '' }}"
                href="{{ route('discount.index') }}">

                <i class="fa-solid fa-layer-group"></i>
                <span>Discounts</span>
            </a>
        </div>

        {{-- Inventory --}}
        <div class="menu-group">

            <span class="menu-title">Inventory</span>

            <a class="menu-item {{ request()->routeIs('stock.index') ? 'active' : '' }}"
                href="{{ route('stock.index') }}">

                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Stocks</span>

            </a>

            <a class="menu-item {{ request()->routeIs('supply.index') ? 'active' : '' }}"
                href="{{ route('supply.index') }}">

                <i class="fa-solid fa-truck"></i>
                <span>Supplies</span>

            </a>

            <a class="menu-item {{ request()->routeIs('stock-report.*') ? 'active' : '' }}"
                href="{{ route('stock-report.index') }}">

                <i class="fa-solid fa-clipboard-list"></i>
                <span>Stock Reports</span>

            </a>
        </div>

        {{-- Finance --}}
        <div class="menu-group">
            <span class="menu-title">Finance</span>

            <a class="menu-item">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <a class="menu-item {{ request()->routeIs('wallet.main.index') ? 'active' : '' }}"
                href="{{ route('wallet.main.index') }}">

                <i class="fa-solid fa-wallet"></i>
                <span>Wallets</span>
            </a>

            <a class="menu-item {{ request()->routeIs('wallet.tenant') ? 'active' : '' }}"
                href="{{ route('wallet.tenant') }}">

                <i class="fa-solid fa-building-columns"></i>
                <span>Tenants</span>
            </a>
        </div>

        {{-- Analytics --}}
        <div class="menu-group">
            <span class="menu-title">Analytics</span>

            <a class="menu-item">
                <i class="fa-solid fa-chart-line"></i>
                <span>Sales</span>
            </a>

            <a class="menu-item">
                <i class="fa-solid fa-ban"></i>
                <span>Cancelled</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a class="menu-item">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>
        <a class="menu-item logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>