@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Category</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('category/edit/' . $getRecord->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ $getRecord->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="image_path" class="form-label">Category Image</label>
                                    <input type="file" name="image_path" id="image_path" class="form-control">
                                    @if ($getRecord->image_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $getRecord->image_path) }}" height="60" alt="Image Preview">
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <a href="{{ url('category') }}" class="btn btn-secondary me-2">Cancel</a>
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
