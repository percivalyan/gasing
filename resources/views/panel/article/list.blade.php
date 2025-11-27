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
                            <h5 class="mb-0">Articles</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ url('article/add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Article
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                {{-- Keyword --}}
                                <div class="col-md-3">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Search title / summary / content" value="{{ $filter_keyword ?? '' }}">
                                </div>

                                {{-- Category filter --}}
                                <div class="col-md-3">
                                    <select name="category_id" class="form-select form-select-sm">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ ($filter_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status filter --}}
                                <div class="col-md-2">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <option value="published"
                                            {{ ($filter_status ?? '') == 'published' ? 'selected' : '' }}>
                                            Published
                                        </option>
                                        <option value="draft" {{ ($filter_status ?? '') == 'draft' ? 'selected' : '' }}>
                                            Draft
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort by --}}
                                <div class="col-md-2">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Created At
                                        </option>
                                        <option value="title" {{ ($sort_by ?? '') == 'title' ? 'selected' : '' }}>
                                            Sort: Title
                                        </option>
                                        <option value="status" {{ ($sort_by ?? '') == 'status' ? 'selected' : '' }}>
                                            Sort: Status
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort direction & buttons --}}
                                <div class="col-md-2 d-flex gap-2">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC
                                        </option>
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>
                                            ASC
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2 mt-md-0 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ url('article') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Author</th>
                                            <th>Created</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                {{-- Nomor urut berbasis pagination --}}
                                                <td>{{ $getRecord->firstItem() + $loop->index }}</td>
                                                <td>{{ $value->title }}</td>
                                                <td>{{ $value->category->name ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $value->status == 'published' ? 'success' : 'secondary' }}">
                                                        {{ ucfirst($value->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $value->user->name ?? '-' }}</td>
                                                <td>{{ $value->created_at->format('d M Y') }}</td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ url('article/edit/' . $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ url('article/delete/' . $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this article?');">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Articles found.</td>
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
