@extends('layouts.main')

@section('title', 'Customer Personal Designs')

@section('content')
<style>
    body {
        background: #f8fafc;
        font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
        line-height: 1.5;
    }

    .dashboard-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .modern-table {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .modern-table .card-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
        font-weight: 600;
        font-size: 1rem;
        color: #1e293b;
    }

    .modern-table thead th {
        padding: 1rem;
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
    }

    .modern-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
        color: #1e293b;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.2s ease;
    }

    .preview-img {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        object-fit: cover;
        cursor: pointer;
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">

    <!-- Page Header -->
    <div class="d-flex align-items-center mb-4 mt-2" style="gap: 10px;">
        <a href="{{ route('staff.customize.index') }}" 
           class="btn btn-outline-dark"
           style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="bi bi-arrow-left"></i>
        </a>

        <h2 class="dashboard-title mb-0">Customer Personal Designs</h2>
    </div>

    <!-- Designs Table -->
    <div class="modern-table">
        <div class="card-header">Saved Designs</div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="designTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($designs as $design)
                        <tr>
                            <td>{{ $design->id }}</td>
                            <td>{{ $design->user->name ?? 'N/A' }}</td>
                            <td>{{ $design->description ?? '—' }}</td>
                            <td>₱{{ number_format($design->total_price, 2) }}</td>
                            <td>
                                @if($design->approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Not Approve</span>
                                @endif
                            </td>
                            <td>
                                <img src="{{ $design->image_design }}" 
                                     class="preview-img img-clickable" 
                                     data-bs-toggle="modal"
                                     data-bs-target="#imageModal"
                                     data-img="{{ $design->image_design }}">
                            </td>
                            <td class="d-flex gap-1">
                                <!-- Update Price -->
                                <button class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#updatePriceModal{{ $design->id }}">
                                    <i class="bi bi-cash-coin"></i>
                                </button>

                                <!-- Approve -->
                                @if(!$design->approved)
                                    <form action="{{ route('personal_design.approve', $design->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted" title="Already approved">✔</span>
                                @endif

                                <!-- Delete -->
                                   <form id="delete-form-{{ $design->id }}" action="{{ route('personal_design.destroy', $design->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-form-{{ $design->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Update Price Modal -->
                        <div class="modal fade" id="updatePriceModal{{ $design->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('personal_design.update-price', $design->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Price</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label>Total Price</label>
                                            <input type="number" name="total_price" step="0.01" class="form-control" value="{{ $design->total_price }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted p-4">No designs found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Original Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-body p-0">
                <img src="" id="modalImage" class="img-fluid w-100" style="border-radius: 8px;">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function () {
        $('#designTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search designs...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ records",
                paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
            },
            responsive: true,
            ordering: true,
            columnDefs: [{ orderable: false, targets: [5, 6] }]
        });
    });

    // Modal Image Preview (Original)
    document.addEventListener('DOMContentLoaded', function () {
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        document.querySelectorAll('.img-clickable').forEach(img => {
            img.addEventListener('click', function () {
                modalImage.src = this.dataset.img;
            });
        });

        imageModal.addEventListener('hidden.bs.modal', function () {
            modalImage.src = '';
        });
    });
</script>
@endpush
@endsection
