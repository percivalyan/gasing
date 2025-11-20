@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')

        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">Add Event Schedule</h5>
                    </div>
                    <div class="card-body">
                        @include('panel._message')

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('event_schedule.insert') }}" method="POST" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Batch Event</label>
                                <select name="event_batch_id" class="form-control" required>
                                    <option value="">-- Pilih Batch --</option>
                                    @foreach ($getBatch as $b)
                                        <option value="{{ $b->id }}" {{ old('event_batch_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->full_batch_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('event_batch_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">User (Guru / Pelatih / Penanggung Jawab)</label>
                                <select name="user_id" class="form-control">
                                    <option value="">-- Optional: Pilih User --</option>
                                    @foreach ($getUser as $u)
                                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="date" class="form-control"
                                           value="{{ old('date') }}">
                                    @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hari</label>
                                    <select name="day_of_week" class="form-control">
                                        <option value="">-- Pilih Hari --</option>
                                        @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $d)
                                            <option value="{{ $d }}" {{ old('day_of_week') == $d ? 'selected' : '' }}>
                                                {{ $d }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('day_of_week') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        @php
                                            $statusList = ['Scheduled','Completed','Cancelled'];
                                        @endphp
                                        @foreach ($statusList as $st)
                                            <option value="{{ $st }}" {{ old('status','Scheduled') == $st ? 'selected' : '' }}>
                                                {{ $st }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" name="start_time" class="form-control"
                                           value="{{ old('start_time') }}">
                                    @error('start_time') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="time" name="end_time" class="form-control"
                                           value="{{ old('end_time') }}">
                                    @error('end_time') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tempat</label>
                                <input type="text" name="place" class="form-control"
                                       value="{{ old('place') }}" placeholder="Contoh: Ruang Aula, Lab Komputer, dll">
                                @error('place') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Agenda</label>
                                <textarea name="agenda" class="form-control" rows="3"
                                          placeholder="Contoh: Pelatihan Guru Tahap 1, Sosialisasi, dll">{{ old('agenda') }}</textarea>
                                @error('agenda') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="text-end">
                                <a href="{{ route('event_schedule.list') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
