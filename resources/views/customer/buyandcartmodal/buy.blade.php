{{-- Buy Now Modal --}}
<div class="modal fade" id="buyNowModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
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
                        <form action="{{ route('customershop.buy', $product->id) }}" 
                              method="POST" 
                              class="mt-auto">
                            @csrf

                            <!-- Delivery Type -->
                            <div class="mb-3">
                                <label class="fw-semibold mb-2">Choose Delivery Type:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="delivery_type" 
                                           id="pickup{{ $product->id }}" 
                                           value="pickup" checked>
                                    <label class="form-check-label" for="pickup{{ $product->id }}">
                                        Pickup
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                           name="delivery_type" 
                                           id="delivery{{ $product->id }}" 
                                           value="delivery">
                                    <label class="form-check-label" for="delivery{{ $product->id }}">
                                        Delivery
                                    </label>
                                </div>
                            </div>

                            <!-- 🟩 Type Request -->
                            <div class="mb-3">
                                <label for="typeRequest{{ $product->id }}" class="fw-semibold mb-2">Type of Request:</label>
                                <select class="form-select" id="typeRequest{{ $product->id }}" name="type_request">
                                    <option value="" selected disabled>Select type</option>
                                    <option value="cash">Cash</option>
                                    <option value="purchase request">Purchase Request</option>
                                </select>
                            </div>

                            
                            <!-- Quantity Controls -->
                            <div class="d-flex align-items-center mb-3">
                                <button type="button" class="btn btn-outline-secondary rounded-circle" 
                                        onclick="decrementQty({{ $product->id }}, 'buy')">−</button>
                                
                                <input type="number" 
                                       id="buyQty{{ $product->id }}" 
                                       name="quantity" 
                                       class="form-control text-center mx-2 fw-bold" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}" 
                                       data-price="{{ $product->price }}" 
                                       onchange="updatePrice({{ $product->id }}, 'buy')"
                                       style="max-width:80px;">
                                
                                <button type="button" class="btn btn-outline-secondary rounded-circle" 
                                        onclick="incrementQty({{ $product->id }}, 'buy')">+</button>
                            </div>

                            <!-- Total Price -->
                            <p class="fw-bold mb-4 fs-5">
                                Total: ₱<span id="buyTotalPrice{{ $product->id }}">
                                    {{ number_format($product->price, 2) }}
                                </span>
                            </p>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-buy flex-fill py-2">
                                    <i class="bi bi-lightning-charge-fill me-1"></i> Buy Now
                                </button>

                                {{-- ✅ Show Customize button only if product is customizable --}}
                                @if($product->is_customizable)
                                    <a href="{{ route('customershop.customize', $product->id) }}" 
                                       class="btn btn-outline-primary flex-fill py-2">
                                        <i class="bi bi-sliders me-1"></i> Customize
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
