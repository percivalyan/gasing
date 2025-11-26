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
                        <h5 class="mb-0">Teacher Events (Admin)</h5>
                        @if (!empty($PermissionAdd))
                            <a href="{{ route('teacher_event.add') }}" class="btn btn-sm btn-primary">
                                <i class="feather icon-plus-circle me-1"></i> Add Teacher Event
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        {{-- Filter & Search --}}
                        <form method="GET" class="row gy-2 gx-2 mb-3">
                            <div class="col-md-3">
                                <input type="text" name="keyword" class="form-control form-control-sm"
                                    placeholder="Search name / NIP / school / expertise"
                                    value="{{ $filter_keyword ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="Pending" {{ ($filter_status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Accepted" {{ ($filter_status ?? '') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="Rejected" {{ ($filter_status ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="sort_by" class="form-select form-select-sm">
                                    <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>Sort: Created At</option>
                                    <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>Sort: Name</option>
                                    <option value="expertise_field" {{ ($sort_by ?? '') == 'expertise_field' ? 'selected' : '' }}>Sort: Expertise</option>
                                    <option value="status" {{ ($sort_by ?? '') == 'status' ? 'selected' : '' }}>Sort: Status</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="sort_direction" class="form-select form-select-sm">
                                    <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>DESC</option>
                                    <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>ASC</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                <a href="{{ route('teacher_event.list') }}" class="btn btn-sm btn-light w-100">Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>NIP</th>
                                        <th>Expertise</th>
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
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->gender == 'M' ? 'Male' : 'Female' }}</td>
                                            <td>{{ $value->nip ?? '-' }}</td>
                                            <td>{{ $value->expertise_field ?? '-' }}</td>
                                            <td>{{ $value->school_origin ?? '-' }}</td>
                                            <td>
                                                @if (!empty($PermissionEdit))
                                                    <form action="{{ route('teacher_event.update_status', $value->id) }}"
                                                          method="POST" class="d-flex align-items-center">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" class="form-select form-select-sm"
                                                                onchange="this.form.submit()">
                                                            <option value="Pending" {{ $value->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="Accepted" {{ $value->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                                            <option value="Rejected" {{ $value->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                        </select>
                                                    </form>
                                                @else
                                                    <span class="badge
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
                                                        <a href="{{ route('teacher_event.edit', $value->id) }}"
                                                           class="btn btn-sm btn-warning me-1">Edit</a>
                                                    @endif
                                                    @if (!empty($PermissionDelete))
                                                        <a href="{{ route('teacher_event.delete', $value->id) }}"
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Are you sure you want to delete this teacher event?');">
                                                            Delete
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No Teacher Event found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Jika nanti pakai paginate():
                        {{ $getRecord->withQueryString()->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
