@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-bag-check-fill me-2"></i> Completed Orders</h4>
            <div class="d-flex gap-2" id="filterButtons">
                <button id="filterSent" class="btn btn-sm btn-outline-success shadow-sm">
                    <i class="bi bi-check-circle-fill"></i> Sent
                </button>
                <button id="filterUnsent" class="btn btn-sm btn-outline-warning shadow-sm">
                    <i class="bi bi-send"></i> Unsent
                </button>
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </button>

                <!-- Bulk Actions -->
                <div class="ms-2">
                    <select id="bulkActionSelect" class="form-select form-select-sm">
                        <option value="">Bulk Actions</option>
                        <option value="send">Bulk Send</option>
                        <option value="delete">Bulk Delete</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="d-flex align-items-center ms-3">
                    <input type="date" id="filterDate" class="form-control form-control-sm" />
                </div>
            </div>
        </div>
        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div id="bulkActionAlert" class="alert alert-info d-none">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span id="bulkActionMessage">Processing bulk action...</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="completedOrdersTable">
                    <thead class="table-primary">
                        <tr>
                            <th><input type="checkbox" id="selectAll" /></th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Products</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th class="text-center">Send Notification</th>
                            <th class="text-center">Send Sms</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $latestNotification = $order->notification()->latest()->first();
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" class="orderCheckbox" value="{{ $order->id }}">
                                </td>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    @foreach($order->orderitem as $item)
                                        <span class="badge bg-info text-dark mb-1">{{ $item->product->name }}</span>
                                    @endforeach
                                </td>
                                <td><span class="fw-bold text-success">₱{{ number_format($order->total_amount, 2) }}</span></td>
                                <td class="order-date" data-order="{{ $order->updated_at->toDateString() }}">
                                    <span class="text-muted">
                                        {{ $order->updated_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                                    </span>
                                </td>
                                <td>
                                    @if(!$latestNotification)
                                        <span class="badge bg-secondary">No Message</span>
                                    @elseif($latestNotification->is_read)
                                        <span class="badge bg-success">Seen</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Unseen</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('notify.send', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                            class="btn btn-sm @if($latestNotification) btn-outline-secondary @else btn-success @endif px-3"
                                            @if($latestNotification) disabled @endif>
                                            @if($latestNotification)
                                                <i class="bi bi-check-circle-fill me-1"></i> Sent
                                            @else
                                                <i class="bi bi-send-fill me-1"></i> Send
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                 <td>
                                    <form action="{{ route('notify.sendSms', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            Send SMS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No completed orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection  

@push('scripts')
<script>
$(function () {
    const table = $('#completedOrdersTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search orders...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ orders",
            paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
        },
        responsive: true,
        ordering: true,
        columnDefs: [{ orderable: false, targets: [0,7] }],
        drawCallback: function() {
            // Update select all checkbox on page change
            // $('#selectAll').prop('checked', false);
        }
    });

    // ✅ Select All (works across pagination)
    // $('#selectAll').on('change', function () {
    //     const isChecked = $(this).prop('checked');
    //     table.$('.orderCheckbox').prop('checked', isChecked);
    // });

    // ✅ Get Selected
    function getSelectedOrders() {
        return table.$('.orderCheckbox:checked').map(function () {
            return $(this).val();
        }).get();
    }

    // ✅ Show/hide bulk action alert
    function toggleBulkActionAlert(show, message = '') {
        const alert = $('#bulkActionAlert');
        if (show) {
            $('#bulkActionMessage').text(message);
            alert.removeClass('d-none');
        } else {
            alert.addClass('d-none');
        }
    }

    // ✅ Bulk Actions
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
            if (!confirm("Are you sure you want to delete notifications for " + ids.length + " order(s)?")) {
                $(this).val('');
                return;
            }
            
            toggleBulkActionAlert(true, 'Deleting notifications...');
            
            $.post("{{ route('notify.bulkDelete') }}", {
                _token: "{{ csrf_token() }}",
                ids: ids
            })
            .done(function(response) {
                if (response.success) {
                    // Reload after a brief delay to show the success message
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            })
            .fail(function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.error || 'Request failed'));
                toggleBulkActionAlert(false);
            });
            
        } else if (action === "send") {
            if (!confirm("Send notifications for " + ids.length + " order(s)?")) {
                $(this).val('');
                return;
            }
            
            toggleBulkActionAlert(true, 'Sending notifications...');
            
            $.post("{{ route('notify.bulkSend') }}", {
                _token: "{{ csrf_token() }}",
                ids: ids
            })
            .done(function(response) {
                if (response.success) {
                    // Reload after a brief delay to show the success message
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            })
            .fail(function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.error || 'Request failed'));
                toggleBulkActionAlert(false);
            });
        }

        $(this).val('');
    });

    // ✅ Date Filter
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        let filterDate = $('#filterDate').val();
        if (!filterDate) return true;
        const cell = table.cell(dataIndex, 5).node();
        const iso = cell ? cell.getAttribute('data-order') : '';
        return iso === filterDate;
    });

    $('#filterDate').on('change', () => table.draw());

    // ✅ Status Filters
    $('#filterSent').on('click', () => {
        table.column(6).search("Seen|Unseen", true, false).draw();
        $('#filterDate').val('');
    });
    
    $('#filterUnsent').on('click', () => {
        table.column(6).search("No Message").draw();
        $('#filterDate').val('');
    });

    // ✅ Reset
    $('#resetFilter').on('click', function () {
        $('#filterDate').val('');
        table.search('').columns().search('').draw();
    });
});
</script>
@endpush