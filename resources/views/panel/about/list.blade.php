@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Tentang Yayasan</h5>
                            @if ($PermissionEdit)
                                <a href="{{ url('about/edit/' . $getRecord->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ti ti-edit"></i> Edit
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-bold">Visi</h6>
                                <p>{{ $getRecord->vision ?? '-' }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-bold">Misi</h6>
                                <p>{{ $getRecord->mission ?? '-' }}</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="fw-bold">Sejarah</h6>
                                <p>{{ $getRecord->history ?? '-' }}</p>
                            </div>

                            @if ($PermissionEdit)
                                <div class="text-end">
                                    <a href="{{ url('about/delete/' . $getRecord->id) }}"
                                        onclick="return confirm('Apakah Anda yakin ingin mengosongkan data About?')"
                                        class="btn btn-outline-danger btn-sm">
                                        <i class="ti ti-trash"></i> Kosongkan Data
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
