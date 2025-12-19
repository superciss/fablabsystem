@extends('layouts.main')

@section('content')
<div class="container-fluid px-4">

   <div class="d-flex align-items-center gap-3 mb-4 mt-2">
        <a href="{{ route('admin.order.index') }}" 
           class="btn btn-outline-secondary rounded-circle p-2 lh-1 btn-modern d-flex align-items-center justify-content-center"
           style="width: 40px; height: 40px;">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h2 class="dashboard-title mb-0">Order History</h2>
            <p class="dashboard-subtitle mb-0">All completed, cancelled, and paid orders</p>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>Delivery</th>
                            <th>Approval</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold">#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>

                            <td>
                                @switch($order->status)
                                    @case('pending') <span class="badge bg-warning">Pending</span> @break
                                    @case('processing') <span class="badge bg-info">Processing</span> @break
                                    @case('completed') <span class="badge bg-success">Completed</span> @break
                                    @case('cancelled') <span class="badge bg-danger">Cancelled</span> @break
                                    @default <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                @endswitch
                            </td>

                            <td>
                                @if($order->paid)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>

                            <td class="text-success fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>

                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($order->orderitem as $item)
                                        <li>{{ $item->product?->name }} <span class="text-muted">(x{{ $item->quantity }})</span></li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>
                                @if($order->delivery_type === 'pickup')
                                    <span class="badge bg-info">Pickup</span>
                                @else
                                    <span class="badge bg-secondary">Delivery</span>
                                @endif
                            </td>

                            <td>
                                @if($order->approve_by_admin === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Not Approved</span>
                                @endif
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y h:i A') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                No order history found.
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
