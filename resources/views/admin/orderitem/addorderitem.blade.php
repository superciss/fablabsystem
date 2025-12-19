<div class="modal fade" id="addOrderItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('orderitems.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add Order Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-4">
              <label for="order_id" class="form-label">Order</label>
              <select name="order_id" id="order_id" class="form-control" required>
                  <option value="">-- Select Order --</option>
                  @foreach($orders as $order)
                      <option value="{{ $order->id }}">{{ $order->id }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-4">
              <label for="product_id" class="form-label">Product</label>
              <select name="product_id" id="product_id" class="form-control" required>
                  <option value="">-- Select Product --</option>
                  @foreach($products as $product)
                      <option value="{{ $product->id }}">{{ $product->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-4">
              <label for="quantity" class="form-label">Quantity</label>
              <input type="number" name="quantity" id="quantity" class="form-control" required>
          </div>

          <div class="mb-4">
              <label for="price" class="form-label">Price</label>
              <input type="number" step="0.01" name="price" id="price" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Item</button>
        </div>
    </form>
  </div>
</div>
