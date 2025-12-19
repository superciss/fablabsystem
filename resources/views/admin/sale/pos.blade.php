@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- LEFT SIDE: Dynamic POS/Online Orders --}}
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5>Point of Sale</h5>
                    <div>
                        <button class="btn btn-light btn-sm" type="button" id="directOrderBtn">Direct Order</button>
                        <button class="btn btn-light btn-sm" type="button" id="onlineOrderBtn">Online Orders</button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Direct Order Form --}}
                    <div id="directOrderForm">
                            <form action="{{ route('pos.store') }}" method="POST">
                                @csrf
                                <table class="table table-bordered" id="posTable">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->name }} stock: {{ $product->stock }}</td>
                                            <td>₱<span class="price">{{ number_format($product->price,2) }}</span></td>
                                            <td>
                                                <input type="number" name="items[{{ $product->id }}][quantity]" min="0" value="0" class="form-control qty" data-price="{{ $product->price }}">
                                                <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                                            </td>
                                            <td>₱<span class="subtotal">0.00</span></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mb-3">
                                    <label>Total:</label>
                                    <input type="text" id="totalAmount" class="form-control" value="₱0.00" readonly>
                                </div>

                                <div class="mb-3">
                                    <label>Cash:</label>
                                    <input type="number" id="cashInput" name="cash" step="0.01" value="0" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Change:</label>
                                    <input type="text" id="changeOutput" class="form-control" value="₱0.00" readonly>
                                </div>

                                <button type="submit" class="btn btn-success">Pay</button>
                            </form>
                        </div>


                  {{-- Online Orders Form --}}
                        <div id="onlineOrderForm" style="display:none;">
                            @php
                                // Only show orders with items and pending payment (change_due < 0)
                                $onlineOrders = $completedOrders->filter(fn($order) => 
                                    $order->orderitem->count() > 0 && (($order->amount_paid ?? 0) - $order->orderitem->sum(fn($item) => $item->quantity * $item->price)) < 0
                                );
                            @endphp

                            @if($onlineOrders->count() > 0)
                            <form action="{{ route('pos.onlinepay') }}" method="POST">
                                @csrf
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Products</th>
                                            <th>Order Date</th>
                                            <th>Total</th>
                                            <th>Cash</th>
                                            <th>Change</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($onlineOrders as $order)
                                        @php
                                            $total = $order->orderitem->sum(fn($item) => $item->quantity * $item->price);
                                            $paid = $order->amount_paid ?? 0;
                                            $change = $paid - $total;
                                            if ($change >= 0) continue; // Skip fully paid orders
                                        @endphp
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                            <td>
                                                <ul class="mb-0">
                                                    @foreach($order->orderitem as $item)
                                                        <li>{{ $item->product->name ?? 'Product Deleted' }} x {{ $item->quantity }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>{{ $order->created_at->timezone('Asia/Manila')->format('M d, Y H:i') }}</td>
                                            <td>₱{{ number_format($total,2) }}</td>
                                            <td>
                                                <input type="number" name="cash[{{ $order->id }}]" step="0.01" value="{{ $paid }}" class="form-control onlineCash" data-total="{{ $total }}">
                                            </td>
                                            <td class="change">₱{{ number_format(max(0, $change),2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary"> Payments</button>
                            </form>
                            @else
                            <p>No online orders pending payment.</p>
                            @endif
                        </div>


                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: Completed Receipts --}}
            
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5>Completed Payments</h5>
                </div>
                <div class="card-body">
                    @php
                        $paidOrders = $completedOrders->filter(fn($order) => ($order->amount_paid ?? 0) > 0);
                    @endphp

                    @if($paidOrders->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Change</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paidOrders as $order)
                                @php
                                    $total = $order->orderitem->sum(fn($item) => $item->quantity * $item->price);
                                    $paid = $order->amount_paid ?? 0;
                                    $change = $order->change_due ?? ($paid - $total);
                                @endphp
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                    <td>₱{{ number_format($total,2) }}</td>
                                    <td>₱{{ number_format($paid,2) }}</td>
                                    <td>₱{{ number_format($change,2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No completed payments yet.</p>
                    @endif
                </div>
            </div>
        </div>  
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const directBtn = document.getElementById('directOrderBtn');
    const onlineBtn = document.getElementById('onlineOrderBtn');
    const directForm = document.getElementById('directOrderForm');
    const onlineForm = document.getElementById('onlineOrderForm');

    // Function to show/hide forms
    function showForm(form) {
        if (form === 'direct') {
            directForm.style.display = 'block';
            onlineForm.style.display = 'none';
        } else {
            directForm.style.display = 'none';
            onlineForm.style.display = 'block';
        }
        // Save the last selected form in localStorage
        localStorage.setItem('posActiveForm', form);
    }

    // Load last selected form from localStorage
    const lastForm = localStorage.getItem('posActiveForm') || 'direct';
    showForm(lastForm);

    // Toggle forms on button click
    directBtn.addEventListener('click', () => showForm('direct'));
    onlineBtn.addEventListener('click', () => showForm('online'));

    // Instant change calculation for online orders
    document.querySelectorAll('.onlineCash').forEach(input => {
        // Clear 0.00 when focused
        input.addEventListener('focus', function() {
            if (parseFloat(this.value) === 0) {
                this.value = '';
            }
        });

        // Restore 0 if left empty on blur
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.value = '0.00';
            }
        });

        // Update change instantly
        input.addEventListener('input', function() {
            const total = parseFloat(this.dataset.total) || 0;
            const paid = parseFloat(this.value) || 0;
            const change = paid - total;
            this.closest('tr').querySelector('.change').textContent = `₱${change.toFixed(2)}`;
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.qty');
    const totalAmount = document.getElementById('totalAmount');
    const cashInput = document.getElementById('cashInput');
    const changeOutput = document.getElementById('changeOutput');

    function calculateTotals() {
        let total = 0;
        qtyInputs.forEach(input => {
            const qty = parseFloat(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            const subtotal = qty * price;
            input.closest('tr').querySelector('.subtotal').textContent = subtotal.toFixed(2);
            total += subtotal;
        });
        totalAmount.value = `₱${total.toFixed(2)}`;
        calculateChange();
    }

    function calculateChange() {
        const total = parseFloat(totalAmount.value.replace('₱','')) || 0;
        const cash = parseFloat(cashInput.value) || 0;
        const change = cash - total;
        changeOutput.value = `₱${change.toFixed(2)}`;
    }

    // Auto-clear qty inputs when focused if 0
    qtyInputs.forEach(input => {
        input.addEventListener('focus', function() {
            if (parseFloat(this.value) === 0) this.value = '';
        });
        input.addEventListener('blur', function() {
            if (this.value === '') this.value = '0';
            calculateTotals();
        });
        input.addEventListener('input', calculateTotals);
    });

    // Auto-clear cash input when focused if 0
    cashInput.addEventListener('focus', function() {
        if (parseFloat(this.value) === 0) this.value = '';
    });
    cashInput.addEventListener('blur', function() {
        if (this.value === '') this.value = '0.00';
        calculateChange();
    });
    cashInput.addEventListener('input', calculateChange);
});


</script>


@endpush
