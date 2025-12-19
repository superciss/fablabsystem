<div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-mb">
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title">Edit Supplier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-4">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
            </div>
            <div class="mb-4">
                <label>Contact Person</label>
                <input type="text" name="contact_person" class="form-control" value="{{ $supplier->contact_person }}">
            </div>
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
            </div>
            <div class="mb-4">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
            </div>
            <div class="mb-4">
                <label>Address</label>
                <textarea name="address" class="form-control">{{ $supplier->address }}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-warning">Update Supply</button>
          </div>
        </div>
    </form>
  </div>
</div>
