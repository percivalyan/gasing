@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Student Course</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('student-course/edit/' . $getRecord->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $getRecord->name }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <input type="text" name="nik" class="form-control" value="{{ $getRecord->nik }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Birth Place</label>
                                    <input type="text" name="birth_place" class="form-control"
                                        value="{{ $getRecord->birth_place }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Birth Date</label>
                                    <input type="date" name="birth_date" class="form-control"
                                        value="{{ $getRecord->birth_date }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="M" {{ $getRecord->gender == 'M' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="F" {{ $getRecord->gender == 'F' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control">{{ $getRecord->address }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Origin District</label>
                                    <input type="text" name="origin_district" class="form-control"
                                        value="{{ $getRecord->origin_district }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">School Level</label>
                                    <select name="school_level" class="form-control">
                                        <option value="">-- Select --</option>
                                        <option value="SD" {{ $getRecord->school_level == 'SD' ? 'selected' : '' }}>SD
                                        </option>
                                        <option value="SMP" {{ $getRecord->school_level == 'SMP' ? 'selected' : '' }}>
                                            SMP
                                        </option>
                                        <option value="SMA" {{ $getRecord->school_level == 'SMA' ? 'selected' : '' }}>
                                            SMA
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Whatsapp Number</label>
                                    <input type="text" name="whatsapp_number" class="form-control"
                                        value="{{ $getRecord->whatsapp_number }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dream</label>
                                    <input type="text" name="dream" class="form-control"
                                        value="{{ $getRecord->dream }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">School Origin</label>
                                    <input type="text" name="school_origin" class="form-control"
                                        value="{{ $getRecord->school_origin }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fee Note</label>
                                    <select name="fee_note" class="form-control">
                                        <option value="yellow" {{ $getRecord->fee_note == 'yellow' ? 'selected' : '' }}>
                                            Yellow</option>
                                        <option value="red" {{ $getRecord->fee_note == 'red' ? 'selected' : '' }}>Red
                                        </option>
                                        <option value="green" {{ $getRecord->fee_note == 'green' ? 'selected' : '' }}>
                                            Green
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Note</label>
                                    <input type="text" name="note" class="form-control"
                                        value="{{ $getRecord->note }}">
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('student-course') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
