@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Add Student Course</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('student-course/insert') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <input type="text" name="nik" class="form-control" value="{{ old('nik') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Birth Place</label>
                                    <input type="text" name="birth_place" class="form-control"
                                        value="{{ old('birth_place') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Birth Date</label>
                                    <input type="date" name="birth_date" class="form-control"
                                        value="{{ old('birth_date') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Origin District</label>
                                    <input type="text" name="origin_district" class="form-control"
                                        value="{{ old('origin_district') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">School Level</label>
                                    <select name="school_level" class="form-control">
                                        <option value="">-- Select --</option>
                                        <option value="SD" {{ old('school_level') == 'SD' ? 'selected' : '' }}>SD
                                        </option>
                                        <option value="SMP" {{ old('school_level') == 'SMP' ? 'selected' : '' }}>SMP
                                        </option>
                                        <option value="SMA" {{ old('school_level') == 'SMA' ? 'selected' : '' }}>SMA
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Whatsapp Number</label>
                                    <input type="text" name="whatsapp_number" class="form-control"
                                        value="{{ old('whatsapp_number') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dream</label>
                                    <input type="text" name="dream" class="form-control" value="{{ old('dream') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">School Origin</label>
                                    <input type="text" name="school_origin" class="form-control"
                                        value="{{ old('school_origin') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fee Note</label>
                                    <select name="fee_note" class="form-control">
                                        <option value="yellow" {{ old('fee_note') == 'yellow' ? 'selected' : '' }}>Yellow
                                        </option>
                                        <option value="red" {{ old('fee_note') == 'red' ? 'selected' : '' }}>Red
                                        </option>
                                        <option value="green" {{ old('fee_note') == 'green' ? 'selected' : '' }}>Green
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <input type="text" name="note" class="form-control" value="{{ old('note') }}">
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('student-course') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
