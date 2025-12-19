<div class="modal fade" id="addmachineModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-mb">
    <form action="{{ route('machine.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add Machine Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-4">
              <label class="form-label">Name</label>
              <input type="text" name="machine_name" class="form-control" required>
          </div>

          <div class="mb-4">
              <label class="form-label">Brand</label>
              <input type="text" name="brand" class="form-control" required>
          </div>

          <div class="mb-4">
              <label class="form-label">Property Number</label>
              <input type="text" name="property_no" class="form-control">
          </div>

          <div class="mb-4">
              <label class="form-label">Status</label>
              <select name="status" class="form-control" required>
                  <option value="serviceable">serviceable</option>
                  <option value="non serviceable">Non serviceable</option>
                  <option value="return to supplier for repairing">Return</option>
                  <option value="functional">Functional</option>
              </select>
          </div>
          <div class="mb-3">
                <label class="form-label">Cost</label>
                <input type="text" name="cost" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Machine Product</button>
        </div>
    </form>
  </div>
</div>
