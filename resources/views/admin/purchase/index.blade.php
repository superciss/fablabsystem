@extends('layouts.main')

@section('title', 'Purchases Management')

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
    .bg-warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #ffffff; }
    .bg-danger { background: linear-gradient(135deg, #ef4444, #f87171); color: #ffffff; }

    .text-primary { background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-success { background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-warning { background: linear-gradient(to right, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
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

    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-partial { background: #fef3c7; color: #92400e; }
    .badge-unpaid { background: #fee2e2; color: #991b1b; }
    .badge-default { background: #e2e8f0; color: #1e293b; }

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

    /* Select styling */
    select.form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem;
        font-size: 0.875rem;
        color: #1e293b;
        background: #ffffff;
        transition: all 0.3s ease;
    }

    select.form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
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
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="dashboard-title">Purchases Management</h2>
                <p class="dashboard-subtitle">Manage purchase transactions and supplier orders</p>
            </div>
        </div>
        <button class="btn btn-primary btn-modern d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addPurchaseModal">
            <i class="bi bi-plus-lg"></i> Add Purchase
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Paid</div>
                        <h4 class="stat-value text-success">{{ $purchases->where('status', 'paid')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Partial</div>
                        <h4 class="stat-value text-warning">{{ $purchases->where('status', 'partial')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Unpaid</div>
                        <h4 class="stat-value text-danger">{{ $purchases->where('status', 'unpaid')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-danger">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="modern-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Purchases List</h5>
            <div class="d-flex gap-3 align-items-center">
                <select id="supplierFilter" class="form-select">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                <button id="filterPaid" class="btn btn-sm btn-outline-success btn-modern" title="Paid">
                    <i class="bi bi-check-circle"></i>
                </button>
                <button id="filterPartial" class="btn btn-sm btn-outline-warning btn-modern" title="Partial">
                    <i class="bi bi-hourglass-split"></i>
                </button>
                <button id="filterUnpaid" class="btn btn-sm btn-outline-danger btn-modern" title="Unpaid">
                    <i class="bi bi-x-circle"></i>
                </button>
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary btn-modern" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="purchasesTable" class="table table-sm align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Purchase ID</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Total Cost</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Balance</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                        <tr>
                            <td>#{{ $purchase->id }}</td>
                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</td>
                            <td class="text-success fw-bold">₱{{ number_format($purchase->total_cost, 2) }}</td>
                            <td>
                                <ul class="list-unstyled mb-0 text-sm">
                                    @foreach($purchase->items as $item)
                                        <li class="d-flex align-items-center gap-2">
                                            <span>{{ $item->product->name ?? 'N/A' }}</span>
                                            <span class="text-muted">(x{{ $item->quantity }})</span>
                                            <span class="badge badge-default">₱{{ number_format($item->cost, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @switch($purchase->status)
                                    @case('paid')
                                        <span class="badge badge-paid">Paid</span>
                                        @break
                                    @case('partial')
                                        <span class="badge badge-partial">Partial</span>
                                        @break
                                    @case('unpaid')
                                        <span class="badge badge-unpaid">Unpaid</span>
                                        @break
                                    @default
                                        <span class="badge badge-default">{{ ucfirst($purchase->status) }}</span>
                                @endswitch
                            </td>
                            <td class="fw-bold" style="color: {{ $purchase->balance > 0 ? '#dc3545' : '#28a745' }};">
                                ₱{{ number_format($purchase->balance, 2) }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning btn-modern" data-bs-toggle="modal" data-bs-target="#editPurchaseModal{{ $purchase->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form id="delete-form-{{ $purchase->id }}" action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-modern" onclick="confirmDelete('delete-form-{{ $purchase->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @include('admin.purchase.editpurchase', ['purchase' => $purchase, 'suppliers' => $suppliers, 'products' => $products])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    @include('admin.purchase.addpurchase', ['suppliers' => $suppliers, 'products' => $products])
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function () {
    const table = $('#purchasesTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search purchases...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ purchases",
            paginate: { 
                previous: "<i class='bi bi-chevron-left'></i>", 
                next: "<i class='bi bi-chevron-right'></i>" 
            }
        },
        responsive: true,
        ordering: true,
        columnDefs: [{ orderable: false, targets: [4, 7] }],
        pageLength: 10,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    });

    // Supplier Filter
    $('#supplierFilter').on('change', function () {
        table.column(1).search(this.value).draw();
    });

    // Status Filters
    $('#filterPaid').on('click', () => table.column(5).search("Paid").draw());
    $('#filterPartial').on('click', () => table.column(5).search("Partial").draw());
    $('#filterUnpaid').on('click', () => table.column(5).search("Unpaid").draw());
    $('#resetFilter').on('click', () => {
        $('#supplierFilter').val('');
        table.search('').columns().search('').draw();
    });

    // Delete Confirmation
    window.confirmDelete = function(formId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This purchase will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#3b82f6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
});
</script>
@endpush