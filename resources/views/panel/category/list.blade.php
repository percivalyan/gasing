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
                            <h5 class="mb-0">Category</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ url('category/add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Category
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                <div class="col-md-4">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Search category name / ID" value="{{ $filter_keyword ?? '' }}">
                                </div>

                                <div class="col-md-3">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="id" {{ ($sort_by ?? '') == 'id' ? 'selected' : '' }}>
                                            Sort: ID
                                        </option>
                                        <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>
                                            Sort: Name
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC
                                        </option>
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>
                                            ASC
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ url('category') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                {{-- Nomor urut pakai pagination --}}
                                                <td>{{ $getRecord->firstItem() + $loop->index }}</td>
                                                <td>{{ $value->name }}</td>
                                                <td>
                                                    @if ($value->image_path)
                                                        <img src="{{ asset('storage/' . $value->image_path) }}"
                                                            alt="Category Image" height="40">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ url('category/edit/' . $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ url('category/delete/' . $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this category?');">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No Category found.</td>
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
