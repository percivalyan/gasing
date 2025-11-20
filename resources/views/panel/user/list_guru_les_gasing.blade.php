@extends('panel.layouts.app')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="mb-2">Guru Les Gasing</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}"><i class="feather icon-home"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Guru Les Gasing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <!-- [ Guru Les Gasing Table Section ] start -->
            <div class="row">
                <div class="col-sm-12">
                    @include('panel._message')

                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex flex-column flex-md-row gap-2 gap-md-0 justify-content-between align-items-md-center">
                            <h5 class="mb-0">Daftar Guru Les Gasing</h5>
                            <div class="d-flex gap-2 align-items-center w-100 w-md-auto">
                                <!-- Pencarian cepat (opsional) -->
                                <form method="GET" action="{{ route('user.list-guru-les-gasing') }}" class="ms-md-auto w-100 w-md-auto">
                                    <div class="input-group">
                                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama atau email...">
                                        <button class="btn btn-outline-secondary" type="submit"><i class="feather icon-search"></i></button>
                                        @if(request('q'))
                                            <a href="{{ route('user.list-guru-les-gasing') }}" class="btn btn-outline-light" title="Reset">&times;</a>
                                        @endif
                                    </div>
                                </form>

                                @if (!empty($PermissionAdd))
                                    <a href="{{ route('user.guru-les-gasing.create') }}" class="btn btn-primary">
                                        <i class="feather icon-plus-circle me-1"></i> Tambah Guru
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 56px">#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>TTL</th>
                                            <th>Tanggal Dibuat</th>
                                            @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                <th class="text-end" style="width: 160px">Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Jika paginated, gunakan firstItem() untuk penomoran; jika bukan, fallback ke 1
                                            $start = method_exists($getRecord, 'firstItem') && $getRecord->firstItem() ? $getRecord->firstItem() : 1;
                                        @endphp

                                        @forelse ($getRecord as $idx => $value)
                                            <tr>
                                                <td>{{ $start + $idx }}</td>
                                                <td class="fw-semibold">
                                                    {{ $value->name }}
                                                    @if(!empty($value->nip))
                                                        <div class="text-muted small">NIP: {{ $value->nip }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="mailto:{{ $value->email }}">{{ $value->email }}</a>
                                                    @if(!empty($value->whatsapp_number))
                                                        <div class="small"><i class="feather icon-phone"></i> {{ $value->whatsapp_number }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($value->gender === 'M')
                                                        <span class="badge bg-primary">Laki-laki</span>
                                                    @elseif($value->gender === 'F')
                                                        <span class="badge bg-info">Perempuan</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($value->birth_place || $value->birth_date)
                                                        <div>{{ $value->birth_place ?? '-' }}</div>
                                                        <div class="text-muted small">{{ optional($value->birth_date ? \Carbon\Carbon::parse($value->birth_date) : null)->format('d M Y') }}</div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ optional($value->created_at)->format('d M Y') }}</td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        <div class="btn-group" role="group" aria-label="Aksi">
                                                            @if (!empty($PermissionEdit))
                                                                <a href="{{ route('user.guru-les-gasing.edit', $value->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                            @endif
                                                            @if (!empty($PermissionDelete))
                                                                <form action="{{ route('user.guru-les-gasing.delete', $value->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    Tidak ada data guru les gasing.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(method_exists($getRecord, 'links'))
                                <div class="mt-3 d-flex justify-content-end">
                                    {!! $getRecord->appends(['q' => request('q')])->links() !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Guru Les Gasing Table Section ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection