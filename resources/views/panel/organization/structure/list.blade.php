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
                            <h5 class="mb-0">Struktur Organisasi</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ url('organization/structure/add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Struktur
                                </a>
                            @endif
                        </div>
                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" action="{{ url('organization/structure') }}" class="row gy-2 gx-2 mb-3">
                                {{-- Keyword: posisi / urutan --}}
                                <div class="col-md-4">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Cari nama posisi / urutan" value="{{ $filter_keyword ?? '' }}">
                                </div>

                                {{-- Sort by --}}
                                <div class="col-md-3">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="order" {{ ($sort_by ?? '') == 'order' ? 'selected' : '' }}>
                                            Sort: Urutan
                                        </option>
                                        <option value="position" {{ ($sort_by ?? '') == 'position' ? 'selected' : '' }}>
                                            Sort: Nama Posisi
                                        </option>
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Tanggal Input
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
                                    <a href="{{ url('organization/structure') }}"
                                        class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Posisi</th>
                                            <th>Urutan</th>
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
                                                <td>{{ $value->position }}</td>
                                                <td>{{ $value->order }}</td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ url('organization/structure/edit/' . $value->id) }}"
                                                                class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <a href="{{ url('organization/structure/delete/' . $value->id) }}"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Yakin ingin menghapus posisi ini?');">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Belum ada data struktur organisasi.
                                                </td>
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
