@extends('layouts.maincustomer')

@section('title', $product->name)

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                <div class="row g-0">
                    {{-- Product Image --}}
                    <div class="col-md-6 p-4 image-section">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/500x400' }}" 
                             class="img-fluid rounded shadow-sm main-image" alt="{{ $product->name }}">
                    </div>

                    <div class="col-md-6 p-4 d-flex flex-column">
                        {{-- Product Info --}}
                        <h2 class="fw-bold text-dark mb-3 product-title">{{ $product->name }}</h2>
                        <p class="text-muted mb-3 product-description">{{ $product->description }}</p>
                        <h4 class="price mb-3">₱{{ number_format($product->price, 2) }}</h4>
                        <div class="d-flex gap-3 mb-4">
                            <p class="stock"><strong>Stock:</strong> {{ $product->stock }}</p>
                            <p class="stock"><strong>Sold:</strong> {{ $totalSold }}</p>
                        </div>

                        {{-- ⭐ Ratings --}}
                        @php
                            $avg = round($product->averageRating(), 1);
                            $totalRatings = $product->ratings->count();
                        @endphp
                        <div class="mb-4 rating-box">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avg))
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i - $avg < 1)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span class="rating-text">
                                {{ $totalRatings > 0 ? "($avg) $totalRatings ratings" : 'No ratings yet' }}
                            </span>
                        </div>

                        {{-- ⭐ Customer Reviews --}}
                    <div class="reviews-card shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 text-dark">Customer Reviews</h5>
                            <div class="reviews-scrollable" style="max-height: 400px; overflow-y: auto;">
                                @forelse($product->ratings as $rating)
                                    <div class="review mb-3 pb-3 border-bottom">
                                        <strong class="d-block text-dark">{{ $rating->user->name ?? 'Anonymous' }}</strong>

                                        {{-- ⭐ Display Stars --}}
                                        <div class="mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>

                                        {{-- 📷 Display Image if exists --}}
                                        @if($rating->image)
                                            <div class="mb-2">
                                                <img src="{{ $rating->image }}" alt="Review Image" class="img-fluid rounded shadow-sm" style="max-width:150px; max-height:150px; object-fit:cover;">
                                            </div>
                                        @endif

                                        {{-- 💬 Comment --}}
                                        <p class="mb-1 text-muted">{{ $rating->comment ?? 'No comment provided.' }}</p>

                                        <small class="text-secondary">Reviewed on {{ $rating->created_at->format('M d, Y') }}</small>
                                    </div>
                                @empty
                                    <p class="text-muted text-center">No reviews yet. Be the first to review!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>


                        {{-- Buttons --}}
                        <div class="d-flex gap-2 mt-auto">
                            <button class="btn btn-buy w-50 btn-lg" data-bs-toggle="modal" data-bs-target="#buyNowModal{{ $product->id }}">
                                <i class="bi bi-lightning-charge-fill me-1"></i> Buy Now
                            </button>
                            <button class="btn btn-add w-50 btn-lg" data-bs-toggle="modal" data-bs-target="#addCartModal{{ $product->id }}">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                        </div>

                        {{-- ⭐ Rate Product --}}
                        @php
                            $hasPurchased = \App\Models\OrderItem::where('product_id', $product->id)
                                ->whereHas('order', function ($query) {
                                    $query->where('user_id', Auth::id())
                                          ->where('status', 'completed'); // depende sa order status mo
                                })
                                ->exists();
                        @endphp

                        @if($hasPurchased)
                            <button class="btn btn-rate mt-3" data-bs-toggle="modal" data-bs-target="#rateProductModal{{ $product->id }}">
                                <i class="fas fa-star me-1"></i> Rate this product
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('customer.buyandcartmodal.cart', ['product' => $product])
    @include('customer.buyandcartmodal.buy', ['product' => $product])

    {{-- Rating Modal (only if purchased) --}}
        @if($hasPurchased)
        <div class="modal fade" id="rateProductModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header border-0">
                        <h6 class="modal-title fw-bold">Rate {{ $product->name }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('customer.rating', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        {{-- ⭐ Star Rating --}}
                        <div class="mb-4 text-center">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}-{{ $product->id }}" class="d-none">
                                <label for="star{{ $i }}-{{ $product->id }}" class="transition-all cursor-pointer">
                                    <i class="far fa-star fs-3 text-warning hover-scale"></i>
                                </label>
                            @endfor
                        </div>

                        {{-- 📷 Image Upload --}}
                        <div class="mb-3">
                            <label class="form-label">Upload Image (optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        {{-- 💬 Comment --}}
                        <textarea name="comment" class="form-control mb-3 rounded-3" rows="4" placeholder="Write your review..."></textarea>

                        {{-- Submit --}}
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-warning rounded-pill px-4">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

</div>
@endsection

@push('scripts')
<script>
function incrementQty(id, type = 'cart') {
    let input = document.getElementById(type + 'Qty' + id);
    let max = parseInt(input.max);
    if (parseInt(input.value) < max) {
        input.value = parseInt(input.value) + 1;
        updatePrice(id, type);
    }
}

function decrementQty(id, type = 'cart') {
    let input = document.getElementById(type + 'Qty' + id);
    if (input.value > 1) {
        input.value = parseInt(input.value) - 1;
        updatePrice(id, type);
    }
}

function updatePrice(id, type = 'cart') {
    let input = document.getElementById(type + 'Qty' + id);
    let qty = parseInt(input.value);
    let price = parseFloat(input.dataset.price);
    let total = qty * price;
    document.getElementById(type + 'TotalPrice' + id).innerText = total.toFixed(2);
}

function addToCart(productId) {
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
        if (data.success) {
            let modalEl = document.getElementById('addCartModal' + productId);
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            updateCartBadge(data.cartCount);

            let toastEl = document.getElementById("cartToast");
            let toast = new bootstrap.Toast(toastEl, { delay: 2000 });
            toast.show();
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCartBadge(count) {
    let badge = document.getElementById('cart-count');
    if (badge) {
        if (count > 0) {
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

.card { 
    border-radius: 1.5rem; 
    overflow: hidden; 
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15)!important; 
}

.image-section { 
    background: linear-gradient(145deg, #f3f4f6, #e5e7eb); 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 1rem;
}

.main-image { 
    border-radius: 1rem; 
    max-height: 450px; 
    object-fit: cover; 
    transition: transform 0.4s ease, box-shadow 0.3s ease;
}
.main-image:hover { 
    transform: scale(1.08); 
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); 
}

.product-title { 
    font-size: 2rem; 
    color: #1e293b; 
    font-weight: 700;
    letter-spacing: -0.025em;
}
.product-description { 
    color: #475569; 
    font-size: 1rem; 
    line-height: 1.6;
}
.price { 
    color: #ef4444; 
    font-size: 2rem; 
    font-weight: 800;
    background: linear-gradient(90deg, #ef4444, #f97316);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.stock { 
    font-size: 1rem; 
    color: #475569; 
    font-weight: 500;
}

.rating-box { 
    font-size: 1.2rem; 
    display: flex; 
    align-items: center;
}
.rating-text { 
    font-size: 0.9rem; 
    color: #64748b; 
    margin-left: 8px; 
}

.btn-buy { 
    background: linear-gradient(135deg, #facc15, #eab308); 
    color: #1f2937; 
    border-radius: 12px; 
    font-weight: 600; 
    padding: 0.9rem; 
    border: none; 
    transition: all 0.3s ease;
}
.btn-buy:hover { 
    background: linear-gradient(135deg, #fde047, #facc15); 
    transform: translateY(-2px);
}

.btn-add { 
    background: linear-gradient(135deg, #3b82f6, #2563eb); 
    color: #fff; 
    border-radius: 12px; 
    font-weight: 600; 
    padding: 0.9rem; 
    border: none; 
    transition: all 0.3s ease;
}
.btn-add:hover { 
    background: linear-gradient(135deg, #60a5fa, #3b82f6); 
    transform: translateY(-2px);
}

.btn-rate { 
    background: transparent; 
    border: 2px solid #f59e0b; 
    color: #f59e0b; 
    border-radius: 50px; 
    font-size: 0.95rem; 
    padding: 0.5rem 1.2rem; 
    transition: all 0.3s ease;
}
.btn-rate:hover { 
    background: #f59e0b; 
    color: #fff; 
    transform: translateY(-2px);
}

.reviews-card { 
    background: #fff; 
    border-radius: 1rem; 
    padding: 1.5rem; 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}
.reviews-card:hover { 
    transform: translateY(-3px); 
}

.reviews-scrollable { 
    max-height: 200px; 
    overflow-y: auto; 
    padding-right: 10px;
}
.reviews-scrollable::-webkit-scrollbar { 
    width: 6px; 
}
.reviews-scrollable::-webkit-scrollbar-track { 
    background: #f1f5f9; 
    border-radius: 10px; 
}
.reviews-scrollable::-webkit-scrollbar-thumb { 
    background: #94a3b8; 
    border-radius: 10px; 
}
.reviews-scrollable::-webkit-scrollbar-thumb:hover { 
    background: #64748b; 
}

.review strong { 
    color: #1e293b; 
    font-weight: 600;
}
.review p { 
    font-size: 0.95rem; 
    line-height: 1.5;
}

.hover-scale { 
    cursor: pointer; 
    transition: transform 0.3s ease, color 0.3s ease; 
}
.hover-scale:hover { 
    transform: scale(1.3); 
    color: #f59e0b; 
}
</style>
@endpush