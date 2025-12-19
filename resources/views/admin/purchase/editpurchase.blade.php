<div class="modal fade" id="editPurchaseModal{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Purchase #{{ $purchase->id }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
              <option value="">-- Select Supplier --</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ $purchase->supplier_id==$supplier->id?'selected':'' }}>{{ $supplier->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label>Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}" required>
          </div>

          <select name="status" class="form-control" required>
            <option value="unpaid" {{ $purchase->status=='unpaid'?'selected':'' }}>Unpaid</option>
            <option value="partial" {{ $purchase->status=='partial'?'selected':'' }}>Partial</option>
            <option value="paid" {{ $purchase->status=='paid'?'selected':'' }}>Paid</option>
        </select>


          <h6>Products</h6>
          <div class="row mb-2">
            <div class="col"><strong>Product</strong></div>
            <div class="col"><strong>Quantity</strong></div>
            <div class="col"><strong>Cost</strong></div>
          </div>

          @for($i=0;$i<5;$i++)
          @php $item = $purchase->items[$i] ?? null; @endphp
          <div class="row mb-2">
            <div class="col">
              <select name="product_id[]" class="form-control">
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                  <option value="{{ $product->id }}" {{ $item && $item->product_id==$product->id ? 'selected':'' }}>{{ $product->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col">
              <input type="number" name="quantity[]" class="form-control" min="1" value="{{ $item->quantity ?? '' }}">
            </div>
            <div class="col">
              <input type="number" step="0.01" name="cost[]" class="form-control" value="{{ $item->cost ?? '' }}">
            </div>
          </div>
          @endfor

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-warning">Update Purchase</button>
        </div>
      </div>
    </form>
  </div>
</div>
