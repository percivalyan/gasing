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
                            <h5 class="mb-0">Assessments</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('assessments.create') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Assessment
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            <form method="GET" class="row g-2 mb-3">
                                <div class="col-md-3">
                                    <select name="teacher_id" class="form-control">
                                        <option value="">-- Semua Guru --</option>
                                        @foreach($teachers as $t)
                                            <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="student_course_id" class="form-control">
                                        <option value="">-- Semua Siswa (Course) --</option>
                                        @foreach($students as $tsc)
                                            <option value="{{ $tsc->id }}" {{ request('student_course_id') == $tsc->id ? 'selected' : '' }}>
                                                {{ optional($tsc->studentCourse)->name ?? ($tsc->student_course_id ?? '-') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="subject_id" class="form-control">
                                        <option value="">-- Semua Subject --</option>
                                        @foreach($subjects as $s)
                                            <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-grid">
                                    <button class="btn btn-outline-secondary">Filter</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Teacher</th>
                                            <th>Student (Course)</th>
                                            <th>Subject</th>
                                            <th>Score</th>
                                            <th>Date</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $r)
                                            <tr>
                                                <td>{{ $loop->iteration + ($records->currentPage()-1)*$records->perPage() }}</td>
                                                <td>{{ optional($r->teacherStudentCourse->teacher)->name ?? '-' }}</td>
                                                <td>{{ optional($r->teacherStudentCourse->studentCourse)->name ?? '-' }}</td>
                                                <td>{{ $r->subject->name ?? '-' }}</td>
                                                <td>{{ $r->score !== null ? $r->score : '-' }}</td>
                                                <td>{{ $r->assessment_date->format('Y-m-d') }}</td>
                                                <td class="text-end">
                                                    @if(!empty($PermissionEdit))
                                                        <a href="{{ route('assessments.edit', $r->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                                    @endif
                                                    <a href="{{ route('assessments.show', $r->id) }}" class="btn btn-sm btn-info me-1">View</a>
                                                    @if(!empty($PermissionDelete))
                                                        <form action="{{ route('assessments.delete', $r->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus penilaian ini?')">Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Belum ada penilaian.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $records->withQueryString()->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
@endsection
