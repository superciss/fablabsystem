<div class="modal fade" id="addPurchaseModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form method="POST" action="{{ route('paysupply.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white"><h5>Add Purchase</h5></div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Supplier</label>
                <select name="supplier_id" class="form-control" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="purchase_date" class="form-control" required>
            </div>
            <div id="product-list">
                <div class="row mb-2 product-item">
                    <div class="col-md-4">
                        <select name="products[]" class="form-control" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><input type="number" name="quantities[]" class="form-control" placeholder="Qty" min="1"></div>
                    <div class="col-md-3"><input type="number" step="0.01" name="costs[]" class="form-control" placeholder="Cost"></div>
                </div>
            </div>
            <button type="button" id="add-product" class="btn btn-secondary">+ Add Product</button>
        </div>
        <div class="modal-footer"><button type="submit" class="btn btn-primary">Add Supply</button></div>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('add-product').addEventListener('click', function() {
    let container = document.getElementById('product-list');
    let newItem = container.querySelector('.product-item').cloneNode(true);
    newItem.querySelectorAll('input').forEach(input => input.value = '');
    newItem.querySelector('select').selectedIndex = 0;
    container.appendChild(newItem);
});
</script>
