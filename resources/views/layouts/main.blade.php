<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CSPC FabLab')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Sidebar CSS -->
    <link rel="stylesheet" href="public/css/sidebar.css">

    <style>
        

    .sidebar {
        width: 280px;
        position: fixed;
        top: 56px;
        left: 0;
        height: calc(100% - 56px); 
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.9) 0%, rgba(55, 48, 163, 0.9) 50%, rgba(30, 64, 175, 0.9) 100%);
        color: #fff;
        padding: 0;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* User Card */
    .user-card {
        margin: 1.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        backdrop-filter: blur(10px);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .user-avatar i {
        font-size: 1.5rem;
        color: white;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #ffffff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.75rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Navigation */
    .sidebar-nav {
        flex: 1;
        padding: 0 1.5rem;
        overflow-y: auto;
    }

    .nav-section {
        margin-bottom: 2rem;
    }

    .nav-section-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.75rem;
        display: block;
        padding-left: 0.5rem;
    }

    .nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-item {
        margin-bottom: 0.25rem;
        position: relative;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #cbd5e1;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        gap: 0.75rem;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .nav-link.active {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    .nav-link.active .nav-indicator {
        opacity: 1;
        transform: scaleY(1);
    }

    .nav-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .nav-icon i {
        font-size: 1.1rem;
    }

    .nav-text {
        font-weight: 500;
        font-size: 0.9rem;
        flex-grow: 1;
    }

    .nav-indicator {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%) scaleY(0);
        width: 3px;
        height: 20px;
        background: #ffffff;
        border-radius: 2px;
        opacity: 0;
        transition: all 0.3s ease;
    }

    /* Dropdown Styles */
    .dropdown-toggle {
        position: relative;
    }

    .dropdown-arrow {
        font-size: 0.8rem;
        transition: transform 0.3s ease;
        margin-left: auto;
    }

    .dropdown-toggle[aria-expanded="true"] .dropdown-arrow {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        position: static !important;
        transform: none !important;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 0.5rem 0;
        margin: 0.5rem 0 0.5rem 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        width: calc(100% - 1rem);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #cbd5e1;
        text-decoration: none;
        gap: 0.75rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .dropdown-item i {
        width: 20px;
        text-align: center;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff;
        border-left: 3px solid #3b82f6;
        padding-left: 1.25rem;
    }

    .dropdown-item.active {
        background: rgba(59, 130, 246, 0.2);
        color: #ffffff;
        border-left: 3px solid #3b82f6;
    }

    /* Footer */
    .sidebar-footer {
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: auto;
    }

    .logout-form {
        margin: 0;
    }

    .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 10px;
        color: #fca5a5;
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #ffffff;
        border-color: rgba(239, 68, 68, 0.4);
        transform: translateY(-1px);
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar.open {
            transform: translateX(0);
        }
    }

    /* Collapsed Sidebar (desktop) */
.sidebar.collapsed {
    width: 70px;
    overflow-x: hidden;
}

.sidebar.collapsed .user-card,
.sidebar.collapsed .nav-text,
.sidebar.collapsed .nav-section-title,
.sidebar.collapsed .dropdown-menu {
    display: none;
}

.sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 0.75rem;
}

.sidebar.collapsed .nav-icon {
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 1000;
    }
    .sidebar.open {
        transform: translateX(0);
    }
    
    #sidebarToggle { display: none !important; }
}

    </style>

    @stack('styles')
</head>
<body>
    <!-- ✅ Navbar -->
    @include('layouts.navbar')


    @include('layouts.sidebar')
    

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>

    
    <style>
    .content {
    margin-top: 40px;              
    margin-left: 280px;           
    padding: 20px;                 
    width: calc(100% - 280px);    
    min-height: calc(100vh - 56px);
    background-color: #fff;   
}

/* ✅ Mobile Fix */
@media (max-width: 768px) {
    .content {
        margin-left: 0;     /* Alisin yung sidebar spacing */
        width: 100%;        /* Full width na sya */
    }
}

    </style>

    <!-- ✅ Low Stock Toast (Only for Admin & Staff) -->
    @if(isset($lowStockCount) && $lowStockCount > 0 && auth()->check() && in_array(auth()->user()->role, ['admin','staff']))
    <style>
            .low-stock-toast {
            position: fixed;
            top: 70px;
            right: 20px;
            width: 350px;
            background: #d63031;
            color: #fff;
            padding: 18px 20px;
            border-radius: 12px;
            z-index: 4000;
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .low-stock-toast h5 {
            font-size: 17px;
            margin: 0;
            font-weight: bold;
        }

        .low-stock-toast ul {
            margin: 8px 0 0 18px;
            padding: 0;
        }

        .low-stock-toast li {
            font-size: 0.85rem;
        }

        .low-stock-close {
            position: absolute;
            top: 10px;
            right: 13px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>

        @if(isset($lowStockProducts) && count($lowStockProducts) > 0)
            
                <div id="lowStockToast" class="low-stock-toast" style="display:none;">
            
            <span class="low-stock-close" onclick="document.getElementById('lowStockToast').style.display='none';">&times;</span>

            <h5><i class="bi bi-exclamation-triangle-fill me-1"></i> Low Stock Alert!</h5>
            <small>Items based on last month's usage:</small>

            <ul>
                @foreach($lowStockProducts as $product)
                    <li>{{ $product->name }} — <b>{{ $product->stock }}</b> left</li>
                @endforeach
            </ul>
        </div>
        
        @endif

        
    @endif

    <!-- ✅ Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- PDF & SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/receipt.js') }}"></script>
    <script src="{{ asset('js/ex.js') }}"></script>

   <script>
$(document).ready(function() {
    // ---------------------------------------------
    // DataTables
    // ---------------------------------------------
    $('.datatable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search..."
        }
    });

    // ---------------------------------------------
    // Show Low Stock Top Banner (Admin + Staff Only)
    // ---------------------------------------------
    @if(isset($lowStockCount) && $lowStockCount > 0 && auth()->check() && in_array(auth()->user()->role, ['admin','staff']))
        const banner = document.getElementById("lowStockBanner");
        if (banner) {
            banner.style.display = "flex"; // show on page load
        }
    @endif
});


// ---------------------------------------------
// SweetAlert Flash Messages
// ---------------------------------------------

document.addEventListener("DOMContentLoaded", function () {
    const toast = document.getElementById("lowStockToast");
    if (toast) {
        toast.style.display = "block"; // show on page load
    }
});


document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            timer: 1000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true
        });
    @endif
});


// ---------------------------------------------
// Global Delete Confirmation
// ---------------------------------------------
function confirmDelete(formId, message = "This action cannot be undone.") {
    Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>

    @stack('scripts')
</body>
</html>
