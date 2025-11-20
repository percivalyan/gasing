@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    @include('panel._message')

                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Buat Penugasan TeacherEvent ↔ StudentEvent</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('event-assign.store') }}" method="POST" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Teacher Event (Guru)</label>
                                    <select name="teacher_event_id" id="teacher_event_id" class="form-control" required>
                                        <option value="">-- Pilih Teacher Event --</option>
                                        @foreach ($teachers as $t)
                                            <option value="{{ $t->id }}"
                                                {{ old('teacher_event_id', $teacherEvent->id ?? '') == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_event_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="start_date" class="form-control"
                                            value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tanggal Selesai</label>
                                        <input type="date" name="end_date" class="form-control"
                                            value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Status Penugasan</label>
                                        <select name="status" class="form-control" required>
                                            @foreach (['Active', 'Inactive'] as $st)
                                                <option value="{{ $st }}"
                                                    {{ old('status', 'Active') == $st ? 'selected' : '' }}>
                                                    {{ $st }}</option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Pilih Student Event</h6>
                                    <div class="d-flex gap-2">
                                        <select id="filter-level" class="form-select form-select-sm">
                                            <option value="">Semua Level</option>
                                            <option value="SD">SD</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA">SMA</option>
                                        </select>
                                        <input type="text" id="filter-name" class="form-control form-control-sm"
                                            placeholder="Cari nama...">
                                    </div>
                                </div>

                                <div class="table-responsive" style="max-height: 420px; overflow:auto;">
                                    <table class="table table-bordered align-middle mb-0" id="students-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:36px">
                                                    <input type="checkbox" id="check-all">
                                                </th>
                                                <th>Nama</th>
                                                <th>Level</th>
                                                <th>Asal Sekolah</th>
                                                <th>WA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $s)
                                                <tr data-name="{{ Str::lower($s->name) }}"
                                                    data-level="{{ $s->school_level }}">
                                                    <td>
                                                        <input type="checkbox" name="student_event_ids[]"
                                                            value="{{ $s->id }}"
                                                            {{ in_array($s->id, old('student_event_ids', [])) ? 'checked' : '' }}>
                                                    </td>
                                                    <td>{{ $s->name }}</td>
                                                    <td><span
                                                            class="badge bg-light text-dark">{{ $s->school_level ?? '-' }}</span>
                                                    </td>
                                                    <td>{{ $s->school_origin ?? '-' }}</td>
                                                    <td>{{ $s->whatsapp_number ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @error('student_event_ids')
                                    <small class="text-danger d-block mt-2">{{ $message }}</small>
                                @enderror

                                <div class="text-end mt-3">
                                    <a href="{{ route('event-assign.index') }}" class="btn btn-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // check all
            document.getElementById('check-all')?.addEventListener('change', function() {
                document.querySelectorAll('#students-table tbody input[type="checkbox"]').forEach(cb => cb.checked =
                    this.checked);
            });

            // filter
            const nameInput = document.getElementById('filter-name');
            const levelSel = document.getElementById('filter-level');
            const rows = Array.from(document.querySelectorAll('#students-table tbody tr'));

            function applyFilter() {
                const q = (nameInput.value || '').toLowerCase();
                const lv = levelSel.value;
                rows.forEach(r => {
                    const matchName = r.dataset.name.includes(q);
                    const matchLevel = !lv || r.dataset.level === lv;
                    r.style.display = (matchName && matchLevel) ? '' : 'none';
                });
            }
            nameInput?.addEventListener('input', applyFilter);
            levelSel?.addEventListener('change', applyFilter);
        </script>
    @endpush
@endsection
