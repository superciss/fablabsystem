<style>
  .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.15);
    background-color: #ffffff;
    overflow: hidden;
    transition: transform 0.3s ease, opacity 0.3s ease;
  }

  .modal-header {
    border-bottom: 1px solid #e5e7eb;
    padding: 1.5rem;
    background-color: #f9fafb;
  }

  .modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
  }

  .btn-close {
    color: #6b7280;
    transition: color 0.2s ease;
  }

  .btn-close:hover {
    color: #374151;
  }

  .btn-close svg {
    width: 1.5rem;
    height: 1.5rem;
  }

  .modal-body {
    padding: 1.5rem;
  }

  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
  }

  .form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    color: #111827;
    background-color: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
  }

  .form-control textarea {
    resize: vertical;
  }

  .modal-footer {
    border-top: 1px solid #e5e7eb;
    padding: 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
  }

  .btn {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    border-radius: 8px;
    transition: background-color 0.2s ease, transform 0.1s ease;
  }

  .btn-primary {
    background-color: #2563eb;
    color: #ffffff;
    border: none;
  }

  .btn-primary:hover {
    background-color: #1d4ed8;
    transform: translateY(-1px);
  }

  .btn-secondary {
    background-color: #e5e7eb;
    color: #374151;
    border: none;
  }

  .btn-secondary:hover {
    background-color: #d1d5db;
    transform: translateY(-1px);
  }
</style>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('categories.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <label for="name" class="form-label">Category Name</label>
          <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-4">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="4"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Category</button>
      </div>
    </form>
  </div>
</div>
