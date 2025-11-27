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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Daftar Guru Les Gasing</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('user.guru-les-gasing.create') }}" class="btn btn-primary btn-sm">
                                    <i class="feather icon-plus-circle me-1"></i> Tambah Guru
                                </a>
                            @endif
                        </div>

                        <div class="card-body">

                            {{-- Filter & Search --}}
                            <form method="GET" action="{{ route('user.list-guru-les-gasing') }}"
                                class="row gy-2 gx-2 mb-3">
                                {{-- Keyword --}}
                                <div class="col-md-5">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="q" value="{{ $filter_keyword ?? '' }}"
                                            class="form-control" placeholder="Cari nama / email / NIP / No. WA">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="feather icon-search"></i> Cari
                                        </button>
                                        @if (!empty($filter_keyword))
                                            <a href="{{ route('user.list-guru-les-gasing') }}" class="btn btn-outline-light"
                                                title="Reset">&times;</a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Sort by --}}
                                <div class="col-md-3">
                                    <select name="sort_by" class="form-select form-select-sm">
                                        <option value="name" {{ ($sort_by ?? '') == 'name' ? 'selected' : '' }}>
                                            Sort: Nama
                                        </option>
                                        <option value="email" {{ ($sort_by ?? '') == 'email' ? 'selected' : '' }}>
                                            Sort: Email
                                        </option>
                                        <option value="created_at" {{ ($sort_by ?? '') == 'created_at' ? 'selected' : '' }}>
                                            Sort: Tanggal Dibuat
                                        </option>
                                    </select>
                                </div>

                                {{-- Sort direction --}}
                                <div class="col-md-2">
                                    <select name="sort_direction" class="form-select form-select-sm">
                                        <option value="asc" {{ ($sort_direction ?? '') == 'asc' ? 'selected' : '' }}>
                                            ASC
                                        </option>
                                        <option value="desc" {{ ($sort_direction ?? '') == 'desc' ? 'selected' : '' }}>
                                            DESC
                                        </option>
                                    </select>
                                </div>

                                {{-- Tombol filter/reset tambahan (opsional) --}}
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                        Terapkan
                                    </button>
                                    <a href="{{ route('user.list-guru-les-gasing') }}"
                                        class="btn btn-sm btn-light w-100">Reset</a>
                                </div>
                            </form>

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
                                        @forelse ($getRecord as $value)
                                            <tr>
                                                {{-- Nomor urut mengikuti pagination --}}
                                                <td>{{ $getRecord->firstItem() + $loop->index }}</td>
                                                <td class="fw-semibold">
                                                    {{ $value->name }}
                                                    @if (!empty($value->nip))
                                                        <div class="text-muted small">NIP: {{ $value->nip }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="mailto:{{ $value->email }}">{{ $value->email }}</a>
                                                    @if (!empty($value->whatsapp_number))
                                                        <div class="small">
                                                            <i class="feather icon-phone"></i>
                                                            {{ $value->whatsapp_number }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($value->gender === 'M')
                                                        <span class="badge bg-primary">Laki-laki</span>
                                                    @elseif($value->gender === 'F')
                                                        <span class="badge bg-info">Perempuan</span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($value->birth_place || $value->birth_date)
                                                        <div>{{ $value->birth_place ?? '-' }}</div>
                                                        <div class="text-muted small">
                                                            {{ $value->birth_date ? \Carbon\Carbon::parse($value->birth_date)->format('d M Y') : '-' }}
                                                        </div>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ optional($value->created_at)->format('d M Y') }}</td>
                                                @if (!empty($PermissionEdit) || !empty($PermissionDelete))
                                                    <td class="text-end">
                                                        <div class="btn-group" role="group" aria-label="Aksi">
                                                            @if (!empty($PermissionEdit))
                                                                <a href="{{ route('user.guru-les-gasing.edit', $value->id) }}"
                                                                    class="btn btn-sm btn-warning">Edit</a>
                                                            @endif
                                                            @if (!empty($PermissionDelete))
                                                                <form
                                                                    action="{{ route('user.guru-les-gasing.delete', $value->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                        Hapus
                                                                    </button>
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

                            {{-- Pagination --}}
                            <div class="mt-3 d-flex justify-content-end">
                                {{ $getRecord->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Guru Les Gasing Table Section ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
