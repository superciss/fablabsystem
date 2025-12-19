<div class="modal fade" id="addOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('adminorder.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Order</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-control" required>
                            @foreach(App\Models\User::where('role', 'customer')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Delivery Type</label>
                        <select name="delivery_type" class="form-control" required>
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                          
                        </select>
                    </div>
                     <div class="mb-4">
                        <label class="form-label">Delivery Status</label>
                        <select name="delivery_status" class="form-control">
                            <option value="for_pickup">For Pickup</option>
                            <option value="for_delivery">For Delivery</option>
                            <option value="is_ongoing">Is Ongoing</option>
                            <option value="is_upcoming">Is Upcoming</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                          <label class="form-label">Type Request</label>
                        <select name="type_request" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="type request">Type Request</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Estimated Date</label>
                        <input type="date" name="estimate_date" class="form-control" required>   
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Products</label>
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
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addProductRow()">Add Product</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Order</button>
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