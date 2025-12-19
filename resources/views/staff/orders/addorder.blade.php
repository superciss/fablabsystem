<div class="modal fade" id="addOrderModal" tabindex="-1">
    <div class="modal-dialog  modal-dialog-centered">
        <form action="{{ route('stafforders.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>User</label>
                        <select name="user_id" class="form-control" required>
                            @foreach(App\Models\User::where('role', 'customer')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Delivery Type</label>
                        <select name="delivery_type" class="form-control" required>
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>

                    
                    <div class="mb-3">
                        <label>Products</label>
                        <div id="products-container">
                            <div class="row mb-2">
                                <div class="col">
                                    <select name="product_id[]" class="form-control" required>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addProductRow()">Add Product</button>
                    </div>
                </div>
                <div class="modal-footer">
                
                    <button class="btn btn-primary">Save Order</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addProductRow() {
    let container = document.getElementById('products-container');
    container.insertAdjacentHTML('beforeend', `
        <div class="row mb-2">
            <div class="col">
                <select name="product_id[]" class="form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
            </div>
        </div>
    `);
}
</script>
