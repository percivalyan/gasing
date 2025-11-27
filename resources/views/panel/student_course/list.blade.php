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
                            <h5 class="mb-0">Student Course</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ url('student-course/add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Student
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" action="{{ route('student_course.list') }}" class="row gy-2 gx-2 mb-3">
                                {{-- Keyword --}}
                                <div class="col-md-4">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Cari nama / NIK / sekolah / distrik"
                                        value="{{ $filter_keyword ?? '' }}">
                                </div>

                                {{-- Filter school level --}}
                                <div class="col-md-2">
                                    <select name="school_level" class="form-select form-select-sm">
                                        <option value="">All Level</option>
                                        <option value="SD"
                                            {{ ($filter_school_level ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP"
                                            {{ ($filter_school_level ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA"
                                            {{ ($filter_school_level ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    </select>
                                </div>

                                {{-- Sort by --}}
                                <div class="col-md-3">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Created At
                                        </option>
                                        <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>
                                            Sort: Name
                                        </option>
                                        <option value="school_level"
                                            {{ ($sort_by ?? '') == 'school_level' ? 'selected' : '' }}>
                                            Sort: School Level
                                        </option>
                                        <option value="nik" {{ ($sort_by ?? '') == 'nik' ? 'selected' : '' }}>
                                            Sort: NIK
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort direction --}}
                                <div class="col-md-2">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC</option>
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>
                                            ASC</option>
                                    </select>
                                </div>

                                {{-- Buttons --}}
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ route('student_course.list') }}"
                                        class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>NIK</th>
                                            <th>Nama Siswa</th>
                                            <th>Birth Date</th>
                                            <th>Gender</th>
                                            <th>School Level</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                {{-- nomor urut mengikuti pagination --}}
                                                <td>{{ $getRecord->firstItem() + $loop->index }}</td>
                                                <td>{{ $value->nik ?? '-' }}</td>
                                                <td>{{ $value->name ?? '-' }}</td>
                                                <td>
                                                    {{ $value->birth_date ? \Carbon\Carbon::parse($value->birth_date)->format('d M Y') : '-' }}
                                                </td>
                                                <td>
                                                    @if ($value->gender === 'M')
                                                        <span class="badge bg-primary">Male</span>
                                                    @elseif($value->gender === 'F')
                                                        <span class="badge bg-info">Female</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $value->school_level ?? '-' }}</td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ url('student-course/edit/' . $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ url('student-course/delete/' . $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Yakin ingin menghapus data ini?');">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Belum ada data Student Course.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $getRecord->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
