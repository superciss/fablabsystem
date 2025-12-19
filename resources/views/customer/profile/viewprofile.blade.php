<!-- Profile View Modal -->
<div class="modal fade" id="profileViewModal" tabindex="-1" aria-labelledby="profileViewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="profileViewModalLabel">Profile Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="text-center mb-3">
        <img src="{{ optional(auth()->user()->userInformation)->photo 
                    ?? 'https://via.placeholder.com/300x200' }}"
            alt="Profile Photo"
            class="rounded-circle mb-2"
            width="90" height="90"
            style="object-fit: cover;">

            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
            <small class="text-muted d-block mb-2">{{ auth()->user()->email }}</small>
        </div>

        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Fullname:</strong> {{ optional(auth()->user()->userInformation)->fullname ?? '-' }}</li>
            <li class="list-group-item"><strong>Address:</strong> {{ optional(auth()->user()->userInformation)->address ?? '-' }}</li>
            <li class="list-group-item"><strong>Contact Number:</strong> {{ optional(auth()->user()->userInformation)->contact_number ?? '-' }}</li>
            <li class="list-group-item"><strong>Degree:</strong> {{ optional(auth()->user()->userInformation)->degree ?? '-' }}</li>
            <li class="list-group-item"><strong>Year:</strong> {{ optional(auth()->user()->userInformation)->year ?? '-' }}</li>
            <li class="list-group-item"><strong>Section:</strong> {{ optional(auth()->user()->userInformation)->section ?? '-' }}</li>
            <li class="list-group-item"><strong>Gender:</strong> {{ optional(auth()->user()->userInformation)->gender ?? '-' }}</li>
        </ul>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-info w-100" 
                data-bs-toggle="modal" 
                data-bs-target="#profileInfoModal" 
                data-bs-dismiss="modal">
            <i class="bi bi-pencil-square me-1"></i> Edit Profile
        </button>
      </div>
    </div>
  </div>
</div>
