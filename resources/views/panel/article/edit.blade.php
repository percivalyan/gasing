@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Article</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('article/edit/' . $getRecord->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ $getRecord->title }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="summary" class="form-label">Summary</label>
                                    <textarea name="summary" id="summary" class="form-control" rows="2">{{ $getRecord->summary }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea name="content" id="content" class="form-control" rows="6">{{ $getRecord->content }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-select">
                                        <option value="">-- Select Category --</option>
                                        @foreach ($getCategory as $cat)
                                            <option value="{{ $cat->id }}" {{ $getRecord->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="image_path" class="form-label">Article Image</label>
                                    <input type="file" name="image_path" id="image_path" class="form-control">
                                    @if ($getRecord->image_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $getRecord->image_path) }}" height="60" alt="Image Preview">
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="draft" {{ $getRecord->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ $getRecord->status == 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('article') }}" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#content'))
                .catch(error => console.error(error));

            ClassicEditor
                .create(document.querySelector('#summary'))
                .catch(error => console.error(error));
        });
    </script>
@endsection
