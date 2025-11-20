@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="mb-0">Add Gallery</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('gallery/insert') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Images</label>
                            <div id="dropArea" class="border rounded p-4 text-center bg-light" style="cursor:pointer;">
                                <p class="mb-2 text-muted">Drag & Drop images here or click to browse</p>
                                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" hidden>
                                <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-3 justify-content-center"></div>
                            </div>
                            <small class="text-muted">You can upload multiple images (JPG, PNG, Max 2MB each)</small>
                        </div>

                        <div class="text-end">
                            <a href="{{ url('gallery') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropArea = document.getElementById('dropArea');
        const imageInput = document.getElementById('imageInput');
        const previewContainer = document.getElementById('previewContainer');

        let filesList = [];

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
            const newFiles = Array.from(e.dataTransfer.files);
            filesList = [...filesList, ...newFiles];
            previewImages(filesList);
            updateFileInput();
        });

        imageInput.addEventListener('change', e => {
            const newFiles = Array.from(e.target.files);
            filesList = [...filesList, ...newFiles];
            previewImages(filesList);
            updateFileInput();
        });

        function updateFileInput() {
            // membuat ulang FileList agar semua file terkumpul
            const dataTransfer = new DataTransfer();
            filesList.forEach(file => dataTransfer.items.add(file));
            imageInput.files = dataTransfer.files;
        }

        function previewImages(files) {
            previewContainer.innerHTML = '';
            files.forEach(file => {
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
