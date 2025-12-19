@php 
    $cart = session('cart', []);
    $cartCount = collect($cart)->sum('quantity');
@endphp

<!-- 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->


<nav class="navbar navbar-expand-lg navbar-dark navbar-navy fixed-top shadow-sm">
    <div class="container-fluid">
        <!-- Sidebar Toggle (Admin & Staff only, desktop only) -->
        @auth
            @if(auth()->user()->role !== 'customer')
              <!-- Desktop Sidebar Toggle -->
                <!-- <button id="sidebarToggle" class="btn btn-light btn-sm ms-2 d-none d-md-inline">
                    <i id="sidebarToggleIcon" class="fa-solid fa-arrow-left"></i>
                </button> -->

            @endif
        @endauth

        <!-- Brand -->
        <a class="navbar-brand fw-bold d-flex align-items-center brand-yellow me-auto">
            CSPC FabLab
        </a>
        
        <!-- Toggler (mobile hamburger) -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-3">

           @auth
                @if(auth()->user()->role !== 'customer')
                    <!-- Admin & Staff Bell -->
                    <li class="nav-item">
                        <a href="{{ route('notifications.index_notify') }}" class="nav-link" title="Notifications">
                            <div class="nav-icon position-relative">
                                <i class="bi bi-bell fs-3"></i> <!-- Bigger icon -->
                                <span id="staff-notif-count" 
                                    class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle"
                                    style="font-size: 0.9rem; min-width: 22px; height: 22px; display:none; font-weight:bold;">
                                    0
                                </span>
                            </div>
                        </a>
                    </li>
                @endif
            @endauth


                @auth
                    @if(auth()->user()->role === 'customer')
                        <!-- Shop -->
                        <li class="nav-item">
                            <a href="{{ route('customer.dashboard') }}" class="nav-link" title="Shop">
                                <i class="bi bi-shop fs-5"></i>
                            </a>
                        </li>
                        <!-- Customization -->
                        <!-- <li class="nav-item">
                            <a href="{{ route('customer.custom.create') }}" class="nav-link" title="Customization">
                                <i class="fa-solid fa-star fs-5"></i>
                            </a>
                        </li> -->
                       
                        <!-- Notifications -->
                        <li class="nav-item">
                            <a href="{{ route('customer.notifications.index') }}" class="nav-link" title="Notifications">
                                <div class="nav-icon position-relative">
                                    <i class="bi bi-bell fs-5"></i>
                                    <span id="notif-count" 
                                          class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle"
                                          style="font-size: 0.7rem; display:none;">
                                          0
                                    </span>
                                </div>
                            </a>
                        </li>
                        <!-- Cart -->
                        <li class="nav-item">
                            <a href="{{ route('customer.cart.index') }}" class="nav-link" title="Cart">
                                <div class="nav-icon position-relative">
                                    <i class="fa-solid fa-cart-shopping fs-5"></i>
                                    <span id="cart-count" 
                                          class="badge rounded-pill bg-warning position-absolute top-0 start-100 translate-middle"
                                          style="font-size: 0.7rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">
                                          {{ $cartCount }}
                                    </span>
                                </div>
                            </a>
                        </li>
                        <!-- Orders -->
                        <li class="nav-item">
                            <a href="{{ route('customer.orderlist.index') }}" class="nav-link" title="Orders">
                                <i class="fa-solid fa-bag-shopping fs-5"></i>
                            </a>
                        </li>
                      
                         <!-- <li class="nav-item">
                            <a href="{{ route('customer.customized.index') }}" class="nav-link" title="Customized Product">
                                <i class="fa-solid fa-sliders fs-5"></i>
                            </a>
                        </li> -->

                         <li class="nav-item">
                            <a href="{{ route('customer.customproduct.index') }}" class="nav-link" title="Order Customized Product">
                               <i class="fa-solid fa-palette fs-5"></i>
                            </a>
                        </li>
                          
                        <li class="nav-item">
                            <a href="{{ route('personal-designs.index') }}" class="nav-link" title="Personal Designs">
                               <i class="bi bi-brush"></i> 
                            </a>
                        </li>

                        
                        <!-- Profile Dropdown -->
                        <li class="nav-item position-relative">
                            <a href="#" class="nav-link" id="profileToggle" title="Profile">
                            <img src="{{ optional(auth()->user()->userInformation)->photo 
                                                ?? 'https://via.placeholder.com/300x200' }}"
                                        alt="Profile Photo"
                                        class="rounded-circle mb-2"
                                        width="40" height="40"
                                        style="object-fit: cover;">

                            </a>
                            <div id="profileCard" class="card shadow-lg border-0 position-absolute end-0 mt-2"
                                 style="width: 250px; display: none; z-index: 1050;">
                                <div class="card-body text-center">
                                    <img src="{{ optional(auth()->user()->userInformation)->photo 
                                                ?? 'https://via.placeholder.com/300x200' }}"
                                        alt="Profile Photo"
                                        class="rounded-circle mb-2"
                                        width="40" height="40"
                                        style="object-fit: cover;">

                                    <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                    <small class="text-muted d-block mb-3">{{ auth()->user()->email }}</small>
                                   <button type="button" class="btn btn-sm btn-outline-info w-100 mb-2" 
                                        data-bs-toggle="modal" data-bs-target="#profileViewModal">
                                    <i class="bi bi-eye me-1"></i> View Profile
                                </button>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endif
                @else
                    <!-- Login -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" 
                           href="{{ route('login') }}" title="Login">
                            <i class="fa-solid fa-right-to-bracket fs-5"></i>
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Include the Profile Modal separately -->
@include('customer.profile.profile')
@include('customer.profile.viewprofile')

