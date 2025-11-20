@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')

        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Add Event Batch</h5>
                        <a href="{{ route('event_batch.list') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
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

                        <form action="{{ route('event_batch.insert') }}" method="POST" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Tahun Event</label>
                                <input type="text" name="event_year" class="form-control"
                                       placeholder="Contoh: 2025"
                                       value="{{ old('event_year') }}" required>
                                @error('event_year') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tahap</label>
                                <input type="text" name="event_phase" class="form-control"
                                       placeholder="Contoh: Tahap 1"
                                       value="{{ old('event_phase') }}" required>
                                @error('event_phase') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="text-end">
                                <a href="{{ route('event_batch.list') }}" class="btn btn-secondary me-2">Cancel</a>
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
