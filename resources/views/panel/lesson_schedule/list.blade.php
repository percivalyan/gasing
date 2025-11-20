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
                            <h5 class="mb-0">Lesson Schedule</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('lesson_schedule.add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Schedule
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            {{-- Filter strip --}}
                            <form class="row g-2 mb-3" method="GET" action="{{ route('lesson_schedule.list') }}">
                                <div class="col-md-3">
                                    <select name="day" class="form-control">
                                        <option value="">All Days</option>
                                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $d)
                                            <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="level" class="form-control">
                                        <option value="">All Levels</option>
                                        @foreach (['SD','SMP','SMA'] as $lv)
                                            <option value="{{ $lv }}" {{ request('level') == $lv ? 'selected' : '' }}>{{ $lv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="q" class="form-control" placeholder="Search subject/teacher/room..."
                                           value="{{ request('q') }}">
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button class="btn btn-outline-secondary" type="submit">Filter</button>
                                </div>
                            </form>

                            @php $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']; @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:120px">Time</th>
                                            @foreach ($days as $d)
                                                <th class="text-center">{{ $d }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($timeSlots as $slot)
                                            <tr>
                                                <td><strong>{{ $slot['start'] }} - {{ $slot['end'] }}</strong></td>
                                                @foreach ($days as $d)
                                                    <td>
                                                        @php
                                                            $items = $matrix[$d][$slot['start'].'-'.$slot['end']] ?? [];
                                                        @endphp
                                                        @forelse ($items as $sch)
                                                            <div class="p-2 mb-2 rounded border">
                                                                <div class="fw-bold">
                                                                    {{ $sch->subject->name ?? $sch->subject_name }}
                                                                    <span class="badge bg-light text-dark ms-1">{{ $sch->school_level }}</span>
                                                                </div>
                                                                <div class="small text-muted">
                                                                    {{ $sch->start_time }} - {{ $sch->end_time }}
                                                                </div>
                                                                <div class="small">
                                                                    <i class="feather icon-user me-1"></i>{{ $sch->teacher->name ?? '-' }}
                                                                </div>
                                                                @if($sch->room)
                                                                    <div class="small">
                                                                        <i class="feather icon-map-pin me-1"></i>{{ $sch->room }}
                                                                    </div>
                                                                @endif

                                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                                    <div class="mt-2 text-end">
                                                                        @if (!empty($PermissionEdit))
                                                                            <a href="{{ route('lesson_schedule.edit', $sch->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                                                        @endif
                                                                        @if (!empty($PermissionDelete))
                                                                            <form action="{{ route('lesson_schedule.delete', $sch->id) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Delete</button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @empty
                                                            <span class="text-muted small">-</span>
                                                        @endforelse
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Belum ada jadwal.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <hr>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Teacher</th>
                                            <th>Level</th>
                                            <th>Subject</th>
                                            <th>Day</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->teacher->name ?? '-' }}</td>
                                                <td>{{ $value->school_level }}</td>
                                                <td>{{ $value->subject->name ?? $value->subject_name }}</td>
                                                <td>{{ $value->day_of_week }}</td>
                                                <td>{{ $value->start_time }} - {{ $value->end_time }}</td>
                                                <td>{{ $value->room ?? '-' }}</td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ route('lesson_schedule.edit', $value->id) }}"
                                                               class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <form action="{{ route('lesson_schedule.delete', $value->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Belum ada data Lesson Schedule.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
