<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-mb"><!-- added modal-lg -->
    <form action="{{ route('suppliers.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-4">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-4">
              <label class="form-label">Contact Person</label>
              <input type="text" name="contact_person" class="form-control">
          </div>
          <div class="mb-4">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control">
          </div>
          <div class="mb-4">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control">
          </div>
          <div class="mb-4">
              <label class="form-label">Address</label>
              <textarea name="address" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Supplier</button>
        </div>
    </form>
  </div>
</div>
