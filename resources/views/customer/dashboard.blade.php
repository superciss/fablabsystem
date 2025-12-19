@extends('layouts.maincustomer')

@section('title', 'Shop')

@section('content')
<div class="container mt-5">

   {{-- 🧭 Dynamic Top 5 Product Carousel --}}
    <div id="shopCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded-4 shadow-lg">
            @php $isFirst = true; @endphp

            @forelse($topProducts as $product)
                @if(strtolower($product->category->name ?? '') !== 'raw material')
                    <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                        @php $isFirst = false; @endphp
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/800x400?text=No+Image' }}" 
                            class="d-block w-100 rounded-4" 
                            alt="{{ $product->name }}" 
                            style="height: 300px; object-fit: cover;">
                        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-3 p-2">
                            <h5 class="fw-bold text-white">{{ $product->name }}</h5>
                        </div>
                    </div>
                @endif
            @empty
                <div class="carousel-item active">
                    <img src="https://via.placeholder.com/800x400?text=No+Products+Available" 
                        class="d-block w-100 rounded-4" alt="No products">
                </div>
            @endforelse
        </div>

        @if($topProducts->where('category.name', '!=', 'Raw Material')->count() > 0)
            <button class="carousel-control-prev" type="button" data-bs-target="#shopCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#shopCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        @endif
    </div>

    {{-- 🔍 Search + Filter + Sort --}}
    <div class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <!-- 🔍 Search Bar -->
            <div class="input-group w-100 w-md-50">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0 shadow-sm" 
                       placeholder="Search products...">
            </div>

            <!-- 🧩 Category Filter -->
            <select id="categoryFilter" class="form-select shadow-sm w-100 w-md-auto" style="max-width: 200px;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
                @endforeach
                <option value="customizable">Customizable Products</option>
            </select>

            <!-- 🔄 Sort Dropdown -->
            <select id="sortFilter" class="form-select shadow-sm w-100 w-md-auto" style="max-width: 200px;">
                <option value="">Sort By</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="rating_desc">Rating: High to Low</option>
                <option value="newest">Newest</option>
            </select>
        </div>
    </div>

    {{-- 🛍️ Product Grid --}}
    <div class="row g-2" id="productList">
        @foreach($products as $product)
        <div class="col-6 col-md-3 product-item" 
             data-name="{{ strtolower($product->name) }}" 
             data-category="{{ strtolower($product->category->name) }}"
             data-price="{{ $product->price }}"
             data-rating="{{ round($product->averageRating(), 1) }}"
             data-date="{{ $product->created_at->timestamp }}"
             data-customizable="{{ $product->is_customizable ? 'true' : 'false' }}">
             
            
            <a href="{{ route('customer.indexview', $product->id) }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm border-0 rounded-4 product-card">
                    <div class="product-img-wrapper position-relative">
                        <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}" 
                             class="card-img-top product-img" alt="{{ $product->name }}">
                        
                        @if($product->stock > 0)
                            <span class="badge stock-badge position-absolute top-0 start-0 m-2">
                                {{ $product->stock }} pcs
                            </span>
                        @else
                            <span class="badge outstock-badge position-absolute top-0 start-0 m-2">
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold text-dark text-truncate">{{ $product->name }}</h6>
                        <p class="card-text text-muted small mb-1 text-truncate">
                            {{ Str::limit($product->description, 40) }}
                        </p>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="text-danger fw-bold fs-6">₱{{ number_format($product->price, 2) }}</span>
                        </div>

                        {{-- ⭐ Rating --}}
                        @php
                            $avg = round($product->averageRating(), 1);
                            $totalRatings = $product->ratings->count();
                        @endphp
                        <div class="rating mb-2">
                            <div class="d-flex align-items-center mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avg))
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif($i - $avg < 1)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-1 small text-muted">
                                    ({{ number_format($avg,1) }}) 
                                    {{ $totalRatings > 0 ? $totalRatings . ' ratings' : 'No ratings' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

{{-- 🧃 Cart Toast --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="cartToast" class="toast align-items-center custom-toast border-0 shadow-lg" role="alert">
    <div class="d-flex">
      <div class="toast-body">🛒 Product added to cart!</div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");
    const sortFilter = document.getElementById("sortFilter");
    const productList = document.getElementById("productList");
    const products = Array.from(document.querySelectorAll(".product-item"));

    function filterAndSort() {
        const search = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();
        const sort = sortFilter.value;

        const filtered = products.filter(p => {
            const name = p.dataset.name;
            const cat = p.dataset.category;
            const isCustom = p.dataset.customizable; //  get customizable status

            const matchesSearch = name.includes(search);

            //  include customizable logic
            const matchesCategory = 
                !category || 
                cat === category || 
                (category === "customizable" && isCustom === "true");

            return matchesSearch && matchesCategory;
        });

        //  Sorting logic
        filtered.sort((a, b) => {
            switch (sort) {
                case 'price_asc': return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price_desc': return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'rating_desc': return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                case 'newest': return parseFloat(b.dataset.date) - parseFloat(a.dataset.date);
                default: return 0;
            }
        });

    
        productList.innerHTML = "";
        filtered.forEach(p => productList.appendChild(p));
    }


    searchInput.addEventListener("input", filterAndSort);
    categoryFilter.addEventListener("change", filterAndSort);
    sortFilter.addEventListener("change", filterAndSort);
});
</script>
@endpush


@push('styles')
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
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: url('/images/logo.png') center/cover no-repeat;
    opacity: 0.25; filter: blur(1px);
    z-index: -1;
}

/* Carousel */
#shopCarousel img {
    height: 250px;
    object-fit: cover;
}
.carousel-caption {
    bottom: 20px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 12px;
}
.carousel-caption h5, .carousel-caption p {
    margin: 0;
}
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(1);
}

/* Search & Filters */
.input-group-text {
    border-radius: 8px 0 0 8px;
}
.form-control, .form-select {
    border-radius: 0 8px 8px 0;
    border: 1px solid #ced4da;
}
#searchInput:focus, #categoryFilter:focus, #sortFilter:focus {
    border-color: #ff6600;
    box-shadow: 0 0 0 0.2rem rgba(255,102,0,0.25);
}

/* Product Cards */
.product-card {
    background: #fff;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.product-img-wrapper {
    height: 150px;
    overflow: hidden;
    border-radius: 0.75rem 0.75rem 0 0;
}
.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.product-img-wrapper:hover .product-img {
    transform: scale(1.05);
}
.stock-badge {
    background: linear-gradient(135deg, #1e3a8a, #2563eb);
    font-size: 0.6rem;
    padding: 0.2rem 0.4rem;
    border-radius: 0.3rem;
    color: #fff;
}
.outstock-badge {
    background: linear-gradient(135deg, #6b7280, #9ca3af);
    font-size: 0.6rem;
    padding: 0.2rem 0.4rem;
    border-radius: 0.3rem;
    color: #fff;
}
.custom-toast {
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: #fff;
    font-weight: 500;
    border-radius: 0.5rem;
    font-size: 0.8rem;
}
.custom-toast .btn-close {
    filter: invert(1);
}
</style>
@endpush
