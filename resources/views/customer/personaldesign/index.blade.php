@extends('layouts.maincustomer')

@section('title', 'Personal Designs')

@section('content')
<style>
    body {
        position: relative;
        min-height: 100vh;
        background: linear-gradient(135deg, rgba(18, 50, 80, 1), rgba(26, 62, 99, 1));
        overflow-x: hidden;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('/images/logo.png') center/cover no-repeat;
        opacity: 0.25;
        filter: blur(1px);
        z-index: -1;
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

    .modern-table table {
        width: 100%;
        border-collapse: collapse;
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

    .preview-img {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        object-fit: cover;
    }
</style>

<div class="container-fluid px-4" style="margin-left: 20px;">
   <div class="d-flex justify-content-between align-items-center mb-4 mt-2">

    <!-- Back Arrow -->
    <a href="{{ route('customer.customized.index') }}" class="btn btn-outline-light me-3" 
       style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
        <i class="bi bi-arrow-left"></i>
    </a>

    <!-- Page Title -->
    <h2 class="dashboard-title mb-0">Personal Designs</h2>

</div>


    <div class="modern-table">
        <div class="card-header">
            Saved Designs
            <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#addDesignModal">Add Design</button>
        </div>
        
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Total Price</th>
            <th>Approved</th>
            <th>Status</th>
            <th>Delivery Status</th>
            <th>Estimated Date</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($designs as $design)
        <tr>
            <td>{{ $design->id }}</td>

            <td>{{ $design->description ?? '—' }}</td>

            <td>₱{{ number_format($design->total_price, 2) }}</td>

            {{-- Approved --}}
            <td>
                @if($design->approved)
                    <span class="badge bg-success">Approved</span>
                @else
                    <span class="badge bg-warning text-dark">Not Approved</span>
                @endif
            </td>

            {{-- Design Status --}}
            <td>
                @if($design->design_status === 'pending')
                    <span class="badge bg-secondary">Pending</span>
                @elseif($design->design_status === 'processing')
                    <span class="badge bg-primary">Processing</span>
                @elseif($design->design_status === 'completed')
                    <span class="badge bg-success">Completed</span>
                @elseif($design->design_status === 'cancelled')
                    <span class="badge bg-danger">Cancelled</span>
                @else
                    <span class="badge bg-warning text-dark">No Set</span>
                @endif
            </td>

            {{-- Delivery Status --}}
            <td>
                @if($design->deliver === 'is_ongoing')
                    <span class="badge bg-secondary">Ongoing</span>
                @elseif($design->deliver === 'is_upcoming')
                    <span class="badge bg-info">Upcoming</span>
                @elseif($design->deliver === 'for_pickup')
                    <span class="badge bg-success">For Pickup</span>
                @elseif($design->deliver === 'for_delivery')
                    <span class="badge bg-primary">For Delivery</span>
                @else
                    <span class="badge bg-warning text-dark">No Set</span>
                @endif
            </td>

            {{-- Estimated Date --}}
            <td>
                @if($design->estimate_date_design)
                    {{ \Carbon\Carbon::parse($design->estimate_date_design)
                        ->timezone('Asia/Manila')
                        ->format('M d, Y') }}
                @else
                    <span class="text-muted">No Date Set</span>
                @endif
            </td>

            {{-- Image --}}
            <td>
                @if($design->image_design)
                    <img src="{{ $design->image_design }}" class="preview-img" alt="Design Image">
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>

            {{-- Actions --}}
            <td>
                <button
                    class="btn btn-sm btn-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#editDesignModal{{ $design->id }}">
                    <i class="bi bi-pencil-square"></i>
                </button>

                 <button
                    class="btn btn-sm btn-success"
                    onclick="generateDesignPDF({{ $design->id }})">
                    <i class="bi bi-file-earmark-pdf"></i>
                </button>

                <!-- Hidden PDF data -->
                <div id="design-data-{{ $design->id }}"
                    data-id="{{ $design->id }}"
                     data-user="{{ Auth::user()->name }}"
                    data-description="{{ $design->description ?? 'N/A' }}"
                    data-price="{{ $design->total_price }}"
                    data-approved="{{ $design->approved ? 'Approved' : 'Not Approved' }}"
                    data-status="{{ ucfirst($design->design_status ?? 'N/A') }}"
                    data-delivery="{{ ucfirst(str_replace('_',' ', $design->deliver ?? 'N/A')) }}"
                    data-estimated="{{ $design->estimate_date_design 
                        ? \Carbon\Carbon::parse($design->estimate_date_design)->format('M d, Y')
                        : 'N/A' }}">
                </div>
            </td>
        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editDesignModal{{ $design->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('personal-designs.update', $design->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Design</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control">{{ $design->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Total Price</label>
                                                <input type="number" step="0.01" name="total_price" class="form-control" value="{{ $design->total_price }}">
                                            </div>
                                            <div class="mb-3">
                                                <label>Image</label>
                                                <input type="file" name="image_design" class="form-control" onchange="previewEditImage(event, {{ $design->id }})">
                                                <img id="editPreview{{ $design->id }}" src="{{ $design->image_design }}" class="preview-img mt-2">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" type="submit">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding: 2rem;">
                                No designs found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDesignModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('personal-designs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Design</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Total Price</label>
                       <input type="number" name="total_price" value="0" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image_design" class="form-control" onchange="previewAddImage(event)">
                        <img id="addPreview" class="preview-img mt-2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
function peso(n) {
    return Number(n).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function generateDesignPDF(id) {
    const el = document.getElementById('design-data-' + id);
    if (!el) return alert('PDF data missing');

    const d = el.dataset;
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const logo = new Image();
    logo.src = '/images/cspc.png';

    logo.onload = () => {
        const pageWidth = doc.internal.pageSize.width;
        const leftX = 20;
        const rightX = pageWidth / 2 + 5;

        // ================= HEADER =================
        doc.addImage(logo, 'PNG', 10, 9, 17, 17);

        doc.setFontSize(11);
        doc.setFont("Helvetica", "normal");
        doc.text("Republic of the Philippines", 30, 13);

        doc.setFont("Helvetica", "bold");
        doc.text("CAMARINES SUR POLYTECHNIC COLLEGES", 30, 18);

        doc.setFont("Helvetica", "normal");
        doc.text("Nabua, Camarines Sur", 30, 22);

        doc.setFont("Helvetica", "bold");
        doc.text("PRODUCTION AND AUXILIARY SERVICES OFFICE", 30, 26);

        doc.setDrawColor(0, 0, 255);
        doc.line(5, 34, pageWidth - 5, 34);

        // ================= TITLE =================
        doc.setFontSize(14);
        doc.text("PERSONAL DESIGN TRANSACTION", pageWidth / 2, 45, { align: "center" });

        // ================= CONTENT =================
        let y = 58;
        doc.setFontSize(11);
        doc.setFont("Helvetica", "normal");

        doc.text(`Design ID: ${d.id}`, leftX, y);
        y += 8;
         doc.text(`Customer Name: ${d.user}`, leftX, y);
        y += 8;

        doc.text(`Description: ${d.description}`, leftX, y);
        doc.text(`Estimated Date: ${d.date}`, rightX, y);
        y += 8;

        doc.text(`Design Status: ${d.status}`, leftX, y);
        doc.text(`Delivery Status: ${d.delivery}`, rightX, y);
        y += 8;

        doc.text(`Approval: ${d.approved}`, leftX, y);
        y += 12;

        // ================= PRICE =================
        doc.setFont("Helvetica", "bold");
        doc.text(`Total Price: PHP ${peso(d.price)}`, leftX, y);

        // ================= FOOTER =================
        y += 20;
        doc.setFontSize(10);
        doc.text(
            "This document serves as an official design transaction slip.",
            pageWidth / 2,
            y,
            { align: "center" }
        );

        y += 6;
        doc.text(
            "Thank you for your transaction!",
            pageWidth / 2,
            y,
            { align: "center" }
        );

        doc.save(`personal-design-${d.id}.pdf`);
    };
}
</script>

@endsection




@section('scripts')

<script>
function previewAddImage(event) {
    var output = document.getElementById('addPreview');
    output.src = URL.createObjectURL(event.target.files[0]);
}

function previewEditImage(event, id) {
    var output = document.getElementById('editPreview' + id);
    output.src = URL.createObjectURL(event.target.files[0]);
}

function confirmDelete(formId) {
    if(confirm('Are you sure you want to delete this design?')) {
        document.getElementById(formId).submit();
    }
}
</script>
@endsection
