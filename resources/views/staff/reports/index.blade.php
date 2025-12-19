@extends('layouts.main')

@section('title', 'Product Management')

@section('content')     
    <div class="container-fluid px-4" style="margin-left:20px;">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold"><i class="bi bi-bar-chart-fill me-2"></i>Reports Dashboard</h2>
                <div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-currency-exchange"></i>
                        <h6 class="mb-1 text-muted">Total Revenue</h6>
                        <h3 class="fw-bold">₱{{ number_format($totalRevenue, 2) }}</h3>
                        <p class="text-success small mb-0"><i class="bi bi-arrow-up"></i> 12.5% from last month</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-cart-check"></i>
                        <h6 class="mb-1 text-muted">Total Purchases</h6>
                        <h3 class="fw-bold">₱{{ number_format($totalPurchases, 2) }}</h3>
                        <p class="text-muted small mb-0">Last 30 days</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-graph-up-arrow"></i>
                        <h6 class="mb-1 text-muted">Profit</h6>
                        <h3 class="fw-bold">₱{{ number_format($profit, 2) }}</h3>
                        <p class="text-success small mb-0"><i class="bi bi-arrow-up"></i> 8.3% growth</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-clipboard-data"></i>
                        <h6 class="mb-1 text-muted">Orders</h6>
                        <h3 class="fw-bold">{{ $ordersByStatus->sum() }}</h3>
                        <div class="d-flex justify-content-center mt-2">
                            <span class="badge bg-warning me-1">Pending: {{ $ordersByStatus['pending'] ?? 0 }}</span>
                            <span class="badge bg-success me-1">Completed: {{ $ordersByStatus['completed'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6">
                <div class="card summary-card">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-exclamation-triangle"></i>
                        <h6 class="mb-1 text-muted">Low Stock</h6>
                        <h3 class="fw-bold">{{ $lowStock->count() }}</h3>
                        <p class="text-danger small mb-0">Needs immediate attention</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab">
                    <i class="bi bi-cash-coin me-1"></i> Sales
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab">
                    <i class="bi bi-boxes me-1"></i> Inventory
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases" type="button" role="tab">
                    <i class="bi bi-cart me-1"></i> Purchases
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="suppliers-tab" data-bs-toggle="tab" data-bs-target="#suppliers" type="button" role="tab">
                    <i class="bi bi-truck me-1"></i> Suppliers
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers" type="button" role="tab">
                    <i class="bi bi-people me-1"></i> Customers
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="financials-tab" data-bs-toggle="tab" data-bs-target="#financials" type="button" role="tab">
                    <i class="bi bi-graph-up me-1"></i> Financials
                </button>
            </li>
        </ul>

        <div class="tab-content" id="reportTabsContent">
            <!-- Sales Tab -->
            <div class="tab-pane fade show active" id="sales" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <span>Sales Reports</span>
                        <div class="d-flex flex-wrap gap-2 filter-buttons">
                            <button id="filterPending" class="btn btn-sm btn-outline-warning shadow-sm">
                                <i class="bi bi-hourglass-split"></i> Pending
                            </button>
                            <button id="filterProcessing" class="btn btn-sm btn-outline-info shadow-sm">
                                <i class="bi bi-arrow-repeat"></i> Processing
                            </button>
                            <button id="filterCompleted" class="btn btn-sm btn-outline-success shadow-sm">
                                <i class="bi bi-check2-circle"></i> Completed
                            </button>
                            <button id="filterCancelled" class="btn btn-sm btn-outline-danger shadow-sm">
                                <i class="bi bi-x-circle"></i> Cancelled
                            </button>
                            <button id="resetFilter" class="btn btn-sm btn-outline-secondary shadow-sm">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="salesTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Delivery</th>
                                    <th>Total Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($order->status=='pending') bg-warning
                                                @elseif($order->status=='processing') bg-info
                                                @elseif($order->status=='completed') bg-success
                                                @elseif($order->status=='cancelled') bg-danger
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($order->delivery_type) }}</td>
                                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <button id="generate_receipt_{{ $order->id }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-pdf"></i> Receipt
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Inventory Tab -->
            <div class="tab-pane fade" id="inventory" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Inventory Reports</span>
                        <button class="btn btn-success d-flex align-items-center gap-2 shadow-sm" 
                                onclick="downloadAllData()">
                            <i class="bi bi-download"></i> Download
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-transparent">Product</div>
                                    <div class="card-body">
                                        <table id="inventoryTable" class="table table-striped table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Stock</th>
                                                    <th>Category</th>
                                                    <th>Unit</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stockLevels as $item)
                                                    <tr>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->stock }}</td>
                                                        <td>{{ $item->category->name ?? 'N/A' }}</td>
                                                        <td>{{ $item->unit }}</td>
                                                        <td>
                                                            @if($item->stock < 10)
                                                                <span class="badge bg-danger">Low Stock</span>
                                                            @elseif($item->stock < 25)
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


                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-transparent">Machines Products</div>
                                    <div class="card-body">
                                       <table id="inventoryTable" class="table table-striped table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Machine Name</th>
                                                <th>brand</th>
                                                <th>Property Number</th>
                                                <th>Status</th>
                                                <th>Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($machine as $m)
                                                <tr>
                                                    <td>{{ $m->machine_name }}</td>
                                                    <td>{{ $m->brand }}</td>
                                                    <td>{{ $m->property_no }}</td>
                                                    <td>{{ $m->status }}</td>
                                                     <td>{{ $m->cost }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Purchases Tab -->
            <div class="tab-pane fade" id="purchases" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header">Purchase Reports</div>
                    <div class="card-body">
                        <table id="purchaseTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                   <tr>
                                        <td>{{ $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') : '-' }}</td>
                                        <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($purchase->status=='unpaid') bg-danger
                                                @elseif($purchase->status=='partial') bg-warning
                                                @elseif($purchase->status=='paid') bg-success
                                                @endif">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $purchase->items->count() }}</td>
                                        <td>₱{{ number_format($purchase->total_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Suppliers Tab -->
            <div class="tab-pane fade" id="suppliers" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header">Supplier Reports</div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center p-3">
                                        <h6 class="card-title text-muted">Total Suppliers</h6>
                                        <h3 class="fw-bold">{{ count($purchaseVolumePerSupplier) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center p-3">
                                        <h6 class="card-title text-muted">Active This Month</h6>
                                        <h3 class="fw-bold">
                                            {{ count($purchaseVolumePerSupplier->filter(function($item) { 
                                                return $item['total'] > 0; 
                                            })) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <table id="suppliersTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Total Purchases</th>
                                    <th>Contact</th>
                                    <th>Phone</th>
                                    <th>Last Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseVolumePerSupplier as $data)
                                    <tr>
                                        <td>{{ $data['supplier']->name ?? 'N/A' }}</td>
                                        <td>₱{{ number_format($data['total'], 2) }}</td>
                                        <td>{{ $data['supplier']->contact_person ?? '-' }}</td>
                                        <td>{{ $data['supplier']->phone ?? '-' }}</td>
                                        <td>{{ $data['last_order'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customers Tab -->
            <div class="tab-pane fade" id="customers" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header">Top Customers</div>
                    <div class="card-body">
                        <div class="alert alert-primary d-flex align-items-center" role="alert">
                            <i class="bi bi-lightbulb me-2"></i>
                            <div>Your top 10 customers by spending</div>
                        </div>
                        
                        <table id="customersTable" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Total Spent</th>
                                    <th>Orders</th>
                                    <th>Last Order</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $cust)
                                    <tr>
                                        <td>{{ $cust['user']->name ?? 'N/A' }}</td>
                                        <td>₱{{ number_format($cust['spent'], 2) }}</td>
                                        <td>{{ $cust['items_count'] ?? 0 }}</td>
                                        <td>{{ isset($cust['last_order_date']) ? $cust['last_order_date']->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Financials Tab -->
            <div class="tab-pane fade" id="financials" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header">Financial Reports</div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-success bg-opacity-10">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-currency-exchange fs-1 text-success"></i>
                                        <h4 class="mt-2 fw-bold">₱{{ number_format($revenue, 2) }}</h4>
                                        <p class="mb-0 text-success">Total Revenue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger bg-opacity-10">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-currency-exchange fs-1 text-danger"></i>
                                        <h4 class="mt-2 fw-bold">₱{{ number_format($expenses, 2) }}</h4>
                                        <p class="mb-0 text-danger">Total Expenses</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-primary bg-opacity-10">
                                    <div class="card-body text-center p-4">
                                        <i class="bi bi-graph-up-arrow fs-1 text-primary"></i>
                                        <h4 class="mt-2 fw-bold">₱{{ number_format($profit, 2) }}</h4>
                                        <p class="mb-0 text-primary">Net Profit</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-transparent">Monthly Performance</div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Revenue Growth</span>
                                                    <span class="badge {{ $revenueGrowth >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                                        {{ number_format($revenueGrowth, 2) }}%
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Expense Change</span>
                                                    <span class="badge {{ $expenseChange >= 0 ? 'bg-danger' : 'bg-success' }} rounded-pill">
                                                        {{ number_format($expenseChange, 2) }}%
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>Profit Margin</span>
                                                    <span class="badge bg-primary rounded-pill">
                                                        {{ number_format($profitMargin, 2) }}%
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-transparent">Key Metrics</div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>Average Order Value</span>
                                                <span>₱{{ number_format($totalRevenue / max($ordersByStatus->sum(), 1), 2) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>Customer Lifetime Value</span>
                                                <span>₱{{ number_format($totalRevenue / max(count($topCustomers), 1), 2) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>Inventory Turnover</span>
                                                <span>{{ number_format($inventoryTurnover, 2) }}x</span>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    window.ordersData = @json($ordersData);
    </script>
    <script>
        $(document).ready(function() {
            // Initialize all DataTables
            $('#salesTable, #inventoryTable, #purchaseTable, #suppliersTable, #customersTable').DataTable({
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
                order: [[1, 'desc']]
            });
            
            // Filter functions for sales table
          function filterSales(status) {
        const table = $('#salesTable').DataTable();
        if (!status) table.column(3).search('').draw(); // Search in status column
        else table.column(3).search(status, true, false).draw();
            }

            $('#filterPending').on('click', () => filterSales('Pending'));
            $('#filterProcessing').on('click', () => filterSales('Processing'));
            $('#filterCompleted').on('click', () => filterSales('Completed'));
            $('#filterCancelled').on('click', () => filterSales('Cancelled'));
            $('#resetFilter').on('click', () => filterSales(''));

            
            // Add active class to nav tabs
            $('.nav-tabs .nav-link').on('click', function() {
                $('.nav-tabs .nav-link').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>

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

  // ✅ Helper to print section titles
  function printSectionTitle(doc, title, subtitle, date, gapY) {
    doc.setFontSize(12);
    doc.setFont("Helvetica", "bold");
    doc.text(title, doc.internal.pageSize.width / 2, gapY, { align: "center" });

    if (subtitle) {
      doc.setFontSize(12);
      doc.text(subtitle, doc.internal.pageSize.width / 2, gapY + 6, { align: "center" });
    }

    if (date) {
      doc.setFontSize(11);
      doc.setFont("Helvetica", "normal");
      doc.text("As of " + date, doc.internal.pageSize.width / 2, gapY + 12, { align: "center" });
    }
  }

  // ✅ Combined Download with headers on every page
  window.downloadAllData = function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const logoImage = new Image();
    logoImage.src = '/images/cspc.png';

    logoImage.onload = () => {
      const products = @json($Products);

      // ✅ Group products by category
      const groupedProducts = {};
      products.forEach(p => {
        const cat = p.pro_category || "Uncategorized";
        if (!groupedProducts[cat]) groupedProducts[cat] = [];
        const consumed = p.consumed_units ?? 0;
        const stock = p.stock ?? 0;
        const available = stock - consumed;
        groupedProducts[cat].push([
          p.name,
          p.unit,
          stock,
          0,
          0,
          consumed,
          available,
          p.created_at // store date for later sorting
        ]);
      });

      let currentY = 45;

      // ✅ Loop through each category, create a section and table
      Object.keys(groupedProducts).forEach(category => {
        const items = groupedProducts[category];

        // ✅ Get latest created_at per category
        let latestCreatedAt = "";
        if (items.length > 0) {
          const sorted = [...items].sort((a, b) => new Date(b[7]) - new Date(a[7]));
          const latestDate = new Date(sorted[0][7]);
          latestCreatedAt = latestDate.toLocaleDateString("en-PH", {
            year: "numeric",
            month: "long",
            day: "numeric",
            timeZone: "Asia/Manila"
          });
        }

        // ✅ Title block
        printSectionTitle(
          doc,
          "INVENTORY OF MATERIALS",
          category,
          latestCreatedAt,
          currentY
        );

        // ✅ Remove created_at column before rendering
        const bodyRows = items.map(i => i.slice(0, 7));

        // ✅ Render table for this category
        doc.autoTable({
          startY: currentY + 20,
          head: [[
            'Item',
            'Unit',
            'No. of Units on Display',
            'No. of Sponsored Units',
            'No. of Damaged Units',
            'No. of Units Consumed',
            'Available Units for Production'
          ]],
          body: bodyRows,
          theme: "plain",
          styles: { font: "Helvetica", fontSize: 10, lineColor: [0,0,0], lineWidth: 0.1 },
          headStyles: { fontStyle: "normal", fillColor: false, textColor: [0,0,0] },
          margin: { top: 55 },
          didDrawPage: function () {
            drawHeader(doc, logoImage);
          }
        });

        currentY = doc.lastAutoTable.finalY + 20; // update Y for next category
      });

      // --- Machines ---
      const machines = @json($machine);
      const machineRows = machines.map(m => {
        const createdAt = new Date(m.created_at);
        const formattedDate = createdAt.toLocaleDateString("en-PH", { timeZone: "Asia/Manila" });
        return [
          m.machine_name,
          m.brand,
          m.property_no,
          formattedDate,
          m.status,
          "" + parseFloat(m.cost).toLocaleString(),
          m.created_at
        ];
      });

      // ✅ Get latest created_at from machines
      let latestCreatedAt = "";
      if (machines.length > 0) {
        const sorted = [...machines].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        const latestDate = new Date(sorted[0].created_at);
        latestCreatedAt = latestDate.toLocaleDateString("en-PH", {
          year: "numeric",
          month: "long",
          day: "numeric",
          timeZone: "Asia/Manila"
        });
      }

      // ✅ Title block before Machines
      if (machines.length > 0) {
        printSectionTitle(
          doc,
          "INVENTORY OF MACHINERY AND EQUIPMENT",
          null,
          latestCreatedAt,
          currentY
        );
      }

      // ✅ Machines Table
      const machineBody = machineRows.map(r => r.slice(0, 6));

      doc.autoTable({
        startY: currentY + 20,
        head: [['Machine Name', 'Brand', 'Property No.', 'Date Acquired', 'Status', 'Cost']],
        body: machineBody,
        theme: "plain",
        styles: { font: "Helvetica", fontSize: 10, lineColor: [0,0,0], lineWidth: 0.1 },
        headStyles: { fontStyle: "normal", fillColor: false, textColor: [0,0,0] },
        margin: { top: 55 },
        didDrawPage: function () {
          drawHeader(doc, logoImage);
        }
      });

      // ✅ Save combined file
      doc.save("fablab_inventory.pdf");
    };
  }
</script>
    @endpush
