@extends('layouts.main')

@section('title', 'Order Items Management')

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

    .text-primary { background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-success { background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-warning { background: linear-gradient(to right, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

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
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1 btn-modern">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="dashboard-title">Order Items</h2>
                <p class="dashboard-subtitle">Manage products inside orders</p>
            </div>
        </div>
        <button class="btn btn-primary btn-modern d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addOrderItemModal">
            <i class="bi bi-plus-circle"></i> Add Order Item
        </button>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Items</div>
                        <h4 class="stat-value text-primary">{{ $orderItems->count() }}</h4>
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
                        <div class="card-header">Total Quantity</div>
                        <h4 class="stat-value text-success">{{ $orderItems->sum('quantity') }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="bi bi-cart-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Sales Value</div>
                        <h4 class="stat-value text-warning">₱{{ number_format($orderItems->sum(fn($i) => $i->quantity * $i->price), 2) }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Table -->
    <div class="modern-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Order Items List</h5>
            <div class="d-flex gap-3">
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary btn-modern" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="orderItemsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderItems as $item)
                        <tr>
                            <td class="fw-semibold">#{{ $item->id }}</td>
                               <td class="text-muted">#{{ $item->order->id ?? 'N/A' }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="fw-semibold">{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->price, 2) }}</td>
                            <td class="text-success fw-bold">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editOrderItemModal{{ $item->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                 <form id="delete-form-{{ $item->id }}" action="{{ route('orderitems.destroy', $item->id) }}"  method="POST" class="d-inline-block">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-xs d-flex align-items-center gap-1 py-1" 
                                                onclick="confirmDelete('delete-form-{{ $item->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        @include('admin.orderitem.editorderitem', ['item' => $item, 'orders' => $orders, 'products' => $products])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
@include('admin.orderitem.addorderitem', ['orders' => $orders, 'products' => $products])
@endsection

@push('scripts')
<script>
$(function () {
    const table = $('#orderItemsTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search order items...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ order items",
            paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
        },
        responsive: true,
        ordering: true,
        columnDefs: [{ orderable: false, targets: [6] }]
    });

    // Reset filter
    $('#resetFilter').on('click', () => table.search('').columns().search('').draw());
});
</script>
@endpush
