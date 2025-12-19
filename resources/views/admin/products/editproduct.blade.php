<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Price (₱)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>


          <div class="mb-3">
            <label class="form-label">Set Stock Alert</label>
            <input type="number" name="low_stock_threshold" class="form-control" value="{{ $product->low_stock_threshold }}">
          </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
            @if($product->image)
              <small>Current: <img src="{{ $product->image }}" width="40"></small>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">Product Type</label>
            <textarea name="pro_category" class="form-control">{{ $product->pro_category }}</textarea>
          </div>

           <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="is_customizable" id="is_customizable{{ $product->id }}" value="1"
                   {{ $product->is_customizable ? 'checked' : '' }}>
            <label class="form-check-label" for="is_customizable{{ $product->id }}">Customizable Product</label>
          </div>

        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Update Product</button>
        </div>
      </form>
    </div>
  </div>
</div>
