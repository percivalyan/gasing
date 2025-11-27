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
                            <h5 class="mb-0">Student Events (Admin)</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('student_event.add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Student Event
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            {{-- Filter & Search --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                <div class="col-md-3">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Search name / NIK / school / district"
                                        value="{{ $filter_keyword ?? '' }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <option value="Pending" {{ ($filter_status ?? '') == 'Pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="Accepted"
                                            {{ ($filter_status ?? '') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="Rejected"
                                            {{ ($filter_status ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="school_level" class="form-select form-select-sm">
                                        <option value="">All Level</option>
                                        <option value="SD" {{ ($filter_school_level ?? '') == 'SD' ? 'selected' : '' }}>
                                            SD</option>
                                        <option value="SMP"
                                            {{ ($filter_school_level ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA"
                                            {{ ($filter_school_level ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Created At</option>
                                        <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>Sort: Name
                                        </option>
                                        <option value="school_level"
                                            {{ ($sort_by ?? '') == 'school_level' ? 'selected' : '' }}>Sort: School Level
                                        </option>
                                        <option value="status" {{ ($sort_by ?? '') == 'status' ? 'selected' : '' }}>Sort:
                                            Status</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC</option>
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>ASC
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ route('student_event.list') }}"
                                        class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>NIK</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Birth Place</th>
                                            <th>School Level</th>
                                            <th>School Origin</th>
                                            <th>Status</th>
                                            <th>Photo</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->nik ?? '-' }}</td>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->gender == 'M' ? 'Male' : 'Female' }}</td>
                                                <td>{{ $value->birth_place }}</td>
                                                <td>{{ $value->school_level ?? '-' }}</td>
                                                <td>{{ $value->school_origin ?? '-' }}</td>
                                                <td>
                                                    {{-- Validasi status (Admin) --}}
                                                    @if (!empty($PermissionEdit))
                                                        <form
                                                            action="{{ route('student_event.update_status', $value->id) }}"
                                                            method="POST" class="d-flex align-items-center gap-1">
                                                            @csrf
                                                            @method('PATCH')
                                                            <select name="status" class="form-select form-select-sm"
                                                                onchange="this.form.submit()">
                                                                <option value="Pending"
                                                                    {{ $value->status == 'Pending' ? 'selected' : '' }}>
                                                                    Pending</option>
                                                                <option value="Accepted"
                                                                    {{ $value->status == 'Accepted' ? 'selected' : '' }}>
                                                                    Accepted</option>
                                                                <option value="Rejected"
                                                                    {{ $value->status == 'Rejected' ? 'selected' : '' }}>
                                                                    Rejected</option>
                                                            </select>
                                                        </form>
                                                    @else
                                                        <span
                                                            class="badge
                                                            @if ($value->status == 'Accepted') bg-success
                                                            @elseif($value->status == 'Rejected') bg-danger
                                                            @else bg-secondary @endif">
                                                            {{ $value->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($value->photo)
                                                        <a href="{{ asset('storage/' . $value->photo) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-info">View</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ route('student_event.edit', $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ route('student_event.delete', $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this student event?');">Delete</a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">No Student Event found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Kalau ganti ke paginate(), taruh links di sini --}}
                            {{-- {{ $getRecord->withQueryString()->links() }} --}}
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
