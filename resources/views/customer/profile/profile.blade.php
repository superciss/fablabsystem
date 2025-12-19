<!-- Edit Profile Modal -->
<div class="modal fade" id="profileInfoModal" tabindex="-1" aria-labelledby="profileInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title" id="profileInfoModalLabel">Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

        <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="file" class="form-control" name="photo">             
        </div>
            <div class="mb-3">
                <label class="form-label">Fullname</label>
                <input type="text" class="form-control" name="fullname" 
                       value="{{ old('fullname', optional(auth()->user()->information)->fullname) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address">{{ old('address', optional(auth()->user()->information)->address) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" class="form-control" name="contact_number" 
                       value="{{ old('contact_number', optional(auth()->user()->information)->contact_number) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Degree</label>
                <input type="text" class="form-control" name="degree" 
                       value="{{ old('degree', optional(auth()->user()->information)->degree) }}">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Year</label>
                    <input type="number" class="form-control" name="year" 
                           value="{{ old('year', optional(auth()->user()->information)->year) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Section</label>
                    <input type="text" class="form-control" name="section" 
                           value="{{ old('section', optional(auth()->user()->information)->section) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select class="form-select" name="gender">
                    <option value="">-- Select Gender --</option>
                    <option value="male" {{ old('gender', optional(auth()->user()->information)->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', optional(auth()->user()->information)->gender) == 'female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
