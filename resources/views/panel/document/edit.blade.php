@extends('panel.layouts.app')

@section('content')
<div class="pc-container">
    <div class="pc-content">
        @include('panel.layouts.breadcrumb')

        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Document</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('document/edit/' . $getRecord->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Document Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $getRecord->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $getRecord->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Visibility</label>
                                <select name="visibility" class="form-select">
                                    <option value="private" {{ $getRecord->visibility == 'private' ? 'selected' : '' }}>Private</option>
                                    <option value="public" {{ $getRecord->visibility == 'public' ? 'selected' : '' }}>Public</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload File</label>
                                <input type="file" name="file_path" class="form-control">
                                @if ($getRecord->file_path)
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $getRecord->file_path) }}" target="_blank">View Current File</a>
                                </div>
                                @endif
                            </div>
                            <div class="text-end">
                                <a href="{{ url('document') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
