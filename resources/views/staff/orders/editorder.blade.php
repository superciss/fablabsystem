<div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('stafforders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Edit Order {{ $order->order_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Delivery Type</label>
                        <select name="delivery_type" class="form-control" required>
                            <option value="pickup" {{ $order->delivery_type == 'pickup' ? 'selected' : '' }}>Pickup</option>
                            <option value="delivery" {{ $order->delivery_type == 'delivery' ? 'selected' : '' }}>Delivery</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Cash Method</label>
                        <select name="type_request" class="form-control" required>
                            <option value="cash" {{ $order->type_request == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="purchase request" {{ $order->type_request == 'purchase request' ? 'selected' : '' }}>Purchase Request</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Estimated Date</label>
                        <input type="date" name="estimate_date" class="form-control"
                            value="{{ $order->estimate_date ? \Carbon\Carbon::parse($order->estimate_date)->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning text-white">Update Order</button>
                </div>
            </div>
        </form>
    </div>
</div>
