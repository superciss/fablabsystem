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
