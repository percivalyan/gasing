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
                            <h5 class="mb-0">Penugasan Guru ↔ Siswa</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('assign.create') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Buat Penugasan
                                </a>
                            @endif
                        </div>

                        <div class="card-body">
                            {{-- Filter & Search --}}
                            <form class="row gy-2 gx-2 mb-3" method="GET" action="{{ route('assign.index') }}">
                                {{-- Keyword --}}
                                <div class="col-md-3">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Cari guru / siswa / level" value="{{ $filter_keyword ?? '' }}">
                                </div>

                                {{-- Guru --}}
                                <div class="col-md-3">
                                    <select name="teacher_id" class="form-select form-select-sm">
                                        <option value="">Semua Guru</option>
                                        @foreach ($teachers as $t)
                                            <option value="{{ $t->id }}"
                                                {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Siswa --}}
                                <div class="col-md-3">
                                    <select name="student_course_id" class="form-select form-select-sm">
                                        <option value="">Semua Siswa</option>
                                        @foreach ($students as $s)
                                            <option value="{{ $s->id }}"
                                                {{ request('student_course_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->name }} @if ($s->school_level)
                                                    ({{ $s->school_level }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-3">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="">Semua Status</option>
                                        @foreach (['Active', 'Inactive'] as $st)
                                            <option value="{{ $st }}"
                                                {{ request('status') == $st ? 'selected' : '' }}>
                                                {{ $st }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Sort by --}}
                                <div class="col-md-3 mt-2">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="created_at"
                                            {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Dibuat
                                        </option>
                                        <option value="start_date"
                                            {{ ($sort_by ?? '') == 'start_date' ? 'selected' : '' }}>
                                            Sort: Mulai
                                        </option>
                                        <option value="end_date" {{ ($sort_by ?? '') == 'end_date' ? 'selected' : '' }}>
                                            Sort: Selesai
                                        </option>
                                        <option value="status" {{ ($sort_by ?? '') == 'status' ? 'selected' : '' }}>
                                            Sort: Status
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort direction --}}
                                <div class="col-md-2 mt-2">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>ASC
                                        </option>
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC</option>
                                    </select>
                                </div>

                                {{-- Tombol --}}
                                <div class="col-md-4 mt-2 d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary w-100" type="submit">Filter</button>
                                    <a href="{{ route('assign.index') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Guru</th>
                                            <th>Siswa</th>
                                            <th>Level</th>
                                            <th>Mulai</th>
                                            <th>Selesai</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($records as $row)
                                            <tr>
                                                {{-- nomor urut mengikuti pagination --}}
                                                <td>{{ $records->firstItem() + $loop->index }}</td>
                                                <td>{{ $row->teacher->name ?? '-' }}</td>
                                                <td>{{ $row->studentCourse->name ?? '-' }}</td>
                                                <td>{{ $row->studentCourse->school_level ?? '-' }}</td>
                                                <td>{{ $row->start_date ?? '-' }}</td>
                                                <td>{{ $row->end_date ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $row->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $row->status }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    @if (!empty($PermissionEdit))
                                                        <a href="{{ route('assign.edit', $row->id) }}"
                                                            class="btn btn-sm btn-warning me-1">Edit</a>

                                                        <form action="{{ route('assign.toggle', $row->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-primary me-1">
                                                                {{ $row->status === 'Active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if (!empty($PermissionDelete))
                                                        <form action="{{ route('assign.delete', $row->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus penugasan ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger">Hapus</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Belum ada penugasan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $records->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
