@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')

        <div class="row">
            <div class="col-sm-8 mx-auto">
                @include('panel._message')

                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Absensi</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('attendance.update', $record->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Guru</label>
                                <select name="teacher_id" class="form-select" required>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}" {{ $record->teacher_id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="attendance_date" value="{{ $record->attendance_date }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="present" {{ $record->status == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="late" {{ $record->status == 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="permission" {{ $record->status == 'permission' ? 'selected' : '' }}>Permission</option>
                                        <option value="absent" {{ $record->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Check-in (datetime)</label>
                                    <input type="datetime-local" name="checkin_at" value="{{ $record->checkin_at ? \Carbon\Carbon::parse($record->checkin_at)->format('Y-m-d\\TH:i') : '' }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Check-out (datetime)</label>
                                    <input type="datetime-local" name="checkout_at" value="{{ $record->checkout_at ? \Carbon\Carbon::parse($record->checkout_at)->format('Y-m-d\\TH:i') : '' }}" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3">{{ $record->note }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Kembali</a>
                                <button class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
