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
                            <h5 class="mb-0">Event Schedule</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('event_schedule.add') }}" class="btn btn-sm btn-primary">
                                    <i class="feather icon-plus-circle me-1"></i> Add Schedule
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            {{-- Filter strip --}}
                            <form class="row g-2 mb-3" method="GET" action="{{ route('event_schedule.list') }}">
                                <div class="col-md-4">
                                    <select name="batch_id" class="form-control">
                                        <option value="all" {{ $selectedBatchId === 'all' ? 'selected' : '' }}>All Batch</option>
                                        @foreach ($getBatch as $b)
                                            <option value="{{ $b->id }}"
                                                {{ $selectedBatchId === $b->id ? 'selected' : '' }}>
                                                {{ $b->full_batch_name ?? ($b->event_year . ' Tahap ' . $b->event_phase) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="q" class="form-control"
                                           placeholder="Search agenda/tempat/user..."
                                           value="{{ request('q') }}">
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button class="btn btn-outline-secondary" type="submit">Filter</button>
                                </div>
                            </form>

                            {{-- MATRIX: Waktu x Hari (mirip Lesson Schedule) --}}
                            @php
                                $days = $days ?? ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                            @endphp

                            <div class="table-responsive mb-4">
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
                                                <td>
                                                    <strong>{{ $slot['start'] }} - {{ $slot['end'] }}</strong>
                                                </td>
                                                @foreach ($days as $d)
                                                    <td>
                                                        @php
                                                            $slotKey = $slot['start'].'-'.$slot['end'];
                                                            $items = $matrix[$d][$slotKey] ?? [];
                                                        @endphp
                                                        @forelse ($items as $sch)
                                                            <div class="p-2 mb-2 rounded border">
                                                                <div class="fw-bold">
                                                                    {{ $sch->agenda ?? '-' }}
                                                                    @if($sch->eventBatch)
                                                                        <span class="badge bg-light text-dark ms-1">
                                                                            {{ $sch->eventBatch->full_batch_name ?? ($sch->eventBatch->event_year . ' Tahap ' . $sch->eventBatch->event_phase) }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="small text-muted">
                                                                    {{ \Carbon\Carbon::parse($sch->date)->format('d-m-Y') }}
                                                                    • {{ $sch->start_time }} - {{ $sch->end_time }}
                                                                </div>
                                                                <div class="small">
                                                                    <i class="feather icon-user me-1"></i>{{ $sch->user->name ?? '-' }}
                                                                </div>
                                                                @if($sch->place)
                                                                    <div class="small">
                                                                        <i class="feather icon-map-pin me-1"></i>{{ $sch->place }}
                                                                    </div>
                                                                @endif
                                                                @php
                                                                    $st = $sch->status ?? 'Planned';
                                                                    $badgeClass = match ($st) {
                                                                        'Completed' => 'bg-success',
                                                                        'Cancelled' => 'bg-danger',
                                                                        'On Going', 'Ongoing', 'Progress' => 'bg-info',
                                                                        default => 'bg-warning text-dark',
                                                                    };
                                                                @endphp
                                                                <div class="mt-1">
                                                                    <span class="badge {{ $badgeClass }}">{{ $st }}</span>
                                                                </div>

                                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                                    <div class="mt-2 text-end">
                                                                        @if (!empty($PermissionEdit))
                                                                            <a href="{{ route('event_schedule.edit', $sch->id) }}"
                                                                               class="btn btn-sm btn-warning me-1">Edit</a>
                                                                        @endif
                                                                        @if (!empty($PermissionDelete))
                                                                            <form action="{{ route('event_schedule.delete', $sch->id) }}"
                                                                                  method="POST"
                                                                                  class="d-inline">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                        class="btn btn-sm btn-danger"
                                                                                        onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                                                    Delete
                                                                                </button>
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
                                                <td colspan="{{ count($days) + 1 }}" class="text-center">
                                                    Belum ada jadwal.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            {{-- LIST DETAIL EVENT (versi tabel biasa) --}}
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Batch</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Agenda</th>
                                            <th>Tempat</th>
                                            <th>User</th>
                                            <th>Status</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $value->eventBatch->full_batch_name ?? '-' }}<br>
                                                    @if($value->eventBatch)
                                                        <small class="text-muted">
                                                            {{ $value->eventBatch->event_year }} Tahap {{ $value->eventBatch->event_phase }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($value->date)
                                                        {{ \Carbon\Carbon::parse($value->date)->format('d-m-Y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $value->start_time }} - {{ $value->end_time }}</td>
                                                <td>{{ $value->agenda ?? '-' }}</td>
                                                <td>{{ $value->place ?? '-' }}</td>
                                                <td>{{ $value->user->name ?? '-' }}</td>
                                                <td>
                                                    @php
                                                        $st = $value->status ?? 'Planned';
                                                        $badgeClass = match ($st) {
                                                            'Completed' => 'bg-success',
                                                            'Cancelled' => 'bg-danger',
                                                            'On Going', 'Ongoing', 'Progress' => 'bg-info',
                                                            default => 'bg-warning text-dark',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ $st }}</span>
                                                </td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        @if (!empty($PermissionEdit))
                                                            <a href="{{ route('event_schedule.edit', $value->id) }}"
                                                               class="btn btn-sm btn-warning me-1">Edit</a>
                                                        @endif
                                                        @if (!empty($PermissionDelete))
                                                            <form action="{{ route('event_schedule.delete', $value->id) }}"
                                                                  method="POST"
                                                                  class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    Belum ada data Event Schedule.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div> {{-- card-body --}}
                    </div> {{-- card --}}
                </div>
            </div>
        </div>
    </div>
@endsection
