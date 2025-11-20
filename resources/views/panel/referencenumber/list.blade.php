{{-- !!!
Ada masalah yang perlu konfirmasi:
1. Apakah fitur reset urutan nomor surat dihapus? Karena di controller, permission untuk reset juga dihapus.
Jika iya, maka bagian tracker di view ini perlu diupdate untuk menghilangkan tombol reset.
2. Apakah fitur delete nomor surat ditambahkan? Karena di controller, permission untuk delete ditambahkan.
Jika iya, maka bagian tabel nomor surat perlu diupdate untuk menampilkan tombol delete.
3. Apakah perlu soft delete untuk nomor surat? Karena jika pakai SoftDeletes, reset urutan tidak perlu dihapus. Jika tidak, maka reset urutan perlu dihapus agar tidak ada nomor ganda.
--}}
@extends('panel.layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            @include('panel.layouts.breadcrumb')

            <div class="row">
                <div class="col-sm-12">
                    @include('panel._message')

                    {{-- FILTER & SORTING --}}
                    <form action="{{ route('referencenumber.list') }}" method="GET" class="row g-2 mb-3">
                        <div class="col-md-2">
                            <select name="letter_type_id" class="form-select">
                                <option value="">-- Semua Jenis Surat --</option>
                                @foreach ($letterTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ request('letter_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->subject }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="year" class="form-control" placeholder="Tahun"
                                value="{{ request('year') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="month" class="form-select">
                                <option value="">-- Bulan --</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="user_id" class="form-select">
                                <option value="">-- Pembuat --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex">
                            <select name="sort_by" class="form-select me-2">
                                <option value="">-- Urutkan --</option>
                                <option value="ref" {{ request('sort_by') == 'ref' ? 'selected' : '' }}>Nomor Surat
                                </option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                    Tanggal</option>
                                <option value="serial_number"
                                    {{ request('sort_by') == 'serial_number' ? 'selected' : '' }}>Nomor Urut</option>
                            </select>
                            <select name="sort_direction" class="form-select me-2">
                                <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>ASC
                                </option>
                                <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>DESC
                                </option>
                            </select>
                            <button class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                    {{-- TABEL NOMOR SURAT --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Daftar Nomor Surat</h5>
                            @if (!empty($PermissionAdd))
                                <a href="{{ route('referencenumber.add') }}" class="btn btn-primary btn-sm">
                                    <i class="feather icon-plus-circle me-1"></i> Add Nomor Surat
                                </a>
                            @endif
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nomor Surat</th>
                                        <th>Tipe Surat</th>
                                        <th>Nomor Urut</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Pembuat</th>
                                        <th>Tanggal Buat</th>
                                        @if (!empty($PermissionDelete))
                                            <th class="text-end">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($getRecord as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + ($getRecord->currentPage() - 1) * $getRecord->perPage() }}
                                            </td>
                                            <td><strong>{{ $item->ref }}</strong></td>
                                            <td>{{ $item->letterType->subject ?? '-' }}</td>
                                            <td>{{ $item->serial_number }}</td>
                                            <td>{{ $item->month }}</td>
                                            <td>{{ $item->year }}</td>
                                            <td>{{ $item->user->name ?? '-' }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                            @if (!empty($PermissionDelete))
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete('{{ $item->ref }}', '{{ $item->id }}')">
                                                        Delete
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Belum ada nomor surat</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $getRecord->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>

                    {{-- TRACKER URUTAN NOMOR SURAT --}}
                    <div class="card mt-4 shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Status Urutan Nomor Surat</h5>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Tipe Surat</th>
                                        <th>Urutan Saat Ini</th>
                                        @if (!empty($PermissionAdd))
                                            <th class="text-end">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trackers as $tracker)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $tracker->letterType->subject ?? '-' }}</td>
                                            <td><span class="badge bg-info">{{ $tracker->current_number }}</span></td>
                                            @if (!empty($PermissionAdd))
                                                <td class="text-end">
                                                    <form id="resetForm-{{ $tracker->letter_type_id }}"
                                                        action="{{ route('referencenumber.reset') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="letter_type_id"
                                                            value="{{ $tracker->letter_type_id }}">
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmReset('{{ $tracker->letterType->subject }}', '{{ $tracker->letter_type_id }}')">
                                                            <i class="ti ti-rotate"></i> Reset Urutan
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada tracker untuk jenis surat</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 Konfirmasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmReset(typeName, id) {
            Swal.fire({
                title: 'Reset Urutan?',
                text: 'Apakah kamu yakin ingin mereset urutan nomor untuk tipe surat "' + typeName + '" menjadi 0?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetForm-' + id).submit();
                }
            });
        }

        function confirmDelete(ref, id) {
            Swal.fire({
                title: 'Hapus Nomor Surat?',
                text: 'Apakah kamu yakin ingin menghapus nomor surat "' + ref + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/referencenumber/delete/' + id;
                }
            });
        }
    </script>
@endsection
