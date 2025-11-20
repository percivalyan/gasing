@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Add Struktur Organisasi</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('organization/structure/insert') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Posisi</label>
                                    <input type="text" name="position" class="form-control" value="{{ old('position') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('organization/structure') }}" class="btn btn-secondary me-2">Cancel</a>
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
