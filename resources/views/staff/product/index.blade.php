@extends('layouts.main')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid px-4" style="margin-left:20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <h2 class="mb-0 text-dark">Products Management</h2>
            <p class="text-muted mb-0">Manage your products, stock levels, and categories</p>
        </div>

          <div class="d-flex gap-3">
            <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" 
                data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-lg"></i> Add Product
        </button>
            <a href="{{ route('staff.machine.index') }}" class="btn btn-outline-secondary btn-modern d-flex align-items-center gap-2">
                <i class="bi bi-tags"></i> Machine Product
            </a>
           
        </div>

    </div>


    <!-- ✅ Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-primary bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Products</p>
                        <h4 class="mb-0">{{ $products->count() }}</h4>
                    </div>
                    <i class="bi bi-box-seam display-6 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-success bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">In Stock</p>
                        <h4 class="mb-0">{{ $products->where('stock','>',0)->count() }}</h4>
                    </div>
                    <i class="bi bi-check2-circle display-6 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-danger bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Low Stock</p>
                        <h4 class="mb-0">{{ $products->where('stock','<',5)->count() }}</h4>
                    </div>
                    <i class="bi bi-exclamation-triangle display-6 text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Products Table -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-gray py-3 d-flex justify-content-between align-items-center position-relative">
            <h5 class="mb-0 text-dark">Product Directory</h5>

            <!-- Filters -->
            <div class="d-flex gap-2" id="filterButtons">
                <button id="filterLow" class="btn btn-sm btn-outline-danger shadow-sm filter-btn" title="Low Stock">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </button>
                <button id="filterHigh" class="btn btn-sm btn-outline-success shadow-sm filter-btn" title="High Stock">
                    <i class="bi bi-box-seam-fill"></i>
                </button>
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary shadow-sm filter-btn" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>

                <!-- Category Filter -->
                <button id="categoryFilterBtn" class="btn btn-sm btn-outline-primary shadow-sm filter-btn" title="Filter Category">
                    <i class="bi bi-funnel-fill"></i>
                </button>
            </div>

            <!-- Toast-style Category Menu -->
            <div id="categoryToast" class="category-toast shadow rounded bg-white p-2">
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
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price (₱)</th>
                            <th>Stock</th>
                            <th>Unit</th>
                            <th>Image</th>
                            <th width="180" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td><span class="badge bg-info">{{ $product->category?->name ?? 'N/A' }}</span></td>
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
                                <button class="btn btn-sm btn-outline-warning py-1 px-2" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                               <form id="delete-form-{{ $product->id }}" action="{{ route('staffproduct.destroy', $product->id) }}"  method="POST" class="d-inline-block">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-xs d-flex align-items-center gap-1 py-1" 
                                                onclick="confirmDelete('delete-form-{{ $product->id }}')">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </form>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        @include('staff.product.editproduct', ['product' => $product, 'categories' => $categories])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
@include('staff.product.addproduct', ['categories' => $categories])
@endsection

@push('styles')
<style>
.category-toast {
    position: absolute;
    top: 60px;
    right: 20px;
    width: 200px;
    display: none;
    z-index: 1055;
    background-color: #fff;
    border: 1px solid #ddd;
}
.category-toast .dropdown-item {
    color: #000 !important;
    font-size: 0.9rem;
    padding: 6px 10px;
    border-radius: 6px;
}
.category-toast .dropdown-item:hover {
    background-color: #f1f1f1;
}
.category-toast i { color: #000; margin-right: 5px; }
</style>
@endpush

@push('scripts')
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
});
</script>
@endpush
