@if(auth()->user()->role !== 'customer')
<div class="sidebar" id="sidebar">
    <!-- User Info Card -->
    <div class="user-card">
        <div class="user-avatar">
            <i class="bi bi-person-circle"></i>
        </div>
        <div class="user-info">
            <span class="user-name">{{ auth()->user()->name }}</span>
            <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        @if(auth()->user()->role === 'admin')
            {{-- ========== ADMIN NAVIGATION ========== --}}
            <div class="nav-section">
                <span class="nav-section-title">Main</span>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-speedometer2"></i></div>
                            <span class="nav-text">Dashboard</span>
                            <div class="nav-indicator"></div>
                        </a>
                    </li>

                    {{-- Inventories Dropdown --}}
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.order.index') || request()->routeIs('admin.orderitem.index') ? 'active' : '' }}" 
                        id="productsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-icon"><i class="bi bi-box-seam"></i></div>
                            <span class="nav-text">Inventories</span>
                            <div class="nav-indicator"></div>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                            <li><a href="{{ route('admin.products.index') }}" class="dropdown-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}"><i class="bi bi-box"></i> Products</a></li>
                            <li><a href="{{ route('admin.materials.index') }}" class="dropdown-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}"><i class="bi bi-box"></i>Raw Materials</a></li>
                            <li><a href="{{ route('admin.order.index') }}" class="dropdown-item {{ request()->routeIs('admin.order.index') ? 'active' : '' }}"><i class="bi bi-receipt"></i> Orders</a></li>
                            <li><a href="{{ route('admin.orderitem.index') }}" class="dropdown-item {{ request()->routeIs('admin.orderitem.index') ? 'active' : '' }}"><i class="bi bi-cart-check"></i> Order Items</a></li>
                            <li><a href="{{ route('admin.inventory') }}" class="dropdown-item {{ request()->routeIs('admin.inventory') ? 'active' : '' }}"><i class="bi bi-clipboard-data"></i> Inventory Logs</a></li>
                            
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.notification.index') }}" class="nav-link {{ request()->routeIs('admin.notification.index') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi-chat"></i></div>
                            <span class="nav-text">Inbox</span>
                            <div class="nav-indicator"></div>
                            <span id="notif-count" class="badge bg-danger ms-2" style="display:none;"></span>
                        </a>
                    </li>

                    
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.report.index') }}" class="nav-link {{ request()->routeIs('admin.report.index') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-bar-chart"></i></div>
                            <span class="nav-text">Reports</span>
                            <div class="nav-indicator"></div>
                        </a>
                    </li>

                    <!-- <li class="nav-item">
                        <a href="{{ route('notifications.index_notify') }}" class="nav-link {{ request()->routeIs('notifications.index_notify') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-bell"></i></div>
                            <span class="nav-text">Notification</span>
                            <div class="nav-indicator"></div>
                            <span id="notif-count" class="badge bg-danger ms-2" style="display:none;"></span>
                        </a>
                    </li> -->

                    <li class="nav-item">
                        <a href="{{ route('admin.purchase.index') }}" class="nav-link {{ request()->routeIs('admin.purchase.index') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-graph-up"></i></div>
                            <span class="nav-text">Purchase</span>
                            <div class="nav-indicator"></div>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section">
                <span class="nav-section-title">Management</span>
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-people"></i></div>
                            <span class="nav-text">Users</span>
                            <div class="nav-indicator"></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customized.index') }}" class="nav-link {{ request()->routeIs('admin.customized.index') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-sliders"></i></div>
                            <span class="nav-text">Customized Order</span>
                            <div class="nav-indicator"></div>
                        </a>
                    </li>

                      <li class="nav-item">
                        <a href="{{ route('admin.texture.index') }}" class="nav-link {{ request()->routeIs('admin.texture.index') ? 'active' : '' }}">
                            <div class="nav-icon"><i class="bi bi-image"></i></div>
                            <span class="nav-text">Textures</span>
                            <div class="nav-indicator"></div>
                        </a>
                    </li>

                </ul>
            </div>

        @elseif(auth()->user()->role === 'staff')
            {{-- ========== STAFF NAVIGATION ========== --}}
            <div class="nav-section">
                <span class="nav-section-title">Management</span>
                <ul class="nav-list">
                    <li class="nav-item"><a href="{{ route('staff.dashboard') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-people"></i></div><span class="nav-text">Dashboard</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.orders.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-receipt"></i></div><span class="nav-text">Orders</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.product.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-box"></i></div><span class="nav-text">Product</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.paysupply.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-cash-coin"></i></div><span class="nav-text">Purchase Supply</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.suppliers.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-truck"></i></div><span class="nav-text">Supplier</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.categories.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-tags"></i></div><span class="nav-text">Category</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.inventories.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-clipboard-data"></i></div><span class="nav-text">Inventory Logs</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.reports.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-bar-chart"></i></div><span class="nav-text">Reports</span></a></li>

                    <li class="nav-item"><a href="{{ route('staff.customize.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-sliders"></i></div><span class="nav-text">Customized Order</span></a></li>
                    <li class="nav-item"><a href="{{ route('staff.textures.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-image"></i></div><span class="nav-text">Textures</span></a></li>

                    <li class="nav-item"><a href="{{ route('staff.notification.index') }}" class="nav-link"><div class="nav-icon"><i class="bi bi-bell"></i></div><span class="nav-text">Notification</span></a></li>
                </ul>
            </div>
        @endif
    </nav>

    <!-- Logout Button -->
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <div class="nav-icon"><i class="bi bi-box-arrow-right"></i></div>
                <span class="nav-text">Logout</span>
            </button>
        </form>
    </div>
