<div class="modal fade" id="addOrderModa" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('orders.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label>Customer</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
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

          <h5>Products (up to 3)</h5>
          @for($i=0;$i<3;$i++)
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
                <input type="number" name="quantity[]" class="form-control" placeholder="Qty" min="1">
              </div>

               <!-- <div class="col">
                <input type="date" name="created_at[]" class="form-control" min="1">
              </div> -->
          </div>
          @endfor

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
