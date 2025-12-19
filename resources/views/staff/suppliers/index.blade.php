@extends('layouts.main')

@section('title', 'Suppliers Management')

@section('content')
<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1">
                <i class="bi bi-arrow-left fs-5"></i> 
            </a>
            <div>
                <h2 class="mb-0 text-dark">Suppliers Management</h2>
                <p class="text-muted mb-0">Manage your vendor relationships and supplier information</p>
            </div>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="bi bi-plus-lg"></i> Add New Supplier
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Total Suppliers</p>
                        <h4 class="mb-0">{{ $suppliers->count() }}</h4>
                    </div>
                    <i class="bi bi-truck display-6 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">Active Suppliers</p>
                        <h4 class="mb-0">{{ $suppliers->count() }}</h4>
                    </div>
                    <i class="bi bi-check-circle display-6 text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-dark">Supplier Directory</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="suppliersTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">#</th>
                            <th class="px-3">Supplier</th>
                            <th class="px-3">Contact Person</th>
                            <th class="px-3">Email</th>
                            <th class="px-3">Phone</th>
                            <th class="px-3">Address</th>
                            <th class="px-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                        <tr>
                            <td class="px-3"><span class="fw-semibold text-muted">#{{ $supplier->id }}</span></td>
                            <td class="px-3">
                                
                                    <div class="px-3">
                                        <h6 class="mb-0">{{ $supplier->name }}</h6>
                                    </div>
                            
                            </td>
                            <td class="px-3">{{ $supplier->contact_person ?? 'N/A' }}</td>
                            <td class="px-3">
                                @if($supplier->email)
                                    <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">{{ $supplier->email }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-3">
                                @if($supplier->phone)
                                    <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">{{ $supplier->phone }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-3">{{ Str::limit($supplier->address ?? 'N/A', 25) }}</td>
                            <td class="px-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1" 
                                            data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form id="delete-form-{{ $supplier->id }}" action="{{ route('staffsupplier.destroy', $supplier->id) }}"  method="POST" class="d-inline-block">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-xs d-flex align-items-center gap-1 py-1" 
                                                onclick="confirmDelete('delete-form-{{ $supplier->id }}')">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        @include('staff.suppliers.editsupplier', ['supplier' => $supplier])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
@include('staff.suppliers.addsupplier')
@endsection

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border-radius: 0.75rem;
    }
    .btn {
        border-radius: 0.5rem;
        font-weight: 500;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    #suppliersTable_filter input {
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        border: 1px solid #dee2e6;
    }
    .dataTables_info, .dataTables_paginate {
        padding: 0.75rem 1.5rem;
    }
    .alert {
        border: none;
        border-radius: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#suppliersTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search suppliers...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ suppliers",
                infoEmpty: "Showing 0 to 0 of 0 suppliers",
                infoFiltered: "(filtered from _MAX_ total suppliers)",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            pageLength: 10,
            responsive: true,
            ordering: true,
            order: [[1, 'asc']],
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: -1 },
                { orderable: false, targets: -1 }
            ]
        });

        // Style DataTable input
        $('.dataTables_filter input').addClass('form-control');
        $('.dataTables_length select').addClass('form-select');
    });
</script>
@endpush
