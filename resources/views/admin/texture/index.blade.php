@extends('layouts.main')

@section('title', 'Textures Management')

@section('content')
<style>
    /* Modern, professional UI styling */
    body {
        background: #f8fafc;
        font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }

    .dashboard-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .dashboard-subtitle {
        font-size: 1.125rem;
        color: #64748b;
        font-weight: 400;
        margin-bottom: 2rem;
    }

    /* Modern card design */
    .modern-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .modern-card:focus-within {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    .card-header {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.1;
        background: linear-gradient(to right, #1e293b, #334155);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Icon styling */
    .icon-circle {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.75rem;
        transition: transform 0.3s ease;
    }

    .icon-circle:hover {
        transform: scale(1.1);
    }

    .bg-primary { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: #ffffff; }
    .bg-success { background: linear-gradient(135deg, #0b37c7ff, #0846b9ff); color: #ffffff; }
    .bg-warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #ffffff; }

    .text-primary { background: linear-gradient(to right, #3b82f6, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-success { background: linear-gradient(to right, #10b981, #34d399); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .text-warning { background: linear-gradient(to right, #f59e0b, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* Modern table styling */
    .modern-table {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .modern-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table .card-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
    }

    .modern-table thead {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
    }

    .modern-table thead th {
        padding: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.075em;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table tbody td {
        padding: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #1e293b;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.2s ease;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modern badge styling */
    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .badge:hover {
        transform: translateY(-1px);
    }

    .badge-primary { background: #dbeafe; color: #1e40af; }
    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }

    /* Button styling */
    .btn-modern {
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-primary { background: linear-gradient(135deg, #3b82f6, #60a5fa); border: none; color: #ffffff; }
    .btn-primary:hover { background: linear-gradient(135deg, #2563eb, #3b82f6); }
    .btn-success { background: linear-gradient(135deg, #1053b9ff, #134cebff); border: none; color: #ffffff; }
    .btn-success:hover { background: linear-gradient(135deg, #059669, #10b981); }
    .btn-outline-secondary { border-color: #e2e8f0; color: #64748b; }
    .btn-outline-secondary:hover { background: #f8fafc; }
    .btn-outline-warning { border-color: #f59e0b; color: #f59e0b; }
    .btn-outline-warning:hover { background: #fef3c7; }
    .btn-outline-danger { border-color: #ef4444; color: #ef4444; }
    .btn-outline-danger:hover { background: #fee2e2; }

    /* Modal styling */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        padding: 1.25rem;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem;
    }

    /* Form styling */
    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-text {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.75rem;
        }
        .dashboard-subtitle {
            font-size: 1rem;
        }
        .stat-value {
            font-size: 1.75rem;
        }
        .modern-card {
            padding: 1.25rem;
        }
        .icon-circle {
            width: 48px;
            height: 48px;
            font-size: 1.5rem;
        }
        .modern-table .card-header {
            padding: 1rem 1.5rem;
        }
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1 btn-modern">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="dashboard-title">Textures Management</h2>
                <p class="dashboard-subtitle">Manage system textures and their categories</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-modern d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTextureModal">
                <i class="bi bi-plus-lg"></i> Add New Texture
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Total Textures</div>
                        <h4 class="stat-value text-primary">{{ $textures->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-primary">
                        <i class="bi bi-image-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Categorized</div>
                        <h4 class="stat-value text-success">{{ $textures->whereNotNull('category')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-success">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="modern-card" tabindex="0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-header">Uncategorized</div>
                        <h4 class="stat-value text-warning">{{ $textures->whereNull('category')->count() }}</h4>
                    </div>
                    <div class="icon-circle bg-warning">
                        <i class="bi bi-tag-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Textures Table -->
    <div class="modern-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Textures Directory</h5>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="textTable">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Type Size</th>
                            <th>Price</th>
                            <th>Upload Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($textures as $texture)
                        <tr>
                            <td>
                                <img src="data:image/png;base64,{{ $texture->image }}" alt="Texture" width="80" height="80" style="border-radius: 8px;">
                            </td>
                            <td>{{ $texture->name }}</td>
                            <td>
                                @if($texture->category)
                                    <span class="badge badge-success">{{ $texture->category }}</span>
                                @else
                                    <span class="badge badge-warning">Uncategorized</span>
                                @endif
                            </td>
                            <td>{{ $texture->size ?? 'N/A' }}</td>
                            <td>{{ $texture->price ?? 0 }}</td>
                            <td>{{ $texture->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning btn-modern" data-bs-toggle="modal" data-bs-target="#editTextureModal{{ $texture->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                  <form id="delete-form-{{ $texture->id }}" action="{{ route('textures.destroy', $texture->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-modern d-flex align-items-center gap-1" onclick="confirmDelete('delete-form-{{ $texture->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        <!-- Edit Texture Modal -->
                        <div class="modal fade" id="editTextureModal{{ $texture->id }}" tabindex="-1" aria-labelledby="editTextureLabel{{ $texture->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="POST" action="{{ route('textures.update', $texture->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title text-white " id="editTextureLabel{{ $texture->id }}">Edit Texture</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Texture Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $texture->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Category</label>
                                                <input type="text" name="category" class="form-control" value="{{ old('category', $texture->category) }}">
                                            </div>
                                            <div>
                                                <label class="form-label">Type Size</label>
                                                <input type="text" name="size" class="form-control" value="{{ old('size', $texture->size) }}">      
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Texture Image</label>
                                                <input type="file" name="image" class="form-control" accept="image/*">
                                                <small class="form-text text-muted">Current image:</small>
                                                <img src="data:image/png;base64,{{ $texture->image }}" alt="Texture" width="80" height="80" style="border-radius: 8px;">
                                            </div>
                                              <div class="mb-3">
                                                <label class="form-label">Price</label>
                                                <input type="text" name="price" class="form-control" value="{{ old('price', $texture->price) }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning text-white">Update Texture</button>
                                          
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 2rem;">No textures found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Texture Modal -->
    <div class="modal fade" id="addTextureModal" tabindex="-1" aria-labelledby="addTextureLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('textures.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white" id="addTextureLabel">Add Texture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Texture Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type Size</label>
                            <input type="text" name="size" class="form-control" value="{{ old('size') }}">      
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Texture Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" name="price" class="form-control" value="{{ old('price') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Texture</button>
                      
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(function () {
    $('#textTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search Texture...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
        },
        responsive: true,
        ordering: true,
        columnDefs: [{ orderable: false, targets: [3] }]
    });
});
</script>
@endpush
