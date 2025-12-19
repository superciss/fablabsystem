<div class="modal fade" id="editPurchaseModal{{ $purchase->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form method="POST" action="{{ route('paysupply.update', $purchase->id) }}">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header bg-warning text-white"><h5>Edit Purchase #{{ $purchase->id }}</h5></div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Supplier</label>
                <select name="supplier_id" class="form-control">
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected':'' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="purchase_date" class="form-control" value="{{ $purchase->purchase_date }}">
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="unpaid" {{ $purchase->status == 'unpaid' ? 'selected':'' }}>Unpaid</option>
                    <option value="partial" {{ $purchase->status == 'partial' ? 'selected':'' }}>Partial</option>
                    <option value="paid" {{ $purchase->status == 'paid' ? 'selected':'' }}>Paid</option>
                </select>
            </div>
            <hr>
            <div id="product-list-{{ $purchase->id }}">
                @foreach($purchase->items as $item)
                <div class="row mb-2 product-item">
                    <div class="col-md-4">
                        <select name="products[]" class="form-control">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected':'' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><input type="number" name="quantities[]" class="form-control" value="{{ $item->quantity }}"></div>
                    <div class="col-md-3"><input type="number" step="0.01" name="costs[]" class="form-control" value="{{ $item->cost }}"></div>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary add-product-edit" data-id="{{ $purchase->id }}">+ Add Product</button>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-warning text-white">Update Supply</button></div>
      </div>
    </form>
  </div>
</div>

<script>
document.querySelectorAll('.add-product-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.getAttribute('data-id');
        let container = document.getElementById('product-list-' + id);
        let newItem = container.querySelector('.product-item').cloneNode(true);
        newItem.querySelectorAll('input').forEach(input => input.value = '');
        newItem.querySelector('select').selectedIndex = 0;
        container.appendChild(newItem);
    });
});
</script>
