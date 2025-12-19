{{-- resources/views/admin/dashboard.blade.php --}}

@extends('layouts.main')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <p class="text-muted">Welcome to CSPC FabLab Inventory & Sales Management System</p>

    <div class="row">
        <!-- Inventory Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Inventory</h5>
                        <p class="mb-0 text-muted">120 Items</p>
                    </div>
                    <i class="bi bi-box-seam fs-1 text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Sales Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Sales</h5>
                        <p class="mb-0 text-muted">₱45,000</p>
                    </div>
                    <i class="bi bi-cash-coin fs-1 text-success"></i>
                </div>
            </div>
        </div>

        <!-- Reports Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Reports</h5>
                        <p class="mb-0 text-muted">5 Generated</p>
                    </div>
                    <i class="bi bi-graph-up fs-1 text-danger"></i>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Users</h5>
                        <p class="mb-0 text-muted">15 Active</p>
                    </div>
                    <i class="bi bi-people fs-1 text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
