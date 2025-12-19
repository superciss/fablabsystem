{{-- Add to Cart Modal --}}
<div class="modal fade" id="addCartModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Product Image -->
                    <div class="col-md-6">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}" 
                             class="img-fluid rounded-4" 
                             alt="{{ $product->name }}">
                    </div>

                    <!-- Product Details -->
                    <div class="col-md-6 d-flex flex-column">
                        <h4 class="fw-bold text-dark">{{ $product->name }}</h4>
                        <p class="text-muted small">{{ $product->description }}</p>
                        <h5 class="text-danger fw-bold mb-2">₱{{ number_format($product->price, 2) }}</h5>
                        <span class="badge bg-dark mb-3">Stock: {{ $product->stock }}</span>

                        <!-- Form -->
                        <form id="add-to-cart-form-{{ $product->id }}" 
                              action="{{ route('customercart.add', $product->id) }}" 
                              method="POST" 
                              class="mt-auto">
                            @csrf

                            <!-- Quantity Controls -->
                            <div class="d-flex align-items-center mb-3">
                                <button type="button" class="btn btn-outline-secondary rounded-circle" 
                                        onclick="decrementQty({{ $product->id }}, 'cart')">−</button>
                                
                                <input type="number" 
                                       id="cartQty{{ $product->id }}" 
                                       name="quantity" 
                                       class="form-control text-center mx-2 fw-bold" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}" 
                                       data-price="{{ $product->price }}" 
                                       onchange="updatePrice({{ $product->id }}, 'cart')"
                                       style="max-width:80px;">
                                
                                <button type="button" class="btn btn-outline-secondary rounded-circle" 
                                        onclick="incrementQty({{ $product->id }}, 'cart')">+</button>
                            </div>

                            <!-- Total Price -->
                            <p class="fw-bold mb-4 fs-5">
                                Total: ₱<span id="cartTotalPrice{{ $product->id }}">
                                    {{ number_format($product->price, 2) }}
                                </span>
                            </p>

                            <!-- Add to Cart -->
                            <button type="button" 
                                    onclick="addToCart({{ $product->id }})" 
                                    class="btn btn-add w-100 py-2">
                                <i class="bi bi-cart-plus me-1"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
