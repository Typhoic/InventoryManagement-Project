<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <img class="sidebar-logo" src="{{ asset('images/ccb_logo_notext.svg') }}" alt="Logo">
        <span class="sidebar-title">Cal's Chicken Bowl</span>
        <button class="sidebar-close" onclick="closeSidebar()">×</button>
    </div>

    <!-- Sidebar Menu -->
    <nav class="sidebar-menu">
        <!-- Dashboard -->
        <div class="sidebar-menu-item">
            <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>Dashboard</span>
            </a>
        </div>

        <div class="sidebar-divider"></div>

        <!-- Orders -->
        <div class="sidebar-menu-item">
            <button class="sidebar-submenu-toggle {{ request()->routeIs('salesorderclicked') || request()->routeIs('orders.*') ? 'open' : '' }}" onclick="toggleSubmenu(this)">
                <span class="toggle-left">
                    <span>Orders</span>
                </span>
                <span class="sidebar-submenu-arrow">▼</span>
            </button>
            <div class="sidebar-submenu {{ request()->routeIs('salesorderclicked') || request()->routeIs('orders.*') ? 'show' : '' }}">
                <a href="{{ route('salesorderclicked') }}" class="sidebar-submenu-link {{ request()->routeIs('salesorderclicked') ? 'active' : '' }}">
                    All Orders
                </a>
                <a href="{{ route('orders.create') }}" class="sidebar-submenu-link {{ request()->routeIs('orders.create') ? 'active' : '' }}">
                    Create Order
                </a>
            </div>
        </div>

        <!-- Items -->
        <div class="sidebar-menu-item">
            <button class="sidebar-submenu-toggle {{ request()->routeIs('itemdetailsclicked') || request()->routeIs('items.*') ? 'open' : '' }}" onclick="toggleSubmenu(this)">
                <span class="toggle-left">
                    <span>Items</span>
                </span>
                <span class="sidebar-submenu-arrow">▼</span>
            </button>
            <div class="sidebar-submenu {{ request()->routeIs('itemdetailsclicked') || request()->routeIs('items.*') ? 'show' : '' }}">
                <a href="{{ route('itemdetailsclicked') }}" class="sidebar-submenu-link {{ request()->routeIs('itemdetailsclicked') ? 'active' : '' }}">
                    All Items
                </a>
                <a href="{{ route('items.create') }}" class="sidebar-submenu-link {{ request()->routeIs('items.create') ? 'active' : '' }}">
                    Create Item
                </a>
            </div>
        </div>
    </nav>
</div>

<!-- Sidebar Script -->
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('show');
        document.getElementById('sidebarOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }

    function toggleSubmenu(button) {
        button.classList.toggle('open');
        const submenu = button.nextElementSibling;
        submenu.classList.toggle('show');
    }
</script>