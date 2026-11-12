<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon">
            <i class="fa-solid fa-store"></i>
        </div>

        <div class="logo-text">
            <h1>Muarra POS</h1>
            @authany
                @if (currentUser()->role_id == 1)
                    <p>{{ currentUser()->name }}</p>
                @elseif (currentUser()->role_id == 3)
                    <p>{{ currentUser()->branch->name }}</p>
                @else
                    <p>{{ currentUser()->tenant->name }}</p>
                @endif
            @endauthany
        </div>
    </div>

    <nav>
        @if(currentUser()->role_id == 2)
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

                <a class="menu-item {{ request()->routeIs('branch.*') ? 'active' : '' }}"
                    href="{{ route('branch.index') }}">

                    <i class="fa-solid fa-box"></i>
                    <span>Branches</span>
                </a>

                <a class="menu-item {{ request()->routeIs('staff.*') ? 'active' : '' }}"
                    href="{{ route('staff.index') }}">

                    <i class="fa-solid fa-box"></i>
                    <span>Staff</span>
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

                <a class="menu-item {{ request()->routeIs('inventory-history.index') ? 'active' : '' }}"
                    href="{{ route('inventory-history.index') }}">

                    <i class="fa-solid fa-truck"></i>
                    <span>History</span>

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
        @elseif (currentUser()->role_id == 3)
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

                <a class="menu-item {{ request()->routeIs('staff.*') ? 'active' : '' }}"
                    href="{{ route('staff.index') }}">

                    <i class="fa-solid fa-box"></i>
                    <span>Staff</span>
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

                <a class="menu-item {{ request()->routeIs('inventory-history.index') ? 'active' : '' }}"
                    href="{{ route('inventory-history.index') }}">

                    <i class="fa-solid fa-truck"></i>
                    <span>History</span>

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
        @elseif (currentUser()->role_id == 1)
            {{-- Items --}}
            <div class="menu-group">
                <span class="menu-title">MAIN</span>

                <a class="menu-item {{ request()->routeIs('tenant.*') ? 'active' : '' }}"
                    href="{{ route('tenant.index') }}">

                    <i class="fa-solid fa-box"></i>
                    <span>Tenants</span>
                </a>

                <a class="menu-item {{ request()->routeIs('registered-user.*') ? 'active' : '' }}"
                    href="{{ route('registered-user.index') }}">

                    <i class="fa-solid fa-box"></i>
                    <span>Registered User</span>
                </a>
            </div>
        @endif
    </nav>


    
    <div class="sidebar-footer">
        <a class="menu-item">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>

        <div class="profile-menu">
            <div class="profile-info">
                <div class="profile-avatar">
                    {{ strtoupper(substr(currentUser()->name, 0, 1)) }}
                </div>

                <div class="profile-details">
                    <span class="profile-name">
                        {{ currentUser()->name }}
                    </span>
                    <span class="profile-email">
                        {{ currentUser()->role->name }}
                    </span>
                </div>

                <a class="menu-item logout" href="{{ route('logout') }}">
                    <i class="fa-solid fa-right-from-bracket" style="transform: rotate(-180deg)"></i>
                </a>
            </div>
        </div>
    </div>
</aside>