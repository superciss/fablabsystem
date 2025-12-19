<div class="modal fade" id="addRawModal" tabindex="-1">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Raw Product</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('material.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Raw Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Price (₱)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
          </div>
           <div class="mb-3">
           <!-- Hidden Stock Field -->
          <input type="hidden" name="stock" value="0">

        </div>

          <div class="mb-3">
            <label class="form-label">Low Stock Alert (pcs)</label>
            <input type="number" name="low_stock_threshold" class="form-control"  required>
        </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
              @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Product Category</label>
            <textarea name="pro_category" class="form-control"></textarea>
          </div>
          
            <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" name="is_customizable" id="is_customizable" value="1">
            <label class="form-check-label" for="is_customizable">Customizable Product</label>
          </div>
          
          </div>
        

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Product</button>
        </div>
      </form>
    </div>
  </div>
</div>
