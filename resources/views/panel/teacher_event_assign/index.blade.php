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
                            <h5 class="mb-0">Penugasan TeacherEvent ↔ StudentEvent</h5>
                            <a href="{{ route('event-assign.create') }}" class="btn btn-sm btn-primary">
                                <i class="feather icon-plus-circle me-1"></i> Buat Penugasan
                            </a>
                        </div>

                        <div class="card-body">
                            {{-- Filter --}}
                            <form class="row g-2 mb-3" method="GET" action="{{ route('event-assign.index') }}">
                                <div class="col-md-4">
                                    <select name="teacher_event_id" class="form-control">
                                        <option value="">Semua Teacher Event</option>
                                        @foreach ($teachers as $t)
                                            <option value="{{ $t->id }}"
                                                {{ request('teacher_event_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="student_event_id" class="form-control">
                                        <option value="">Semua Student Event</option>
                                        @foreach ($students as $s)
                                            <option value="{{ $s->id }}"
                                                {{ request('student_event_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->name }} @if ($s->school_level)
                                                    ({{ $s->school_level }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        @foreach (['Active', 'Inactive'] as $st)
                                            <option value="{{ $st }}"
                                                {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button class="btn btn-outline-secondary" type="submit">Filter</button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Teacher Event</th>
                                            <th>Student Event</th>
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
                                                <td>{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $row->teacherEvent->name ?? '-' }}</td>
                                                <td>{{ $row->studentEvent->name ?? '-' }}</td>
                                                <td>{{ $row->studentEvent->school_level ?? '-' }}</td>
                                                <td>{{ $row->start_date ?? '-' }}</td>
                                                <td>{{ $row->end_date ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $row->status === 'Active' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $row->status }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('event-assign.edit', $row->id) }}"
                                                        class="btn btn-sm btn-warning me-1">Edit</a>

                                                    <form action="{{ route('event-assign.toggle', $row->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary me-1">
                                                            {{ $row->status === 'Active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('event-assign.delete', $row->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus penugasan ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                    </form>
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
                                {{ $records->links() }}
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
