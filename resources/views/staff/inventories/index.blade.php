@extends('layouts.main')

@section('title', 'Inventory Management')

@section('content')
<div class="container-fluid px-4" style="margin-left: 20px;">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold"><i class="bi bi-boxes me-2"></i>Inventory Management</h2>
                <div>
                    <!-- <button class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add New Item
                    </button> -->
                </div>
            </div>
            <p class="text-muted">Track and manage your inventory in real-time</p>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-box-seam"></i>
                        <h6 class="mb-1 text-muted">Total Products</h6>
                        <h3 class="fw-bold">{{ $products->count() }}</h3>
                        <p class="text-primary small mb-0"><i class="bi bi-grid-3x3"></i> Across all categories</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-stack"></i>
                        <h6 class="mb-1 text-muted">Total Stock</h6>
                        <h3 class="fw-bold">{{ $products->sum('stock') }}</h3>
                        <p class="text-muted small mb-0">Units in inventory</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-arrow-down-circle"></i>
                        <h6 class="mb-1 text-muted">Stock In (Purchases)</h6>
                        <h3 class="fw-bold">₱{{ number_format($purchases->sum('total_cost'), 2) }}</h3>
                        <p class="text-success small mb-0"><i class="bi bi-arrow-up"></i> Recent purchases</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-arrow-up-circle"></i>
                        <h6 class="mb-1 text-muted">Stock Out (Orders)</h6>
                        <h3 class="fw-bold">₱{{ number_format($orders->sum('total_amount'), 2) }}</h3>
                        <p class="text-info small mb-0">Last 30 days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="inventoryTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">
                    <i class="bi bi-box-seam me-1"></i> Products
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="machine-tab" data-bs-toggle="tab" data-bs-target="#machine" type="button" role="tab">
                    <i class="bi bi-clock-history me-1"></i> Machine Product
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                    <i class="bi bi-cart-check me-1"></i> Orders
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab">
                    <i class="bi bi-cart-plus me-1"></i> Purchases
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab">
                    <i class="bi bi-clock-history me-1"></i> Inventory Logs
                </button>
            </li>
        </ul>

        <div class="tab-content" id="inventoryTabsContent">
            <!-- Products Tab -->
            <div class="tab-pane fade show active" id="products" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span>Product Inventory</span>
                        <div class="d-flex flex-wrap gap-2">
                            <!-- <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <button class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Export
                            </button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div>{{ $products->where('stock', '<=', 5)->count() }}</div>
                        </div>
                        <table id="productsTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Unit</th>
                                    <th>Category</th>
                                    <!-- <th>Supplier</th> -->
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $p)
                                <tr class="{{ $p->stock <= 5 ? 'low-stock' : '' }}">
                                    <td>{{ $p->sku }}</td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->stock }}</td>
                                    <td>{{ $p->unit }}</td>
                                    <td>{{ $p->category->name ?? '-' }}</td>
                                  <!-- <td>{{ optional($p->supplier)->name ?? 'No Supplier' }}</td> -->
                                    <td>
                                        @if($p->stock <= 5)
                                            <span class="badge bg-danger">Low Stock</span>
                                        @elseif($p->stock <= 20)
                                            <span class="badge bg-warning">Moderate</span>
                                        @else
                                            <span class="badge bg-success">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

             <!-- Products Tab -->
            <div class="tab-pane fade show active" id="machine" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span>Machine Inventory</span>
                        <div class="d-flex flex-wrap gap-2">
                           
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div></div>
                        </div>
                        <table id="machineTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Machine Name</th>
                                    <th>brand</th>
                                    <th>Property No.</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($machine as $m)
                                <tr>
                                    <td>{{ $m->machine_name }}</td>
                                    <td>{{ $m->brand }}</td>
                                    <td>{{ $m->property_no }}</td>
                                    <td>{{ $m->cost }}</td>
                                    <td>{{ $m->status }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Orders Tab -->
            <div class="tab-pane fade" id="orders" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span>Order History</span>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <button class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="ordersTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $o)
                                <tr>
                                    <td>{{ $o->order_number }}</td>
                                    <td>{{ $o->user->name }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($o->status == 'pending') bg-warning
                                            @elseif($o->status == 'processing') bg-info
                                            @elseif($o->status == 'completed') bg-success
                                            @elseif($o->status == 'cancelled') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($o->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $o->orderitem->count() }}</td>
                                    <td>₱{{ number_format($o->total_amount, 2) }}</td>
                                    <td>{{ $o->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Purchases Tab -->
            <div class="tab-pane fade" id="purchases" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span>Purchase Records</span>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <button class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark-spreadsheet"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="purchasesTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Items</th>
                                    <th>Total Cost</th>
                                    <th>Status</th>
                                    <!-- <th>Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $p)
                                <tr>
                                    <td>{{ $p->purchase_date ? \Carbon\Carbon::parse($p->purchase_date)->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $p->supplier->name }}</td>
                                    <td>{{ $p->items->count() }}</td>
                                    <td>₱{{ number_format($p->total_cost, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($p->status == 'unpaid') bg-danger
                                            @elseif($p->status == 'partial') bg-warning
                                            @elseif($p->status == 'paid') bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <!-- <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Inventory Logs Tab -->
            <div class="tab-pane fade" id="logs" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span>Inventory Logs</span>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <button id="exportLogs" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark-pdf"></i> Export PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>Track all inventory movements and changes</div>
                        </div>
                        <table id="logsTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Qty</th>
                                    <th>Remarks</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $log->product->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $log->type == 'in' ? 'success' : 'danger' }}">
                                            {{ strtoupper($log->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->quantity }}</td>
                                    <td>{{ $log->remarks }}</td>
                                    <td>{{ $log->user->name ?? 'System' }}</td>
                                </tr>
                                @endforeach -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

@push('styles')
<style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }
        
        .page-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-2px);
        }
        
        .summary-card {
            height: 100%;
            border-left: 4px solid var(--primary-color);
        }
        
        .summary-card i {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .summary-card:nth-child(2) {
            border-left-color: #6c757d;
        }
        
        .summary-card:nth-child(2) i {
            color: #6c757d;
        }
        
        .summary-card:nth-child(3) {
            border-left-color: #20c997;
        }
        
        .summary-card:nth-child(3) i {
            color: #20c997;
        }
        
        .summary-card:nth-child(4) {
            border-left-color: #6610f2;
        }
        
        .summary-card:nth-child(4) i {
            color: #6610f2;
        }
        
        .summary-card:nth-child(5) {
            border-left-color: #fd7e14;
        }
        
        .summary-card:nth-child(5) i {
            color: #fd7e14;
        }
        
        .nav-tabs {
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 1.5rem;
        }
        
        .nav-tabs .nav-link {
            border: none;
            padding: 0.8rem 1.2rem;
            color: #6c757d;
            font-weight: 500;
            border-radius: 8px 8px 0 0;
            transition: all 0.2s;
        }
        
        .nav-tabs .nav-link:hover {
            background-color: rgba(67, 97, 238, 0.05);
            color: var(--primary-color);
        }
        
        .nav-tabs .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            box-shadow: 0 -4px 10px rgba(67, 97, 238, 0.15);
        }
        
        .card-header {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }
        
        .table th {
            font-weight: 600;
            color: #495057;
            border-top: none;
            background-color: #f8f9fa;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(67, 97, 238, 0.03);
        }
        
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 6px;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 0.5rem 1rem;
        }
        
        .filter-buttons .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.4rem 0.8rem;
        }
        
        @media (max-width: 768px) {
            .filter-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .filter-buttons .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
<script>
  $(document).ready(function() {
            // Initialize all DataTables
            $('#productsTable, #machineTable, #ordersTable, #purchasesTable, #logsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-sm btn-outline-primary'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-outline-success'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-outline-danger'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-outline-info'
                    }
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: { 
                        previous: "<i class='bi bi-chevron-left'></i>", 
                        next: "<i class='bi bi-chevron-right'></i>" 
                    }
                },
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });
            
            // Add active class to nav tabs
            $('.nav-tabs .nav-link').on('click', function() {
                $('.nav-tabs .nav-link').removeClass('active');
                $(this).addClass('active');
            });
            
            // Export logs to PDF
            document.getElementById('exportLogs').addEventListener('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                doc.text("Inventory Logs Report", 14, 15);
                doc.autoTable({ 
                    html: '#logsTable', 
                    startY: 25,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [67, 97, 238]
                    }
                });
                doc.save('inventory_logs_report.pdf');
            });
        });
</script>
@endpush
