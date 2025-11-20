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
                        <h5 class="mb-0">Teacher Events</h5>
                        @if (!empty($PermissionAdd))
                            <a href="{{ route('teacher_event.add') }}" class="btn btn-sm btn-primary">
                                <i class="feather icon-plus-circle me-1"></i> Add Teacher Event
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
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
                                                @if ($value->photo)
                                                    <a href="{{ asset('storage/' . $value->photo) }}" target="_blank" class="btn btn-sm btn-outline-info">View</a>
                                                @endif
                                            </td>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <td class="text-end">
                                                    @if (!empty($PermissionEdit))
                                                        <a href="{{ route('teacher_event.edit', $value->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                                    @endif
                                                    @if (!empty($PermissionDelete))
                                                        <a href="{{ route('teacher_event.delete', $value->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this teacher event?');">Delete</a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No Teacher Event found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
