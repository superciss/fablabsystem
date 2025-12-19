<div class="modal fade" id="editOrderModa{{ $order->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg  modal-dialog-centered">
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Order #{{ $order->order_number }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label>Customer</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                  <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                  </option>
                @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label>Delivery Type</label>
            <select name="delivery_type" class="form-control" required>
              <option value="pickup" {{ $order->delivery_type == 'pickup' ? 'selected' : '' }}>Pickup</option>
              <option value="delivery" {{ $order->delivery_type == 'delivery' ? 'selected' : '' }}>Delivery</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
              <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
              <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
          </div>

          <h5>Products (up to 3)</h5>
          @php $count = 0; @endphp
          @foreach($order->orderitem as $item)
          <div class="row mb-2">
              <div class="col">
                <select name="product_id[]" class="form-control">
                  <option value="">-- Select Product --</option>
                  @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                      {{ $product->name }} (₱{{ $product->price }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col">
                <input type="number" name="quantity[]" value="{{ $item->quantity }}" class="form-control" min="1">
              </div>

              <div class="col">
                <input type="date" name="created_at[]" value="{{ $item->created_at }}" class="form-control" min="1">
              </div>
          </div>
          @php $count++; @endphp
          @endforeach

          @for($i=$count;$i<3;$i++)
          <div class="row mb-2">
              <div class="col">
                <select name="product_id[]" class="form-control">
                  <option value="">-- Select Product --</option>
                  @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (₱{{ $product->price }})</option>
                  @endforeach
                </select>
              </div>
              <div class="col">
                <input type="number" name="quantity[]" class="form-control" min="1">
              </div>
          </div>
          @endfor

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
