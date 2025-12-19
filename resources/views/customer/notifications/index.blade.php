@extends('layouts.maincustomer')

@section('content')
<style>
    body {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, rgba(18, 50, 80, 1), rgba(26, 62, 99, 1));
    overflow-x: hidden;
    opacity: 1;
}

/* Blurred Background Image Overlay */
body::before {
    content: "";
    position: fixed; /* ✅ fixed so it won’t move on scroll */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('/images/logo.png') center/cover no-repeat;
    opacity: 0.25; /* Adjust transparency */
    filter: blur(1px); /* Adjust blur intensity */
    z-index: -1; /* Stay behind content */
}
</style>
<div class="container">
   

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($notifications as $notification)
        <div class="card mb-3 shadow-sm @if(!$notification->is_read) border-warning @endif">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-1">
                        @if($notification->is_read)
                            <span class="badge bg-success">Seen</span>
                        @else
                            <span class="badge bg-warning text-dark">Unseen</span>
                        @endif
                        <small class="text-muted ms-2">
                             {{ $notification->updated_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                        </small>
                    </h6>

                    <!-- Preview -->
                    <p class="mb-0 text-muted" id="preview-{{ $notification->id }}">
                        {{ \Illuminate\Support\Str::limit($notification->message, 60, '...') }}
                    </p>

                    <!-- Full message hidden initially -->
                    <p class="mb-0" id="full-{{ $notification->id }}" style="display:none;">
                        {{ $notification->message }}
                    </p>
                </div>

                <div class="ms-3">
                    @if(!$notification->is_read)
                        <button class="btn btn-sm btn-primary read-btn"
                            data-id="{{ $notification->id }}">
                            Read Now
                        </button>
                    @else
                        <button class="btn btn-sm btn-outline-secondary open-btn"
                            data-id="{{ $notification->id }}">
                            Open
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted">No notifications found.</div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle first-time "Read Now"
    document.querySelectorAll('.read-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            let id = this.dataset.id;

            fetch(`/customer/notifications/read/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // show full message
                    document.getElementById('preview-' + id).style.display = 'none';
                    document.getElementById('full-' + id).style.display = 'block';

                    // update button + badge
                    this.outerHTML = `<button class="btn btn-sm btn-outline-secondary open-btn" data-id="${id}">Open</button>`;
                    this.closest('.card-body').querySelector('.badge').outerHTML =
                        '<span class="badge bg-success">Read</span>';

                    // re-attach event for "Open" button
                    attachOpenButtons();
                }
            });
        });
    });

    // Handle re-opening messages
    function attachOpenButtons() {
        document.querySelectorAll('.open-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                let id = this.dataset.id;
                document.getElementById('preview-' + id).style.display = 'none';
                document.getElementById('full-' + id).style.display = 'block';
            });
        });
    }

    attachOpenButtons();
});
</script>
@endsection
