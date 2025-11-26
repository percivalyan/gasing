@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')
            <div class="row">
                <div class="col-sm-12">
                    @include('panel._message')
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">My Student Event Registrations</h5>
                            <a href="{{ route('student_event.formregistration') }}" class="btn btn-sm btn-primary">
                                <i class="feather icon-plus-circle me-1"></i> New Registration
                            </a>
                        </div>
                        <div class="card-body">
                            {{-- Filter & Search untuk Kepala Sekolah --}}
                            <form method="GET" class="row gy-2 gx-2 mb-3">
                                <div class="col-md-4">
                                    <input type="text" name="keyword" class="form-control form-control-sm"
                                        placeholder="Search name / NIK / school / district"
                                        value="{{ $filter_keyword ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="">All Status</option>
                                        <option value="Pending" {{ ($filter_status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Accepted" {{ ($filter_status ?? '') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="Rejected" {{ ($filter_status ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('student_event.my_registration') }}" class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

                            <div class="alert alert-info py-2">
                                Halaman ini hanya menampilkan data Student Event yang di-input oleh akun Anda (Kepala Sekolah).
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>NIK</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Birth Place</th>
                                            <th>School Level</th>
                                            <th>School Origin</th>
                                            <th>Status</th>
                                            <th>Photo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->nik ?? '-' }}</td>
                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->gender == 'M' ? 'Male' : 'Female' }}</td>
                                                <td>{{ $value->birth_place }}</td>
                                                <td>{{ $value->school_level ?? '-' }}</td>
                                                <td>{{ $value->school_origin ?? '-' }}</td>
                                                <td>
                                                    <span class="badge
                                                        @if ($value->status == 'Accepted') bg-success
                                                        @elseif($value->status == 'Rejected') bg-danger
                                                        @else bg-secondary @endif">
                                                        {{ $value->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($value->photo)
                                                        <a href="{{ asset('storage/' . $value->photo) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-info">View</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No Student Event registration found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Kalau nanti pakai paginate(), bisa tambahkan links --}}
                            {{-- {{ $getRecord->withQueryString()->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
