@extends('layouts.main')

@section('title', 'Staff Orders')

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
    .bg-info { background: linear-gradient(135deg, #06b6d4, #22d3ee); color: #ffffff; }

    .text-primary { background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-success { background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-warning { background: linear-gradient(to right, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-danger { background: linear-gradient(to right, #ef4444, #f87171); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-info { background: linear-gradient(to right, #06b6d4, #22d3ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

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
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table tbody td {
        padding: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
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
    .badge-secondary { background: #e2e8f0; color: #64748b; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }

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
    .btn-outline-info { border-color: #06b6d4; color: #06b6d4; }
    .btn-outline-info:hover { background: #cffafe; }

    /* Form select styling */
    .form-select-modern {
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .form-select-modern:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-select-modern:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }

    /* Input styling */
    .form-control-modern {
        border-radius: 8px;
        font-size: 0.875rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .form-control-modern:hover {
        border-color: #3b82f6;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
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
        .form-select-modern, .form-control-modern {
            font-size: 0.8rem;
            padding: 0.4rem;
        }
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1 btn-modern">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="dashboard-title">Orders</h2>
                <p class="dashboard-subtitle">Manage customer orders and track their status</p>
            </div>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-primary btn-modern d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                <i class="bi bi-plus-lg"></i> Add New Order
            </button>

              <a href="{{ route('admin.history.index') }}" class="btn btn-primary btn-modern d-flex align-items-center gap-2" >
                <i class="bi bi-plus-lg"></i> Order History
              </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Orders</div>
                        <h4 class="stat-value text-primary">{{ $orders->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-primary">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Pending</div>
                        <h4 class="stat-value text-warning">{{ $orders->where('status', 'pending')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Completed</div>
                        <h4 class="stat-value text-success">{{ $orders->where('status', 'completed')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Cancelled</div>
                        <h4 class="stat-value text-danger">{{ $orders->where('status', 'cancelled')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-danger">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="modern-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Orders Directory</h5>
            <div class="d-flex gap-3" id="filterButtons">
                <button id="filterPending" class="btn btn-sm btn-outline-warning btn-modern" title="Filter Pending">
                    <i class="bi bi-hourglass-split"></i>
                </button>
                <button id="filterProcessing" class="btn btn-sm btn-outline-info btn-modern" title="Filter Processing">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
                <button id="filterCompleted" class="btn btn-sm btn-outline-success btn-modern" title="Filter Completed">
                    <i class="bi bi-check2-circle"></i>
                </button>
                <button id="filterCancelled" class="btn btn-sm btn-outline-danger btn-modern" title="Filter Cancelled">
                    <i class="bi bi-x-circle"></i>
                </button>
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary btn-modern" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
                <select id="bulkActionSelect" class="form-select form-select-sm form-select-modern">
                    <option value="">Bulk Actions</option>
                    <option value="pending">Set Pending</option>
                    <option value="processing">Set Processing</option>
                    <option value="completed">Set Completed</option>
                    <option value="approved">Set Approved</option>
                    <option value="paid">Set Paid</option>
                    <option value="cancelled">Set Cancelled</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <input type="date" id="filterDate" class="form-control form-control-sm form-control-modern" />
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="ordersTable">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" /></th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Delivery</th>
                            <th>Status</th>
                            <th>Delivery Status</th>
                            <th>Approval</th>
                            <th>Total</th>
                            <th>Payment Status</th>
                            <th>Items</th>
                            <th>Payment Method</th>
                            <th>Estimated Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <input type="checkbox" class="orderCheckbox" value="{{ $order->id }}">
                            </td>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                           <td>
                                @if($order->delivery_type === 'pickup')
                                    <span class="badge badge-info">Pickup</span>
                                @elseif($order->delivery_type === 'delivery')
                                    <span class="badge badge-secondary">Delivery</span>
                                @else
                                    <span class="badge badge-light">Unknown</span>
                                @endif
                            </td>

                            <td>
                                @switch($order->status)
                                    @case('pending') <span class="badge badge-warning">Pending</span> @break
                                    @case('processing') <span class="badge badge-info">Processing</span> @break
                                    @case('completed') <span class="badge badge-success">Completed</span> @break
                                    @case('cancelled') <span class="badge badge-danger">Cancelled</span> @break
                                    @default <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>

                              <td>
                                @if($order->delivery_status === 'for_pickup')
                                    <span class="badge badge-info">Pickup</span>
                                @elseif($order->delivery_status === 'for_delivery')
                                    <span class="badge badge-secondary">Delivery</span>
                                @elseif($order->delivery_status === 'is_ongoing')
                                    <span class="badge badge-warning">Ongoing</span>
                                @elseif($order->delivery_status === 'is_upcoming')
                                    <span class="badge badge-primary">Upcoming</span>
                                @else
                                    <span class="badge badge-light">Unknown</span>
                                @endif
                            </td>
                            
                            <td>
                                @if($order->approve_by_admin === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Not Approved</span>
                                @endif
                            </td>
                            <td class="text-success fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                           <td>
                                @if($order->paid)
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="badge badge-danger">Unpaid</span>
                                @endif
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($order->orderitem as $item)
                                        <li>{{ $item->product?->name ?? 'N/A' }} <span class="text-muted">(x{{ $item->quantity }})</span></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($order->type_request === 'cash')
                                    <span class="badge bg-info text-dark">Cash</span>
                                @else
                                    <span class="badge bg-secondary">Purchase Request</span>
                                @endif
                            </td>
                            <td>
                                {{ $order->estimate_date ? \Carbon\Carbon::parse($order->estimate_date)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning btn-modern" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form id="delete-form-{{ $order->id }}" action="{{ route('adminorder.destroy', $order->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-modern" onclick="confirmDelete('delete-form-{{ $order->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @include('admin.order.editorder', ['order' => $order, 'products' => $products])
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted" style="padding: 2rem;">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.order.addorder', ['products' => $products])
@endsection

@push('scripts')
<script>
$(function () {
    const table = $('#ordersTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search orders...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
        },
        responsive: true,
        ordering: true,
        columnDefs: [{ orderable: false, targets: [0, 7, 9] }]
    });

    // Select all checkbox
    $('#selectAll').on('change', function () {
        $('.orderCheckbox').prop('checked', $(this).prop('checked'));
    });

    // Get selected order IDs
    function getSelectedOrders() {
        return $('.orderCheckbox:checked').map(function () {
            return $(this).val();
        }).get();
    }

    // Handle Bulk Action Select with SweetAlert
    $('#bulkActionSelect').on('change', function () {
        const action = $(this).val();
        if (!action) return;

        const ids = getSelectedOrders();
        if (ids.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Orders Selected',
                text: 'Please select at least one order.',
                timer: 2000,
                showConfirmButton: false
            });
            $(this).val('');
            return;
        }

        let title = '';
        let text = '';

        if (action === "delete") {
            title = 'Delete Orders?';
            text = `Are you sure you want to delete ${ids.length} orders?`;
        } else if (action === "approved") {
            title = 'Update Approval';
            text = `Update approval for ${ids.length} orders to Approved?`;
        } else if (action === "paid") {   // 🔹 new condition
            title = 'Mark Orders as Paid?';
            text = `Are you sure you want to mark ${ids.length} orders as Paid?`;
        } else {
            title = 'Update Orders';
            text = `Update ${ids.length} orders to "${action}"?`;
        }


        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let route = '';
                let data = {
                    _token: "{{ csrf_token() }}",
                    ids: ids
                };

                if (action === "delete") {
                    route = "{{ route('adminorder.bulkDelete') }}";
                } else if (action === "approved") {
                    route = "{{ route('adminorder.bulkApprove') }}";
                    data.approve_by_admin = action;
                } else if (action === "paid") {  // 🔹 new case
                    route = "{{ route('adminorder.bulkPaid') }}";
                    data.paid = 1; // send flag to backend
                } else {
                    route = "{{ route('adminorder.bulkUpdate') }}";
                    data.status = action;
                }


                $.post(route, data, function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Action completed successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                });
            } else {
                $(this).val('');
            }
        });
    });

    // Date filter
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let filterDate = $('#filterDate').val();
        if (!filterDate) return true;
        const cell = table.cell(dataIndex, 8).node();
        const iso = cell ? cell.getAttribute('data-order') : '';
        return iso === filterDate;
    });

    $('#filterDate').on('change', () => table.draw());

    // Status filter buttons
    $('#filterPending').on('click', () => table.column(4).search("Pending").draw());
    $('#filterProcessing').on('click', () => table.column(4).search("Processing").draw());
    $('#filterCompleted').on('click', () => table.column(4).search("Completed").draw());
    $('#filterCancelled').on('click', () => table.column(4).search("Cancelled").draw());

    // Reset filter
    $('#resetFilter').on('click', function () {
        $('#filterDate').val('');
        table.search('').columns().search('').draw();
    });

    // Confirm delete function
    window.confirmDelete = function(formId) {
        if (confirm('Are you sure you want to delete this order?')) {
            document.getElementById(formId).submit();
        }
    };
});
</script>
@endpush