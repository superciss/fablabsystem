@extends('layouts.main')

@section('title', 'Product Management')

@section('content')
<style>
    /* Modern, professional UI styling */
    body {
        background: #f8fafc;
        font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }

    .dashboard-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .dashboard-subtitle {
        font-size: 1.125rem;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 2rem;
    }

    /* Modern card design */
    .modern-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .modern-card:focus-within {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    .card-header {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.1;
        background: linear-gradient(to right, #1e293b, #334155);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Icon styling */
    .icon-circle {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.75rem;
        transition: transform 0.3s ease;
    }

    .icon-circle:hover {
        transform: scale(1.1);
    }

    .bg-primary { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: #ffffff; }
    .bg-success { background: linear-gradient(135deg, #10b981, #34d399); color: #ffffff; }
    .bg-danger { background: linear-gradient(135deg, #ef4444, #f87171); color: #ffffff; }

    .text-primary { background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-success { background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-danger { background: linear-gradient(to right, #ef4444, #f87171); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* Modern table styling */
    .modern-table {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .modern-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table .card-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
    }

    .modern-table thead {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
    }

    .modern-table thead th {
        padding: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.075em;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table tbody td {
        padding: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #1e293b;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.2s ease;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modern badge styling */
    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .badge:hover {
        transform: translateY(-1px);
    }

    .badge-info { background: #cffafe; color: #0e7490; }

    /* Button styling */
    .btn-modern {
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-primary { background: linear-gradient(135deg, #3b82f6, #60a5fa); border: none; color: #ffffff; }
    .btn-primary:hover { background: linear-gradient(135deg, #2563eb, #3b82f6); }
    .btn-outline-secondary { border-color: #e2e8f0; color: #64748b; }
    .btn-outline-secondary:hover { background: #f8fafc; }
    .btn-outline-warning { border-color: #f59e0b; color: #f59e0b; }
    .btn-outline-warning:hover { background: #fef3c7; }
    .btn-outline-danger { border-color: #ef4444; color: #ef4444; }
    .btn-outline-danger:hover { background: #fee2e2; }

    /* Category toast styling */
    .category-toast {
        position: absolute;
        top: 70px;
        right: 20px;
        width: 220px;
        display: none;
        z-index: 1055;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        padding: 0.75rem;
    }

    .category-toast .dropdown-item {
        color: #1e293b;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .category-toast .dropdown-item:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .category-toast i {
        color: #64748b;
        margin-right: 8px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.75rem;
        }
        .dashboard-subtitle {
            font-size: 1rem;
        }
        .stat-value {
            font-size: 1.75rem;
        }
        .modern-card {
            padding: 1.25rem;
        }
        .icon-circle {
            width: 48px;
            height: 48px;
            font-size: 1.5rem;
        }
        .modern-table .card-header {
            padding: 1rem 1.5rem;
        }
        .category-toast {
            width: 180px;
            top: 60px;
            right: 10px;
        }
    }
</style>
<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h2 class="dashboard-title">Raw Material Management</h2>
            <p class="dashboard-subtitle">Manage your products, stock levels, and categories</p>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-primary btn-modern d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addRawModal">
                <i class="bi bi-plus-lg"></i> Add RAw Material
            </button>
            <a href="{{ route('admin.category.index') }}" class="btn btn-outline-secondary btn-modern d-flex align-items-center gap-2">
                <i class="bi bi-tags"></i> Categories
            </a>
            <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline-secondary btn-modern d-flex align-items-center gap-2">
                <i class="bi bi-truck"></i> Suppliers
            </a>
             <a href="{{ route('admin.machines.index') }}" class="btn btn-outline-secondary btn-modern d-flex align-items-center gap-2">
                <i class="bi bi-truck"></i> Machine Product
            </a>
            <button class="btn btn-success btn-modern d-flex align-items-center gap-2" 
                    onclick="downloadAllProducts()">
                <i class="bi bi-download"></i> Download
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Products</div>
                        <h4 class="stat-value text-primary">{{ $products->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">In Stock</div>
                        <h4 class="stat-value text-success">{{ $products->where('stock', '>', 0)->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Low Stock</div>
                        <h4 class="stat-value text-danger">{{ $products->where('stock', '<', 5)->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="modern-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Product Directory</h5>
            <div class="d-flex gap-3" id="filterButtons">
                <button id="filterLow" class="btn btn-sm btn-outline-danger btn-modern" title="Low Stock">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </button>
                <button id="filterHigh" class="btn btn-sm btn-outline-success btn-modern" title="High Stock">
                    <i class="bi bi-box-seam-fill"></i>
                </button>
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary btn-modern" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
                <button id="categoryFilterBtn" class="btn btn-sm btn-outline-primary btn-modern" title="Filter Category">
                    <i class="bi bi-funnel-fill"></i>
                </button>
            </div>
            <!-- Category Filter Toast -->
            <div id="categoryToast" class="category-toast">
                <ul class="list-unstyled mb-0">
                    <li>
                        <a href="javascript:void(0)" class="dropdown-item category-filter" data-category="all">
                            <i class="bi bi-circle"></i> All
                        </a>
                    </li>
                    @foreach($categories as $category)
                    <li>
                        <a href="javascript:void(0)" class="dropdown-item category-filter" data-category="{{ $category->name }}">
                            <i class="bi bi-tag"></i> {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="productsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price (₱)</th>
                            <th>Stock</th>
                            <th>Unit</th>
                            <th>Image</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td><span class="badge badge-info">{{ $product->category?->name ?? 'N/A' }}</span></td>
                            <td class="text-success fw-bold">{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->unit }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ $product->image }}" class="rounded shadow-sm" width="45" alt="{{ $product->name }}">
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning btn-modern" data-bs-toggle="modal" data-bs-target="#editRawModal{{ $product->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form id="delete-form-{{ $product->id }}" action="{{ route('material.destroy', $product->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-modern" onclick="confirmDelete('delete-form-{{ $product->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                
                            </td>
                        </tr>
                        @include('admin.materials.editraw', ['product' => $product, 'categories' => $categories])
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 2rem;">No products found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.materials.addraw', ['categories' => $categories])
@endsection

@push('styles')
<style>
    .category-toast {
        position: absolute;
        top: 70px;
        right: 20px;
        width: 220px;
        display: none;
        z-index: 1055;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        padding: 0.75rem;
    }

    .category-toast .dropdown-item {
        color: #1e293b;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .category-toast .dropdown-item:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .category-toast i {
        color: #64748b;
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .category-toast {
            width: 180px;
            top: 60px;
            right: 10px;
        }
    }
</style>
@endpush

@push('scripts')

{{-- ✅ jsPDF & AutoTable --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.1/jspdf.plugin.autotable.min.js"></script>

<script>
  function drawHeader(doc, logoImage) {
      const pageWidth = doc.internal.pageSize.width;

      doc.addImage(logoImage, 'PNG', 10, 9, 17, 17);

      doc.setFontSize(11);
      doc.setFont("Helvetica", "normal");
      doc.text("Republic of the Philippines", 30, 13);

      doc.setFontSize(11);
      doc.setFont("Helvetica", "bold");
      doc.text("CAMARINES SUR POLYTECHNIC COLLEGES", 30, 18);

      doc.setFontSize(11);
      doc.setFont("Helvetica", "normal");
      doc.text("Nabua, Camarines Sur", 30, 22);

      doc.setFontSize(11);
      doc.setFont("Helvetica", "bold");
      doc.text("PRODUCTION AND ENTREPRENEURIAL DEVELOPMENT SERVICES", 30, 26);

      doc.setDrawColor(0, 0, 255);
      doc.setLineWidth(1);
      doc.line(5, 34, 170, 34);

      doc.setFontSize(10);
      doc.setFont("Helvetica", "bold");
      doc.text("CSPC-F-PEDS-01", pageWidth - 10, 35, { align: "right" });
  }

  // ✅ Download All Products
  window.downloadAllProducts = function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {
        drawHeader(doc, logoImage);

       const products = @json($products);

        const rows = products.map(p => {
        const consumed = p.consumed_units ?? 0;
        const stock = p.stock ?? 0;
        const available = stock - consumed;

        return [
            p.name,
            p.unit,
            stock,       
            0,           // sponsored
            0,           // damaged
            consumed,    // ✅ total ordered quantity
            available    // ✅ stock minus consumed
        ];
        });


        doc.autoTable({
            startY: 55,
            head: [[
              'Item',
              'Unit',
              'No. of Units on Display',
              'No. of Sponsored Units',
              'No. of Damaged Units',
              'No. of Units Consumed',
              'Available Units for Production'
            ]],
            body: rows,
            theme: "plain",
            styles: { font: "Helvetica", fontSize: 10, lineColor: [0,0,0], lineWidth: 0.1 },
            headStyles: { fontStyle: "normal", fillColor: false, textColor: [0,0,0] }
        });

        doc.save("all_products.pdf");
    };
  }
</script>


<script>
$(document).ready(function () {
    let table = $('#productsTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search products...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ products",
            paginate: {
                previous: "<i class='bi bi-chevron-left'></i>",
                next: "<i class='bi bi-chevron-right'></i>"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: true,
        ordering: true,
        columnDefs: [
            { orderable: false, targets: [6, 7] }
        ]
    });

    // Stock Filters
    $('#filterLow').on('click', function () {
        table.column(4).search('^([0-4])$', true, false).draw();
    });
    $('#filterHigh').on('click', function () {
        table.column(4).search('^(5[1-9]|[6-9][0-9]|[1-9][0-9]{2,})$', true, false).draw();
    });
    $('#resetFilter').on('click', function () {
        table.search('').columns().search('').draw();
    });

    // Category Filter Toast
    $('#categoryFilterBtn').on('click', function (e) {
        e.stopPropagation();
        $('#categoryToast').toggle();
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#categoryToast, #categoryFilterBtn').length) {
            $('#categoryToast').hide();
        }
    });
    $('.category-filter').on('click', function () {
        let category = $(this).data('category');
        table.column(2).search(category === "all" ? "" : category).draw();
        $('#categoryToast').hide();
    });

    // Confirm delete function
    window.confirmDelete = function(formId) {
        if (confirm('Are you sure you want to delete this product?')) {
            document.getElementById(formId).submit();
        }
    };
});
</script>
@endpush