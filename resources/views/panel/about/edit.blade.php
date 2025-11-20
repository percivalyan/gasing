@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Edit Tentang Yayasan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('about/edit/' . $getRecord->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Visi</label>
                                    <textarea name="vision" class="form-control" rows="3">{{ old('vision', $getRecord->vision) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Misi</label>
                                    <textarea name="mission" class="form-control" rows="3">{{ old('mission', $getRecord->mission) }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Sejarah</label>
                                    <textarea name="history" class="form-control" rows="4">{{ old('history', $getRecord->history) }}</textarea>
                                </div>

                                <div class="text-end">
                                    <a href="{{ url('about') }}" class="btn btn-secondary me-2">Batal</a>
                                    <button type="submit" class="btn btn-primary">Perbarui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
