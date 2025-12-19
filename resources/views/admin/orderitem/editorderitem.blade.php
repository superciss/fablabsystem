<div class="modal fade" id="editOrderItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('orderitems.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title">Edit Order Item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-4">
                <label>Order</label>
                <select name="order_id" class="form-control" required>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ $item->order_id == $order->id ? 'selected' : '' }}>
                            {{ $order->id }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label>Product</label>
                <select name="product_id" class="form-control" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" required>
            </div>
            <div class="mb-4">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ $item->price }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-warning">Update</button>
          </div>
        </div>
    </form>
  </div>
</div>
