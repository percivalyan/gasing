@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Anggota Organisasi</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('organization/member/edit/' . $getRecord->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Pilih Posisi</label>
                                    <select name="structure_id" class="form-select" required>
                                        @foreach ($structure as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->id == $getRecord->structure_id ? 'selected' : '' }}>
                                                {{ $item->position }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Anggota</label>
                                    <input type="text" name="name" class="form-control" value="{{ $getRecord->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jabatan (opsional)</label>
                                    <input type="text" name="title" class="form-control" value="{{ $getRecord->title }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" name="order" class="form-control" value="{{ $getRecord->order }}">
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('organization/member') }}" class="btn btn-secondary me-2">Cancel</a>
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
