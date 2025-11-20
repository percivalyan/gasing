@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Add Lesson Schedule</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('lesson-schedule/insert') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Teacher (Guru Les Gasing)</label>
                                    <select name="teacher_id" class="form-control" required>
                                        <option value="">-- Select Teacher --</option>
                                        @foreach ($teachers as $t)
                                            <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">School Level</label>
                                    <select name="school_level" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        <option value="SD"  {{ old('school_level') == 'SD'  ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('school_level') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('school_level') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Subject Name</label>
                                    <input type="text" name="subject_name" class="form-control" value="{{ old('subject_name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Day of Week</label>
                                    <select name="day_of_week" class="form-control" required>
                                        <option value="">-- Select --</option>
                                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $d)
                                            <option value="{{ $d }}" {{ old('day_of_week') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Room</label>
                                    <input type="text" name="room" class="form-control" value="{{ old('room') }}">
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('lesson-schedule') }}" class="btn btn-secondary me-2">Cancel</a>
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
