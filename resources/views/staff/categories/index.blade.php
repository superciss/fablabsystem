@extends('layouts.main')

@section('title', 'Categories Management')

@section('content')
<div class="container-fluid px-4" style="margin-left: 20px;">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary rounded-circle p-2 lh-1">
                <i class="bi bi-arrow-left fs-5"></i> 
            </a>
            <div>
                <h2 class="mb-0 text-dark">Categories Management</h2>
                <p class="text-muted mb-0">Organize your products with categories and subcategories</p>
            </div>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg"></i> Add New Category
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Total Categories</p>
                            <h4 class="mb-0">{{ $categories->count() }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-tag display-6 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Active Categories</p>
                            <h4 class="mb-0">{{ $categories->count() }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle display-6 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Products in Categories</p>
                            <h4 class="mb-0">156</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-box display-6 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info bg-opacity-10 border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Recently Added</p>
                            <h4 class="mb-0">3</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-clock-history display-6 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Table Card -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header py-3 d-flex justify-content-between align-items-center text-white"
     style="background: linear-gradient(90deg, rgb(0,123,255), rgba(26, 45, 151, 1), rgb(132,0,255));">


            <h5 class="mb-0 text-dark">Category Directory</h5>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="categoriesTable" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="px-2">#</th>
                            <th class="px-2">Category</th>
                            <th class="px-2">Description</th>
                            <th class="px-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td class="px-2">{{ $loop->iteration }}</td>
                            <td class="px-2">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-tag text-primary"></i>
                                    </div>
                                    <h6 class="mb-0 small">{{ $category->name }}</h6>
                                </div>
                            </td>
                            <td class="px-2 small">{{ $category->description ?? 'N/A' }}</td>

                          <td class="px-2 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Edit Button -->
                                    <button class="btn btn-outline-warning btn-xs d-flex align-items-center gap-1 py-1" 
                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                        <i class="bi bi-pencil-square"></i> 
                                    </button>

                                    <form id="delete-form-{{ $category->id }}" action="{{ route('staffcategories.destroy', $category->id) }}"  method="POST" class="d-inline-block">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-xs d-flex align-items-center gap-1 py-1" 
                                                onclick="confirmDelete('delete-form-{{ $category->id }}')">
                                            <i class="bi bi-trash"></i> 
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        {{-- Edit Modal --}}
                        @include('staff.categories.editcategory', ['category' => $category])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Modal --}}
@include('staff.categories.addcategory')
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
    
    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 0.75rem 0.5rem;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem 0.5rem;
    }
    
    .table-sm td {
        padding: 0.5rem;
    }
    
    #categoriesTable_filter input {
        border-radius: 0.5rem;
        padding: 0.4rem 0.8rem;
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
    }
    
    .dataTables_info, .dataTables_paginate {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .alert {
        border: none;
        border-radius: 0.75rem;
    }
    
    .bg-opacity-10 {
        background-opacity: 0.1;
    }
    
    .small {
        font-size: 0.875rem;
    }
</style>
@endpush


@push('scripts')
<script>
    $(document).ready(function() {
        $('#categoriesTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search categories...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ categories",
                infoEmpty: "Showing 0 to 0 of 0 categories",
                infoFiltered: "(filtered from _MAX_ total categories)",
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

        $('.dataTables_filter input').addClass('form-control form-control-sm');
        $('.dataTables_length select').addClass('form-select form-select-sm');
    });
</script>
@endpush
