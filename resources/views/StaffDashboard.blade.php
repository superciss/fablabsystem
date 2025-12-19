<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/staffdashboard.css') }}">
</head>
<body>
    <div id="sidebarBackdrop" class="sidebar-backdrop hide"></div>
    <div class="d-flex">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button id="sidebarToggle" class="btn btn-warning d-lg-none position-fixed" style="top: 18px; left: 18px; z-index:2100;">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Main Content -->
        <div class="main-content d-flex" style="width:100%;">
            <!-- Sidebar (now inside main-content) -->
            <nav class="sidebar">
                <div class="sidebar-content">
                    <h4><i class="fas fa-cogs me-2"></i>Staff Panel</h4>
                    <div class="sidebar-nav">
                        <a href="#"><i class="fas fa-home"></i>Dashboard</a>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-boxes"></i>Inventory
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('staff.inventory') }}">View Inventory</a></li>
                                <li><a class="dropdown-item" href="#">Update Stock (Raw Materials)</a></li>
                                <li><a class="dropdown-item" href="#">Update Stock (Finished Product)</a></li>
                                <li><a class="dropdown-item" href="#">Check Low Stock Alerts</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-shopping-cart"></i>Purchase Orders
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">View Assigned Orders</a></li>
                                <li><a class="dropdown-item" href="#">Update Order Status</a></li>
                                <li><a class="dropdown-item" href="#">Upload Receipt/Notes</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-cube"></i>3D Printing Jobs
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">View 3D Models</a></li>
                                <li><a class="dropdown-item" href="#">Compute Printing Time & Material Needed</a></li>
                                <li><a class="dropdown-item" href="#">Update Job Progress</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-calculator"></i>Cost & Profit Estimator
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Compute Cost</a></li>
                                <li><a class="dropdown-item" href="#">View Estimated Profit</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-paint-brush"></i>Product Customization
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Assist in Customization Requests</a></li>
                                <li><a class="dropdown-item" href="#">Upload Design Files</a></li>
                                <li><a class="dropdown-item" href="#">Request Clarification from Customer</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-truck"></i>Order Tracking
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Check Order Status</a></li>
                                <li><a class="dropdown-item" href="#">Update Tracking Details</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-chart-bar"></i>Reports
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Daily Sales Summary</a></li>
                                <li><a class="dropdown-item" href="#">Completed Orders</a></li>
                                <li><a class="dropdown-item" href="#">Printing Time Logs</a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-user-cog"></i>Profile/Settings
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Change Password</a></li>
                                <li><a class="dropdown-item" href="#">Update Personal Info</a></li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i>Logout</a>
                    </div>
                </div>
            </nav>
            <!-- Dashboard Content (now beside sidebar) -->
            <div class="flex-grow-1 d-flex flex-column">   
                <!-- Dashboard Content -->
                <div class="container-fluid px-4 py-4">
                    <!-- Header above cards -->
                    <header class="navbar navbar-expand top-navbar mb-4">
                        <div class="container-fluid d-flex justify-content-between align-items-center">
                            <span class="navbar-brand mb-0 h1">
                                <i class="fas fa-tachometer-alt me-2"></i>Welcome, Staff
                            </span>
                            <div class="dropdown profile-dropdown">
                                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" 
                                   id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('images/profile.png') }}" alt="Profile" 
                                         width="35" height="35" class="rounded-circle me-2" style="object-fit: cover;">
                                    <span class="fw-semibold">My Profile</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewProfileModal">
                                            <i class="fas fa-user me-2"></i>View Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                            <i class="fas fa-edit me-2"></i>Edit Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>
                    <!-- Stats Cards -->
                    <div class="stats-cards">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
                            <div class="stat-label">Total Users</div>
                        </div>
                        
                        <div class="stat-card accent">
                            <div class="stat-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="stat-number">{{ $totalProducts ?? 0 }}</div>
                            <div class="stat-label">Total Products</div>
                        </div>
                        
                        <div class="stat-card secondary">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-number">{{ $pendingRequests ?? 0 }}</div>
                            <div class="stat-label">Pending Requests</div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="stat-number">{{ $totalForDelivery ?? 0 }}</div>
                            <div class="stat-label">For Delivery</div>
                        </div>
                        
                        <div class="stat-card accent">
                            <div class="stat-icon">
                                <i class="fas fa-hand-holding"></i>
                            </div>
                            <div class="stat-number">{{ $totalForPickup ?? 0 }}</div>
                            <div class="stat-label">For Pickup</div>
                        </div>
                    </div>

                    <!-- Inventory Section -->
                    <div class="inventory-section">
                        <div class="inventory-header">
                            <h5><i class="fas fa-warehouse me-2"></i>Inventory Overview</h5>
                        </div>
                        <div class="table-container">
                            <div class="table-responsive">
                                <table class="table modern-table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag me-1"></i>#</th>
                                            <th><i class="fas fa-tag me-1"></i>Item Name</th>
                                            <th><i class="fas fa-list me-1"></i>Category</th>
                                            <th><i class="fas fa-cubes me-1"></i>Stock</th>
                                            <th><i class="fas fa-balance-scale me-1"></i>Unit</th>
                                            <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">1</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                                    PLA Filament
                                                </div>
                                            </td>
                                            <td><span class="badge bg-secondary">Raw Material</span></td>
                                            <td class="fw-bold">25</td>
                                            <td>kg</td>
                                            <td><span class="status-badge badge-success">In Stock</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">2</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                                    ABS Filament
                                                </div>
                                            </td>
                                            <td><span class="badge bg-secondary">Raw Material</span></td>
                                            <td class="fw-bold">5</td>
                                            <td>kg</td>
                                            <td><span class="status-badge badge-warning">Low Stock</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">3</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                                    3D Printed Gear
                                                </div>
                                            </td>
                                            <td><span class="badge bg-info">Finished Product</span></td>
                                            <td class="fw-bold">12</td>
                                            <td>pcs</td>
                                            <td><span class="status-badge badge-success">In Stock</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">4</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-danger rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                                    3D Printed Case
                                                </div>
                                            </td>
                                            <td><span class="badge bg-info">Finished Product</span></td>
                                            <td class="fw-bold">0</td>
                                            <td>pcs</td>
                                            <td><span class="status-badge badge-danger">Out of Stock</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ url('staff.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/profile.png') }}" 
                                 alt="Profile Picture" class="rounded-circle mb-2" width="80" height="80" style="object-fit: cover;">
                            <div>
                                <input class="form-control mt-2" type="file" name="profile_picture" accept="image/*">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position" value="{{ Auth::user()->position }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Profile Modal -->
    <div class="modal fade" id="viewProfileModal" tabindex="-1" aria-labelledby="viewProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProfileModalLabel">
                        <i class="fas fa-user me-2"></i>My Profile
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/profile.png') }}" 
                         alt="Profile Picture" class="rounded-circle mb-3" width="90" height="90" style="object-fit: cover;">
                    <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ Auth::user()->email }}</p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ Auth::user()->phone ?? 'N/A' }}</p>
                    <p class="mb-0"><i class="fas fa-briefcase me-2"></i>{{ Auth::user()->position ?? 'Staff' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (required for dropdowns and modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        function showSidebar() {
            sidebar.classList.add('show');
            sidebarBackdrop.classList.remove('hide');
        }
        function hideSidebar() {
            sidebar.classList.remove('show');
            sidebarBackdrop.classList.add('hide');
        }
        sidebarToggle.addEventListener('click', showSidebar);
        sidebarBackdrop.addEventListener('click', hideSidebar);
        // Hide sidebar on nav link click (mobile)
        document.querySelectorAll('.sidebar a, .sidebar .dropdown-item').forEach(el => {
            el.addEventListener('click', () => {
                if(window.innerWidth < 992) hideSidebar();
            });
        });
        // Hide sidebar on resize if desktop
        window.addEventListener('resize', () => {
            if(window.innerWidth >= 992) hideSidebar();
        });
    </script>
</body>
</html>