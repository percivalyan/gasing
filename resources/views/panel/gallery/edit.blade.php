@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="mb-0">Edit Gallery</h5>
                </div>
                <div class="card-body">
                    {{-- ✅ Perbaikan action: gunakan route update, bukan edit --}}
                    <form action="{{ route('gallery.update', $getRecord->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $getRecord->title }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ $getRecord->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Add New Images</label>
                            <div id="dropArea" class="border rounded p-4 text-center bg-light" style="cursor:pointer;">
                                <p class="mb-2 text-muted">Drag & Drop new images here or click to browse</p>
                                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" hidden>
                                <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-3 justify-content-center"></div>
                            </div>
                        </div>

                        @if ($getRecord->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Existing Images</label>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($getRecord->images as $img)
                                        <div class="position-relative border rounded p-1 text-center" style="width:110px;">
                                            <img src="{{ asset('storage/' . $img->image_path) }}" class="rounded mb-1"
                                                width="100" height="100" style="object-fit: cover;">
                                            <div class="form-check text-center">
                                                <input type="checkbox" class="form-check-input" name="delete_images[]"
                                                    value="{{ $img->id }}">
                                                <label class="form-check-label small">Delete</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="text-end">
                            <a href="{{ route('gallery.list') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Script drag & drop area --}}
    <script>
        const dropArea = document.getElementById('dropArea');
        const imageInput = document.getElementById('imageInput');
        const previewContainer = document.getElementById('previewContainer');

        dropArea.addEventListener('click', () => imageInput.click());

        ['dragenter', 'dragover'].forEach(event => {
            dropArea.addEventListener(event, e => {
                e.preventDefault();
                dropArea.classList.add('border-primary');
            });
        });

        ['dragleave', 'drop'].forEach(event => {
            dropArea.addEventListener(event, e => {
                e.preventDefault();
                dropArea.classList.remove('border-primary');
            });
        });

        dropArea.addEventListener('drop', e => {
            const files = e.dataTransfer.files;
            imageInput.files = files;
            previewImages(files);
        });

        imageInput.addEventListener('change', e => previewImages(e.target.files));

        function previewImages(files) {
            previewContainer.innerHTML = '';
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = evt => {
                    const img = document.createElement('img');
                    img.src = evt.target.result;
                    img.classList.add('border', 'rounded');
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection
