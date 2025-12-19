<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-mb">
    <form action="{{ route('users.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-4">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-4">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-4">
              <label class="form-label">Password <small class="text-muted">(leave blank if using Google login)</small></label>
              <input type="password" name="password" class="form-control">
          </div>

          <div class="mb-4">
              <label class="form-label">Role</label>
              <select name="role" class="form-control" required>
                  <option value="customer">Customer</option>
                  <option value="staff">Staff</option>
                  <option value="admin">Admin</option>
              </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add User</button>
        </div>
    </form>
  </div>
</div>
