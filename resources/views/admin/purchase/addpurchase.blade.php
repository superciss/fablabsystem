<div class="modal fade" id="addPurchaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form action="{{ route('purchases.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add Purchase</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
              <option value="">-- Select Supplier --</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label>Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
              <option value="unpaid">Unpaid</option>
              <option value="partial">Partial</option>
              <option value="paid">Paid</option>
            </select>
          </div>

          <h6>Products</h6>
          <div class="row mb-2">
            <div class="col"><strong>Product</strong></div>
            <div class="col"><strong>Quantity</strong></div>
            <div class="col"><strong>Cost</strong></div>
          </div>

          @for($i=0;$i<5;$i++)
          <div class="row mb-2">
            <div class="col">
              <select name="product_id[]" class="form-control">
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <input type="number" name="quantity[]" class="form-control" min="1">
            </div>
            <div class="col">
              <input type="number" step="0.01" name="cost[]" class="form-control">
            </div>
          </div>
          @endfor

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Purchase</button>
        </div>
      </div>
    </form>
  </div>
</div>
