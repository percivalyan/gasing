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
                            <h5 class="mb-0">Jenis Surat</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ url('lettertype/add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Jenis Surat
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" action="{{ url('lettertype') }}" class="row gy-2 gx-2 mb-3">
                                {{-- Keyword: subject / code --}}
                                <div class="col-md-4">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Search subject / code" value="{{ $filter_keyword ?? '' }}">
                                </div>

                                {{-- Sort by --}}
                                <div class="col-md-3">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="subject" {{ ($sort_by ?? '') == 'subject' ? 'selected' : '' }}>
                                            Sort: Subject
                                        </option>
                                        <option value="code" {{ ($sort_by ?? '') == 'code' ? 'selected' : '' }}>
                                            Sort: Code
                                        </option>
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Created At
                                        </option>
                                        <option value="id" {{ ($sort_by ?? '') == 'id' ? 'selected' : '' }}>
                                            Sort: ID
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort direction --}}
                                <div class="col-md-2">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>
                                            ASC
                                        </option>
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC
                                        </option>
                                    </select>
                                </div>

                                {{-- Buttons --}}
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    <a href="{{ url('lettertype') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Kode</th>
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
                                                <td>{{ $value->subject }}</td>
                                                <td><code>{{ $value->code }}</code></td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ url('lettertype/edit/' . $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ url('lettertype/delete/' . $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this letter type?');">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No Jenis Surat found.</td>
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