</div>
@endif

<script>
    // ====== Notification Counter ======
    async function fetchNotifCount() {
        try {
            let response = await fetch("{{ route('notifications.count') }}");
            let data = await response.json();
            let badge = document.getElementById("notif-count");

            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = "inline-block";
                } else {
                    badge.style.display = "none";
                }
            }
        } catch (error) {
            console.error("Error fetching notif count:", error);
        }
    }
    fetchNotifCount();
    setInterval(fetchNotifCount, 10000);

    // ====== Sidebar Toggle ======
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.getElementById("sidebar");
        const sidebarToggle = document.getElementById("sidebarToggle"); // desktop arrow
        const navbarToggler = document.querySelector(".navbar-toggler"); // mobile hamburger

        if (sidebarToggle) {
            sidebarToggle.addEventListener("click", () => {
                sidebar.classList.toggle("collapsed");
            });
        }

        if (navbarToggler) {
            navbarToggler.addEventListener("click", () => {
                sidebar.classList.toggle("open");
            });
        }
    });

     async function fetchNotifCount() {
        try {
            const response = await fetch("{{ route('notifications.count') }}");
            const data = await response.json();
            const badge = document.getElementById("notif-count");

            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = "inline-block";
            } else {
                badge.style.display = "none";
            }

        } catch (e) { console.error(e); }
    }

    async function loadNotifList() {
        try {
            const response = await fetch("{{ route('notifications.list') }}");
            const products = await response.json();
            const list = document.getElementById("notif-list");

            if (products.length === 0) {
                list.innerHTML = `<li class="text-center text-muted p-2">No Low Stock Items</li>`;
                return;
            }

            let html = "";
            products.forEach(p => {
                html += `
                    <li class="p-2 border-bottom">
                        <strong>${p.product_name}</strong><br>
                        <span class="text-danger fw-bold">Stock: ${p.quantity}</span><br>
                        <small class="text-muted">${p.quantity == 0 ? "Out of Stock" : "Low Stock"}</small>
                    </li>
                `;
            });

            list.innerHTML = html;

        } catch (e) { console.error(e); }
    }

    // Load count regularly
    fetchNotifCount();
    setInterval(fetchNotifCount, 10000);

    // Load list when Dropdown opens
    document.getElementById("notifDropdown")
        .addEventListener("click", loadNotifList);
</script>
