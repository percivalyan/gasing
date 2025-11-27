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
                            <h5 class="mb-0">Galleries</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ url('gallery/add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Gallery
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                {{-- Keyword --}}
                                <div class="col-md-4">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Search title / description" value="{{ $filter_keyword ?? '' }}">
                                </div>

                                {{-- Filter has_images --}}
                                <div class="col-md-3">
                                    <select name="has_images" class="form-select form-select-sm">
                                        <option value="">All Galleries</option>
                                        <option value="with" {{ ($filter_has_images ?? '') == 'with' ? 'selected' : '' }}>
                                            With Images
                                        </option>
                                        <option value="without"
                                            {{ ($filter_has_images ?? '') == 'without' ? 'selected' : '' }}>
                                            Without Images
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
                                        <option value="id" {{ ($sort_by ?? '') == 'id' ? 'selected' : '' }}>
                                            Sort: ID
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort direction --}}
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

                                {{-- Buttons --}}
                                <div class="col-md-2 mt-2 mt-md-0 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ url('gallery') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Images</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                {{-- Nomor urut sesuai pagination --}}
                                                <td>{{ $getRecord->firstItem() + $loop->index }}</td>
                                                <td>{{ $value->title }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($value->description, 50) }}</td>
                                                <td>
                                                    @if ($value->images->count() > 0)
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @foreach ($value->images as $img)
                                                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                                                    alt="gallery" class="rounded" width="60"
                                                                    height="60" style="object-fit: cover;">
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No Images</span>
                                                    @endif
                                                </td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ url('gallery/edit/' . $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ url('gallery/delete/' . $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this gallery?');">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No Galleries found.</td>
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
