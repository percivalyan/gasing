<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $getRecord->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">NIK</label>
    <input type="text" name="nik" class="form-control" value="{{ old('nik', $getRecord->nik ?? '') }}">
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Birth Place</label>
        <input type="text" name="birth_place" class="form-control"
            value="{{ old('birth_place', $getRecord->birth_place ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Birth Date</label>
        <input type="date" name="birth_date" class="form-control"
            value="{{ old('birth_date', $getRecord->birth_date ?? '') }}" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Gender</label>
    <select name="gender" class="form-select" required>
        <option value="M" {{ old('gender', $getRecord->gender ?? '') == 'M' ? 'selected' : '' }}>Male</option>
        <option value="F" {{ old('gender', $getRecord->gender ?? '') == 'F' ? 'selected' : '' }}>Female</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="3">{{ old('address', $getRecord->address ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Origin District</label>
    <input type="text" name="origin_district" class="form-control" value="{{ old('origin_district', $getRecord->origin_district ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">School Level</label>
    <select name="school_level" class="form-select">
        <option value="">Select</option>
        <option value="SD" {{ old('school_level', $getRecord->school_level ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
        <option value="SMP" {{ old('school_level', $getRecord->school_level ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
        <option value="SMA" {{ old('school_level', $getRecord->school_level ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">WhatsApp Number</label>
    <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $getRecord->whatsapp_number ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Dream</label>
    <input type="text" name="dream" class="form-control" value="{{ old('dream', $getRecord->dream ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">School Origin</label>
    <input type="text" name="school_origin" class="form-control" value="{{ old('school_origin', $getRecord->school_origin ?? '') }}">
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control">
        @if (!empty($getRecord->photo))
            <div class="mt-2">
                <a href="{{ asset('storage/' . $getRecord->photo) }}" target="_blank">View Current Photo</a>
            </div>
        @endif
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Letter of Assignment (PDF)</label>
        <input type="file" name="letter_of_assignment" class="form-control">
        @if (!empty($getRecord->letter_of_assignment))
            <div class="mt-2">
                <a href="{{ asset('storage/' . $getRecord->letter_of_assignment) }}" target="_blank">View Current File</a>
            </div>
        @endif
    </div>
</div>

<div class="text-end">
    <a href="{{ route('student_event.list') }}" class="btn btn-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary">
        {{ $action == 'Edit' ? 'Update' : ($action == 'Register' ? 'Register' : 'Submit') }}
    </button>
</div>
