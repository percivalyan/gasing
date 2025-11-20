@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    @include('panel._message')
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Add Assessment</h5>
                            <a href="{{ route('assessments.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('assessments.store') }}" method="POST" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Teacher ↔ Student (assignment)</label>
                                    <select name="teacher_student_course_id" class="form-control" required>
                                        <option value="">-- Pilih assignment --</option>
                                        @foreach($teacherStudentCourses as $t)
                                            <option value="{{ $t->id }}" {{ old('teacher_student_course_id') == $t->id ? 'selected' : '' }}>
                                                {{ optional($t->teacher)->name ?? '-' }} — {{ optional($t->studentCourse)->name ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_student_course_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Subject (optional)</label>
                                    <select name="subject_id" class="form-control">
                                        <option value="">-- Pilih subject --</option>
                                        @foreach($subjects as $s)
                                            <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Score (0-100)</label>
                                    <input type="number" name="score" class="form-control" value="{{ old('score') }}" min="0" max="100">
                                    @error('score') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Assessment Date</label>
                                    <input type="date" name="assessment_date" class="form-control" value="{{ old('assessment_date', now()->toDateString()) }}" required>
                                    @error('assessment_date') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                                    @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('assessments.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Assessment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
@endsection
