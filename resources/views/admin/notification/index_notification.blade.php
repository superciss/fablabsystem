@extends('layouts.main')

@section('content')
<div class="container py-5">
    <h3 class="mb-4 text-primary"><i class="bi bi-exclamation-triangle-fill"></i> Low Stock Notifications</h3>

    @if($lowProducts->count() > 0)
        <div class="row g-3">
            @foreach($lowProducts as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-danger h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <span class="badge bg-danger">{{ $product->stock }}</span>
                            </div>
                            <p class="card-text mt-2">
                                @if($product->stock == 0)
                                    <span class="text-danger fw-bold">Out of Stock</span>
                                @else
                                    <span class="text-warning fw-bold">Low Stock</span>
                                @endif
                            </p>
                        </div>
                        <div class="card-footer text-muted text-end">
                            <small><i class="bi bi-clock"></i> Updated just now</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-check-circle-fill"></i> No low stock items.
        </div>
    @endif
</div>
@endsection
