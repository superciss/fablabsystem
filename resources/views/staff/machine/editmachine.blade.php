<div class="modal fade" id="editmachineModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-mb">
    <form action="{{ route('machine.update', $machine->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title">Edit Machine Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="machine_name" class="form-control" value="{{ $machine->machine_name }}" required>
            </div>
            <div class="mb-4">
                <label>Brand</label>
                <input type="text" name="brand" class="form-control" value="{{ $machine->brand }}" required>
            </div>
            <div class="mb-4">
                <label>Property Number</label>
                <input type="text" name="property_no" class="form-control" value="{{ $machine->property_no }}" required>
            </div>
            <div class="mb-4">
                <label>Status</label>
                <select name="status" class="form-control" required>
                  <option value="serviceable" {{ $machine->status == 'serviceable' ? 'selected' : '' }} >serviceable</option>
                  <option value="non serviceable" {{ $machine->status == 'non serviceable' ? 'selected' : '' }}>Non serviceable</option>
                  <option value="return to supplier for repairing" {{ $machine->status == 'return to supplier for repairing' ? 'selected' : '' }}>Return</option>
                  <option value="functional" {{ $machine->status == 'functional' ? 'selected' : '' }}>Functional</option>
              </select>
            </div>

             <div class="mb-3">
                <label class="form-label">Cost</label>
                <input type="text" name="cost" class="form-control" value="{{ $machine->cost }}" required>
             </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-warning">Update Machine Product</button>
          </div>
        </div>
    </form>
  </div>
</div>
