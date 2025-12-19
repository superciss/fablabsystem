@extends('layouts.main')

@section('title', 'Orders Management')

@section('content')
<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1">
                <i class="bi bi-arrow-left fs-5"></i> 
            </a>
            <div>
                <h2 class="mb-0 text-dark">Orders Management</h2>
                <p class="text-muted mb-0">Manage customer orders and track their status</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                <i class="bi bi-plus-lg"></i> Add New Order
            </button>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Orders</p>
                        <h4 class="mb-0">{{ $orders->count() }}</h4>
                    </div>
                    <i class="bi bi-bag-check display-6 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pending</p>
                        <h4 class="mb-0">{{ $orders->where('status','pending')->count() }}</h4>
                    </div>
                    <i class="bi bi-hourglass-split display-6 text-warning"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Completed</p>
                        <h4 class="mb-0">{{ $orders->where('status','completed')->count() }}</h4>
                    </div>
                    <i class="bi bi-check2-circle display-6 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Cancelled</p>
                        <h4 class="mb-0">{{ $orders->where('status','cancelled')->count() }}</h4>
                    </div>
                    <i class="bi bi-x-circle display-6 text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-gray py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Orders Directory</h5>
            <div class="d-flex gap-2" id="filterButtons">
                <button id="filterPending" class="btn btn-sm btn-outline-warning shadow-sm"><i class="bi bi-hourglass-split"></i></button>
                <button id="filterProcessing" class="btn btn-sm btn-outline-info shadow-sm"><i class="bi bi-arrow-repeat"></i></button>
                <button id="filterCompleted" class="btn btn-sm btn-outline-success shadow-sm"><i class="bi bi-check2-circle"></i></button>
                <button id="filterCancelled" class="btn btn-sm btn-outline-danger shadow-sm"><i class="bi bi-x-circle"></i></button>
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="bi bi-arrow-counterclockwise"></i></button>
                
                <!-- Bulk Actions -->
                <div class="ms-2">
                    <select id="bulkActionSelect" class="form-select form-select-sm">
                        <option value="">Bulk Actions</option>
                        <option value="pending">Set Pending</option>
                        <option value="processing">Set Processing</option>
                        <option value="completed">Set Completed</option>
                        <option value="cancelled">Set Cancelled</option>
                        <option value="approved">Set Approved</option>
                        <option value="not approve">Set Not Approved</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                </div>

                <div class="d-flex align-items-center ms-3">
                    <input type="date" id="filterDate" class="form-control form-control-sm" />
                </div>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="selectAll" /></th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Delivery</th>
                            <th>Status</th>
                            <th>Approval</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><input type="checkbox" class="orderCheckbox" value="{{ $order->id }}"></td>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>
                                @if($order->delivery_type === 'pickup')
                                    <span class="badge bg-info">Pickup</span>
                                @else
                                    <span class="badge bg-secondary">Delivery</span>
                                @endif
                            </td>
                            <td>
                                @switch($order->status)
                                    @case('pending') <span class="badge bg-warning text-dark">Pending</span> @break
                                    @case('processing') <span class="badge bg-info">Processing</span> @break
                                    @case('completed') <span class="badge bg-success">Completed</span> @break
                                    @case('cancelled') <span class="badge bg-danger">Cancelled</span> @break
                                    @default <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                @if($order->approve_by_admin === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Not Approved</span>
                                @endif
                            </td>
                            <td class="text-success fw-bold">₱{{ number_format($order->total_amount,2) }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($order->orderitem as $item)
                                        <li>{{ $item->product?->name ?? 'N/A' }} <span class="text-muted">(x{{ $item->quantity }})</span></li>
                                    @endforeach
                                </ul>
                            </td>
                             <td class="order-date" data-order="{{ $order->created_at->toDateString() }}">
                                {{ $order->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline-block">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this order?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @include('admin.order.editorder', ['order'=>$order,'users'=>$users,'products'=>$products])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.order.addorder', ['users'=>$users,'products'=>$products])
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

    // ✅ Select all checkbox
    $('#selectAll').on('change', function () {
        $('.orderCheckbox').prop('checked', $(this).prop('checked'));
    });

    // ✅ Get selected order IDs
    function getSelectedOrders() {
        return $('.orderCheckbox:checked').map(function () {
            return $(this).val();
        }).get();
    }

    // ✅ Handle Bulk Action Select
    $('#bulkActionSelect').on('change', function () {
        const action = $(this).val();
        if (!action) return;

        const ids = getSelectedOrders();
        if (ids.length === 0) {
            alert("Please select at least one order.");
            $(this).val('');
            return;
        }

        if (action === "delete") {
            if (!confirm("Delete " + ids.length + " orders?")) {
                $(this).val('');
                return;
            }
            $.post("{{ route('orders.bulkDelete') }}", {
                _token: "{{ csrf_token() }}",
                ids: ids
            }, function () {
                location.reload();
            });
        } 
        else if (action === "approved" || action === "not approve") {
            if (!confirm("Update approval for " + ids.length + " orders?")) {
                $(this).val('');
                return;
            }
            $.post("{{ route('orders.bulkApprove') }}", {
                _token: "{{ csrf_token() }}",
                ids: ids,
                approve_by_admin: action
            }, function () {
                location.reload();
            });
        }
        else {
            if (!confirm("Update " + ids.length + " orders to " + action + "?")) {
                $(this).val('');
                return;
            }
            $.post("{{ route('orders.bulkUpdate') }}", {
                _token: "{{ csrf_token() }}",
                ids: ids,
                status: action
            }, function () {
                location.reload();
            });
        }

        $(this).val(''); // reset after action
    });

    // ✅ Date filter
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let filterDate = $('#filterDate').val();
        if (!filterDate) return true;
        const cell = table.cell(dataIndex, 8).node();
        const iso = cell ? cell.getAttribute('data-order') : '';
        return iso === filterDate;
    });

    $('#filterDate').on('change', () => table.draw());

    // ✅ Status filter buttons
    $('#filterPending').on('click', () => table.column(4).search("Pending").draw());
    $('#filterProcessing').on('click', () => table.column(4).search("Processing").draw());
    $('#filterCompleted').on('click', () => table.column(4).search("Completed").draw());
    $('#filterCancelled').on('click', () => table.column(4).search("Cancelled").draw());

    // ✅ Reset filter
    $('#resetFilter').on('click', function () {
        $('#filterDate').val('');
        table.search('').columns().search('').draw();
    });
});
</script>
@endpush
