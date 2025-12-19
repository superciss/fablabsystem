@extends('layouts.main')

@section('title', 'Customized Products')

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
        padding: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
        text-transform: uppercase;
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

    .preview-img {
        width: 160px;
        height: 120px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        object-fit: cover;
    }

    /* ✅ Modal Image Styles */
    #mainPreview {
        max-width: 90%;
        max-height: 80vh;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        object-fit: contain;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    #switchImageBtn {
        margin-top: 16px;
        font-weight: 500;
    }

    .modal-content {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 1rem;
    }

    .modal-header {
        border-bottom: none;
        padding-bottom: 0;
    }

    .modal-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h2 class="dashboard-title">Customized Products</h2>
        </div>
    </div>

    <!-- Customizations Table -->
    <div class="modern-table">
        <div class="card-header">

         <a href="{{ route('personal_designs.index') }}" class="btn btn-primary btn-sm">
                Style Custom Designs
            </a>

        </div>
         
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="customTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Product</th>
                            <th>Preview</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Approved</th>
                            <th>Status</th>
                            <th>Delivery Status</th>
                            <th>Total Price</th>
                            <th>Estimated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customizations as $custom)
                        <tr>
                            <td>{{ $custom->user->name ?? 'N/A' }}</td>
                            <td>{{ $custom->product->name ?? 'N/A' }}</td>
                            <td>
                                <img src="{{ $custom->front_image }}" alt="Front preview" class="preview-img">
                            </td>
                            <td>{{$custom->quantity}}</td>
                            <td>{{ $custom->description ?? '—' }}</td>

                            <!-- Status toggle -->
                            <td>
                                <form action="{{ route('customize.toggleApproval', $custom->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $custom->approved ? 'btn-success' : 'btn-warning' }}">
                                        {{ $custom->approved ? 'Approved' : 'Not Approved' }}
                                    </button>
                                </form>
                            </td>
                            <td>{{ ucfirst($custom->customized_status ?? 'N/A') }}</td>
                            <td>{{ $custom->delivery_customized ? ucfirst(str_replace('_',' ',$custom->delivery_customized)) : 'Not Set' }}</td>

                            <td>₱{{ number_format($custom->total_price, 2) }}</td>
                            <td>  @if($custom->estimate_date_custom)
                                        {{ \Carbon\Carbon::parse($custom->estimate_date_custom)->timezone('Asia/Manila')->format('M d, Y') }}
                                    @else
                                        No Date Set
                                    @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <!-- ✅ View Button -->
                                    <button type="button"
                                        class="btn btn-sm btn-outline-info d-flex align-items-center gap-1"
                                        onclick="viewImages('{{ $custom->front_image }}', '{{ $custom->backImage->back_img ?? '' }}')">
                                        <i class="bi bi-eye"></i> View
                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#updateOrderModal{{ $custom->order->id ?? $custom->id }}">
                                        Update Order
                                    </button>

                                    <!-- SMS button -->
                                    <form action="{{ route('customize.sendSms', $custom->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                                            <i class="bi bi-send"></i> SMS
                                        </button>
                                    </form>

                                    <!-- Delete button -->
                                        <form id="delete-form-{{  $custom->id }}" 
                                            action="{{ route('customize.destroy', $custom->id) }}" 
                                            method="POST" class="m-0">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" 
                                                    onclick="confirmDelete('delete-form-{{ $custom->id }}')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>              
                                 </div>
                            </td>
                        </tr>

                        <!-- UPDATE ORDER MODAL -->
                        <div class="modal fade" id="updateOrderModal{{ $custom->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Update Customization</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <form action="{{ route('customize.updateOrder', $custom->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="modal-body">

                                            {{-- STATUS --}}
                                            <div class="mb-3">
                                                <label for="customized_status{{ $custom->id }}" class="form-label">Status</label>
                                                <select name="customized_status" id="customized_status{{ $custom->id }}" class="form-select" required>
                                                    <option value="pending" {{ ($custom->customized_status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="processing" {{ ($custom->customized_status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
                                                    <option value="completed" {{ ($custom->customized_status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                    <option value="cancelled" {{ ($custom->customized_status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>

                                            {{-- ESTIMATED DATE --}}
                                            <div class="mb-3">
                                                <label for="estimate_date_custom{{ $custom->id }}" class="form-label">Estimated Date</label>
                                                <input type="datetime-local" 
                                                    class="form-control" 
                                                    id="estimate_date_custom{{ $custom->id }}" 
                                                    name="estimate_date_custom"
                                                    value="{{ isset($custom->estimate_date_custom) ? \Carbon\Carbon::parse($custom->estimate_date_custom)->format('Y-m-d\TH:i') : '' }}">
                                            </div>

                                            {{-- DELIVERY STATUS --}}
                                            <div class="mb-3">
                                                <label for="delivery_customized{{ $custom->id }}" class="form-label">Delivery Status</label>
                                                <select name="delivery_customized" id="delivery_customized{{ $custom->id }}" class="form-select">
                                                    <option value="is_ongoing" {{ ($custom->delivery_customized ?? '') == 'is_ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                    <option value="is_upcoming" {{ ($custom->delivery_customized ?? '') == 'is_upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                    <option value="for_pickup" {{ ($custom->delivery_customized ?? '') == 'for_pickup' ? 'selected' : '' }}>For Pickup</option>
                                                    <option value="for_delivery" {{ ($custom->delivery_customized ?? '') == 'for_delivery' ? 'selected' : '' }}>For Delivery</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>


                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted" style="padding: 2rem;">
                                No customizations found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-semibold" id="imageModalLabel">Image Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <img id="mainPreview" src="" alt="Preview">
        <button id="switchImageBtn" class="btn btn-outline-primary btn-sm mt-3">
            <i class="bi bi-arrow-repeat"></i> Switch to Back
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    $(function () {
        $('#customTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search customizations...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ records",
                paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
            },
            responsive: true,
            ordering: true,
            columnDefs: [{ orderable: false, targets: [3, 7] }]
        });
    });

    let currentView = 'front';
    let frontImage = '';
    let backImage = '';

    // ✅ When clicking "View"
    function viewImages(frontUrl, backUrl) {
        frontImage = frontUrl;
        backImage = backUrl || "{{ asset('images/no-image.png') }}";
        currentView = 'front';

        const preview = document.getElementById('mainPreview');
        const switchBtn = document.getElementById('switchImageBtn');

        preview.src = frontImage;
        switchBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Switch to Back`;

        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }

    // ✅ Switch Button
    document.addEventListener('DOMContentLoaded', function() {
        const switchBtn = document.getElementById('switchImageBtn');
        const preview = document.getElementById('mainPreview');

        switchBtn.addEventListener('click', () => {
            if (currentView === 'front') {
                preview.src = backImage;
                currentView = 'back';
                switchBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Switch to Front`;
            } else {
                preview.src = frontImage;
                currentView = 'front';
                switchBtn.innerHTML = `<i class="bi bi-arrow-repeat"></i> Switch to Back`;
            }
        });
    });

</script>
@endpush
