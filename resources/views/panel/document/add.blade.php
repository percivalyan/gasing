@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')

        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">Add New Document</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('document/store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Document Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Visibility</label>
                                <select name="visibility" class="form-select">
                                    <option value="private" selected>Private</option>
                                    <option value="public">Public</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload File</label>
                                <input type="file" name="file_path" class="form-control">
                            </div>
                            <div class="text-end">
                                <a href="{{ url('document') }}" class="btn btn-secondary me-2">Cancel</a>
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
