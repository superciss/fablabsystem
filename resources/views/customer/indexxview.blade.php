@extends('layouts.maincustomer')

@section('title', 'Shop')

@section('content')
<div class="container mt-5">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    <div class="row g-2">
        @foreach($products as $product)
        <div class="col-6 col-md-3">
            <div class="card h-100 shadow-sm border-0 rounded-4 product-card">
                
              
                <div class="product-img-wrapper position-relative">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}" 
                         class="card-img-top product-img" alt="{{ $product->name }}">
                    
                  
                    <span class="badge stock-badge position-absolute top-0 start-0 m-2">
                        {{ $product->stock }} pcs
                    </span>

                  
                </div>

               
                <div class="card-body d-flex flex-column">
                   
                    <span class="store-badge mb-1"></span>

                   
                    <h6 class="fw-bold text-dark text-truncate">{{ $product->name }}</h6>
                    <p class="card-text text-muted small mb-1 text-truncate">
                        {{ Str::limit($product->description, 40) }}
                    </p>

                   
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="text-danger fw-bold fs-6">₱{{ number_format($product->price, 2) }}</span>
                        <!-- <small class="text-muted text-decoration-line-through">
                            ₱{{ number_format($product->price + 100, 2) }}
                        </small> -->
                    </div>

                  

                    <div class="d-flex gap-2 mt-auto">
                        <button class="btn btn-buy w-50" data-bs-toggle="modal" data-bs-target="#buyNowModal{{ $product->id }}">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Buy
                        </button>
                        <button class="btn btn-add w-50" data-bs-toggle="modal" data-bs-target="#addCartModal{{ $product->id }}">
                            <i class="bi bi-cart-plus me-1"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @include('customer.buyandcartmodal.cart', ['product' => $product])
        @include('customer.buyandcartmodal.buy', ['product' => $product])
        @endforeach
    </div>
</div>


<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="cartToast" class="toast align-items-center custom-toast border-0 shadow-lg" role="alert">
    <div class="d-flex">
      <div class="toast-body">
        🛒 Product added to cart!
      </div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
function incrementQty(id, type = 'cart'){
    let input = document.getElementById(type+'Qty'+id);
    let max = parseInt(input.max);
    if(parseInt(input.value) < max){
        input.value = parseInt(input.value) + 1;
        updatePrice(id, type);
    }
}

function decrementQty(id, type = 'cart'){
    let input = document.getElementById(type+'Qty'+id);
    if(input.value > 1){
        input.value = parseInt(input.value) - 1;
        updatePrice(id, type);
    }
}

function updatePrice(id, type = 'cart'){
    let input = document.getElementById(type+'Qty'+id);
    let qty = parseInt(input.value);
    let price = parseFloat(input.dataset.price);
    let total = qty * price;
    document.getElementById(type+'TotalPrice'+id).innerText = total.toFixed(2);
}

function addToCart(productId){
    let form = document.getElementById('add-to-cart-form-' + productId);
    let formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success){
            let modalEl = document.getElementById('addCartModal' + productId);
            let modal = bootstrap.Modal.getInstance(modalEl);
            if(modal) modal.hide();

            updateCartBadge(data.cartCount);

            let toastEl = document.getElementById("cartToast");
            let toast = new bootstrap.Toast(toastEl, { delay: 2000 });
            toast.show();
        }
    })
    .catch(error => console.error('Error:', error));
}

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
</script>
@endpush

@push('styles')
<style>

body {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, rgba(18, 50, 80, 1), rgba(26, 62, 99, 1));
    overflow-x: hidden;
    opacity: 1;
}


body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('/images/logo.png') center/cover no-repeat;
    opacity: 0.25; 
    filter: blur(1px); 
    z-index: -1; 
}


.product-card { 
    background: #fff;
    border-radius: 0.75rem; 
    transition: all 0.3s ease;
}
.product-card:hover { 
    transform: translateY(-4px); 
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.product-img-wrapper { 
    height: 150px; 
    overflow: hidden; 
    border-radius: 0.75rem 0.75rem 0 0; 
}
.product-img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    transition: transform 0.5s ease; 
}
.product-img-wrapper:hover .product-img { 
    transform: scale(1.05); 
}


.stock-badge { 
    background: linear-gradient(135deg, #1e3a8a, #2563eb); 
    font-size: 0.6rem; 
    padding: 0.2rem 0.4rem; 
    border-radius: 0.3rem;
    color: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}
.discount-badge { 
    background: #f43f5e; 
    font-size: 0.6rem; 
    padding: 0.2rem 0.4rem;
    border-radius: 0.3rem;
    color: #fff;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}
.store-badge {
    font-size: 0.6rem;
    font-weight: 600;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    padding: 2px 5px;
    border-radius: 0.2rem;
}


.btn-buy { 
    background: linear-gradient(135deg, #facc15, #f59e0b);
    border: none; 
    color: #000; 
    font-weight: 600; 
    border-radius: 0.5rem; 
    font-size: 0.8rem; 
    padding: 0.4rem; 
    transition: all 0.3s ease;
}
.btn-buy:hover { 
    background: linear-gradient(135deg, #fbbf24, #f59e0b); 
    transform: scale(1.03); 
}
.btn-add { 
    background: linear-gradient(135deg, #2563eb, #3b82f6); 
    border: none; 
    color: #fff; 
    font-weight: 600; 
    border-radius: 0.5rem;
    font-size: 0.8rem;
    padding: 0.4rem;
    transition: all 0.3s ease;
}
.btn-add:hover { 
    background: linear-gradient(135deg, #1d4ed8, #2563eb); 
    transform: scale(1.03); 
}


.card-body {
    padding: 0.75rem; 
}
.card-body h6 {
    font-size: 0.9rem; 
    margin-bottom: 0.3rem;
}
.card-body p {
    font-size: 0.7rem; 
    margin-bottom: 0.3rem;
}
.card-body .text-danger {
    font-size: 0.85rem; 
}

.custom-toast { 
    background: linear-gradient(135deg, #16a34a, #22c55e); 
    color: #fff;
    font-weight: 500;
    border-radius: 0.5rem;
    font-size: 0.8rem; 
}
.custom-toast .btn-close { filter: invert(1); }
</style>
@endpush
