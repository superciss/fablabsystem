@extends('layouts.main')

@section('title', 'Purchases Management')

@section('content')
<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h2 class="mb-0 text-dark">Purchases</h2>
                <p class="text-muted mb-0">Manage supplier purchases and transactions</p>
            </div>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addPurchaseModal">
            <i class="bi bi-plus-circle"></i> Add Purchase
        </button>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-4">
            <div class="card bg-success bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Paid</p>
                        <h4 class="mb-0">{{ $purchases->where('status','paid')->count() }}</h4>
                    </div>
                    <i class="bi bi-check-circle-fill display-6 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="card bg-warning bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Partial</p>
                        <h4 class="mb-0">{{ $purchases->where('status','partial')->count() }}</h4>
                    </div>
                    <i class="bi bi-hourglass-split display-6 text-warning"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="card bg-danger bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Unpaid</p>
                        <h4 class="mb-0">{{ $purchases->where('status','unpaid')->count() }}</h4>
                    </div>
                    <i class="bi bi-x-circle-fill display-6 text-danger"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark">Purchases List</h5>
            <div class="d-flex gap-2">
                <!-- Supplier Filter -->
                <select id="supplierFilter" class="form-select form-select-sm w-auto">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <button id="filterPaid" class="btn btn-sm btn-outline-success shadow-sm"><i class="bi bi-check-circle"></i></button>
                <button id="filterPartial" class="btn btn-sm btn-outline-warning shadow-sm"><i class="bi bi-hourglass-split"></i></button>
                <button id="filterUnpaid" class="btn btn-sm btn-outline-danger shadow-sm"><i class="bi bi-x-circle"></i></button>
                <button id="resetFilter" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="bi bi-arrow-counterclockwise"></i></button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="purchasesTable">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Remaining</th>
                            <th>Products</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                        <tr>
                            <td class="fw-semibold">#{{ $purchase->id }}</td>
                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</td>
                            <td>
                                @switch($purchase->status)
                                    @case('paid') <span class="badge bg-success">Paid</span> @break
                                    @case('partial') <span class="badge bg-warning text-dark">Partial</span> @break
                                    @case('unpaid') <span class="badge bg-danger">Unpaid</span> @break
                                    @default <span class="badge bg-secondary">{{ ucfirst($purchase->status) }}</span>
                                @endswitch
                            </td>
                            <td class="text-success fw-bold">₱{{ number_format($purchase->total_cost, 2) }}</td>
                            <td class="fw-semibold {{ $purchase->remaining > 0 ? 'text-danger' : 'text-success' }}">
                                ₱{{ number_format($purchase->remaining, 2) }}
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($purchase->items as $item)
                                        <li>
                                            {{ $item->product->name ?? 'N/A' }}
                                            <span class="text-muted">(x{{ $item->quantity }})</span>
                                            <span class="badge bg-light text-dark">₱{{ number_format($item->cost,2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1" data-bs-toggle="modal" data-bs-target="#editPurchaseModal{{ $purchase->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form id="delete-form-{{ $purchase->id }}" action="{{ route('paysupply.destroy', $purchase->id) }}"  method="POST" class="d-inline-block">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-xs d-flex align-items-center gap-1 py-1" 
                                                onclick="confirmDelete('delete-form-{{ $purchase->id }}')">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </form>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        @include('staff.paysupply.editpay', ['purchase'=>$purchase,'suppliers'=>$suppliers,'products'=>$products])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
@include('staff.paysupply.addpay', ['suppliers'=>$suppliers,'products'=>$products])
@endsection

@push('scripts')
<script>
$(function () {
    const table = $('#purchasesTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search purchases...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ purchases",
            paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
        },
        responsive: true,
        ordering: true,
        columnDefs: [{ orderable: false, targets: [6,7] }]
    });

    // Supplier Filter
    $('#supplierFilter').on('change', function () {
        table.column(1).search(this.value).draw();
    });

    // Status Filters
    $('#filterPaid').on('click', () => table.column(3).search("Paid").draw());
    $('#filterPartial').on('click', () => table.column(3).search("Partial").draw());
    $('#filterUnpaid').on('click', () => table.column(3).search("Unpaid").draw());
    $('#resetFilter').on('click', () => {
        $('#supplierFilter').val('');
        table.search('').columns().search('').draw();
    });
});
</script>
@endpush
