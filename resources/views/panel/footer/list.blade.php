@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Kontak & Alamat Yayasan</h5>
                            @if ($PermissionEdit)
                                <a href="{{ url('footer/edit/' . $getRecord->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ti ti-edit"></i> Edit
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="fw-bold">Telepon</h6>
                                <p>{{ $getRecord->phone ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Email</h6>
                                <p>{{ $getRecord->email ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Alamat</h6>
                                <p>{{ $getRecord->address_street ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-bold">Kode Pos</h6>
                                <p>{{ $getRecord->address_post_code ?? '-' }}</p>
                            </div>

                            @if ($PermissionEdit)
                                <div class="text-end">
                                    <a href="{{ url('footer/delete/' . $getRecord->id) }}"
                                        onclick="return confirm('Apakah Anda yakin ingin mengosongkan data Footer?')"
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