<style>
.navbar-navy {
    background: linear-gradient(90deg, #1e3a8a, #082b64ff);
    border-bottom: 1px solid rgba(255,255,255,0.15);
}
.brand-yellow { color: #facc15 !important; }
.navbar .nav-link { color: rgba(255,255,255,0.85); transition: color 0.3s ease-in-out; display: flex; align-items: center; justify-content: center; }
.navbar .nav-link i { color: #fff; transition: color 0.3s ease-in-out; }
.navbar .nav-link:hover i { color: #facc15 !important; }
#cart-count { font-size: 0.7rem; font-weight: bold; color: #000; padding: 3px 6px; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; }
</style>

 <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

     -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Profile Dropdown Toggle
    const toggle = document.getElementById("profileToggle");
    const card = document.getElementById("profileCard");
    if(toggle && card){
        toggle.addEventListener("click", function (e) {
            e.preventDefault();
            card.style.display = (card.style.display === "none" || card.style.display === "") ? "block" : "none";
        });
        document.addEventListener("click", function (e) {
            if (!toggle.contains(e.target) && !card.contains(e.target)) card.style.display = "none";
        });
    }

    // Sidebar toggle (admin/staff desktop only)
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.querySelector(".sidebar");
    const sidebarIcon = document.getElementById("sidebarToggleIcon");
    if(sidebarToggle && sidebar){
        sidebarToggle.addEventListener("click", function(){
            sidebar.classList.toggle("collapsed");
            if(sidebar.classList.contains("collapsed")){
                sidebarIcon.classList.remove("fa-arrow-left");
                sidebarIcon.classList.add("fa-arrow-right");
            } else {
                sidebarIcon.classList.remove("fa-arrow-right");
                sidebarIcon.classList.add("fa-arrow-left");
            }
        });
    }

    // Fetch Notification Count
    async function fetchNotifCount() {
        try {
            let res = await fetch("{{ route('notifications.count') }}");
            let data = await res.json();
            let badge = document.getElementById("notif-count");
            if(badge){
                badge.style.display = data.count > 0 ? "inline-block" : "none";
                badge.textContent = data.count;
            }
        } catch(e){ console.error(e) }
    }
    fetchNotifCount(); setInterval(fetchNotifCount, 10000);

    // Fetch Cart Count
    async function fetchCartCount() {
        try {
            let res = await fetch("{{ route('cart.count') }}");
            let data = await res.json();
            let badge = document.getElementById("cart-count");
            if(badge){
                badge.style.display = data.count > 0 ? "inline-flex" : "none";
                badge.textContent = data.count;
            }
        } catch(e){ console.error(e) }
    }
    fetchCartCount(); setInterval(fetchCartCount, 10000);
});

document.addEventListener("DOMContentLoaded", function () {
    async function fetchStaffNotifCount() {
        try {
            let res = await fetch("{{ route('notifications.counts') }}"); // Admin/Staff route
            let data = await res.json();
            let badge = document.getElementById("staff-notif-count");
            if(badge){
                badge.style.display = data.count > 0 ? "inline-block" : "none";
                badge.textContent = data.count;
            }
        } catch(e){ console.error(e) }
    }
    fetchStaffNotifCount();
    setInterval(fetchStaffNotifCount, 10000);
});

</script>
