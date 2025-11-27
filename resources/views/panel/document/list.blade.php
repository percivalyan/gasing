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
                            <h5 class="mb-0">Documents</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ url('document/add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Document
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                {{-- Keyword --}}
                                <div class="col-md-3">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Search name / description / uploader"
                                        value="{{ $filter_keyword ?? '' }}">
                                </div>

                                {{-- Visibility --}}
                                <div class="col-md-2">
                                    <select name="visibility" class="form-select form-select-sm">
                                        <option value="">All Visibility</option>
                                        <option value="public"
                                            {{ ($filter_visibility ?? '') == 'public' ? 'selected' : '' }}>
                                            Public
                                        </option>
                                        <option value="private"
                                            {{ ($filter_visibility ?? '') == 'private' ? 'selected' : '' }}>
                                            Private
                                        </option>
                                    </select>
                                </div>

                                {{-- Has file --}}
                                <div class="col-md-2">
                                    <select name="has_file" class="form-select form-select-sm">
                                        <option value="">All Files</option>
                                        <option value="with" {{ ($filter_has_file ?? '') == 'with' ? 'selected' : '' }}>
                                            With File
                                        </option>
                                        <option value="without"
                                            {{ ($filter_has_file ?? '') == 'without' ? 'selected' : '' }}>
                                            Without File
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort by --}}
                                <div class="col-md-2">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Created At
                                        </option>
                                        <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>
                                            Sort: Name
                                        </option>
                                        <option value="visibility" {{ ($sort_by ?? '') == 'visibility' ? 'selected' : '' }}>
                                            Sort: Visibility
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort direction --}}
                                <div class="col-md-1">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC
                                        </option>
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>
                                            ASC
                                        </option>
                                    </select>
                                </div>

                                {{-- Buttons --}}
                                <div class="col-md-2 mt-2 mt-md-0 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ url('document') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Visibility</th>
                                            <th>File</th>
                                            <th>Uploader</th>
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
                                                <td>{{ $value->name }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($value->description, 60) }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $value->visibility == 'public' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($value->visibility) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($value->file_path)
                                                        <a href="{{ asset('storage/' . $value->file_path) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-info">
                                                            View
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No File</span>
                                                    @endif
                                                </td>
                                                <td>{{ $value->user->name ?? '-' }}</td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ url('document/edit/' . $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ url('document/delete/' . $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this document?');">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Document found.</td>
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
