@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="mb-2">User Management</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                                </li>
                                <li class="breadcrumb-item active">User</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    @include('panel._message')

                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">List of Users</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add User
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- FILTER, SEARCH, SORTING --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                <div class="col-md-4">
                                    <input type="text"
                                        name="keyword"
                                        class="form-control form-control-sm"
                                        placeholder="Search name / email / role"
                                        value="{{ $filter_keyword ?? '' }}">
                                </div>

                                <div class="col-md-3">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Created Date
                                        </option>
                                        <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>
                                            Sort: Name
                                        </option>
                                        <option value="email" {{ ($sort_by ?? '') == 'email' ? 'selected' : '' }}>
                                            Sort: Email
                                        </option>
                                        <option value="role_name" {{ ($sort_by ?? '') == 'role_name' ? 'selected' : '' }}>
                                            Sort: Role
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>DESC</option>
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>ASC</option>
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <select name="per_page" class="form-select form-select-sm">
                                        @php
                                            $pp = $per_page ?? 10;
                                        @endphp
                                        <option value="10" {{ $pp == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ $pp == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $pp == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                </div>

                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Date</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                {{-- Nomor urut mengikuti pagination --}}
                                                <td>
                                                    {{ ($getRecord->currentPage() - 1) * $getRecord->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->email }}</td>
                                                <td>{{ $value->role_name }}</td>
                                                <td>
                                                    {{ $value->created_at ? $value->created_at->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ route('user.edit', $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">
                                                                Edit
                                                            </a>
                                                        @endif

                                                        @if (!empty($PermissionDelete))
                                                            <form action="{{ route('user.destroy', $value->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No users found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- PAGINATION --}}
                            @if ($getRecord instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="mt-2">
                                    {{ $getRecord->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
