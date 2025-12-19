@extends('layouts.maincustomer')

@section('title', 'My Cart')

@section('content')
<style>
    body {
        position: relative;
        min-height: 100vh;
        background: linear-gradient(135deg, rgba(18, 50, 80, 1), rgba(26, 62, 99, 1));
        overflow-x: hidden;
        opacity: 1;
    }

    /* Blurred Background Image Overlay */
    body::before {
        content: "";
        position: fixed; /* ✅ fixed so it won’t move on scroll */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('/images/logo.png') center/cover no-repeat;
        opacity: 0.25; /* Adjust transparency */
        filter: blur(1px); /* Adjust blur intensity */
        z-index: -1; /* Stay behind content */
    }

    h4 {
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
</style>

<div id="cart-container">
    <div class="row">
        <!-- LEFT: Cart Items -->
        <div class="col-lg-8">
            <h4 class="fw-bold mb-3">My Cart ({{ $cart->count() }})</h4>

            @if($cart->count() > 0)
                @php $grandTotal = 0; @endphp

                @foreach($cart->groupBy('product.shop.name') as $shopName => $items)
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-body">
                            @foreach($items as $item)
                                @php 
                                    $total = $item->product->price * $item->quantity;
                                    $grandTotal += $total;
                                @endphp
                                <div class="row align-items-center border-bottom py-3" id="cart-item-{{ $item->id }}">
                                    <div class="col-2">
                                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/100' }}"
                                            class="img-fluid rounded border"
                                            style="width:80px; height:80px; object-fit:cover;">
                                    </div>
                                    <div class="col-4">
                                        <h6 class="fw-semibold mb-1">{{ $item->product->name }}</h6>
                                        <p class="text-muted small mb-0">₱{{ number_format($item->product->price, 2) }}</p>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary qty-minus" data-id="{{ $item->id }}">-</button>
                                            <input type="text" value="{{ $item->quantity }}" 
                                                class="form-control text-center qty-input" 
                                                data-id="{{ $item->id }}" style="max-width:50px;">
                                            <button class="btn btn-outline-secondary qty-plus" data-id="{{ $item->id }}">+</button>
                                        </div>
                                    </div>
                                    <div class="col-2 text-end fw-bold text-success row-total">
                                        ₱{{ number_format($total, 2) }}
                                    </div>
                                    <div class="col-1 text-center">
                                        <button class="btn btn-sm text-danger" onclick="removeFromCart({{ $item->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            @else
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="120" class="mb-3">
                    <h5 class="text-muted">Your cart is empty</h5>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary mt-3">
                        <i class="bi bi-shop"></i> Continue Shopping
                    </a>
                </div>
            @endif
        </div>

        <!-- RIGHT: Order Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-3 sticky-top" style="top:90px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal ({{ $cart->count() }} items)</span>
                        <span id="cart-subtotal">₱{{ number_format($grandTotal ?? 0, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold mb-3">
                        <span>Total</span>
                        <span class="text-success" id="cart-total">₱{{ number_format($grandTotal ?? 0, 2) }}</span>
                    </div>
                    <form action="{{ route('customercart.checkout') }}" method="POST">
                        <div class="mb-3">
                            <label class="fw-semibold">Choose Delivery Type:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                    name="delivery_type" id="pickupOption" value="pickup" checked>
                                <label class="form-check-label" for="pickupOption">Pickup</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                    name="delivery_type" id="deliveryOption" value="delivery">
                                <label class="form-check-label" for="deliveryOption">Delivery</label>
                            </div>
                        </div>

                         <div class="mb-3">
                            <label class="fw-semibold">Type of Request:</label>
                            <select name="type_request" class="form-select" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="cash">Cash</option>
                                <option value="purchase request">Purchase Request</option>
                            </select>
                        </div>
                                            @csrf
                        <button id="checkout-btn" class="btn btn-warning w-100 fw-bold py-2 rounded-pill">
                            PROCEED TO CHECKOUT ({{ $cart->count() }})
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Quantity + and -
    document.addEventListener("click", function(e){
        if(e.target.classList.contains("qty-plus") || e.target.classList.contains("qty-minus")){
            let id = e.target.dataset.id;
            let input = document.querySelector(`.qty-input[data-id="${id}"]`);
            let value = parseInt(input.value) || 1;

            if(e.target.classList.contains("qty-plus")){
                value++;
            } else if(e.target.classList.contains("qty-minus") && value > 1){
                value--;
            }
            input.value = value;
            updateCartQuantity(id, value);
        }
    });

    document.querySelectorAll(".qty-input").forEach(input => {
        input.addEventListener("change", function(){
            let id = this.dataset.id;
            let value = parseInt(this.value) || 1;
            if(value < 1) value = 1;
            this.value = value;
            updateCartQuantity(id, value);
        });
    });

    function updateCartQuantity(cartItemId, quantity){
        let url = "{{ route('customercart.update', ':id') }}".replace(':id', cartItemId);

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                let row = document.getElementById("cart-item-" + cartItemId);
                if(row){
                    row.querySelector(".row-total").textContent = "₱" + data.rowTotal.toFixed(2);
                }
                document.querySelector("#cart-subtotal").textContent = "₱" + data.subtotal.toFixed(2);
                document.querySelector("#cart-total").textContent = "₱" + data.subtotal.toFixed(2);
                document.querySelector("#checkout-btn").textContent = 
                    "PROCEED TO CHECKOUT (" + data.cartCount + ")";
                updateCartBadge(data.cartCount);
            }
        })
        .catch(err => console.error(err));
    }

    // Remove item dynamically
    function removeFromCart(cartItemId){
        let url = "{{ route('customercart.remove', ':id') }}".replace(':id', cartItemId);

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                // Remove row
                let row = document.getElementById('cart-item-' + cartItemId);
                if(row) row.remove();

                // Update subtotal & total
                document.querySelector("#cart-subtotal").textContent = "₱" + data.subtotal.toFixed(2);
                document.querySelector("#cart-total").textContent = "₱" + data.subtotal.toFixed(2);

                // Update checkout count
                document.querySelector("#checkout-btn").textContent = 
                    "PROCEED TO CHECKOUT (" + data.cartCount + ")";

                // Update badge
                updateCartBadge(data.cartCount);

                // If no items left
                if(data.cartCount === 0){
                    document.querySelector(".col-lg-8").innerHTML = `
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="120" class="mb-3">
                            <h5 class="text-muted">Your cart is empty</h5>
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary mt-3">
                                <i class="bi bi-shop"></i> Continue Shopping
                            </a>
                        </div>`;
                }
            }
        })
        .catch(error => console.error("Error:", error));
    }

    // Update cart badge
    function updateCartBadge(count){
        let badge = document.getElementById('cart-count');
        if(badge){
            if(count > 0){
                badge.style.display = "inline-flex";
                badge.textContent = count;
            } else {
                badge.style.display = "none";
            }
        }
    }

    // Select All functionality
    document.getElementById('select-all')?.addEventListener('change', function(){
        document.querySelectorAll('.item-checkbox, .shop-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Shop checkbox selects all items inside
    document.querySelectorAll('.shop-checkbox').forEach(shopCb => {
        shopCb.addEventListener('change', function(){
            let card = this.closest('.card');
            card.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });
</script>
@endpush
