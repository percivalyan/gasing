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
                            <h5 class="mb-0">Assessment Detail</h5>
                            <a href="{{ route('assessments.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Teacher</dt>
                                <dd class="col-sm-9">{{ optional($record->teacherStudentCourse->teacher)->name ?? '-' }}</dd>

                                <dt class="col-sm-3">Student (Course)</dt>
                                <dd class="col-sm-9">{{ optional($record->teacherStudentCourse->studentCourse)->name ?? '-' }}</dd>

                                <dt class="col-sm-3">Subject</dt>
                                <dd class="col-sm-9">{{ $record->subject->name ?? '-' }}</dd>

                                <dt class="col-sm-3">Score</dt>
                                <dd class="col-sm-9">{{ $record->score !== null ? $record->score : '-' }}</dd>

                                <dt class="col-sm-3">Date</dt>
                                <dd class="col-sm-9">{{ $record->assessment_date->format('Y-m-d') }}</dd>

                                <dt class="col-sm-3">Assessor</dt>
                                <dd class="col-sm-9">{{ optional($record->assessor)->name ?? '-' }}</dd>

                                <dt class="col-sm-3">Notes</dt>
                                <dd class="col-sm-9">{!! nl2br(e($record->notes)) ?? '-' !!}</dd>
                            </dl>

                            <div class="text-end">
                                <a href="{{ route('assessments.edit', $record->id) }}" class="btn btn-warning me-2">Edit</a>
                                <a href="{{ route('assessments.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
@endsection
