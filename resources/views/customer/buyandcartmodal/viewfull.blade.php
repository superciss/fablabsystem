@foreach($orders as $order)
<div class="modal fade" id="viewFullModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold">Order #{{ $order->order_number }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="mb-3">
                    <p class="mb-1"><strong>Status:</strong> 
                        <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span>
                    </p>
                    <p class="mb-1"><strong>Type Request:</strong> {{ ucfirst($order->type_request ?? 'N/A') }}</p>
                    <p class="mb-1"><strong>Delivery Type:</strong> {{ ucfirst($order->delivery_type) }}</p>
                    <p class="mb-1"><strong>Estimate Date:</strong> 
                        {{ $order->estimate_date ? \Carbon\Carbon::parse($order->estimate_date)->format('M d, Y') : 'No Estimate Date' }}
                    </p>
                    <p class="mb-1"><strong>Payment:</strong> 
                        <span class="badge {{ $order->paid ? 'bg-success' : 'bg-danger' }}">
                            {{ $order->paid ? 'Paid' : 'Unpaid' }}
                        </span>
                    </p>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">Ordered Items</h6>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItem as $item)
                                <tr>
                                    <td>
                                        @if($item->product && $item->product->image)
                                            <img src="{{ $item->product->image }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                        @else
                                            <img src="/images/no-image.png" 
                                                 alt="No image" 
                                                 style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                        @endif
                                    </td>
                                    <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                                    <td>x{{ $item->quantity }}</td>
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                    <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <h5 class="fw-bold">Total: ₱{{ number_format($order->total_amount, 2) }}</h5>
                </div>
            </div>

            <div class="modal-footer">
                {{-- ✅ Receipt Logic --}}
                @if($order->type_request === 'purchase request')
                    @if(!$order->paid && !in_array($order->status, ['pending', 'cancelled']))
                        {{-- Purchase Receipt (Unpaid) --}}
                        <div class="d-flex gap-2 w-100">
                            <button type="button" 
                                    class="btn btn-sm btn-info flex-fill"
                                    onclick='viewReceipt(@json($order), "purchase")'>
                                <i class="bi bi-eye"></i> View Purchase Receipt
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-warning flex-fill"
                                    onclick='downloadReceipt(@json($order), "purchase")'>
                                <i class="bi bi-file-earmark-arrow-down"></i> Download Purchase Receipt
                            </button>
                        </div>
                    @elseif($order->paid && !in_array($order->status, ['pending', 'cancelled']))
                        {{-- Official Receipt (Paid Purchase Request) --}}
                        <div class="d-flex gap-2 w-100">
                            <button type="button" 
                                    class="btn btn-sm btn-info flex-fill"
                                    onclick='viewReceipt(@json($order), "official")'>
                                <i class="bi bi-eye"></i> View Official Receipt
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-primary flex-fill"
                                    onclick='downloadReceipt(@json($order), "official")'>
                                <i class="bi bi-receipt"></i> Download Official Receipt
                            </button>
                        </div>
                    @endif

                @elseif($order->type_request === 'cash')
                     @if(!in_array($order->status, ['pending', 'cancelled']))
                        {{-- Official Receipt (Cash Transaction) --}}
                        <div class="d-flex gap-2 w-100">
                            <button type="button" 
                                    class="btn btn-sm btn-info flex-fill"
                                    onclick='viewReceipt(@json($order), "official")'>
                                <i class="bi bi-eye"></i> View Official Receipt
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-primary flex-fill"
                                    onclick='downloadReceipt(@json($order), "official")'>
                                <i class="bi bi-receipt"></i> Download Official Receipt
                            </button>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</div>
@endforeach
