@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    @include('panel._message')

                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Penugasan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('event-assign.update', $record->id) }}" method="POST" novalidate>
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teacher Event (Guru)</label>
                                        <select name="teacher_event_id" class="form-control" required>
                                            <option value="">-- Pilih Teacher Event --</option>
                                            @foreach ($teachers as $t)
                                                <option value="{{ $t->id }}"
                                                    {{ old('teacher_event_id', $record->teacher_event_id) == $t->id ? 'selected' : '' }}>
                                                    {{ $t->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('teacher_event_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Student Event</label>
                                        <select name="student_event_id" class="form-control" required>
                                            <option value="">-- Pilih Student Event --</option>
                                            @foreach ($students as $s)
                                                <option value="{{ $s->id }}"
                                                    {{ old('student_event_id', $record->student_event_id) == $s->id ? 'selected' : '' }}>
                                                    {{ $s->name }} @if ($s->school_level)
                                                        ({{ $s->school_level }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_event_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="start_date" class="form-control"
                                            value="{{ old('start_date', $record->start_date ? $record->start_date->format('Y-m-d') : '') }}">
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" name="end_date" class="form-control"
                                            value="{{ old('end_date', $record->end_date ? $record->end_date->format('Y-m-d') : '') }}">
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control" required>
                                            @foreach (['Active', 'Inactive'] as $st)
                                                <option value="{{ $st }}"
                                                    {{ old('status', $record->status) == $st ? 'selected' : '' }}>
                                                    {{ $st }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('event-assign.index') }}" class="btn btn-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
