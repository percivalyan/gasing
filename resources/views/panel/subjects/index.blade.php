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
                            <h5 class="mb-0">Subjects</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('subjects.create') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Subject
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            {{-- Search & Sorting --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                <div class="col-md-6">
                                    <input type="text" name="q" class="form-control form-control-sm"
                                        placeholder="Search name / description..." value="{{ $filter_q ?? '' }}">
                                </div>

                                <div class="col-md-3">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>
                                            Sort: Name
                                        </option>
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Created At
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>ASC
                                        </option>
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC</option>
                                    </select>
                                </div>

                                <div class="col-md-2 d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary w-100" type="submit">Filter</button>
                                    <a href="{{ route('subjects.index') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subjects as $s)
                                            <tr>
                                                <td>{{ $subjects->firstItem() + $loop->index }}</td>
                                                <td>{{ $s->name }}</td>
                                                <td>{{ $s->description ?? '-' }}</td>
                                                <td class="text-end">
                                                    @if (!empty($PermissionEdit))
                                                        <a href="{{ route('subjects.edit', $s->id) }}"
                                                            class="btn btn-sm btn-warning me-1">Edit</a>
                                                    @endif
                                                    @if (!empty($PermissionDelete))
                                                        <form action="{{ route('subjects.destroy', $s->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus subject ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Belum ada subject.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 d-flex justify-content-end">
                                {{ $subjects->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
